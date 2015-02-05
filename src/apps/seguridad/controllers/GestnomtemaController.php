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
	class GestnomtemaController extends ZendExt_Controller_Secure
	{
		function init ()
		{
			parent::init();
		}
		
		function gestnomtemaAction()
		{
			$this->render();
		} 
		
		function insertartemaAction()
		{
			 $tema = new NomTema();
			 $tema->denominacion = $this->_request->getPost('denominacion');
			 $tema->abreviatura = $this->_request->getPost('abreviatura');
			 $tema->descripcion = $this->_request->getPost('descripcion');	
             if($this->verificartema($tema->denominacion,''))
				throw new ZendExt_Exception('SEG016'); 
			if($this->verificartema('', $tema->abreviatura))
				throw new ZendExt_Exception('SEG017');
			 $modeltema = new NomTemaModel();
			 $modeltema->insertartema($tema);
			  echo"{'codMsg':1,'mensaje': 'El tema fue insertado satisfactoriamente.'}";
                    
		}
        
		function modificartemaAction()
		{
          $tema = new NomTema();
          $denominacion = $this->_request->getPost('denominacion');
          $abreviatura =  $this->_request->getPost('abreviatura'); 
	      $tema = Doctrine::getTable('NomTema')->find($this->_request->getPost('idtema'));
          $tema->descripcion = $this->_request->getPost('descripcion');

            if($tema->denominacion != $denominacion)
            {
                if($this->verificartema($denominacion, '-1'))
                    throw new ZendExt_Exception('SEG016'); 
            }
            if($tema->abreviatura != $abreviatura)
            {
                if($this->verificartema('-1', $abreviatura))
                    throw new ZendExt_Exception('SEG017');
            }
             $tema->denominacion = $denominacion;                 
             $tema->abreviatura = $abreviatura;
             $modeltema = new NomTemaModel();
             $modeltema->modificartema($tema);
             echo"{'codMsg':1,'mensaje': 'El tema fue modificado satisfactoriamente.'}"; 
        }
		function verificartema($denominacion,$abreviatura)
        {
         $tema = NomTema::comprobartema($denominacion,$abreviatura);
         if($tema)
            return 1;
         else 
           return 0;
        }
		function eliminarnomtemaAction()
		{
			 $modeltema = new NomTemaModel();
			 $tema = Doctrine::getTable('NomTema')->find($this->_request->getPost('idtema'));
			 $modeltema->eliminartema($tema);
			  echo"{'codMsg':1,'mensaje': 'El tema fue eliminado satisfactoriamente.'}";
		}
		
		function cargarnomtemaAction()
		{
			 $start = $this->_request->getPost("start");
	         $limit = $this->_request->getPost("limit");	
	         if($limit>0)
	         {
	          $datostema = NomTema::cargarnomtema($limit,$start);
			  $canfilas = NomTema::obtenercantnomtema();	
			  $datos=$datostema->toArray();
			  $result =  array('cantidad_filas'=> $canfilas, 'datos' => $datos);
	         }
	         else 
	         {
	          $combotema = NomTema::cargarcombotema();
	          $result = $combotema->toArray();
	         }
		     echo json_encode($result);return;
		}
	}
?>