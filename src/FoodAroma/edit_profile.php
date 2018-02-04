<?php
    include_once("inc/header.php");
    include_once("classes/config_class.php");
    include_once("classes/user_class.php");
    include_once("classes/order_class.php");
    authenticate();    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>Edit Profile</title>
        <link href="style/normalize.css" rel="stylesheet" type="text/css">
        <link href="style/skeleton.css" rel="stylesheet" type="text/css">
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <script type="text/javascript">
            function validate() {
                var pass=document.forms['edit']['pass'].value;
                var name=document.forms['edit']['name'].value;
                var email=document.forms['edit']['email'].value;
                var contact=document.forms['edit']['contact'].value;
                var city=document.forms['edit']['city'].value;
                if(pass==null || pass=="") {
                    alert("Enter Password.");
                    return false;
                }else if(name==null || name=="") {
                    alert("Name cannot be empty. Try again.");
                    return false;
                }else if(email==null || email=="") {
                    alert("Email cannot be empty. Try again.");
                    return false;
                }else if(contact==null || contact=="") {
                    alert("Contact cannot be empty. Try again.");
                    return false;
                }else if(city==null || city=="") {
                    alert("City cannot be empty. Try again.");
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
            <?php include_once("inc/menu.php"); ?>
            <div id="content">
                <div class="row">
                    <h5>Edit Profile:</h5>
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
                    ?>
                    <form name="edit" action="profile.php" method="post" onsubmit="return validate();">
                    <div class="seven columns">                    
                        
                            <label for="pass">Password:</label>
                            <input required type="password" id="pass" name="pass">
                            <br>
                            <label for="name">Name:</label>
                            <input required type="text" id="name" name="name" value="<?php echo $u->getname(); ?>">
                            <br>
                            <?php if(!iscustomer()){
                            ?>
                            <label for="des">Description:</label>
                            <textarea id="des" name="des"><?php echo $u->getdes(); ?></textarea>
                            <?php
                            }
                            ?>
                            
                    
                    </div>
                    <div class="five columns">
                        <label for="contact">Contact:</label>
                        <input required type="text" name="contact" id="contact" value="<?php echo $u->getcontact(); ?>">
                        <br>
                        <label for="email">Email:</label>
                        <input required type="email" name="email" id="email" value="<?php echo $u->getemail(); ?>">
                        <br>
                        <label for="city">City:</label>
                        <input type="text" required name="city" id="city" value="<?php echo $u->getcity(); ?>" >
                        <label for="area">Area:</label>
                        <input type="text" name="area" id="area" value="<?php echo $u->getarea(); ?>" >
                    </div>
                        <input type="hidden" name="uid" value="<?php echo $u->getuid(); ?>">
                        <input type="hidden" name="uname" value="<?php echo $u->getuname(); ?>">
                        <input type="hidden" name="role" value="<?php echo $u->getrole(); ?>">
                        <input type="submit" value="Update" name="edit_user">                            
                        <input type="Submit" name="delete_user" value="Delete Account">
                    </form>
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
                