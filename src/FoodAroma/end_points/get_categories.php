<?php

error_reporting(E_ALL);

include_once("../classes/config_class.php");
include_once("../classes/user_class.php");
include_once("../classes/category_class.php");


if(isset($_POST['get_categories'])){

    $c= new category();
    
    if($c->select_all($_POST['hid'])){
            
            
            $result = array();
            
            while($c->next()){
                $result[]=array("ctid"=>$c->getctid(),"hid"=>$c->gethid(),"pid"=>$c->getpid(),"name"=>$c->getcname());
            }
            
            print(json_encode($result));

    }else{
            $result=array(array("result"=>"false","error"=>"No categories available!"));
            print(json_encode($result));
    }
}else{
        $result=array(array("result"=>"false","error"=>"No request received!"));
        print(json_encode($result));
}

?>