<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseNomGrupocomple extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('nom_grupocomple');
    $this->hasColumn('idgrupocomplejidad', 'integer', 8, array('unsigned' => false, 'notnull' => true, 'primary' => true));
    $this->hasColumn('denominacion', 'string', 20, array('fixed' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('abreviatura', 'integer', 2, array('unsigned' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('idescalasalarial', 'integer', 8, array('notnull' => false, 'primary' => false));
    $this->hasColumn('salarioescala', 'integer', 8, array('notnull' => false, 'primary' => false));
    $this->hasColumn('fechaini', 'date', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('fechafin', 'date', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('orden', 'decimal', null, array('notnull' => false, 'primary' => false));
  }

  public function setUp()
  {
    parent::setUp();
    $this->hasOne("NomEscalasalarial",array('local'=>'idescalasalarial','foreign'=>'idescalasalarial'));
    $this->hasMany("NomCargocivil",array('local'=>'idgrupocomplejidad','foreign'=>'idgrupocomplejidad'));
    
  }

}
