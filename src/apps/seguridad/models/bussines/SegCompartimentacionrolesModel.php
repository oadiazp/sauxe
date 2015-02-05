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
	class SegCompartimentacionrolesModel extends ZendExt_Model
	  {
        public function SegCompartimentacionrolesModel()
        {
          parent::ZendExt_Model();
        }
		   
        public function insertarRolesDominio($arrayIns, $arrayElim)
        {
			if(count($arrayElim))
		        foreach ($arrayElim as $objElim){
			        SegCompartimentacionroles::eliminarRolesDominio($objElim->idrol,$objElim->iddominio);}
        	if(count($arrayIns)) 
	        	foreach ($arrayIns as $obj){
	        		$obj->save();}	        
			return true;
        }	
	  }













?>