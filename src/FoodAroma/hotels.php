<?php
    include_once("inc/header.php");
    include_once("classes/config_class.php");
    include_once("classes/user_class.php");
    //authenticate();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Manage Hotels</title>
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <link href="style/normalize.css" rel="stylesheet" type="text/css">
        <link href="style/skeleton.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
            <script type="text/javascript">
                function validate() {
				var skey=document.forms['search']['skey'].value;
                                var city=document.forms['search']['city'].value;
                                var area=document.forms['search']['area'].value;
				if(skey=="" && city=="" && area=="") {
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
                    <h2 class="text-center">Manage Hotels</h2>
                    <?php
                    if(isset($_GET['search'])){
                    $skey=$_GET['skey'];
                    $city=$_GET['city'];
                    $area=$_GET['area'];
                }
                else{
                    $skey="";
                    $city="";
                    $area="";
                }
                if(isset($_GET['page'])){
                    $page=$_GET['page'];
                    
                }else{
                $page=0;
                }
                ?>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="search" onsubmit="return validate();">
                        <input type="text" name="skey" placeholder="Search Hotel" value="<?php if(isset($_GET['skey'])){ echo $_GET['skey']; } ?>">
                            <select name="city">
                                <?php
                                $l = new hotel();
                                if($l->select_cities()){
                                ?>
                                <option value="">-Select City-</option>
                                <?php
                                while($l->next_city()){
                                ?>
                                <option value="<?php echo $l->getcity(); ?>" <?php if(isset($_GET['city']) && $_GET['city']==$l->getcity() ){ echo "selected"; }?> ><?php echo $l->getcity(); ?></option>
                                <?php
                                }
                                }else{
                                ?>
                                <option value="">-No Cities-</option>
                                <?php
                                }
                                ?>
                            </select>
                            <select name="area">
                                <?php
                                if($city!=""){
                                $l = new hotel();
                                if($l->select_areas($city)){
                                ?>
                                <option value="">-Select Area-</option>
                                <?php
                                while($l->next_area() && $l->getarea()!=""){
                                ?>
                                <option value="<?php echo $l->getarea(); ?>" <?php if(isset($_GET['area']) && $_GET['area']==$l->getarea() ){ echo "selected"; }?> ><?php echo $l->getarea(); ?></option>
                                <?php
                                }
                                }else{
                                ?>
                                <option value="">-No Areas-</option>
                                <?php
                                }
                                }else{
                                ?>
                                <option value="" disabled="disabled">-No Areas-</option>
                                <?php
                                }
                                ?>
                            </select>
                        <input type="submit" name="search" value="Search">
                    </form>
                </div>
                <div class="row">
                <?php
                include_once("controllers/del_user.php");
                include_once("controllers/add.php");
                include_once("controllers/delete.php");
                $h  = new hotel();
                $h->setuid(0);
                
                    if($h->search_page($skey,$city,$area,$page)){
                ?>
                                <table border='0' class='tbl' cellspacing='0' cellpadding='0'>
                                    <tr>
                                        
                                        <th>Name</th>
                                        <th>City</th>
                                        <th>Area</th>
                                        <th>Speciality</th> 
                                        <th>Orders Accepted</th>
                                        <th>Orders Rejected</th>
                                        <th>Net Sales</th>
                                        <th></th>
                
                                    </tr>
                                    <?php
                                                                   
                                while($h->next()){
                                
                                ?>
                                        <tr>
                                            
                                            <td><a href='view_report.php?uid=<?php echo $h->getuid(); ?>'><?php echo $h->getname(); ?></a></td>
                                            <td><?php echo $h->getcity(); ?></td>
                                            <td><?php echo $h->getarea(); ?></td>
                                            <td><?php echo $h->getdes(); ?></td>
                                            <td><?php echo $h->getoaccepted(); ?></td>
                                            <td><?php echo $h->getorejected(); ?></td>
                                            <td><?php echo $h->getsales()?$h->getsales():0; ?></td>
                                            <td class='text-center'>
                                            <form name='delete_user' method='post' action='<?php echo $_SERVER['PHP_SELF']; ?>'>
                                                <input type='hidden' name='uid' value='<?php echo $h->getuid(); ?>'>
                                                <input type='hidden' name='role' value='<?php echo $h->getrole(); ?>'>
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
                        <a align="centre" href="<?php echo $_SERVER['PHP_SELF'].'?skey='.$skey.'&city='.$city.'&area='.$area.'&search=Search&page='.($page-1); ?>">Previous</a>
                        <?php
                        }if($h->isnext(($page+1))){
                        ?>
                        <a align="centre" href="<?php echo $_SERVER['PHP_SELF'].'?skey='.$skey.'&city='.$city.'&area='.$area.'&search=Search&page='.($page+1); ?>">Next</a>
                        <?php
                        }
                        }
                        else{
                            echo "<div class='error'>No such Hotel Found</div>";
                        }
                        ?>
                </div>
            </div>
        </div>
    </body>
</html>