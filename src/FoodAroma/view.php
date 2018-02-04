<?php
    if(isset($_POST["place_order"]) && isset($_GET["hid"])){
        echo "<script>location.href='place_order.php?hid=".$_GET['hid']."';</script>";
    }
    include_once("inc/header.php");
    include_once("classes/config_class.php");
    include_once("classes/user_class.php");
    include_once("classes/review_class.php");
    include_once("classes/category_class.php");
    include_once("classes/item_class.php");
    authenticate();
    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>View Hotel</title>
        <link href="style/normalize.css" rel="stylesheet" type="text/css">
        <link href="style/skeleton.css" rel="stylesheet" type="text/css">
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <script type="text/javascript">
            function validate() {
                var quantity=document.getElementsByName("quantity[]");
                var items=document.getElementsByName("iid[]");
                
                var ic=false;
                    for(var i=0; i < items.length ;i++){
                        if(items[i].checked && quantity[i].value!=""){
                            ic=true;
                        }
                    }
                if(!ic){
                    alert("Select items and enter their values!");
                    return false;
                }

                    return true;
            }
        </script>    
    </head>
    <body>
        <div class="container">
            <?php include_once("inc/menu.php"); ?>
            <div id="content">
                <?php
                if(isset($_GET['page'])){
                    $page=$_GET['page'];
                    
                }else{
                $page=0;
                }
                        $h  = new hotel();
                        $hid= $_GET['hid'];
                        if($h->getuser($_GET['hid']) && $h->getrole()==0 && $h->getuid()!=$_SESSION["uid"]){
                            include_once("controllers/delete.php");
                            include_once("controllers/add.php");
                ?>
                <div class="row">
            
                        <h5><b><?php echo $h->getname(); ?></b></h5>
                        <a href="view_reviews.php?hid=<?php echo $h->getuid(); ?>">View Reviews</a>
                        <br>
                        <p><?php echo $h->getdes(); ?>
                        <br><?php echo $h->getcontact(); ?>
                        <br><?php echo $h->getemail(); ?>
                        <br><?php echo $h->getcity(); ?>
                        <br><?php echo $h->getarea(); ?>
                        </p>
                        <br>
                    <h5>Menu:</h5>
                    <?php
                    
                    $i  = new item();
                        if(!isset($_GET['search_cat'])){
                        
                            $hid=$_GET['hid'];
                            $cat="";
                        }else{
                            $hid=$_GET['hid'];
                            $cat=$_GET['category'];
                        }
                    

                    ?>
                    <form name="search" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                    <select name="category" id="category">
                            <?php
                                $c = new category();
                                if($c->select_all_parent_cat($_GET['hid'])){
                                ?>
                                <option value="">-Select Category-</option>
                                <option value="0" <?php if(isset($_GET['category']) && $_GET['category']==0 ){ echo "selected"; }?>>Today's Specials</option>
                                <?php
                                while($c->next()){
                                ?>
                                <option value="<?php echo $c->getctid(); ?>" <?php if(isset($_GET['category']) && $_GET['category']==$c->getctid() ){ echo "selected"; }?>><?php echo $c->getname(); ?></option>
                                <?php
                                    $c1 = new category();
                                    if($c1->select_all_sub_cat($_GET['hid'],$c->getctid())){
                                    while($c1->next()){
                                ?>
                                <option value="<?php echo $c1->getctid(); ?>" ><?php echo $c1->getname(); ?></option>
                                <?php
                                }
                                }
                                }
                                }else{
                                ?>
                                <option value="">-No Categories-</option>
                                <?php
                                }
                                ?>
                        </select>
                    <input type="hidden" name="hid" value="<?php echo $_GET['hid']; ?>">
                    <input type="submit" name="search_cat" value="Search">
                    </form>
                    <form name="in_cart" action="<?php echo $_SERVER['PHP_SELF'].'?hid='.$_GET['hid']; ?>" method="post" onsubmit="return validate();">
                        <?php if(iscustomer()){ ?>
                        <input type="submit" name="place_order" value="Generate Bill">
                        <input type="submit" name="add_cart" value="Add to Cart">
                        <input type="hidden" name="hid" value="<?php echo $_GET['hid']; ?>">
                        <input type="hidden" name="cid" value="<?php echo $_SESSION['uid']; ?>">
                        <?php } ?>
                        <br>
                            <?php
                            if($i->search_category_page($hid,$cat,$page)){
                            ?>
                            <table border="1">
                                <th></th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th><?php if(iscustomer()){ ?>Quantity<?php } ?></th>
                                <?php
                                    while($i->next()){
                                            if(($i->is_in_cart($i->getiid(),$_SESSION['uid']))!=false){
                                            $quantity=$i->get_cart_quantity($i->getiid(),$_SESSION['uid']);
                                            $check=true;
                                            }else{
                                            $check=false;
                                            }
                                            ?>
                                <tr>
                                    <td><?php if(iscustomer()){ ?><input type="checkbox" name="iid[]" value="<?php echo $i->getiid(); ?>" <?php if($check==true){ echo "checked disabled"; } ?> ><?php } ?></td>
                                    <td><?php echo $i->getname(); ?></td>
                                    <td><?php echo $i->getcategory(); ?></td>
                                    <td><?php echo $i->getprice(); ?></td>
                                    <td><b><?php echo $i->getdiscount(); ?>%</b></td>
                                    <td><?php if(iscustomer()){ ?><input type="text" name="quantity[]" value="<?php if($check==true){ echo $quantity; } ?>" <?php if($check==true){ echo "disabled"; } ?>><?php } ?></td>
                                </tr>
                                
                                <?php
                                    }
                                ?>
                            </table>
                        
                            
                    <?php
                    if($page!=0){
                        ?>
                        <a align="centre" href="<?php echo $_SERVER['PHP_SELF'].'?hid='.$hid.'&category='.$cat.'&search_cat=Search&page='.($page-1); ?>">Previous</a>
                        <?php
                        }if($i->isnext(($page+1))){
                        ?>
                        <a align="centre" href="<?php echo $_SERVER['PHP_SELF'].'?hid='.$hid.'&category='.$cat.'&search_cat=Search&page='.($page+1); ?>">Next</a>
                        <?php
                        }
                        }
                        else{
                            echo "<div class='error'>No Items!</div>";
                        }
                    
                        
                    ?>
                </div>
                <?php
                }
                else{
                    echo "<div class='error'>Something Wrong!</div>";
                }
                ?>
            </div>
            <div class="row" id="footer">
                 © 2016
            </div>
        </div>
    </body>
</html>
                