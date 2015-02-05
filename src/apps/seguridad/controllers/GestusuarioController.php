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
	class GestusuarioController extends ZendExt_Controller_Secure
	{
		function init ()
		{
			parent::init();
		}	
			
		function gestusuarioAction()
		{
			$this->render();
		}
		
		function fichausuarioAction() {
			$params = $this->getRequest()->getParams();
			$idusuario = $params['idusuario'];
			$this->view->perfilusuario = $this->getDatosUsuario($idusuario);
			$this->render();
		}
		
		function cargarRolesUsuarioAction(){
			$idusuario = $this->_request->getPost('idusuario');
			$roles = SegRol::obtenerrolesusuario($idusuario)->toArray(true);
			$cantidad = SegRol::obtenerCantRolesUsuario($idusuario);
			echo json_encode(array('cantidad_filas' => $cantidad,'datos' => $roles));	
		}
		
		public function getDatosUsuario($idusuario) {
			$perfil = SegUsuario::cargarperfilusuario($idusuario);
			$perfilusuario = '';
			if(count($perfil))
			{		
				$perfilusuario['tema'] = $perfil[0]['NomTema']['denominacion'];
	            $perfilusuario['idioma'] = $perfil[0]['NomIdioma']['denominacion'];
	            $perfilusuario['portal'] = $perfil[0]['NomDesktop']['denominacion'];
	            $perfilusuario['usuario'] = $perfil[0]['nombreusuario'];
	            $perfilusuario['idusuario'] = $perfil[0]['idusuario'];
	            $perfilusuario['activo'] = ($perfil[0]['activo']) ? 'Si' : 'No';
	            $arrayDominio = $this->integrator->metadatos->DatosDominioDadoID($perfil[0]['iddominio']);
	            $perfilusuario['dominio'] = $arrayDominio[0]['denominacion'];
	            $perfilusuario['iddominio'] = $perfil[0]['iddominio'];
	            $arrayEstructuras = $this->integrator->metadatos->MostrarCamposEstructuraSeguridad($perfil[0]['identidad']);
	            $perfilusuario['entidad'] = $arrayEstructuras[0]['text']; 
	            if($perfil[0]['idarea']) {
	                $area = $this->integrator->metadatos->EstructurasInternasDadoIDSeguridad($perfil[0]['idarea']);
	                $perfilusuario['area'] = $area[0]['denominacion'];
	            }
	            if($perfil[0]['idcargo']) 
	            {     
	                $cargo = $this->integrator->metadatos->CargoDadoIDSeguridad($perfil[0]['idcargo']);
	                $perfilusuario['cargo'] = $cargo[0]['denominacion'];
	            }
				if (isset($perfil[0]['NomFila']) && is_array($perfil[0]['NomFila']['NomValor'])){
	            	$perfilusuario['dinamico'] = array();
					foreach ($perfil[0]['NomFila']['NomValor'] as $valor) {   
						if($valor['idfila']) {	
			                $arraycampos= NomValor::cargarcamposdadovalores($valor['idvalor']);
			                $perfilusuario[$arraycampos[0]['NomCampo']['nombre']] = array($valor['valor'], $arraycampos[0]['NomCampo']['nombreamostrar']);
	                        $perfilusuario['dinamico'][] = $arraycampos[0]['NomCampo']['nombre'];
						}  		
					}
	            }
			}
			return $perfilusuario;
		}
				
		function cargarusuarioAction() {
            $limit = $this->_request->getPost('limit');
	    	$start = $this->_request->getPost('start');
	    	$idusuario = $this->global->Perfil->idusuario;
            $nombreusuario = $this->_request->getPost('nombreusuario');
	    	$dominiobuscar = $this->_request->getPost('dominiobuscar');    	
	    	$iddominio = $this->global->Perfil->iddominio;	
	    	$activar = $this->_request->getPost('activar');	
	    	$cantf = 0; 
	    	$arrayresult = array();
	    	$datosusuario = array();
	    	$usuariosSinDominio = array(); 
	    	$usuariosconpermisosadominios = array(); 
	    	$permisos = SegCompartimentacionusuario::cargardominioUsuario($idusuario);
        	$filtroDominio = $this->arregloToUnidimensional($permisos);        		
			if(count($filtroDominio))				
				$usuariosconpermisosadominios = SegUsuario::cargarUsuariosconpermisosaDominios($filtroDominio); 
			$usuariosconpermisosadominios = $this->arregloToUnidimensionalUsuario($usuariosconpermisosadominios);
			$usuariosdelDominio = SegUsuarioNomDominio::cargarUsuariosDominios($iddominio);
			$usuariosdelDominio = $this->arregloToUnidimensionalUsuario($usuariosdelDominio);	
			$usuariosSinDominio = SegUsuario::usuariosSinDominio();	
			$usuariosSinDominio = $this->arregloToUnidimensionalUsuario($usuariosSinDominio);		
			$arrayresult = array_merge($usuariosconpermisosadominios,$usuariosdelDominio); 
			$arrayresult = array_merge($arrayresult,$usuariosSinDominio);	   
            if($nombreusuario || $dominiobuscar || $activar){
            	if(count($arrayresult)){
					$datosusuario = SegUsuario::cargarGridUsuarioBuscados($nombreusuario, $arrayresult, $dominiobuscar, $activar, $limit, $start);
					$cantf = SegUsuario::cantidadFilasUsuariosBuscados($nombreusuario, $arrayresult, $dominiobuscar, $activar);
					}
            	}
	   		else{
				if(count($arrayresult)){
					$datosusuario = SegUsuario::cargarGridUsuario($arrayresult,$limit, $start);
					$cantf = SegUsuario::cantidadFilas($arrayresult);
					}
		    	}
		 	if(count($datosusuario)){
                $arrayusuario = $this->datosGridUsuarios($datosusuario);
			    $result =  array('cantidad_filas' => $cantf, 'datos' => $arrayusuario);
			    echo json_encode($result); return;
		 		}
            else{
                $result =  array('cantidad_filas' => $cantf, 'datos' => $datosusuario);
                echo json_encode($result);return;
            	}
		}	
		
		function arregloToUnidimensionalUsuario($arrayvalores){
			$array = array();
				foreach ($arrayvalores as $idusuario)
					$array[] = $idusuario['idusuario'];
				return $array;
		}
		
		function arregloToUnidimensional($arrayDominios) {
				$array = array();
				foreach ($arrayDominios as $dominios)
					$array[] = $dominios['iddominio'];
				return $array;
			}
		
        function datosGridUsuarios($datosusuario) {
            foreach($datosusuario as $key=>$usuario)
             {               
                $arrayusuario[$key]['idusuario'] = $usuario->idusuario; 
                $arrayusuario[$key]['activo'] = $usuario->activo;                        
                $arrayusuario[$key]['nombreusuario'] = $usuario->nombreusuario;
                $arrayusuario[$key]['ip'] = $usuario->ip;
                $arrayusuario[$key]['iddominio'] = $usuario->iddominio;
                $dominio = $this->integrator->metadatos->DatosDominioDadoID($usuario->iddominio);
                $arrayusuario[$key]['dominio'] = $dominio[0]['denominacion'];  
                $arrayusuario[$key]['iddesktop'] = $usuario->iddesktop;
                $arrayusuario[$key]['idtema'] = $usuario->idtema;
                $arrayusuario[$key]['ididioma'] = $usuario->ididioma;
                $arrayusuario[$key]['tema'] = $usuario->NomTema->denominacion;
                $arrayusuario[$key]['idioma'] = $usuario->NomIdioma->denominacion;
                $arrayusuario[$key]['desktop'] = $usuario->NomDesktop->denominacion;
                $arrayusuario[$key]['identidad'] = $usuario->identidad;
                $arrayusuario[$key]['idarea'] = $usuario->idarea;    
                $arrayusuario[$key]['idcargo'] = $usuario->idcargo;
                $datosservidoraut = SegUsuario::cargarservidoraut($usuario->idusuario);
                $arrayusuario[$key]['idservidor'] = $datosservidoraut[0]->idservidor;
                $arrayusuario[$key]['denominacion'] = $datosservidoraut[0]->denominacion;
                if($usuario->identidad)
                {
                    $entidad = $this->integrator->metadatos->MostrarCamposEstructuraSeguridad($usuario->identidad);
                    $arrayusuario[$key]['entidad'] = $entidad[0]['text'];
                }
                if($usuario->idarea)
                {
                    $area = $this->integrator->metadatos->EstructurasInternasDadoIDSeguridad($usuario->idarea);
                    $arrayusuario[$key]['area'] = $area[0]['denominacion'];
                }
                if($usuario->idcargo) 
                {     
                    $cargo = $this->integrator->metadatos->CargoDadoIDSeguridad($usuario->idcargo);
                    $arrayusuario[$key]['cargo'] = $cargo[0]['denominacion'];
                }
             }
            return $arrayusuario;
        }
        
		function comprobartienerol($idrol,$idusuario,$identidad)
		{		 	
		 $rolmarcado = DatEntidadSegUsuarioSegRol::cargarroles($idrol,$idusuario,$identidad);
           if(count($rolmarcado))
           {
                foreach($rolmarcado as $valor)
                  {  
                   $roles=$valor->toArray();
                    if($roles['idrol']==$idrol)                       
                     return true;
                   }
                   return false;
           }
		 	return false;		 
		}
        
        function cargarEntidadesRolAction()
        {
            $idnodo = $this->_request->getPost('nodo');
            $identidad = $this->_request->getPost('identidad');
            $idusuario = $this->_request->getPost('idusuario');
            if($idnodo == 0 && $identidad == 0)
                {          
                $entidades = DatSistemaDatEntidad::cargarentidades(); 
                 
                foreach($entidades as $key=>$entidad)
                    { 
                        $datosentidad = $this->integrator->metadatos->MostrarCamposEstructuraSeguridad($entidad->identidad);
                        $arrayentidades[$key]['id'] = $datosentidad[0]['id']; 
                        $arrayentidades[$key]['text'] = $datosentidad[0]['text'];
                    }
                  echo json_encode($arrayentidades);return;  
                }
             elseif($identidad > 0)
                 {         
                  $entidadesrol = SegRol::cargarentidadesrol($identidad);
                  foreach($entidadesrol as $key=>$rolesentidad)
                      {
                       $arrayentidadrol[$key]['id'] = $identidad._.$rolesentidad['id'];
                       $arrayentidadrol[$key]['idrol'] = $rolesentidad['id'];
                       $arrayentidadrol[$key]['text'] = $rolesentidad['text'];
                       $arrayentidadrol[$key]['leaf'] = true;
                           if($idusuario)
                           {    
                            $arrayentidadrol[$key]['checked'] = $this->comprobartienerol($rolesentidad['id'],$idusuario,$identidad)?true:false;
                           }
                           else{
                            $arrayentidadrol[$key]['checked'] = false;}
                       }
                    echo json_encode($arrayentidadrol); return;
                  }
        }		
	
		function eliminarusuarioAction() {
		$ArrayUserDel = json_decode(stripslashes($this->_request->getPost('ArrayUserDel')));
		
		foreach($ArrayUserDel as $idusuario){
		$band = false;
		$comprovar = SegUsuario::comprovarestado($idusuario);
            	if($comprovar[0]->estado == '0'){
			SegUsuario::eliminarusuario($idusuario);
			$band = true;}
		}
		if($band)
		$this->showMessage('Usuario(s) eliminado(s) satisfactoriamente.');
		else{
            	throw new ZendExt_Exception('SEG013');}
		}	
		
		function insertarusuarioAction()
		{	
            $usuario = new SegUsuario(); 
            $idservidorauth = $this->_request->getPost('idservidor');           
            $usuario->nombreusuario = $this->_request->getPost('nombreusuario');
          
            if(!$this->verificarpass($this->_request->getPost('contrasenna')))
                return false;           
            $usuario->contrasenna=md5($this->_request->getPost('contrasenna'));            
            $usuario->contrasenabd=md5($this->_request->getPost('contrasena'));
            $usuario->ip=$this->_request->getPost('ip');
            $usuario->idtema=$this->_request->getPost('idtema');
            $usuario->iddominio=$this->_request->getPost('iddominio');
            $usuario->ididioma=$this->_request->getPost('ididioma');
            $usuario->iddesktop=$this->_request->getPost('iddesktop');
            if($this->_request->getPost('idarea'))
            $usuario->idarea=$this->_request->getPost('idarea');
            if($this->_request->getPost('identidad'))    
            $usuario->identidad=$this->_request->getPost('identidad'); 
            if($this->_request->getPost('idcargo'))   
            $usuario->idcargo=$this->_request->getPost('idcargo');
            if(!$this->verificarusuario($usuario->nombreusuario))
            {
             $usuario->save();

			 $objusuarioDominio = new SegUsuarioNomDominio();
             $objusuarioDominio->idusuario =  $usuario->idusuario;
             $objusuarioDominio->iddominio =  $this->global->Perfil->iddominio;
             $modelusuarioDominio = new SegUsuarioNomDominioModel();
             $modelusuarioDominio->insertar($objusuarioDominio); 
             $array[] = $usuario->idusuario;
             $obj = new SeguridadProxyService();
             $obj->actualizarGruposDPO($array, $usuario->idcargo, 'I');            
             if($idservidorauth)
                {
                $sistemaservautenticacion = new SegUsuarioDatSerautenticacion();    
                $sistemaservautenticacion->idusuario = $usuario->idusuario;    
                $sistemaservautenticacion->idservidor = $idservidorauth;
                $model=new SegUsuarioModel();
                $model->insertarservidoraut($sistemaservautenticacion);
                }
            $this->showMessage('El usuario fue insertado satisfactoriamente.');
            }
            else 
              throw new ZendExt_Exception('SEG009');
		}		

	function modificarusuarioAction() { 
            $idusuario = $this->_request->getPost('idusuario');
            $usuario = Doctrine::getTable('SegUsuario')->find($idusuario);
            $nombreusuario = $this->_request->getPost('nombreusuario'); 
            $usuario->ip = $this->_request->getPost('ip');
            $usuario->idtema = $this->_request->getPost('idtema');
            $usuario->iddominio = $this->_request->getPost('iddominio');
            $usuario->ididioma = $this->_request->getPost('ididioma');
            $usuario->iddesktop = $this->_request->getPost('iddesktop');
             if($this->_request->getPost('idarea')) 
            	$usuario->idarea = $this->_request->getPost('idarea');
            if($this->_request->getPost('identidad'))    
            $usuario->identidad = $this->_request->getPost('identidad'); 
            if($this->_request->getPost('idcargo') && $this->_request->getPost('idcargo') != $usuario->idcargo) {
            	$array[] = $usuario->idusuario; 
             	$obj = new SeguridadProxyService();
             	$obj->actualizarGruposDPO($array, $this->_request->getPost('idcargo'), 'M');   
            	$usuario->idcargo = $this->_request->getPost('idcargo');
            }
            $idservidorauth = $this->_request->getPost('idservidor'); 
            if($usuario->nombreusuario != $nombreusuario )
                {
               
                if(!$this->verificarusuario($this->_request->getPost('nombreusuario')))
                {        
                   $usuario->nombreusuario=$this->_request->getPost('nombreusuario');           
                   if($idservidorauth)
                    {
                        $sistemaservautenticacion = new SegUsuarioDatSerautenticacion();    
                        $sistemaservautenticacion->idusuario = $usuario->idusuario;    
                        $sistemaservautenticacion->idservidor = $idservidorauth;
                    }
                     $model = new SegUsuarioModel();
                     $model->modificarusuario($sistemaservautenticacion,$usuario);
                     $this->showMessage('El usuario fue modificado satisfactoriamente.');
                 }
                 else 
                  {
                      throw new ZendExt_Exception('SEG009');
                  }
                }
                else
                {
                    $usuario->nombreusuario = $this->_request->getPost('nombreusuario');
                    $sistemaservautenticacion = null;   
                    if($idservidorauth)
                       {
                        $sistemaservautenticacion = new SegUsuarioDatSerautenticacion();    
                        $sistemaservautenticacion->idusuario = $usuario->idusuario;    
                        $sistemaservautenticacion->idservidor = $idservidorauth;
                       }
                    $model = new SegUsuarioModel();
                    $model->modificarusuario($sistemaservautenticacion,$usuario); 
                    $this->showMessage('El usuario fue modificado satisfactoriamente.');
                }
		}
		
		function cargarcombodesktopAction() {
		  $datoscombo = NomDesktop::cargarcombodesktop();
		  $datos=$datoscombo->toArray(true);
		  echo json_encode($datos);return;
		}
        
		function cargarcombodominioAction() {
       			$arraydominios = $this->integrator->metadatos->BuscarComboDominio(0,0);
			$arraydominios['datos'][count($arraydominios['datos'])] = array('iddominio'=>0,'denominacion'=>'S/D','descripcion'=>'S/D');
        		echo json_encode($arraydominios);return; 
		}	

		function cargarComboDominioBuscarAction() {
			$filtroDominio = array();
			$permisos = array();
			$dominios = array();
			$idusuario = $this->global->Perfil->idusuario;
			$iddominio = $this->global->Perfil->iddominio; 
	    	$permisos = SegCompartimentacionusuario::cargardominioUsuario($idusuario);
	    	$dominios = SegUsuario::obtenerIdDominios($iddominio);
	    	$dominios = $this->arregloToUnidimensional($dominios);
        	$permisos = $this->arregloToUnidimensional($permisos);
        	$filtroDominio = array_merge($dominios,$permisos);
	        $arraydominios = $this->integrator->metadatos->cargarComboDominioBuscar($filtroDominio);
			$arraydominios['datos'][count($arraydominios['datos'])] = array('iddominio'=>0,'denominacion'=>'S/D','descripcion'=>'S/D');
	        echo json_encode($arraydominios);return; 
			}
		
		function cargarcomboidiomaAction() {
	          $datoscombo = NomIdioma::cargarcomboidioma();
	          $datos=$datoscombo->toArray(true);
	          echo json_encode($datos);return;
        }	
				
		function cargarcombotemaAction() {
	          $datoscombo = NomTema::cargarcombotema();
	          $datos=$datoscombo->toArray(true);
	          echo json_encode($datos);return;
        }
			
		function verificarusuario($nombusuario) {
	     $usuario = SegUsuario::contarusuario($nombusuario);
	     if($usuario)
     	   return 1;
	     else 
	       return 0;
		}
		
		function verificarpass($pass) {
            $s = '/^([a-zA-ZÃ¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½])+$/';
            $sn = '/^[\da-zA-ZÃ¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½Ã¯Â¿Â½]+$/';
            $nn = '/[\d]/';
            $nl = '/[\!\\Ã¯Â¿Â½\\!\@\#\$\%\^\&\*\(\)\_\+\=\{\}\[\]\:\"\;\'\?\/\>\.\<\,\\\*\-]+$/';
            $clave = new SegRestricclaveacceso();
            $datos = $clave->cargarclave(0,0);
            $resultados = array();
            $results = array();
            if($datos->getData() == null)
                throw new ZendExt_Exception('SEG001');
            $datosacc=$datos->toArray(true);
            if(strlen($pass)< $datosacc[0]['minimocaracteres'])
            {
                $long=$datosacc[0]['minimocaracteres'];
                echo("{'codMsg':3,'mensaje':'La clave debe tener al menos'+' '+'$long'+' '+'caracteres.'}");
                return false;
            }
            if($datosacc[0]['numerica'] == 1 && $datosacc[0]['alfabetica'] == 0 && $datosacc[0]['signos'] == 0)
            {
                if(!$this->hayNumeros($pass))
                    throw new ZendExt_Exception('SEG002');
            }
            if($datosacc[0]['numerica'] == 0 && $datosacc[0]['alfabetica'] == 1 && $datosacc[0]['signos'] == 0)
            {
                if(!$this->hayLetras($pass))
                    throw new ZendExt_Exception('SEG003'); 
            }
            if($datosacc[0]['numerica'] == 0 && $datosacc[0]['alfabetica'] == 0  && $datosacc[0]['signos'] == 1 )
            {
                $signos=preg_match($sn,$pass,$resultados);
                if($signos == 1)
                    throw new ZendExt_Exception('SEG004');
            }    
            if ($datosacc[0]['numerica'] == 1 && $datosacc[0]['alfabetica'] == 1 && $datosacc[0]['signos'] == 0)
            {
                if(!$this->hayLetras($pass) || !$this->hayNumeros($pass))
                    throw new ZendExt_Exception('SEG005');
            }
            if($datosacc[0]['numerica'] == 0 && $datosacc[0]['alfabetica'] == 1 && $datosacc[0]['signos'] == 1)
            {
                $signos = preg_match($sn,$pass,$resultados);
                if(!$this->hayLetras($pass) || !$signos)
                    throw new ZendExt_Exception('SEG006');
            }
        
            if($datosacc[0]['numerica'] == 1 && $datosacc[0]['alfabetica'] == 0 && $datosacc[0]['signos'] == 1)
            {                
                $signos=preg_match_all($sn,$pass,$resultados);
                if(!$signos == 1 || !$this->hayNumeros($pass) )
                    throw new ZendExt_Exception('SEG007');
            }
            if($datosacc[0]['numerica'] == 1 && $datosacc[0]['alfabetica'] == 1 && $datosacc[0]['signos'] == 1)
            {
                $signos=preg_match($sn,$pass,$resultados);                
                if(!$this->hayNumeros($pass) || $signos==1 || !$this->hayNumeros($pass))
                    throw new ZendExt_Exception('SEG008');
            }
            return true;
        }
        
        function hayNumeros($cadena) {
            $cant = strlen($cadena);
            for($i=0; $i<$cant; $i++)
                if($cadena[$i] >= '0' && $cadena[$i] <= '9')
                    return true;
            return false; 
        }
        
        function hayLetras($cadena) {
            $cant = strlen($cadena);
            for($i=0; $i<$cant; $i++)
                if(($cadena[$i] >= 'A' && $cadena[$i] <= 'Z') || ($cadena[$i] >= 'a' && $cadena[$i] <= 'z') || $cadena[$i] == 'Ã¯Â¿Â½' || $cadena[$i] == 'Ã¯Â¿Â½')
                    return true;
            return false; 
        }
        
        function cargarestructuraAction() {
        $idEstructura = $this->_request->getPost('node');
        $arrayEstructuras = $this->integrator->metadatos->DameEstructurasinChecked($idEstructura);
        
        echo json_encode($arrayEstructuras);return;        
        }
        
        function cargarareasAction() {
        $idestructura = $this->_request->getPost('identidad');
        $idarea =  $this->_request->getPost('node');
        if($idarea ){  
        $arrayareas = $this->integrator->metadatos->DameHijosInternaSeguridadSinCheked($idarea);
        }
        else{
        $arrayareas = $this->integrator->metadatos->DameEstructurasInternasSeguridadSinCheked($idestructura,true);}
        echo json_encode($arrayareas);return;        
        }
        
        function cargarcargosAction() {
                $idarea =  $this->_request->getPost('idarea');
                $arraycargos = $this->integrator->metadatos->BuscarCargosPorTiposSeguridad($idarea);
                if(count($arraycargos))
		{	 
			foreach($arraycargos as $key=>$cargo)
			{
			$cargosformados[$key]['text'] = $cargo['Asignacion']['text'];
			$cargosformados[$key]['id'] = $cargo['id'];
			$cargosformados[$key]['idcargo'] = $cargo['id'];
			$cargosformados[$key]['leaf'] = true;                  
			}
			echo json_encode($cargosformados);  
		}
		else 
     		echo json_encode($arraycargos);
        }
        
        function cargarcomboservidoresautAction() {
            $servidoresaut = DatServidor::cargarcomboservidoresaut();        
            $datos=$servidoresaut->toArray(true);
            echo json_encode($datos);return;
        }
        
        function cargarrolesAction() {
        $start = $this->_request->getPost("start");
        $limit = $this->_request->getPost("limit");
	    $idusuario = $this->_request->getPost("idusuario");
        $rolbuscado = $this->_request->getPost("rolbuscado");
        $filtroDominio = $this->global->Perfil->iddominio;
        $arrayResult = array();
        $arrayRolesDominio = array();
        $arrayRolesPermisoDominio = array();
        $arrayRolesDominio = SegRolNomDominio::RolesdelDominio($filtroDominio);//todos los roles del dominio
        $arrayRolesDominio = $this->arregloToUnidimensionalRoles($arrayRolesDominio);
        $arrayRolesPermisoDominio = SegCompartimentacionroles::cargarRolesDominio($filtroDominio);
        $arrayRolesPermisoDominio = $this->arregloToUnidimensionalRoles($arrayRolesPermisoDominio);
        $arrayResult = array_merge($arrayRolesDominio,$arrayRolesPermisoDominio);
        if(!$rolbuscado) {
        	$result = SegRol::obtenerrolesasociados($limit, $start, $idusuario, $arrayResult); 
            $cantrol = SegRol::cantrolesDominio($arrayResult);
        	}
        else {
            $result = SegRol::obtenerRolesBuscado($rolbuscado,$limit,$start,$idusuario); 
            $cantrol = SegRol::cantrolBuscados($rolbuscado);
        	}
        if(count($result)) {
                foreach($result as $key=>$rol) { 
                    $arrayroles[$key]['idrol'] = $rol['idrol']; 
                    $arrayroles[$key]['denominacion'] = $rol['denominacion'];
                    $arrayroles[$key]['abreviatura'] = $rol['abreviatura'];
                    $arrayroles[$key]['descripcion'] = $rol['descripcion'];
		            if (count($rol['DatEntidadSegUsuarioSegRol']))
			            $arrayroles[$key]['estado'] = 1;
		            else
			            $arrayroles[$key]['estado'] = 0;
                	}
                $result = array ('cantidad_filas' => $cantrol, 'datos' => $arrayroles); 
                echo json_encode($result);return;
            	}
            else
              echo json_encode($result);return; 
        }
        
        public function arregloToUnidimensionalRoles($rolesDominio){
        	$array = array();
			foreach ($rolesDominio as $rol)
				$array[] = $rol['idrol'];
			return $array;
        }
        
		function arregloBidimensionalToUnidimensional($arrayEstructuras) {
			$array = array();
			foreach ($arrayEstructuras as $est)
				$array[] = $est['idestructura'];
			return $array;
		}

		function cargarentidadesReporteAction() {
	            $idusuario = $this->_request->getPost("idusuario");
	            $idrol = $this->_request->getPost("idrol");
	            $idEstructura = $this->_request->getPost("node");
	            $iddominio = $this->_request->getPost("iddominio");
	            $arrayEst = DatEntidadSegUsuarioSegRol::cargarentidadesusuariorol($idusuario, $idrol);
	            $arrayEst = $this->arregloBidimensionalToUnidimensional($arrayEst);
	            $arrayEstNoRol = DatEntidadSegUsuarioSegRol::cargarentidadesusuarionorol($idusuario, $idrol);
	            $arrayEstNoRol = $this->arregloBidimensionalToUnidimensional($arrayEstNoRol);
	            $arrayEstructuras = $this->integrator->metadatos->buscarHijosEstructurasDadoArray($iddominio, $idEstructura, 1, $arrayEst);
	        	$estructuras = array();
	            foreach ($arrayEstructuras as $key=>$est) {
	            	$estructuras[$key] = $est;
	            	$estructuras[$key]['disabled'] = true;
	            	if ($est['unchecked'] || !$est['checked'] || count(array_intersect($arrayEstNoRol, array($est['id']))))
	            		unset($estructuras[$key]['checked']);
	            }
	            echo json_encode($estructuras);return;       
	        }
		
        function cargarentidadesAction()
        {
            $idusuario = $this->_request->getPost("idusuario");
            $idrol = $this->_request->getPost("idrol");
            $idEstructura = $this->_request->getPost("node");
            $tcheck = $this->_request->getPost('tcheck'); 
            $iddominio = $this->_request->getPost("iddominio");
            $arrayEst = DatEntidadSegUsuarioSegRol::cargarentidadesusuariorol($idusuario, $idrol);
            $arrayEst = $this->arregloBidimensionalToUnidimensional($arrayEst);
            $arrayEstNoRol = DatEntidadSegUsuarioSegRol::cargarentidadesusuarionorol($idusuario, $idrol);
            $arrayEstNoRol = $this->arregloBidimensionalToUnidimensional($arrayEstNoRol);
            $arrayEstructuras = $this->integrator->metadatos->buscarHijosEstructurasDadoArray($iddominio, $idEstructura, 1, $arrayEst);
        	$estructuras = array();
            foreach ($arrayEstructuras as $key=>$est) {
            	$estructuras[$key] = $est;
            	if ($est['unchecked'] || count(array_intersect($arrayEstNoRol, array($est['id']))))
            		unset($estructuras[$key]['checked']);
            }
            echo json_encode($estructuras);return;       
        }
        
        function gestionarMarcadoEntidades($arrayEstructuras, $arrayEst, $idEstructura, $tcheck) {
            $cant = count($arrayEstructuras);
            if($tcheck == 'marcado')
            {
                if(!$this->estaCheckEnBd($arrayEst, $idEstructura))
                {
                    for($i=0; $i<$cant;$i++)
                        $arrayEstructuras[$i]['checked'] = true;
                }
                else
                    $arrayEstructuras = $this->marcarEntidades($arrayEstructuras, $arrayEst);                    
            }
            else if($tcheck == 'desmarcado')
            {
                if($this->estaCheckEnBd($arrayEst, $idEstructura))
                {
                    for($i=0; $i<$cant;$i++)
                        $arrayEstructuras[$i]['checked'] = false;    
                }
                else
                    $arrayEstructuras = $this->marcarEntidades($arrayEstructuras, $arrayEst);  
            }
            else
                $arrayEstructuras = $this->marcarEntidades($arrayEstructuras, $arrayEst);
            return $arrayEstructuras;     
        }
        
        function estaCheckEnBd($arrayEst, $idEstructura) {
            $cant = count($arrayEst);
            for($i=0; $i<$cant;$i++)
            {
                if($arrayEst[$i]['identidad'] == $idEstructura)
                    return true;   
            }
            return false;        
        }
        
        function cargarEntidadesDadoDominio($arrayEntidades, $idEntidad) {
            return $this->buscarIdCargar($arrayEntidades, $idEntidad);
        }
        
        function buscarIdCargar($array, $idEntidad=0) {
            $resultado = array();
            if(!$idEntidad)
            {
                foreach($array as $valor)
                {
                    $temp = explode("-",$valor);
                    if(!$this->existeValorArray($resultado, substr($temp[0],0, strlen($temp[0])-2)))
                        $resultado[] = substr($temp[0],0, strlen($temp[0])-2);        
                }    
            }
            else
            {
                foreach($array as $valor)
                {    
                    $temp = explode("-",$valor);
                    $res = $this->existeValorArrayNoUltimo($temp,$idEntidad);
                    if($res != -1)
                    {    
                        if(!$this->existeValorArray($resultado, substr($res,0,strlen($res)-2)))
                            $resultado[] = substr($res,0,strlen($res)-2);        
                    }
                }    
            }
            return $resultado; 
        }
        
        function existeValorArray($array, $valor) {
            foreach($array as $valor1)
                if($valor1 == $valor)
                    return true;    
            return false;    
        }
        
        function marcarEntidades($arrayEstructuras, $arrayEst) {
            $cant = count($arrayEstructuras);
            $cant1 = count($arrayEst);
            for($i=0; $i<$cant;$i++)
            {
                for($j=0; $j<$cant1; $j++)
                {
                    if($arrayEstructuras[$i]['id'] == $arrayEst[$j]['identidad'])
                    {
                        $arrayEstructuras[$i]['checked'] = true;
                        break;    
                    }       
                }
            }
            return $arrayEstructuras;
        }
        
        function ponerComoHoja($array,$arrayEntidades) {      
            $n = count($array);
            for($i=0; $i<$n; $i++)
            {
                if($array[$i]['rgt'] - $array[$i]['lft'] == 1)
                {
                    $arrayEstructuras1 = $this->integrator->metadatos->DameEstructurasInternasSeguridad($array[$i]['idestructura'],true);
                    if(!count($arrayEstructuras1))
                        $array[$i]['leaf'] = true;
                }
                else
                {
                  if($this->tieneHijasDominio($array[$i]['idestructura'],$arrayEntidades) == -1)
                    {
                      $array[$i]['leaf'] = false;
                    }
                }
            }
            return $array;        
        }
        
        function tieneHijasDominio($identidad, $arrayEntidades) {
            if(count($arrayEntidades)== 1)
              return -1;           
            foreach($arrayEntidades as $valor)
            {   
                $temp = explode("-",$valor);
                if(count($temp) > 1)
                {  
                    $res = $this->existeValorEnCadenaNoUltimo($temp,$identidad);			
                    if($res == -1)
                          return -1;
                }
            }
        return 1;
        }
        
        function obterEntidadesPonerChecBox($array) {
            $resultado = array();
            foreach($array as $valor)
            {
                $temp = explode("-",$valor);
                $resultado[] = substr($temp[count($temp)-1], 0, strlen($temp[count($temp)-1])-2);             
            }
            return $resultado;             
        }
        
        function ponerCheck($arrayEntidades, $arrayIdEntidadesUltimas) {
            $cant = count($arrayEntidades);
            for($i=0; $i<$cant; $i++)
            {
                foreach($arrayIdEntidadesUltimas as $valor)
                {
                    if($arrayEntidades[$i]['idestructura'] == $valor)
                    {
                        $arrayEntidades[$i]['checked'] = false;
                        break;    
                    }
                }
            }
            return $arrayEntidades;             
        }
        
        function asignarrolesAction() {
            $arrayUsuario = json_decode(stripslashes($this->_request->getPost('arrayUsuario')));
            $idrol = $this->_request->getPost("idrol");
            $arrayTiene = json_decode(stripslashes($this->_request->getPost('arrayTiene')));
            $arrayEstructuras = json_decode(stripslashes($this->_request->getPost('arrayEntidades'))); //estructura chekeadas
            $arrayEstructurasEliminar = json_decode(stripslashes($this->_request->getPost('arrayEntidadesEliminar')));//estructuras a eliminar ke son las ke se deschekearon 
            $arrayPadres = json_decode(stripslashes($this->_request->getPost('arrayPadres')));//array de nodos marcados sin desplegar, es decir los ke hay ke buscar sus hijos y ponerlos como chekeados
            $arrayPadresEliminar = json_decode(stripslashes($this->_request->getPost('arrayPadresEliminar')));//array de nodos desmarcados sin desplegar, hay ke buscar sus hijos y ponerlos en el array a eliminar
            
        	$arrayHijos = array();
            if (count($arrayPadres)) {
            	$arrayHijos = $this->integrator->metadatos->buscarArbolHijosEstructuras($arrayPadres);
            	$arrayHijos = $this->arregloBidimensionalToUnidimensional($arrayHijos);
            }
            
        	$arrayHijosEliminar = array();
            if (count($arrayPadresEliminar)) {
            	$arrayHijosEliminar = $this->integrator->metadatos->buscarArbolHijosEstructuras($arrayPadresEliminar);
            	$arrayHijosEliminar = $this->arregloBidimensionalToUnidimensional($arrayHijosEliminar);
            }
            
            $arrayEstructurasEliminar = array_merge($arrayEstructurasEliminar, $arrayHijosEliminar);
            $arrayEstructuras = array_merge($arrayEstructuras, $arrayHijos);
	    	foreach($arrayUsuario as $idusuario)
	     	{
		    	$arrayEstGeneral = DatEntidadSegUsuarioSegRol::cargarentidadesusuariorol($idusuario[0],$idrol);
		    	$arrayEstGeneral = $this->arregloBidimensionalToUnidimensional($arrayEstGeneral);
		    	$arrayEstructurasUsuario = $arrayEstructuras;
		    	if(count($arrayEstGeneral))
		        	$arrayEstructurasUsuario = array_diff($arrayEstructurasUsuario, $arrayEstGeneral);
		    	
		    	$cantEstElim = count($arrayEstructurasEliminar);
		    	$cantEstrMarcadas = count($arrayEstructurasUsuario);
		    	$cantEstrGeneral = count($arrayEstGeneral);
		    	if($cantEstElim)
		    	{
		        	if(!$cantEstrMarcadas && ($cantEstElim == $cantEstrGeneral))
		        	{
		            	DatEntidadSegUsuarioSegRol::eliminarusuariorol($idusuario[0],$idrol);
			    		echo "{'bien':4}"; //Se quitan todas las entidades al rol
		            	return;
		        	}
		    	}

		   		$arrayExternas = $this->integrator->metadatos->cantIdEstructurasDominio($idusuario[1], $arrayEstructurasUsuario, 1);
			   	if(!count($arrayExternas) && !count($arrayEstGeneral)) {
			    	echo "{'bien':2}";
			    	return ;
			   	}
		    
		    	if(count($arrayEstructurasEliminar))
		    	{
		      		foreach($arrayEstructurasEliminar as $entel)
		            {
		               $entidadusuariorolelim = new stdClass();
		               $entidadusuariorolelim->idusuario = $idusuario[0];
		               $entidadusuariorolelim->idrol = $idrol;
		               $entidadusuariorolelim->identidad = $entel;
		               $arrayentidadusuariorolElim[] = $entidadusuariorolelim;
		            }
		    	}
		     
		    	if(count($arrayEstructurasUsuario))
		    	{ 
		     		$datos = DatEntidadSegUsuarioSegRol::comprobarExisteRol($idusuario[0],$idrol);
		     		foreach($arrayEstructurasUsuario as $entins)
		            {
		               	if(!$this->verificarRol($entins, $datos)) { 
							$entidadusuariorolins = new DatEntidadSegUsuarioSegRol();
			               	$entidadusuariorolins->idusuario = $idusuario[0];
			               	$entidadusuariorolins->idrol = $idrol;
			               	$entidadusuariorolins->identidad = $entins;
			               	$arrayentidadusuariorolAdd[] = $entidadusuariorolins;
		               	}
		            }
		     	}
	     	}
			if (!count($arrayentidadusuariorolAdd) && !count($arrayentidadusuariorolElim))
				echo "{'bien':3}"; //No se hace nada
			else {
				$model = new SegUsuarioModel();
				if($model->asignarroles($arrayentidadusuariorolAdd, $arrayentidadusuariorolElim ))
					echo "{'bien':1}";
				else
					echo "{'bien':2}";
			}
        }
        
        function verificarRol($elem, $array) { 
         foreach($array as $valores)
            if($elem == $valores['identidad'])
               return true;
            return false; 
        }
        
        function arrayUltimasEntidades($array) {
            $cantidad = count($array);
            $externa = explode('_',$array[$cantidad - 1]);
            if($externa[1] == 'e')
                return $externa[0];
            else
                return 0;    
        }
        
        function agregarChekeados(&$arreglo, &$arreglo1, $id, $cadenaPadre = '') {
            if($cadenaPadre == '')
            {
                $pos = $this->obtenerPos($arreglo, $id);
                $cadenaPadre = $arreglo[$pos];
            }
            if(substr($cadenaPadre,-1) == 'e')
            {
                $arrayEstructuras = $this->integrator->metadatos->DameEstructuraSeguridad($id);
                $arrayEstructuras1 = $this->integrator->metadatos->DameEstructurasInternasSeguridad($id,true);    
                $arrayEstructuras = array_merge_recursive($arrayEstructuras, $arrayEstructuras1);        
            }
            else
                $arrayEstructuras = $this->integrator->metadatos->DameHijosInternaSeguridad($id);
            foreach($arrayEstructuras as $valor)
            {
                $cad1 =   ($valor['tipo'] == 'externa') ? $cadenaPadre.'-'.$valor['id'].'_e' : $cadenaPadre.'-'.$valor['id'].'_i'; 
                $arreglo[] = $cad1;
                $arreglo1[] = $valor['id'];
                $this->agregarChekeados(&$arreglo, &$arreglo1, $valor['id'], $cad1);      
            }         
        }
        
        function agregaraArrayEliminar(&$arreglo, $id, $tipo='') {
            if($tipo == 'externa')
            {
                $arrayEstructuras = $this->integrator->metadatos->DameEstructuraSeguridad($id);
                $arrayEstructuras1 = $this->integrator->metadatos->DameEstructurasInternasSeguridad($id,true);    
                $arrayEstructuras = array_merge_recursive($arrayEstructuras, $arrayEstructuras1);        
            }
            else{
                    $arrayEstructuras = $this->integrator->metadatos->DameHijosInternaSeguridad($id);}
            foreach($arrayEstructuras as $valor)
            {
                $arreglo[] = $valor['id'];
                $this->agregaraArrayEliminar(&$arreglo, $valor['id'], $valor['tipo']);      
            }         
        }
        
        function obtenerPos($array, $id) {
            $cant = count($array);
            for($i=0; $i<$cant;$i++)
                if($this->obtenerUltimoIdCadena($array[$i]) == $id)
                    return $i;
            return -1;
        }
        
        function obtenerUltimoIdCadena($cadena) {
            $array = explode('-', $cadena);
            return substr($array[count($array)-1],0,strlen($array[count($array)-1])-2);
        }

	    function cambiarpasswordAction() {
         $usuario = $this->_request->getPost('usuariop');
         $newpass = $this->_request->getPost('contrasenap');
         $verificar = SegUsuario::verificarpass($usuario);
	        if($this->verificarpass($newpass))
	            {
					$objusuario = Doctrine::getTable('SegUsuario')->find($verificar[0]->idusuario);
					$objusuario->contrasenna = md5($newpass);
					$objusuario->contrasenabd = md5($newpass);
					$objusuario->save();
					$this->showMessage('Su contrase&ntilde;a ha sido cambiada correctamente.');
	            }
                else
                  return false;
	    }
        
		function validarasignacionrolesAction(){
			$arrayUsuario = json_decode(stripslashes($this->_request->getPost('ArrayUsuarios')));
			$arrayroles = array();
			$result = SegUsuario::validarasignacionroles($arrayUsuario);
			$recorrer = $result[0]['DatEntidadSegUsuarioSegRol'];		
			$temp = array();
			$temp = $this->borrarCampoUsurio($recorrer);				
			unset($result[0]);
			foreach($result as $record)
			{
			if(!$this->compararArreglos($temp,$this->borrarCampoUsurio($record['DatEntidadSegUsuarioSegRol'])))
				{
				throw new ZendExt_Exception('SEG022');
				}
			}
			echo "{success: true}";
		}
	
		function borrarCampoUsurio($recorrer){
		$temporal = $recorrer;
			foreach($recorrer as $key=>$valores)
			{
			unset($temporal[$key]['idusuario']);
			}
		return $temporal;
		}
		
		function compararArreglos($array1, $array2){ 
		foreach($array1 as $key=>$value)
		{
		if(!(isset($array2[$key]) && $value['idrol'] == $array2[$key]['idrol'] && $value['identidad'] == $array2[$key]['identidad']))
	    return false;	
		}
		return true;
		}
		
		function ActivarUsuarioAction(){
			$ArrayUserActivar = json_decode(stripslashes($this->_request->getPost('arrUsuarioAct')));
			$bandera = true;
			foreach($ArrayUserActivar as $usuarios){
				if($bandera){		
					$cadena.= $usuarios;
					$bandera = false;
					}
				else				
					$cadena.=",$usuarios";
			}
			if(SegUsuario::activarUsuarios($cadena))
				echo "{success: true}"; 	
		}
		
		function DesactivarUsuarioAction(){
			$arrUsuarioDesact = json_decode(stripslashes($this->_request->getPost('arrUsuarioDesact')));
			$bandera = true;
			foreach($arrUsuarioDesact as $usuarios){
				if($bandera){		
					$cadena.= $usuarios;
					$bandera = false;
					}
				else				
					$cadena.=",$usuarios";
			}
			if(SegUsuario::DesactivarUsuarios($cadena))
				echo "{success: true}"; 	
		}
    }	
?>
