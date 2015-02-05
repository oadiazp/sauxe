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
	class GestfuncionalidadController extends ZendExt_Controller_Secure
	{
		function init () {
			parent::init();		
		}
        		
		function gestfuncionalidadAction() {
			$this->render();
		}	
        	
		function insertarfuncionalidadAction() {
			$funcionalidad = new DatFuncionalidad();
			$funcionalidad->idsistema = $this->_request->getPost('idsistema');
			$funcionalidad->denominacion = $this->_request->getPost('text');
			$funcionalidad->referencia = $this->_request->getPost('referencia');
			$funcionalidad->descripcion = $this->_request->getPost('descripcion');
			$funcionalidad->iddominio = $this->global->Perfil->iddominio;
	        	$funcionalidad->index = ($this->_request->getPost('index'))?$this->_request->getPost('index'):0;
			$funcionalidad->icono = $this->_request->getPost('icono');
			$denominacion = $this->_request->getPost('text');
			$arrayFuncionalidades = DatFuncionalidad::obtenerFuncionalidadesSistema($funcionalidad->idsistema);
			$denom = true;
			foreach($arrayFuncionalidades as $funcionalidades)
			{
				if($funcionalidades['denominacion'] == $funcionalidad->denominacion)
				{
					$denom = false;
					break;	
				}
			}			
			if(!$denom)
				throw new ZendExt_Exception('SEG030');
			else
			{			
				$model = new DatFuncionalidadModel();				
				$idfuncionalidad = $model->insertarfuncionalidad($funcionalidad);
				$objFuncCompart = new DatFuncionalidadCompartimentacion();
				$objFuncCompart->iddominio = $this->global->Perfil->iddominio;
				$objFuncCompart->idfuncionalidad = $idfuncionalidad;
				$objFuncCompart->save();
				$this->showMessage('La funcionalidad fue insertada satisfactoriamente.');
			}
		}
        		
		function modificarfuncionalidadAction() {
			$idfuncionalidad = $this->_request->getPost('idfuncionalidad');
			$funcionalidad=new DatFuncionalidad();
			$funcionalidad = Doctrine::getTable('DatFuncionalidad')->find($idfuncionalidad);
			$funcionalidad->idsistema = $this->_request->getPost('idsistema');
			$funcionalidad->denominacion = $this->_request->getPost('text');
			$funcionalidad->referencia = $this->_request->getPost('referencia');
			$funcionalidad->descripcion = $this->_request->getPost('descripcion');
	        	$funcionalidad->index = $this->_request->getPost('index');
			$funcionalidad->icono = $this->_request->getPost('icono');
			$model = new DatFuncionalidadModel();
			$arrayFuncionalidades = DatFuncionalidad::obtenerFuncionalidadesSistema($funcionalidad->idsistema);
			$denom = true;
			$auxden = 0;
			foreach($arrayFuncionalidades as $aux2)
			{
				if($aux2['idfuncionalidad'] == $idfuncionalidad)
				{
					$auxden = $aux2['denominacion'];
				}	
			}
			if($funcionalidad->denominacion != $auxden)
			{
				
				foreach($arrayFuncionalidades as $funcionalidades)
				{
					
					if($funcionalidades['denominacion'] == $funcionalidad->denominacion)
					{
						$denom = false;
						break;	
					}
				}
			}
			if(!$denom)
				throw new ZendExt_Exception('SEG030');
			else
			{				
				$model->modificarfuncionalidad($funcionalidad);
				$this->showMessage('La funcionalidad fue modificada satisfactoriamente.');
			}
		}
        
		function verificarfuncionalidad($denominacion) {
			$datosfuncionalidad = DatFuncionalidad::verificarfuncionalidad($denominacion);
			if($datosfuncionalidad)
			    return 1;
			else 
			   return 0;
        	} 
		
		function eliminarfuncionalidadAction() {
			$funcionalidad = new DatFuncionalidad();
			$funcionalidad  = Doctrine::getTable('DatFuncionalidad')->find($this->_request->getPost('idfuncionalidad'));
			$model = new DatFuncionalidadModel();
			$model->eliminarfuncionalidad($funcionalidad);
			$this->showMessage('La funcionalidad fue eliminada satisfactoriamente.');
		}
        
		function cargarsistemaAction() {
			$sistemas = DatSistema::cargarsistema($this->_request->getPost('node'));				
			if($sistemas->count()) {       
				$sistemaArr = array();              
				foreach($sistemas as $valores=>$valor) {
					$sistemaArr[$valores]['id'] = $valor->id;
					$sistemaArr[$valores]['text'] = $valor->text;
					$sistemaArr[$valores]['abreviatura'] = $valor->abreviatura;
					$sistemaArr[$valores]['descripcion'] = $valor->descripcion;
					$sistemaArr[$valores]['icono'] = $valor->icono;
					$sistemaArr[$valores]['leaf'] = $valor->leaf;
					}
				echo json_encode ($sistemaArr);return;
		    		}
		   	else {
				$sist=$sistemas->toArray();
				echo json_encode ($sist);return;
		    		}
			}
        
		function cargarfuncionalidadesAction() {
			$idsistema = $this->_request->getPost('idsistema');
			$limit = $this->_request->getPost("limit");
			$start = $this->_request->getPost("start");
			$denominacion = $this->_request->getPost("denominacion");
			if($denominacion) {
				$datosfunc = DatFuncionalidad::buscarfuncionalidades($idsistema,$denominacion,$limit,$start);				
				$cantf = DatFuncionalidad::obtenercantfuncdenominacion($idsistema,$denominacion);				
				}	
			else {
				$datosfunc = DatFuncionalidad::buscarfuncionalidadesgrid($idsistema,$limit,$start);
				$cantf = DatFuncionalidad::obtenercantfunc($idsistema);		
				}				
			if($datosfunc) {
				$datos=$datosfunc->toArray();
				$result =  array('cantidad_filas' => $cantf, 'datos' => $datos);
				echo json_encode($result);return;
				}
		}
	}
	
?>
