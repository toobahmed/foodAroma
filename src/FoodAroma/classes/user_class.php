<?php
class user extends config{
    protected $uid, $uname, $pass, $name, $city, $contact, $email, $role, $area;
    protected $all,$result,$err, $count;
    
    public function user(){
        $this->connect();
        $this->uid=0;
    }
    public function getvalues($POST){
        
        $this->uname    = mysqli_real_escape_string($this->con,$POST['uname']);
        $this->pass     = mysqli_real_escape_string($this->con,$POST['pass']);
        $this->name     = mysqli_real_escape_string($this->con,$POST['name']);
        $this->city  = mysqli_real_escape_string($this->con,$POST['city']);
        $this->contact  = mysqli_real_escape_string($this->con,$POST['contact']);
        $this->email    = mysqli_real_escape_string($this->con,$POST['email']);
        $this->area  = mysqli_real_escape_string($this->con,$POST['area']);
        $this->role     = $POST['role'];
        
        
    }
    public function validate(){
        if($this->uname=="" || $this->uname==null){
            $this->err="User name cannot be empty.";
            return false;
        }else if(!preg_match('/^[A-Za-z 0-9]+$/',$this->uname)){
            $this->err="User name must contain only alphabets or digits.";
            return false;
        }
        else if($this->pass=="" || $this->pass==null){
            $this->err="Password cannot be empty.";
            return false;
        }else if(!preg_match('/^[A-Za-z 0-9]+$/',$this->pass)){
            $this->err="Password must contain only alphabets or digits.";
            return false;
        }else if($this->name=="" || $this->name==null){
            $this->err="Name cannot be empty.";
            return false;
        }else if(!preg_match('/^[A-Za-z 0-9]+$/',$this->name)){
            $this->err="Name must contain only alphabets or digits.";
            return false;
        }else if($this->city=="" || $this->city==null){
            $this->err="City cannot be empty.";
            return false;
        }else if(!preg_match('/^[A-Za-z ]+$/',$this->city)){
            $this->err="City must contain only alphabets.";
            return false;
        }else if(!preg_match('/^[A-Za-z 0-9]+$/',$this->area)){
            $this->err="Area must be alphanumeric.";
            return false;
        }else if($this->contact=="" || $this->contact==null){
            $this->err="Contact cannot be empty.";
            return false;
        }else if(!preg_match('/^[0-9]{10,12}$/',$this->contact)){
            $this->err="Contact must contain 10 to 12 digits.";
            return false;
        }else if($this->email=="" || $this->email==null){
            $this->err="Email cannot be empty.";
            return false;
        }
        return true;
    }
    public function geterr(){
        return $this->err;
    }
    public function getuser($uid){
        
        $uid=mysqli_real_escape_string($this->con,$uid);
        
        $this->uid  = $uid;
        $sql	    = "SELECT * FROM user WHERE uid='$this->uid'";
        $sqlID	    = mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)==1){
            $row    = mysqli_fetch_array($sqlID);
            
            $this->uname    = $row['uname'];
            $this->pass     = $row['pass'];
            $this->name     = $row['name'];
            $this->city     = $row['city'];
            $this->contact  = $row['contact'];
            $this->email    = $row['email'];
            $this->role     = $row['role'];
            $this->area     = $row['area'];
            
            return true;
        }
        else{
            return false; 
        }
    }
    
    public function insert(){
        if(!$this->exists()){
            $sql        = "INSERT INTO user(uname,pass,name,city,contact,email,role,area) VALUES('$this->uname','".$this->hash_password($this->pass)."','$this->name','$this->city','$this->contact','$this->email','$this->role','$this->area')";
            $sqlID      = mysqli_query($this->con,$sql);
            if($sqlID){
                if($this->role==0){
                    $this->hid  = mysqli_insert_id($this->con);
                    $sql1       = "INSERT INTO stats (hid,des,oaccepted,orejected,status) VALUES ('$this->hid','',0,0,'request')";
                    $sqlID1     = mysqli_query($this->con,$sql1);
                    if($sqlID1){
                        return true;
                    }
                    return false;
                }
                return true;            
            }
        }
        return false;        
    }
    public function update($POST){
        $this->getvalues($POST);
        $sql        = "UPDATE user SET pass='".$this->hash_password($this->pass)."',name='$this->name',city='$this->city',contact='$this->contact',email='$this->email',area='$this->area' WHERE uid='$this->uid'";
        $sqlID      = mysqli_query($this->con,$sql);
        if($sqlID){
            return true;
        }
        else {
            return false;
        }
    }
    
    
    
    function exists(){
        $sql	= "SELECT * FROM user WHERE uname='$this->uname'";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)==1){
            return true;
        }
        else{
            return false;
        }
    }
    public function login($uname,$pass){
        
        $uname=mysqli_real_escape_string($this->con,$uname);
        $pass=mysqli_real_escape_string($this->con,$pass);
        
        $sql	= "SELECT * FROM user WHERE uname='$uname' AND pass='".$this->hash_password($pass)."'";
        $sqlID	= mysqli_query($this->con,$sql);
        
        if(mysqli_num_rows($sqlID)==1){
            $row    = mysqli_fetch_array($sqlID);
        
                $this->uid=$row['uid'];
                $this->role=$row['role'];
                return true;
        }
        else{
            return false;
        }
    }
    public function login1($uname,$pass){
        $uname=mysqli_real_escape_string($this->con,$uname);
        $pass=mysqli_real_escape_string($this->con,$pass);
        
        $sql	= "SELECT * FROM user WHERE uname='$uname' AND pass='".$pass."'";
        $sqlID	= mysqli_query($this->con,$sql);
        
        if(mysqli_num_rows($sqlID)==1){
            $row    = mysqli_fetch_array($sqlID);
            $this->uid=$row['uid'];
            $this->role=$row['role'];
            return true;
        }
        else{
            return false;
        }
    }
    public function delete(){
        if($this->exists()){
            $sql        = "DELETE FROM user WHERE uid='$this->uid'";
            $sqlID      = mysqli_query($this->con,$sql);
            if($sqlID){
                return true;
            }    
        }
        return false;
    }
    
    public function next(){
        if($row    = mysqli_fetch_array($this->all)){
            $this->uid      = $row['uid'];
            $this->name     = $row['name'];
            $this->city  = $row['city'];
            $this->email    = $row['email'];
            $this->contact  = $row['contact'];
            $this->role     = $row['role'];
            $this->area     = $row['area'];
            
            return true;
        }
        else{
            return false; 
        }
    }
    public function setuid($uid){
        $uid=mysqli_real_escape_string($this->con,$uid);
        
        $this->uid=$uid;
    }
    public function getuid(){
        return $this->uid;
    }
    
    public function getuname(){
        return $this->uname;
    }
    
    public function getpass(){
        return $this->pass;
    }
    
    public function getname(){
        return $this->name;
    }
    
    public function getcity(){
        return $this->city;
    }
    public function getarea(){
        return $this->area;
    }
    
    public function getcontact(){
        return $this->contact;
    }
    
    public function getemail(){
        return $this->email;
    }
    
    public function getrole(){
        return $this->role;
    }
    
    public function report(){
        return mysqli_error($this->con);
    }
    public function getcount(){
        return $this->count;
    }
}
class customer extends user{
    public function delete(){
        $o          = new orders();
        if(!$o->pending_customer_orders($this->uid)){
            $sql        = "SELECT oid FROM orders WHERE cid='$this->uid'";
            $sqlID      = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_array($sqlID)){
                $oid = $row['oid'];
                $sql        = "DELETE FROM orders_items WHERE orders.oid='$oid'";
                $sqlID      = mysqli_query($this->con,$sql);
            }
            $sql        = "DELETE FROM cart WHERE cid='$this->uid'";
            $sqlID      = mysqli_query($this->con,$sql);
                parent::delete();
                return true;
        }
        return false;
    }
    public function select_all(){
        
        $sql	= "SELECT * FROM user WHERE uid<>'$this->uid' AND role='1'";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }
    public function search($search){
        $search=mysqli_real_escape_string($this->con,$search);
        
        if($search!=""){  
            $sql	= "SELECT * FROM user WHERE uid<>'$this->uid' AND role='1' AND name LIKE '%$search%'";
        }else{
            $sql	= "SELECT * FROM user WHERE uid<>'$this->uid' AND role='1'";
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
    public function search_page($search,$page_no){
        $search=mysqli_real_escape_string($this->con,$search);
        $page_no=mysqli_real_escape_string($this->con,$page_no);
        
        $offset=$page_no*$this->per_page;
        
        if($search!=""){  
            $sql	= "SELECT * FROM user WHERE uid<>'$this->uid' AND role='1' AND ( name LIKE '%$search%' OR city LIKE '%$search%' OR area LIKE '%$search%')";
        }else{
            $sql	= "SELECT * FROM user WHERE uid<>'$this->uid' AND role='1'";
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
}
class hotel extends user{
    
    private $des, $oaccepted, $orejected, $sid, $status;
    
    public function getvalues($POST){
        
        $this->des    = mysqli_real_escape_string($this->con,$POST['des']);
        parent::getvalues($POST);
        
    }
    public function getuser($uid){
        
        $uid=mysqli_real_escape_string($this->con,$uid);
        
        $sql	= "SELECT * FROM stats WHERE hid='$uid'";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)==1){
            $row            = mysqli_fetch_array($sqlID);
            
            $this->sid      = $row['sid']; 
            $this->oaccepted= $row['oaccepted'];            
            $this->orejected= $row['orejected'];
            $this->des      = $row['des'];
            $this->status      = $row['status'];
            parent::getuser($uid);
            return true;
        }
        return false;
    }
    public function update($POST){
        $this->des  = mysqli_real_escape_string($this->con,$POST['des']);
        $sql        = "UPDATE stats SET des='$this->des' WHERE hid='$this->uid'";
        $sqlID      = mysqli_query($this->con,$sql);
        if($sqlID){
            parent::update($POST);
            return true;
        }
        else {
            return false;
        }
    }
    public function accept(){
        $sql        = "UPDATE stats SET status='accepted' WHERE hid='$this->uid'";
        $sqlID      = mysqli_query($this->con,$sql);
        if($sqlID){
            return true;
        }
        else {
            return false;
        }
    }
    public function reject(){
        $sql        = "UPDATE stats SET status='rejected' WHERE hid='$this->uid'";
        $sqlID      = mysqli_query($this->con,$sql);
        if($sqlID){
            return true;
        }
        else {
            return false;
        }
    }
    public function delete(){
        $o          = new orders();
        if(!$o->pending_hotel_orders($this->uid)){
            $tables = array("stats","review","item","cart");
            foreach($tables as $table){
                $sql        = "DELETE FROM $table WHERE hid='$this->uid'";
                $sqlID      = mysqli_query($this->con,$sql);
            }
            $sql        = "SELECT oid FROM orders WHERE hid='$this->uid'";
            $sqlID      = mysqli_query($this->con,$sql);
            while($row = mysqli_fetch_array($sqlID)){
                $oid = $row['oid'];
                $sql        = "DELETE FROM orders_items WHERE orders.oid='$oid'";
                $sqlID      = mysqli_query($this->con,$sql);
            }
                parent::delete();
                return true; 
        }
        return false;
        
    }
    public function search_request($hname,$city,$area){
        
        $hname = mysqli_escape_string($this->con,$hname);
        $city = mysqli_escape_string($this->con,$city);
        $area = mysqli_escape_string($this->con,$area);
        
        if($hname!="" && $hname!=null && $city!="" && $city!=null){
            $sql	= "SELECT * FROM user,stats WHERE user.uid<>'$this->uid' AND user.role='0' AND user.name LIKE '%$hname%' AND user.city LIKE '%$city%' AND user.area LIKE '%$area%'AND stats.hid=user.uid AND stats.status='request'";
        }else{
            $sql	= "SELECT * FROM user,stats WHERE user.uid<>'$this->uid' AND user.role='0' AND ( user.name LIKE '%$hname%' OR user.city LIKE '%$city%' OR user.area LIKE '%$area%') AND stats.hid=user.uid AND stats.status='request'";
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
    public function select_all(){
        
        $sql	= "SELECT * FROM user,stats WHERE user.uid<>'$this->uid' AND user.role='0' AND stats.hid=user.uid AND stats.status='accepted'";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }
    public function select_cities(){
        
        $sql	= "SELECT city FROM user,stats WHERE user.uid<>'$this->uid' AND user.role='0' AND stats.hid=user.uid AND stats.status='accepted' GROUP BY city";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }public function select_r_cities(){
        
        $sql	= "SELECT city FROM user,stats WHERE user.uid<>'$this->uid' AND user.role='0' AND stats.hid=user.uid AND stats.status='request' GROUP BY city";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $this->all=$sqlID;
            return true;
        }
        else{
            return false;
        }
    }
    public function select_areas($city){
        
        $city=mysqli_real_escape_string($this->con,$city);
        
        if($city!=null && $city!=""){
            $sql	= "SELECT area FROM user,stats WHERE user.uid<>'$this->uid' AND user.role='0' AND stats.hid=user.uid AND stats.status='accepted' AND city LIKE '%$city%'";
        }
        else{
            $sql	= "SELECT area FROM user,stats WHERE user.uid<>'$this->uid' AND user.role='0' AND stats.hid=user.uid AND stats.status='accepted'";
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
    public function select_r_areas($city){
        
        $city=mysqli_real_escape_string($this->con,$city);
        
        if($city!=null && $city!=""){
            $sql	= "SELECT area FROM user,stats WHERE user.uid<>'$this->uid' AND user.role='0' AND stats.hid=user.uid AND stats.status='request' AND city LIKE '%$city%'";
        }
        else{
            $sql	= "SELECT area FROM user,stats WHERE user.uid<>'$this->uid' AND user.role='0' AND stats.hid=user.uid AND stats.status='request'";
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
    public function next_city(){
        if($row    = mysqli_fetch_array($this->all)){
            $this->city  = $row['city'];            
            return true;
        }
        else{
            return false; 
        }
    }
    public function next_area(){
        if($row    = mysqli_fetch_array($this->all)){
            $this->area  = $row['area'];            
            return true;
        }
        else{
            return false; 
        }
    }
    public function search1($hname,$city,$area){
        $hname = mysqli_escape_string($this->con,$hname);
        $city = mysqli_escape_string($this->con,$city);
        $area = mysqli_escape_string($this->con,$area);
        
        if($hname!="" && $hname!=null && $city!="" && $city!=null && $area!="" && $area!=null){
            $sql	= "SELECT * FROM user,stats WHERE user.uid<>'$this->uid' AND user.role='0' AND user.name LIKE '%$hname%' AND user.city LIKE '%$city%' AND user.area LIKE '%$area%' AND stats.hid=user.uid AND stats.status='accepted'";
        }
        else if($hname!="" && $hname!=null && $city!="" && $city!=null){
            
            $sql	= "SELECT * FROM user,stats WHERE user.uid<>'$this->uid' AND user.role='0' AND user.name LIKE '%$hname%' AND user.city LIKE '%$city%' AND stats.hid=user.uid AND stats.status='accepted'";
        }else if($hname!="" && $hname!=null && $area!="" && $area!=null){
            
            $sql	= "SELECT * FROM user,stats WHERE user.uid<>'$this->uid' AND user.role='0' AND user.name LIKE '%$hname%' AND user.area LIKE '%$area%' AND stats.hid=user.uid AND stats.status='accepted'";
        }else if($city!="" && $city!=null && $area!="" && $area!=null){
            
            $sql	= "SELECT * FROM user,stats WHERE user.uid<>'$this->uid' AND user.role='0' AND user.city LIKE '%$city%' AND user.area LIKE '%$area%' AND stats.hid=user.uid AND stats.status='accepted'";
        }else if($hname!="" && $hname!=null){
            
            $sql	= "SELECT * FROM user,stats WHERE user.uid<>'$this->uid' AND user.role='0' AND user.name LIKE '%$hname%' AND stats.hid=user.uid AND stats.status='accepted'";
        }else if($area!="" && $area!=null){
            
            $sql	= "SELECT * FROM user,stats WHERE user.uid<>'$this->uid' AND user.role='0' AND user.area LIKE '%$area%' AND stats.hid=user.uid AND stats.status='accepted'";
        }else if($city!="" && $city!=null){
            
            $sql	= "SELECT * FROM user,stats WHERE user.uid<>'$this->uid' AND user.role='0' AND user.city LIKE '%$city%' AND stats.hid=user.uid AND stats.status='accepted'";
        }else{
            
            $sql	= "SELECT * FROM user,stats WHERE user.uid<>'$this->uid' AND user.role='0' AND stats.hid=user.uid AND stats.status='accepted'";
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
    public function search_page($hname,$city,$area,$page_no){
        $hname = mysqli_escape_string($this->con,$hname);
        $city = mysqli_escape_string($this->con,$city);
        $area = mysqli_escape_string($this->con,$area);
        
        $offset=$page_no*$this->per_page;
        
        if($hname!="" && $hname!=null){
            
            $hsql	= "AND user.name LIKE '%$hname%' ";
        }else{
            $hsql       ="";
        }
        if($area!="" && $area!=null){
            
            $asql	= "AND user.area LIKE '%$area%' ";
        }else{
            $asql       ="";
        }
        if($city!="" && $city!=null){
            
            $csql	= "AND user.city LIKE '%$city%' ";
        }else{
            
            $csql	= "";
        }
        
        $sql    ="SELECT * FROM user,stats WHERE user.uid<>'$this->uid' AND user.role='0' ".$hsql.$csql.$asql."AND stats.hid=user.uid AND stats.status='accepted'";
        $this->count= $this->count($sql);
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
    public function search($hname,$city,$area){
        $hname = mysqli_escape_string($this->con,$hname);
        $city = mysqli_escape_string($this->con,$city);
        $area = mysqli_escape_string($this->con,$area);
        
        
        if($hname!="" && $hname!=null){
            
            $hsql	= "AND user.name LIKE '%$hname%' ";
        }else{
            $hsql       ="";
        }
        if($area!="" && $area!=null){
            
            $asql	= "AND user.area LIKE '%$area%' ";
        }else{
            $asql       ="";
        }
        if($city!="" && $city!=null){
            
            $csql	= "AND user.city LIKE '%$city%' ";
        }else{
            
            $csql	= "";
        }
        
        $sql    ="SELECT * FROM user,stats WHERE user.uid<>'$this->uid' AND user.role='0' ".$hsql.$csql.$asql."AND stats.hid=user.uid AND stats.status='accepted'";

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
            $this->des       = $row['des'];
            $this->sid       = $row['sid'];
            $this->oaccepted = $row['oaccepted'];            
            $this->orejected = $row['orejected'];
            $this->status   = $row['status'];
            $this->uid      = $row['uid'];
            $this->name     = $row['name'];
            $this->city     = $row['city'];
            $this->email    = $row['email'];
            $this->contact  = $row['contact'];
            $this->role     = $row['role'];
            $this->area     = $row['area'];
            
            return true;
        }
        else{
            return false; 
        }
    }
    public function getdes(){
        return $this->des?$this->des:"NA";
    }
    public function getstatus(){
        return $this->status;
    }
    public function getsid(){
        return $this->sid;
    }
    public function getoaccepted(){
        $sql	= "SELECT COUNT(*) AS oaccepted FROM orders WHERE hid='$this->uid' AND ( status='accepted' OR status='complete' )";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)==1){
            $row    = mysqli_fetch_array($sqlID);
            return $row["oaccepted"];
        }
        else{
            return false;
        }
    }
    public function getorejected(){
        $sql	= "SELECT COUNT(*) AS orejected FROM orders WHERE hid='$this->uid' AND status='rejected'";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)==1){
            $row    = mysqli_fetch_array($sqlID);
            return $row["orejected"];
        }
        else{
            return false;
        }
    }
    public function getts(){
        $sql	= "SELECT name FROM item WHERE hid='$this->uid' AND special=1";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)!=0){
            $row    = mysqli_fetch_array($sqlID);
            $ts=$row['name'];
            while($row    = mysqli_fetch_array($sqlID))
            {
                $ts=$ts.", ".$row['name'];
            }
            return $ts;
        }
        else{
            return "NA";
        }
    }
    public function getsales(){
        $sql	= "SELECT SUM(total) AS sales FROM orders WHERE hid='$this->uid' AND ( status='complete' OR status='accepted' )";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)==1){
            $row    = mysqli_fetch_array($sqlID);
            return $row["sales"];
        }
        else{
            return 0;
        }
    }
}
?>