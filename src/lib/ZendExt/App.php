<?php
	/**
	 * ZendExt_App
	 * 
	 * Gestor de la aplicacion integrado con el SIGES (SGIS).
	 * 
	 * @author Yoandry Morejon Borbon
	 * @package ZendExt
	 * @copyright UCID-ERP Cuba
	 * @version 1.0-0
	 */
	class ZendExt_App
	{
		/**
		 * Gestor de configuracion del framework
		 * 
		 * @var Zend_Config
		 */
		protected $config;
				
		/**
		 * Gestor del registro unico de objetos, arreglos, textos, ...
		 * 
		 * @var Zend_Register
		 */
		protected $register;
		
		/**
		 * Interfaz de seguridad
		 * 
		 * @var ZendExt_Security
		 */
		protected $security;
		
		/**
		 * Gestor de sessiones
		 * 
		 * @var Zend_Session_Namespace
		 */
		protected $session;
		
		/**
		 * Controlador frontal
		 * 
		 * @var Zend_Controller_Front
		 */
		protected $frontController;
		
		/**
		 * Conexion a la Base de Datos
		 * 
		 * @var Doctrine_Connection
		 */
		protected $conexion;
		
		/**
		 * Directorio de controladores externos
		 * 
		 * @var array of array of string
		 */
		protected $controllerArray = array();
		
		/**
		 * Perfil del usuario que se accedio al sistema
		 * 
		 * @var array
		 */
		protected $perfil;
		
		/**
		 * Peticion o solicitud al servidor web
		 * 
		 * Zend_Controller_Request_Abstract
		 */
		protected $request;
		
		/**
		 * Muestra la ventana de autenticacion por HTTP
		 * 
		 * @return void
		 */
		protected function showLogonWindow ()
		{
			//Se muetra la ventana de autenticacion. 
			header('WWW-Authenticate: Basic realm="SIGES"');
			//Si cancela la autenticacion se muestra un mensaje de acceso denegado
			header('HTTP/1.0 401 Unauthorized');
			die('<h1 style="color:#FF0000">Acceso denegado</h1>');
		}		
		
		/**
		 * Inicializa la aplicacion y controla las excepciones disparadas por el sistema
		 * 
		 * @param array $config - arreglo con la configuracion de la aplicacion
		 * @throws ZendExt_Exception - dispara las excepciones no controladas en tiempo de desarrollo.
		 * @return void
		 */
		public function init($config, $idapp = 0)
		{
			try
			{
				$this->initApp($config);
			}
			catch (ZendExt_Exception $e)
			{
				$e->handle();
			}
		}
		
		/**
		 * Inicializa la aplicacion
		 * 
		 * @param array $config - arreglo con la configuracion de la aplicacion
		 * @throws ZendExt_Exception - dispara las excepciones no controladas en tiempo de desarrollo.
		 * @return void
		 */
		protected function initApp($config)
		{
			try //Control de Excepciones
			{
				//Se inicializa el registro
				$this->initRegister();
				//Se inicializa la configuracion de la aplicacion
				$this->initConfig($config);
				//Se inicializa la configuracion de la session
				$this->initSession(); //Aspect OKP
				//Se inicializa la conexion
				$this->initConexion(); //Aspect OKP
				//Se chequea la seguridad de la aplicacion
				$this->checkSecurity(); //Aspect OKP
				//Se inicializa el controlador frontal
				$this->initFrontController();
				//Se inicializa el perfil del usuario
				$this->initPerfil(); //Aspect OKP
				//Se actualiza el registro
				$this->updateRegister();
				//Se intenta cargar el controlador solicitado
				$this->frontController->dispatch();
			}
			catch (Exception $e) //Si se captura una excepcion
			{
				if ($e instanceof ZendExt_Exception) //Si la excepcion capturada es de ZendExt 
					$e->handle (); //Se dispara la excepcion
				else {//Si es una excepcion no controlada
					$e = new ZendExt_Exception('NOCONTROLLED', $e); //Se dispara una excepcion controlada
					$aspectxml = ZendExt_FastResponse::getXML('aspect');
					if ($aspectxml->failedTraceAction['active'] == 'true')
						ZendExt_Aspect_Trace::getInstance()->failedTraceAction($e);
					throw $e;
				}
			}
		}
		
		/**
		 * Inicializa la instalacion de la aplicacion
		 * 
		 * @param array $config - arreglo con la configuracion de instalacion de la aplicacion
		 * @throws ZendExt_Exception - dispara las excepciones no controladas en tiempo de desarrollo.
		 * @return void
		 */
		public function initCargainicial($config)
		
		{
			try
			{
				$this->initRegister();
				$this->initConfig($config);
				$this->initSession();
				$this->initConexion();
				$this->checkSecurityInstalador();
				$this->initFrontController();
				$this->frontController->dispatch();
			}
			catch (Exception $e)
			{ 
				if ($e instanceof ZendExt_Exception) //Si la excepcion capturada es de ZendExt
					$e->handle (); //Se dispara la excepcion
				else {//Si es una excepcion no controlada
					$e = new ZendExt_Exception('NOCONTROLLED', $e); //Se dispara una excepcion controlada
					$aspecttemplatemt = ZendExt_FastResponse::getXML('aspect');
					if ($aspectxml->failedTraceAction['active'] == 'true')
						ZendExt_Aspect::getInstance()->failedTraceAction($e);
					throw $e;
				}
			}
		}
		public function initInstalacion($config)
		
		{
			try
			{
				$this->initRegister();
				$this->initConfigInstalacion($config);
				$this->initSession();
				$this->checkSecurityInstalador();
				$this->initFrontController();
				$this->frontController->dispatch();
			}
			catch (Exception $e)
			{ 
				if ($e instanceof ZendExt_Exception) //Si la excepcion capturada es de ZendExt
					$e->handle (); //Se dispara la excepcion
				else {//Si es una excepcion no controlada
					$e = new ZendExt_Exception('NOCONTROLLED', $e); //Se dispara una excepcion controlada
					$aspecttemplatemt = ZendExt_FastResponse::getXML('aspect');
					if ($aspectxml->failedTraceAction['active'] == 'true')
						ZendExt_Aspect::getInstance()->failedTraceAction($e);
					throw $e;
				}
			}
		}
		
		/**
		 * Inicializa la configuracion de la aplicacion
		 * 
		 * @param array $config - arreglo con la configuracion de la aplicacion
		 * @return void
		 */
		protected function initConfig($config)
		{
			$configApp = new ZendExt_App_Config();
			$this->config = $configApp->configApp($config);
			//Se guarda en el singelton la configuracion del framework.
			$this->register->config = $this->config;
		}
		
		/**
		 * Inicializa la configuracion de instalacion de la aplicacion
		 * 
		 * @param array $config - arreglo con la configuracion de instalacion de la aplicacion
		 * @return void
		 */
		protected function initConfigInstalacion($config)
		{
			$configApp = new ZendExt_App_Config();
			$this->config = $configApp->configApp($config, false);
			//Se guarda en el singelton la configuracion del framework.
			$this->register->config = $this->config;
		}
		
		/**
		 * Inicializa la session del usuario
		 * 
		 * @return void
		 */
		protected function initSession()
		{
			//Se inicia la sesion.
			session_save_path($this->config->session_save_path);
			Zend_Session::start(array('save_path' => $this->config->session_save_path));
			$this->session = new Zend_Session_Namespace ('UCID_Cedrux_UCI');
			
			//Se busca en la cache si la session se inicializo
			$cacheObj = ZendExt_Cache::getInstance();
			$cacheData = $cacheObj->load(session_id());
			
			//Se protege el id de la session contra ataques.
			if (!isset($cacheData->initialized))
			{
			    Zend_Session::regenerateId();
			    $sessionStd = new stdClass();
			    $sessionStd->initialized = true;
			    $cacheObj->save($sessionStd, session_id());
			}
			
			//Se guarda en el registro la session.
			$this->register->session = $this->session;
		}
				
		/**
		 * Inicializa el registro unico de objetos, arreglos, ...
		 * 
		 * @return void
		 */
		protected function initRegister()
		{
			//Se crea el registro de objetos, variables y arreglos
			$this->register = new Zend_Registry(array(), ArrayObject::ARRAY_AS_PROPS);
			//Se guarda el registro en el singelton de Registro
			Zend_Registry::setInstance($this->register);
		}
				
		/**
		 * Actualiza el registro unico de objetos, arreglos, ...
		 * 
		 * @return void
		 */
		protected function updateRegister()
		{
			//Se guarda en el singelton seguridad.
			$this->register->seguridadInstance = $this->security;
			//Se guarda en el registro la session.
			$this->register->session = $this->session;
			//Se guarda en el registro la conexion activa.
			$this->register->conexion = $this->conexion;
		}
				
		/**
		 * Chequea la seguridad de aplicacion
		 * 
		 * @return void
		 */
		protected function checkSecurity()
		{
			//$aspectxml = ZendExt_FastResponse::getXML('aspect');
			//if ($aspectxml->checkSecurity['active'] == 'true') {
				$security = ZendExt_Aspect_Security_Sgis::getInstance();
				$security->authenticateUser();
				$security->verifyAccessToFunctionality();
			//}
		}
				
		/**
		 * Inicializa el controlador frontal
		 * 
		 * @return void
		 */
		protected function initFrontController()
		{
			//Se inicia el controlador frontal.
			$this->frontController = Zend_Controller_Front::getInstance();
			//Se obtiene la peticion o solicitud al servidor web
			$this->request = $this->frontController->getRequest();
			//Se activan las excepciones
			$this->frontController->throwExceptions(true);
			//Se especifica el directorio de los controladores
			$this->frontController->setControllerDirectory($this->config->controllers_path);
			if (count($this->controllerArray)) //Si el array de controladores externos no esta vacio
			{
				//Se adicionan los controladores externos
				foreach ($this->controllerArray as $controller)
				{
					//Adiciono cada uno de los controladores
					$this->frontController->addControllerDirectory($controller[0], $controller[1]);
				}
			}
		}
				
		/**
		 * Inicializa la conexion a la base de datos
		 * 
		 * @throws ZendExt_Exception - excepcion declarada en el xml de excepciones
		 * @return void
		 */
		protected function initConexion()
		{
			//Obtengo una instancia del administrador de transacciones
			$transactionManager = ZendExt_Aspect_TransactionManager::getInstance();
			//Creo la conexion activa
			$this->conexion = $transactionManager->openConections(null, true);
		}
	
		/**
		 * Adiciona un nuevo directorio de controladores
		 * 
		 * @return void
		 */
		public function addController($controllerDirectory, $moduleName)
		{
			$this->controllerArray[] = array($controllerDirectory, $moduleName);
		}
		
		/**
		 * initPerfil
		 * 
		 * Inicializa el perfil del usuario
		 * 
		 * @throws ZendExt_Exception - dispara excepcion declarada en el xml de excepciones
		 * @return void
		 */
		public function initPerfil()
		{
			$security = ZendExt_Aspect_Security_Sgis::getInstance();
			$security->getUserData();
		}
					
		/**
		 * Chequea la seguridad para el instalador
		 * 
		 * @return void
		 */
		protected function checkSecurityInstalador()
		{
			//$aspectxml = ZendExt_FastResponse::getXML('aspect');
			//if ($aspectxml->checkSecurity['active'] == 'true') {
				$security = ZendExt_Aspect_Security_Sgis::getInstance();
				$security->authenticateUserInstalador();
				//$security->verifyAccessToFunctionality();
			//}
		}
	}

