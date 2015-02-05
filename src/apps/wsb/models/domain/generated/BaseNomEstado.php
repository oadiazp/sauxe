<?php 
abstract class BaseNomEstado extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('mod_wsb.nom_estado');
       $this->hasColumn('idestado', 'numeric', null, array('notnull' => true, 'primary' => true, 'sequence' => 'mod_wsb.seq_nom_estado')); 
       $this->hasColumn('nombre', 'character varying', 255 , array('notnull' => true, 'primary' => false)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase 
?>

