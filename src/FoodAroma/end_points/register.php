<?php

include_once("../classes/config_class.php");
include_once("../classes/user_class.php");

        if(isset($_POST['register'])){

        	$u= new user();
                $u->getvalues($_POST);
        	
		if($u->insert()){
			
			$result=array("register"=>"true");
            		print(json_encode($result));

		}else{
			$result=array("register"=>"false","error"=>$u->report());
            		print(json_encode($result));
		}
	}else{
		$result=array("register"=>"false","error"=>"Nothing received!");
            	print(json_encode($result));
	}
?>