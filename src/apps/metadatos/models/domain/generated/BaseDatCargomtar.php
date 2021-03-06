<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseDatCargomtar extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('dat_cargomtar');
    $this->hasColumn('idcargo', 'integer', 8, array('unsigned' => false, 'notnull' => true, 'primary' => true));
    $this->hasColumn('idcargomilitar', 'numeric', 8, array('unsigned' => false, 'notnull' => true, 'primary' => false));
    $this->hasColumn('idgradomilit', 'numeric', 8, array('unsigned' => false, 'notnull' => true, 'primary' => false));
    $this->hasColumn('salario', 'numeric', 8, array('notnull' => false, 'primary' => false));
    $this->hasColumn('escadmando', 'numeric', 8, array('notnull' => false, 'primary' => false));
  }

  public function setUp()
  {
    parent::setUp();
   $this->hasOne("DatCargo",array('local'=>'idcargo','foreign'=>'idcargo'));
   $this->hasOne("NomCargomilitar",array('local'=>'idcargomilitar','foreign'=>'idcargomilitar'));
   $this->hasOne("NomCargomilitar as Asignacion",array('local'=>'idcargomilitar','foreign'=>'idcargomilitar'));
   $this->hasOne("NomGradomilit",array('local'=>'idgradomilit','foreign'=>'idgradomilit'));
  }

}
