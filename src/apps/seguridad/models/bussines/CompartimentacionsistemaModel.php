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
class CompartimentacionsistemaModel extends ZendExt_Model
	{
    public function CompartimentacionsistemaModel() {
          parent::ZendExt_Model();
        }
		
	public function gestionarSistemasFuncAcc($arraySistemas, $arrayFuncionalidades, $arrayAcciones, $arraySistEliminar, $arrayFuncEliminar, $arrayAccEliminar, $iddominio){
			if(count($arraySistemas))
				$this->insertarCompartimentacionObj('DatSistemaCompartimentacion', 'idsistema', $iddominio, $arraySistemas);
			if($arrayFuncionalidades)
				$this->insertarCompartimentacionObj('DatFuncionalidadCompartimentacion', 'idfuncionalidad', $iddominio, $arrayFuncionalidades);
			if($arrayAcciones)
				$this->insertarCompartimentacionObj('DatAccionCompartimentacion', 'idaccion', $iddominio, $arrayAcciones);
			if(count($arraySistEliminar))
				DatSistemaCompartimentacion::eliminarSistemas($arraySistEliminar,$iddominio);
			if(count($arrayFuncEliminar))
				DatFuncionalidadCompartimentacion::eliminarFuncionalidades($arrayFuncEliminar,$iddominio);
			if(count($arrayAccEliminar)) {
				$rolesDominio = SegRolNomDominio::RolesdelDominio($iddominio);
				$rolesDominio = $this->arrayBidimencionalToUnidimencional($rolesDominio);
				DatAccionCompartimentacion::eliminarAcciones($arrayAccEliminar,$iddominio);
				DatSistemaSegRolDatFuncionalidadDatAccion::eliminarAccionesAutorizadas($arrayAccEliminar,$rolesDominio);
			}
		}
		
	private function insertarCompartimentacionObj($nameObj, $nameId, $iddominio, $arrayIdObj) {
        $Obj= array();
		foreach ($arrayIdObj as $key => $id){
			$Obj[$key] = new $nameObj();
		    $Obj[$key]->$nameId = $id;
		    $Obj[$key]->iddominio = $iddominio;
		    $Obj[$key]->save();
			}
        }
        
     private function arrayBidimencionalToUnidimencional($arrayRoles){
     	$array = array();
     	foreach($arrayRoles as $rol){
     		$array[] = $rol['idrol'];
     	}
     	return $array;
     } 
       
	}













?>