<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseNomCategocup extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('nom_categocup');
    $this->hasColumn('idcategocup', 'numeric', 10, array('unsigned' => false, 'notnull' => true, 'primary' => true));
    $this->hasColumn('dencategocup', 'string', 20, array('fixed' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('orden', 'integer', 2, array('unsigned' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('fechaini', 'date', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('fechafin', 'date', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('abreviatura', 'string', 255, array('fixed' => false, 'notnull' => false, 'primary' => false));
  }

  public function setUp()
  {
    parent::setUp();
  }

}
