<?php
    
    	error_reporting(E_ALL);

	include_once("../classes/config_class.php");
	
	include_once("../classes/user_class.php");


        if(isset($_POST['login'])){

        	$u= new user();
        	
		if($u->login($_POST['uname'],$_POST['pass'])){
			
			$u->getuser($u->getuid());
			
			$result=array("login"=>"true","uid"=>$u->getuid(),"name"=>$u->getname(),"contact"=>$u->getcontact(),"email"=>$u->getemail());
            		print(json_encode($result));

		}else{
			$result=array("login"=>"false","error"=>$u->report());
            		print(json_encode($result));
		}
	}else{
		$result=array("login"=>"false","error"=>"Nothing received!");
            	print(json_encode($result));
	}
	
?>