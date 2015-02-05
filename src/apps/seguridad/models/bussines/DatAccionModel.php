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
	class DatAccionModel extends ZendExt_Model 
	{
		public function DatAccionModel()
		{
			parent::ZendExt_Model();
		}
        
		public function insertaraccion($accion)
		{ 
	       	 	$accion->save();return $accion->idaccion;
		}
        
		public function modificaraccion($instance)
		{ 
	       	 	$instance->save();return true;
		}
        
		public function eliminaraccion($instance)
		{ 
	       	 	$instance->delete();return true;
		}
	}
?>