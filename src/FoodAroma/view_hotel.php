<?php
    include_once("inc/header.php");
    include_once("classes/config_class.php");
    include_once("classes/user_class.php");
    include_once("classes/review_class.php");
    include_once("classes/item_class.php");
    include_once("classes/category_class.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>View Hotel</title>
        <link href="style/normalize.css" rel="stylesheet" type="text/css">
        <link href="style/skeleton.css" rel="stylesheet" type="text/css">
        <link href="style/style.css" rel="stylesheet" type="text/css">
            
    </head>
    <body>
        <div class="container">
            <div class="row" id="header">
                <div id="loginStrip">
                    
                    <a href='index.php'>Login</a>
                </div>
                <a href="index.php">
                    <h1>FoodAroma</h1>
                    <h2>Online Food ordering System</h2>
                </a>
            </div>
            <div id="content">
                <div class="row">
                    <div class="seven columns">
                    <a href="javascript:history.go(-1)">Back</a>
                        <br>
                        <?php
                        if(isset($_GET['page'])){
                            $page=$_GET['page'];
                            
                        }else{
                        $page=0;
                        }
                        $h  = new hotel();
                        if(isset($_GET['hid']) && $h->getuser($_GET['hid']) && $h->getrole()==0){
                        ?>
                        
                        <b><?php echo $h->getname(); ?></b>
                        <br>
                        <p><?php echo $h->getdes(); ?>
                        <br><?php echo $h->getcontact(); ?>
                        <br><?php echo $h->getemail(); ?>
                        <br><?php echo $h->getcity(); ?>
                        <br><?php echo $h->getarea(); ?>
                        </p>
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
                        <form name="search_cat" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
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
                        <form name="place_order" method="post" action="index.php">
                            <input type="hidden" name="hid" value="<?php echo $_GET['hid']; ?>">
                            <input type="submit" name="place_order" value="Place Order Now!">
                        </form>
                        <br>
                        <?php
                        if($i->search_category_page($hid,$cat,$page)){
                        ?>
                            
                            <table border="1">
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <?php
                                    while($i->next()){
                                        
                                ?>
                                <tr>
                                    <td><?php echo $i->getname(); ?></td>
                                    <td><?php echo $i->getcategory(); ?></td>
                                    <td><?php echo $i->getprice(); ?></td>
                                    <td><b><?php echo $i->getdiscount(); ?>%</b></td>
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
                    <div class="five columns">
                        <h5>Reviews:</h5>
                        <?php 
                        $r  = new review();
                        if($r->select_hotel_reviews($h->getuid())){
                        ?>

                            <table border="1">
                                <?php
                                    while($r->next()){
                                        $u  = new customer();
                                        
                                ?>
                                <tr>
                                    <td><?php if($u->getuser($r->getcid())){ echo $u->getname();} else{echo "[deleted]";} ?></td>
                                    <td><?php echo $r->getmsg(); ?></td>
                                    <td><?php echo @date('h:i A d M Y',,$r->getdate()); ?></td>
                                </tr>
                                <?php
                                    }
                                ?>
                            </table>
                    <?php                            
                        }
                        else{
                            echo "<div class='error'>No Reviews</div>";
                        }
                        
                    }
                    else{
                        echo "<div class='error'>Something Wrong!</div>";
                    }
                    
                    ?>
                    </div>
                </div>
            </div>
            <div class="row" id="footer">
                 © 2016
            </div>
        </div>
    </body>
</html>
                