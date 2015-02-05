<?php
	//Direccion de la clase servicio
	$dir_index = __FILE__;
	//Direccion del fichero de configuracion
	$config_file = substr($dir_index, 0, strrpos($dir_index, 'apps')) . 'apps/comun/config.php';

	echo $config_file;
    $config_file = str_replace('aplicaciones', '../../apps', $config_file);
    $config_file = realpath($config_file);

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
		
		$config ['dir_index'] = $dir_index;
		//Se modifica la configuracion para el caso de los servicios web (Se sustituye service por index)
		$module_reference = substr($dir_index, strrpos($dir_index, 'aplicaciones') + strlen('aplicaciones/'), strrpos($dir_index, 'services.php') - strrpos($dir_index, 'aplicaciones') - strlen('aplicaciones/') - 1);
		$array_module = explode('/', $module_reference);
		$config ['module_reference'] = $module_reference;
		$config ['xml']['iocinter']  = $dirSistema . $array_module[0] . '/comun/recursos/xml/ioc.xml';
		
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
		$app = new ZendExt_App_Service();
		$app->init($config);
		
		
		echo 1111111;
	} 
