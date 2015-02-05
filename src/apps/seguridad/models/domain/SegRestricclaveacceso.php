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
  class SegRestricclaveacceso extends BaseSegRestricclaveacceso
{
  public function setUp()
  {
    parent::setUp();      
  }

   static	public function cargarclave($limit,$start)
		{
	            $query = Doctrine_Query::create();
	            $fndes = $query->select('a.idrestricclaveacceso,a.minimocaracteres,a.numerica,a.signos,a.alfabetica,a.diascaducidad')->from('SegRestricclaveacceso a')->orderby('idrestricclaveacceso')->limit($limit)->offset($start)->execute();
	            return $fndes;
		}
        
   static function obtenerclave()
		{
			  $query = Doctrine_Query::create();
	            $cantFndes = $query->select('COUNT(a.idrestricclaveacceso) cant')->from('SegRestricclaveacceso a')
	            				   ->execute();
	            return $cantFndes[0]['cant'];
		}  
		

}