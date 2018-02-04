<?php

error_reporting(E_ALL);

include_once("../classes/config_class.php");
include_once("../classes/review_class.php");
include_once("../classes/user_class.php");


if(isset($_POST['get_reviews'])){

    $r= new review();
    
    if($r->select_hotel_reviews($_POST['hid'])){
            
            
            $result = array();
            
            while($r->next()){
            
                $u = new customer();
                $u->getuser($r->getcid());
                $result[]=array("rid"=>$r->getrid(),"hid"=>$r->gethid(),"date"=>$r->getdate(),"msg"=>$r->getmsg(),"uname"=>$u->getname());
            }
            
            print(json_encode($result));

    }else{
            $result=array(array("result"=>"false","error"=>"No reviews available!"));
            print(json_encode($result));
    }
}else{
        $result=array(array("result"=>"false","error"=>"No request received!"));
        print(json_encode($result));
}

?>