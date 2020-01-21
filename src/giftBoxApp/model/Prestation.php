<?php
namespace giftBoxApp\model;
class Prestation extends \Illuminate\Database\Eloquent\Model {

       protected $table      = 'prestation';  
       protected $primaryKey = 'id';     
       public    $timestamps = false;  
       
       public function categorie()
       {
           return $this->belongsTo('giftBoxApp\model\Categorie', 'id_cat');
       }

       
}