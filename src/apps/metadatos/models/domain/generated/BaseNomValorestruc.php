<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseNomValorestruc extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('nom_valorestruc');
    $this->hasColumn('idfila', 'integer', 8, array('unsigned' => false, 'notnull' => true, 'primary' => true));
    $this->hasColumn('idcampo', 'integer', 8, array('unsigned' => false, 'notnull' => true, 'primary' => true));
    $this->hasColumn('valor', 'string', 255, array('fixed' => false, 'notnull' => false, 'primary' => false));
  }

  public function setUp()
  {
    parent::setUp();
     $this->hasOne('NomFilaestruc',array('local'=>'idfila','foreign'=>'idfila'));
     $this->hasOne('NomCampoestruc',array('local'=>'idcampo','foreign'=>'idcampo'));
   
  }

}