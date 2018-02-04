<?php

error_reporting(E_ALL);

include_once("../classes/config_class.php");
include_once("../classes/user_class.php");

if(isset($_POST['get_cities'])){

    $u= new hotel();
    
    if($u->select_cities()){
            
            
            $result = array();
            //$result[]=array("result"=>"true");
            
            while($u->next_city()){
                $result[]=array("city"=>$u->getcity());
            }
            
            print(json_encode($result));

    }else{
            $result=array(array("result"=>"false","error"=>"No cities available!"));
            print(json_encode($result));
    }
}else{
        $result=array(array("result"=>"false","error"=>"No request received!"));
        print(json_encode($result));
}

?>