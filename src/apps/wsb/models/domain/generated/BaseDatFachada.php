<?php 
abstract class BaseDatFachada extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('mod_wsb.dat_fachada');
       $this->hasColumn('idfachada', 'numeric', null, array('notnull' => true, 'primary' => true, 'sequence' => 'mod_wsb.seq_dat_fachada')); 
       $this->hasColumn('idpaquete', 'numeric', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('idservicio', 'numeric', null, array('notnull' => true, 'primary' => false)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase 
?>

