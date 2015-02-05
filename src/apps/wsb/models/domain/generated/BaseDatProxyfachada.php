<?php 
abstract class BaseDatProxyfachada extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('mod_wsb.dat_proxyfachada');
       $this->hasColumn('idproxyfachada', 'numeric', null, array('notnull' => true, 'primary' => true, 'sequence' => 'mod_wsb.seq_dat_proxyfachada')); 
       $this->hasColumn('idfachada', 'numeric', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('idproxy', 'numeric', null, array('notnull' => true, 'primary' => false)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase 
?>

