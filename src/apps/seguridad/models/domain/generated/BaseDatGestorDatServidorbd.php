<?php

/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garc�a Tejo
 * @author Julio Cesar Garc�a Mosquera  
 * @version 1.0-0
 */
abstract class BaseDatGestorDatServidorbd extends Doctrine_Record
{

  public function setTableDefinition()
  {
    $this->setTableName('dat_gestor_dat_servidorbd');
    $this->hasColumn('idservidor', 'decimal', null, array (
  'ntype' => 'numeric',
  'alltypes' => 
  array (
    0 => 'decimal',
  ),
  'notnull' => true,
  'primary' => true,
));
    $this->hasColumn('idgestor', 'decimal', null, array (
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