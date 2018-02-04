<?php
    include_once("inc/header.php");
    include_once("classes/config_class.php");
    include_once("classes/user_class.php");
    include_once("classes/item_class.php");
    include_once("classes/category_class.php");
    authenticate();    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>Menu</title>
        <link href="style/normalize.css" rel="stylesheet" type="text/css">
        <link href="style/skeleton.css" rel="stylesheet" type="text/css">
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <script type="text/javascript">
        function validate() {
                var skey=document.forms['search']['skey'].value;
                if(skey==null || skey=="") {
                    alert("Nothing entered. Try again.");
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
                <?php include_once("controllers/delete.php"); ?>
                <?php include_once("controllers/add.php"); ?>
                <div class="row">
                <?php
                    if(!iscustomer()){
                    if(isset($_GET['page'])){
                        $page=$_GET['page'];
                        
                    }else{
                    $page=0;
                    }
                    if(isset($_GET['search'])){
                        $skey=$_GET['skey'];
                        
                    }else{
                    $skey="all";
                    }
                ?>
                        <h5>Menu:</h5>
                        <form name="search" onsubmit="return validate();" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                        <input type="text" name="skey" placeholder="Search Items">
                            <input type="submit" name="search" value="Search">
                        </form>
                        <button name="add_item" onclick="location='item.php'">Add Item</button>
                        
                        <br>
                        <?php
                        $uid = $_SESSION["uid"];
                        $u  = new hotel();
                        $u->getuser($uid);
                        $i  = new item();
                        if($i->search_page($u->getuid(),$skey,$page)){
                            ?>
                            <table border="1">
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th colspan="3"></th>
                                <?php
                                    while($i->next()){
                                        
                                ?>
                                <tr>
                                    <td><?php echo $i->getname(); ?></td>
                                    <td><?php echo $i->getcategory(); ?></td>
                                    <td><?php echo $i->getprice(); ?></td>
                                    <td><b><?php echo $i->getdiscount(); ?>%</b></td>
                                    <td><?php
                                    if($i->getspecial()==0){
                                    ?>
                                        <form name="add_ts" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <input type="hidden" name="iid" value="<?php echo $i->getiid(); ?>">
                                            <input type="submit" value="Add Today's Special" name="add_ts">
                                        </form>
                                        <?php
                                        }else if($i->getspecial()==1){
                                        ?>
                                        <form name="del_ts" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <input type="hidden" name="iid" value="<?php echo $i->getiid(); ?>">
                                            <input type="submit" value="Remove" name="del_ts">
                                        </form>
                                        <?php
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <form name="edit_item" action="item.php" method="post">
                                            <input type="hidden" name="iid" value="<?php echo $i->getiid(); ?>">
                                            <input type="submit" value="Edit" name="edit_item">
                                        </form>
                                    </td>
                                    <td>
                                        <form name="del_item" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                            <input type="hidden" name="iid" value="<?php echo $i->getiid(); ?>">
                                            <input type="submit" value="Delete" name="del_item">
                                        </form>
                                    </td>
                                </tr>
                                <?php
                                    }
                                ?>
                            </table>
                    <?php
                        if($page!=0){
                        ?>
                        <a align="centre" href="<?php echo $_SERVER['PHP_SELF'].'?skey='.$skey.'&search=Search&page='.($page-1); ?>">Previous</a>
                        <?php
                        }if($i->isnext(($page+1))){
                        ?>
                        <a align="centre" href="<?php echo $_SERVER['PHP_SELF'].'?skey='.$skey.'&search=Search&page='.($page+1); ?>">Next</a>
                        <?php
                        }
                        }
                        else{
                            echo "<div class='error'>No Items!</div>";
                        }
                    }else{
                        echo "<div class='error'>Something Wrong!</div>";
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
                