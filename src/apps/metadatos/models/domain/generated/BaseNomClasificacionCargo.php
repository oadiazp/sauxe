<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseNomClasificacionCargo extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('nom_clasificacion_cargo');
    $this->hasColumn('idclasificacion', 'decimal', null, array('notnull' => true, 'primary' => true));
    $this->hasColumn('denominacion', 'string', 35, array('fixed' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('orden', 'decimal', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('fechaini', 'date', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('fechafin', 'date', null, array('notnull' => false, 'primary' => false));
    //$this->hasColumn('version', 'decimal', null, array('notnull' => false, 'default' => '0', 'primary' => false));
  }

  public function setUp()
  {
    parent::setUp();
  }

}
