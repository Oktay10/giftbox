<?php
namespace giftBoxApp\model;
class Coffret extends \Illuminate\Database\Eloquent\Model {

       protected $table      = 'coffret';  
       protected $primaryKey = 'id';     
       public    $timestamps = false;  
       
       public function user()
       {
           return $this->belongsTo('giftBoxApp\model\User', 'id_user');
       }

       public function prestations()
       {
           return $this->belongsToMany('giftBoxApp\model\Prestation', 'coff_pres', 'id_coffret', 'id_prestation');
       }

       
}