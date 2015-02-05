<?php 
abstract class BaseDatSolucion extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('mod_wsb.dat_solucion');
       $this->hasColumn('idsolucion', 'numeric', null, array('notnull' => true, 'primary' => true, 'sequence' => 'mod_wsb.seq_dat_solucion')); 
       $this->hasColumn('config', 'character varying', 255 , array('notnull' => false, 'primary' => false)); 
       $this->hasColumn('nombre', 'character varying', 255 , array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('path', 'character varying', 255 , array('notnull' => true, 'primary' => false)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase 
?>

