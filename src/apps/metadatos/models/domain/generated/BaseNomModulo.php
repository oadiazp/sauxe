<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseNomModulo extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('nom_modulo');
    $this->hasColumn('idmodulo', 'integer', 2, array('unsigned' => false, 'notnull' => true, 'primary' => true));
    $this->hasColumn('denmodulo', 'string', 35, array('fixed' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('orden', 'integer', 5, array('fixed' => true, 'notnull' => false, 'primary' => false));
    $this->hasColumn('fechaini', 'date', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('fechafin', 'date', null, array('notnull' => false, 'primary' => false));
  }

  public function setUp()
  {
    parent::setUp();
  }

}
