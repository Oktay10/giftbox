<?php
namespace giftBoxApp\auth;

use giftBoxApp\model\User;
use mf\auth\exception\AuthentificationException;
use mf\auth\Authentification;

class GiftBoxAuthentification extends \mf\auth\Authentification {

    const ACCESS_LEVEL_USER  = 100;   
    const ACCESS_LEVEL_ADMIN = 200;

    /* constructeur */
    public function __construct(){
        parent::__construct();
    }
    
    public function createUser($username, $pass, $fullname,
                               $level=self::ACCESS_LEVEL_USER) {
            try{

                    $userCheck = User::where('username','=',$username)->first();
                    if(isset($userCheck)){
                        if($userCheck->username == $username)
                            throw new AuthentificationException('username already exit !');
                        return false;
                    }else{
                        $newUser = new User();
                        $newUser->username = $username;
                        $newUser->password = password_hash($pass,PASSWORD_DEFAULT);
                        $newUser->fullname = $fullname;
                        $newUser->level = $level;
                        $newUser->save();
                        return true;
                    }
            }catch(AuthentificationException $e){

            }
    }

    
    public function loginUser($username, $password){
                try{

                    $userCheck = User::where('username','=',$username)->first();
                    if(isset($userCheck)){
                        if($userCheck->username != $username){
                            throw new AuthentificationException('username does not exit !');
                            return false;
                        }else{
                            
                            return $this->login($username,$userCheck->password,$password,$userCheck->level);
                        }
                    }
                    
                }catch(AuthentificationException $e){

                }
    }

}