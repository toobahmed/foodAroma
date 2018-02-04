<?php
    include_once("inc/header.php");
    include_once("classes/config_class.php");
    include_once("classes/user_class.php");
    //authenticate();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard</title>
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <link href="style/normalize.css" rel="stylesheet" type="text/css">
        <link href="style/skeleton.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
            <script type="text/javascript">
                function validate() {
				var skey=document.forms['search']['hkey'].value;
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
                        <li><a href='admin_login.php?logout=true'><i class="fa fa-sign-out"></i> Logout</a></li>
                    </ul>
                </div>
                <div class="row">
                    <?php
                if(!isset($_POST['search'])){
                            $hkey="";
                            $city="";
                            $area="";
                        }else{
                            $hkey=$_POST['hkey'];
                            $city=$_POST['city'];
                            $area=$_POST['area'];
                        }
                ?>
                    <h2 class="text-center">Requests</h2>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="search" onsubmit="return validate();">
                        <input type="text" name="hkey" placeholder="Search" value="<?php if(isset($_POST['hkey'])){ echo $_POST['hkey']; } ?>">
                            <select name="city">
                                <?php
                                $l = new hotel();
                                if($l->select_r_cities()){
                                ?>
                                <option value="">-Select City-</option>
                                <?php
                                while($l->next_city()){
                                ?>
                                <option value="<?php echo $l->getcity(); ?>" <?php if(isset($_POST['city']) && $_POST['city']==$l->getcity() ){ echo "selected"; }?>><?php echo $l->getcity(); ?></option>
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
                                if($l->select_r_areas($_POST['city'])){
                                ?>
                                <option value="">-Select Area-</option>
                                <?php
                                while($l->next_area() && $l->getarea()!=""){
                                ?>
                                <option value="<?php echo $l->getarea(); ?>" <?php if(isset($_POST['area']) && $_POST['area']==$l->getarea() ){ echo "selected"; }?> ><?php echo $l->getarea(); ?></option>
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
                $h  = new hotel();
                $h->setuid(0);
                    if($h->search_request($hkey,$city,$area)){
?>
                                <table border='0' class='tbl' cellspacing='0' cellpadding='0'>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>City</th>
                                        <th>Area</th>
                                        <th>Email</th>
                                        <th>Contact</th>
                                        <th colspan="2"></th>
                
                                    </tr>
                <?php
                                    $i=0;                                    
                                while($h->next()){
                                    $i++;
                                ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $h->getname(); ?></td>
                                            <td><?php echo $h->getcity(); ?></td>
                                            <td><?php echo $h->getarea(); ?></td>
                                            <td><?php echo $h->getemail(); ?></td>
                                            <td><?php echo $h->getcontact(); ?></td>
                                            <td class='text-center'>
                                            <form name='reject_hotel' method='post' action='<?php echo $_SERVER['PHP_SELF']; ?>'>
                                                <input type='hidden' name='hid' value='<?php echo $h->getuid(); ?>'>                                             
                                                <input type='submit' name='reject_hotel' value='Reject'>
                                            </form>
                                            </td>
                                            <td class='text-center'>
                                            <form name='accept_hotel' method='post' action='<?php echo $_SERVER['PHP_SELF']; ?>'>
                                                <input type='hidden' name='hid' value='<?php echo $h->getuid(); ?>'>                                             
                                                <input type='submit' name='accept_hotel' value='Accept'>
                                            </form>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                            </table>
                                <?php
        
                        }
                        else{
                            echo "<div class='error'>No Hotels!</div>";
                        }
               ?>
                </div>
            </div>
        </div>
    </body>
</html>