<?php
class CompilerModel extends ZendExt_Model{
    function  __construct() {
        parent::ZendExt_Model();
    }

    function CreateServicePackage($datos, $nuevo = true){
	//print_r($datos);die;
        //creando clase
        $class = new Zend_CodeGenerator_Php_Class();

        //adicionando herencia
        $class->setExtendedClass('ZendExt_Service_Abstract');

        //adicionando el constructor
        $constructor = new Zend_CodeGenerator_Php_Method();
        $constructor->setName('__construct');
        $constructor->setBody("parent::__construct();");
        $class->setMethod($constructor);

        //adicionando los metodos
        foreach($datos['clases'] as $i=>$clase)
        {
            $method = new Zend_CodeGenerator_Php_Method();
            $method->setName($datos['metodos'][$i]);
            $comentario = new Zend_CodeGenerator_Php_Docblock();
            $parametros = "(";
            $params=$datos['comentarios'][$i]->getTags('param');
            foreach ($params as $j=>$param)
            {
                if($j>0)$parametros.=", ";
                $parametros.=$param->getVariableName();
                $p = new Zend_CodeGenerator_Php_Parameter();
                $p->setName(str_ireplace("$", "", $param->getVariableName()));
                $method->setParameter($p);
                $comentario->setTag(array(
                'name'=> 'param',
                'description'=> $param->getType() ." ". $param->getVariableName()
                ));
            }
            $parametros .= ")";
            $method->setBody("parent::PreElementsInjector();\n".'$result = '.$clase.'::'.$datos['metodos'][$i].$parametros.";\nparent::PostElementsInjector();\n".'return $result;');
            $comentario->setTag(array(
                        'name'=> 'return',
                        'description'=> $datos['comentarios'][$i]->getTag('return')->getType()
            ));
            if($datos['comentarios'][$i]->getShortDescription())$comentario->setShortDescription($datos['comentarios'][$i]->getShortDescription());
            if($datos['comentarios'][$i]->getLongDescription())$comentario->setLongDescription($datos['comentarios'][$i]->getLongDescription());
            $method->setDocblock($comentario);
            $class->setMethod($method);
        }

        //adicionando comentario de la clase SOAP
        $docblock = new Zend_CodeGenerator_Php_Docblock(array(
                        'shortDescription' => $datos['nombre'].'SOAP',
                        'longDescription'  => $datos['descripcion'],
                        'tags'=>array(
                                    array(
                                        'name'        => 'service'
                                    ),
                                    array(
                                        'name'        => 'binding.soap'
                                    ),
                                    array(
                                        'name'        => 'Copyright',
                                        'description' => 'UCI'
                                    ),
                                    array(
                                        'name'        => 'Author',
                                        'description' => $datos['autor']
                                    ),
                                    array(
                                        'name'        => 'Version',
                                        'description' => $datos['version']
                                    )
                                )
        ));
        $class->setDocblock($docblock);
        //creando ficheros SOAP
        $class->setName($datos['nombre'].'SOAP');
        $file = new Zend_CodeGenerator_Php_File();
        $include = str_replace("aplicaciones/wsb/index", "comun/frameworks/ZendExt/Service/Abstract", $_SERVER['SCRIPT_FILENAME']);
        /*$file->setBody("include '".$include."';\n\n".$class->generate()."\n\ninclude 'SCA/SCA.php';\n?>");*/
        $register = Zend_Registry::getInstance();
        
        $file->setBody("include '".$include."';\ninclude_once('".$register->session->solutionconfig."');\n\n".$class->generate()."\n\ninclude 'SCA/SCA.php';\n?>");
        $url = $datos['url'].'/'.$datos['nombre'].'SOAP'.'.php';
        $dir = str_replace($_SERVER ['DOCUMENT_ROOT'], "http://".$_SERVER['SERVER_ADDR'], $url);
        $wsdl = $datos['url'].'/'.$datos['nombre'].'SOAP'.'.wsdl';
        file_put_contents($url, $file->generate());
        file_put_contents($wsdl, file_get_contents($dir.'?wsdl'));

        $salvaBD = array();
        $salvaBD['idsolucion'] = $datos['idsolution'];
        $salvaBD['nombrepackage'] = $datos['nombre'];
        $salvaBD['uri'] = str_replace(".php", ".wsdl", $dir);
        $salvaBD['metodos'] = array();
        
        foreach($datos['metodos'] as $i=>$met){
            $documentator = new DocumentatorModel();
            $descrip = $documentator->GetDocblock($datos['clases'][$i], $met)->getShortDescription();
            $salvaBD['metodos'][] = array('idestado'=>1,'nombremetodo'=>$met,'uri'=>$datos['paths'][$i]."/".$datos['clases'][$i]."/".$met, 'descripcion'=> $descrip);
        }
	if($nuevo)
	        DatPaqueteModel::AddPackage($salvaBD);
    }

    function DonwloadProxy($idproxy){
        
        $class = new Zend_CodeGenerator_Php_Class();
        $proxy = $this->BuscarProxy($idproxy);
        $solucion = $this->BuscarSolucion($idproxy);
        $class->setName("Proxy_".$proxy->nombre."_".$solucion->nombre);
        $proxyfachadas = $this->BuscarFachadas($idproxy);
        $metodos = array();
        foreach ($proxyfachadas as $proxyfachada){
            $fachada = Doctrine::getTable('DatFachada')->find ($proxyfachada['idfachada']);
            $servicio = Doctrine::getTable('DatServicio')->find ($fachada->idservicio);
            $uri = $servicio->uri;
            $metodoname = substr($uri,strripos ($uri,'/') + 1);
            $cad = substr($uri,0,strripos ($uri,'/'));
            $clasename = substr($cad,strripos ($cad,'/') + 1);
            $instrosp = new InstrospectorModel();
            $params = $instrosp->getParamsByClassAndMethod ($clasename, $metodoname);
            $parameters = array();
            $stringparam = "";
            foreach ($params as $param){
                $parameters[] = array('name'=>$param->param);
                $stringparam.= ", $".$param->param;
            }
            $paquete = Doctrine::getTable('DatPaquete')->find ($fachada->idpaquete);
			$body= '$stock_quote = SCA::getService('."'".$paquete->uri."');";
            $body.= PHP_EOL;
            $body.= '$a = $stock_quote->'."$servicio->nombre".'('.substr($stringparam,2).');';
			$body.= 'return $a;';
            $metodos[] = array('name'=>$servicio->nombre,'parameters'=>$parameters,
            'body'=>$body);
        }
        $class->setMethods($metodos);
        return $this->ExportarClase($class, "include 'SCA/SCA.php';".PHP_EOL);
    }
	
    private function ExportarClase($class, $header){
        $code = "<?php" . PHP_EOL;
		$code = $code. $header;
        $code = $code. $class->generate();
        $code = $code. "?>";
        return $code;
    }

    function BuscarProxy($idproxy){
        return Doctrine::getTable('DatProxy')->find ($idproxy);
    }

    function ProbarServicio($idfachada, $parametros)
	{
                //include 'SCA/SCA.php';
		$f = Doctrine::getTable('DatFachada')->find ($idfachada);
		$paquete = Doctrine::getTable('DatPaquete')->find ($f->idpaquete);
		$servicio = Doctrine::getTable('DatServicio')->find ($f->idservicio);
		$codigo = "<?php".PHP_EOL;
                $codigo.= "include 'SCA/SCA.php';".PHP_EOL;
		$codigo.= '$exito = true;'.PHP_EOL;
		$codigo.= "try{".PHP_EOL;
		$codigo.= '$stock_quote = SCA::getService('."'".$paquete->uri."');";
                $codigo.= PHP_EOL;
                $codigo.= '$a = $stock_quote->'."$servicio->nombre($parametros);";
                $codigo.= 'print_r($a);';
		$codigo.= PHP_EOL;
		$codigo.= "}".PHP_EOL;
		$codigo.= 'catch(Exception $ex){'.PHP_EOL;
		$codigo.= "print_r('ERROR!!!');".PHP_EOL;
                $codigo.= "print_r(".'$ex'.");".PHP_EOL;
		$codigo.= "}".PHP_EOL;
		$codigo.="?>";
		$file = fopen("file.php","w");
		fputs($file,$codigo);
		fclose($file);
                $url = explode("index.php", $_SERVER['HTTP_REFERER']);
                $url = $url[0]."file.php";
                $exito = file_get_contents($url);
		unlink('file.php');
		return $exito;
    }

    public function BuscarFachadas($idproxy){
        $proxyfachada =  new DatProxyfachada();
        $result = $proxyfachada->FindByProxy($idproxy);
        return $result;
    }
	
	public function BuscarProxyFachada($idfachada)
	{
		$proxyfachada =  new DatProxyfachada();
                $result = $proxyfachada->FindByFachada($idfachada);
                return $result;
	}
	
	function BuscarFachadasPaquete($idpaquete)
	{
		$fachada = new DatFachada();
		$result = $fachada->FindByPaquete($idpaquete);
		return $result;
	}

    function BuscarSolucion($idproxy){
        $fachada = $this->BuscarFachadas($idproxy);
        $fachada = Doctrine::getTable('DatFachada')->find ($fachada[0]['idfachada']);
        $paquete = Doctrine::getTable('DatPaquete')->find ($fachada->idpaquete);
        $solucion = Doctrine::getTable('DatSolucion')->find ($paquete->idsolucion);
        return $solucion;
    }

    function ResloverIncludePath($ruta){
        $ficheroConfiguracion=$ruta."/ws/include_path.php";
        if(!file_exists($ficheroConfiguracion)){
            $include_path = $this->CargarCarpetas($ruta,"");
            $cuerpo = "<?php\n\$include = get_include_path();\nset_include_path(\$include.PATH_SEPARATOR .'".$include_path."');\n?>";
            file_put_contents($ficheroConfiguracion, $cuerpo);
        }
        include_once($ficheroConfiguracion);
    }

    function CargarCarpetas($ruta, $include_path)
    {
        
        $d = dir($ruta);
        while (false !== ($entrada = $d->read()))
        {
            if(substr($entrada, 0, 1) == ".")
                continue;
            $entrada = $ruta . "/" . $entrada;
            if(is_dir($entrada))
            {
                $include_path .=$entrada . PATH_SEPARATOR;
                $include_path = $this->CargarCarpetas($entrada, $include_path);
            }
        }
        $d->close();
        return $include_path;
    }
	
	function AgregarMetodo($clase, $nombreMetodo, $idpaquete, $path)
	{
		$uri = $path.'/'.$clase.'/'.$nombreMetodo;
		$servicio = new DatServicio();
		$fachada = new DatFachada();
		$servicios = $servicio->GetDatServicebyURi($uri);
		if($servicios == null)
		{
			$servicio = new DatServicio();
			$servicio->idestado = 1;
			$servicio->nombre = $nombreMetodo;
			$servicio->uri = $uri;
			$servicio->descripcion = $service['descripcion'];
			$servicio->save();
		}
		else
		{
			$servicio->idservicio = $servicios[0]['idservicio'];
			$fachadas = $fachada->FindByPaqueteAndSercicio($idpaquete, $servicio->idservicio);
			if($fachadas != null)
			{
				return array();
			}
		}
		$fachada->idpaquete = $idpaquete;
		$fachada->idservicio = $servicio->idservicio;
		$fachada->save();
		return $fachada->FindByPaquete($idpaquete);
	}
	
	function EliminarMetodo($clase, $nombremetodo)
	{
	try{
		$class = Zend_CodeGenerator_Php_Class::fromReflection(new Zend_Reflection_Class($clase));}
		catch(Exception $ex)
		{
			print_r($ex);die;
		}
		$nueva = new Zend_CodeGenerator_Php_Class();
		//Agregar docblock
		if($class->getDocBlock() != null)
			$nueva->setDocBlock($class->getDocBlock());
		//Nombre
		$nueva->setName($class->getName());
		//Abstracta??
		if($class->isAbstract())
			$nueva->setAbstract(true);
		//Clases k extiende
		$nueva->setExtendedClass($class->getExtendedClass());
		//Interfaces k implementa
		$nueva->setImplementedInterfaces($class->getImplementedInterfaces());
		/*//Propiedades
		if($class->getProperties() != null)
			$nueva->setProperties($class->getProperties());*/
		//Metodos
		$metodos = $class->getMethods();
		$nuevos = array();
		foreach ($metodos as $metodo)
		{
			if($metodo->getName() != $nombremetodo)
				$nuevos[] = $metodo;
		}
		$nueva->setMethods($nuevos);
		return $nueva->generate();
	}
	
	function EliminarFachada($idfachada)
	{
		$fachada = Doctrine::getTable('DatFachada')->find ($idfachada);
		$datproxyfachada = new DatProxyFachada();
		$proxies = $datproxyfachada->FindByFachada($idfachada);
		if ($proxies == null)
		{
			$fachada->delete();
			$paquete = Doctrine::getTable('DatPaquete')->find ($fachada->idpaquete);
			$datfachada = new DatFachada();
			$fachadas = $datfachada->FindByPaquete($paquete->idpaquete);
			if($fachadas == null)
			{
				$solucion = Doctrine::getTable('DatSolucion')->find ($paquete->idsolucion);
				$arr = explode("/",$paquete->uri);
				$clase = $arr[count($arr)-1];
				$arr = explode(".",$clase);
				$clase = $arr[0];
				$path = $solucion->path."/ws/".$clase;
				unlink($path.".php");
				unlink($path.".wsdl");
				$paquete->delete();
				return "Todo eliminado";
			}
			return $fachadas;
		}
		
	}
}
?>
