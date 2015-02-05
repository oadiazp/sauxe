<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseNomValorDefecto extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('nom_valor_defecto');
    $this->hasColumn('idvalordefecto', 'integer', 8, array('unsigned' => false, 'notnull' => true, 'primary' => true));
    $this->hasColumn('valorid ', 'string', 255, array('fixed' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('valor', 'text', 1, array('notnull' => true, 'primary' => false));
    $this->hasColumn('idcampo', 'integer', 8, array('notnull' => true, 'primary' => false));
  }

  public function setUp()
  {
    parent::setUp();
    
  }

}
