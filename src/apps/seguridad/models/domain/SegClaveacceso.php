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
class SegClaveacceso extends BaseSegClaveacceso
{

	public function setUp()
  {
    parent::setUp();    
    $this->hasOne('SegUsuario',array('local'=>'idusuario','foreign'=>'idusuario'));   
  }

}