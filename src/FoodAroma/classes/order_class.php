<?php
include_once("user_class.php");

class orders extends config{
    private $oid, $hid, $cid, $date, $quantity, $iid, $address, $status, $total;
    private $all,$err;
    
    public function orders(){
        $this->connect();
    }
    public function getorders($oid){
        
        $oid=mysqli_real_escape_string($this->con,$oid);
        
        $this->oid      = $oid;
        $sql	= "SELECT * FROM orders WHERE oid='$this->oid'";
        $sqlID	= mysqli_query($this->con,$sql);
        if($sqlID){
            $row    = mysqli_fetch_array($sqlID);
            
            $this->hid      = $row['hid']; 
            $this->cid      = $row['cid'];            
            $this->date     = $row['date'];
            $this->address  = $row['address'];
            $this->status   = $row['status'];
            $this->omsg    = $row['omsg'];
            $this->total    = $row['total'];
            
            return true;
        }
        else{
            return false; 
        }
    }
    public function getvalues($POST){
        
        $this->hid      = mysqli_real_escape_string($this->con,$POST['hid']);
        $this->cid      = mysqli_real_escape_string($this->con,$POST['cid']);
        if(isset($POST['address'])){
        $this->address  = mysqli_real_escape_string($this->con,$POST['address']);
        }
        
        $this->date     = time();
        $this->status   = "request";
        
        $this->quantity = isset($POST['quantity'])?$POST['quantity']:"";
        $this->iid = isset($POST['iid'])?$POST['iid']:"";

    }
    
    public function validate(){
        if($this->iid=="" || $this->iid==null){
            $this->err="No Items Selected.";
            return false;
        }
        else if($this->quantity=="" || $this->quantity==null){
            $this->err="Quantity cannot be empty.";
            return false;
        }else if($this->iid=="" || $this->iid==null){
            $this->err="No item was selected";
            return false;
        }
        foreach($this->iid as $key=>$id){
            if(!ctype_digit($this->quantity[$key])){
                $this->err="Quantity must only be digits.";
                return false;
            }else if($this->quantity[$key]=="0"){
                $this->err="Invalid Quantity!";
                return false;
            }
        }
        return true;
    }
    public function geterr(){
        return $this->err;
    }     
    public function place_order(){

        
        $sql1        = "INSERT INTO orders (hid,cid,date,address,status) VALUES ('$this->hid','$this->cid','$this->date','$this->address','$this->status')";
        $sqlID1      = mysqli_query($this->con,$sql1);
        
        $this->oid=mysqli_insert_id($this->con);
        $total=0;
        foreach($this->iid as $key=>$iid){
            $iid         = mysqli_real_escape_string($this->con,$iid);
            $quantity    = mysqli_real_escape_string($this->con,$this->quantity[$key]);
            
            $i=new item();
            $i->getitem($iid);
            $price=$i->getprice();
            $discount=$i->getdiscount();

            $total+=$price*(1-(0.01*$discount))*$quantity;
            
            $sql2        = "INSERT INTO orders_items (oid,iid,quantity) VALUES ('$this->oid','$iid','$quantity')";
            $sqlID2      = mysqli_query($this->con,$sql2);
        }
        $sql3        = "UPDATE orders SET total='$total' WHERE oid='$this->oid'";
        $sqlID3     = mysqli_query($this->con,$sql3);
        $sql4        = "DELETE FROM cart WHERE hid='$this->hid' AND cid='$this->cid'";
        $sqlID4     = mysqli_query($this->con,$sql4);
        if($sqlID1 && $sqlID2 && $sqlID3 && $sqlID4){
            $h = new hotel();
            $h->getuser($this->hid);
            //$this->send_sms($h->getcontact(),"An Order has been placed to ".$h->getname());
            return true;
        }
        return false;        
    }
    public function add_to_cart(){
        foreach($this->iid as $key=>$iid){
            $iid         = mysqli_real_escape_string($this->con,$iid);
            $quantity    = mysqli_real_escape_string($this->con,$this->quantity[$key]);
            
            
            $sql2        = "INSERT INTO cart (hid,iid,quantity,cid) VALUES ('$this->hid','$iid','$quantity','$this->cid')";
            $sqlID2      = mysqli_query($this->con,$sql2);
        }
        
        if($sqlID2){
            return true;
        }
        return false;        
    }
    public function update_cart(){
        foreach($this->iid as $key=>$iid){
            $iid         = mysqli_real_escape_string($this->con,$iid);
            $quantity    = mysqli_real_escape_string($this->con,$this->quantity[$key]);
            
            
            $sql2        = "UPDATE cart SET quantity='$quantity' WHERE iid='$iid' AND cid='$this->cid'";
            $sqlID2      = mysqli_query($this->con,$sql2);
        }
        
        if($sqlID2){
            return true;
        }
        return false;        
    }
    public function delete_from_cart(){
        foreach($this->iid as $key=>$iid){
            $iid         = mysqli_real_escape_string($this->con,$iid);
            $quantity    = mysqli_real_escape_string($this->con,$this->quantity[$key]);
            
            
            $sql2        = "DELETE FROM cart WHERE iid='$iid' AND cid='$this->cid'";
            $sqlID2      = mysqli_query($this->con,$sql2);
        }
        
        if($sqlID2){
            return true;
        }
        return false;        
    }
    function has_cart(){
        $sql	= "SELECT * FROM cart WHERE hid='$this->hid'";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            return true;
        }
        else{
            return false;
        }
    }
    public function accept($omsg){
        if(!$this->isaccepted()){
            $omsg      =mysqli_real_escape_string($this->con,$omsg);
            $sql1        = "UPDATE orders SET status='accepted',omsg='$omsg' WHERE oid='$this->oid'";
            $sqlID1     = mysqli_query($this->con,$sql1);
            
            
            if($sqlID1){
                $c = new customer();
                $c->getuser($this->cid);
                $h = new hotel();
                $h->getuser($this->hid);
            
                //$this->send_sms($c->getcontact(),"Your order to ".$h->getname()." has been accepted");
                
                return true;
            }
        }
        return false;
    }
    public function reject($omsg){
        if(!$this->isrejected()){
            $omsg      =mysqli_real_escape_string($this->con,$omsg);
            if($this->omsg==null || $this->omsg==""){
                $this->msg="NA";
            }
            $sql1        = "UPDATE orders SET status='rejected' AND omsg='$omsg' WHERE oid='$this->oid'";
            $sqlID1      = mysqli_query($this->con,$sql1);
            
            if($sqlID1){
                $c = new customer();
                $c->getuser($this->cid);
                $h = new hotel();
                $h->getuser($this->hid);
            
                //$this->send_sms($c->getcontact(),"Your order to ".$h->getname()." has been rejected");
                
                return true;
            }
        }
         return false;
    }
    public function complete(){
        $sql        = "UPDATE orders SET status='complete' WHERE oid='$this->oid'";
        $sqlID      = mysqli_query($this->con,$sql);
        if($sqlID){
            return true;
        }
        return false;
    }
    
    public function delete(){
        if($this->exists()){
            $sql        = "DELETE FROM orders WHERE oid='$this->oid'";
            $sqlID      = mysqli_query($this->con,$sql);
            $sql1        = "DELETE FROM orders_items WHERE oid='$this->oid'";
            $sqlID1      = mysqli_query($this->con,$sql1);
            if($sqlID && $sqlID1){
                return true;
            }  
        }
        return false;
    }
    public function isaccepted(){
        $sql	= "SELECT * FROM orders WHERE oid='$this->oid' AND status='accepted'";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)==1){
            return true;
        }
        else{
            return false;
        }
    }
    public function exists(){
        $sql	= "SELECT * FROM orders WHERE oid='$this->oid'";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)==1){
            return true;
        }
        else{
            return false;
        }
    }
    public function isrejected(){
        $sql	= "SELECT * FROM orders WHERE oid='$this->oid' AND status='rejected'";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)==1){
            return true;
        }
        else{
            return false;
        }
    }
    public function refresh_order(){
        $sql        = "UPDATE orders SET status='incomplete' WHERE date < UNIX_TIMESTAMP(DATE_SUB(NOW(),INTERVAL 6 HOUR)) AND status='request'";
        $sqlID      = mysqli_query($this->con,$sql);
        if($sqlID){
            return true;
        }
        return false;
    }
    public function select_hotel_orders($hid,$filter){
        
        $hid=mysqli_real_escape_string($this->con,$hid);
        $filter=mysqli_real_escape_string($this->con,$filter);
        
        if($filter=="all"){
            $sql	= "SELECT * FROM orders WHERE hid='$hid' ORDER BY date";
        }
        else{
            $sql	= "SELECT * FROM orders WHERE hid='$hid' AND status='$filter' ORDER BY date";
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
    public function select_hotel_orders_today($hid,$filter){
    
        $hid=mysqli_real_escape_string($this->con,$hid);
        $filter=mysqli_real_escape_string($this->con,$filter);
        
        $sql	= "SELECT * FROM orders WHERE hid='$hid' AND status='$filter' AND date >= DATE_SUB(CURDATE(),INTERVAL 1 DAY) ORDER BY date";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }
    public function select_customer_orders_today($cid,$filter){
        
        $cid=mysqli_real_escape_string($this->con,$cid);
        $filter=mysqli_real_escape_string($this->con,$filter);
        
        $sql	= "SELECT * FROM orders WHERE cid='$cid' AND status='$filter' AND date >= DATE_SUB(CURDATE(),INTERVAL 1 DAY) ORDER BY date DESC";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }
    public function select_customer_orders_today_page($cid,$filter,$page){
        
        $cid=mysqli_real_escape_string($this->con,$cid);
        $filter=mysqli_real_escape_string($this->con,$filter);
        $page=mysqli_real_escape_string($this->con,$page);
        
        $offset=$page*$this->per_page;
        $sql	= "SELECT * FROM orders WHERE cid='$cid' AND status='$filter' AND date >= DATE_SUB(CURDATE(),INTERVAL 1 DAY) ORDER BY date DESC LIMIT $offset,$page";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }
    public function pending_hotel_orders($hid){
        
        $hid=mysqli_real_escape_string($this->con,$hid);
        
        $sql	= "SELECT * FROM orders WHERE hid='$hid' AND status='request' ORDER BY date";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }
    public function pending_customer_orders($cid){
        $cid=mysqli_real_escape_string($this->con,$cid);
        
        $sql	= "SELECT * FROM orders WHERE cid='$cid' AND status='request' ORDER BY date";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }
    public function accepted_customer_orders($cid){
        
        $cid=mysqli_real_escape_string($this->con,$cid);
        
        $sql	= "SELECT * FROM orders WHERE cid='$cid' AND status='accepted' ORDER BY date";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }
    public function rejected_customer_orders($cid){
        $cid=mysqli_real_escape_string($this->con,$cid);
        
        $sql	= "SELECT * FROM orders WHERE cid='$cid' AND status='rejected' ORDER BY date";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }
    public function select_customer_orders($cid,$filter){
        $filter=mysqli_real_escape_string($this->con,$filter);
        $cid=mysqli_real_escape_string($this->con,$cid);
        
        if($filter=="all"){
            $sql	= "SELECT * FROM orders WHERE cid='$cid' ORDER BY date";
        }else{
            $sql	= "SELECT * FROM orders WHERE cid='$cid' AND status='$filter' ORDER BY date";
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
    public function select_customer_orders_page($cid,$filter,$page){
        $filter=mysqli_real_escape_string($this->con,$filter);
        $cid=mysqli_real_escape_string($this->con,$cid);
        $page=mysqli_real_escape_string($this->con,$page);
        
        $offset=$page*$this->per_page;
        if($filter=="all"){
            $sql	= "SELECT * FROM orders WHERE cid='$cid' ORDER BY date";
        }else{
            $sql	= "SELECT * FROM orders WHERE cid='$cid' AND status='$filter' ORDER BY date";
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
    public function select_hotel_orders_page($hid,$filter,$page){
        $filter=mysqli_real_escape_string($this->con,$filter);
        $hid=mysqli_real_escape_string($this->con,$hid);
        $page=mysqli_real_escape_string($this->con,$page);
        
        $offset=$page*$this->per_page;
        if($filter=="all"){
            $sql	= "SELECT * FROM orders WHERE hid='$hid' ORDER BY date";
        }else{
            $sql	= "SELECT * FROM orders WHERE hid='$hid' AND status='$filter' ORDER BY date";
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
    
    public function next(){
        if($row    = mysqli_fetch_array($this->all)){
            $this->oid      = $row['oid'];
            $this->hid      = $row['hid'];
            $this->cid      = $row['cid'];            
            $this->date     = $row['date'];
            $this->address  = $row['address'];
            $this->status   = $row['status'];
            $this->total    = $row['total'];
            $this->omsg    = $row['omsg'];
            return true;
        }
        else{
            return false; 
        }
    }
    
    public function getoid(){
        return $this->oid;
    }
    public function gethid(){
        return $this->hid;
    }
    public function getcid(){
        return $this->cid;
    }
    public function getdate(){
        return $this->date;
    }
    public function getaddress(){
        return $this->address;
    }
    public function gettotal(){
        return $this->total;
    }
    public function getomsg(){
        return $this->omsg;
    }
    public function getstatus(){
        return $this->status;
    }
    public function getitems(){
        $items=null;
        $sql	= "SELECT i.name,oi.quantity FROM orders AS o,item AS i,orders_items AS oi WHERE o.oid='$this->oid' AND oi.oid=o.oid AND i.iid=oi.iid";
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
    public function report(){
        return mysqli_error($this->con);
    }
}

?>