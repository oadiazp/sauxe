<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseHisExcepcion extends HisTraza
{
  public function setTableDefinition()
  {
    $this->setTableName('his_excepcion');
    $this->hasColumn('idtraza', 'decimal', null, array('type' => 'decimal', 'primary' => true));
    $this->hasColumn('codigo', 'string', 50, array('type' => 'string', 'length' => '50'));
    $this->hasColumn('tipo', 'string', 2, array('type' => 'string', 'length' => '2'));
    $this->hasColumn('idioma', 'string', 10, array('type' => 'string', 'length' => '10'));
    $this->hasColumn('mensaje', 'string', 255, array('type' => 'string', 'length' => '255'));
    $this->hasColumn('descripcion', 'string', null, array('type' => 'string'));
    $this->hasColumn('log', 'string', null, array('type' => 'string', 'length' => '255'));
    parent :: setUp ();
  }
}