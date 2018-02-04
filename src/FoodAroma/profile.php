<?php
    include_once("inc/header.php");
    authenticate();    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>Profile</title>
        <link href="style/normalize.css" rel="stylesheet" type="text/css">
        <link href="style/skeleton.css" rel="stylesheet" type="text/css">
        <link href="style/style.css" rel="stylesheet" type="text/css">
            
    </head>
    <body>
        <div class="container">
            <?php include_once("inc/menu.php"); ?>
            <div id="content">
                <?php include_once("controllers/user.php"); ?>
                <div class="row">
                    <button name="edit_profile" onclick="location='edit_profile.php'">Edit Profile</button>
                    <?php
                        if(isset($_SESSION["uid"])){
                            $uid = $_SESSION["uid"];
                            if(!iscustomer()){
                                $u  = new hotel();
                            }
                            else{
                                $u  = new customer();
                            }
                            $u->getuser($uid);
                            echo $u->report();
                    ?>
                    <h5>My Profile:</h5>
                    <p>
                        <h5><?php echo $u->getname(); ?></h5>
                        <?php
                            if(!iscustomer()){
                                echo $u->getdes();
                            }
                        ?>
                        <br><?php echo $u->getcontact(); ?>
                        <br><?php echo $u->getemail(); ?>
                        <br><?php echo $u->getcity(); ?>
                        <br><?php echo $u->getarea(); ?>
                    </p>    
                    <?php
                        }
                        else{
                            echo "<div class='error'>Something wrong with session here!</div>";
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
                