<?php

error_reporting(E_ALL);

include_once("../classes/config_class.php");
include_once("../classes/user_class.php");
include_once("../classes/item_class.php");
include_once("../classes/category_class.php");


if(isset($_POST['get_items'])){

    $i= new item();
    
    if($i->select_all($_POST['hid'])){
            
            
            $result = array();
            
            while($i->next()){
                $result[]=array("iid"=>$i->getiid(),"hid"=>$i->gethid(),"special"=>$i->getspecial(),"name"=>$i->getname(),"category"=>$i->getctid(),"price"=>$i->getprice(),"discount"=>$i->getdiscount());
            }
            
            print(json_encode($result));

    }else{
            $result=array(array("result"=>"false","error"=>"No items available!"));
            print(json_encode($result));
    }
}else{
        $result=array(array("result"=>"false","error"=>"No request received!"));
        print(json_encode($result));
}

?>