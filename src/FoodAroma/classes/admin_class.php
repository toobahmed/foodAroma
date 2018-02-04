<?php
class admin extends config{
    private $aid, $uname, $pass;
    public function admin(){
        $this->connect();
    }
    public function getvalues($POST){
        $this->uname    = mysqli_real_escape_string($this->con,$POST['auname']);
        $this->pass     = mysqli_real_escape_string($this->con,$POST['apass']);
    }
    public function insert(){
        
        
        $sql	= "INSERT INTO admin(uname,pass) VALUES ('$this->uname','".$this->hash_password($this->pass)."')";
        $sqlID	= mysqli_query($this->con,$sql);
        if($sqlID){
            return true;
        }
        else{
            return false;
        }
    }
    public function login($uname,$pass){
        $uname=mysqli_real_escape_string($this->con,$uname);
        $pass=mysqli_real_escape_string($this->con,$pass);
        
        $sql	= "SELECT * FROM admin WHERE uname='$uname' AND pass='".$this->hash_password($pass)."'";
        $sqlID	= mysqli_query($this->con,$sql);
        if(mysqli_num_rows($sqlID)==1){
            
            $row    = mysqli_fetch_array($sqlID);
            
                $this->aid=$row['aid'];
                
                return true;
            
            
        }
        else{
            return false;
        }
    }
    public function update($pass){
        $this->pass=mysqli_real_escape_string($this->con,$pass);
        
        $sql        = "UPDATE admin SET pass='".hash_password($this->pass)."'";
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
    public function getaid(){
        return $this->aid;
    }
    
    public function getuname(){
        return $this->uname;
    }
    
    public function getpass(){
        return $this->pass;
    }
    public function report(){
        return mysqli_error($this->con);
    }
}
?>