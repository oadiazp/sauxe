<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseNomPrepmilitar extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('nom_prepmilitar');
    $this->hasColumn('idprepmilitar', 'integer', 2, array('unsigned' => false, 'notnull' => true, 'primary' => true));
    $this->hasColumn('denprepmilitar', 'string', 35, array('fixed' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('abrevprepmilitar', 'string', 10, array('fixed' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('orden', 'integer', 2, array('unsigned' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('fechaini', 'date', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('fechafin', 'date', null, array('notnull' => false, 'primary' => false));
  }

  public function setUp()
  {
    parent::setUp();
  }

}