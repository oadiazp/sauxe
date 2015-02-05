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
	class GestgestorController extends ZendExt_Controller_Secure
	{
		function init ()
		{
			parent::init();
		}
				
		function gestgestorAction()
		{
			$this->render();
		}	
		
		function insertargestorservidorAction()
		{
			 $gestorservidor = new DatGestorDatServidorbd();
			 $gestorservidor->idgestor = $this->_request->getPost('idgestor');
			 $gestorservidor->idservidor = $this->_request->getPost('idservidor');				
			 $model = new DatGestorDatServidorbdModel();
			 $model->insertargestorervidor($gestorservidor);
			 $this->showMessage('El gestor fue insertado satisfactoriamente.');
		}	

		function modificargestorAction()
		{		
				$idgestor = $this->_request->getPost('idgestor');
				$nomgestor = $this->_request->getPost('gestor');			
				$puertogestor = $this->_request->getPost('puerto');
				$descgestor = $this->_request->getPost('descripcion');
				$cantgestsist = DatGestor::obtenercantgestsist($idgestor);
				$cantgestbd = DatGestor::obtenercantgestbd($idgestor);
						
				 if(($cantgestsist == 0)  && ($cantgestbd == 0))
				 {  
				 		$gestor = new DatGestor();
						$model = new DatGestorModel();
						$gestor = Doctrine::getTable('DatGestor')->find($idgestor);
						$gestor->gestor = $nomgestor;
						$gestor->puerto = $puertogestor;
						$gestor->descripcion = $descgestor;
						$model->modificargestor($gestor);
						$this->showMessage('El gestor fue modificado satisfactoriamente.');
				 }
		}	
			
		function cargargestoresAction()
		{
			 $idservidor = $this->_request->getPost('idservidor');
			 $start = $this->_request->getPost("start");
	         $limit = $this->_request->getPost("limit");	            
			 $datosgest = DatGestor::cargargestores($idservidor,$limit,$start);
			 $canfilas = DatGestor::obtenercantgest($idservidor);
			 $datos=$datosgest->toArray();
			 $result =  array('cantidad_filas'=> $canfilas, 'datos' => $datos);
			  echo json_encode($result);return;
		}
		
		function cargarservidoresAction()
		{
			 $idnodo =$this->_request->getPost('node');
			 if($idnodo==0)
			  {
			   $servidores = DatServidor::cargarservidores(0,0);
			   if($servidores->count())
				{
				 foreach ($servidores as $valores=>$valor)
				  {
				   $servidoresArr[$valores]['id'] = $valor->id;							
				   $servidoresArr[$valores]['text'] = $valor->text;
		           $servidoresArr[$valores]['leaf'] = true;
				  }
				  echo json_encode ($servidoresArr);return;
				}
				else
				{
				 $serv=$servidores->toArray();
				 echo json_encode ($serv);return;
				}
			  }
		}
		
		function comprobargestoresAction()
		{
			 $idgestor = $this->_request->getPost('idgestor');
			 $idservidor = $this->_request->getPost('idservidor');
             $comprobar = DatGestor::obtenercantgestsistema($idservidor,$idgestor);					
			 if($comprobar > 0)
			   throw new ZendExt_Exception('SEG011');
			 if(DatGestorDatServidorbd::eliminargestorservidor($idservidor,$idgestor))
			  echo"{'codMsg':1,'mensaje': 'El gestor fue eliminado satisfactoriamente.'}";				 				 			
		}
		
		function eliminargestoresAction()
		{
		   	 $idservidor = $this->_request->getPost('idservidor');
		  	 $idgestor = $this->_request->getPost('idgestor');			
			 if(DatGestorDatServidorbd::eliminargestorservidor($idservidor,$idgestor))
			  echo"{'codMsg':1,'mensaje': 'El gestor fue eliminado satisfactoriamente.'}";	
		} 
		
		function cargarcombogestorAction()
		{
			$datos = DatGestor::cargarcombogestor();
			echo json_encode($datos->toArray());return;
		}  
				
		function cargarcombogestoresAction()
		{
    		 $idservidor = $this->_request->getPost('idservidor');
			 $gestores = DatGestor::cargarcombogestores($idservidor);		
			 $datos=$gestores->toArray(true);
			 echo json_encode($datos);return;
		}
	
	}
	
?>