<?php
include_once("category_class.php");

class item extends config{
    private $iid,$hid,$name,$category,$price,$discount,$quantity,$special;
    private $all,$err;
    
    public function item(){
        $this->connect();
    }
    public function getitem($iid){
        
        $this->iid      = mysqli_real_escape_string($this->con,$iid);
        $sql	        = "SELECT * FROM item WHERE iid='$this->iid'";
        $sqlID	        = mysqli_query($this->con,$sql);
        if($sqlID){
            $row        = mysqli_fetch_array($sqlID);
            
            $this->hid      = $row['hid'];
            $this->name     = $row['name'];
            $this->category = $row['category'];
            $this->price    = $row['price'];
            $this->discount = $row['discount'];
            $this->special = $row['special'];
            
            return true;
        }
        else{
            return false; 
        }
    }
    public function getvalues($POST){
        
        $this->hid      = mysqli_real_escape_string($this->con,$POST['hid']);
        $this->name     = mysqli_real_escape_string($this->con,$POST['name']);
        $this->category = $POST['category'];
        $this->price    = mysqli_real_escape_string($this->con,$POST['price']);
        $this->discount = mysqli_real_escape_string($this->con,$POST['discount']);
        
    }
    public function validate(){
        if($this->name=="" || $this->name==null){
            $this->err="Item name cannot be empty.";
            return false;
        }else if(!preg_match('/^[A-Za-z 0-9]+$/',$this->name)){
            $this->err="Item name must contain only alphanumeric characters. ".$this->name;
            return false;
        }
        else if($this->price=="" || $this->price==null){
            $this->err="Price cannot be empty.";
            return false;
        }else if(!ctype_digit($this->price)){
            $this->err="Price must contain only digits.";
            return false;
        }else if($this->discount=="" || $this->discount==null){
            $this->err="Discount cannot be empty.";
            return false;
        }else if(!ctype_digit($this->discount)){
            $this->err="Discount must contain only digits.";
            return false;
        }
        return true;
    }
    public function geterr(){
        return $this->err;
    }    
    public function insert(){
        if(!$this->exists()){
            $sql        = "INSERT INTO item(hid,name,category,discount,price,special) VALUES('$this->hid','$this->name','$this->category','$this->discount','$this->price','0')";
            $sqlID      = mysqli_query($this->con,$sql);
            if($sqlID)
                return true;            
        }
        return false;        
    }
    public function update(){

        $sql        = "UPDATE item SET category='$this->category',discount='$this->discount',price='$this->price',name='$this->name' WHERE iid='$this->iid'";
        $sqlID      = mysqli_query($this->con,$sql);
        if($sqlID){
            return true;
        }
        else {
            return false;
        }
    }
    public function makets(){

        $sql        = "UPDATE item SET special='1' WHERE iid='$this->iid'";
        $sqlID      = mysqli_query($this->con,$sql);
        if($sqlID){
            return true;
        }
        else {
            return false;
        }
    }
    public function removets(){

        $sql        = "UPDATE item SET special='0' WHERE iid='$this->iid'";
        $sqlID      = mysqli_query($this->con,$sql);
        if($sqlID){
            return true;
        }
        else {
            return false;
        }
    }
    public function remove(){

        $sql        = "UPDATE item SET discount=0 WHERE iid='$this->iid'";
        $sqlID      = mysqli_query($this->con,$sql);
        if($sqlID){
            return true;
        }
        else {
            return false;
        }
    }
    public function delete(){
        if($this->exists()){
            if(!$this->isused()){
            $sql        = "DELETE FROM item WHERE iid='$this->iid'";
            $sqlID      = mysqli_query($this->con,$sql);
            }
        }
        return false;
    }
    function isused(){
        $sql	= "SELECT * FROM orders_items WHERE iid='$this->iid'";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            return true;
        }
        else{
            return false;
        }
    }
    
    function exists(){
        $sql	= "SELECT * FROM item WHERE hid='$this->hid' AND name='$this->name'";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            return true;
        }
        else{
            return false;
        }
    }
    public function select_all($hid){
        
        $hid=mysqli_real_escape_string($this->con,$hid);
        
        $sql	= "SELECT * FROM item WHERE hid='$hid' ORDER BY discount DESC";
        
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }public function select_all_page($hid,$page){
        
        $hid=mysqli_real_escape_string($this->con,$hid);
        $page=mysqli_real_escape_string($this->con,$page);
        
        $offset=$page*$this->per_page;
        
        $sql	= "SELECT * FROM item WHERE hid='$hid' ORDER BY discount DESC";
        
        $this->count=$this->count($sql);
        
        $sql	= $sql." LIMIT $offset,$this->per_page";
        
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }

    public function select_ts($hid){
        $hid=mysqli_real_escape_string($this->con,$hid);
        
        $sql	= "SELECT * FROM item WHERE hid='$hid' AND special='1'  ORDER BY discount DESC";
        
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }
    
    public function search_page($hid,$search,$page){
        
        $hid=mysqli_real_escape_string($this->con,$hid);
        $page=mysqli_real_escape_string($this->con,$page);
        $search=mysqli_real_escape_string($this->con,$search);
        
        $offset=$page*$this->per_page;
        if($search=="all"){
            $sql	= "SELECT * FROM item WHERE hid='$hid' ORDER BY discount DESC";
        }else{
            $sql	= "SELECT * FROM item,category WHERE item.hid='$hid' AND category.ctid=item.category AND ( name LIKE '%$search%' OR category.cname LIKE '%$search%' ) ORDER BY discount DESC";
        }
        $this->count=$this->count($sql);
        
        $sql	= $sql." LIMIT $offset,$this->per_page";
        
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }
    public function search_category($hid,$search){
        
        $hid = mysqli_real_escape_string($this->con,$hid);
        $search = mysqli_real_escape_string($this->con,$search);
        
        if($search!=null || $search!=""){
            if($search==0){
                $sql    = "SELECT * FROM item WHERE item.hid='$hid' AND item.special=1";
            }else{
            $c =new category();
            $c->getcategory($search);
            if($c->isparent()){
                 $sql	= "SELECT * FROM item,category WHERE item.hid='$hid' AND item.category=category.ctid AND ( category.ctid='$search' OR category.pid='$search' )";
            }else{
                $sql	= "SELECT * FROM item,category WHERE item.hid='$hid' AND item.category=category.ctid AND category.ctid='$search'";
            }
            }
        }else{
            $sql	= "SELECT * FROM item WHERE hid='$hid'";
        }
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }
    public function search_category_page($hid,$search,$page){
        
        $hid=mysqli_real_escape_string($this->con,$hid);
        $page=mysqli_real_escape_string($this->con,$page);
        $search=mysqli_real_escape_string($this->con,$search);
        
        $offset=$page*$this->per_page;
        if($search!=null || $search!=""){
            if($search==0){
                $sql    = "SELECT * FROM item WHERE item.hid='$hid' AND item.special='1'";
            }else{
                $c =new category();
                $c->getcategory($search);
                if($c->isparent()){
                     $sql	= "SELECT * FROM item,category WHERE item.hid='$hid' AND item.category=category.ctid AND ( category.ctid='$search' OR category.pid='$search' )";
                }else{
                    $sql	= "SELECT * FROM item,category WHERE item.hid='$hid' AND item.category=category.ctid AND category.ctid='$search'";
                }
            }
        }else{
            $sql	= "SELECT * FROM item WHERE hid='$hid'";
        }
        $this->count=$this->count($sql);
        $sql=$sql." LIMIT $offset,$this->per_page";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }
    public function select_order_items($oid){
        
        $oid=mysqli_real_escape_string($this->con,$oid);
        
        $sql	= "SELECT * FROM orders_items AS oi,item AS i WHERE oi.oid='$oid' AND i.iid=oi.iid";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $row    = mysqli_fetch_array($sqlID);
            
            $this->oid      = $row['oid'];
            $this->quantity = $row['quantity'];
            $this->all      =$sqlID;
            
        }
        return true;
    }
    public function get_cart_items($hid,$cid){
        $hid=mysqli_real_escape_string($this->con,$hid);
        $cid=mysqli_real_escape_string($this->con,$cid);
        
        $sql	= "SELECT * FROM cart,item WHERE cart.hid='$hid' AND cart.cid='$cid' AND item.iid=cart.iid";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            
            $this->all      =$sqlID;
            return true;
        }
        return false;
    }
    public function get_cart_quantity($iid,$cid){
        
        $iid=mysqli_real_escape_string($this->con,$iid);
        $cid=mysqli_real_escape_string($this->con,$cid);
        
        $sql	= "SELECT * FROM cart WHERE iid='$iid' AND cid='$cid'";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)==1){
            $row=mysqli_fetch_array($sqlID);
            return $row['quantity'];
            
        }
        return false;
    }
    public function is_in_cart($iid,$cid){
        
        $iid=mysqli_real_escape_string($this->con,$iid);
        $cid=mysqli_real_escape_string($this->con,$cid);
        
        $sql	= "SELECT * FROM cart WHERE iid='$iid' AND cid='$cid'";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)==1){
            return true;  
        }
        else{
            return false;
        }
    }
    public function cart_next(){
        if($row    = mysqli_fetch_array($this->all)){
            $this->iid      = $row['iid'];
            $this->name     = $row['name'];
            $this->category = $row['category'];
            $this->price    = $row['price'];
            $this->discount = $row['discount'];
            $this->special = $row['special'];
            $this->quantity = $row['quantity'];
            return true;
        }
        else{
            return false; 
        }
    }
    public function get_cart_hotels($cid,$skey,$page){
        
        $skey=mysqli_real_escape_string($this->con,$skey);
        $cid=mysqli_real_escape_string($this->con,$cid);
        $page=mysqli_real_escape_string($this->con,$page);
        
        $offset=$page*$this->per_page;
        if($skey==null || $skey==""){
            $sql	= "SELECT hid FROM cart WHERE cid='$cid' GROUP BY hid";
        }else{
            $sql	= "SELECT hid FROM cart,user WHERE user.name LIKE '%$skey%' AND cart.hid=user.uid AND cart.cid='$cid' GROUP BY hid";
        }
        $this->count=$this->count($sql);
        $sql=$sql." LIMIT $offset,$this->per_page";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            
            $this->all      =$sqlID;
            return true;
        }
        return false;
    }
    public function next_hotel(){
        if($row    = mysqli_fetch_array($this->all)){
            $this->hid      = $row['hid'];
            return true;
        }
        else{
            return false; 
        }
    }
    public function getcitems($hid,$cid){
        
        $hid=mysqli_real_escape_string($this->con,$hid);
        $cid=mysqli_real_escape_string($this->con,$cid);
        
        $items=null;
        $sql	= "SELECT i.name,c.quantity FROM item AS i,cart AS c WHERE i.iid=c.iid AND c.cid='$cid' AND c.hid='$hid'";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            while($row    = mysqli_fetch_array($sqlID)){
                $items.=$row["quantity"]." * ".$row["name"]."<br>";
            }
            return $items;
        }
        else{
            return $this->report();
        }
    }
    public function next(){
        if($row    = mysqli_fetch_array($this->all)){
            $this->iid      = $row['iid'];
            $this->hid      = $row['hid'];
            $this->name     = $row['name'];
            $this->category = $row['category'];
            $this->price    = $row['price'];
            $this->discount = $row['discount'];
            $this->special = $row['special'];
            return true;
        }
        else{
            return false; 
        }
    }
    public function getiid(){
        return $this->iid;
    }
    public function gethid(){
        return $this->hid;
    }
    public function getname(){
        return $this->name;
    }
    public function getspecial(){
        return $this->special;
    }
    public function getcategory(){
        $cat    =new category();
        $cat->getcategory($this->category);
        return $cat->getname();
    }
    public function getctid(){
        return $this->category;
    }
    public function getprice(){
        return $this->price;
    }
    public function getdiscount(){
        return $this->discount;
    }
    public function getquantity(){
        return $this->quantity;
    }
    public function report(){
        return mysqli_error($this->con);
    }
}

?>