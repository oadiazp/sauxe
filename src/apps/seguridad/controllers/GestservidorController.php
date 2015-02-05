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
	class GestservidorController extends ZendExt_Controller_Secure
	{
		function init ()
		{
			parent::init();
		}

		function gestservidorAction()
		{
			$this->render();
		}
		
		function insertarservAction()
		{	
			$openldap = $this->_request->getPost('openldap');
			$ldap = $this->_request->getPost('ldap');
			$cadenaconexion = $this->_request->getPost('usuario');			 
			if($openldap == 'openldap')
				$openldapaux = 'openldap';
			elseif($ldap == 'ldap')
				$ldapaux = 'ldap';			 
			$servidor = new DatServidor();
			$tiposerv = $this->_request->getPost('tiposervidor');
			$servidor->denominacion = $this->_request->getPost('denominacion');
			$servidor->descripcion = $this->_request->getPost('descripcion');
			$servidor->ip = $this->_request->getPost('ip');
			$servidor->tiposervidor = $this->_request->getPost('tiposervidor');
			if($this->verificadatosservidor($servidor->denominacion))
				throw new ZendExt_Exception('SEG050'); 
            $servidor->save();			   		   	
		    if($tiposerv == 'autenticaci&oacute;n')		   
		      {	
			   	   $servidorauth = new DatSerautenticacion();
				   $servidorauth->idservidor = $servidor->idservidor;
				   if($openldapaux){
				   $servidorauth->tservidor = $openldapaux;
				   $servidorauth->cadconexion = $cadenaconexion;}
				   else{
				   $servidorauth->tservidor = $ldapaux;
				   $servidorauth->cadconexion = '';}
				   $model = new DatSerautenticacionModel();
				   $model->insertar($servidorauth);
				   $this->showMessage('El servidor de autenticaci&oacute;n fue insertado satisfactoriamente.');
			  }		   
		    else
		      {
				   $servidorbd = new DatServidorbd();
				   $servidorbd->idservidor = $servidor->idservidor;
				   $model = new DatServidorModel();
				   $model->insertar($servidorbd);
				   $this->showMessage('El servidor de base de datos fue insertado satisfactoriamente.');				
		      }
	    }
		function modificarservAction()
		{
			 $servidor = new DatServidor();
			 $model = new DatServidorModel(); 	
			 $idservidor =$this->_request->getPost('idservidor');
			 $descripcion = $this->_request->getPost('descripcion');
			 $denominacion = $this->_request->getPost('denominacion');
			 $tpservaux = $this->_request->getPost('tiposervidor');
			 $tpserv = 'autenticaci&oacute;n';
                         $tpservbd = 'bd';
			 $server_mod = Doctrine::getTable('DatServidor')->find($idservidor);
			 if($server_mod->denominacion !=  $denominacion)
			 {
				if($this->verificadatosservidor($denominacion, ''))
					throw new ZendExt_Exception('SEG050'); 
			 } 
			 $ip = $this->_request->getPost('ip');
			 $openldap = $this->_request->getPost('openldap');
			 $ldap = $this->_request->getPost('ldap');
			 $cadenaconexion = $this->_request->getPost('usuario');			 
			 if($openldap == 'openldap')
				$openldapaux = 'openldap';
			 elseif($ldap == 'ldap')
				$ldapaux = 'ldap';
			 $cantservsist = DatSistemaDatServidores::obtenercantservsistema($idservidor);
			 if($cantservsist > 0)
				throw new ZendExt_Exception('SEG010');
			 $tiposervidor = DatServidor::gettiposerv($idservidor);
			 $tiposerv = $tiposervidor[0]->tiposervidor;
			 if(($tpservaux !=$tiposerv) && ($tpservaux =='autenticaci&oacute;n'))
			  {
			   $cantservidoresbdsist = DatSistemaDatServidores::obtenercantservsistema($idservidor);
			if($cantservidoresbdsist != 0)
		    	throw new ZendExt_Exception('SEG010');
				if(($cantservidoresbdsist == 0) )
	    		{ 		
				  $serbd = new DatServidorbd();
			      $serbd = Doctrine::getTable('DatServidorbd')->find($idservidor);
			      $serbdm = new DatServidorbdModel();
			      $serbdm->eliminarservbd($serbd);								    				    
			      $serauth = new DatSerautenticacion();
				  $serauthm = new DatSerautenticacionModel();
			      $serauth->idservidor = $idservidor;
				  if($openldapaux ==  'openldap')
					{
						$serauth->tservidor = $openldapaux;
						$serauth->cadconexion = $cadenaconexion;
					}	
					else
                                        {
						$serauth->tservidor =$ldapaux;
                                                $serauth->cadconexion = '';
                                        }
			      $servidor = Doctrine::getTable('DatServidor')->find($idservidor);
			      $servidor->descripcion = $descripcion;
			      $servidor->denominacion = $denominacion;
			      $servidor->tiposervidor = $tpservaux;
			      $servidor->ip = $ip;
			      $model->modificarservidor($servidor,$serauth);
				  $serauthm->modificarservidor($serauth);
				  $this->showMessage('El servidor fue modificado satisfactoriamente.');
       	    	}			       		
			  }
			 elseif (($tpservaux!=$tiposerv) && ($tpservaux =='bd'))
			  {
	 		   $cantservidoresauthusers = SegUsuarioDatSerautenticacion::obtenercantservuser($idservidor);
	 		   if($cantservidoresauthusers > 0)
		    	throw new ZendExt_Exception('SEG010');			 			 
	 		    if($cantservidoresauthusers == 0)
 	   			 {
 		 		  $serautenticacion = new DatSerautenticacion();
	 			  $serautenticacion = Doctrine::getTable('DatSerautenticacion')->find($idservidor);
	 			  $serautenticacionmodel = new DatSerautenticacionModel();
	 			  $serautenticacionmodel->eliminarservauth($serautenticacion);
				$serbd = new DatServidorbd();
				  $serbdm = new DatServidorbdModel();
			      $serbd->idservidor = $idservidor;
			      $servidor = Doctrine::getTable('DatServidor')->find($idservidor);				    
			      $servidor->descripcion = $descripcion;
			      $servidor->denominacion = $denominacion;
			      $servidor->tiposervidor = $tpservaux;
			      $servidor->ip = $ip;
			      $model->modificarservidor($servidor);
				  $serbdm->modificarservidor($serbd);
				  $this->showMessage('El servidor fue modificado satisfactoriamente.');				 			 	   
 	 			 }		
			  }
			  else if(($tpservaux ==$tiposerv) && ($tpservaux =='autenticaci&oacute;n'))
			  {
                               $cantservidoresbdsist = DatSistemaDatServidores::obtenercantservsistema($idservidor);
                                if($cantservidoresbdsist != 0)
                                    throw new ZendExt_Exception('SEG010');
				if(($cantservidoresbdsist == 0) )
                                {
                                      $serbd = new DatServidorbd();
                                      $serauth = new DatSerautenticacion();
                                      $serauthm = new DatSerautenticacionModel();
                                      $serauth->idservidor = $idservidor;
                                      $servidor = Doctrine::getTable('DatServidor')->find($idservidor);
                                      $serauth = Doctrine::getTable('DatSerautenticacion')->find($idservidor);
                                      if($openldapaux ==  'openldap')
                                      {
                                            $serauth->tservidor = $openldapaux;
                                            $serauth->cadconexion = $cadenaconexion;
                                      }
                                      else
                                      {
                                            $serauth->tservidor =$ldapaux;
                                            $serauth->cadconexion = '';
                                      }
                                      
                                      $servidor->descripcion = $descripcion;
                                      $servidor->denominacion = $denominacion;
                                      $servidor->tiposervidor = $tpservaux;
                                      $servidor->ip = $ip;
                                      $model->modificarservidor($servidor,$serauth);
                                      $serauthm->modificarservidor($serauth);
                                      $this->showMessage('El servidor fue modificado satisfactoriamente.');
                                }
			  }
                          elseif (($tpservaux==$tiposerv) && ($tpservaux =='bd'))
			  {
                               $cantservidoresauthusers = SegUsuarioDatSerautenticacion::obtenercantservuser($idservidor);
                               if($cantservidoresauthusers > 0)
                                    throw new ZendExt_Exception('SEG010');
                                if($cantservidoresauthusers == 0)
 	   			{
                                    $serautenticacion = new DatSerautenticacion();
                                    $serbd = new DatServidorbd();
                                    $serbdm = new DatServidorbdModel();
                                    $servidor = Doctrine::getTable('DatServidor')->find($idservidor);
                                    $serbd = Doctrine::getTable('DatServidorbd')->find($idservidor);
                                    $serbd->idservidor = $idservidor;
                                    $servidor->descripcion = $descripcion;
                                    $servidor->denominacion = $denominacion;
                                    $servidor->tiposervidor = $tpservaux;
                                    $servidor->ip = $ip;
                                    $model->modificarservidor($servidor);
                                    $serbdm->modificarservidor($serbd);
                                    $this->showMessage('El servidor fue modificado satisfactoriamente.');
 	 			}
			  }
			  
	    }
		
		function verificadatosservidor($denominacion)
        {
         $datosservidor = DatServidor::comprobardatosservidor($denominacion);
         if($datosservidor)
            return 1;
         else 
           return 0;
        } 		
		function comprobarservidorAction()
		{
			$arrayServ = json_decode(stripcslashes($this->_request->getPost('arrayServ')));
            $arrayElim = array();
            foreach($arrayServ as $servidor)
			{
				if(DatSerautenticacion::obtenercantidad($servidor) == 0 && DatSistemaDatServidores::obtenercantservsistema($servidor) == 0)
				{
					$arrayElim[] = $servidor;
				}
            }
            if(count($arrayServ) == count($arrayElim))
		    { 
                DatServidor::elimirarServidores($arrayServ);
                $this->showMessage('Servidor(es) eliminado(s) satisfactoriamente.');
			}
            elseif(count($arrayElim) > 0)
			{ 
                 DatServidor::elimirarServidores($arrayElim);
                 $this->showMessage('No todos los servidores fueron eliminados debido a que est&aacute;n siendo utilizados.');
			}
            else
                throw new ZendExt_Exception('SEG011');   
		}
		
		function cargarservidoresAction()
		{
			 $start = $this->_request->getPost("start");
	         $limit = $this->_request->getPost("limit");	            
			 $datosserv = DatServidor::cargarservidores($limit,$start)->toArray(true);
			 $datos = array();
			 foreach ($datosserv as $key=>$valor)
			 {
			 	$datos[$key]['ip'] = $valor['ip'];
			 	$datos[$key]['id'] = $valor['id'];
			 	$datos[$key]['text'] = $valor['text'];
			 	$datos[$key]['descripcion'] = $valor['descripcion'];
			 	$datos[$key]['tiposervidor'] = $valor['tiposervidor'];
			 	if(isset($valor['DatSerautenticacion']))
			 	{
			 	if(isset($valor['DatSerautenticacion']['tservidor'])){
			 		if($valor['DatSerautenticacion']['tservidor'] == 'ldap')	
			 			$datos[$key]['ldap'] = 'ldap';
			 		else 
			 			$datos[$key]['openldap'] = 'openldap';
			 	}
			 	$datos[$key]['cadconexion'] = $valor['DatSerautenticacion']['cadconexion'];	
			 	}
			 }
			 $canfilas = DatServidor::obtenercantserv();
			 if(isset($datos))
			 	$result =  array('cantidad_filas'=> $canfilas,'datos' => $datos);
			 else
			  	$result =  array('cantidad_filas'=> 0,'datos' => '');
			 echo json_encode($result);return;
					
		} 
 	
	}
?>