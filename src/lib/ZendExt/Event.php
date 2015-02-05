<?php
	/**
	 * ZendExt_Event
	 * 
	 * Clase para modelar eventos
	 * 
	 * @author Omar Antonio Díaz Peña.
	 * @package ZendExt.
	 * @copyright ERP-Cuba.
	 * @version 1.0.0.
	 */
	class ZendExt_Event {
		/**
		 * Instancia del Xml de eventos
		 *
		 * @var SimpleXml
		 */
		private $_xml;
		
		/**
		 * Instancia del IoC
		 *
		 * @var unknown_type
		 */
		private $_ioc;
		
		/**
		 * Instancia para la implementación del patrón Sigleton
		 *
		 * @var ZendExt_Event
		 */
		private static $_instance;
		
		/**
		 * Constructor
		 */
		private function __construct() {
			$this->_xml = ZendExt_FastResponse::getXML('events');
			$this->_ioc = ZendExt_IoC::getInstance();
		}
		
		/**
		 * Retorna una instancia de ZendExt_Event para el singleton
		 *
		 * @return ZendExt_Event
		 */
		static function getInstance () {
			if (self :: $_instance == null)
				self :: $_instance = new self ();
				
			return self :: $_instance;
		}
		
		/**
		 * Envia un evento pasándole los parámetros por POST
		 *
		 * @param String $pEventName.
		 * @param array $pParams.
		 * @return void.
		 */
		function dispatch ($pEventName, $pParams = array ()) {
            //Arreglo de parametros a pasarle a la invocación del evento
			$parameters = array ();

			//Se obtienen los observadores del evento
			$observers = $this->_xml->$pEventName->observers;
			
			//Se recorren cada uno de los observadores
			foreach ($observers->children () as $var) {
				//Se obtienen los datos de un evento, la referencia, el controlador y la acción.
				$src = (string) $var ['reference']; $controller = (string) $var ['controller']; 
				$action = (string) $var ['action'];
				
				//Se ajusta el include_path en función de la referencia
				$this->_ioc->setIncludePath($src);
				
				//Si existe la clase que implementa en observador
				if (class_exists($controller)) {
					//Se instancia la clase
					$controllerInstance = new $controller ();
					
					//Si existe el método
					if (method_exists($controllerInstance, $action)) {
						//Se llama al método del controlador que implementa el evento
						$controllerInstance->$action ($pParams);
					} else throw new ZendExt_Exception ('EVENT002'); //Excepción por si no existe el método
				} else throw new ZendExt_Exception ('EVENT001'); //Excepción por si no existe la clase que implementa el evento
			}	
		}
	}
	
?>