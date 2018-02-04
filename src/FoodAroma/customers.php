<?php
    include_once("inc/header.php");
    include_once("classes/config_class.php");
    include_once("classes/user_class.php");
    //authenticate();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Manage Customers</title>
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <link href="style/normalize.css" rel="stylesheet" type="text/css">
        <link href="style/skeleton.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
            <script type="text/javascript">
                function validate() {
				var skey=document.forms['search']['skey'].value;
				
				if(skey==null || skey=="") {
				    alert("No value entered. Try again.");
				    return false;
				}
				else {
				    return true;
				}
			}
            </script>
    </head>
    <body>
        <div class="container">
            <div id="content">
                <div class="row">
                    <ul id="nav">
                        <li><a href='dashboard.php'><i class="fa fa-dashboard"></i> Dashboard</a></li>
                        <li><a href="#"><i class="fa fa-cogs"></i> Manage</a>
                            <ul>
                                <li><a href='hotels.php'>Manage Hotels</a></li>
                                <li><a href='customers.php'>Manage Customers</a></li>
                                <li><a href='p_category.php'>Manage Categories</a></li>
                            </ul>
                        </li>
                        <li><a href='admin_login.php?logout=true'>Logout</a></li>
                    </ul>
                </div>
                <div class="row">
                <h2 class="text-center">Manage Customers</h2>
                <?php
                include_once("controllers/del_user.php");
                $c  = new customer();
                $c->setuid(0);
                if(isset($_GET['search'])){
                    $skey=$_GET['skey'];
                }
                else{
                    $skey="";
                }
                if(isset($_GET['page'])){
                    $page=$_GET['page'];
                    
                }else{
                $page=0;
                }
                ?>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="search" onsubmit="return validate();">
                        <input type="text" name="skey" placeholder="Name,City or Area" value="<?php if(isset($_GET['skey'])){ echo $_GET['skey']; } ?>">
                        <input type="submit" name="search" value="Search">
                    </form>
                <?php
                    if($c->search_page($skey,$page)){
                ?>
                                <table border='0' class='tbl' cellspacing='0' cellpadding='0'>
                                    <tr>
                                        <th>Name</th>
                                        <th>Contact</th>
                                        <th>Email</th>
                                        <th>City</th>
                                        <th>Area</th> 
                                        <th></th>
                                    </tr>
                                    <?php
                                while($c->next()){
                                ?>
                                        <tr>
                                            <td><a href="view_report.php?uid=<?php echo $c->getuid(); ?>" ><?php echo $c->getname(); ?></a></td>
                                            <td><?php echo $c->getcontact(); ?></td>
                                            <td><?php echo $c->getemail(); ?></td>
                                            <td><?php echo $c->getcity(); ?></td>
                                            <td><?php echo $c->getarea(); ?></td>
                                            <td class='text-center'>
                                            <form name='delete_user' method='post' action='<?php echo $_SERVER['PHP_SELF']; ?>' >
                                                <input type='hidden' name='uid' value='<?php echo $c->getuid(); ?>'>
                                                <input type='hidden' name='role' value='<?php echo $c->getrole(); ?>'>
                                                <input type='Submit' name='delete_user' value='Delete'>
                                            </form>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </table>
                                <?php
                                if($page!=0){
                        ?>
                        <a align="centre" href="<?php echo $_SERVER['PHP_SELF'].'?skey='.$skey.'&search=Search&page='.($page-1); ?>">Previous</a>
                        <?php
                        }if($c->isnext(($page+1))){
                        ?>
                        <a align="centre" href="<?php echo $_SERVER['PHP_SELF'].'?skey='.$skey.'&search=Search&page='.($page+1); ?>">Next</a>
                        <?php
                        }
                        }
                        else{
                            echo "<div class='error'>No such Customer Found</div>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>