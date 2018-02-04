<?php
    include_once("inc/header.php");
    include_once("classes/config_class.php");
    include_once("classes/user_class.php");
    include_once("classes/review_class.php");
    authenticate();    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>My Reviews</title>
        <link href="style/normalize.css" rel="stylesheet" type="text/css">
        <link href="style/skeleton.css" rel="stylesheet" type="text/css">
        <link href="style/style.css" rel="stylesheet" type="text/css">
            
    </head>
    <body>
        <div class="container">
            <?php include_once("inc/menu.php"); ?>
            <div id="content">
                <?php include_once("controllers/delete.php");
                if(isset($_GET['page'])){
                    $i=$_GET['page'];
                }else{
                $i=0;
                }
                ?>
                <div class="row">
                        <h5>My Reviews:</h5>
                        <br>
                        <?php
                        $uid = $_SESSION["uid"];
                        if(!iscustomer()){
                        $u  = new hotel();
                        $u->getuser($uid);
                        $r  = new review();
                        if($r->select_hotel_reviews_page($u->getuid(),$i)){
                        ?>
                            <table border="1">
                                <tr>
                                <th>Customer Name</th>
                                <th>Review</th>
                                <th>Date Posted</th>
                                <th></th>
                            </tr>
                            <?php
                                while($r->next()){
                                    $u  = new customer();
                                    
                            ?>
                                <tr>
                                    <td><?php if($u->getuser($r->getcid())){ echo $u->getname(); } else{ echo "[deleted]"; } ?></td>
                                    <td><?php echo $r->getmsg(); ?></td>
                                    <td><?php echo @date('h:i A d M Y',$r->getdate()); ?></td>
                                    <td><form name="del_review" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <input type="hidden" name="rid" value="<?php echo $r->getrid(); ?>">
                                            <input type="submit" value="Delete" name="del_review">
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
                            echo "<div class='error'>No Reviews.</div>";
                        }
                        if($i!=0){
                        ?>
                        <a align="centre" href="<?php echo $_SERVER['PHP_SELF'].'?page='.($i-1); ?>">Previous</a>
                        <?php
                        }if($r->isnext(($i+1))){
                        ?>
                        <a align="centre" href="<?php echo $_SERVER['PHP_SELF'].'?page='.($i+1); ?>">Next</a>
                        <?php
                        }
                    }else{
                        $u  = new customer();
                        $u->getuser($uid);
                        $r  = new review();
                        if($r->select_customer_reviews_page($u->getuid(),$i)){
                        ?>
                            <table border="1">
                            <tr>
                                <th>Customer Name</th>
                                <th>Review</th>
                                <th>Date Posted</th>
                                <th></th>
                            </tr>
                            <?php
                                while($r->next()){
                                    $u  = new hotel();
                                    
                            ?>
                                <tr>
                                    <td><a href="view.php?hid=<?php echo $r->gethid(); ?>" ><?php $u->getuser($r->gethid()); echo $u->getname(); ?></a></td>
                                    <td><?php echo $r->getmsg(); ?></td>
                                    <td><?php echo @date('h:i A d M Y',$r->getdate()); ?></td>
                                    <td><form name="del_review" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <input type="hidden" name="rid" value="<?php echo $r->getrid(); ?>">
                                            <input type="submit" value="Delete" name="del_review">
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
                            echo "<div class='error'>No Reviews.</div>";
                        }
                        if($i!=0){
                        ?>
                        <a align="centre" href="<?php echo $_SERVER['PHP_SELF'].'?page='.($i-1); ?>">Previous</a>
                        <?php
                        }if($r->isnext(($i+1))){
                        ?>
                        <a align="centre" href="<?php echo $_SERVER['PHP_SELF'].'?page='.($i+1); ?>">Next</a>
                        <?php
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
                