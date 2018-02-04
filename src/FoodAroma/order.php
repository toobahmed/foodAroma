<?php
    include_once("inc/header.php");
    include_once("classes/config_class.php");
    include_once("classes/user_class.php");
    include_once("classes/review_class.php");
    include_once("classes/item_class.php");
    include_once("classes/category_class.php");
    include_once("classes/order_class.php");
    authenticate();    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>Order Details</title>
        <link href="style/normalize.css" rel="stylesheet" type="text/css">
        <link href="style/skeleton.css" rel="stylesheet" type="text/css">
        <link href="style/style.css" rel="stylesheet" type="text/css">
            <script language="text/javascript">
                function validate1() {
                var msg=document.forms['approve']['omsg'].value;
                if(msg==null || msg=="") {
                    alert("No Estimate provided. Try again.");
                    return false;
                }
                else {
                    return true;
                }
            }
            function validate2() {
                var msg=document.forms['reject']['omsg'].value;
                if(msg==null || msg=="") {
                    alert("No Reason provided. Try again.");
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
                <?php
                $o =new orders();
                if(isset($_GET["oid"])){
                            $o->getorders($_GET["oid"]);
                ?>
                <a href="javascript:history.go(-1)">Back</a>
                <div class="row">
                    <?php
                            if($o->getcid()==$_SESSION['uid']){
                            $h =new hotel();
                            $h->getuser($o->gethid());
                            ?>
                    <div class="seven columns">
                        
                        <?php
                            echo "<a href='view.php?hid=".$h->getuid()."'><h5>".$h->getname()."</h5></a>";
                            echo $h->getcontact();
                            echo "<br>".$h->getcity()."<br>".$h->getarea();
                            echo "<br><br>Delivery Address: <b>".$o->getaddress()."</b>";
                            echo "<br>Order Total: <b>".$o->gettotal()."</b>";
                            echo "<br>Order Items: <br><b>".$o->getitems()."</b>";
                            ?>
                    </div>
                    <div class="five columns">
                        <?php
                        echo "Status:<b>".$o->getstatus()."</b><br>";
                        echo "Date: <b>".@date('h:i A d M Y',$o->getdate())."</b>";
                        if($o->getstatus()=='accepted'){
                            echo "<br>Delivery within: <b>".$o->getomsg()."</b>";
                        ?>
                        <br>Is this order complete?
                        <form name="order" action="home.php" method="post">
                            <input type="hidden" name="oid" value="<?php echo $o->getoid(); ?>">
                            <input type="hidden" name="hid" value="<?php echo $o->gethid(); ?>">
                            <input type="hidden" name="cid" value="<?php echo $_SESSION['uid']; ?>">
                            <textarea name="msg" placeholder="Post review"></textarea>
                            <input type="submit" value="Yes" name="cmpl_order">
                            <input type="submit" value="No" name="inc_order">
                        </form>
                    <?php
                        }else if($o->getstatus()=='rejected'){
                            if($o->getomsg()!=null || $o->getomsg()!=""){
                            echo "<br>Reason provided: <b>".$o->getomsg()."</b>";
                            }
                        }else if($o->getstatus()=='request'){
                        ?>
                    <form name="del_order" action="home.php" method="post">
                        <input type="hidden" name="oid" value="<?php echo $o->getoid(); ?>">
                        <input type="submit" value="Cancel Order" name="del_order">
                    </form>
                    <?php
                        }
                        ?>
                    </div>
                            <?php
                            }else if($o->gethid()==$_SESSION['uid']){
                                $c=new customer();
                                $c->getuser($o->getcid());
                            ?>
                            <div class="seven columns">
                        <?php
                            echo "<h5>".$c->getname()."</h5>";
                            echo $c->getcontact();
                            echo "<br>".$c->getcity()."<br>".$c->getarea();
                            echo "<br><br>Delivery Address: <b>".$o->getaddress()."</b>";
                            echo "<br>Order Total: <b>".$o->gettotal()."</b>";
                            echo "<br>Order Items: <br><b>".$o->getitems()."</b>";
                            ?>
                    </div>
                    <div class="five columns">
                        <?php
                        echo "Status:<b>".$o->getstatus()."</b><br>";
                        echo "Date: <b>".@date('h:i A d M Y',$o->getdate())."</b>";
                        if($o->getstatus()=='request'){
                        ?>
                        <form name="approve" action="home.php" method="post" onsubmit="return validate1();">
                            <input type="text" name="omsg" placeholder="Estimate Time">
                            <input type="hidden" name="oid" value="<?php echo $o->getoid(); ?>">
                            <input type="submit" value="Approve Order" name="approve">
                        </form>
                        <form name="reject" action="home.php" method="post" onsubmit="return validate2();">
                            <input type="text" name="omsg" placeholder="Reason?">
                            <input type="hidden" name="oid" value="<?php echo $o->getoid(); ?>">
                            <input type="submit" value="Reject Order" name="reject">
                        </form>
                        <?php
                        }else if($o->getstatus()!="rejected"){
                            echo "<br>Delivery estimate: <b>".$o->getomsg()."</b>";
                        }else if($o->getstatus()=="rejected"){
                            echo "<br>Reason: <b>".$o->getomsg()."</b>";
                        }
                        ?>
                    </div>
                            <?php
                            }else{
                                echo "<div class='error'>Stop messing around!</div>";
                            }
                            ?>
                </div>
                <?php
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