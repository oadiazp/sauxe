<?php
class ZendExt_History_Db_Concrete {
	private $_conn;
	private $_rsa;
	public function __construct() {
		$this->_rsa =  new ZendExt_RSA();
		//print '<pre>';
		//$configInstance = Zend_Registry :: get ('config');
			//$this->_conn = new PDO("$configInstance->gestor:dbname=$configInstance->bd host=$configInstance->host", $configInstance->usuario, $configInstance->password);
			//$this->_conn->exec ("set search_path = 'historial';");
			$pass = $this->_rsa->decrypt('91390886301531470515 44519431940775260482 13326532545045264097', '85550694285145230823', '99809143352650341179');
	$this->_conn = new PDO("pgsql:dbname=erp2 host=10.12.171.233", 'erp', $pass);
	$this->_conn->exec ("set search_path = 'mod_historial';");
	}
	/*91390886301531470515 44519431940775260482 13326532545045264097*/
	public function query($pSQL) {
		$pSQL .= ";";
		
		$query = $this->_conn->query ( $pSQL );
		
		return ($query != null) ? $query->fetchAll ( PDO::FETCH_ASSOC ) : false;
	}
}
?>