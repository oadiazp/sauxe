<?php
/**
 * Componente para gestionar las acciones de un sistema.
 * 
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garcia Tejo
 * @author Julio Cesar Garci­a Mosquera   
 * @version 1.0-0
 */
class IndexController extends ZendExt_Controller_Secure {
	
	function init ()
	{
		parent::init(false);
	}
	
	function indexAction()
	{
		$this->render();
	}
        
	function instalacionAction() {
		$direccion =$_SERVER['HTTP_HOST'].'/';
		
		$host = $this->_request->getPost('servidor');
		$dbname = $this->_request->getPost('basedatos');
		$user = $this->_request->getPost('usuario');
		$pass = $this->_request->getPost('password');
		$opcion = $this->_request->getPost('opcion');
		$port = $this->_request->getPost('puerto');
		$conexion = null;

		try {
                    $dsn = "pgsql://$user:$pass@$host:$port/postgres";
                    $conexion = Doctrine_Manager :: connection ( $dsn ) ;
                    $sql="CREATE DATABASE $dbname  WITH OWNER = postgres ENCODING = 'UTF8';";
                    $conexion->exec($sql);
                } catch (Exception $e) {
                    echo '<pre>'; print_r ($e); die;
                }
                
                if($this->configBD($host, $dbname, $user,$port, $pass, $opcion)) {
                        $this->configFicheros($host, $dbname);
                        $url = array('sussess'=>true,'cadena'=>$direccion);
                        echo json_encode($url);
                }
                else {
                        $query = "DROP DATABASE $dbname;";
                        $conexion->exec($query);
                        echo "{'codMsg':3,'mensaje':'Hubo problemas mientras se trataba de restablecer la base de datos. Por favor verifique los scripts.'}";
                }
	}
        
	function configBD($host, $dbname, $user,$port, $pass, $opcion)
	{
		
                $dsn = "pgsql://$user:$pass@$host:$port/$dbname";
                $conexionbd = Doctrine_Manager :: connection ( $dsn ) ;
                
		$xml = ZendExt_FastResponse::getXML ( 'configuracionservidor' );
		if ($xml instanceof SimpleXMLElement) {
			$number = $xml->idservidor ['numero'];
			$ddmmorigen = file_get_contents ( 'comun/script/datosmaestros/2-ddmm.sql' );
			$ddmm = str_replace ( '?', $number, $ddmmorigen );
		}
		
		//$conexionbd = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
		if($opcion != 'windows')
		    {
		      	$lenguaje = file_get_contents('comun/script/1-inicial_L.sql');

		    }
		else 
			{
				$lenguaje = file_get_contents('comun/script/1-inicial_W.sql');
			}
			
			
		//$ddmmorigen = file_get_contents ( 'comun/script/datosmaestros/2-ddmm.sql' );
		$dmaestros1 = file_get_contents ( 'comun/script/datosmaestros/1-ddmm.sql' );
		$estructura1 = file_get_contents ( 'comun/script/estructura/1-estructura.sql' );
		$nomenclador1 = file_get_contents ( 'comun/script/nomencladores/1-nom.sql' );
		$seguridad1 = file_get_contents ( 'comun/script/seguridad/1-seg.sql' );
		$traza1 = file_get_contents ( 'comun/script/traza/1-traz.sql' );

		
		//Scripts de estructura de los modulos basicos   
		$estructura2 = file_get_contents ( 'comun/script/estructura/2-estructura.sql' );
		$nomenclador2 = file_get_contents ( 'comun/script/nomencladores/2-nom.sql' );
		$estructura_nom_2 = file_get_contents ( 'comun/script/estructura/2-(estructura-nom).sql' );
		$traza2 = file_get_contents ( 'comun/script/traza/2-traz.sql' );
		$seguridad2 = file_get_contents ( 'comun/script/seguridad/2-seg.sql' );
		
		//Scripts de datos de los modulos basicos
		$estructura3 = file_get_contents ( 'comun/script/estructura/3-estructura.sql' );
		$nomenclador3 = file_get_contents ( 'comun/script/nomencladores/3-nom.sql' );
		$traza3 = file_get_contents ( 'comun/script/traza/3-traz.sql' );
		$seguridad3 = file_get_contents ( 'comun/script/seguridad/3-seg.sql' );

		//Script de permisos
		$ddmm4 = file_get_contents ( 'comun/script/datosmaestros/4-ddmm.sql' );
		$estructura4 = file_get_contents ( 'comun/script/estructura/4-estructura.sql' );
		$nomenclador4 = file_get_contents ( 'comun/script/nomencladores/4-nom.sql' );
		$traza4 = file_get_contents ( 'comun/script/traza/4-traz.sql' );
		$seguridad4 = file_get_contents ( 'comun/script/seguridad/4-seg.sql' );

		//Comienzo la ejecuci�n de los scripts cargados
		
		
		//Ejecuci�n de los scripts para crear roles de los subsistemas bases: ddmm, estructura,nom, traz,seg

                try {
                    $conexionbd->exec ( utf8_encode ( $lenguaje ) );
                    $conexionbd->exec ( utf8_encode ( $ddmm ) );
                    $conexionbd->exec ( utf8_encode ( $dmaestros1 ) );
                    $conexionbd->exec ( utf8_encode ( $estructura1 ) );
                    $conexionbd->exec ( utf8_encode ( $nomenclador1 ) );
                    $conexionbd->exec ( utf8_encode ( $traza1 ) );
                    $conexionbd->exec ( utf8_encode ( $seguridad1 ) );

                    //script de estructura

                    $conexionbd->exec ( utf8_encode ( $estructura2 ) );
                    $conexionbd->exec ( utf8_encode ( $nomenclador2 ) );
                    $conexionbd->exec ( utf8_encode ( $estructura_nom_2 ) );
                    $conexionbd->exec ( utf8_encode ( $traza2 ) );
                    $conexionbd->exec ( utf8_encode ( $seguridad2 ) );

                    //Ejecuci�n de los scripts de datos de los subsistemas bases: ddmm, estructura,nom, traz,seg
                    $conexionbd->exec (  $nomenclador3 );
                    $conexionbd->exec (  $estructura3 );
                    $conexionbd->exec (  $traza3 );
                    $conexionbd->exec (  $seguridad3 );
                    //Ejecucion de los scripts de permisos
                    $conexionbd->exec ( utf8_encode ( $ddmm4 ) );
                    $conexionbd->exec ( utf8_encode ( $estructura4 ) );
                    $conexionbd->exec ( utf8_encode ( $nomenclador4 ) );
                    $conexionbd->exec ( utf8_encode ( $traza4 ) );
                    $conexionbd->exec ( utf8_encode ( $seguridad4 ) );
                }

                catch (Exception $e) {
                    echo '<pre>'; print_r($e); die;
                }

		return true;
	}
        
	function configFicheros($host, $dbname)
	{
		$register = Zend_Registry::getInstance();
		$dirModulesConfig = $register->config->xml->modulesconfig;
		$dirNomConfig = $register->config->xml->nomconfig;
		
		$modulesConfig = new SimpleXMLElement($dirModulesConfig, null, true);
		$nomConfig = new SimpleXMLElement($dirNomConfig, null, true);  
		if (isset($modulesConfig->conn['host']))
			$modulesConfig->conn['host'] = $host;
		else $modulesConfig->conn->addAttribute('host', $host);
		if (isset($modulesConfig->conn['bd']))
			$modulesConfig->conn['bd'] = $dbname;
		else $modulesConfig->conn->addAttribute('bd', $dbname);
		
		$urlSeguridadModule = 'http://' . $_SERVER['SERVER_NAME'] . $register->config->dir_aplication . '/seguridad/';
		if (!isset($modulesConfig->security))
			$modulesConfig->addChild('security');
		if (isset($modulesConfig->security->uri))
			$modulesConfig->security->uri = $urlSeguridadModule . 'services.php';
		else $modulesConfig->security->addChild('uri', $urlSeguridadModule . 'services.php');
		if (isset($modulesConfig->security->location))
			$modulesConfig->security->location = $urlSeguridadModule . 'services.php';
		else $modulesConfig->security->addChild('location', $urlSeguridadModule . 'services.php');
		if (isset($modulesConfig->security->wsdl))
			$modulesConfig->security->wsdl = $urlSeguridadModule . 'services.wsdl';
		else $modulesConfig->security->addChild('wsdl', $urlSeguridadModule . 'services.wsdl');
		
		if (isset($nomConfig->conn['host']))
			$nomConfig->conn['host'] = $host;
		else $nomConfig->conn->addAttribute('host', $host);
		if (isset($nomConfig->conn['bd']))
			$nomConfig->conn['bd'] = $dbname;
		else $nomConfig->conn->addAttribute('bd', $dbname);
		
		$modulesConfig->asXML($dirModulesConfig);
		$nomConfig->asXML($dirNomConfig);
		$this->ActivarTrazas();
		unlink('../index.php');
		rename('../indexPACSOFT.php','../index.php');
		return true;
	}
		function ActivarTrazas()
	{
		$register = Zend_Registry::getInstance ();
		$diraspect= $register->config->xml->aspect;
		$xmlaspect= new SimpleXMLElement ( $diraspect, null, true );			
		if ($xmlaspect->beginTraceAction ['active'] =='false')
		$xmlaspect->beginTraceAction ['active']='true';
		if ($xmlaspect->endTraceAction ['active'] =='false')
		$xmlaspect->endTraceAction ['active']='true';
		if ($xmlaspect->failedTraceAction ['active'] =='false')
		$xmlaspect->failedTraceAction ['active']='true';
		if ($xmlaspect->beginTraceIoC ['active'] =='false')
		$xmlaspect->beginTraceIoC ['active']='true';
		if ($xmlaspect->failedTraceIoC ['active'] =='false')
		$xmlaspect->failedTraceIoC ['active']='true';
		$xmlaspect->asXML ( $diraspect );
	}
	
	
}
