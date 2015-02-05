<?php
/**
 * ZendExt_RSA_Facade
 * Algoritmo RSA
 * 
 * @author Darien Garcia Tejo
 * @package ZendExt
 * @subpackage ZendExt_RSA
 * @copyright UCID - ERP Cuba
 * @version 1.0-0
 */
class ZendExt_RSA_Facade {
	
	/**
     * Constructor de la clase
     */
    public function __construct () {
    	
	}
	
	function encrypt($string) {
		$RSA = new ZendExt_RSA();
		$keys = $RSA->generate_keys ('9990454949', '9990450271', 0);		
		$encoded = $RSA->encrypt ($string, $keys[1], $keys[0], 5);
		return $encoded;
	}
	
	function decrypt($encoded) {
		$RSA = new ZendExt_RSA();
		$keys = $RSA->generate_keys ('9990454949', '9990450271', 0);
		$decoded = $RSA->decrypt ($encoded, $keys[2], $keys[0]);
		return $decoded;
	}
}
