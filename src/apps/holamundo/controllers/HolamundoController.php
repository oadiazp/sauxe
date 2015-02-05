<?php
/**
 * Componente para gestionar las acciones de un sistema.
 * 
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garcia Tejo
 * @author Julio Cesar Garci­a Mosquera   
 * @version 1.0-0
 */
class HolamundoController extends ZendExt_Controller_Secure {
	
	function init ()
	{
		parent::init(false);
	}
	
	function holamundoAction()
	{
		$this->render();
	}
        
	
	
}
