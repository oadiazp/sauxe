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
	class GestnomdesktopController extends ZendExt_Controller_Secure
	{
		function init ()
		{
			parent::init();
		}
		
	    function gestnomdesktopAction()
		{
			$this->render();
		}
				
		function insertarnomdesktopAction()
		{
			$desktop = new NomDesktop();
			$desktop->denominacion = $this->_request->getPost('denominacion');
			$desktop->abreviatura = $this->_request->getPost('abreviatura');
			$desktop->descripcion = $this->_request->getPost('descripcion');	
			$modeldesktop = new NomDesktopModel();
			$denominacion = $this->_request->getPost("denominacion");
			$abreviatura = $this->_request->getPost("abreviatura");
			if($this->verificarnombredesktop($denominacion,''))
				throw new ZendExt_Exception('SEG037'); 
			if($this->verificarnombredesktop('', $abreviatura))
				throw new ZendExt_Exception('SEG045');
			$modeldesktop->insertardesktop($desktop);
			echo"{'codMsg':1,'mensaje': 'El escritorio fue insertado satisfactoriamente.'}";	 
		}
					
		function modificarnomdesktopAction()
		{
	        $iddesktop = $this->_request->getPost('iddesktop');
	        $denominacion = $this->_request->getPost('denominacion');
	        $descripcion = $this->_request->getPost('descripcion');	
	        $abreviatura = $this->_request->getPost('abreviatura');   
			$desktop_mod = Doctrine::getTable('NomDesktop')->find($iddesktop);
			if($desktop_mod->denominacion !=  $denominacion)
			{
				if($this->verificarnombredesktop($denominacion, ''))
					throw new ZendExt_Exception('SEG037'); 
			}
			if($desktop_mod->abreviatura != $abreviatura)
			{
				if($this->verificarnombredesktop('', $abreviatura))
					throw new ZendExt_Exception('SEG045');
			}			
			$desktop_mod->denominacion = $denominacion;
			$desktop_mod->descripcion = $descripcion;
			$desktop_mod->abreviatura = $abreviatura;
			$modeldesktop = new NomDesktopModel();
	        $modeldesktop->modificardesktop($desktop_mod);
	        echo"{'codMsg':1,'mensaje': 'El escritorio fue modificado satisfactoriamente.'}";
		}
		function verificarnombredesktop($denominacion,$abreviatura)
        {
	        $datosescritorio = NomDesktop::verificarnombredesktop($denominacion,$abreviatura);
	        if($datosescritorio)
	            return 1;
	        else 
	            return 0;
        } 
					
		function eliminarnomdesktopAction()
		{
			 
			 $modeldesktop = new NomDesktopModel();
			 $desktop = Doctrine::getTable('NomDesktop')->find($this->_request->getPost('iddesktop'));
			 $iddesktop = $this->_request->getPost("iddesktop");
			 $comprobar = SegUsuario::comprobardesktop($iddesktop);
			 if ($comprobar==0)
			 {
			 $modeldesktop->eliminardesktop($desktop);
			  echo"{'codMsg':1,'mensaje': 'El escritorio fue eliminado satisfactoriamente.'}";
			  }
			  else
			  {
			   throw new ZendExt_Exception('SEG027');
		}
		}

		function cargarnomdesktopAction()
		{
			 $start = $this->_request->getPost("start");
             $limit = $this->_request->getPost("limit");	
             if($limit>0)
             {
              $datosdesktop = NomDesktop::cargarnomdesktop($limit,$start);
			  $canfilas = NomDesktop::obtenercantnomdesktop();
			  $datos=$datosdesktop->toArray();
			  $result =  array('cantidad_filas'=> $canfilas, 'datos' => $datos);
			 }
             else 
             {
              $combodesktop = NomDesktop::cargarcombodesktop();
              $result = $combodesktop->toArray();
             }
			 echo json_encode($result);return;
		}

	}
?>