<?php

include_once("../classes/config_class.php");
include_once("../classes/user_class.php");
include_once("../classes/order_class.php");
include_once("../classes/item_class.php");

        if(isset($_POST['place_order'])){

        	$o= new orders();
                $o->getvalues($_POST);
        	
		if($o->place_order()){
			
			$result=array("order"=>"true");
            		print(json_encode($result));

		}else{
			$result=array("order"=>"false","error"=>$o->report());
            		print(json_encode($result));
		}
	}else{
		$result=array("order"=>"false","error"=>"Nothing received!");
            	print(json_encode($result));
	}
?>