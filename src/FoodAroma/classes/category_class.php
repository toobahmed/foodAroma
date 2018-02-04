<?php
class category extends config{
    private $ctid, $pid, $cname,$hid;
    private $all,$err;
    
    public function category(){
        $this->connect();
    }
    public function getcategory($ctid){
        $ctid=mysqli_real_escape_string($this->con,$ctid);
        
        $this->ctid     = $ctid;
        $sql	        = "SELECT * FROM category WHERE ctid='$this->ctid'";
        $sqlID	        = mysqli_query($this->con,$sql);
        if($sqlID){
            $row        = mysqli_fetch_array($sqlID);
            
            $this->pid          = $row['pid'];
            $this->cname     = $row['cname'];
            $this->hid     = $row['hid'];
            return true;
        }
        else{
            return false; 
        }
    }
    public function getvalues($POST){
        
        $this->pid      = $POST['pid'];
        $this->hid      = $POST['hid'];
        $this->cname = mysqli_real_escape_string($this->con,$POST['cname']);
        
    }
    public function validate(){
        if($this->cname=="" || $this->cname==null){
            $this->err="Category name cannot be empty.";
            return false;
        }else if(!preg_match('/^[A-Za-z 0-9]+$/',$this->cname)){
            $this->err="Category name must contain only alphabets.";
            return false;
        }
        return true;
    }
    public function geterr(){
        return $this->err;
    }
    public function insert(){
        if(!$this->exists()){
            $sql        = "INSERT INTO category(pid,cname,hid) VALUES('$this->pid','$this->cname','$this->hid')";
            $sqlID      = mysqli_query($this->con,$sql);
            if($sqlID)
                return true;            
        }
        return false;        
    }
    public function update(){
        if(!$this->exists()){
            $sql        = "UPDATE category SET pid='$this->pid',cname='$this->cname' WHERE ctid='$this->ctid'";
            $sqlID      = mysqli_query($this->con,$sql);
            if($sqlID)
                return true;            
        }
        return false;
    }
    public function delete(){
        if(!$this->isused()){
            if($this->isparent()){
                $sql        = "DELETE FROM category WHERE ctid='$this->ctid' OR pid='$this->ctid'";
                $sqlID      = mysqli_query($this->con,$sql);
                
                if($sqlID){
                    return true;
                }
            }else {
                $sql        = "DELETE FROM category WHERE ctid='$this->ctid'";
                $sqlID      = mysqli_query($this->con,$sql);
                
                if($sqlID){
                    return true;
                }
            }
        }
            return false;

    }
    function exists(){
        $sql	= "SELECT * FROM category WHERE pid='$this->pid' AND cname='$this->cname' AND (hid='$this->hid' OR hid='0')";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            return true;
        }
        else{
            return false;
        }
    }
    public function isparent(){
        $sql	= "SELECT * FROM category WHERE pid='$this->ctid'";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            return true;
        }
        else{
            return false;
        }
    }
    public function isused(){
        $sql	= "SELECT * FROM item WHERE category='$this->ctid'";
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
        
        $sql	= "SELECT * FROM category WHERE hid=$hid OR hid=0";
        
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }
    public function select_allh($hid){
        $hid=mysqli_real_escape_string($this->con,$hid);
        
        $sql	= "SELECT * FROM category WHERE hid=$hid";
        
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }
    public function select_all_parent_cat($hid){
        $hid=mysqli_real_escape_string($this->con,$hid);
        
        $sql	= "SELECT * FROM category WHERE pid=0 AND (hid='$hid' OR hid=0)";
        
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }
    public function select_all_hparent_cat($hid){
        $hid=mysqli_real_escape_string($this->con,$hid);
        
        $sql	= "SELECT * FROM category WHERE pid=0 AND hid='$hid'";
        
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }
    public function select_all_sub_cat($hid,$ctid){
        $hid=mysqli_real_escape_string($this->con,$hid);
        $ctid=mysqli_real_escape_string($this->con,$ctid);
        
        $sql	= "SELECT * FROM category WHERE pid=$ctid AND (hid=$hid OR hid=0)";
        
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }
    public function select_all_hsub_cat($hid,$ctid){
        $hid=mysqli_real_escape_string($this->con,$hid);
        $ctid=mysqli_real_escape_string($this->con,$ctid);
        
        $sql	= "SELECT * FROM category WHERE pid='$ctid' AND hid='$hid'";
        
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
            $this->ctid     = $row['ctid'];
            $this->pid      = $row['pid'];
            $this->hid      = $row['hid'];
            $this->cname    = $row['cname'];
            return true;
        }
        else{
            return false; 
        }
    }
    public function getctid(){
        return $this->ctid;
    }
    public function getpid(){
        return $this->pid;
    }
    public function gethid(){
        return $this->hid;
    }
    public function getname(){
        $category=$this->cname;
        if($this->pid!=0){
            $pid=$this->pid;
            do{
                $c=new category();
                $c->getcategory($pid);
                $category=$c->getname().":".$category;
                $pid=$c->getpid();
            }while($pid!=0);            
        }
        return $category;
    }
    public function getcname(){
        return $this->cname;
    }
    public function report(){
        return mysqli_error($this->con);
    }
}
?>