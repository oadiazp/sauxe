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
	class GestservprestaController extends ZendExt_Controller_Secure
	{
		function init ()
		{
			parent::init();
		}
		
		function gestservprestaAction()
		{
			$this->render();
		}
		
		function insertarservicioAction()
		{
			$servicio = new DatServicio();
			$servicio->denominacion=$this->_request->getPost('denominacion');
                        $servicio->descripcion=$this->_request->getPost('descripcion');
			$servicio->wsdl=$this->_request->getPost('wsdl');
			$servicio->idsistema=$this->_request->getPost('idsistema');
			$denominacion=$this->_request->getPost('denominacion');
			$serviciomodel= new DatServPrestaModel();
			$arrayServPresta = array();
			$arrayServPresta = DatServicio::obtenerServPrestaSistema($servicio->idsistema);
                        $arrayServPrestaWSDL = DatServicio::obtenerWSDLSistema($servicio->idsistema);
			$denom = true;
                        $wsdl = true;
			foreach($arrayServPresta as $ServPresta)
			{
				if($ServPresta['denominacion'] == $servicio->denominacion)
				{
					$denom = false;
					break;	
				}
			}
                        foreach($arrayServPrestaWSDL as $ServPresta)
			{
				if($ServPresta['wsdl'] == $servicio->wsdl)
				{
					$wsdl = false;
					break;
				}
			}
			if(!$denom)
				throw new ZendExt_Exception('SEG032');
                        else if(!$wsdl)
                                throw new ZendExt_Exception('SEG052');
			else
			{
				$serviciomodel->insertarservicio($servicio);
				$this->showMessage('El servicio fue insertado satisfactoriamente.');	
			}
		}
		
		function eliminarservicioAction()
		{
		   $servicio= new DatServicio();
		   $servicio= Doctrine::getTable('DatServicio')->find($this->_request->getPost('idservicio'));
		   $serviciomodel= new DatServPrestaModel();
		   $serviciomodel->eliminarservicio($servicio);
		   $this->showMessage('El servicio fue eliminado satisfactoriamente.');
		}
		
		function modificarservicioAction()
		{
		    $servicio = new DatServicio();
			$servicio= Doctrine::getTable('DatServicio')->find($this->_request->getPost('idservicio'));
			$servicio->denominacion=$this->_request->getPost('denominacion');
		    $servicio->descripcion=$this->_request->getPost('descripcion'); 
			$servicio->wsdl=$this->_request->getPost('wsdl');
			$servicio->idsistema=$this->_request->getPost('idsistema');
			$idservicio = $this->_request->getPost('idservicio');
			$serviciomodel= new DatServPrestaModel();
			$arrayServPresta = array();
			$arrayServPresta = DatServicio::obtenerServPrestaSistema($servicio->idsistema);
			$denom = true;
			$auxden = 0;
			foreach($arrayServPresta as $aux2)
			{
				if($aux2['idservicio'] == $idservicio)
				{
					$auxden = $aux2['denominacion'];
				}	
			}
			if($servicio->denominacion != $auxden)
			{
				
				foreach($arrayServPresta as $ServPresta)
				{
					if($ServPresta['denominacion'] == $servicio->denominacion)
					{
						$denom = false;
						break;	
					}
				}
			}
			
			if(!$denom)
				throw new ZendExt_Exception('SEG032');
			else
			{
				$serviciomodel->modificarservicio($servicio);
				$this->showMessage('El servicio fue modificado satisfactoriamente.');
			}
		} 
		
		function cargarservicioAction()
		{
				$idsistema = $this->_request->getPost('idsistema');
				$start =$this->_request->getPost("start");
	            $limit =$this->_request->getPost("limit");
				$datservicio = DatServicio::obtenerServicio($idsistema,$limit,$start);
				$cantserv = DatServicio::cantserviciop($idsistema);	
				$datos = $datservicio->toArray ();
				$result = array ('cantidad_filas' => $cantserv, 'datos' => $datos);
				 echo json_encode($result);return;
		}
		
		
	}
?>