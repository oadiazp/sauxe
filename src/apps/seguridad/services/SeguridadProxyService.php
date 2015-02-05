<?php
/**
 * SeguridadProxyService
 * Interfaz o Proxy utilizado por el SGIS para brindar los servicios de
 * de autenticacion, autorizacion, auditoria y administracion de perfiles
 * 
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo	
 * @author Darien Garcia Tejo 
 * @version 1.0-0
 */
class SeguridadProxyService {
	
	private function devolverIp() {
        if ($_SERVER) 
        { 
            if($_SERVER['HTTP_X_FORWARDED_FOR'])
                $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            else{
                if($_SERVER['HTTP_CLIENT_IP']) 
                     $realip = $_SERVER['HTTP_CLIENT_IP']; 
                    else 
                     $realip = $_SERVER['REMOTE_ADDR'];  
                }
        } 
        else{ 
            if ( getenv( "HTTP_X_FORWARDED_FOR" ) ) 
                $realip = getenv( "HTTP_X_FORWARDED_FOR" ); 
            elseif ( getenv( "HTTP_CLIENT_IP" ) ) 
                $realip = getenv( "HTTP_CLIENT_IP" ); 
            else 
             $realip = getenv( "REMOTE_ADDR" ); 
             }
        return $realip;
    }
    
    private function verificarIP($ip,$arraybd) {
    $arraybd = explode(',',$arraybd);
    $ip = explode('/',$ip);    
     foreach($arraybd as $key=>$array)
       $result[$key] = explode('/',$array);
     foreach($result as $rest) 
       {
            if($this->compreobarIP($ip,$rest,$rest[1]))
            return true;
       }
     return false;
    }
    
    private function compreobarIP($valor1,$valor2,$opc) {
        $result1 = explode('.',$valor1[0]);
        $result2 = explode('.',$valor2[0]);
     if($opc == '0')
        return 1;
     elseif($opc == '8')
     {  
      if( ($result1[0] == $result2[0]) )
        return 1;
     }
     elseif($opc == '16')
     {
      if( ($result1[0] == $result2[0]) && ($result1[1] == $result2[1]) )
        return 1;
     }
     elseif($opc == '24')
     {
      if( ($result1[0] == $result2[0]) && ($result1[1] == $result2[1]) && ($result1[2] == $result2[2]))
        return 1;
     }
     elseif($opc == '32')
     {
    if( ($result1[0] == $result2[0]) && ($result1[1] == $result2[1]) && ($result1[2] == $result2[2]) && ($result1[3] == $result2[3]))
        return 1; 
     }
     else
     {
    if( ($result1[0] == $result2[0]) && ($result1[1] == $result2[1]) && ($result1[2] == $result2[2]) && ($result1[3] == $result2[3]))
        return 1; 
     }
     return 0;
    }
    
    /**
	 * authenticateUser
	 * Brinda el servicio de autenticacion por usuario y password
	 * 
	 * @param string $user - Usuario que se esta autenticando
	 * @param string $password - Clave de acceso del usuario
	 * @return string - Certificado o token de seguridad asignado al usuario. 
	 */
	 
	public function authenticateUser($user, $password) {
        $mac=1;

        $verificar = SegUsuario::verificarpass($user);
        if(!$verificar[0]->activo)
        	return 0;
       if($verificar[0]->ip)
            {  
            $ip = $this->devolverIp(); 
            $acceso = $this->verificarIP($ip,$verificar[0]->ip);
            }
         elseif($verificar[0]->idusuario) 
                $acceso = true;
            else
                $acceso = false;
        if($acceso)   
          {  
           $observ = SegUsuarioDatSerautenticacion::cantservidoraut($verificar[0]->idusuario);
        if(count($observ))
        { 
	  $SA = DatServidor::usuarioservidoraut($observ[0]->idservidor); 
	  $type = 'openLdap';
	  $result = $this->$type($SA, $user, $password);
          if($result) {       
               if($verificar[0]->contrasenna != md5($password)) { 
                    $objusurio = Doctrine::getTable('SegUsuario')->find($verificar[0]->idusuario);
                    $objusurio->contrasenna = md5($password);
                    $objusurio->save();
                    $result = $this->returnCertificate($mac,$verificar[0]->idusuario);
                    return $result;
                   }
               else {   
                    $result = $this->returnCertificate($mac,$verificar[0]->idusuario);
                    return $result;
                   }
              }
          else                       
                return 0;
        }
        else {  
            $objUsuario = new SegUsuario();
            $resultUsuario = $objUsuario->comprobarusuario($user, md5($password));
            if(count($resultUsuario)) {
                $result = $this->returnCertificate($mac,$resultUsuario[0]['idusuario']);
                return $result;
                }
            else return 0;
         }
       }
       return 0;
    }
	
	private function openLdap($SA, $user, $password) {	  
	  $serverOpenLdap = $SA[0]->ip;
	  $dc = explode('.',$SA[0]->denominacion);
	  $AD_Auth_User = "cn=Administrador,dc={$dc[0]},dc={$dc[1]}";
    	  $AD_Auth_PWD = "1qazxsw2";  
	  $objOpenLdap = new ZendExt_OpenLdap($AD_Auth_User, $AD_Auth_PWD, $serverOpenLdap, $user, $password);
	  $result = $objOpenLdap->authenticate();
	  $array = $result->getMessages();
	  if(!$array[0]) 
		return true;
	  return false;
	}

	private function Ldap($objldap, $user, $password) {
	  $ldap = $objldap[0]->denominacion;
          $servidor = explode('.',$ldap);
          $options = array('server' => array('host'=>$ldap,'accountDomainName'=> $ldap,'baseDn'=>"OU=$servidor[0] Domain Users,DC=$servidor[0],DC=$servidor[1],DC=$servidor[2]"));
          $auth = Zend_Auth::getInstance();
          $adapter = new Zend_Auth_Adapter_Ldap($options, $user, $password);
          $result = $auth->authenticate($adapter);
          $array = $result->getMessages();
	  if(!$array[0]) 
		return true;
	  return false;	
	}
 	
	/**
     * returnCertificate
     * Actualiza o crea un certificado al usuario autenticado
     * 
     * @param integer $idusuario - Id del usuario que se esta autenticando
     * @param string $mac - Mac del usuario
     * @return string - Certificado o token de seguridad asignado al usuario. 
     */
    private function returnCertificate($mac, $idusuario) { 
	    $objCertificado = new SegCertificado();
	    $resultCertificado = $objCertificado->existecertificado($idusuario);
	    if(count($resultCertificado))
	            $nuevocertificado = $this->updateCertificate($mac,$resultCertificado[0]['idcertificado'],$idusuario);
	        else 
	        {
	            $modelcertificado= new SegCertificadoModel();
	            $objCertificado->mac = $mac;
	            $objCertificado->valor = $this->createCertificate($idusuario);                      
	            $objCertificado->idusuario = $idusuario;
	            $nuevocertificado = $objCertificado->valor;                    
	            $modelcertificado->insertarcertificado($objCertificado);                        
	        }                    
	        return $nuevocertificado;
    }
 
    /**
     * createCertificate
     * Crea un certificado a partir del identificador del usuario
     * 
     * @param integer $iduser - Identificador del usuario
     * @ignore - El certificado no se esta generando correctamente
     */
    private function createCertificate($iduser) {
        //$numero = rand(1, 100000000000000);
        return 1 . $iduser /*+ $numero*/;
    }
    
    /**
     * updateCertificate
     * Cambia el valor del certificado asignado al usuario
     * 
     * @param string $mac - Mac de la PC cliente
     * @param string $idcertificate - Identificador del certificado
     * @param integer $iduser - Identificador del usuario
     * @return string - Nuevo certificado asignado al usuario
     */ 
    private function updateCertificate($mac, $idcertificate, $iduser) {
        $objCertificado = Doctrine::getTable('SegCertificado')->find($idcertificate);
        $objCertificado->mac = $mac;
        $objCertificado->valor = $this->createCertificate($iduser); 
        $model = new SegCertificadoModel();
        return  $model->modificarcertificadoServicio($objCertificado);
    }

	/**
	 * loadProfile
	 * Brinda el servicio de configuracion del perfil del usuario autenticado
	 * 
	 * @param string $certificate - Certificado o token de seguridad asignado al usuario.
	 * @return array - Datos del perfil del usuario autenticado.
	 */
	public function getProfile($certificate) {   
		$objcertificado = new SegCertificado();
		$datos = $objcertificado->verificarcertificado($certificate);
		if(count($datos)) {			
			$idusuario = $datos[0]->idusuario;
			$perfil = SegUsuario::cargarperfilusuario($idusuario);
			$perfilusuario=array();
			if(count($perfil))
			{		
			$perfilusuario['tema'] = $perfil[0]['NomTema']['tema'];
            $perfilusuario['idioma'] = $perfil[0]['NomIdioma']['idioma'];
            $perfilusuario['portal'] = $perfil[0]['NomDesktop']['desktop'];
            $perfilusuario['idusuario'] = $perfil[0]['idusuario'];
            $perfilusuario['usuario'] = $perfil[0]['nombreusuario'];
            $perfilusuario['identidad'] = $perfil[0]['identidad'];
            $perfilusuario['idarea'] = $perfil[0]['idarea'];
            $perfilusuario['idcargo'] = $perfil[0]['idcargo'];
            $perfilusuario['iddominio'] = $perfil[0]['iddominio'];
            $Entidades = $this->cantidadEstructuras($perfil[0]['iddominio']);
            if(count($Entidades) == 1)
                $perfilusuario['cantidad'] = $Entidades[0]['idestructura']; 
            else
                $perfilusuario['cantidad'] = 0;  
		    $integrator = ZendExt_IoC::getInstance();
            $arrayEstructuras = $integrator->metadatos->MostrarCamposEstructuraSeguridad($perfil[0]['identidad']);
            $perfilusuario['entidad'] = $arrayEstructuras[0]['text']; 
            if($perfil[0]['idarea'] != 0)
                {
                $area = $integrator->metadatos->EstructurasInternasDadoIDSeguridad($perfil[0]['idarea']);
                if (isset($area[0]['text']))
                	$perfilusuario['area'] = $area[0]['text'];
		else $perfilusuario['area'] = '';
                }
            if($perfil[0]['idcargo'] != 0) 
                {     
                $cargo = $integrator->metadatos->CargoDadoIDSeguridad($perfil[0]['idcargo']);
                $perfilusuario['cargo']=$cargo[0]['denominacion'];
                }
				if (isset($perfil[0]['NomFila']) && is_array($perfil[0]['NomFila']['NomValor'])){
                    $perfilusuario['dinamico'] = array();
					foreach ($perfil[0]['NomFila']['NomValor'] as $valor) 
					{   
						if($valor['idfila'])
						{	
		                	$arraycampos= NomValor::cargarcamposdadovalores($valor['idvalor']);
		                    $perfilusuario[$arraycampos[0]['NomCampo']['nombre']] = $valor['valor'];
                            $perfilusuario['dinamico'][] = $arraycampos[0]['NomCampo']['nombre'];
						}  		
					}
                }
			}
			return $perfilusuario; 	
		}
		else 
			throw new ZendExt_Exception('SGIS003');
	}
    
     private function cantidadEstructuras($iddominio) {
     	$integrator = ZendExt_IoC::getInstance();
     	return $integrator->metadatos->buscarIdEstructurasDominio($iddominio, array(), 1);
     }
     
    private function arrayUltimasEntidades($array) {
            $cantidad = count($array);
            $externa = explode('_',$array[$cantidad - 1]);
            if($externa[1] == 'e')
                return $externa[0];
            else
                return 0;    
        }
        
	/**
	 * loadDomain
	 * Obtener el dominio de entidades a la que el usuario tiene acceso
	 * 
	 * @param string $certificate - Certificado o token de seguridad asignado al usuario.
	 * @return array - Entidades a las que el usuario tiene acceso.
	 */
	public function loadDomain($certificate, $idEstructura) {
        $objcertificado = new SegCertificado();                
        $datos = $objcertificado->verificarcertificado($certificate);                    
        $idusuario = $datos[0]->idusuario;
      
        if(count($datos))
        {
            $iddominio = SegUsuario::cargardominiodeusuario($idusuario);
	        $integrator = ZendExt_IoC::getInstance();
            $arrayEstructuras = $integrator->metadatos->buscarHijosEstructuras($iddominio[0]->iddominio, $idEstructura, 0 , 0, 1);
            return $arrayEstructuras;                            
        }
		else 
			throw new ZendExt_Exception('SGIS003');
	}
    
    /**
     * loadFormalEntity
     * Obtener las entidades formales a las que el usuario tiene acceso
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $param - Id del organo.
     * @return array - Entidades formales a las que el usuario tiene acceso.
     */
    public function getEntityByIdOrgano($certificate,$param) {
        $objcertificado = new SegCertificado();                
        $datos = $objcertificado->verificarcertificado($certificate);                    
        $idusuario = $datos[0]->idusuario;
        if(count($datos))
        { 
            $iddominio = SegUsuario::cargardominiodeusuario($idusuario);
            $integrator = ZendExt_IoC::getInstance();
            $arrayEstructuras = $integrator->metadatos->ObtenerEstructurasPorIdOrgano($iddominio[0]->iddominio,$param);
            return $arrayEstructuras;                             
       }
        else 
            throw new ZendExt_Exception('SGIS003');
    } 
    
    private function cargar($array) {
     $resultado = array();
            foreach($array as $valor)
            {
                $temp = explode("-",$valor);
                foreach($temp as $valor1)
                {
                    if($valor1[strlen($valor1)-1] == 'e'){
                        if(!$this->existeValorArray($resultado, substr($valor1,0, strlen($valor1)-2)))
                            $resultado[] = substr($valor1,0, strlen($valor1)-2);}
                }
            }
            return $resultado;
    }           
	
	/**
	 * ObtenerEstructurasNoFormales
	 * Obtener las estructuras a las que tiene acceso un usuario
	 * 
	 * @param string $certificate - Certificado o token de seguridad asignado al usuario.
	 * @return array - Estructuras a las que tiene acceso
	 */
	public function ObtenerEstructurasNoFormales($certificate) {
       		$objcertificado = new SegCertificado();                
       		$datos = $objcertificado->verificarcertificado($certificate);                    
       		$idusuario = $datos[0]->idusuario;		
			$entidades = DatEntidadSegUsuarioSegRol::cargarentidadesusuario($idusuario);
			$entidades = $this->arregloBidimensionalToUnidimensional($entidades);
			$global = ZendExt_GlobalConcept::getInstance();
      		$iddominio = $global->Perfil->iddominio;
      		$integrator = ZendExt_IoC::getInstance();
      		$arrayultimas = $integrator->metadatos->buscarIdEstructurasDominio($iddominio, $entidades, 2);
      		$arrayultimas = $this->arregloBidimensionalToUnidimensional($arrayultimas);
      		return $arrayultimas;
	}
	
	/**
	 * ObtenerEstructurasFormales
	 * Obtener las estructuras a las que tiene acceso un usuario
	 * 
	 * @param string $certificate - Certificado o token de seguridad asignado al usuario.
	 * @return array - Estructuras a las que tiene acceso
	 */
	public function ObtenerEstructurasFormales($certificate) {
       		$objcertificado = new SegCertificado();                
       		$datos = $objcertificado->verificarcertificado($certificate);                    
       		$idusuario = $datos[0]->idusuario;		
			$entidades = DatEntidadSegUsuarioSegRol::cargarentidadesusuario($idusuario);
			$entidades = $this->arregloBidimensionalToUnidimensional($entidades);
			$global = ZendExt_GlobalConcept::getInstance();
      		$iddominio = $global->Perfil->iddominio;
      		$integrator = ZendExt_IoC::getInstance();
      		$arrayultimas = $integrator->metadatos->buscarIdEstructurasDominio($iddominio, $entidades, 1);
      		$arrayultimas = $this->arregloBidimensionalToUnidimensional($arrayultimas);
      		return $arrayultimas;
	}

	private function arregloBidimensionalToUnidimensional($arrayEstructuras) {
		$array = array();
		foreach ($arrayEstructuras as $est)
			$array[] = $est['idestructura'];
		return $array;
	}

	private function arrayUltimasEntidadesInternas($array,$entidades)
     	{
	    	$cantidad = count($array);
           	$internas = explode('_',$array[$cantidad - 1]);		
		$band = $this->verificarValorArrayInternas($internas[0], $entidades);		
            	if($internas[1] == 'i' && $band)
                	return $internas[0];
            	else
                	return 0;    
        }

	private function verificarValorArrayInternas($valor, $array) {
	   	foreach($array as $interna)
		{
			if($interna['identidad'] == $valor)
				return true;
		}
		return false;	
	}
    
    /**
     * existSomeRol
     * Funcion para saber si hay algun rol aparte del de instalacion creado.
     * 
     * @return boolean - buleano. 
     */    
    public function existSomeRol() {
        $cantRoles = SegRol::cantrol();
        if($cantRoles > 1)
            return true;
        return false;
    }
    
    /**
     * existUserRol
     * Funcion para saber si existe algun usuario.
     * 
     * @return boolean - buleano. 
     */    
    public function existUserRol() {
       if(SegUsuario::existealgunusuario() > '0')
                return 1;
             else
                return 0;
    }

    private function buscarIdCargar($array, $idEntidad=0) {
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
    
    private function cargarEntidadesDadoDominio($arrayEntidades, $idEntidad) {
        return  $this->buscarIdCargar($arrayEntidades, $idEntidad); 
    }
    
    /**
     * getSystems
     * Brinda el servicio de listar todos los sistemas a los que tiene acceso un usuario en una entidad.
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $identidad - Identificador de la estructura.
     * @return json | null - Los sistemas de la entidad.
     */
	public function getSystems ($certificate, $identidad) {
		$objcertificado = new SegCertificado();
		$datos = $objcertificado->verificarcertificado($certificate);
        if(count($datos))
        {
        	$idusuario = $datos[0]->idusuario;
        	$rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario, $identidad);
            
            $sistemaObj = DatSistema::obtenersistemas($rolusuarioentidad[0]->idrol);
		$sistemaArr = array();
		foreach ($sistemaObj as $key => $sistema) {
			$sistemaArr[$key]['id'] = $sistema->idsistema;
			$sistemaArr[$key]['icono'] = ($sistema->icono) ? $sistema->icono : 'falta';
			$sistemaArr[$key]['titulo'] = $sistema->denominacion;
            $sistemaArr[$key]['externa'] = $sistema->externa;
		}
            return $sistemaArr;
        }
		else
			throw new ZendExt_Exception('SGIS003');
	}
    
    private function existeValorArray($array, $valor) {
        foreach($array as $valor1)
            if($valor1 == $valor)
                return true;    
        return false;    
    }
    
    private function existeValorArrayNoUltimo($array, $valor) {
        for($i=0; $i<count($array)-1; $i++)
            if(substr($array[$i],0,strlen($array[$i])-2) == $valor)
                return $array[$i+1]; 
        return -1;    
    } 
    
    private function ponerComoHoja($array) {
    $integrator = ZendExt_IoC::getInstance();
    $n = count($array);
    for($i=0; $i<$n; $i++)
    {
      if($array[$i]['rgt'] - $array[$i]['lft'] == 1)
      {
      $arrayEstructuras1 = $integrator->metadatos->DameEstructurasInternasSeguridad($array[$i]['idestructura'],true);
      if(!count($arrayEstructuras1))
      $array[$i]['leaf'] = true;
      }      
    }
        return $array;        
    }
	
    /**
     * loadActions
     * Servicio que le permite obtener las acciones de un usuario.
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $identidad - Identificador de la entidad.
     * @param integer $idsistema - Identificador del sistema.
     * @param integer $idfuncionalidad -  Identificador de la funcionalidad.
     * @return json | null - Las acciones del usuario autenticado.
     */    
    function loadActions($identidad,$idsistema,$idfuncionalidad,$certificado) {
        $objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificado);
        if($datos){
            $idcertificado = $datos[0]->idcertificado;    
            $idusuario = $datos[0]->idusuario;    
            $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$identidad);      
            $accionesquetiene = DatAccion::cargaracciones($idsistema,$idfuncionalidad,$rolusuarioentidad[0]->idrol);
                foreach ($accionesquetiene as $valores=>$valor)
                    {
                    $arracciones[$valores]['idaccion']= $valor->idaccion;
                    $arracciones[$valores]['denominacion']= $valor->denominacion;
                    }
            return json_encode($arracciones);
        }
        else
        throw new ZendExt_Exception('SGIS003');                    
    }
    
  /**
     * getSystemsFunctions
     * Carga sistemas, subsistemas y funcionalidades.
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $idsistema - Identificador del sistema.
     * @param integer $identidad - Identificador de la estructura.
     * @return json | null - Los subsistemas de un sistema o las funcionalidades de un subsistema.
     */
	public function getSystemsFunctions($certificate, $idsistema, $identidad) {
		$tmpidsistema = explode('_', $idsistema);
        
		if (count($tmpidsistema) >= 1)
			$idsistema = $tmpidsistema[0];
            $objcertificado = new SegCertificado();
            $datos = $objcertificado->verificarcertificado($certificate);
            $idusuario = $datos[0]->idusuario;
            $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$identidad);
            $idrol = $rolusuarioentidad[0]->idrol;
            $acceso = DatSistema::verificarrolsistema($idrol,$idsistema);            
            if(count($acceso))
            {     
                    $sistemas = DatSistema::obtenersistemasxml($idrol,$idsistema);
                    $externa = DatSistema::buscarservidorweb($idsistema); 
                    $contador = 0;
                    $sistemafunArr = array();
                    if($sistemas->count())
                        {
                            foreach ($sistemas as $valores=>$valor)
                            {
                            $sistemafunArr[$contador]['id'] = $valor->id.'_'.$idsistema;
                            $sistemafunArr[$contador]['idsistema'] = $valor->id;
                            $sistemafunArr[$contador]['externa'] = $valor->externa;
                            $sistemafunArr[$contador]['icono'] = $valor->icono;  
                            $sistemafunArr[$contador]['text'] = $valor->text;
                            $sistemafunArr[$contador]['servidores'] = $this->servidores($valor->id);
                            $contador++;                            
                            }
                        }
					$funcionalidad = DatFuncionalidad::obtenerFuncionalidades($idsistema,$idrol);
                    if($funcionalidad->getData() != NULL)
                        {
                            foreach ($funcionalidad as $valores=>$valor)
                            {
                            $sistemafunArr[$contador]['id'] = $valor->id.'_'.$idsistema;
                            $sistemafunArr[$contador]['idfuncionalidad'] = $valor->id;
                             if($externa[0]->externa)
                                $sistemafunArr[$contador]['referencia'] = 'http://'.$externa[0]->externa.'/'.$valor->referencia;
                             else
                                $sistemafunArr[$contador]['referencia'] = '../../../' . $valor->referencia;
                            $sistemafunArr[$contador]['icono'] = $valor->icono;                            
                            $sistemafunArr[$contador]['text'] = $valor->text;
                            $sistemafunArr[$contador]['leaf'] = true;
                            $contador++;
                            }
                        }
                    if(count($sistemafunArr))
                    return $sistemafunArr;
                    else
                    return array();
            }
            else        
            	throw new ZendExt_Exception('SGIS003');        
	}
	
	/**
	 * getSystemsDesktopModules
	 * Obtener los modulos para el portal tipo escritorio.
	 * 
	 * @param string $certificate - Certificado o token de seguridad asignado al usuario.
	 * @param integer $identidad - Identificador de la entidad a la que accedió el usuario.
	 * @return array - Arreglo de modulos. 
	 */
	public function getSystemsDesktopModules($certificate, $identidad) {
        $objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificate);    
		if(count($datos))
        {
        	$idusuario = $datos[0]->idusuario;
            $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario, $identidad);
            $sistemas = DatSistema::obtenersistemas($rolusuarioentidad[0]->idrol)->toArray(true);
            if(count($sistemas))
            {
            	foreach ($sistemas as $mdf)
                	$resultado[] = $this->subsistemas($mdf,$rolusuarioentidad[0]->idrol);        
				return $resultado;
			}                
            else return array();
		}
		else        
            throw new ZendExt_Exception('SGIS003');
	}
	
	/**
	 * getSystemsFunctionsDesktopModules
	 * Obtener los modulos para el portal tipo escritorio.
	 * 
	 * @param string $certificate - Certificado o token de seguridad asignado al usuario.
	 * @param integer $identidad - Identificador de la entidad a la que accedió el usuario.
	 * @return array - Arreglo de modulos. 
	 */
	public function getSystemsFunctionsDesktopModules($certificate, $identidad) {
        $objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificate);    
		if(count($datos))
        {
        	$idusuario = $datos[0]->idusuario;
            $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario, $identidad);
            $sistemas = DatSistema::obtenersistemas($rolusuarioentidad[0]->idrol)->toArray();
            if(count($sistemas))
            {
            	foreach ($sistemas as $mdf)
                	$resultado[] = $this->subsistemasfunc($mdf,$rolusuarioentidad[0]->idrol);        
                return $resultado;
			}                
            else return array();
		}
		else        
            throw new ZendExt_Exception('SGIS003');
	}

    private function subsistemas($raiz,$idrol) {            
        $result = array('icono'=>$raiz['icono'], 'id'=>$raiz['idsistema'], 'text'=>$raiz['denominacion'],'externa'=>$raiz['externa'],'servidores'=> $this->servidores($raiz['idsistema']));
        $sistemashijos = DatSistema::cargarsistemahijos($raiz['idsistema'],$idrol);
        if(count($sistemashijos))
        { 
            foreach ($sistemashijos as $hijo) 
            {
                if($raiz['idsistema'] != $hijo['idsistema'])
                {
                    $result['menu'][] = $this->subsistemas($hijo,$idrol);
                }        
            }
            return $result; 
        }
        else 
            return $result; 
        }
    
	private function subsistemasfunc($raiz,$idrol) {            
		$result = array('icono'=>$raiz['icono'], 'id'=>$raiz['idsistema'], 'text'=>$raiz['denominacion'],'externa'=>$raiz['externa'], 'menu'=> $this->funcionalidades($raiz['idsistema'],$idrol,$raiz['externa']),'servidores'=> $this->servidores($raiz['idsistema']));
		$sistemashijos = DatSistema::cargarsistemahijos($raiz['idsistema'],$idrol);
		if(count($sistemashijos))
		{ 
		foreach ($sistemashijos as $hijo) 
		{
			if($raiz['idsistema'] != $hijo['idsistema'])
			{
			$result['menu'][] = $this->subsistemasfunc($hijo,$idrol);
			}        
		}
		return $result; 
		}
		else 
		return $result; 
		}

    private function servidores($idsistema) {
    	return array(); /***ARREGLAR***/
            $obj = new DatSistemaDatServidores();
            $esquemassistema = $obj->obtenersistemacompleto($idsistema);                            
            foreach ($esquemassistema as $valor1)
            {
                $arrayresult['idsistema'] = $valor1['idsistema'];
                $arrayresult['denominacion'] = $valor1['denominacion']; 
                if (isset($valor1['DatServidor'][0]))                       
                	$arrayresult['servidor'] = $valor1['DatServidor'][0]['denominacion'];
                else $arrayresult['servidor'] = '';                                    
                if (isset($valor1['DatGestor'][0]))
                	$arrayresult['gestor'] = $valor1['DatGestor'][0]['gestor'];
                else $arrayresult['gestor'] = '';
                $arraybd = array();
                foreach($valor1['DatBd'] as $bd)
                {    
                    $arraybd[]=$bd['denominacion'];
                    foreach($valor1['DatEsquema'] as $esquemas)
                    {
                        $arrayesquemas[]= $esquemas['denominacion'];            
                    }
                    $arrayresult['esquemas']=$arrayesquemas;
                }
                $arrayresult['bd']=$arraybd;                                    
            }
            return $arrayresult; 
        }
        
    private function funcionalidades($idsistema,$idrol,$externa) {
         $funcsistema = DatFuncionalidad::obtenerFuncionalidades($idsistema,$idrol);
         $arrayFuncionalidades = array();
         foreach($funcsistema as $key=>$funcionalidades)
             {
              $arrayFuncionalidades[$key]['text'] = $funcionalidades->text; 
              $arrayFuncionalidades[$key]['id'] = $funcionalidades->idfuncionalidad;
              $arrayFuncionalidades[$key]['index'] = $funcionalidades->index;
              if($externa)
              $arrayFuncionalidades[$key]['referencia'] = 'http://'.$externa.'/'.$funcionalidades->referencia;
              else
              $arrayFuncionalidades[$key]['referencia'] = '../../../' . $funcionalidades->referencia;
             }
         return $arrayFuncionalidades;
        } 

    /**
     * verifyAccessEntity
     * Verifica si un usuario tiene acceso a una entidad.
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $identity - Identificador de la entidad a la que accedió el usuario.
     * @return integer - entero. 
     */
	public function verifyAccessEntity ($certificate, $identity) {
	    $objcertificado = new SegCertificado();                
        $datos = $objcertificado->verificarcertificado($certificate);                    
        $idusuario = $datos[0]->idusuario;
            if($this->usuarioEntidad($idusuario,$identity))
             {
			 $roles = SegRol::obtenerrolesusuarioentidad($idusuario,$identity);			      		 
			 if (count($roles))
 				return $roles[0]['text'];
 			else return '0';
			}
	}
    
    /**
     * LoadXML
     * Obtiene un XML con la configuracion para un usuario.
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param integer $identity - Identificador de la entidad a la que accedió el usuario.
     * @return XML - xml. 
     */
    function LoadXML($certificate,$identity) {
        $objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificate);    
        $idusuario = $datos[0]->idusuario;
        if(count($idusuario))
        {                
             $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$identity);
             $sistemas = DatSistema::obtenersistemas($rolusuarioentidad[0]->idrol)->toArray(true);
            if(count($sistemas))
            {   
                $menu = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><menu>";
                foreach ($sistemas as $mdf)
                {
                    $this->subsistemasxml($mdf,$rolusuarioentidad[0]->idrol,$menu);        
                }    
                $menu .= "</menu>";
                header('Content-Type: text/xml');
                echo $menu;
                return $menu;
            }                
        }
        return '';   
    }
    
    /**
     * LoadXMLModule
     * Obtiene un XML con la configuracion de un modulo dado para un usuario.
     * 
     * @param string $certificate - Certificado o token de seguridad asignado al usuario.
     * @param string $module - Modulo especificado.
     * @param integer $identity - Identificador de la entidad a la que accedió el usuario.
     * @return XML - xml. 
     */
    function LoadXMLModule($certificate,$identity,$module) {
                $objcertificado = new SegCertificado();
                $datos = $objcertificado->verificarcertificado($certificate);    
                $idusuario = $datos[0]->idusuario;
                if(count($idusuario))
                {                
                    $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$identity);
                    $sistemas = DatSistema::obtenersistemasxml($rolusuarioentidad[0]->idrol,$module)->toArray(true);
                    if(count($sistemas))
                    {   
                        $menu = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><menu>";
                        foreach ($sistemas as $mdf)
                        {
                            $this->subsistemasxml($mdf,$rolusuarioentidad[0]->idrol,$menu);        
                        }    
                        $menu .= "</menu>";
                        header('Content-Type: text/xml');
                        echo $menu;
                        return $menu;
                    }                
                }
                return '';   
        } 
    
    private function subsistemasxml($raiz,$idrol,& $menu) {
        $menu .= "<MenuItem name=\"{$raiz['denominacion']}\" id=\"{$raiz['idsistema']}\" externa=\"{$raiz['externa']}\" icon=\"{$raiz['icono']}\"  status=\"{$raiz['descripcion']}\">" ;
        $this->funcionalidadesxml($raiz['idsistema'],$idrol, $menu);
        $sistemashijos = DatSistema::cargarsistemahijos($raiz['idsistema'],$idrol);
        if(count($sistemashijos))
        { 
            foreach ($sistemashijos as $hijo) 
                if($raiz['idsistema'] != $hijo['idsistema'])
                     $this->subsistemasxml($hijo,$idrol,$menu);
        }
        $menu .= "</MenuItem>";
    }
        
    private function funcionalidadesxml($idsistema,$idrol, & $menu) {
     $funcsistema = DatFuncionalidad::obtenerFuncionalidades($idsistema,$idrol);
     if(count($funcsistema))
       foreach($funcsistema as $funcionalidades)
         $menu .= "<MenuItem name=\"{$funcionalidades->text}\" id=\"{$funcionalidades->idfuncionalidad}\"  src=\"{$funcionalidades->referencia}\" icon=\"{$funcionalidades->icono}\"  status=\"{$funcionalidades->descripcion}\"/>" ;
    } 
   
   /**
     * getUsersProfile
     * Devuelve los datos del perfil de usuario.
     * 
     * @param array  $arrayusers- Identificadores de los usuario.
     * @return array - array. 
     */
    public function getUsersProfile($arrayusers, $certificate) {
        if($this->verificateCertificate($certificate))
        {
         foreach($arrayusers as $key=>$usuarios)
         {
             $datos = SegUsuario::nombusuario($usuarios)->toArray();
             $perfil = SegUsuario::cargarperfilusuario($usuarios);
             if (is_array($perfil[0]['NomFila']['NomValor']))
             {  
             foreach ($perfil[0]['NomFila']['NomValor'] as $valor) 
                if($valor['idfila'])
                {                        
                    $arraycampos = NomValor::cargarcamposdadovalores($valor['idvalor']);
                    $arrayPerfil[$key][$arraycampos[0]['NomCampo']['nombre']] =$valor['valor'];                       
                }          
             }    
             $arrayPerfil[$key]['idusuario'] = $usuarios;
             $arrayPerfil[$key]['usuario'] = $datos[0]['nombreusuario']; 
         }
         return $arrayPerfil;
        }
        else return array(); 
        } 
        
   /**
     * getUsersEntity
     * Devuelve la entidad, el area y el cargo de cada usuario.
     * 
     * @param array - Identificadores de usuarios.
     * @return array - array. 
     */ 
    public function getUsersEntity($array) {
         foreach($array as $key=>$usuarios)
         {
             $datos = SegUsuario::cargarentidadesareascargos($usuarios);
             $integrator = ZendExt_IoC::getInstance();
             $arrayEstructuras = $integrator->metadatos->MostrarCamposEstructuraSeguridad($datos[0]['identidad']);
                    $arrayreturn[$key]['entidad'] = $arrayEstructuras[0]['text'];
                    $arrayreturn[$key]['identidad'] = $datos[0]['identidad']; 
                    if($datos[0]['idarea'] != 0)
                        {
                        $area = $integrator->metadatos->EstructurasInternasDadoIDSeguridad($datos[0]['idarea']);
                        $arrayreturn[$key]['area'] = $area[0]['denominacion'];        
                        $arrayreturn[$key]['idarea'] = $datos[0]['idarea'];
                        }
                    if($datos[0]['idcargo'] != 0) 
                        {     
                        $cargo = $integrator->metadatos->CargoDadoIDSeguridad($datos[0]['idcargo']);
                        $arrayreturn[$key]['cargo'] = $cargo[0]['denominacion'];
                        $arrayreturn[$key]['idcargo'] = $datos[0]['idcargo'];
                        }
             $arrayreturn[$key]['idusuario'] = $usuarios;
         }
         return $arrayreturn; 
        }
        
     /**
     * loadUserActions
     * Devuelve las acciones de un usuario.
     * 
     * @param string $certificate - Certificado del usuario.
     * @param integer $idfuncionalidad -  Identificador de la funcionalidad.
     * @param string $entity -  Identificador de la entidad.
     * @return array - array. 
     */    
		public function loadUserActions($certificate,$idfuncionalidad,$entity) { 
			$objcertificado = new SegCertificado();
		    $datos = $objcertificado->verificarcertificado($certificate);  
		    $idcertificado = $datos[0]->idcertificado;    
		    $idusuario = $datos[0]->idusuario;
		        $rolusuarioentidad = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$entity);
		        $accionesquetiene = DatAccion::cargaraccionesservice($idfuncionalidad,$rolusuarioentidad[0]->idrol);
		        if(count($accionesquetiene))
		        {
		        	$direccion ='http://'.$_SERVER['HTTP_HOST'].'/'.'report_generator.php/api/exportReport?id=';
			        foreach ($accionesquetiene as $valores=>$valor)
			        {	
				        $arracciones[$valores]['idaccion']= $valor->idaccion;
				        $arracciones[$valores]['abreviatura']= $valor->abreviatura;		           	            	

				        $reportes = DatAccionDatReporte::cargaraccionesasociadasrep($valor->idaccion);       

					
				        if(count($reportes))
				        {
				        	foreach ($reportes as $rep=>$valorrep)	            	
					        {
					        	$arrayrep[$rep]['denominacion'] = $valorrep['denominacion'];
					        	$arrayrep[$rep]['url'] = $direccion.$valorrep['idreporte'];
								$arrayrep[$rep]['idreporte'] = 	$valorrep['idreporte'];
					        }
					$arracciones[$valores]['reportes']=$arrayrep;
				        }		            	
			        }  	  
		        	return $arracciones;
		        }
		        else
		            return $arracciones = array(); 
		    }
     
     /**
     * LoadUserRoles
     * Devuelve un arreglo con todos los roles de un usuario.
     * 
     * @param string $certificate - Certificado del usuario.
     * @return array - array. 
     */   
    public function LoadUserRoles($certificate) {
         $objcertificado = new SegCertificado();
         $datos = $objcertificado->verificarcertificado($certificate);
         $idcertificado = $datos[0]->idcertificado;    
         $idusuario = $datos[0]->idusuario;
         $rolesuser = SegRol::obtenerrolesusuario($idusuario)->toArray();
         foreach($rolesuser as $key=>$rol)
         {
            $arrayresult[$key]['text'] = $rol['text'];
            $arrayresult[$key]['denominacion'] = $rol['text'];
            $arrayresult[$key]['id'] = $rol['id'];
            $arrayresult[$key]['idrol'] = $rol['id'];
            $arrayresult[$key]['abreviatura'] = $rol['abreviatura']; 
         }
         return $arrayresult; 
      }

	/**
     * LoadUserRolByEntidad
     * Devuelve el rol de un usuario en una entidad.
     * 
     * @param string $certificate - Certificado del usuario.
     * @return array - array. 
     */   
     public function LoadUserRolByEntidad($certificate, $identidad)
     {
         $objcertificado = new SegCertificado();
         $datos = $objcertificado->verificarcertificado($certificate);
         $idcertificado = $datos[0]->idcertificado;    
         $idusuario = $datos[0]->idusuario;
         $rolesuser = SegRol::obtenerrolesusuarioentidad($idusuario, $identidad);
         $arrayresult = new stdClass();
         $arrayresult->idrol = $rolesuser[0]->id;
         $arrayresult->denominacion = $rolesuser[0]->text;
         $arrayresult->abreviatura = $rolesuser[0]->abreviatura; 
         return $arrayresult; 
      }    

     /**
     * ExistUserRoles
     * Devuelve true o false si la entidad tiene roles o usuarios.
     * 
     * @param string $entity - Entidad.
     * @return integer - entero. 
     */  
    public function ExistUserRoles($entity) {
     $existe = DatEntidadSegUsuarioSegRol::existeusuariorol($entity);
     if($existe)
        return 1;
     else
        return 0;
    }
    
     /**
     * ChangeState
     * Devuelve true si se ha cambiado el estado de un usuario.
     * 
     * @param string $idusuario - Id de usuario.
     * @param char $opcion - Opcion.
     * @return integer - entero. 
     */
    public function ChangeState($idusuario,$opcion) {
        if($opcion == '0')
             {
                 $obj = Doctrine::getTable('SegUsuario')->find($idusuario);
                 $obj->estado = '0';
                 $obj->save();
                 return 1;
             }
         elseif($opcion == '1')
             {
                 $obj = Doctrine::getTable('SegUsuario')->find($idusuario);
                 $obj->estado = '1';
                 $obj->save();
                 return 1;
             }
         return 0;
    }
    
    /**
     * ChangePassword
     * Funcion para cambiar el password a un usuario.
     * 
     * @param string $usuario - nombre de usuario.
     * @param string $oldpass - Contrase�a vieja.
     * @param string $newpass - Nueva contrase�a.
     * @return integer - entero. 
     */    
    public function ChangePassword($usuario,$oldpass,$newpass) {
     $verificar = SegUsuario::verificarpass($usuario);
     $oldpass = md5($oldpass);
     if($verificar[0]->contrasenna == $oldpass)
        {
         if($this->verificarpass($newpass))
             {  
              $objusuario = Doctrine::getTable('SegUsuario')->find($verificar[0]->idusuario);
              $objusuario->contrasenna = md5($newpass);
              $objusuario->contrasenabd =  md5($newpass);
              $objusuario->save();
              return 1;
             }
         else
            {throw new ZendExt_Exception('SEG020');}
         }
     else
        {throw new ZendExt_Exception('SEG013');}
    } 
    
    private function verificarpass($pass) {
            $s = '/^([a-zA-Z��������])+$/';
            $sn = '/^[\da-zA-Z��������]+$/';
            $nn = '/[\d]/';
            $nl = '/[\!\\�\\!\@\#\$\%\^\&\*\(\)\_\+\=\{\}\[\]\:\"\;\'\?\/\>\.\<\,\\\*\-]+$/';
            $clave = new SegRestricclaveacceso();
            $datos = $clave->cargarclave(0,0);
            $resultados = array();
            $results = array();
            if($datos->getData() == null)
                throw new ZendExt_Exception('SEG001');
            $datosacc=$datos->toArray(true);
            if(strlen($pass)< $datosacc[0]['minimocaracteres'])
            {
                throw new ZendExt_Exception('SEG021');
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
        
    private function hayNumeros($cadena) {
        $cant = strlen($cadena);
        for($i=0; $i<$cant; $i++)
            if($cadena[$i] >= '0' && $cadena[$i] <= '9')
                return true;
        return false; 
    }
        
    private function hayLetras($cadena) {
        $cant = strlen($cadena);
        for($i=0; $i<$cant; $i++)
            if(($cadena[$i] >= 'A' && $cadena[$i] <= 'Z') || ($cadena[$i] >= 'a' && $cadena[$i] <= 'z') || $cadena[$i] == '�' || $cadena[$i] == '�')
                return true;
        return false; 
    }
    
    /**
     * rolAsUsersGain
     * Servicio para obtener todos los usuarios de un rol en una entidad.
     * 
     * @param integer $identidad - Identificador de la entidad.
     * @param integer $idrol - Identificador del rol.
     * @return array - array. 
     */ 
    public function rolAsUsersGain($identidad,$idrol) {
    $result = SegUsuario::cargarusuarios($identidad,$idrol);
    foreach($result as $key=>$usuarios)
         {
             $perfil = SegUsuario::cargarperfilusuario($usuarios['idusuario']);
             if (is_array($perfil[0]['NomFila']['NomValor']))
             foreach ($perfil[0]['NomFila']['NomValor'] as $valor) 
                if($valor['idfila'])
                {                        
                    $arraycampos= NomValor::cargarcamposdadovalores($valor['idvalor']);
                    $arrayreturn[$key][$arraycampos[0]['NomCampo']['nombre']] =$valor['valor'];                       
                }          
             $arrayreturn[$key]['idusuario']= $usuarios['idusuario'];
         }
       return $arrayreturn;
    }
    
    /**
     * modificateDomain
     * Servicio para saber si el dominio fue modificado.
     * 
     * @param integer $iddominio - Identificador del dominio.
     * @return boolean - buleano. 
     */
    public function modificateDomain($dominio) {
      $result = SegDominio::verificarmodificaciones($dominio ='1');
      $cadena = explode(',',$result[0]['cadena']);
      if(count($cadena) > '1')
        return 1;
        else
        return 0;
    }
    
     /**
     * verificateCertificate
     * Servicio para saber si un certificado es valido.
     * 
     * @param integer $certificate - Certificado.
     * @return integer - Uno si existe cero de lo contrario. 
     */
    function verificateCertificate($certificate) {
     $existe = SegCertificado::existcertificado($certificate);
      if($existe)
        return 1;
      else
        return 0;
    } 
        
    private function verificarendominio($idusuario,$idEstructura) {
       $iddominio = SegUsuario::cargardominiodeusuario($idusuario);
       $arrayEntidades = SegDominio::obtenerCadenaEntidades($iddominio[0]->iddominio);
       
       if(count($this->buscarIdCargarnueva($arrayEntidades,$idEstructura)))
       		return $this->buscarIdCargarnueva($arrayEntidades,$idEstructura);
        else
         	return array();
     }
     
    private function usuarioEntidad($idusuario,$idEstructura) {
      $exist = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$idEstructura);
      if(count($exist))
      return 1;
      else
      return 0;
     }
     
    private function buscarIdCargarnueva($array, $idEntidad) {   
        foreach($array as $valor)
        {    
            $temp = explode("-",$valor);                        
            $res = $this->existeValor($temp,$idEntidad);
            if($res)
            $result[] = $res; 
        } 
        return $result;   
    }
    
    private function existeValor($array, $valor) {   
     for($i=0; $i<count($array)-1; $i++)
        {
            if(substr($array[$i],0,strlen($array[$i])-2) == $valor)
                {
                	$result = substr($array[count($array)-1],0,strlen($array[count($array)-1])-2);
                }
        }
         
          return $result;   
    }
    
    private function arrayUsuariosPerfil($arrayusers) {
         foreach($arrayusers as $key=>$usuarios)
         {
             $perfil = SegUsuario::cargarperfilusuario($usuarios['idusuario']);
             if (is_array($perfil[0]['NomFila']['NomValor']))
             foreach ($perfil[0]['NomFila']['NomValor'] as $valor) 
                if($valor['idfila'])
                {                        
                    $arraycampos= NomValor::cargarcamposdadovalores($valor['idvalor']);
                    $arrayreturn[$key][$arraycampos[0]['NomCampo']['nombre']] =$valor['valor'];                       
                }          
             $arrayreturn[$key]['id'] = $usuarios['idusuario'];
             $arrayreturn[$key]['text'] = $usuarios['nombreusuario']; 
             $arrayreturn[$key]['idusuario'] = $usuarios['idusuario'];
             $arrayreturn[$key]['usuario'] = $usuarios['nombreusuario'];
			 $arrayreturn[$key]['idpadre']= $perfil[0]['idcargo'];			 
             $arrayreturn[$key]['leaf'] = true;
             $arrayreturn[$key]['checked'] = false;
         }
         return $arrayreturn; 
        }
        
    private function ponerComoHojaPDO($array, $arrayEntDominio) {
	    $integrator = ZendExt_IoC::getInstance();
	    $n = count($array);		
		    for($i=0; $i<$n; $i++) {
				if($this->existeEnArray($arrayEntDominio, $array[$i]['idestructura'])) {
					$array[$i]['checked'] = false;  
					$array[$i]['dominio'] = true;
				}
				else 
					$array[$i]['dominio'] = false;  
				if($array[$i]['rgt'] - $array[$i]['lft'] == 1) {
				$arrayEstructuras1 = $integrator->metadatos->DameEstructurasInternasSeguridad($array[$i]['idestructura'],true);
				$cargos = $integrator->metadatos->BuscarCargosPorTiposSeguridad($array[$i]['idestructura']);
				if(!count($arrayEstructuras1) && !count($cargos) )
				$array[$i]['leaf'] = true;
		      }      
		    }
		
	    return $array;        
    }
    
     /**
     * loadDomainPDO
     * Servicio para saber si un certificado es valido.
     * 
     * @param integer $certificado - Certificado.
     * @param integer $idEstructura - Id de la estructura.
     * @param string $tipo - Tipo. 
     * @return array - array. 
     */
    function loadDomainPDO($certificado,$idEstructura,$tipo) {
                    $objcertificado = new SegCertificado();                
                    $datos = $objcertificado->verificarcertificado($certificado);                    
                    $idusuario = $datos[0]->idusuario;
                    if($idusuario)
                    {
                        $iddominio = SegUsuario::cargardominiodeusuario($idusuario);
                        $arrayEntidades = SegDominio::obtenerCadenaEntidades($iddominio[0]->iddominio);
						$arrayEntDominio = $this->entidadesDominio($arrayEntidades); // Todas las entidades que pertenecen al dominio
                        $arrayId = $this->cargarEntidadesDadoDominio($arrayEntidades,$idEstructura);// devuelve los hijos en el dominio
                         if((!count($arrayId)) && ($tipo =='interna'))
                         {	
                          $integrator = ZendExt_IoC::getInstance();
                          $result = $integrator->metadatos->BuscarCargosPorTiposSeguridad($idEstructura);                        
                          foreach($result as $key=>$cargo)
                            {
                            $arrayEstructuras[$key]['text'] = $cargo['Asignacion']['text'];
                            $arrayEstructuras[$key]['id'] = $cargo['id'];
                            $arrayEstructuras[$key]['tipo'] = 'cargo';
                            $arrayEstructuras[$key]['idcargo'] = $cargo['id'];
                            $arrayEstructuras[$key]['checked'] = false;
                            if(!count(SegUsuario::usuariosdelcargo($cargo['id'])))
                            {$arrayEstructuras[$key]['leaf'] = true;}               
                            }
                         }
						 elseif($tipo =='interna')
						 { 
								$key = 0;
								$integrator = ZendExt_IoC::getInstance();
								$datos = $integrator->metadatos->ListarEstructurasDadoArrayId($arrayId);		 
	                            $datos = $this->ponerComoHojaPDO($datos, $arrayEntDominio);
								foreach($datos as $valores)
								{
									 $arrayEstructuras[$key]['rgt'] = $valores['rgt'];
						             $arrayEstructuras[$key]['lft'] = $valores['lft'];
						             $arrayEstructuras[$key]['id'] = $valores['id'];
						             $arrayEstructuras[$key]['idestructura'] = $valores['idestructura'];
						             $arrayEstructuras[$key]['text'] = $valores['text'];
						             $arrayEstructuras[$key]['tipo'] = $valores['tipo'];
									 if($this->existeEnArray($arrayEntDominio, $valores['idestructura'])){
						             	$arrayEstructuras[$key]['checked'] = false;
						             	$arrayEstructuras[$key]['dominio'] = true;
									 }
									 else 
									 	$arrayEstructuras[$key]['dominio'] = false;
									 $key++; 
								}								
								//me da los cargos de un area
								if($this->existeEnArray($arrayEntDominio, $idEstructura)) {								
	                            $result = $integrator->metadatos->BuscarCargosPorTiposSeguridad($idEstructura);                         
	                            foreach($result as $cargo) {
			                            $arrayEstructuras[$key]['text'] = $cargo['Asignacion']['text'];
			                            $arrayEstructuras[$key]['id'] = $cargo['id'];
			                            $arrayEstructuras[$key]['tipo'] = 'cargo';
			                            $arrayEstructuras[$key]['idcargo'] = $cargo['id'];
			                            $arrayEstructuras[$key]['checked'] = false;
			                            if(!count(SegUsuario::usuariosdelcargo($cargo['id'])))
			                            	{$arrayEstructuras[$key]['leaf'] = true;}  
										$key++;            
	                            	}
								}
						 }
                         elseif($tipo =='cargo')
                         { 
                          $usuarios = SegUsuario::usuariosdelcargo($idEstructura);
                          $arrayEstructuras = $this->arrayUsuariosPerfil($usuarios);
                         }
                        else
                         {
                             $integrator = ZendExt_IoC::getInstance();
							 if(count($arrayId))
							 {
	                             $arrayEstructuras = $integrator->metadatos->ListarEstructurasDadoArrayId($arrayId);
	                             $arrayEstructuras = $this->ponerComoHojaPDO($arrayEstructuras, $arrayEntDominio);
                             }							 
                         }
                        if(count($arrayEstructuras))
                        { return $arrayEstructuras;}
                        else
                        { return $arrayEstructuras = array();}                            
                    }

        } 
     
	private function entidadesDominio($array) {
    	$result = array();
    	foreach($array as $valores) {
     	 	$cadena = explode('-',$valores);  
      		$result[] = substr($cadena[count($cadena)-1],0,strlen($cadena[count($cadena)-1])-2);
    	}
  	return $result;
  	}

  	private function existeEnArray($array, $valor) {
    	foreach($array as $entidades){
      		if($valor == $entidades)
				return true;
    		}
    	return false;
  	}
  	
     /**
     * getUsersDates
     * Servicio que debuelve la entidad, el area y el cargo de un grupo de usuarios.
     * 
     * @param integer $idusuario - Id del usuario.
     * @return array - array. 
     */   
    function getUsersDates($idusuario)
    {
     $a = new  SegUsuario();
     $datos = $a->cargarperfilusuario($idusuario);
     foreach($datos as $key=>$valores)
     {
     $result[$key]['identidad'] = $valores['identidad'];
     $result[$key]['idarea'] = $valores['idarea'];
     $result[$key]['idcargo'] = $valores['idcargo'];
     }
     return $result;
    }  

    /**
     * nameUsersProfile
     * Devuelve el nombre del usuario.
     * 
     * @param $user - Identificador del usuarios.
     * @return string - cadena. 
     */ 
    public function nameUsersProfile($user) {
     $datos = SegUsuario::nombusuario($user)->toArray();
     $nombre = $datos[0]['nombreusuario']; 
     return $nombre; 
   	}
   
   /**
     * InsertUserSIGAC
     * Funcion para insertar usuario de SIGAC.
     * 
     * @param $user - Nombre de usuario.
     * @param $pass - Password del usuario.
     * @param $url - Url especificada.
     * @return bool - bool. 
     */
    function InsertUserSIGAC($user,$pass,$url) {
     $idusuario = $this->insertar($user,$pass);
     $idcampo = $this->insertarCampo();
     $idfila = $this->insertarFila($idusuario);
     if($this->insertarValor($idfila,$idcampo,$url) > '0')
        return true;
     else
        return false; 
    }
    
    private function insertar($user,$pass) {
    $tema = NomTema::cargarTema();
    $dominio = SegDominio::cargarDominio();
    $idioma = NomIdioma::cargarcomboidioma();
    $desktop = NomDesktop::cargarcombodesktop();
    $usuario = new SegUsuario();    
    $usuario->nombreusuario = $user;
    $usuario->contrasenna = $pass;
    $usuario->contrasenabd = $pass;
    $usuario->ip = '0.0.0.0/0';
    $usuario->idtema = $tema[0]->idtema;
    $usuario->iddominio = $dominio[0]->iddominio;
    $usuario->ididioma = $idioma[0]->ididioma;
    $usuario->iddesktop = $desktop[0]->iddesktop;
    $usuario->idarea = '0';
    $usuario->identidad = '0';
    $usuario->idcargo = '0';
    $usuario->estado = '1';
    $usuario->save();
    return $usuario->idusuario; 
    }
    
	public function insertarUsuarioUCID($user, $numid, $nombre, $papell, $sapell)
    {
	    $tema = NomTema::cargarTema();
	    $idioma = NomIdioma::cargarcomboidioma();
	    $desktop = NomDesktop::cargarcombodesktop();
	    $usuario = new SegUsuario();
	    if ($user)
	    	$usuario->nombreusuario = $user;
	    else
	    	$usuario->nombreusuario = $this->generarNombreUsuario($nombre, $papell, $sapell);
	    $usuario->contrasenna = md5($numid);
	    $usuario->contrasenabd = md5($numid);
	    $usuario->ip = '0.0.0.0/0';
	    $usuario->idtema = $tema[0]->idtema;
	    $usuario->iddominio = 0;
	    $usuario->ididioma = $idioma[0]->ididioma;
	    $usuario->iddesktop = $desktop[0]->iddesktop;
	    $usuario->idarea = '0';
	    $usuario->identidad = '0';
	    $usuario->idcargo = '0';
	    $usuario->save();
	    return $usuario->idusuario;
    }
    
    private function generarNombreUsuario($nombre, $papell, $sapell) {
    	$i = 1;
    	$existe = false;
    	do {
    		$nombreusuario = substr($nombre,0,$i) . $papell . substr($sapell,0,$i);
    		$usuario = SegUsuario::contarusuario($nombreusuario);
    		if($usuario)
     	   		$existe = true;
     	   	$i++;
    	} while ($existe);
    	return strtolower($nombreusuario);
    }
    
    public function obtenerIdUsuarioDadoNombre($nombreusuario) {
    	return SegUsuario::obtenerIdUsuarioDadoNombre($nombreusuario);
    }
    
    private function insertarCampo()
    {
    $idexpresion = NomExpresiones::obtenerexpresionRegular();
    $campo = new NomCampo();
    $campo->tipo = 'varchar';
    $campo->nombre = 'urlSIGAC';
    $campo->nombreamostrar = 'urlSIGAC';
    $campo->longitud = '200';
    $campo->visible = 'FALSE';
    $campo->idexpresiones = $idexpresion[0]->idexpresiones;
    $campo->descripcion = '';
    $campo->tipocampo = 'textfield';
    $campo->save();
    return $campo->idcampo;
    }
    
    private function insertarFila($idusuario) {
     $fila = new NomFila();
     $fila->idusuario = $idusuario;
     $fila->save();
     return $fila->idfila; 
    }
    
    private function insertarValor($idfila,$idcampo,$url) {
     $valor = new NomValor();
     $valor->idfila = $idfila;
     $valor->idcampo = $idcampo;
     $valor->valor = $url;
     $valor->save();
     return $valor->idvalor;
    }
    
  /**
     * getStoreSIGAC
     * Funcion para devolver datos al usuario de SIGAC.
     * 
     * @param $user - Nombre de usuario.
     * @return array - array. 
     */
    function getStoreSIGAC($user)
    {
      $idusuario = SegUsuario::verificarpass($user);
      $perfil = SegUsuario::cargarperfilusuario($idusuario[0]->idusuario);
      $datosSIGAC['usuario'] = $user;
      $datosSIGAC['password'] =  $idusuario[0]->contrasenna; 
      $datosSIGAC['url'] = $perfil[0]['NomFila']['NomValor'][0]['valor'];
      return $datosSIGAC;
    }
    
  /**
     * getUserNames
     * Funcion para devolver los nombres de usuarios.
     * 
     * @param $arrayid - Nombre de usuario.
     * @return array - array. 
     */
    function getUserNames($arrayid)
    {
     foreach($arrayid as $key=>$idusuario)
     {
      $nombreusuario = SegUsuario::cargarperfilusuario($idusuario);
      $arrayresult[$key]['nombreusuario'] =  $nombreusuario[0]['nombreusuario'];
     }
     return  $arrayresult;
    }
	
  /**
     * accessToFunctionality
     * Devuelve si un usuario tiene acceso una funcionalidad.
     * 
     * @param $certificate - Token de seguridad.
     * @param $url - Url.
     * @param $identity - Identificador de la entidad.
     * @return bool - bool. 
     */
    function accessToFunctionality($certificate,$url,$identity) {
      $objcertificado = new SegCertificado();                
      $datos = $objcertificado->verificarcertificado($certificate);                    
      $idusuario = $datos[0]->idusuario;
      $rol = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$identity);
      $acceso = DatFuncionalidad::accesoFuncionalidad($rol[0]->idrol,$url);
      if(count($acceso))
        return true;
      else
        return false;
    }
    
  /**
     * accessToAction
     * Devuelve si un usuario tiene acceso una accion.
     * 
     * @param $certificate - Token de seguridad.
     * @param $denominacion - Denominacion de la accion.
     * @param $identity - Identificador de la entidad.
     * @return bool - bool. 
     */
    function accessToAction($certificate,$denominacion,$identity)
    {
      $objcertificado = new SegCertificado();                
      $datos = $objcertificado->verificarcertificado($certificate);                    
      $idusuario = $datos[0]->idusuario;
      $rol = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$identity);
      $acceso = DatAccion::accesoAccion($rol[0]->idrol,$denominacion);
      if(count($acceso))
        return true;
      else
        return false;
    }
    
    /**
     * modifyStoreSIGAC
     * Modifica todos los datos que el usuario sigac tiene registrado.
     * 
     * @param string $userviejo - Usuario viejo.
     * @param string $nuevouser - Usuario nuevo.
     * @param string $pass - Contrase�a.
     * @param string $url - Url.
     * @return bool - bool. 
     */
    function modifyStoreSIGAC($userviejo, $nuevouser, $pass, $url) {
    $idusuario = SegUsuario::verificarpass($userviejo); 
    $objusuario = Doctrine::getTable('SegUsuario')->find($idusuario[0]->idusuario);
    if($userviejo != $nuevouser)
        {$objusuario->nombreusuario = $nuevouser;}
    $objusuario->contrasenna = $pass;
    $objusuario->contrasenabd = $pass;
    $objusuario->save();
    $perfil = SegUsuario::cargarperfilusuario($idusuario[0]->idusuario);
    $objvalor = Doctrine::getTable('NomValor')->find($perfil[0]['NomFila']['NomValor'][0]['idvalor']);
    $objvalor->valor = $url;
    $objvalor->save();
    if($objusuario->idusuario && $objvalor->idvalor)
        return true;
    else
        return false;
    }
    
/**
     * listadoUsuariosCompartimentados
     * Retorna todos los usuarios que pueden ser manipulados por el usuario logeado.
     * 
     * @param integer $iddominio - Usuario viejo.
     * @param integer $idusuario - Usuario nuevo.
     * @return array - array. 
     */
    public function listadoUsuariosCompartimentados($iddominio, $idusuario) {
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
			$arrayresult = array_merge($usuariosconpermisosadominios,$usuariosdelDominio);
			$arrayresult = array_unique($arrayresult);
			return $arrayresult;
    }
    
	private function arregloToUnidimensional($arrayDominios) {
				$array = array();
				foreach ($arrayDominios as $dominios)
					$array[] = $dominios['iddominio'];
				return $array;
			}
			
	private function arregloToUnidimensionalUsuario($arrayvalores){
			$array = array();
				foreach ($arrayvalores as $idusuario)
					$array[] = $idusuario['idusuario'];
				return $array;
		}
    /**
     * getDomainUser
     * Modifica todos los datos que el usuario sigac tiene registrado.
     * 
     * @param string $certificate - Certificado del usuario.
     * @param string $iddominio - Dominio del usuario.
     * @return array - array. 
     */
    function getDomainUser($certificate, $iddominio) {
    	$objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificate);
        $idusuario = $datos[0]->idusuario;
        if($idusuario) {
        	$arrayUser = SegUsuario::obtenerIdUsuariosDadoIdDominio($iddominio);
        }
        return $arrayUser;
    }
    
    /**
     * actualizarGruposDPO
     * Actualiza los grupos de planificacion.
     * 
     * @param array $usuarios - Arreglo de usuarios.
     * @param integer $idcargo - Id del cargo del usuario.
     * @param string $accion - Accion.
     * @return bool - boleano. 
     */
	 public function actualizarGruposDPO($usuarios, $idcargo, $accion) {
        	$integrator = ZendExt_IoC::getInstance();
        	try {
        		$integrator->planificacion->actualizarGruposDPO($usuarios, $idcargo, $accion);}
        	catch (ZendExt_Exception $exception) {
        		if($exception)
        			return false;
        	}
        }
        
     /**
     * getSistems
     * Devuelve los sistemas a los que tiene permiso un usuario en una entidad.
     * 
     * @param integer $idsistema - Id de sistema.
     * @param string $certificate - Certificado de seguridad asociado al usuario.
     * @param integer $entity - Entidad donde se encuentra logeado el usuario.
     * @return array - array. 
     */
     public function getSistems($certificate, $entity, $idsistema) {
     	$result = array();
     	$objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificate);   
        $idusuario = $datos[0]->idusuario;
        if($idusuario) {
        	$rol = DatEntidadSegUsuarioSegRol::obtenerrol($idusuario,$entity);
        	$result = DatSistema::cargarsistemasdelrol($idsistema,$rol[0]->idrol);
        }
        return $result;
     }
     
     /**
     * returnConex
     * Devuelve la conexion de un sistema por partes.
     * 
     * @param string $certificate - Certificado de seguridad asociado al usuario.
     * @param array $array - Arreglo de parametros.
     * @return array - array. 
     */
	 function returnConex($certificate, $array) {
		$result = array();
		$objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificate); 
        if(count($datos)) {
	    	if ( $array['idsistema'] && $array['idservidor'] && $array['idgestor'] && $array['idbd'] ){ 
	    		$result = DatEsquema::getSchemasConfig($array['idsistema'], $array['idservidor'], $array['idgestor'], $array['idbd']);
	    		return $result;
	    	} 
	    	elseif ( $array['idsistema'] && $array['idservidor'] && $array['idgestor']) {
	    		$result = DatBd::getBdConfig($array['idsistema'], $array['idservidor'], $array['idgestor']); 
	    		return $result;
	    	}
	    	elseif ( $array['idsistema'] && $array['idservidor']) {
	    		$result = DatGestor::getGestorConfig($array['idsistema'], $array['idservidor']); 
	    		return $result;
	    	}
	    	else {
	    		$result = DatServidor::getServerName($array['idsistema']);
	    		return $result;
	    	}
        }
        return $result;
    	 
    }
    
     /**
     * getReportByActions
     * Devuelve los reportes asociados a una accion.
     * 
     * @param string $certificate - Certificado de seguridad asociado al usuario.
     * @param integer $idaction - Accion.
     * @return array - array. 
     */
     function getReportByActions($certificate, $idaction) {
    	$objcertificado = new SegCertificado();
        $datos = $objcertificado->verificarcertificado($certificate);
        if (count($datos)) {
            
        	 $direccion ='http://'.$_SERVER['HTTP_HOST'].'/'.'report_generator.php/api/exportReport?id=';
                 $reportes = DatAccionDatReporte::cargaraccionesasociadasrep($idaction);

		            if(count($reportes))
		            {
		            	foreach ($reportes as $rep=>$valorrep)
			            $reportes[$rep]['url'] = $direccion.$valorrep['idreporte'];
		            }
        	 return $reportes;
        }
    }
    
    /**
     * cantidadDominios
     * Devuelve la cantidad de dominios existentes.
     *
     * @return integer - integer.
     */

    function cantidadDominios()
    {
     $cantidad= SegDominio::obtenercantnomdominio();
     return $cantidad;
    }

      /**
     * cantidadRoles
     * Devuelve la cantidad de reoles existentes.
     *
     * @return integer - integer.
     */
    function cantidadRoles()
    {
     $cantidad= SegRol::canttotalrol();
     return $cantidad;
    }
    /**
     * cantidadUsuarios
     * Devuelve la cantidad de usuarios existentes.
     *
     * @return integer - integer.
     */
    function cantidadUsuarios()
    {
     $valor = 0;
     $cantidad= SegUsuario::cantUsuarioAsociadoRol();
	if(count($cantidad))
	{
		foreach($cantidad as $valores)
		{
			 if($valores['activo']==1)			
			{
			  	$valor = 1;
	 			return $valor;
			}
               	}
    	}
	else
     return $valor;
    }
    
}
