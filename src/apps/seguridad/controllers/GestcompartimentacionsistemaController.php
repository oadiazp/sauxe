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
	class GestcompartimentacionsistemaController extends ZendExt_Controller_Secure
	{
		function init (){
			parent::init();
		}	

		function gestcompartimentacionsistemaAction(){
			$this->render();
		}
		
        function cargarArbolDominiosAction(){        	
        	$iddominio = $this->_request->getPost('node');
        	$arrayDominio = $this->integrator->metadatos->cargarArbolDominios($iddominio);
        	$arrayDominioSinChecked = array();
        	foreach ($arrayDominio as $dominio) {
        		unset($dominio['checked']);
        		$arrayDominioSinChecked[] = $dominio;
        		
        	}        		
        	echo json_encode($arrayDominioSinChecked); return;
        }
        
        function cargarSistFuncAccAction() {
        	$idnodo = $this->_request->getPost('node');
			$idsistema = $this->_request->getPost('idsistema');
			$idfuncionalidad = $this->_request->getPost('idfuncionalidad');
			$iddominio = $this->_request->getPost('iddominio');
			if($idnodo == 0)
				$sistemas = DatSistema::cargarsistema($idnodo);					
			elseif ($idsistema) 
				$sistemas = DatSistema::cargarsistema($idsistema);                        
            $contador = 0;
            $sistemafunArr = array();
			if(isset($sistemas) && $sistemas->count()) {
				foreach ($sistemas as $valores=>$valor) {
					$sistemafunArr[$contador]['id'] = $valor->id.'_'.$idnodo;
					$sistemafunArr[$contador]['idsistema'] = $valor->id;
					$sistemafunArr[$contador]['text'] = $valor->text;
                    $contador++;							
				}
			}
			if ($idsistema && !count($sistemafunArr)) {
				$funcionalidad = DatFuncionalidad::cargarFuncionalidadesCompartimentacion($idsistema, $iddominio);
				if($funcionalidad->getData() != NULL) {
					foreach ($funcionalidad as $valores=>$valor) {
						$sistemafunArr[$contador]['id'] = $valor->id.'_'.$idnodo;
						$sistemafunArr[$contador]['idfuncionalidad'] = $valor->id;
						$sistemafunArr[$contador]['text'] = $valor->text;
						//echo json_encode($valor->toArray(true));die;
						$sistemafunArr[$contador]['checked'] = ($valor->DatFuncionalidadCompartimentacion->getData() != null) ? true : false;
                        $contador++;
					}						 
				}
			}
        	if ($idfuncionalidad) {
				$funcionalidad = DatAccion::cargarAccionesCompartimentacion($idfuncionalidad,$iddominio);
				if($funcionalidad->getData() != NULL) {
					foreach ($funcionalidad as $valores=>$valor) {
						$sistemafunArr[$contador]['id'] = $valor->idaccion.'_'.$idnodo;
						$sistemafunArr[$contador]['idaccion'] = $valor->idaccion;
						$sistemafunArr[$contador]['text'] = $valor->denominacion;
						$sistemafunArr[$contador]['leaf'] = true;
						$sistemafunArr[$contador]['checked'] = ($valor->DatAccionCompartimentacion->getData() != null) ? true : false;
                        $contador++;
					}						 
				}
			}
            echo json_encode ($sistemafunArr);return;
        }
        
        function insertarCompartimentacionSistFuncAccAction() {
        	$iddominio = $this->_request->getPost('iddominio');
        	$arrayPadres = json_decode(stripslashes($this->_request->getPost('arrayPadres')));
        	$arrayPadresEliminar = json_decode(stripslashes($this->_request->getPost('arrayPadresEliminar')));//array de nodos desmarcados sin desplegar
        	$arraySistemas = json_decode(stripslashes($this->_request->getPost('arraySistemas'))); //estructura chekeadas
            $arrayFuncionalidades = json_decode(stripslashes($this->_request->getPost('arrayFuncionalidades')));//estructuras a eliminar ke son las ke se deschekearon
            $arrayAcciones = json_decode(stripslashes($this->_request->getPost('arrayAcciones')));//estructuras a eliminar ke son las ke se deschekearon
            $arraySistEliminar = json_decode(stripslashes($this->_request->getPost('arraySistEliminar'))); //estructura chekeadas
            $arrayFuncEliminar = json_decode(stripslashes($this->_request->getPost('arrayFuncEliminar')));//estructuras a eliminar ke son las ke se deschekearon
            $arrayAccEliminar = json_decode(stripslashes($this->_request->getPost('arrayAccEliminar')));//estructuras a eliminar ke son las ke se deschekearon
             
			$sistemasIns = array();
			$funcionalidadesIns = array();
			$accionesIns = array();
            
			$sistemasIns = DatSistemaCompartimentacion::obtenerSistemasByIddominio($iddominio);
			$sistemasIns = $this->arregloBidimensionalToUnidimensional($sistemasIns);
			if(count($sistemasIns)) {
				$arraySistemas = array_diff($arraySistemas, $sistemasIns);
				$arraySistEliminar = array_intersect($arraySistEliminar, $sistemasIns);
			} else $arraySistEliminar = array();
			
			$funcionalidadesIns = DatFuncionalidadCompartimentacion::obtenerfuncionalidadesByIddominio($iddominio);
			$funcionalidadesIns = $this->arregloBidimensionalToUnidimensional($funcionalidadesIns);
			if (count($funcionalidadesIns)) {
				$arrayFuncionalidades = array_diff($arrayFuncionalidades, $funcionalidadesIns);
				$arrayFuncEliminar = array_intersect($arrayFuncEliminar, $funcionalidadesIns);
			} else $arrayFuncEliminar = array();
				
			if (count($arrayPadres)) {
				$arrayAccionesHijas = DatAccion::cargarAccionByArrayIdFunc($arrayPadres);
				$arrayAccionesHijas = $this->arregloBidimensionalToUnidimensional($arrayAccionesHijas);
				if (count($arrayAccionesHijas))
					$arrayAcciones = array_merge($arrayAcciones, $arrayAccionesHijas);
			}
			
			$accionesIns = DatAccionCompartimentacion::obteneraccionesByIddominio($iddominio);
			$accionesIns = $this->arregloBidimensionalToUnidimensional($accionesIns);
			if (count($accionesIns)) {
				$arrayAcciones = array_diff($arrayAcciones, $accionesIns);
				$arrayAccEliminar = array_intersect($arrayAccEliminar, $accionesIns);
			} else $arrayAccEliminar = array();
			
        	if (count($arrayPadresEliminar)) {
				$arrayAccionesHijasEliminar = DatAccion::cargarAccionByArrayIdFunc($arrayPadresEliminar);
				$arrayAccionesHijasEliminar = $this->arregloBidimensionalToUnidimensional($arrayAccionesHijasEliminar);
				if (count($arrayAccionesHijasEliminar))
					$arrayAccEliminar = array_merge($arrayAccEliminar, $arrayAccionesHijasEliminar);
			}
			
         if (!count($arraySistemas) && !count($arrayFuncionalidades) && !count($arrayAcciones) && !count($arraySistEliminar) && !count($arrayFuncEliminar) && !count($arrayAccEliminar))
				echo "{'bien':3}";
			else {
				$model = new CompartimentacionsistemaModel();
				$model->gestionarSistemasFuncAcc($arraySistemas, $arrayFuncionalidades, $arrayAcciones, $arraySistEliminar, $arrayFuncEliminar, $arrayAccEliminar, $iddominio);
				echo "{'bien':1}";
			}
        }
        
		function arregloBidimensionalToUnidimensional($array) {
			$arrayResult = array();
			foreach ($array as $id)
				$arrayResult[] = $id['id'];
			return $arrayResult;
		}
    }	
?>
