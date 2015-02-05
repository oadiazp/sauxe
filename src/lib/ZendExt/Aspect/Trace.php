<?php
/**
 * ZendExt_Aspect_Trace
 * 
 * Integracion de trazas
 * 
 * @author: Elianys Hurtado && Aquiles Maso
 * @copyright UCID-ERP Cuba
 * @package: ZendExt
 * @version 1.5-0
 */
class ZendExt_Aspect_Trace implements ZendExt_Aspect_ISinglenton {
	
	private $start_script_runtime = 0;
	
	private $end_script_runtime = 0;

    private $memory;
		
	/**
	 * Constructor de la clase, es privado para impedir que pueda 
	 * ser instanciado y de esta forma garantizar que la instancia 
	 * sea un singlenton
	 * 
	 * @return void
	 */
	private function __construct() {
	
	}
	
	static public function getInstance() {
		static $instance;
		if (! isset ( $instance ))
			$instance = new self ( );
		return $instance;
	}
	
	/**
	 * Salva de las trazas de inicio de accion
	 *
	 */
	public function beginTraceAction() {		
		$this->script_runtime();
		$frontController = Zend_Controller_Front::getInstance ();
		$controller = $frontController->getRequest()->getControllerName ();
		$action=$frontController->getRequest ()->getActionName();				
		$register = Zend_Registry::getInstance();
		$user = $register->session->usuario;	
		$global=ZendExt_GlobalConcept::getInstance();
		$idestructura=$global->Estructura->idestructura;
		$moduleReference=$register->config->module_reference;
		$array=explode('/',$moduleReference);
		$categoria=$global->Subsistema->idsubsistema;
		$beginTrace = new ZendExt_Trace_Container_Action(false, true, $moduleReference, $controller, $action, $categoria,  $user, $idestructura);
		$instance = ZendExt_Trace :: getInstance ();
		$instance->handle ($beginTrace);

        $this->memory = memory_get_usage();
	}
	/**
	 * Salva de las trazas de fin de accion
	 *
	 */
	
	public function endTraceAction() {
		$register = Zend_Registry::getInstance();
		$usuario = $register->session->usuario; 
		$categoria = 99;
		$frontController = Zend_Controller_Front::getInstance ();
		$action = $frontController->getRequest ()->getActionName ();
		$controller = $frontController->getRequest ()->getControllerName ();
		$this->script_runtime();
		$exectime=$this->end_script_runtime;
		$global=ZendExt_GlobalConcept::getInstance();
		$idestructura=$global->Estructura->idestructura;
		$moduleReference=$register->config->module_reference;
        $memory = (memory_get_usage() - $this->memory)/1048576;
		$endTrace = new ZendExt_Trace_Container_Performance($exectime, $memory, false, false,  $moduleReference, $controller, $action, $categoria, $idestructura, $usuario);
		$instance = ZendExt_Trace :: getInstance ();
		$instance->handle ($endTrace);
	}
	/**
	 * Salva las trazas de fallo de la accion  
	 *
	 */
	public function failedTraceAction(Exception $e = null)
	{
		if  (!($e instanceof ZendExt_Exception))
			$e = new ZendExt_Exception('NOCONTROLLED', $e);
		$this->start_script_runtime=0;
		$this->end_script_runtime=0;
		$global = ZendExt_GlobalConcept::getInstance();
		$frontController = Zend_Controller_Front::getInstance ();
		
		$request = $frontController->getRequest(); 

		if ($request) {
			$controller = $frontController->getRequest()->getControllerName ();	
			$action = $frontController->getRequest ()->getActionName ();
		}
		else {
			$controller = '';
			$action = '';
		}
		
		$register = Zend_Registry::getInstance();
		$moduleReference = $register->config->module_reference;
		$categoria = $global->Subsistema->idsubsistema;
		$user = $global->Perfil->usuario;
		$idestructura = $global->Estructura->idestructura;
		$failedTrace = new ZendExt_Trace_Container_Action(true,false,$moduleReference, $controller, $action, $categoria,$user, $idestructura);
		
		$codeExc = $e->getIdException();
		$typeExc = $e->getType();
		$langExc = $global->Perfil->idioma;
		$msgExc = $e->getDescription();
		$descExc = $e->getMessage();
		$traceExc = $e->getTraceAsString();
		$exceptionTrace = new ZendExt_Trace_Container_Exception($codeExc,$typeExc,$langExc,$msgExc,$descExc,$traceExc,$categoria,$user,$idestructura);
		
		$instance = ZendExt_Trace :: getInstance ();
		$instance->handle ($failedTrace);
		$instance->handle ($exceptionTrace);
	}

	/**
	 * Funcion para el calculo del tiempo de ejecucion de la accion
	 */
	private function script_runtime ( $round = 20 ) {
		 //Check to see if the global is already set
		 if ($this->start_script_runtime != 0) {
			//The global was set. So, get the current microtime and explode it into an array.
			list($msec, $sec) = explode(" ", microtime());
			$this->end_script_runtime = round(($sec + $msec) - $this->start_script_runtime, $round);
		 }
		 else {
			 // The global was not set. Create it!
			 list($msec, $sec) = explode(" ", microtime());
			 $this->start_script_runtime = $sec + $msec;
		 }
	}
	
	/**
	 * Salva las trazas de excepciones
	 *
	 * @param ZendExt_Exception $exception
	 */
	public function exceptionTraceAction($exception)
	{
		$code = $exception->getIdException();
		$type = $exception->getType();
		$register = Zend_Registry::getInstance();
		$languaje = $register->session->perfil['idioma'];;
		$mensaje = $exception->getMessage();
		$descripcion = (string) $exception->getDescription();
		$global = ZendExt_GlobalConcept::getInstance();
		$idestructura = $global->Estructura->idestructura;
		$moduleReference = $register->get(config)->module_reference;
		$log = $exception->getInnerException ();
		$categoria = $global->Subsistema->idsubsistema;		
		$user = $register->session->usuario;
		$exceptionTrace = new ZendExt_Trace_Container_Exception($code, $type, $languaje, $mensaje, $descripcion, $log, $categoria, $user, $idestructura);
		$instance = ZendExt_Trace :: getInstance ();
		$instance->handle ($exceptionTrace);
	}

	public function failedTraceIoC($exception, $targetComponent, $class, $method, $iocType)
	{
		$sourceComponent = Zend_Registry::get('config')->module_name;
		$register = Zend_Registry::getInstance();
		$usuario = $register->session->usuario;
		$global = ZendExt_GlobalConcept::getInstance();
		if ($iocType == 'ioc')
			$pIdTraceCategory = '99';
		else
			$pIdTraceCategory = $global->Subsistema->idsubsistema;
		$pIdCommonStructure = $global->Estructura->idestructura;
		$request = Zend_Controller_Front::getInstance () -> getRequest();
		if($request) {
			$action = $request->getActionName();
			$controller = $request->getControllerName();
		}
		else {
			$action = 'framework';
			$controller = 'framework';
		}
		$code = $exception->getIdException();
		$mensaje = $exception->getMessage();
		$descripcion = (string) $exception->getDescription();
		$log = (string)$exception->getInnerException ();
		$integracion = new ZendExt_Trace_Container_IoCException($code, $mensaje, $descripcion, $sourceComponent, $targetComponent, $class, $method, $action, $log, $controller, $pIdTraceCategory, $usuario, $pIdCommonStructure);
		
		$instance = ZendExt_Trace :: getInstance ();
		$instance->handle ($integracion);
	}

	public function beginTraceIoC($targetComponent, $class, $method, $iocType)
	{
		$sourceComponent = Zend_Registry::get('config')->module_name;
		$register = Zend_Registry::getInstance();
		$usuario = $register->session->usuario;
		$global = ZendExt_GlobalConcept::getInstance();
		if ($iocType == 'ioc')
			$pIdTraceCategory = '99';
		else
			$pIdTraceCategory = $global->Subsistema->idsubsistema;
		$pIdCommonStructure = $global->Estructura->idestructura;
		$request = Zend_Controller_Front::getInstance () -> getRequest();
		if($request) {
			$action = $request->getActionName();
			$controller = $request->getControllerName();
		}
		else {
			$action = 'framework';
			$controller = 'framework';
		}
		if($iocType == 'ioc')
		{
			$integracion = new ZendExt_Trace_Container_IoC(false, $sourceComponent, $targetComponent, $action, $class, $method, $pIdTraceCategory, $usuario, $pIdCommonStructure);
		}
		else
		{
			$integracion = new ZendExt_Trace_Container_IoC(true, $sourceComponent, $targetComponent, $action, $class, $method, $pIdTraceCategory, $usuario, $pIdCommonStructure);	
		}
		$instance = ZendExt_Trace :: getInstance ();
		$instance->handle ($integracion);
	}
}
