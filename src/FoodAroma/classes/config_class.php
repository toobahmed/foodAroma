<?php

abstract class config{
    protected $db_host    = "localhost";
    protected $db_user    = "root";
    protected $db_pass    = "";
    protected $db_database= "foodaroma";
    protected $con;
    
    protected $salt         ="Itg976F^%FUYGTihy89h7t";
    protected $per_page =1;
    protected $count;
    
    protected $sms_uname="";
    protected $sms_pass="";
    
    protected function connect(){
        $this->con  = mysqli_connect($this->db_host,$this->db_user,$this->db_pass,$this->db_database);
        if(!$this->con) {
            die("<h1>Database Error</h1><h3>".mysqli_error($this->con)."</h3>");
        }
        return true;
    }
    
    protected function hash_password($p) {
        $p=mysqli_real_escape_string($this->con,$p);
        return sha1($p.$this->salt);
    }
    
    public function count($sql){
        $sqlID	= mysqli_query($this->con,$sql);
        return mysqli_num_rows($sqlID);
    }
    public function isnext($i){
        $i=mysqli_real_escape_string($this->con,$i);
        if($this->count>($i*$this->per_page)){
            return true;
        }
        return false;
    }
    
    protected function send_sms($mob_no,$msg){
        $mob_no=mysqli_real_escape_string($this->con,$mob_no);
        $msg=mysqli_real_escape_string($this->con,$msg);
        
        $request="http://www.smszone.in/sendsms.asp?page=SendSmsBulk&username=".$sms_uname."
        &password=".$sms_pass."&number=".$mob_no."&message=".$msg;
        /*
        $http=new HttpRequest("http://www.smszone.in/sendsms.asp",HttpRequest::METH_GET);
        $http->addQueryData(array("page"=>"SendSmsBulk","username"=>$sms_uname,"
        password"=>$sms_pass,"number"=>$mob_no,"message"=>$msg));
        try{
            $http->send();
            if($http->getResponseCode()==200){
                return true;
            }
        }catch (HttpException $ex) {
            return false;
        }
        */
    }
    function getcount(){
        return $this->count;
    }
}
?>