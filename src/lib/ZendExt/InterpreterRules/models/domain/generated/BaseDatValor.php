<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseDatValor extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('dat_valor');
    $this->hasColumn('idhecho', 'decimal', null, array('notnull' => true, 'primary' => true));
    $this->hasColumn('ident', 'decimal', null, array('notnull' => true, 'primary' => true));
    $this->hasColumn('valor', 'string', 100, array('fixed' => false, 'notnull' => false, 'primary' => false));
  }

  public function setUp()
  {
    parent::setUp();
  }

}