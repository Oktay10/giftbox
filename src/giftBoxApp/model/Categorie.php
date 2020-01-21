<?php
namespace giftBoxApp\model;
class Categorie extends \Illuminate\Database\Eloquent\Model {

       protected $table      = 'categorie';  
       protected $primaryKey = 'id';     
       public    $timestamps = false;  
       
       public function prestations()
       {
            return $this->hasMany('giftBoxApp\model\Prestation', 'id_cat');
       }

       
}