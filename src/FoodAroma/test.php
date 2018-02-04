<?php
error_reporting(E_ALL);

include_once("classes/config_class.php");
include_once("classes/order_class.php");
$o=new orders();
var_dump($o->has_cart(2));

?>