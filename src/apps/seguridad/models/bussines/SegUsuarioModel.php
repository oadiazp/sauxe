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
  class SegUsuarioModel extends ZendExt_Model
  {
       	public function SegUsuarioModel()
	   	{
	      parent::ZendExt_Model();
	   	}
	   
	   	function insertarservicio($instance) {
	       	 	$instance->save();return true;
	   	}

		public function insertarservidoraut($sistemaservautenticacion) {
		if($sistemaservautenticacion)
			 $sistemaservautenticacion->save();
		}

  		public function insertarperfil($result) {
		foreach($result as $valor){			
			$valor->save();}
            return true;
		}
		
  		public function modificarperfil($resultModificar, $resultEliminar, $resultAdicionar) {
	       	if($resultModificar)
	       	{
		       	foreach($resultModificar as $valor){		       		
		       		$valor->save();}	       		
	       	}
	       	if($resultEliminar)
	       	{
			foreach($resultEliminar as $valor)
				$valor->delete();
	       	}
	       	if($resultAdicionar)
	       	{
			foreach($resultAdicionar as $valor)
				$valor->save();
	       	}
	        return true;
		}
		
  		public function modificarusuario($sistemaservautenticacion,$usuario) {
        $usuario->save();
        if($sistemaservautenticacion)
            {   
                SegUsuarioDatSerautenticacion::eliminarusuarioservidoraut($usuario->idusuario); 
	            $sistemaservautenticacion->save();
            }						
        return true;
		}
        
        public function asignarroles($arrayentidadusuariorolAdd, $arrayentidadusuariorolElim)
        { 
        	if(count($arrayentidadusuariorolElim))
            {   
                foreach($arrayentidadusuariorolElim as $valor)
                    DatEntidadSegUsuarioSegRol::eliminarentidadusuariorol($valor->idusuario,$valor->idrol,$valor->identidad);
            }
        	if(count($arrayentidadusuariorolAdd))
            {   
            	foreach($arrayentidadusuariorolAdd as $valor1)
                	$valor1->save();
            }
            return true;                          
        }
  }
?>