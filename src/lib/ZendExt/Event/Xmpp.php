<?php
/**
 *  
 */
class ZendExt_Event_Xmpp
{
	private $conn;

	public function __construct ()
	{
		$xml      = ZendExt_FastResponse :: getXML ('xmpp');
		$server   = (string) $xml->php ['server'];
		$port	  = 5222;
		$username = (string) $xml->php ['username'];
		$password = (string) $xml->php ['password'];		

		$this->conn = new ZendExt_Xmpp ($username, $password, $port, $server);
		$this->conn->useEncryption (false);
		$this->conn->connect();
		$this->conn->processUntil('session_start');
		$this->conn->presence ();
	}

	public function getOnlineUsers ()
	{
		$cache = ZendExt_Cache :: getInstance ();
		$users = $cache->load ('onlineUsers');
		return array_keys($users);
	}


	public function dispatch ()
	{
		$transactionManager = ZendExt_Aspect_TransactionManager::getInstance();
		$configApp = new ZendExt_App_Config();
		$configApp->addBdToConfig('traza', 'traza');
		$conn = $transactionManager->openConections('traza');

		$query = Doctrine_Query :: create ($conn);
		$result = $conn->execute ('select denominacion, count (his_traza.idcategoriatraza) as cantidad 
								   from mod_traza.nom_categoriatraza
								   inner join mod_traza.his_traza 
								   on his_traza.idcategoriatraza = nom_categoriatraza.idcategoriatraza
								   group by his_traza.idcategoriatraza, nom_categoriatraza.denominacion;')
					    ->fetchAll (Doctrine :: FETCH_OBJ);

		foreach ($this->getOnlineUsers () as $user) {
			$this->conn->message ($user,
						   json_encode(
								   			array (
										   			'code' => '001',
										   			'msg'  => array ('data' => $result)
								   	 			  )
							   	 			)
				   	 		);
		}		

 		$this->conn->disconnect();
	}
}