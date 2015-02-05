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
	class NomCampoModel extends ZendExt_Model 
	{
		public function NomCampoModel()
		{
			parent::ZendExt_Model();
		}
        
		function insertarcampo($campo)
		{ 
			 	$campo->save();return true;
		}
        
		function modificarcampo($campo)
		{ 
	       	 	$campo->save();return true;
		}
        
		function eliminarcampo($campo)
		{ 
	       	 	$campo->delete();return true;
		}
		
	}
?>