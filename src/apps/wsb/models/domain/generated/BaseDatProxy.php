<?php 
abstract class BaseDatProxy extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('mod_wsb.dat_proxy');
       $this->hasColumn('idproxy', 'numeric', null, array('notnull' => true, 'primary' => true, 'sequence' => 'mod_wsb.seq_dat_proxy')); 
       $this->hasColumn('descripcion', 'character varying', 255 , array('notnull' => false, 'primary' => false)); 
       $this->hasColumn('nombre', 'character varying', 255 , array('notnull' => true, 'primary' => false)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase 
?>

