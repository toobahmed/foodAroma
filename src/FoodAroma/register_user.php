<!DOCTYPE html>
<html>
    <head>
        <title>Register</title>
        <link href="style/normalize.css" rel="stylesheet" type="text/css">
        <link href="style/skeleton.css" rel="stylesheet" type="text/css">
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <script type="text/javascript">
            function validate() {
                var uname=document.forms['new']['uname'].value;
                var upass=document.forms['new']['upass'].value;
                var name=document.forms['new']['name'].value;
                var email=document.forms['new']['email'].value;
                var contact=document.forms['new']['contact'].value;
                var role=document.forms['new']['role'].value;
                var city=document.forms['new']['city'].value;
                if(uname==null || uname=="") {
                    alert("Enter username.");
                    return false;
                }else if(pass==null || pass=="") {
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
                }else if(role==null || role=="") {
                    alert("Role cannot be empty. Try again.");
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
                <div id="loginStrip">
                    <a href='index.php'>Login</a>
                </div>
                <a href="index.php">
                    <h1>FoodAroma</h1>
                    <h2>Online Food Ordering System</h2> 
                </a>
            </div>
            <div class="row" id="content">
                <div class="row">
                    <h2>Create Account</h2>
                    <?php include_once("controllers/user.php");
                    ?>
                </div>
                <div class="row">
                    <form name="new_user" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validate();">
                        <div class="five columns">
                            
                            <label for="name">Name:</label>
                            <input required type="text" id="name" name="name">
                            <br>
                            <label for="contact">Contact:</label>
                            <input required type="text" name="contact" id="contact">
                            <br>
                            <label for="email">Email:</label>
                            <input required type="email" name="email" id="email">
                            <br>
                            <label for="city">City:</label>
                            <input type="text" required name="city" id="city">
                            <label for="area">Area:</label>
                            <input type="text" name="area" id="area" >
                        </div>
                        <div class="seven columns">
                            
                            <label for="uname">User Name:</label>
                            <input required type="text" id="uname" name="uname">
                            <br>
                            <label for="pass">Password:</label>
                            <input required type="password" id="pass" name="pass">
                            <br>
                            <label for="role">Role:</label>
                            <input type="radio" id="role" name="role" value="0">Hotel 
                            <input type="radio" id="role" name="role" value="1">Customer
                            
                            <br>
                            <br>
                            <input type="submit" value="Submit" name="new_user">
                            <input type="reset" value="Clear">
                        </div>
                    </form>
                </div>
            </div>
            <div class="row" id="footer">
                © 2016
            </div>
        </div>
    </body>
</html>
