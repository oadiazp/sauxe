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
	class GestnomidiomaController extends ZendExt_Controller_Secure
	{
		function init ()
		{
			parent::init();
		}
		
	    function gestnomidiomaAction()
		{
			$this->render();
		}
		
		function insertarnomidiomaAction()
		{
		    $idioma = new NomIdioma();
			$idioma->denominacion = $this->_request->getPost('denominacion');
			$idioma->abreviatura = $this->_request->getPost('abreviatura');
            if($this->verificaridioma($idioma->denominacion,''))
				throw new ZendExt_Exception('SEG014'); 
			if($this->verificaridioma('', $idioma->abreviatura))
				throw new ZendExt_Exception('SEG015');		 	
			$modelidioma = new NomIdiomaModel();
			$modelidioma->insertaridioma($idioma);
			    echo"{'codMsg':1,'mensaje': 'El idioma fue insertado satisfactoriamente.'}";
        }			
		function modificarnomidiomaAction()
		{
            $ididioma = $this->_request->getPost('ididioma');
			$denominacion = $this->_request->getPost('denominacion');
            $abreviatura = $this->_request->getPost('abreviatura');
			$idioma_mod = Doctrine::getTable('NomIdioma')->find($ididioma);
			if($idioma_mod->denominacion !=  $denominacion)
			{
				if($this->verificaridioma($denominacion, ''))
					throw new ZendExt_Exception('SEG014'); 
			}
			if($idioma_mod->abreviatura != $abreviatura)
			{
				if($this->verificaridioma('', $abreviatura))
					throw new ZendExt_Exception('SEG015');
			}	
		     $idioma_mod->denominacion = $denominacion;
		     $idioma_mod->abreviatura = $abreviatura;	
		     $modelidioma = new NomIdiomaModel();
		     $modelidioma->modificaridioma($idioma_mod);
		     echo"{'codMsg':1,'mensaje': 'El idioma fue modificado satisfactoriamente.'}";
		}
		function verificaridioma($denominacion,$abreviatura)
        {
         $datosservidor = NomIdioma::comprobaridioma($denominacion,$abreviatura);
         if($datosservidor)
            return 1;
         else 
           return 0;
        } 		
		function eliminarnomidiomaAction()
		{
				 $modelidioma = new NomIdiomaModel();
				 $idioma= Doctrine::getTable('NomIdioma')->find($this->_request->getPost('ididioma'));
				 $modelidioma->eliminaridioma($idioma);
				  echo"{'codMsg':1,'mensaje': 'El idioma fue eliminado satisfactoriamente.'}";
		}
			
		function cargarnomidiomaAction()
		{
			 $start = $this->_request->getPost("start");
	         $limit = $this->_request->getPost("limit");	
	         if($limit>0)
	         {
	          $datosidioma = NomIdioma::cargarnomidioma($limit,$start);
			  $canfilas = NomIdioma::obtenercantnomidioma();	
			  $datos = $datosidioma->toArray();
			  $result =  array('cantidad_filas'=> $canfilas, 'datos' => $datos);
	         }
	         else 
	         {
	          $comboidioma = NomIdioma::cargarcomboidioma();
	          $result = $comboidioma->toArray();
	         }
			 echo json_encode($result);return;
		}
			
	}	
?>
