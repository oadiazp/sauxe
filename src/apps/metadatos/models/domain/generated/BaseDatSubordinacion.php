<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseDatSubordinacion extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('dat_subordinacion');
    $this->hasColumn('idpadre', 'integer', 8, array('unsigned' => false, 'notnull' => true, 'primary' => true));
    $this->hasColumn('idhijo', 'integer', 8, array('unsigned' => false, 'notnull' => true, 'primary' => true));
    $this->hasColumn('idnomsubordinacion', 'integer', 8, array('unsigned' => false, 'notnull' => true, 'primary' => false));
  }

  public function setUp()
  {
    parent::setUp();
     $this->hasMany("NomFilaestruc as padre",array('local'=>'idpadre','foreign'=>'idfila'));
     $this->hasMany("NomFilaestruc as hijo",array('local'=>'idhijo','foreign'=>'idfila'));
     $this->hasOne("NomSubordinacion",array('local'=>'idnomsubordinacion','foreign'=>'idnomsubordinacion'));
    
  }

}