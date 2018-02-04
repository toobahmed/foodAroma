<?php
    include_once("classes/review_class.php");
    include_once("classes/category_class.php");
    
    if(isset($_POST['del_item'])){
        $i = new item();
        $i->getitem($_POST['iid']);
        if($i->delete()){
            echo "<div class='success'>Item Successfully Deleted!</div>";
        }
        else{
            echo "<div class='error'>ERROR : This item is ordered, cannot be deleted! ".$i->report()."</div>";
        }
    }else if(isset($_POST['del_ts'])){
        $i = new item();
        $i->getitem($_POST['iid']);
        if($i->removets()){
            echo "<div class='success'>Item Removed from Today's Special.</div>";
        }
        else{
            echo "<div class='error'>ERROR : ".$i->report()."</div>";
        }
    }
    else if(isset($_POST['reject'])){
        $o = new orders();
        $o->getorders($_POST['oid']);
        if($o->reject($_POST['omsg'])){
            echo "<div class='success'>Order Rejected.</div>";
        }
        else{
            echo "<div class='error'>ERROR : ".$o->report()."</div>";
        }
    }
    else if(isset($_POST['del_order'])){
        $o = new orders();
        $o->getorders($_POST["oid"]);
        if($o->delete()){
            echo "<div class='success'>Order deleted!</div>";
        }
        else{
            echo "<div class='error'>ERROR : ".$o->report()."</div>";
        }
    }
    else if(isset($_POST['delete_cat'])){
        $c = new category();
        $c->getcategory($_POST["ctid"]);
        if($c->delete()){
            echo "<div class='success'>Category deleted!</div>";
        }
        else{
            echo "<div class='error'>ERROR : Category in Use! ".$c->report()."</div>";
        }
    }
    else if(isset($_POST['del_review'])){
        $r  = new review();
        $r->getreview($_POST['rid']);
        if($r->delete()){
            echo "<div class='success'>Review Deleted!</div>";
        }
        else {
            echo "<div class='error'>ERROR : ".$r->report()."</div>";
        }
    }else if(isset($_POST['inc_order'])){
        $o = new orders();
        $o->getorders($_POST["oid"]);
        if($o->delete()){
            if(isset($_POST['msg'])){
                $r = new review();
                $r->getvalues($_POST);
                if($r->post()){
                    echo "<div class='success'>Review Posted!</div>";
                }else{
                    echo "<div class='error'>ERROR : ".$r->report()."</div>";
                }
            }
            echo "<div class='success'>Order deleted!</div>";
        }
        else{
            echo "<div class='error'>ERROR : ".$o->report()."</div>";
        }
    }
    
?>