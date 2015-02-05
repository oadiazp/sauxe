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
class NomDesktopModel extends ZendExt_Model 
{

	public function NomDesktopModel()
	{
	   	parent::ZendExt_Model();       
	}
    
	function insertardesktop($desktop)
	{
	   $desktop->save();
	}
    
    function modificardesktop($desktop)
	{ 
	  $desktop->save();
	}
    
    function eliminardesktop($desktop)
	{ 
	  $desktop->delete();
	}

}