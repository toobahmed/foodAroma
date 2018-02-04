<?php

include_once("../classes/config_class.php");
include_once("../classes/user_class.php");
include_once("../classes/review_class.php");

        if(isset($_POST['post_review'])){

        	$r= new review();
                $r->getvalues($_POST);
        	
		if($r->post()){
			
			$result=array("result"=>"true");
            		print(json_encode($result));

		}else{
			$result=array("result"=>"false","error"=>$r->report());
            		print(json_encode($result));
		}
	}else{
		$result=array("result"=>"false","error"=>"Nothing received!");
            	print(json_encode($result));
	}
?>