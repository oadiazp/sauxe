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
	class DatSistemaModel extends ZendExt_Model 
	{
		public function DatSistemaModel()
		{
			parent::ZendExt_Model();
		}
		
		public function insertarsistema($arrayObjServidores,$sistema)
		{ 
	       	 	$sistema->save();
	       	 	if($arrayObjServidores)
	       	 	{
		       		foreach ($arrayObjServidores as $servidor)
		       		{	
		       	 		$servidor->save();
		       		}
	       	 	}
		}
        
        public function modificarsistema($arrayObjServidores,$sistema) {
              $sistema->save();                                                
               if (count($arrayObjServidores) > 0) {
                 foreach ($arrayObjServidores as $servidor)
                    $servidor->save();
               }
        }
	}
?>