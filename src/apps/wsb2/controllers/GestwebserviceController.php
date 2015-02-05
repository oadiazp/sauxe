<?php
    class GestwebserviceController extends ZendExt_Controller_Secure {
        /**
         *
         * @var DatatypeModel
         */
        private $_datatype;

        /**
         *
         * @var InstrospectorModel
         */
        private $_instrospector;

        /**
         *
         * @var GeneratorModel
         */
        private $_generator;

        /**
         *
         * @var  ServiceModel
         */
        private $_services;

        function init () {
            parent :: init();
            $this->_datatype = new DatatypeModel ();
            $this->_instrospector = new InstrospectorModel();
            $this->_generator = new GeneratorModel();
            $this->_services = new ServiceModel();
        }

        function gestwebserviceAction () {
            $this->render();
        }

        function loadiocAction (){
            $node = $this->_request->getPost('node');
            $register = Zend_Registry::getInstance();
            $IoC = ZendExt_FastResponse::getXML('ioc');
            $result = array();
            if($node){
                $children = $IoC->$node->children();
                $srcModule = (string) $IoC->{$node}['src'];
                foreach ($children as $item){
                    $child = array();
                    $child['id']=$item->getName();
                    $child['text']=$item->getName();
                    $child['clas']=(string)$item->inyector['clase'];
                    $child['method']=(string)$item->inyector['metodo'];
                    $child['leaf']= true;
                    $child['checked']= false;
                    $referenceService = (string) $item['reference'];
                    $separator = '';
                    if ($referenceService)
                        $separator = '/';
                    $child['src']= $srcModule . $separator . $referenceService;
                    $result[] = $child;
                }
            }
            else{
                $children = $IoC->children();
                foreach ($children as $item){
                    $child = array();
                    $child['id']=$item->getName();
                    $child['text']=$item->getName();
                    $child['leaf']= false;
                    $result[] = $child;
                }
            }
            echo json_encode($result);
        }
    
        function loadservicesAction(){
            $result = $this->_services->LoadAllServices();
            $IoC = ZendExt_FastResponse::getXML('ioc');

            foreach ($result as $i=>$service){
                $srcModule = (string) $IoC->{$service['subsystem']}['src'];
                $referenceService = (string) $IoC->{$service['subsystem']}->{$service['name']}['reference'];
                $separator = '';
                if ($referenceService)
                    $separator = '/';
                $this->integrator->setIncludePath($srcModule . $separator . $referenceService);
                $result[$i]['descripcion'] = $this->_instrospector->getDescriptionByClassAndMethod($service['clas'], $service['method']);
            }
            echo json_encode(array('datos'=>$result,'cantidad_filas'=>sizeof($result)));
        }

        function loadpackagesAction(){
            $result->data  = $this->_services->LoadAllPackage();
            echo json_encode($result);
        }

        function loaddocblockAction(){
            $class = $this->_request->getPost('clas');
            $method = $this->_request->getPost('method');
            $src = $this->_request->getPost('src');
            $this->integrator->setIncludePath($src);
            $response = array();
            $response['result']= $this->_instrospector->getReturnByClassAndMethod($class, $method);
            $response['description']= $this->_instrospector->getDescriptionByClassAndMethod($class, $method);
            $response['params']= array();
            $parameters = $this->_instrospector->getParamsByClassAndMethod($class, $method);
            foreach ($parameters as $param){
                $response['params'][]=array('param'=>$param->param,'type'=>$param->type,'description'=>$param->description);
            }
            echo json_encode($response);
        }

        function modifydocblockAction(){
            $class = $this->_request->getPost('clas');
            $method = $this->_request->getPost('method');
            $src = $this->_request->getPost('src');
            $params = json_decode(str_replace("\\", '', $this->_request->getPost('params')));
            $data = json_decode(str_replace("\\",'',$this->_request->getPost('data')));
            $docblock = new stdClass();
            $docblock->result = $data->result;
            $docblock->description = $data->description;
            $docblock->params = $params;
            $this->integrator->setIncludePath($src);
            $path = $this->_instrospector->getFilebyClass($class);
            if($this->_generator->GenerateDocblock($path, $class, $method, $docblock)){
                echo json_encode(array("codigo"=>"1"));
            }
            else{
                echo json_encode(array("codigo"=>"2"));
            }
        }

        function generatepackageAction(){
            //captura de datos enviados desde la vista
            $selection = $this->_request->getPost('selection');
            $selection = str_replace("\\",'',$selection);
            $selection = json_decode($selection);
            $data = $this->_request->getPost('data');
            $data = json_decode(str_replace("\\",'',$data));
            //estructuración de los datos
            $package = new stdClass();
            $package->name = $data->name;
            $package->author = $data->author;
            $package->description = $data->description;
            $package->uri = "/ws/".$package->name.".php";
            $package->services = array();
            foreach ($selection[0] as $i=>$service){
                 $item = new stdClass();
                 $item->name = $service;
                 $item->subsystem = $selection[1][$i];
                 $item->class = $selection[2][$i];
                 $item->method = $selection[3][$i];
                 $item->src = $selection[4][$i];
                 $package->services[]=$item;
            }
            //extrayendo datos de los comentarios
            foreach($package->services as $service){
                $this->integrator->setIncludePath($service->src);
                $params = $this->_instrospector->getParamsByClassAndMethod($service->class, $service->method);
                $service->params = array();
                foreach ($params as $param){
                    $service->params[$param->param] = $param->type;
                }
                $service->result = $this->_instrospector->getReturnByClassAndMethod($service->class, $service->method);
            }
            //verificando errores en los comentarios
            $errors = array();
            $include = array();
            foreach($package->services as $service){
                foreach ($service->params as $paramname=>$paramtype){
                    if(!$paramtype){
                        $message= "El método <b>'$service->method'</b> de la clase <b>'$service->class'</b> del componente <b>'$service->subsystem'</b> no tiene especificado el tipo de dato del parámetro <b>'$paramname'</b>.";
                        $errors[]=array("type"=>"<b>Tipo de parámetro indefinido</b>","message"=>$message,"src"=>$service->src,"clas"=>$service->class,"method"=>$service->method);
                    }
                    else if(!$this->_datatype->exists($paramtype)){
                        $message= "El método <b>'$service->method'</b> de la clase <b>'$service->class'</b> del componente <b>'$service->subsystem'</b> tiene especificado un tipo de dato inválido para el parámetro <b>'$paramname'</b>.";
                        $errors[]=array("type"=>"<b>Tipo de parámetro incorrecto</b>","message"=>$message,"src"=>$service->src,"clas"=>$service->class,"method"=>$service->method);
                    }
                    else{
                        $datatype = DatatypeFactory::parse($paramtype);
                        if(!$datatype->getPrimary() && !isset($include[$paramtype])){
                            $include[$paramtype] = "datatypes/$paramtype.php";
                        }
                    }
                }
                if(!$service->result){
                    $message= "El método <b>'$service->method'</b> de la clase <b>'$service->class'</b> del componente <b>'$service->subsystem'</b> no tiene especificado el tipo de dato retornado.";
                    $errors[]=array("type"=>"<b>Tipo de retorno indefinido</b>","message"=>$message,"src"=>$service->src,"clas"=>$service->class,"method"=>$service->method);
                }
                else if(!$this->_datatype->exists($service->result)){
                    $message= "El método <b>'$service->method'</b> de la clase <b>'$service->class'</b> del componente <b>'$service->subsystem'</b> tiene especificado un tipo de dato retornado inválido.";
                    $errors[]=array("type"=>"<b>Tipo de parámetro incorrecto</b>","message"=>$message,"src"=>$service->src,"clas"=>$service->class,"method"=>$service->method);

                }
                else{
                    $datatype = DatatypeFactory::parse($service->result);
                    if(!$datatype->getPrimary() && !isset($include[$service->result])){
                        $include[$service->result] = "datatypes/$service->result.php";
                    }
                }
            }
            if(count($errors)){
                //envio de respuesta con errores
		echo json_encode(array("codigo"=>"2","errors"=>$errors));
		return;
            }

            //agregando el includepath
            $package->includepath = $include;

            //generación del paquete
            $this->_generator->GeneratePackage($package);

            //actualizacion del modelo de datos
            $this->_services->AddPackage($package);
            //envio de respuesta exitosa
            echo json_encode(array("codigo"=>"1"));
        }
    
        function addserviceAction(){
            //estructuración de los datos
            $package = $this->_request->getPost('pack');
            $service = new stdClass();
            $service->name = $this->_request->getPost('service');
            $service->subsystem = $this->_request->getPost('subsystem');
            $service->class = $this->_request->getPost('clas');
            $service->method = $this->_request->getPost('method');
            $service->src = $this->_request->getPost('src');
            //extrayendo datos de los comentarios
            $this->integrator->setIncludePath($service->src);
            $params = $this->_instrospector->getParamsByClassAndMethod($service->class, $service->method);
            $service->params = array();
            foreach ($params as $param){
                $service->params[$param->param] = $param->type;
            }
            $service->result = $this->_instrospector->getReturnByClassAndMethod($service->class, $service->method);
            
            //verificando errores en los comentarios
            $errors = array();
            $include = array();
            
            foreach ($service->params as $paramname=>$paramtype){
                if(!$paramtype){
                    $message= "El método <b>'$service->method'</b> de la clase <b>'$service->class'</b> del componente <b>'$service->subsystem'</b> no tiene especificado el tipo de dato del parámetro <b>'$paramname'</b>.";
                    $errors[]=array("type"=>"<b>Tipo de parámetro indefinido</b>","message"=>$message,"src"=>$service->src,"clas"=>$service->class,"method"=>$service->method);
                }
                else if(!$this->_datatype->exists($paramtype)){
                    $message= "El método <b>'$service->method'</b> de la clase <b>'$service->class'</b> del componente <b>'$service->subsystem'</b> tiene especificado un tipo de dato inválido para el parámetro <b>'$paramname'</b>.";
                    $errors[]=array("type"=>"<b>Tipo de parámetro incorrecto</b>","message"=>$message,"src"=>$service->src,"clas"=>$service->class,"method"=>$service->method);
                }
            }
            if(!$service->result){
                $message= "El método <b>'$service->method'</b> de la clase <b>'$service->class'</b> del componente <b>'$service->subsystem'</b> no tiene especificado el tipo de dato retornado.";
                $errors[]=array("type"=>"<b>Tipo de retorno indefinido</b>","message"=>$message,"src"=>$service->src,"clas"=>$service->class,"method"=>$service->method);
            }
            else if(!$this->_datatype->exists($service->result)){
                $message= "El método <b>'$service->method'</b> de la clase <b>'$service->class'</b> del componente <b>'$service->subsystem'</b> tiene especificado un tipo de dato retornado inválido.";
                $errors[]=array("type"=>"<b>Tipo de parámetro incorrecto</b>","message"=>$message,"src"=>$service->src,"clas"=>$service->class,"method"=>$service->method);

            }
            if(count($errors)){
                //envio de respuesta con errores
		echo json_encode(array("codigo"=>"2","errors"=>$errors));
		return;
            }
            
            //actualizacion del modelo de datos
            $this->_services->AddService($service, $package);
            //reconstruccion de paquete
            $pack = $this->_services->LoadPackage($package);
            $include = array();
            foreach($pack->services as $service){
                $datatype = DatatypeFactory::parse($service->result);
                if(!$datatype->getPrimary() && !isset($include[$service->result])){
                    $include[$service->result] = "datatypes/$service->result.php";
                }
                foreach ($service->params as $paramname=>$paramtype){
                    $datatype = DatatypeFactory::parse($paramtype);
                    if(!$datatype->getPrimary() && !isset($include[$paramtype])){
                        $include[$paramtype] = "datatypes/$paramtype.php";
                    }
                }
            }
            $pack->includepath = $include;
            $this->_generator->GeneratePackage($pack);
            //envio de respuesta
            echo json_encode(array("codigo"=>"1"));
        }

        function removeserviceAction(){
            //captura de datos enviados desde la vista
            $service = $this->_request->getPost('service');
            $package = $this->_request->getPost('pack');
            //actualizacion del modelo de datos
            $this->_services->RemoveService($service, $package);
            //reconstruccion de paquete
            $pack = $this->_services->LoadPackage($package);
            if(count($pack->services)){
                $include = array();
                foreach($pack->services as $service){
                    $datatype = DatatypeFactory::parse($service->result);
                    if(!$datatype->getPrimary() && !isset($include[$service->result])){
                        $include[$service->result] = "datatypes/$service->result.php";
                    }
                    foreach ($service->params as $paramname=>$paramtype){
                        $datatype = DatatypeFactory::parse($paramtype);
                        if(!$datatype->getPrimary() && !isset($include[$paramtype])){
                            $include[$paramtype] = "datatypes/$paramtype.php";
                        }
                    }
                }
                $pack->includepath = $include;
                $this->_generator->GeneratePackage($pack);
            }
            else{
                $this->_services->RemovePackage($package);
                unlink("../ws/".$package.".php");
            }
            //envio del respuesta
            echo json_encode(array("codigo"=>"1"));
        }

        function removepackageAction(){
            //captura de datos enviados desde la vista
            $package = $this->_request->getPost('pack');

            //eliminacion del fichero
            $dir="../ws/".$package.".php";
            unlink($dir);
            
            //actualizacion del modelo de datos
            $this->_services->RemovePackage( $package);
            //envio del respuesta
            echo json_encode(array("codigo"=>"1"));
        }

        function getdatatypesAction(){
            $result = array ();

            $datatypes = array_values (DatatypeFactory::getHash());
            
            foreach ($datatypes as $datatype) {
                $dt=$datatype->toArray();
                $result[] = array('name'=>$dt->name);
                $result[] = array('name'=>$dt->name."[]");
                $dt = null;
            }

            $json->datatypes = $result;
            echo json_encode($json);
        }
    }
?>
