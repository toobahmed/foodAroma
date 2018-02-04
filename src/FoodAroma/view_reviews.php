<?php
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
        <title>View Reviews</title>
        <link href="style/normalize.css" rel="stylesheet" type="text/css">
        <link href="style/skeleton.css" rel="stylesheet" type="text/css">
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <script type="text/javascript">
            function rvalidate() {
                var msg=document.forms['post_review']['msg'].value;
                if(msg==null || msg=="") {
                    alert("Empty review. Try again.");
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
                
                
                        $h  = new hotel();
                        $hid= $_GET['hid'];
                        if($h->getuser($_GET['hid']) && $h->getrole()==0 && $h->getuid()!=$_SESSION["uid"]){
                        include_once("controllers/delete.php");
                        include_once("controllers/add.php");
                ?>
                <div class="row">
                        <h5><b><a href="view.php?hid=<?php echo $h->getuid(); ?>" ><?php echo $h->getname(); ?></a></b></h5>
                        <?php
                            if(iscustomer()){
                            
                        ?>
                        <form name="post_review" action="<?php echo $_SERVER['PHP_SELF'].'?hid='.$hid; ?>" method="post" onsubmit="return rvalidate();">
                            <input type="hidden" name="hid" value="<?php echo $h->getuid(); ?>">
                            <input type="hidden" name="cid" value="<?php echo $_SESSION['uid']; ?>">
                            <label for="des">Review:</label>
                            <br>
                            <textarea id="msg" name="msg"></textarea>
                            <input type="submit" name="post_review" value="Post">
                        </form>
                        <?php
                        }
                        ?>
                </div>
                        <h5>Reviews:</h5>
                        <?php
                        if(isset($_GET['page'])){
                                $i=$_GET['page'];
                                
                            }else{
                            $i=0;
                            }
                            $n=$i+1;
                            $p=$i-1;
                            if($p<0){ $p=0; }
                            
                        $r  = new review();
                        if($r->select_hotel_reviews_page($h->getuid(),$i)){
                        ?>
                            <table border="1">
                            <tr>
                                <th>Customer Name</th>
                                <th>Review</th>
                                <th>Date Posted</th>
                            
                            </tr>
                                <?php
                                    while($r->next()){
                                        $c  = new customer();
                                        
                                ?>
                                <tr>
                                    <td><?php if($c->getuser($r->getcid())){
                                                echo $c->getname();
                                            } else{
                                                echo "[deleted]";
                                            } ?>
                                    </td>
                                    <td><?php echo $r->getmsg(); ?></td>
                                    <td><?php echo @date('h:i A d M Y',$r->getdate()); ?></td>
                                    
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
                        if($i!=0){
                        ?>
                        <a align="centre" href="<?php echo $_SERVER['PHP_SELF'].'?hid='.$hid.'&page='.$p; ?>">Previous</a>
                        <?php
                        }if($r->isnext($h->getuid(),$n)){
                        ?>
                        <a align="centre" href="<?php echo $_SERVER['PHP_SELF'].'?hid='.$hid.'&page='.$n; ?>">Next</a>
                        <?php
                        }
                        
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
                