<?php
class ZendExt_Aspect_Security implements ZendExt_Aspect_ISinglenton {

	/**
	 * Constructor de la clase, es privado para impedir que pueda 
	 * ser instanciado y de esta forma garantizar que la instancia 
	 * sea un singlenton
	 * 
	 * @return void
	 */
	private function __construct() {
	
	}
	
	/**
	 * Obtencion de la instancia de la clase, ya que esta no puede ser 
	 * instanciada directamente debido a que es un singlenton
	 * 
	 * @return ZendExt_Aspect_Security - instancia de la clase
	 */
	static public function getInstance() {
		static $instance;
		if (!isset($instance))
			$instance = new self();
		//Se guarda en el singelton seguridad.
		$register = Zend_Registry::getInstance();
		$register->seguridadInstance = $instance;
		return $instance;
	}

	/**
	 * authenticateUser
	 * Realiza el proceso de autenticacion de un usuario a traves del SIGES
	 * 
	 * @return void
	 */
	public function authenticateUser () {
		$session = Zend_Registry::getInstance()->session;
		$rsaObj = new ZendExt_RSA();
		$keys = $rsaObj->generate_keys ('9990454949', '9990450271', 0);
		if (isset($_SERVER ['PHP_AUTH_USER'])) {
			//Se guarda en la session el usuario y el password
			$session->usuario = $_SERVER ['PHP_AUTH_USER'];
			$session->pass = $rsaObj->encrypt ($_SERVER ['PHP_AUTH_PW'], $keys[1], $keys[0], 5);
		}
		//Si no existe el certificado, ni se autentico por http y no se ha cerrado la session.
		if (!$session->certificado && !$session->close && $session->usuario && $session->pass)
		{			
			$session->certificado = $this->getCertificate($session->usuario, $rsaObj->decrypt ($session->pass, $keys[2], $keys[0]));
			if ($session->certificado) //Si existe el certificado
			{
				$config = Zend_Registry::get('config');
				//Se define cada que tiempo expira la variable de sesion del certificado
				$session->setExpirationSeconds($config->security->ttl, 'certificado');
			}
			else
			{
				//Se limpian todas las variables de session.
				$this->clearOutSession();
				$session->unsetAll();
				//Se muetra la ventana de autenticacion. 
				$this->showLogonWindow();
			}
		}
		elseif ($session->close)
		{
			//Se limpian todas las variables de session.
			$this->clearOutSession();
			$session->unsetAll();
			//Se muetra la ventana de autenticacion. 
			$this->showLogonWindow();
		}
		elseif (!$session->certificado) {
			//Se limpian todas las variables de session.
			$this->clearOutSession();
			$session->unsetAll();
			//Se muetra la ventana de autenticacion. 
			$this->showLogonWindow();
		}
	}
	
	protected function clearOutSession () {
		if (isset($_SESSION['__ZF']) && is_array($_SESSION['__ZF'])) {
			foreach($_SESSION['__ZF'] as $key => $sesszf) {
				$namespace = $key;
				break;
			}
			$session = $_SESSION;
			foreach ($session as $key=>$sess) {
				if ($key != $namespace && $key != '__ZF')
					unset ($_SESSION[$key]);
			}
			unset($_SESSION['__ZF'][$namespace]);
		}
	}
	
	/** 
	 * getCertificate
	 * Obtiene el certificado del usuario
	 * 
	 * @param $user - nombre o alias del usuario que se registro
	 * @param $password - clave de acceso del usuario
	 * @return string - devuelve el certificado asignado al usuario
	 * @ignore return - retorna 1, pendiente a utilizacion
	 */
	protected function getCertificate ($user, $password) {
		return 1;
	}
	
	/**
	 * getUserData
	 * Obtiene los datos del usuario autenticado a traves de un servicio de
	 * negocio que brinda el componente portal
	 * 
	 * @return void
	 */
	public function getUserData() {
		$register = Zend_Registry::getInstance();
		$session = $register->session;
		if (!$session->perfil)
		{
			$integrator = ZendExt_IoC::getInstance();
			$usuario = $integrator->portal->BuscarUsuarioByAlias($session->usuario);
			$usuarioArr = $usuario->toArray();
			$session->perfil = $usuarioArr[0];
		}
	}
	
	/**
	 * hasAccess
	 * Verifica que un usuario tiene acceso a una funcionalidad y a una
	 * accion.
	 * 
	 * @return boolean - retorna true si el usuario autenticado tiene acceso falso sino.
	 * @ignore - Ignorar el resultado pq siempre se devuelve true.
	 */
	public function hasAccess() {
		return true;
	}
	
	/**
	 * Muestra la ventana de autenticacion por HTTP
	 * 
	 * @return void
	 */
	protected function showLogonWindow ()
	{
		//Se muetra la ventana de autenticacion. 
		header('WWW-Authenticate: Basic realm="Acaxia"');
		//Si cancela la autenticacion se muestra un mensaje de acceso denegado
		header('HTTP/1.0 401 Unauthorized');
		if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) //Si la peticion es por ajax
			die(json_encode (array('codMsg'=>3,'mensaje'=>'<b> Acceso denegado </b>'))); //Se imprime la excepcion en codigo json
		else
			die('<h1 style="color:#FF0000">Acceso denegado</h1>'); 
	}

	public function verifyAccessToFunctionality () {
		
	}
	/**
	 * authenticateUser
	 * Realiza el proceso de autenticacion de un usuario a traves del SIGES
	 * 
	 * @return void
	 */
	public function authenticateUserInstalador () {
		$session = Zend_Registry::getInstance()->session;
		//Si no existe el certificado, ni se autentico por http y no se ha cerrado la session
			$rsaObj = new ZendExt_RSA();
			$keys = $rsaObj->generate_keys ('9990454949', '9990450271', 0);
			//Se guarda en la session el usuario y el password
			$session->usuario = 'instalacion';
			$session->pass = $rsaObj->encrypt ('instalacion', $keys[1], $keys[0], 5);
	}
}
