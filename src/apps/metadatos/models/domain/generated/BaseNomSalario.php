<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseNomSalario extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('nom_salario');
    $this->hasColumn('idsalario', 'decimal', null, array('notnull' => true, 'primary' => true));
    $this->hasColumn('idgrupocomplejidad', 'decimal', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('idescalasalarial', 'decimal', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('salario', 'decimal', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('fechaini', 'date', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('fechafin', 'date', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('tarifa', 'decimal', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('orden', 'decimal', null, array('notnull' => false, 'primary' => false));
  }

  public function setUp()
  {
  	
    parent::setUp();
   $this->hasOne("NomGrupocomple", array('local'=>'idgrupocomplejidad','foreign'=>'idgrupocomplejidad'));
    $this->hasOne("NomEscalasalarial", array('local'=>'idescalasalarial','foreign'=>'idescalasalarial'));
  }

}
