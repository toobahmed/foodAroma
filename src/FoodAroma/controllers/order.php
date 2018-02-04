<?php
include_once("classes/config_class.php");
include_once("classes/item_class.php");
include_once("classes/order_class.php");
if(isset($_POST['place_order'])){
        $o = new orders();
        $o->getvalues($_POST);
        if($o->validate()){
                
                        if($o->place_order()){
                                echo "<script>location.href='home.php?order=done';</script>";
                        }
                        else{
                                echo "<script>location.href='home.php?order=error';</script>";
                        }
        }else{
                echo "<div class='error'>ERROR: ".$o->geterr()."</div>";
        }
    }
    else if(isset($_POST['update_cart'])){
        $o = new orders();
        $o->getvalues($_POST);
        if($o->validate()){
                if($o->update_cart()){
                     echo "<div class='success'>Cart Updated!</div>"; 
                }
                else{
                    echo "<div class='error'>ERROR: ".$o->report()."</div>";
                }
        }else{
               echo "<div class='error'>".$o->geterr()."</div>";
        }
    }
    else if(isset($_POST['del_cart'])){
        $o = new orders();
        $o->getvalues($_POST);
        
                if($o->delete_from_cart()){
                     echo "<div class='success'>Cart Updated!</div>"; 
                }
                else{
                    echo "<div class='error'>ERROR: ".$o->report()."</div>";
                }
        
    }
?>