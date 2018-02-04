<?php
    include_once("inc/header.php");
    include_once("classes/config_class.php");
    include_once("classes/user_class.php");
    include_once("classes/item_class.php");
    include_once("classes/category_class.php");
    include_once("classes/order_class.php");
    authenticate();    
?>
<!DOCTYPE html>
<html>
    <head>
        
        <title>Home</title>
        <link href="style/normalize.css" rel="stylesheet" type="text/css">
        <link href="style/skeleton.css" rel="stylesheet" type="text/css">
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <meta charset="ISO-8859-1" />
        <meta http-equiv="refresh" content="300" />
            
    </head>
    <body>
        <div class="container">
            <?php
                
                if(!iscustomer()){
                    $u = new hotel();
                    $u->getuser($_SESSION['uid']);
                    if($u->getstatus()=="accepted"){
                        include_once("inc/menu.php");
                    }else{
                        ?>
                        <div class="row" id="header">
                        <div id="loginStrip">
                            <a href='index.php?logout=true'>Logout</a>
                        </div>
                        <a href="home.php">
                            <h1>FoodAroma</h1>
                            <h2>Online Food ordering System</h2>
                        </a>
                        </div>
    <?php
                    }
                }else{
                    include_once("inc/menu.php");
                }
            ?>
            <div id="content">
            <?php   include_once("controllers/delete.php");
                    include_once("controllers/add.php");
                    
                    if(isset($_POST['order'])){
                        if($_POST['order']=="done"){
                            echo "<div class='success'>Order Placed!</div>";
                        }else if($_POST['order']=="error"){
                            echo "<div class='error'>Could not process your request.</div>";
                        }
                    }
                    $o= new orders();
                    $o->refresh_order();
            ?>
                <div class="row">
                
                <?php
                    if(!iscustomer()){
                        $u = new hotel();
                        $u->getuser($_SESSION['uid']);
                        if($u->getstatus()=="request"){
                            echo "<h5>Your Request Has Been Sent!</h5>";
                        }else if($u->getstatus()=="rejected"){
                            echo "<h5>Your Request Has Been Rejected!</h5>";
                        }else if($u->getstatus()=="accepted"){
                            echo "<h5>Welcome ".$u->getname()." to FoodAroma!</h5>";
                            
                        if(!isset($_POST['filter'])){
                            $filter="request";
                        }
                        else if($_POST['order']=="accepted" || $_POST['order']=="request" || $_POST['order']=="rejected"){
                            $filter=$_POST['order'];
                        }
                    ?>
                    <h5>Today's Orders:</h5>
                    <form  method="post" name="filter" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <select name="order">
                            <option value="request" <?php if($filter=="request"){ echo "selected"; } ?> >Pending</option>
                            <option value="accepted" <?php if($filter=="accepted"){ echo "selected"; } ?> >Accepted</option>
                            <option value="rejected" <?php if($filter=="rejected"){ echo "selected"; } ?> >Rejected</option>
                        </select>
                        <input type="submit" name="filter" value="Filter">
                    </form>
                    <?php
                    
                        
                        
                        
                        if($o->select_hotel_orders_today($_SESSION["uid"],$filter)){
                            $c= new customer(); 
                        ?>
                            <table border='0' class='tbl' cellspacing='0' cellpadding='0' class="text-center">
                            <tr>
                                <th>Customer Name</th>
                                <th>Delivery Address</th>
                                <th>Items</th>
                                <th>Order Total</th>
                                <th></th>
                            </tr>
                            <?php
                            while($o->next()){
                                $c->getuser($o->getcid());
                            ?>
                                <tr>
                                    <td><?php echo $c->getname(); ?></td>
                                    <td><?php echo $o->getaddress(); ?></td>
                                    <td><?php echo $o->getitems(); ?></td>
                                    <td><?php echo $o->gettotal(); ?></td>
                                    <td><a href="order.php?oid=<?php echo $o->getoid(); ?>">View Order</a>
                                    </td>
                                </tr>  
                            <?php
                                }
                            ?>
                            </table>
                        <?php                                
                            }else{
                                echo "<div class='error'>No Orders!</div>";
                            }
                        ?>
                        
                        <button name="add_ts" onclick="location='my_menu.php'">Add Today's Special</button>
                        <br>
                        <?php
                        $uid = $_SESSION["uid"];
                        $u  = new hotel();
                        $u->getuser($uid);
                        $i  = new item();
                        if($i->select_ts($u->getuid())){
                            ?><h5>Todays's Special:</h5>
                            <table border="1">
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th colspan="2"></th>
                                <?php
                                    while($i->next()){
                                        
                                ?>
                                <tr>
                                    <td><?php echo $i->getname(); ?></td>
                                    <td><?php echo $i->getcategory(); ?></td>
                                    <td><?php echo $i->getprice(); ?></td>
                                    <td><b><?php echo $i->getdiscount(); ?>%</b></td>
                                    <td>
                                        <form name="del_ts" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <input type="hidden" name="iid" value="<?php echo $i->getiid(); ?>">
                                            <input type="submit" value="Remove" name="del_ts">
                                        </form>
                                    </td>
                                </tr>
                                <?php
                                    }
                                ?>
                            </table>
                    <?php                            
                        }
                        else{
                            echo "<div class='error'>No Items!</div>";
                        }
                    }
                    }else{
                        if(!isset($_POST['filter'])){
                            $filter="request";
                        }
                        else if($_POST['order']=="accepted" || $_POST['order']=="request" || $_POST['order']=="rejected"){
                            $filter=$_POST['order'];
                        }
                        
                    ?><h5>Today's Orders:</h5>
                    <form  method="post" name="filter" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <select name="order">
                            <option value="request" <?php if($filter=="request"){ echo "selected"; } ?> >Pending</option>
                            <option value="accepted" <?php if($filter=="accepted"){ echo "selected"; } ?> >Accepted</option>
                            <option value="rejected" <?php if($filter=="rejected"){ echo "selected"; } ?> >Rejected</option>
                        </select>
                        <input type="submit" name="filter" value="Filter">
                    </form>
                
                    <?php
                    
                    if($o->select_customer_orders_today($_SESSION["uid"],$filter)){
                        $h= new hotel();
                    ?>
                        <table border='0' class='tbl' cellspacing='0' cellpadding='0' class="text-center">
                        <tr>
                            <th>Hotel Name</th>
                            <th>Delivery Address</th>
                            <th>Items</th>
                            <th>Order Total</th>
                            <th></th>
                        </tr>
                        <?php
                        while($o->next()){
                            $h->getuser($o->gethid());
                        ?>
                            <tr>
                                <td><a href="view.php?hid=<?php echo $h->getuid(); ?>" ><?php echo $h->getname(); ?></a></td>
                                <td><?php echo $o->getaddress(); ?></td>
                                <td><?php echo $o->getitems(); ?></td>
                                <td><?php echo $o->gettotal(); ?></td>
                                <td><a href="order.php?oid=<?php echo $o->getoid(); ?>">View Order</a></td>
                            </tr>
                        <?php
                            }
                        ?>
                        </table>
                    <?php
                        }else{
                            echo "<div class='error'>No Orders!</div>";
                        }
                    }
                ?>
                </div>
            </div>
            <div class="row" id="footer">
                 © 2016
            </div>
        </div>
    </body>
</html>
                