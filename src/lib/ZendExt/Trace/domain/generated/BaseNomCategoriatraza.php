<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseNomCategoriatraza extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('mod_traza.nom_categoriatraza');
    $this->hasColumn('idcategoriatraza', 'decimal', null, array('notnull' => true, 'primary' => true, 'sequence' => 'mod_traza.sec_categoriatraza_seq'));
    $this->hasColumn('denominacion', 'string', 100, array('fixed' => false, 'notnull' => false, 'primary' => false));
  }

  public function setUp()
  {
    parent::setUp();
  }

}
