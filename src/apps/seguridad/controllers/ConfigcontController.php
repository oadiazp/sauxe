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

	class ConfigcontController extends ZendExt_Controller_Secure
	{             
    
		function init ()
		{
			parent::init ();
		}
		   
		function configcontAction()
		{
			$this->render();
		}
		
		function insertarclaveAction()
		{
			 $clave=new SegRestricclaveacceso();
			 $clave->diascaducidad=$this->_request->getPost('diascaducidad');
			 $clave->minimocaracteres=$this->_request->getPost('minimocaracteres');
			 if($this->_request->getPost('numerica')=="on")
			  $clave->numerica='1';
			 else { $clave->numerica='0';}
			  if($this->_request->getPost('alfabetica')=="on")
			   $clave->alfabetica='1';
			 else{$clave->alfabetica='0';}
			  if($this->_request->getPost('signos')=="on")
			   $clave->signos='1';
			 else{$clave->signos='0';}
			  $model=new SegRestricclaveaccesoModel();
			 if($model->insertarclave($clave))
			  $this->showMessage('La clave fue insertada satisfactoriamente.');	
		}
		
	    function modificarclaveAction()
	    {
		     $clave=new SegRestricclaveacceso();
		     $clave = Doctrine::getTable('SegRestricclaveacceso')->find($this->_request->getPost('idrestricclaveacceso'));
		     $clave->diascaducidad=$this->_request->getPost('diascaducidad');
		     $clave->minimocaracteres=$this->_request->getPost('minimocaracteres');
	         if($this->_request->getPost('numerica')=="on")
		      $clave->numerica='1';
		     else { $clave->numerica='0';}
	          if($this->_request->getPost('alfabetica')=="on")
		       $clave->alfabetica='1';
		     else{$clave->alfabetica='0';}
	          if($this->_request->getPost('signos')=="on")
		       $clave->signos='1';
		     else{$clave->signos='0';}
             $model=new SegRestricclaveaccesoModel();
		     if($model->insertarclave($clave))
	          $this->showMessage('La clave fue modificada satisfactoriamente.');
	    }
		
	    function cargarclavesAction()
	    {
		     $start = $this->_request->getPost("start");
	         $limit = $this->_request->getPost("limit");            
		     $datosacc = SegRestricclaveacceso::cargarclave($limit,$start);
		     $canfilas = SegRestricclaveacceso::obtenerclave();
		     $datos=$datosacc->toArray();
		     $result =  array('cantidad_filas' => $canfilas, 'datos' => $datos);
		     echo json_encode($result);
             return;
	    }
	}
?>