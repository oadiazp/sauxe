<?php 
abstract class BaseDatServicio extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('mod_wsb.dat_servicio');
       $this->hasColumn('idservicio', 'numeric', null, array('notnull' => true, 'primary' => true, 'sequence' => 'mod_seguridad.sec_datservicio')); 
       $this->hasColumn('descripcion', 'character varying', 255 , array('notnull' => false, 'primary' => false)); 
       $this->hasColumn('idestado', 'numeric', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('nombre', 'character varying', 255 , array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('uri', 'character varying', 255 , array('notnull' => true, 'primary' => false)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase 
?>

