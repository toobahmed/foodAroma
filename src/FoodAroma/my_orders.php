<?php
    include_once("inc/header.php");
    include_once("classes/config_class.php");
    include_once("classes/user_class.php");
    include_once("classes/order_class.php");
    include_once("classes/item_class.php");
    authenticate();    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>Orders</title>
        <link href="style/normalize.css" rel="stylesheet" type="text/css">
        <link href="style/skeleton.css" rel="stylesheet" type="text/css">
        <link href="style/style.css" rel="stylesheet" type="text/css">
            
    </head>
    <body>
        <div class="container">
            <?php include_once("inc/menu.php"); ?>
            <div id="content">
                <?php include_once("controllers/delete.php"); ?>
                <div class="row">
                <?php
                if(isset($_GET['page'])){
                    $page=$_GET['page'];
                    
                }else{
                $page=0;
                }
                    if(iscustomer()){
                ?>
                        <h5>My Orders:</h5>
                        <form  method="get" name="filter" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <select name="order">
                                
                                <option value="request" <?php if(isset($_GET['filter']) && $_GET['order']=="request" ){ echo "selected"; }?> >Pending</option>
                                <option value="accepted" <?php if(isset($_GET['filter']) && $_GET['order']=="accepted" ){ echo "selected"; }?>>Accepted</option>
                                <option value="rejected" <?php if(isset($_GET['filter']) && $_GET['order']=="rejected" ){ echo "selected"; }?>>Rejected</option>
                                <option value="complete" <?php if(isset($_GET['filter']) && $_GET['order']=="complete" ){ echo "selected"; }?>>Completed</option>
                                <option value="incomplete" <?php if(isset($_GET['filter']) && $_GET['order']=="incomplete" ){ echo "selected"; }?>>Incomplete</option>
                            </select>
                            <input type="submit" name="filter" value="Filter">
                        </form>
                        <br>
                        <?php
                        if(!isset($_GET['filter'])){
                            $filter="request";
                        }
                        else{
                            $filter=$_GET['order'];
                        }
                        $uid = $_SESSION["uid"];
                        $u  = new customer();
                        $u->getuser($uid);
                        $o  = new orders();
                        if($o->select_customer_orders_page($_SESSION["uid"],$filter,$page)){
                        $i= new item();
                        $h= new hotel();
                            ?>
                            <table border="1">
                                <tr>
                                <th>Hotel</th>
                                <th>Items</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th></th>
                               
                                
                                </tr>
                                <?php
                                    while($o->next()){
                                        $h->getuser($o->gethid());
                                ?>
                                <tr>
                                    <td><a href="view.php?hid=<?php echo $h->getuid(); ?>" ><?php echo $h->getname(); ?></a></td>
                                    <td><?php echo $o->getitems(); ?></td>
                                    <td><?php echo $o->gettotal(); ?></td>
                                    <td><?php echo $o->getstatus(); ?></td>
                                    <td><?php echo @date('h:i A d M Y',$o->getdate()); ?></td>
                                    
                                    <td>
                                        <a href="order.php?oid=<?php echo $o->getoid(); ?>">View Order</a>
                                        <?php
                                    if($filter=="request"){
                                    ?>
                                        <form name="del_order" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <input type="hidden" name="oid" value="<?php echo $o->getoid(); ?>">
                                            <input type="submit" value="Delete" name="del_order">
                                        </form>
                                        <?php
                                    }
                                    ?>
                                    </td>
                                    
                                </tr>
                                <?php
                                    }
                                ?>
                            </table>
                    <?php
                    if($page!=0){
                        ?>
                        <a align="centre" href="<?php echo $_SERVER['PHP_SELF'].'?order='.$filter.'&filter=Filter&page='.($page-1); ?>">Previous</a>
                        <?php
                        }if($o->isnext(($page+1))){
                        ?>
                        <a align="centre" href="<?php echo $_SERVER['PHP_SELF'].'?order='.$filter.'&filter=Filter&page='.($page+1); ?>">Next</a>
                        <?php
                        }
                        }
                        else{
                            echo "<div class='error'>No Orders!</div>";
                        }
                    }else{
                ?>
                <h5>My Orders:</h5>
                <form  method="get" name="filter" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <select name="order">
                                
                                <option value="request" <?php if(isset($_GET['filter']) && $_GET['order']=="request" ){ echo "selected"; }?> >Pending</option>
                                <option value="accepted" <?php if(isset($_GET['filter']) && $_GET['order']=="accepted" ){ echo "selected"; }?>>Accepted</option>
                                <option value="rejected" <?php if(isset($_GET['filter']) && $_GET['order']=="rejected" ){ echo "selected"; }?>>Rejected</option>
                                <option value="complete" <?php if(isset($_GET['filter']) && $_GET['order']=="complete" ){ echo "selected"; }?>>Completed</option>
                                <option value="incomplete" <?php if(isset($_GET['filter']) && $_GET['order']=="incomplete" ){ echo "selected"; }?>>Incomplete</option>
                            </select>
                            <input type="submit" name="filter" value="Filter">
                        </form>
                        <?php
                        if(!isset($_GET['filter'])){
                            $filter="request";
                        }
                        else{
                            $filter=$_GET['order'];
                        }
                        
                        $uid = $_SESSION["uid"];
                        $u  = new hotel();
                        $u->getuser($uid);
                        echo "Total Orders Accepted/Completed: <b>".($u->getoaccepted()?$u->getoaccepted():0)."</b>";
                        echo "<br>Total Orders Rejected: <b>".($u->getorejected()?$u->getorejected():0)."</b>";
                        echo "<br>Net Sales: <b>Rs ".($u->getsales()?$u->getsales():0)."</b>";
                        $o  = new orders();
                        if($o->select_hotel_orders_page($_SESSION["uid"],$filter,$page)){
                        $i= new item();
                        $c= new customer();
                            ?>
                <br>
                            <table border="1">
                                <tr>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Total Price</th>
                                <th>Date</th>
                                <th></th>
                                </tr>
                                <?php
                                    while($o->next()){
                                        $c->getuser($o->getcid());
                                ?>
                                <tr>
                                    <td><?php echo $c->getname(); ?></td>
                                    <td><?php echo $o->getitems(); ?></td>
                                    <td><?php echo $o->gettotal(); ?></td>
                                    <td><?php echo @date('h:i A d M Y',$o->getdate()); ?></td>
                                    <td><a href="order.php?oid=<?php echo $o->getoid(); ?>">View Order</a>
                                    </td>
                                </tr>
                                <?php
                                    }
                                ?>
                            </table>
                    <?php
                    if($page!=0){
                        ?>
                        <a align="centre" href="<?php echo $_SERVER['PHP_SELF'].'?order='.$filter.'&filter=Filter&page='.($page-1); ?>">Previous</a>
                        <?php
                        }if($o->isnext(($page+1))){
                        ?>
                        <a align="centre" href="<?php echo $_SERVER['PHP_SELF'].'?order='.$filter.'&filter=Filter&page='.($page+1); ?>">Next</a>
                        <?php
                        }
                        }
                        else{
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
                