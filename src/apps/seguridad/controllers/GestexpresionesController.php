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
	class GestexpresionesController extends ZendExt_Controller_Secure
	{
		function init ()
		{
			parent::init ();
		}
		
		function gestexpresionesAction()
		{
		$this->render();
		}
		
		function insertarexpresionAction()
		{
	        $expresion = new NomExpresiones();
	        $expresion->denominacion = $this->_request->getPost('denominacion');
                $expresionaux =  stripcslashes($this->_request->getPost('expresion'));
                utf8_decode($expresionaux);
	        $expresion->expresion = $expresionaux;
	        $expresion->descripcion = $this->_request->getPost('descripcion');
                $denominacion = $this->_request->getPost('denominacion');
                $verificarExpre = NomExpresiones::verificarExpresiones($denominacion);
	        $model = new NomExpresionesModel();
                if($this->verificarExpresiones($expresion->denominacion))
                        throw new ZendExt_Exception('SEG039');
                $model->insertarexpresion($expresion);
                $this->showMessage('La expresi&oacute;n fue insertada satisfactoriamente.');
			
		}
		
		function modificarexpresionAction()
		{
	        $idexpresion = $this->_request->getPost('idexpresiones');
	        $denominacion=$this->_request->getPost('denominacion');
	        $expresion=stripcslashes($this->_request->getPost('expresion'));
	        $descripcion=$this->_request->getPost('descripcion');
	        $expresion_mod = Doctrine::getTable('NomExpresiones')->find($idexpresion);
			if($expresion_mod->denominacion !=  $denominacion)
			{
				if($this->verificarExpresiones($denominacion))
					throw new ZendExt_Exception('SEG039'); 
			}
			$expresion_mod->denominacion=$this->_request->getPost('denominacion');
	        $expresion_mod->expresion=$this->_request->getPost('expresion');
	        $expresion_mod->descripcion=$this->_request->getPost('descripcion');
			$model=new NomExpresionesModel();		
	        $model->modificarexpresion($expresion_mod);	
	        $this->showMessage('La expresi&oacute;n fue modificada satisfactoriamente.');	
		}
		
		function verificarExpresiones($denominacion)
                {
                 $datosusuario = NomExpresiones::verificarExpresiones($denominacion);
                 if($datosusuario)
                    return 1;
                 else
                   return 0;
                }
		
	    function eliminarexpresionAction()
		{
        $arrayElim = json_decode(stripslashes($this->_request->getPost('expresionesElim')));
        NomExpresiones::eliminarExpresiones($arrayElim);
        $this->showMessage('Expresi&oacute;n(es) eliminada(s) satisfactoriamente.');
		}
		
	    function cargarexpresionesAction ()
		{
		 $start = $this->_request->getPost("start");
	     $limit = $this->_request->getPost("limit");
         $expresiones = $this->_request->getPost("denominacion");
         if($expresiones)
            {
             $datosacc = NomExpresiones::cargarexpresionBuscar($expresiones,$limit,$start);
             $canfilas = NomExpresiones::obtenerexpresionBuscar($expresiones);
            }
         else
            {
             $datosacc = NomExpresiones::cargarexpresion($limit,$start);
             $canfilas = NomExpresiones::obtenerexpresion();
            }	
		 $result =  array('cantidad_filas' => $canfilas, 'datos' => $datosacc->toArray());
		 echo json_encode($result);return;
		}
		
	}
?>  