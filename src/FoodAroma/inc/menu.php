<div class="row" id="header">

    <div id="loginStrip">
        <a href='index.php?logout=true'>Logout</a>
    </div>
    <a href="home.php">
        <h1>FoodAroma</h1>
        <h2>Online Food ordering System</h2>
    </a>

    <ul id="nav">
        <li><a href="home.php">Home</a>
        <li><a href="profile.php">Profile</a>
        <?php if(!iscustomer()){
        ?>
        <li><a href="my_menu.php">Manage Menu</a>
        <li><a href="category.php">Manage Category</a> 
        <?php
        }else{
            ?>
            <li><a href="cart.php">My Cart</a> 
            <?php
        }
        ?>
        <li><a href="my_orders.php">My Orders</a>
        <li><a href="reviews.php">My Reviews</a>
        <li><a href="browse.php">Browse Hotels</a>
    </ul>
</div>