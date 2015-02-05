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
	class SegCompartimentacionusuarioModel extends ZendExt_Model
	  {
        public function SegCompartimentacionusuarioModel()
        {
          parent::ZendExt_Model();
        }
		   
        public function insertarUsuarioDominio($arrayIns, $arrayElim)
        {
			if(count($arrayElim))
		        foreach ($arrayElim as $objElim){
			        SegCompartimentacionusuario::eliminarUsuarioDominio($objElim->idusuario,$objElim->iddominio);}
        	if(count($arrayIns)) 
	        	foreach ($arrayIns as $obj){
	        		$obj->save();}	        
			return true;
        }	
	  }













?>