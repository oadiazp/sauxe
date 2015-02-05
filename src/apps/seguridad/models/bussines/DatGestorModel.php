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
	class DatGestorModel extends ZendExt_Model 
	{
		public function DatGestorModel()
		{
			parent::ZendExt_Model();
		}
        
		function insertarnomgestor($gestor)
		{
	      $gestor->save();	       	 	
		}
        
		function modificarnomgestor($instance)
		{ 
	      $instance->save();
		}
        
		function eliminarnomgestor($instance)
		{ 
	      $instance->delete();
		}
	}
?>