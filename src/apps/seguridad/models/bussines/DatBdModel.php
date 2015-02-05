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
	class DatBdModel extends ZendExt_Model 
	{
		public function DatBdModel()
		{
			parent::ZendExt_Model();
		}
        
		public function insertarnombd($instance)
		{ 
          $instance->save();		       
		}
        
		public function modificarnombd($instance)
		{ 
	      $instance->save();
		}
        
		public function eliminarnombd($instance)
		{ 
	      $instance->delete();
		}
		
	}
?>