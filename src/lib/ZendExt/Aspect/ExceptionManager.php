<?php
/**
 * ZendExt_Exception_Manager
 * Controla las excepciones segun su especificacion
 * en el fichero managerexception.xml
 * 
 * @author: Yoandry Morejon Borbon
 * @copyright UCID-ERP Cuba
 * @package: ZendExt
 * @subpackage ZendExt_Exception
 * @version 1.0-0
 */
class ZendExt_Aspect_ExceptionManager implements ZendExt_Aspect_ISinglenton {
	
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
	 * @return ZendExt_Aspect_Validation - instancia de la clase
	 */
	static public function getInstance() {
		static $instance;
		if (!isset($instance))
			$instance = new self();
		return $instance;
	}
	
	/**
	 * Dispara las excepciones controladas en una accion segun su
	 * especificacion en el xml  managerexception.xml
	 * 
	 * @param Exception $e - Excepcion disparada durante la ejecucion de la accion
	 * @throws ZendExt_Exception - Excepcion controlada por el sistema
	 */
	public function handle(SimpleXMLElement $xmlAction = null, Exception $e = null) {
		if (isset($xmlAction)) {
			foreach ($xmlAction->exception as $exception) {
				$type = (string) $exception['type'];
				if ($e instanceof $type) {
					$code = (string) $exception['code'];
					$filter = (string) $exception['filter'];
					if (!$filter || $this->existFilter($filter, $e)) {
						throw new ZendExt_Exception($code, $e);																			
					}	
				}
			}
		}		
		throw $e;
	}
	
	public function existFilter($filter, Exception $e) {
		if (substr_count($e->getMessage(), $filter))
			return true;
		return false;
	}
}
