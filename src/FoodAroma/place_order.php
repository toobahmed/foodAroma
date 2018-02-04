<?php
    include_once("inc/header.php");
    include_once("classes/config_class.php");
    include_once("classes/user_class.php");
    include_once("classes/review_class.php");
    include_once("classes/item_class.php");
    include_once("classes/category_class.php");
    authenticate();    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>Place Order</title>
        <link href="style/normalize.css" rel="stylesheet" type="text/css">
        <link href="style/skeleton.css" rel="stylesheet" type="text/css">
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <script type="text/javascript">
            var s;
            function validate() {
                var address=document.forms['place_order']['address'];
                if(s=="Place Order" && (address==null || address=="")) {
                    alert("Address not provided. Try again.");
                    return false;
                }
                else {
                    return true;
                }
            }
            function checkAll(ele){
                var checkboxes=document.getElementsByName('iid[]');
                    for( var i=0; i < checkboxes.length ; i++)
                    {
                            checkboxes[i].checked=ele.checked;
                    }
            }
            function source(ele){
                s=ele;
            }
        </script>    
    </head>
    <body>
        <div class="container">
            <?php include_once("inc/menu.php"); ?>
            <div id="content">
                <?php
                $h  = new hotel();
                if(isset($_GET["hid"]) && $_GET["hid"]!=$_SESSION['uid'] && $h->getuser($_GET["hid"]) && iscustomer()){
                $total=0;
                $i  = new item();
                $h = new hotel();
                $h->getuser($_GET["hid"]);
                if(isset($_GET['updated'])){
                    echo "<div class='success'>Cart Updated!</div>";
                }
                include("controllers/order.php");
                if($i->get_cart_items($_GET["hid"],$_SESSION['uid'])){
                ?>
                <h5>Cart at <?php echo $h->getname(); ?>:</h5>
                <form name="place_order" action="<?php echo $_SERVER['PHP_SELF'].'?hid='.$_GET['hid']; ?>" method="post" onsubmit="return validate();">
                <div class="row">
                    
                    <div class="eight columns">
                    
                    <input type="submit" name="del_cart" value="Delete from Cart" onclick="source(this)">
                    <input type="submit" name="update_cart" value="Update Cart" onclick="source(this)">
                        <br>
                            <table border="1">
                                <th><input type="checkbox" name="chk" value="" onchange="checkAll(this)"></th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Quantity</th>
                                <?php
                                    while($i->cart_next()){
                                        
                                ?>
                                <tr>
                                    <td><input type="checkbox" name="iid[]" value="<?php echo $i->getiid(); ?>"></td>
                                    <td><?php echo $i->getname(); ?></td>
                                    <td><?php echo $i->getcategory(); ?></td>
                                    <td><?php echo $i->getprice(); ?></td>
                                    <td><b><?php echo $i->getdiscount(); ?>%</b></td>
                                    <td>
                                    <input type="text" name="quantity[]" value="<?php echo $i->getquantity(); ?>"></td>
                                </tr>
                                <?php
                                    $total+=$i->getprice()*(1-(0.01*$i->getdiscount()))*$i->getquantity();;
                                    }
                                ?>
                            </table>
                            <input type="hidden" name="hid" value="<?php echo $h->getuid(); ?>">
                            <input type="hidden" name="cid" value="<?php echo $_SESSION["uid"]; ?>">
                            
                    </div>
                    <div class="four columns">
                        <label for="address">Delivery Adress:</label>
                        <textarea name="address" id="address"></textarea>
                        <h5>Total: Rs <?php echo $total; ?></h5>
                        <input type="submit" name="place_order" value="Place Order" onclick="source(this)">
                        <br>
                    </div>
                </div>
                    </form>
                <?php                            
                        }
                        else{
                            echo "<div class='error'>No Items selected!</div>";
                        }
                    
                        }else{
                        echo "<div class='error'>Something Wrong here!</div>";
                        }
                    ?>
            </div>
            <div class="row" id="footer">
                 © 2016
            </div>
        </div>
    </body>
</html>   