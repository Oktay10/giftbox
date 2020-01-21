<?php
namespace giftBoxApp\model;
class Cagnotte extends \Illuminate\Database\Eloquent\Model {

       protected $table      = 'cagnotte';  
       protected $primaryKey = 'id';     
       public    $timestamps = false;  
       
       public function coffret()
       {
           return $this->belongsTo('giftBoxApp\model\Coffret', 'id_cof');
       }
       
}