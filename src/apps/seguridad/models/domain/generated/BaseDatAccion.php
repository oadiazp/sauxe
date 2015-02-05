<?php

/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien García Tejo
 * @author Julio Cesar García Mosquera  
 * @version 1.0-0
 */
abstract class BaseDatAccion extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('dat_accion');
    $this->hasColumn('idaccion', 'decimal', null, array ('ntype' => 'numeric','alltypes' =>array(0 =>'decimal',),'notnull' => true,'primary' => true,'sequence' => 'sec_dataccion',));

    $this->hasColumn('icono', 'string', 250, array (
  'ntype' => 'varchar',
  'alltypes' => 
  array (
    0 => 'string',
  ),
  'fixed' => false,
  'notnull' => false,
  'primary' => false,
));

    $this->hasColumn('denominacion', 'string', null, array (
  'ntype' => 'text',
  'alltypes' => 
  array (
    0 => 'string',
    1 => 'clob',
  ),
  'fixed' => false,
  'notnull' => true,
  'primary' => false,
));

    $this->hasColumn('descripcion', 'string', 250, array (
  'ntype' => 'varchar',
  'alltypes' => 
  array (
    0 => 'string',
  ),
  'fixed' => false,
  'notnull' => false,
  'primary' => false,
));

    $this->hasColumn('abreviatura', 'string', null, array (
  'ntype' => 'text',
  'alltypes' => 
  array (
    0 => 'string',
    1 => 'clob',
  ),
  'fixed' => false,
  'notnull' => true,
  'primary' => false,
));
    $this->hasColumn('idfuncionalidad', 'decimal', null, array (
  'ntype' => 'numeric',
  'alltypes' => 
  array (
    0 => 'decimal',
  ),
  'notnull' => true,
  'primary' => false,
));

$this->hasColumn('iddominio', 'decimal', null, array (
  'ntype' => 'numeric',
  'alltypes' => 
  array (
    0 => 'decimal',
  ),
  'notnull' => true,
  'primary' => false,
));
  }


}
