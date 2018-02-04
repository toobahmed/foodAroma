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
        <title>Item</title>
        <link href="style/normalize.css" rel="stylesheet" type="text/css">
        <link href="style/skeleton.css" rel="stylesheet" type="text/css">
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <script type="text/javascript">
            function validate() {
                var name=document.forms['item']['name'].value;
                var price=document.forms['item']['price'].value;
                var discount=document.forms['item']['dicount'].value;
                var category=document.forms['item']['category'].value;
                if(name==null || name=="") {
                    alert("Category name cannot be empty. Try again.");
                    return false;
                }else if(price==null || price=="") {
                    alert("Price cannot be empty. Try again.");
                    return false;
                }else if(category==null || category=="") {
                    alert("Category cannot be empty. Try again.");
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
                    if(!iscustomer()){
                    if(isset($_POST["edit_item"])){
                        $i= new item();
                        $i->getitem($_POST["iid"]);
                    ?>
                    <a href="javascript:history.go(-1)">Back</a>
                        <h5>Edit Item:</h5>
                        
                        <form name="item" action="my_menu.php" method="post" onsubmit="return validate();">
                        <div class="six columns">
                        <label for="name">Name:</label>
                        <input required type="text" id="name" name="name" value="<?php echo $i->getname(); ?>">
                        <br>
                        <label for="category">Category:</label>
                        <select name="category" id="category">
                            <?php
                                $c = new category();
                                if($c->select_all($_SESSION['uid'])){
                                ?>
                                <option value="0">-Select-</option>
                                <?php
                                while($c->next()){
                                ?>
                                <option value="<?php echo $c->getctid(); ?>" <?php if($i->getctid()==$c->getctid()){ echo "selected"; } ?> ><?php echo $c->getname(); ?></option>
                                <?php
                                }
                                }else{
                                ?>
                                <option value="0">-No Categories-</option>
                                <?php
                                }
                                ?>
                        </select>
                        </div>
                        <div class="six columns">
                        <label for="price">Price:</label>
                        <input required type="text" id="price" name="price" value="<?php echo $i->getprice(); ?>">
                        <br>
                        <label for="discount">Discount:</label>
                        <input required type="text" id="discount" name="discount" value="<?php echo $i->getdiscount(); ?>">%
                        </div>
                        <input type="hidden" name="iid" value="<?php echo $i->getiid(); ?>">
                        <input type="hidden" name="hid" value="<?php echo $i->gethid(); ?>">
                        <input type="submit" value="Update Item" name="update_item">
                        </form>
                    <?php
                    }else{
                    ?>
                    <h5>New Item:</h5>
                        <form name="item" action="my_menu.php" method="post" onsubmit="return validate();">
                        <div class="six columns">
                        <label for="name">Name:</label>
                        <input required type="text" id="name" name="name">
                        <br>
                        <label for="category">Category:</label>
                        <select name="category" id="category">
                            <?php
                                $c = new category();
                                if($c->select_all($_SESSION['uid'])){
                                ?>
                                <option value="0">-Select-</option>
                                <?php
                                while($c->next()){
                                ?>
                                <option value="<?php echo $c->getctid(); ?>"><?php echo $c->getname(); ?></option>
                                <?php
                                }
                                }else{
                                ?>
                                <option value="0">-No Categories-</option>
                                <?php
                                }
                                ?>
                        </select>
                        </div>
                        <div class="six columns">
                        <label for="price">Price:</label>
                        <input required type="text" id="price" name="price">
                        <br>
                        <label for="discount">Discount:</label>
                        <input required type="text" id="discount" name="discount">%
                        </div>
                        <input type="hidden" name="hid" value="<?php echo $_SESSION['uid']; ?>">
                        <input type="submit" value="Add Item" name="add_item">
                        </form>
                    <?php
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
                