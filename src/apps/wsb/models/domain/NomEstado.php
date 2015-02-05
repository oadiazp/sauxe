<?php 
class NomEstado extends BaseNomEstado
 { 
   public function setUp() 
    {   parent::setUp(); 
         $this->hasMany('DatServicio', array('local'=>'idestado','foreign'=>'idestado')); 

    } 
 
 
}//fin clase 
?>


