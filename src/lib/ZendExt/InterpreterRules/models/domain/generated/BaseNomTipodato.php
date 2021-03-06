<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseNomTipodato extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('nom_tipodato');
    $this->hasColumn('idtipodato', 'decimal', null, array('notnull' => true, 'primary' => true));
    $this->hasColumn('tipocampo', 'string', 100, array('fixed' => false, 'notnull' => true, 'primary' => false));
    $this->hasColumn('regex', 'string', 255, array('fixed' => false, 'notnull' => true, 'primary' => false));
  }

  public function setUp()
  {
    parent::setUp();
  }

}
