<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseNomTipotraza extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('nom_tipotraza');
    $this->hasColumn('idtipotraza', 'decimal', null, array('notnull' => true, 'primary' => true, 'sequence' => 'mod_traza.sec_tipotraza_seq'));
    $this->hasColumn('tipotraza', 'string', 100, array('fixed' => false, 'notnull' => false, 'primary' => false));
  }

  public function setUp()
  {
    parent::setUp();
  }

}
