<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseSegRolNomDominio extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('seg_rol_nom_dominio');
    $this->hasColumn('idrol', 'decimal', null, array('notnull' => true, 'primary' => true));
    $this->hasColumn('iddominio', 'decimal', null, array('notnull' => true, 'primary' => true));
  }

  public function setUp()
  {
    parent::setUp();
  }

}
