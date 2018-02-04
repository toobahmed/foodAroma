<?php
    include_once("inc/header.php");
    include_once("classes/config_class.php");
    include_once("classes/user_class.php");
    include_once("classes/review_class.php");
    //authenticate();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>View Report</title>
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <link href="style/normalize.css" rel="stylesheet" type="text/css">
        <link href="style/skeleton.css" rel="stylesheet" type="text/css">
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    </head>
    <body>
        <div class="container">
            <div id="content">
                <div class="row">
                    <ul id="nav">
                       <li><a href='dashboard.php'><i class="fa fa-dashboard"></i> Dashboard</a></li>
                        <li><a href="#"><i class="fa fa-cogs"></i> Manage</a>
                            <ul>
                                <li><a href='hotels.php'>Manage Hotels</a></li>
                                <li><a href='customers.php'>Manage Customers</a></li>
                                <li><a href='p_category.php'>Manage Categories</a></li>
                            </ul>
                        </li>
                        <li><a href='index.php?logout=true'>Logout</a></li>
                    </ul>
                </div>
                <div class="row">
                    <?php
                    include_once("controllers/delete.php");
                    include_once("controllers/user.php");
                    ?>
                <div class="five columns">
                <a href="javascript:history.go(-1)">Back</a>
                <?php
                        $u  = new user();
                        if($u->getuser($_GET['uid'])){
                        ?>
                        <b><?php echo $u->getname(); ?></b>
                        <br>
                        <p><?php echo $u->getcontact(); ?>
                        <br><?php echo $u->getemail(); ?>
                        <br><?php echo $u->getcity(); ?>
                        <br><?php echo $u->getarea(); ?>
                        <br><form name='delete' method='post' action='<?php echo $_SERVER['PHP_SELF']; ?>'>
                                <input type='hidden' name='uid' value='".$h->getuid()."'>
                                <input type='Submit' name='delete_user' value='Delete'>
                            </form>
                        </p>
                        
                        
                    
                    </div>
                    <div class="seven columns">
                        <h5>Reviews:</h5>
                        <?php 
                        $r  = new review();
                        if($u->getrole()==0 && $r->select_hotel_reviews($u->getuid())){
                        ?>

                            <table border="1">
                                <?php
                                    while($r->next()){
                                        $c  = new customer();
                                        
                                ?>
                                <tr>
                                    <td><?php if($c->getuser($r->getcid())){ echo $c->getname();} else{echo "[deleted]";} ?></td>
                                    <td><?php echo $r->getmsg(); ?></td>
                                    <td><?php echo @date('h:i A d M Y',,$r->getdate()); ?></td>
                                    <td>
                                        <form name="del_review" action="delete.php" method="post">
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
                        }else if($u->getrole()==1 && $r->select_customer_reviews($u->getuid())){
                        ?>

                            <table border="1">
                                <?php
                                    while($r->next()){
                                        $h  = new hotel();
                                        
                                ?>
                                <tr>
                                    <td><?php if($h->getuser($r->getcid())){ echo $h->getname();} else{echo "[deleted]";} ?></td>
                                    <td><?php echo $r->getmsg(); ?></td>
                                    <td><?php echo @date('h:i A d M Y',,$r->getdate()); ?></td>
                                    <td>
                                        <form name="del_review" action="delete.php" method="post">
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
        </div>
    </body>
</html>