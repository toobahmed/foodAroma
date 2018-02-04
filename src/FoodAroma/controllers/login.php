<?php
    include_once("classes/config_class.php");
    include_once("classes/user_class.php");
    include_once("classes/admin_class.php");
    

	if(isset($_GET["logout"])) {
		unset($_SESSION["login"]);
                unset($_SESSION["uid"]);
                unset($_SESSION["role"]);
		session_destroy();
		echo "<div class='success'>Logout successful.</div>";
	}
	else if(isset($_POST['user_login'])) {
            $u	= new user();
            if($u->login($_POST['uname'],$_POST['pass'])){
                    $_SESSION["login"] = true;
                    $_SESSION["uid"]=$u->getuid();
                    $_SESSION["role"]=$u->getrole();
		    $_SESSION["sel_item"]=array();
		    $_SESSION["quantity"]=array();
                    session_start();
                    if(isset($_POST["hid"])){
                        if($u->getrole()==1){
                            //header("Location:view.php?hid=".$_POST['hid']);
                            echo "<div class='success'>Login successful. Redirecting to Place Order<script>location.href='view.php?hid=".$_POST['hid']."';</script></div>";
                        }
                        else{
                            echo "<div class='error'>ERROR: You need to register as customer to place order!".$u->report()."</div>";
                        }
                    }else{
                        //header("Location:home.php");
                        echo "<div class='success'>Login successful. Redirecting to Home<script>location.href='home.php';</script></div>";
                    }   
            } else {
                    echo "<div class='error'>ERROR: Incorrect Username and Password".$u->report()."</div>";
            }
	}
	else if(isset($_POST['admin_login'])){
		$a	= new admin();
                
		if($a->login($_POST['auname'],$_POST['apass'])){
			$_SESSION["login"] = true;
			$_SESSION["uid"]=$a->getaid();
                        $_SESSION["role"] = "admin";
                        session_start();
			echo "<div class='success'>Login successful. Redirecting to Dashboard<script>location.href='dashboard.php';</script></div>";
		} else {
			echo "<div class='error'>ERROR:".$a->report()."</div>";	
		}
	}
?>