<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class BaseHisAccion extends HisTraza
{
  public function setTableDefinition()
  {
    $this->setTableName('his_accion');
    $this->hasColumn('idtraza', 'decimal', null, array('type' => 'decimal', 'primary' => true));
    $this->hasColumn('referencia', 'string', 200, array('type' => 'string', 'length' => '200'));
    $this->hasColumn('controlador', 'string', 200, array('type' => 'string', 'length' => '200'));
    $this->hasColumn('accion', 'string', 200, array('type' => 'string', 'length' => '200'));
    $this->hasColumn('inicio', 'decimal', null, array('type' => 'decimal'));
    $this->hasColumn('falla', 'decimal', null, array('type' => 'decimal'));
    parent::setUp();
  }

}