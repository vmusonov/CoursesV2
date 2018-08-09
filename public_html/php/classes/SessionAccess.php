<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SessionAccess{
    
    private $keys;
    
    public function __construct(){
        $this->keys=array(
            'id',
            'login',
            'password',
            'admin',
            'curator',
            'teacher',
            'student'
        );
    }
    
    public function SetSession($res){
        for($i=0;$i<count($this->keys);$i++){
            $_SESSION[$this->keys[$i]]=$res[$this->keys[$i]];
        }
    }
    
    public function GetSession(){
        $user=array();
        for($i=0;$i<count($this->keys);$i++){
            $user[count($user)]=$_SESSION[$this->keys[$i]];
        }
        return $user;
    }
    
}

?>

