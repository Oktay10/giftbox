<?php
namespace giftBoxApp\model;
class User extends \Illuminate\Database\Eloquent\Model {

       protected $table      = 'user';  
       protected $primaryKey = 'id';     
       public    $timestamps = false;  
       
       public function coffrets()
       {
            return $this->hasMany('giftBoxApp\model\Coffret', 'id_user');
       }

       
}