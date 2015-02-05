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
class NomTemaModel extends ZendExt_Model 
{

	public function NomTemaModel()
	{
	   		parent::ZendExt_Model();       
	    }
	    
	function insertartema($tema)
    {
	      $tema->save();
	    }
	    
    function modificartema($tema)
	{ 
	  $tema->save();
	}
		
    function eliminartema($tema)
	{ 
	       	$tema->delete();
	}

}