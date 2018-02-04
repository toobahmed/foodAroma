<?php
    include_once("inc/header.php");
    include_once("classes/config_class.php");
    include_once("classes/category_class.php");
    //authenticate();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Manage Categories</title>
        <link href="style/style.css" rel="stylesheet" type="text/css">
        <link href="style/normalize.css" rel="stylesheet" type="text/css">
        <link href="style/skeleton.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
            <script type="text/javascript">
                function validate() {
				var cat=document.forms['edit_cat']['category'].value;
				
				if(cat==null || cat=="") {
				    alert("No value entered. Try again.");
				    return false;
				}
				else {
				    return true;
				}
			}
                        function validate1() {
				var cat=document.forms['update_cat']['category'].value;
				var cname=document.forms['update_cat']['cname'].value;
				if(cname==null || cname=="") {
				    alert("No value entered. Try again.");
				    return false;
				}
				else {
				    return true;
				}
			}
                        function validate2() {
				var cat=document.forms['new_cat']['category'].value;
				var cname=document.forms['new_cat']['cname'].value;
				if(cname==null || cname=="") {
				    alert("No value entered. Try again.");
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
                        <li><a href='admin_login.php?logout=true'>Logout</a></li>
                    </ul>
                </div>
                <div class="row">
                    <h2 class="text-center">Manage Categories</h2>
                    
                <?php
                include_once("controllers/delete.php");
                include_once("controllers/add.php");
                ?>
                <form name="edit_cat" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validate();">
                           <select name="ctid" id="category">
                                <?php
                                $c = new category();
                                if($c->select_all_parent_cat(0)){
                                ?>
                                <option value="">-Select Category-</option>
                                <?php
                                while($c->next()){
                                ?>
                                <option value="<?php echo $c->getctid(); ?>" ><?php echo $c->getname(); ?></option>
                                <?php
                                    $c1 = new category();
                                    if($c1->select_all_sub_cat(0,$c->getctid())){
                                    while($c1->next()){
                                ?>
                                <option value="<?php echo $c1->getctid(); ?>" ><?php echo $c1->getname(); ?></option>
                                <?php
                                }
                                }
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
                                if($c->select_all_parent_cat(0)){
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
                                <option value="0">No Parent</option>
                                <?php
                                }
                                ?>                                
                            </select>
                        <label for="cname">Category Name:</label>
                        <input required type="text" id="cname" name="cname" value="<?php echo $ct->getcname(); ?>">
                        <input type="hidden" name="ctid" value="<?php echo $ct->getctid(); ?>">
                        <input type="hidden" name="hid" value="0">
                        <input type="submit" value="Update Category" name="update_cat">
                        <input type="submit" value="Delete Category" name="delete_cat">
                        </form>
                        <?php
                   }else{
                   ?>
                        <h5>Add:</h5>
                        <form name="new_cat" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return validate2();">
                            <label for="category">Parent Category:</label>
                            <select name="pid" id="category">
                                
                                <?php
                                $c = new category();
                                if($c->select_all_parent_cat(0)){
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
                                <option value="">No Parent</option>
                                <?php
                                }
                                ?>                                
                            </select>
                        <label for="cname">Category Name:</label>
                        <input required type="text" id="cname" name="cname">
                        <input type="hidden" name="hid" value="0">
                        <input type="submit" value="Add Category" name="new_cat">
                        </form>
                    <?php
                    }
                
                ?>
                
                </div>
            </div>
        </div>
    </body>
</html>