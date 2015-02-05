<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseDatPuesto extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('dat_puesto');
    $this->hasColumn('idpuesto', 'integer', 8, array('unsigned' => false, 'notnull' => true, 'primary' => true));
    $this->hasColumn('idcargo', 'integer', 8, array('unsigned' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('denominacion', 'string', 255, array('fixed' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('abreviatura', 'string', 50, array('fixed' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('habilidades', 'string', null, array('fixed' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('condiciones', 'string', null, array('fixed' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('acciones', 'string', null, array('fixed' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('riesgos', 'string', null, array('fixed' => false, 'notnull' => false, 'primary' => false));
  }

  public function setUp()
  {
    parent::setUp();
  }

}
