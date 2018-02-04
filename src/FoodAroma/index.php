<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Food Aroma</title>
        <link href="style/normalize.css" rel="stylesheet" type="text/css">
        <link href="style/skeleton.css" rel="stylesheet" type="text/css">
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <script type="text/javascript">
            function validate() {
                var uname=document.forms['login']['uname'].value;
                var pass=document.forms['login']['pass'].value;
                if(uname==null || uname=="") {
                    alert("Username cannot be empty. Try again.");
                    return false;
                }else if(pass==null || pass=="") {
                    alert("Password cannot be empty. Try again.");
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
            <div class="row" id="header">
                <div class="row" id="loginStrip">
                    <a href='admin_login.php'>Admin Login</a>
                    <a href='register_user.php'>Register</a>
                </div>
                <a href="index.php">
                <h1>FoodAroma</h1>
                <h2>Online Food Ordering System</h2>                    
                </a>
            </div>
            <div class="row" id="content">
                <div class="eight columns">
                        <h4>About Us</h4>
                        <p>
                            Online Food Ordering System
                            <br>
                            <a href="browse_hotels.php">Browse</a>
                        </p>
                    </div>
                    <div class="four columns">
                        <h4>Login</h4>
                        <?php   include_once("controllers/login.php");
                        if(isset($_GET['register']) && $_GET['register']=="success"){
                            echo "<div class='success'>Registration Successful! </div>";
                        }
                        ?>
                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="login" onsubmit="return validate();">        
                            <?php
                            if(isset($_POST["hid"])){
                            ?>
                            <div class="success">Please Login...</div>
                            <input type="hidden" name="hid" value="<?php echo $_POST['hid']; ?>">
                            <?php
                            }
                            ?>
                            <input type="text" id="uname" name="uname" placeholder="Username">
                            <br>

                            <input type="password" id="pass" name="pass" placeholder="Password">
                            <br>
                            <input type="submit" value="Submit" name="user_login">
                            <input type="reset" value="Clear">                                    
                        </form>
                    </div>
            </div>
            <div class="row" id="footer">
                © 2016
            </div>
        </div>
    </body>
</html>