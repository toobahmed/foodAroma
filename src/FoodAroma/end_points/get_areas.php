<?php

error_reporting(E_ALL);

include_once("../classes/config_class.php");
include_once("../classes/user_class.php");

if(isset($_POST['get_areas'])){

    $u= new hotel();
    
    if(($u->select_areas($_POST['city'])) && ($u->getarea()!="")){
            
            
            $result = array();
            //$result[]=array("result"=>"true");
            
            while($u->next_area()){
                $result[]=array("area"=>$u->getarea());
            }
            
            print(json_encode($result));

    }else{
            $result=array(array("result"=>"false","error"=>"No areas available!"));
            print(json_encode($result));
    }
}else{
        $result=array(array("result"=>"false","error"=>"No request received!"));
        print(json_encode($result));
}

?>