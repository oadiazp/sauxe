<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseDatHechos extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('dat_hechos');
    $this->hasColumn('idhecho', 'decimal', null, array('notnull' => true, 'primary' => true));
    $this->hasColumn('descripcion', 'string', 255, array('fixed' => false, 'notnull' => false, 'primary' => false));
	$this->hasColumn('abreviatura', 'string', 255, array('fixed' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('idsubsistema', 'decimal', null, array('notnull' => true, 'primary' => false));
    $this->hasColumn('idtipodato', 'decimal', null, array('notnull' => false, 'primary' => false));	
  }

  public function setUp()
  {
    parent::setUp();
  }

}
