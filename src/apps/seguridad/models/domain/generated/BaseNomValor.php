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
abstract class BaseNomValor extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('nom_valor');
    $this->hasColumn('idvalor', 'decimal', null, array (
  'ntype' => 'numeric',
  'alltypes' => 
  array (
    0 => 'decimal',
  ),
  'notnull' => true,
  'primary' => true,
  'sequence' => 'sec_nomvalor',
));

    $this->hasColumn('idfila', 'decimal', null, array (
  'ntype' => 'numeric',
  'alltypes' => 
  array (
    0 => 'decimal',
  ),
  'notnull' => false,
  'primary' => false,
));

    $this->hasColumn('idcampo', 'decimal', null, array (
  'ntype' => 'numeric',
  'alltypes' => 
  array (
    0 => 'decimal',
  ),
  'notnull' => false,
  'primary' => false,
));
    $this->hasColumn('valor', 'string', 250, array (
  'ntype' => 'varchar',
  'alltypes' => 
  array (
    0 => 'string',
  ),
  'fixed' => false,
  'notnull' => false,
  'primary' => false,
));
  }


}