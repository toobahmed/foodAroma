<?php
include_once("classes/config_class.php");
include_once("classes/user_class.php");
include_once("classes/order_class.php");

    if(isset($_POST['new_user'])) {
        $u  = new user();
        $u->getvalues($_POST);
        if($u->validate()){
            if($u->insert()){
                echo "<script>location.href='index.php?register=success';</script>";
            }
            else {
                echo "<div class='error'>ERROR : Unfortunately this username exists, try another. ".$u->report()."</div>";
            }
        }else{
            echo "<div class='error'>ERROR: ".$u->geterr().". Try Again.</div>";
        }
    }
    
    else if(isset($_POST['edit_user'])) {
        
        $uid= $_SESSION['uid'];
        if(iscustomer()){
            $u  = new customer();
        }
        else{
            $u  = new hotel();
        }
        $u->getuser($uid);
        if($u->validate()){
            if($u->update($_POST)){
                echo "<div class='success'>Profile Updated Successfully!</div>";
            }
            else {
                echo "<div class='error'>ERROR : ".$u->report()."</div>";
            }
        }
        else{
            echo "<div class='error'>".$u->geterr().". Try Again.</div>";
        }
    }
    
    else if(isset($_POST['delete_user'])) {
        
        $uid=$_SESSION['uid'];
        if(iscustomer()){
            $u  = new customer();
        }
        else{
            $u  = new hotel();
        }
        $u->getuser($uid);
        
        if($u->delete()){
            echo "<script>location.href='index.php?logout=true';</script>";
        }
        else {
            echo "<div class='error'>ERROR : You have pending orders! Cannot Delete Account!".$u->report()."</div>";
        }
    }
    ?>
