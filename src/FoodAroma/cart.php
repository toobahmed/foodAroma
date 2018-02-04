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
        <title>Cart</title>
        <link href="style/normalize.css" rel="stylesheet" type="text/css">
        <link href="style/skeleton.css" rel="stylesheet" type="text/css">
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <script type="text/javascript">
            function validate() {
                var skey=document.forms['search']['skey'].value;
                if(skey==null || skey=="") {
                    alert("Please enter a hotel name.");
                    return false;
                }
                else {
                    return true;
                }
            }
        </script>       
    </head>
    <body>
        <div class="container">
            <?php include_once("inc/menu.php"); ?>
            <div id="content">
                <div class="row">
                <?php
                if(isset($_GET['page'])){
                    $page=$_GET['page'];
                    
                }else{
                $page=0;
                }
                    if(iscustomer()){
                ?>
                        <h5>My Cart:</h5>
                        <form  method="get" name="search" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return validate();">
                            <input type="text" name="hkey" placeholder="Search Hotel">
                            <input type="submit" name="search" value="Search">
                        </form>
                        <br>
                        <?php
                        if(!isset($_GET['search'])){
                            $filter="";
                        }
                        else{
                            $filter=$_GET['hkey'];
                        }
                        $uid = $_SESSION["uid"];
                        $u  = new customer();
                        $u->getuser($uid);
                        $ci  = new item();
                        if($ci->get_cart_hotels($_SESSION["uid"],$filter,$page)){
                        $i= new item();
                        $h= new hotel();
                            ?>
                            <table border="1">
                                <tr>
                                <th>Hotel</th>
                                <th>Items</th>
                                <th></th>
                               
                                
                                </tr>
                                <?php
                                    while($ci->next_hotel()){
                                        $h->getuser($ci->gethid());
                                ?>
                                <tr>
                                    <td><a href="view.php?hid=<?php echo $h->getuid(); ?>" ><?php echo $h->getname(); ?></a></td>
                                    <td><?php echo $ci->getcitems($h->getuid(),$_SESSION['uid']); ?></td>
                                    <td><a href="place_order.php?hid=<?php echo $h->getuid(); ?>" >Place Order</a>
                                    
                                    </td>
                                    
                                </tr>
                                <?php
                                    }
                                ?>
                            </table>
                    <?php
                    if($page!=0){
                        ?>
                        <a align="centre" href="<?php echo $_SERVER['PHP_SELF'].'?hkey='.$filter.'&search=Search&page='.($page-1); ?>">Previous</a>
                        <?php
                        }if($ci->isnext(($page+1))){
                        ?>
                        <a align="centre" href="<?php echo $_SERVER['PHP_SELF'].'?hkey='.$filter.'&search=Search&page='.($page+1); ?>">Next</a>
                        <?php
                        }
                        }
                        else{
                            echo "<div class='error'>Nothing in Cart!</div>";
                        }
                    }else{
                        echo "<div class='error'>Something Wrong Here!</div>";
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
                