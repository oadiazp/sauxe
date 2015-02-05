<?php 
abstract class BaseDatPaquete extends Doctrine_Record 
 { 
   public function setTableDefinition() 
    { 
       $this->setTableName('mod_wsb.dat_paquete');
       $this->hasColumn('idpaquete', 'numeric', null, array('notnull' => true, 'primary' => true, 'sequence' => 'mod_wsb.seq_dat_paquete')); 
       $this->hasColumn('autor', 'character varying', 255 , array('notnull' => false, 'primary' => false)); 
       $this->hasColumn('descripcion', 'character varying', 255 , array('notnull' => false, 'primary' => false)); 
       $this->hasColumn('idsolucion', 'numeric', null, array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('nombre', 'character varying', 255 , array('notnull' => true, 'primary' => false)); 
       $this->hasColumn('uri', 'character varying', 255 , array('notnull' => true, 'primary' => false)); 
    } 
 
   public function Setup() 
    { 
       parent::setUp(); 
    } 
 }//fin clase 
?>

