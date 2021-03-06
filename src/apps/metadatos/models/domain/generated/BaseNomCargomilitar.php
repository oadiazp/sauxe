<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseNomCargomilitar extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('nom_cargomilitar');
    $this->hasColumn('idcargomilitar', 'integer', 4, array('unsigned' => false, 'notnull' => true, 'primary' => true));
    $this->hasColumn('idprepmilitar', 'integer', 2, array('unsigned' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('idespecialidad', 'integer', 2, array('unsigned' => false, 'notnull' => true, 'primary' => false));
    $this->hasColumn('dencargomilitar', 'string', 60, array('fixed' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('abrevcargomilitar', 'string', 35, array('fixed' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('orden', 'integer', 2, array('unsigned' => false, 'notnull' => false, 'primary' => false));
    $this->hasColumn('fechaini', 'date', null, array('notnull' => false, 'primary' => false));
    $this->hasColumn('fechafin', 'date', null, array('notnull' => false, 'primary' => false));
  }

  public function setUp()
  {
    parent::setUp();
     $this->hasOne("NomPrepmilitar",array('local'=>'idprepmilitar','foreign'=>'idprepmilitar'));
     $this->hasOne("NomEspecialidad",array('local'=>'idespecialidad','foreign'=>'idespecialidad'));
     $this->hasMany("DatCargomtar as Asignacion" ,array('local'=>'idcargomilitar','foreign'=>'idcargomilitar'));
     $this->hasMany("DatCargomtar" ,array('local'=>'idcargomilitar','foreign'=>'idcargomilitar'));
   
  }

}
