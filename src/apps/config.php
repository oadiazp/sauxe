<?php
/**
 * Fichero de configuracion del Marco de Trabajo
 * 
 * @author Yoandry Morejon Borbon
* @copyright UCID-ERP Cuba
 * @version 1.1-0
 */

	//Direccion de la carpeta de publicacion
	$dir_www = $_SERVER['DOCUMENT_ROOT'];

	//Direccion del Marco de trabajo relativa a la carpeta de publicacion.
	$dir_rel_mt = '/';

	//Direccion absoluta del marco de trabajo
	$dir_abs_mt = str_replace('//','/', dirname($dir_www . '..') . '/' . $dir_rel_mt);
	
	//Variable que contiene la configuracion general del Marco de Trabajo.
	$config = array();
	
	//Direccion del modulo en ejecucion.
	$dir_modulo = substr($_SERVER['SCRIPT_FILENAME'], 0, strrpos($_SERVER['SCRIPT_FILENAME'], '/') + 1);
	$dir_modulo = str_replace('web','apps', $dir_modulo);
	$config['modulo_path'] = $dir_modulo;
				
	//Nombre del Modulo
	$arr_module_name = explode('/', $dir_modulo);
	$config['module_name'] = $arr_module_name[count($arr_module_name) - 2];
	
	//Include_Path de PHP con la direccion de los frameworks y los modelos de los modulos.
	$config['include_path'] = '.' 	. PATH_SEPARATOR . $dir_abs_mt . 'lib/'
					. PATH_SEPARATOR . $dir_abs_mt . 'lib/Doctrine'
				
					. PATH_SEPARATOR . $dir_modulo . 'models/bussines'
					. PATH_SEPARATOR . $dir_modulo . 'models/domain'
					. PATH_SEPARATOR . $dir_modulo . 'models/domain/generated'
					. PATH_SEPARATOR . $dir_modulo . 'validators'
					. PATH_SEPARATOR . $dir_modulo . 'services'

					. PATH_SEPARATOR . $dir_abs_mt . 'lib/ZendExt/Trace/domain'
					. PATH_SEPARATOR . $dir_abs_mt . 'lib/ZendExt/Trace/domain/generated';			  
	
	//Configuracion de los xml
	$config ['xml']['expresiones'] 		= $dir_abs_mt . 'config/xml/expressions.xml';
	$config ['xml']['tipos_excepciones']= $dir_abs_mt . 'config/xml/tipos_excepciones.xml';
	$config ['xml']['aspect'] 			= $dir_abs_mt . 'config/xml/aspect.xml';
	$config ['xml']['aspecttemplate'] 	= $dir_abs_mt . 'config/xml/aspecttemplate.xml';
	$config ['xml']['aspecttemplatemt']	= $dir_abs_mt . 'config/xml/aspecttemplatemt.xml';
	$config ['xml']['log']				= $dir_abs_mt . 'config/xml/log.xml';
	$config ['xml']['traza']			= $dir_abs_mt . 'config/xml/CategoriaTipo.xml';
	$config ['xml']['concepts']			= $dir_abs_mt . 'config/xml/concepts.xml';
	$config ['xml']['traceconfig']		= $dir_abs_mt . 'config/xml/traceconfig.xml';
	$config ['xml']['xmpp']				= $dir_abs_mt . 'config/xml/xmpp.xml';
	
	//Fichero log de excepciones
	$config ['exception_log_file'] = $dir_abs_mt . 'log/exception.log';
	$config ['session_save_path'] = $dir_abs_mt . 'session/';
	
	//Configuracion de ZendExt Cache
	$config['cache']['frontend']			= 'Core'; //Cachear instancias de clases
	$config['cache']['backend'] 			= 'File'; //Cachear en ficheros
	$config['cache']['lifetime'] 		        = 7200; //Tiempo de Vida
	$config['cache']['automatic_serialization']	= true; //Serializar
	$config['cache']['cache_dir'] 		    	= $dir_abs_mt . 'cache/'; //Directorio de cache
	$config['cache']['chmod']			= 0644; //Directorio de cache
	
	//Comprobación de lectura escritura del usuario apache
	function checkDirectories ($dir, & $is_writable) {
		if (!is_writable($dir)) {
			$is_writable = false;
			echo '<pre><b>Fatal error</b>: Apache no tiene permisos para escribir en la carpeta ' . $dir;
		}
	}

    function debug ($pVar, $pEnd = true) {
       echo '<pre>';
       print_r($pVar);
            
       if ($pEnd)
       	die;
    }

	
	$is_writable = true;
	checkDirectories($dir_abs_mt . 'log/', $is_writable);
	checkDirectories($dir_abs_mt . 'session/', $is_writable);
	checkDirectories($dir_abs_mt . 'cache/', $is_writable);
	if (!$is_writable) {
		exit();
	}
	
	//Nombre de la carpeta que contiene los Controladores dentro de los modulos.
	$config['controllers_path'] = $dir_modulo . 'controllers';

	//Direccion del Framework de Presentacion EXTJS relativa a la carpeta de publicacion.
	$config['extjs_path']			= $dir_rel_mt . 'lib/ExtJS/';
	$config['extjs_themes_path']		= $dir_rel_mt . 'lib/ExtJS/temas/';
	$config['idioma']['es']['extjs_path']	= $dir_rel_mt . 'lib/ExtJS/idioma/es/';
	$config['idioma']['en']['extjs_path']	= $dir_rel_mt . 'lib/ExtJS/idioma/en/';

	//Direccion del Framework UCID relativa a la carpeta de publicacion.
	$config['ucid_path'] 	 = $dir_rel_mt . 'lib/UCID/';
	$config['schema_reglas'] = 'mod_arquitectura';
