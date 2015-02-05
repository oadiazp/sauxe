<?php
/*
 *Componente frontal de la aplicaci�n.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garcia Tejo
 * @author Julio Cesar Garc�a Mosquera  
 * @version 1.0-0
 */
	//Direccion de la servidora
	$dir_index = $_SERVER['SCRIPT_FILENAME'];
	//Direccion del fichero de configuracion
	$config_file = substr($dir_index, 0, strrpos($dir_index, 'web')) . 'apps/comun/config.php';	
	if (!file_exists($config_file)) //Si no existe el fichero de configuracion
	{
		//Se dispara una excepcion
		throw new Exception('El fichero de configuracion no existe');
	}
	elseif (!is_readable($config_file)) //Si no se puede leer
	{
		//Se dispara una excepcion
		throw new Exception('No se pudo leer el fichero de configuracion. Acceso denegado.');
	}
	else //Si existe el fichero y se puede leer
	{
		//Se incluye el fichero
		include_once ($config_file);
		if (!isset($config['include_path']))
			throw new Exception('El framework no esta configurado correctamente.');
		//Se inicializa el include path de php a partir de la variable de configuracion
		set_include_path($config['include_path']);
		
		//Se inicia la carga automatica de clases y ficheros
		$loader_file = 'Zend/Loader.php';
		if (!@include_once($loader_file))
			throw new Exception('El framework no esta configurado correctamente.');
		Zend_Loader::registerAutoload();
		
		//Se inicia la aplicacion
		$app = new ZendExt_App();
		$app->initInstalacion($config);
	}
