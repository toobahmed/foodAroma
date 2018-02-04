<?php
    include_once("inc/header.php");
    include_once("classes/config_class.php");
    include_once("classes/user_class.php");
    include_once("classes/category_class.php");
    authenticate();    
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <title>Category</title>
        <link href="style/normalize.css" rel="stylesheet" type="text/css">
        <link href="style/skeleton.css" rel="stylesheet" type="text/css">
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <script type="text/javascript">
            function validate3() {
                var category=document.forms['edit_cat']['category'].value;
                if(category==null || category==""){
                    alert("No Category Selected");
                }
                else {
                    return true;
                }
            }
            function validate2() {
                var category=document.forms['new_cat']['category'].value;
                var cname=document.forms['new_cat']['cname'].value;
                if(cname==null || cname=="") {
                    alert("Category name cannot be empty! Try again.");
                    return false;
                }else if(category==null || category==""){
                    alert("No Parent Selected");
                }
                else {
                    return true;
                }
            }
            function validate1() {
                var category=document.forms['update_cat']['category'].value;
                var cname=document.forms['update_cat']['cname'].value;
                if(cname==null || cname=="") {
                    alert("Category name cannot be empty! Try again.");
                    return false;
                }else if(category==null || category==""){
                    alert("No Parent Selected");
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
            <div id="content" class="row">
                <?php include_once("controllers/add.php");
                    include_once("controllers/delete.php");
                    if(!iscustomer()){
                ?>  <h5>Manage Category:</h5>
                <form name="edit_cat" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validate3();">
                           <select name="ctid" id="category">
                                <?php
                                $c = new category();
                                if($c->select_allh($_SESSION['uid'])){
                                ?>
                                <option value="">-Select Category-</option>
                                <?php
                                while($c->next()){
                                ?>
                                <option value="<?php echo $c->getctid(); ?>" ><?php echo $c->getname(); ?></option>
                                <?php
                                }
                                }else{
                                ?>
                                <option value="">-No Categories-</option>
                                <?php
                                }
                                ?>                                
                            </select>
                        <input type="submit" value="Edit Category" name="edit_cat">
                        </form>
                        <?php
                   if(isset($_POST['edit_cat'])){
                   $ct = new category();
                   $ct->getcategory($_POST['ctid']);
                        ?>
                        <h5>Edit:</h5>
                        <form name="update_cat" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validate1();">
                            <label for="category">Parent Category:</label>
                            <select name="pid" id="category">
                                <?php
                                $c = new category();
                                if($c->select_all_parent_cat($_SESSION['uid'])){
                                ?>
                                <option value="0" <?php if($ct->getpid()==0){ echo "selected"; } ?> > No Parent</option>
                                <?php
                                while($c->next()){
                                ?>
                                <option value="<?php echo $c->getctid(); ?>" <?php if($ct->getpid()==$c->getctid()){ echo "selected"; } ?> ><?php echo $c->getname(); ?></option>
                                <?php
                                }
                                }else{
                                ?>
                                <option value="0">-No Parent-</option>
                                <?php
                                }
                                ?>                                
                            </select>
                        <label for="cname">Category Name:</label>
                        <input required type="text" id="cname" name="cname" value="<?php echo $ct->getcname(); ?>">
                        <input type="hidden" name="ctid" value="<?php echo $ct->getctid(); ?>">
                        <input type="hidden" name="hid" value="<?php echo $_SESSION['uid']; ?>">
                        <input type="submit" value="Update Category" name="update_cat">
                        <input type="submit" value="Delete Category" name="delete_cat">
                        </form>
                        <?php
                   }else{
                   ?>
                        <h5>Add New:</h5>
                        <form name="new_cat" action="my_menu.php" method="post" onsubmit="return validate2();">
                            <label for="category">Parent Category:</label>
                            <select name="pid" id="category">
                                <?php
                                $c = new category();
                                if($c->select_all_parent_cat($_SESSION['uid'])){
                                ?>
                                <option value="">-Select Category-</option>
                                <option value="0">No Parent</option>
                                <?php
                                while($c->next()){
                                ?>
                                <option value="<?php echo $c->getctid(); ?>"><?php echo $c->getname(); ?></option>
                                <?php
                                }
                                }else{
                                ?>
                                <option value="">-No Categories-</option>
                                <?php
                                }
                                ?>                                
                            </select>
                        <label for="cname">Category Name:</label>
                        <input required type="text" id="cname" name="cname">
                        <input type="hidden" name="hid" value="<?php echo $_SESSION['uid']; ?>">
                        <input type="submit" value="Add Category" name="new_cat">
                        </form>
                    <?php
                    }
                    }else{
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
                