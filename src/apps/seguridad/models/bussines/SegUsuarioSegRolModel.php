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
	class SegUsuarioSegRolModel extends ZendExt_Model
	  {
        public function SegUsuarioSegRolModel()
        {
          parent::ZendExt_Model();
        }
		   
        public function moduserrol($usuariorol)
        { 
        $usuariorol->save();
        return true;
        }	
	  }













?>