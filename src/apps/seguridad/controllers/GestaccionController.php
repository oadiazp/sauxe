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
	class GestaccionController extends ZendExt_Controller_Secure
	{
		function init () {
			parent::init();
		}
		
		function gestaccionAction() {
			$this->render();
		}
		
		function cargarsistfuncAction() {
				$idnodo = $this->_request->getPost('node');
				$idsistema =$this->_request->getPost('idsistema');	
					if($idnodo == 0)
						$sistemas = DatSistema::cargarsistema($idnodo);					
					else 
						$sistemas = DatSistema::cargarsistema($idsistema);                        
                    $contador = 0;
                    $sistemafunArr = array();
					if($sistemas->count())
						{
							foreach ($sistemas as $valores=>$valor)
							{
							$sistemafunArr[$contador]['id'] = $valor->id.'_'.$idnodo;
							$sistemafunArr[$contador]['idsistema'] = $valor->id;
							$sistemafunArr[$contador]['text'] = $valor->text;
                            $contador++;							
							}
				        }
					$funcionalidad = DatFuncionalidad::cargarfuncionalidades($idsistema,0,0);
					if($funcionalidad->getData() != NULL)
						{
							foreach ($funcionalidad as $valores=>$valor)
							{
							$sistemafunArr[$contador]['id'] = $valor->id.'_'.$idnodo;
							$sistemafunArr[$contador]['idfuncionalidad'] = $valor->id;
							$sistemafunArr[$contador]['text'] = $valor->text;
							$sistemafunArr[$contador]['leaf'] = true;
                            $contador++;
							}
  							echo json_encode ($sistemafunArr);return; 
							
				        }
            echo json_encode ($sistemafunArr);return;  				    
		}
		
		function insertaraccionAction() {
			$denominacion = $this->_request->getPost('denominacion');
			$abreviatura = $this->_request->getPost('abreviatura');
			$idfuncionalidad = $this->_request->getPost('idfuncionalidad');
			$arrayAcciones = array();
			$arrayAcciones = DatAccion::obtenerAccionesFuncionalidad($idfuncionalidad);
			$denom = true;
			$abrev = true;
			foreach($arrayAcciones as $accion) {
				if($accion['denominacion'] == $denominacion) {
					$denom = false;
					break;	
					}
				elseif($accion['abreviatura'] == $abreviatura) {
					$abrev = false;
					break;
					}
				}
			if(!$denom)
				throw new ZendExt_Exception('SEG048');
			else if(!$abrev)
				throw new ZendExt_Exception('SEG049');
			else {
				$accion = new DatAccion();
				$accion->denominacion = $denominacion;
				$accion->abreviatura = $abreviatura;
				$accion->iddominio = $this->global->Perfil->iddominio;
				$accion->descripcion =$this->_request->getPost('descripcion');
				$accion->icono ='icon';
				$accion->idfuncionalidad = $idfuncionalidad; 
				$model = new DatAccionModel();
				$idaccion = $model->insertaraccion($accion);
			
				$objAccCompart = new DatAccionCompartimentacion();
				$objAccCompart->idaccion = $idaccion;
				$objAccCompart->iddominio = $this->global->Perfil->iddominio;
				$objAccCompart->save();

				$this->showMessage('La acci&oacute;n fue insertada satisfactoriamente.');
			}
			
		}
		
		function modificaraccionAction() {
			$accion = Doctrine::getTable('DatAccion')->find($this->_request->getPost('idaccion'));
			$accion->denominacion =$this->_request->getPost('denominacion');
			$accion->descripcion =$this->_request->getPost('descripcion');
			$accion->abreviatura =$this->_request->getPost('abreviatura');
			$accion->icono =$this->_request->getPost('icono');
			$accion->idfuncionalidad = $this->_request->getPost('idfuncionalidad');	
			$idaccion = $this->_request->getPost('idaccion');
			$arrayAcciones = array();
			$arrayAcciones = DatAccion::obtenerAccionesFuncionalidad($accion->idfuncionalidad);
			$denom = true;
			$abrev = true;
			
			$auxden = 0;
			$auxabv = 0;
			foreach($arrayAcciones as $aux2) {
				if($aux2['idaccion'] == $idaccion) {
					$auxden = $aux2['denominacion']; 
					$auxabv = $aux2['abreviatura'];
					}	
				}
			$denom = true;
			$abrev = true;
			if($accion->denominacion != $auxden) {
			 foreach($arrayAcciones as $aux3) {
				if($aux3['denominacion'] == $accion->denominacion) {
					$denom = false;
					break;	
					}
				}
			}
			if($accion->abreviatura != $auxabv) {
			 foreach($arrayAcciones as $aux3) {
				if($aux3['abreviatura'] == $accion->abreviatura) {
					$abrev = false;
					break;
					}
				}
			}
			if(!$denom)
				throw new ZendExt_Exception('SEG048');
			else if(!$abrev)
				throw new ZendExt_Exception('SEG049');
			else {
				$model = new DatAccionModel();
				$model->modificaraccion($accion);
				$this->showMessage('La acci&oacute;n fue modificada satisfactoriamente.');
				}
		}
		
		function eliminaraccionAction() {
			$accion = new DatAccion();
			$accion = Doctrine::getTable('DatAccion')->find($this->_request->getPost('idaccion'));
			$model = new DatAccionModel();
			$model->eliminaraccion($accion);
			$this->showMessage('La acci&oacute;n fue eliminada satisfactoriamente.');
			}
		
		function cargargridaccionesAction () {
			$idfuncionalidad = $this->_request->getPost('idfuncionalidad');
			$denominacion = $this->_request->getPost('denominacion');
			$start = $this->_request->getPost("start");
	            	$limit = $this->_request->getPost("limit");	 
	            	if($denominacion) {
	            		$datosacc = DatAccion::buscaraccion($idfuncionalidad,$denominacion,$limit,$start);
				$canfilas = DatAccion::obtenercantaccionbuscadas($idfuncionalidad,$denominacion);	
	            		}  
	            	else {
	            		$datosacc = DatAccion::cargaraccion($idfuncionalidad,$limit,$start);
				$canfilas = DatAccion::obtenercantaccion($idfuncionalidad);	
	            		}
	            $datos = array();
	            if(count($datosacc))				
				$datos = $datosacc->toArray();
				$result =  array('cantidad_filas' => $canfilas, 'datos' => $datos);
				echo json_encode($result);return;
			}
	}
?>
