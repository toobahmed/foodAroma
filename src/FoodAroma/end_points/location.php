<?php

error_reporting(E_ALL);

include_once("../classes/config_class.php");
include_once("../classes/user_class.php");

if(isset($_POST['sel_city'])){

    $u= new hotel();
    
    if($u->search('',$_POST['city'],'')){
            
            $result=array("result"=>"true");
            print(json_encode($result));

    }else{
            $result=array("result"=>"false","error"=>"Not available!");
            print(json_encode($result));
    }
}else{
        $result=array("result"=>"false","error"=>"Nothing received!");
        print(json_encode($result));
}

?>