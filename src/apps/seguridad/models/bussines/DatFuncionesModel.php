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
	class DatFuncionesModel extends ZendExt_Model 
	{
		public function DatFuncionesModel()
		{
			parent::ZendExt_Model();
		}
        
		public function insertarfuncion($funcion)
		{ 
			$funcion->save();
		}
        
		public function modificarfuncion($instance)
		{ 
	       	 	$instance->save();
		}
        
		public function eliminarfuncion($instance)
		{ 
	       	 	$instance->delete();
		}
	}
?>