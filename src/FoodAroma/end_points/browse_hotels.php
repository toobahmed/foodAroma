<?php

error_reporting(E_ALL);

include_once("../classes/config_class.php");
include_once("../classes/user_class.php");

if(isset($_POST['get_hotels'])){

    $u= new hotel();
    
    if($u->search('',$_POST['city'],$_POST['area'])){
            
            
            $result = array();
            //$result[]=array("result"=>"true");
            
            while($u->next()){
                $result[]=array("hid"=>$u->getuid(),"name"=>$u->getname(),"des"=>$u->getdes(),"contact"=>$u->getcontact(),"email"=>$u->getemail());
            }
            
            print(json_encode($result));

    }else{
            $result=array(array("result"=>"false","error"=>"No hotels available!"));
            print(json_encode($result));
    }
}else{
        $result=array(array("result"=>"false","error"=>"No request received!"));
        print(json_encode($result));
}

?>