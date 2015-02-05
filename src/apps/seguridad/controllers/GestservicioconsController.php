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
	class GestservicioconsController extends ZendExt_Controller_Secure
	{
		function init ()
		{
			parent::init();
		}
        		
		function gestservicioconsAction()
		{
			$this->render();
		}	
        	
		function insertarservicioconsAction()
		{
			$serviciocons=new DatServicioDatSistema();
			$serviciocons->idservicio =$this->_request->getPost('idservicio');
			$serviciocons->idsistema=$this->_request->getPost('idsistema');
			$model=new DatServicioDatSistemaModel();
			$model->insertarserviciocons($serviciocons);
			$this->showMessage('El servicio fue insertado satisfactoriamente.');
		}		
		
		function eliminarservicioconsAction()
		{
			$idservicio = $this->_request->getPost('idservicio');
			$idsistema = $this->_request->getPost('idsistema');
			DatServicioDatSistema::buscarservicioscons($idservicio,$idsistema);
			$this->showMessage('El servicio fue eliminado satisfactoriamente.');
		}	
		
		function cargarservicioconsAction()
		{
				$idsistema = $this->_request->getPost('idsistema');
				$limit = $this->_request->getPost("limit");
				$start = $this->_request->getPost("start");
				$datoserviciosc = DatServicioDatSistema::cargarserviciocons($idsistema,$limit,$start);
				$cantf = DatServicioDatSistema::obtenercantsercons($idsistema);		
				$datos=$datoserviciosc->toArray();
				$result =  array('cantidad_filas' => $cantf, 'datos' => $datos);
				echo json_encode($result);return;
		}		
		
		function cargarservicionoconsAction()
		{
				$idsistema = $this->_request->getPost('idsistema');
				$datoserviciosnoc = DatServicioDatSistema::cargarservicionocons($idsistema);
				$datos=$datoserviciosnoc->toArray(true);
				echo json_encode($datos);return;
		}
		
	}
?>