<?php
class review extends config{
    private $rid,$hid,$cid,$date,$msg;
    private $all,$err;
    
    public function review(){
        $this->connect();
    }
    public function getreview($rid){
        $rid=mysqli_real_escape_string($this->con,$rid);
        
        $this->rid      = $rid;
        $sql	= "SELECT * FROM review WHERE rid='$this->rid'";
        $sqlID	= mysqli_query($this->con,$sql);
        if($sqlID){
            $row    = mysqli_fetch_array($sqlID);
            
            $this->hid      = $row['hid']; 
            $this->cid      = $row['cid'];            
            $this->date     = $row['date'];
            $this->msg      = $row['msg'];
            
            return true;
        }
        else{
            return false; 
        }
    }    
    public function getvalues($POST){
        
        $this->hid      = mysqli_real_escape_string($this->con,$POST['hid']);
        $this->cid      = mysqli_real_escape_string($this->con,$POST['cid']);        
        $this->date     = time();
        $this->msg      = mysqli_real_escape_string($this->con,$POST['msg']);

    }
    function validate(){
        if($this->msg=="" || $this->msg==null){
            $this->err="Category name cannot be empty.";
            return false;
        }
        return true;
    }
    public function geterr(){
        return $this->err;
    }
    public function post(){
        $sql        = "INSERT INTO review (hid,cid,msg,date) VALUES ('$this->hid','$this->cid','$this->msg','$this->date')";
        $sqlID      = mysqli_query($this->con,$sql);
        if($sqlID)
            return true;
        return false;        
    }

    
    public function delete(){
        if($this->exists()){
            $sql        = "DELETE FROM review WHERE rid='$this->rid'";
            $sqlID      = mysqli_query($this->con,$sql);
            if($sqlID){
                return true;
            }
        }
         return false;
    }
    public function exists(){
        $sql        = "SELECT * FROM review WHERE cid='$this->cid' AND hid='$this->hid' AND msg='$this->msg'";
        $sqlID      = mysqli_query($this->con,$sql);
        if($sqlID){
            return true;
        }
        else {
            return false;
        }
    }
    
    public function select_hotel_reviews($uid){

    $uid=mysqli_real_escape_string($this->con,$uid);
        
        $sql	= "SELECT * FROM review WHERE hid=$uid";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }
    public function select_hotel_reviews_page($uid,$page_no){
        
        $uid=mysqli_real_escape_string($this->con,$uid);
        $page_no=mysqli_real_escape_string($this->con,$page_no);
        
        $offset=$page_no*$this->per_page;
        $sql	= "SELECT * FROM review WHERE hid=$uid";
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
    public function select_customer_reviews($uid){
        $uid=mysqli_real_escape_string($this->con,$uid);
        
        $sql	= "SELECT * FROM review WHERE cid=$uid";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }
    public function select_customer_reviews_page($uid,$page_no){
        $uid=mysqli_real_escape_string($this->con,$uid);
        $page_no=mysqli_real_escape_string($this->con,$page_no);
        
        $offset=$page_no*$this->per_page;
        $sql	= "SELECT * FROM review WHERE cid=$uid";
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
    public function next(){
        if($row    = mysqli_fetch_array($this->all)){
            
            $this->rid      = $row['rid'];
            $this->hid      = $row['hid'];
            $this->cid      = $row['cid'];            
            $this->date     = $row['date'];
            $this->msg      = $row['msg'];
            
            return true;
        }
        else{
            return false; 
        }
    }
    
    public function getrid(){
        return $this->rid;
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
    public function getmsg(){
        return $this->msg;
    }
    public function report(){
        return mysqli_error($this->con);
    }
}

?>