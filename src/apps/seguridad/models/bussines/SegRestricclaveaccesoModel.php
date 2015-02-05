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
	class SegRestricclaveaccesoModel extends ZendExt_Model 
	{
	public function SegRestricclaveaccesoModel()
		{
			parent::ZendExt_Model();
		}
	function insertarclave($clave)
		{ 
			try
	       	 {
	       	 	$clave->save();
	       	 	return true;
		     }
	       	 catch(Doctrine_Exception $ee)
	         {
	         	throw $ee;
	         }					
		}
	function modificarclave($clave)
		{ 
			try
	       	 {
	       	 	$clave->save();
	        	return true;
	         }
	       	 catch(Doctrine_Exception $ee)
	         {
	            throw $ee;
	         }				
		}
	function eliminarclave($clave)
		{ 
			try
	       	 {
	       	 	$clave->delete();
	        	return true;
	         }
	       	 catch(Doctrine_Exception $ee)
	         {
	            throw $ee;
	         }				
		}
		
	}
?>