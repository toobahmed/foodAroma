<?php
session_start();
?>
<html>
	<head>
		<title>FoodAroma</title>
		<link href="style/normalize.css" rel="stylesheet" type="text/css">
		<link href="style/skeleton.css" rel="stylesheet" type="text/css">
		<link href="style/style.css" rel="stylesheet" type="text/css">
                <script type="text/javascript">
			function validate() {
				var auname=document.forms['login']['auname'].value;
				var apass=document.forms['login']['apass'].value;
				if(auname==null || auname=="") {
				    alert("Username cannot be empty. Try again.");
				    return false;
				}else if(apass==null || apass=="") {
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
		<div class='container'>

                <div class="row" id="loginStrip">
                    <a href='index.php'>Back</a>
                
            </div>
			<div class="row">
				<?php
				include_once("controllers/login.php");
				?>
				<div id="loginbox">
					<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="admin_login" onsubmit="return validate();">
						<h2>Welcome</h2>
						<input type="text" id="auname" name="auname" placeholder="Username">
						<input type="password" id="apass" name="apass" placeholder="Password">
						<input type="submit" value="Login" name="admin_login">
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
