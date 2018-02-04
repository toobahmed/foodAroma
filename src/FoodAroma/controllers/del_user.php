<?php

include_once("/classes/order_class.php");
include_once("/classes/category_class.php");
    
    if(isset($_POST['delete_user'])){
        if($_POST['role']==0){
            $u = new hotel();
        }else{
            $u = new customer();
        }
    
        $u->getuser($_POST['uid']);
        if($u->delete()){
            echo "<div class='success'>User Deleted Successfully!</div>";
        }
        else {
            echo "<div class='error'>ERROR : This user has pending orders! Cannot Delete Account!".$u->report()."</div>";
        }
    }
    if(isset($_POST['reject_hotel'])){
        $u = new hotel();    
        $u->getuser($_POST['hid']);
        if($u->reject()){
            echo "<div class='success'>Hotel Request Rejected Successfully!</div>";
        }
        else {
            echo "<div class='error'>ERROR :".$u->report()."</div>";
        }
    }
    if(isset($_POST['accept_hotel'])){
        $u = new hotel();    
        $u->getuser($_POST['hid']);
        if($u->accept()){
            echo "<div class='success'>Hotel Request Accepted Successfully!</div>";
        }
        else {
            echo "<div class='error'>ERROR :".$u->report()."</div>";
        }
    }
    
    
?>