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
	class DatServidorDatGestorDatBdDatEsquemaModel extends ZendExt_Model 
	{
		public function DatServidorDatGestorDatBdDatEsquemaModel()
		{
			parent::ZendExt_Model();
		}
        
		function insertarservidorgestorbdesquema($servgestorbdesquema)
		{ 
	       	 	$servgestorbdesquema->save();
		}		
	}
?>