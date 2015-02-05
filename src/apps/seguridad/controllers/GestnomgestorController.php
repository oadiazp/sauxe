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
	class GestnomgestorController extends ZendExt_Controller_Secure
	{
		function init () {
			parent::init();
		}	
			
		function gestnomgestorAction() {
			$this->render();
		}
				
		function insertargestorAction() {
		    $gestor = new DatGestor();
			$gestor->gestor = $this->_request->getPost('gestor');
			$gestor->puerto = $this->_request->getPost('puerto');
			$gestor->descripcion = $this->_request->getPost('descripcion');
			if($this->verificargestor($gestor->gestor))
				throw new ZendExt_Exception('SEG051'); 
			$model = new DatGestorModel();
			$model->insertarnomgestor($gestor);			
			$this->showMessage('El gestor fue adicionado satisfactoriamente.');
			
		}
		
		function modificaromgestorAction() 
		{
			$idgestor = $this->_request->getPost('idgestor');
			$gestor = $this->_request->getPost('gestor');
			$puerto = $this->_request->getPost('puerto');
			$descripcion = $this->_request->getPost('descripcion');
			$gestor_mod = Doctrine::getTable('DatGestor')->find($idgestor);
			if($gestor_mod->gestor !=  $gestor)
			{
				if($this->verificargestor($gestor))
					throw new ZendExt_Exception('SEG051'); 
			} 
			$gestor_mod->gestor = $gestor;
			$gestor_mod->puerto = $puerto;
			$gestor_mod->descripcion = $descripcion;
			$model = new DatGestorModel();
			$model->modificarnomgestor($gestor_mod);
			$this->showMessage('El gestor fue modificado satisfactoriamente.');			 	
		}
		
		function verificargestor($gestor) 
		{
	         $gestorbd = Datgestor::comprobargestor($gestor);
	         if($gestorbd)
	            return 1;
	         else 
	           return 0;
        }
		
					
		function comprobargestoresAction() {
		   	$idgestor = $this->_request->getPost('idgestor');
		   	$cant = DatGestor::obtenercantnomgestsist($idgestor);			
			if($cant > 0)
				throw new ZendExt_Exception('SEG011');
			elseif (DatGestorDatServidorbd::obtenercantnomgest($idgestor)){
				echo"{'tiene':1}";return;
				}
			else {
					$model = new DatGestorModel();
					$gestor = Doctrine::getTable('DatGestor')->find($idgestor);
					$model->eliminarnomgestor($gestor);
					$this->showMessage('El gestor fue eliminado satisfactoriamente.');
				 }	
		}  
		
		function eliminargestorAction() {
			$model = new DatGestorModel();
			$gestor = Doctrine::getTable('DatGestor')->find($this->_request->getPost('idgestor'));
			$model->eliminarnomgestor($gestor);
			$this->showMessage('El gestor fue eliminado satisfactoriamente.');	
		} 
				
		function cargarnomgestoresAction() {
		    $start = $this->_request->getPost("start");
	        $limit = $this->_request->getPost("limit");	
	        $gestor = $this->_request->getPost("gestor");
	        if($gestor) {
	            $datosgest = DatGestor::buscarnomgestores($gestor,$limit,$start);
	            $canfilas = DatGestor::obtenercantnomgestoresbuscados($gestor);
	        	}
	        else {	            
		        $datosgest = DatGestor::cargarnomgestores($limit,$start);
		        $canfilas = DatGestor::obtenercantnomgestores();
	        	}
		    $datos=$datosgest->toArray();
		    $result =  array('cantidad_filas'=> $canfilas, 'datos' => $datos);
		    echo json_encode($result);return;
		}
	}
?>