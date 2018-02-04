<?php
    include_once("inc/header.php");
    include_once("classes/config_class.php");
    include_once("classes/user_class.php");
    authenticate();    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>Browse Hotels</title>
        <link href="style/normalize.css" rel="stylesheet" type="text/css">
        <link href="style/skeleton.css" rel="stylesheet" type="text/css">
        <link href="style/style.css" rel="stylesheet" type="text/css">
            
    </head>
    <body>
        <div class="container">
            <?php include_once("inc/menu.php"); ?>
            <div id="content">
                <?php
                if(!isset($_GET['search'])){
                            $hkey="";
                            $city="";
                            $area="";
                        }else{
                            $hkey=$_GET['hkey'];
                            $city=$_GET['city'];
                            $area=$_GET['area'];
                        }
                if(isset($_GET['page'])){
                    $i=$_GET['page'];
                    
                }else{
                $i=0;
                }
                ?>
                <div class="row">
                    <h5>Browse Hotels:</h5>
                        <form  method="get" name="search" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <input type="text" name="hkey" placeholder="Search Hotel" value="<?php if(isset($_GET['hkey'])){ echo $_GET['hkey']; } ?>" >
                            <select name="city">
                                <?php
                                $l = new hotel();
                                $l->setuid($_SESSION["uid"]);
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
                                if(isset($_GET['city'])){
                                $l = new hotel();
                                $l->setuid($_SESSION["uid"]);
                                if($l->select_areas($_GET['city'])){
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
                        <?php
                        
                        $h  = new hotel();
                        $h->setuid($_SESSION["uid"]);
                        
                            if($h->search_page($hkey,$city,$area,$i)){
                            ?>
                        <table border='0' class='tbl' cellspacing='0' cellpadding='0' class="text-center">
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Contact</th>
                                <th>City</th>
                                <th>Today's Special</th>
                            </tr>
                            <?php
                            while($h->next()){
                            ?>
                            <tr>
                            <td><a href="view.php?hid=<?php echo $h->getuid(); ?>"><?php echo $h->getname(); ?></a></td>
                            <td><?php echo $h->getdes(); ?></td>
                            <td><?php echo $h->getcontact(); ?></td>
                            <td><?php echo $h->getcity(); ?></td>
                            <td><?php echo $h->getts(); ?></td>
                            </tr>
                            <?php
                                }
                            ?>
                        </table>
                        <?php
                        if($i!=0){
                        ?>
                        <a align="centre" href="<?php echo $_SERVER['PHP_SELF'].'?hkey='.$hkey.'&city='.$city.'&area='.$area.'&search=Search&page='.($i-1); ?>">Previous</a>
                        <?php
                        }if($h->isnext(($i+1))){
                        ?>
                        <a align="centre" href="<?php echo $_SERVER['PHP_SELF'].'?hkey='.$hkey.'&city='.$city.'&area='.$area.'&search=Search&page='.($i+1); ?>">Next</a>
                        <?php
                        }
                            }
                            else{
                                echo "<div class='error'>Nothing found!</div>";
                            }
                        
                        
                    ?>

                </div>
            </div>
            <div class="row" id="footer">
                 © 2016
            </div>
        </div>
    </body>
</html>
                