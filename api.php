<?php
require 'restful_api.php';
require './class/database.php';
require './class/user.php';
class api extends restful_api {
	function __construct(){
		parent::__construct();
	}
	function user(){
        $db= new Database('root','','localhost','user'); 
        $user= new user($db);//
        $result = array(
            'status' => '0'
        );
		if ($this->method == 'GET'){
            if(empty($this->params)){
                $alluser = $user->GetAllUsers();
                if(count($alluser) > 0){
                $result['status'] = '1';
                $result['data'] = $alluser;
                } 
                else{
                $result['detail'] = 'Kullanici kaydi henuz yok!';
            }
            }
            else {
                $auser = $user->GetUser($this->params['0']);
                if($auser){
                    $result['status'] = '1';
                    $result['data'] = $auser;
                }
                else{
                    $result['detail'] = 'Kullanici Bulunmuyor!';
            }
            }
        }
		 elseif ($this->method == 'POST'){
            $username=$this->params['username'];
            $password=$this->params['password'];
            $email=$this->params['email'];
            $profileName=$this->params['profileName'];
            if(!$user->Exist($username)){
            $user->AddUser($username,$password,$email,$profileName);
            $result['status'] = '1';
            $result['detail'] = 'Kullanici Kaydedildi!';
            }
            else{
            $result['detail'] = 'Kullanici Bulunmuyor!';
            }   
        }
		 elseif ($this->method == 'PUT'){
            $input=array();
            $data=explode('&',$this->file);
            foreach($data as $val){
                $tmp=explode('=',$val);
                $input[$tmp[0]]=$tmp[1];
            }
            $username=$input['username'];
            $password=$input['password'];
            $email=$input['email'];
            $profileName=$input['profileName'];
            $auser = $user->ChangeUser($username,$password,$email,$profileName);
                if($auser){
            $result['status'] = '1';
            $result['detail'] = 'Kullanici Profili Degistirildi!';
            }
            else{
            $result['detail'] = 'Aradıgınız Kullanici Bulunmuyor!';
            }
        }
		 elseif ($this->method == 'DELETE'){
             if(!empty($this->params)){
                 $auser = $user->DelUser($this->params['0']);
                 if($auser){
                    $result['status'] = '1';
                    $result['detail'] = 'Kullanici Silindi!';
                 }else{
                    $result['detail'] = 'Kullanici Bulunmuyor!';
                 }
             }
		 }
         $this->response(200,$result);
	}
    
}
$user = new api();
?>