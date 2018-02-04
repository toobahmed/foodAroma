<?php
    include_once("classes/config_class.php");
    include_once("classes/user_class.php");
    include_once("classes/item_class.php");
    include_once("classes/order_class.php");
    include_once("classes/review_class.php");

    
    if(isset($_POST['add_item'])){
        $i = new item();
        $i->getvalues($_POST);
        if($i->validate()){
            if($i->insert()){
                echo "<div class='success'>Item Added!</div>";
            }
            else{
                echo "<div class='error'>ERROR : ".$i->report()."</div>";
            }
        }else{
            echo "<div class='error'>".$i->geterr()."</div>";
        }
    }
    else if(isset($_POST['add_ts'])){
        $i = new item();
        $i->getitem($_POST['iid']);
        if($i->makets()){
            echo "<div class='success'>Item Added as Today's Special!</div>";
        }
        else{
            echo "<div class='error'>ERROR : ".$i->report()."</div>";
        }
    }
    else if(isset($_POST['new_cat'])){
        $c = new category();
        $c->getvalues($_POST);
        if($c->validate()){
            if($c->insert()){
                echo "<div class='success'>Category Added!</div>";
            }
            else{
                echo "<div class='error'>ERROR : ".$c->report()."</div>";
            }
        }else{
            echo "<div class='error'>".$c->geterr()."</div>";
        }
    }else if(isset($_POST['update_cat'])){
        $c = new category();
        $c->getcategory($_POST['ctid']);
        $c->getvalues($_POST);
        if($c->validate()){
            if($c->update()){
                echo "<div class='success'>Category Updated!</div>";
            }
            else{
                echo "<div class='error'>ERROR : ".$c->report()."</div>";
            }
        }else{
            echo "<div class='error'>".$c->geterr()."</div>";
        }
    }
    else if(isset($_POST['update_item'])){
        $i = new item();
        $i->getitem($_POST['iid']);
        $i->getvalues($_POST);
        if($i->validate()){
            if($i->update()){
                echo "<a href='javascript:history.go(-2)'>Back</a><div class='success'>Item Updated!</div>";
            }
            else{
                echo "<div class='error'>ERROR : ".$i->report()."</div>";
            }
        }else{
            echo "<div class='error'>".$i->geterr()."</div>";
        }
    }else if(isset($_POST['add_cart'])){
        $o = new orders();
        $o->getvalues($_POST);
        if($o->validate()){
                if($o->add_to_cart()){
                     echo "<div class='success'>Cart Updated!</div>";    
                }
                else{
                    echo "<div class='error'>ERROR : ".$o->report()."</div>";
                }
        }else{
               echo "<div class='error'>".$o->geterr()."</div>";
        }
    }
    else if(isset($_POST['approve'])){
        $o = new orders();
        $o->getorders($_POST["oid"]);
        if($o->accept($_POST["omsg"])){
            echo "<div class='success'>Order Accepted!</div>";
        }
        else{
            echo "<div class='error'>ERROR : ".$o->report()."</div>";
        }
    }
    else if(isset($_POST['cmpl_order'])){
        $o = new orders();
        $o->getorders($_POST["oid"]);
        if($o->complete()){
            if(isset($_POST['msg'])){
                $r = new review();
                $r->getvalues($_POST);
                if($r->post()){
                    echo "<div class='success'>Review Posted!</div>";
                }else{
                    echo "<div class='error'>ERROR : ".$r->report()."</div>";
                }
            }
            echo "<div class='success'>Order Completed!</div>";
        }
        else{
            echo "<div class='error'>ERROR : ".$o->report()."</div>";
        }
    }
    else if(isset($_POST['post_review'])){
        $r  = new review();
        $r->getvalues($_POST);
        if($r->validate()){
            if($r->post()){
                echo "<div class='success'>Review Added!</div>";
            }
            else {
                echo "<div class='error'>ERROR : ".$r->report()."</div>";
            }
        }else{
            echo "<div class='error'>".$r->geterr()."</div>";
        }
    }    
    
?>