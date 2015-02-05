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
	class DatFuncionalidadModel extends ZendExt_Model 
	{
		public function DatFuncionalidadModel()
		{
			parent::ZendExt_Model();
		}
        
		function insertarfuncionalidad($instance)
		{ 
	      $instance->save();
	      return $instance->idfuncionalidad;
		}
        
		function modificarfuncionalidad($instance)
		{ 
	      $instance->save();
		}
        
		function eliminarfuncionalidad($instance)
		{ 
	      $instance->delete();
		}
				
	}
?>