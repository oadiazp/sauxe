<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CompilerController
 *
 * @author WSB team
 */
    class CompilerController extends ZendExt_Controller_Secure {

        function compilerAction () {
            $this->render ();
            //$this->setSolution("/var/www/repo/wsb");
        }

        function compileAction () {
            $register = Zend_Registry::getInstance();
            include_once($register->session->solutionconfig);
            
            $seleccion = $this->_request->getPost('seleccion');
            $seleccion = str_replace("\\",'',$seleccion);
            $seleccion = json_decode($seleccion);
            $necesarios = $this->_request->getPost('necesarios');
            $necesarios = json_decode(str_replace("\\",'',$necesarios));

            $datos = array();
            $datos['url']=$register->session->solutionpath ."/ws";
            $datos['nombre']=$necesarios->nombre;
            $datos['autor']=$necesarios->autor;
            //$datos['version']=$opcionales->version;
            $datos['descripcion']=$necesarios->descripcion;
            $datos['clases']=$seleccion[0];
            $datos['metodos']=$seleccion[1];
            $datos['paths']=$seleccion[2];
            $datos['comentarios'] =array();
            $datos['idsolution'] = $register->session->solutionid;
            $errores = array();
            $documentator= new DocumentatorModel();
            $compiler = new CompilerModel();
            try{
                foreach($datos['metodos'] as $i=>$method)
                {
                    $coment = $documentator->GetDocblock($datos['clases'][$i], $method);
                    if($coment)
                    {
                        if($coment->getTag('return')){
                            $datos['comentarios'][] = $coment;
                        }
                        else{
                            $error = array();
                            $error['clase'] = $datos['clases'][$i];
                            $error['metodo'] = $datos['metodos'][$i];
                            $error['path'] = $datos['paths'][$i];
                            $error['tipo'] = "tipo de retorno desconocido";
                            $error['descripcion'] = "El método ".$datos['metodos'][$i]." de la clase ".$datos['clases'][$i]." no tiene especificado el tipo de retorno.";
                            $errores[]=$error;
                            unset( $datos['clases'][$i]);
                            unset( $datos['metodos'][$i]);
                            unset( $datos['paths'][$i]);
                        }
                    }
                    else
                    {
                        $error = array();
                        $error['clase'] = $datos['clases'][$i];
                        $error['metodo'] = $datos['metodos'][$i];
                        $error['path'] = $datos['paths'][$i];
                        $error['tipo'] = "comentario no encontrado";
                        $error['descripcion'] = "El método ".$datos['metodos'][$i]." de la clase ".$datos['clases'][$i]." no tiene comentario.";
                        $errores[]=$error;
                        unset( $datos['clases'][$i]);
                        unset( $datos['metodos'][$i]);
                        unset( $datos['paths'][$i]);
                    }
            }
                if(!$errores){
                    $compiler->CreateServicePackage($datos);
                    echo json_encode(array("codigo"=>"1"));
                }
                else
                {
                    echo json_encode(array("codigo"=>"2","cantidad_filas"=>sizeof($errores),"datos"=>$errores));
                }
            }
            catch(Exception $ee){
                echo json_encode(array("codigo"=>"3"));
            }
        }

        function addserviceAction(){
                $register = Zend_Registry::getInstance();
                include_once($register->session->solutionconfig);
                $idpaquete = $this->_request->getPost ('idpaquete');
                $metodo = $this->_request->getPost ('metodo');
                $clase = $this->_request->getPost ('clase');
                $path = $this->_request->getPost ('path');
                $model = new CompilerModel();
                $fachadas = $model->AgregarMetodo($clase,$metodo,$idpaquete,$path);
                if($fachadas == null)
                {
                        echo json_encode(array("codigo"=>"2","message"=>"El servicio no pudo ser agregado."));
                        return;
                }
                else
                {
                        $paquete = Doctrine::getTable('DatPaquete')->find ($idpaquete);
                        $datos = array();
                        $datos['url']=$register->session->solutionpath ."/ws";
                        $datos['nombre']=$paquete->nombre;
                        $datos['autor']=$paquete->autor;
                        $datos['descripcion']=$paquete->descripcion;
                        foreach ($fachadas as $fachada)
                        {

                                $servicio = Doctrine::getTable('DatServicio')->find ($fachada['idservicio']);
                                $arr = explode("/",$servicio->uri);
                                $claseName = $arr[count($arr)-2];
                                $datos['clases'][] = $claseName;
                                $metodoName = $arr[count($arr)-1];
                                $datos['metodos'][] = $metodoName;
                                $uriNueva = $servicio->uri;
                                $arr = explode(".php",$uriNueva);
                                $uriNueva = $arr[0].".php";
                                $datos['paths'][] = $uriNueva;
                        }
                        $model = new CompilerModel();
                        $documentator= new DocumentatorModel();
            $compiler = new CompilerModel();
                        foreach($datos['metodos'] as $i=>$method)
                        {
                                $coment = $documentator->GetDocblock($datos['clases'][$i], $method);
                                if($coment)
                                        if($coment->getTag('return'))
                                                $datos['comentarios'][] = $coment;
                        }
                        $model->CreateServicePackage($datos, false);
                }
                echo json_encode(array("codigo"=>"1","message"=>"El servicio ".$metodo." de la clase ".$clase." fue agregado al paquete ".$paquete->nombre."."));

    }

        function removeservicesAction(){

    $register = Zend_Registry::getInstance();
    include_once($register->session->solutionconfig);
                $mensaje = "";
    $seleccion = $this->_request->getPost('seleccion');
    $seleccion = str_replace("\\",'',$seleccion);
    $seleccion = json_decode($seleccion);
    $model = new CompilerModel();
    $proxies = array();
    $no_eliminar = array();
    $eliminar = array();
    foreach ($seleccion as $fachada)
    {
                        $proxy = $model->BuscarProxyFachada($fachada);
                        if(empty($proxy))
                                $eliminar[] = $fachada;
                        else
                                $no_eliminar[] = $fachada;
    }
    foreach ($eliminar as $fach)
    {
                        $datos = array();
                        $fachada = Doctrine::getTable('DatFachada')->find ($fach);
                        $paquete = Doctrine::getTable('DatPaquete')->find ($fachada->idpaquete);
                        $datos['url']=$register->session->solutionpath ."/ws";
                        $datos['nombre']=$paquete->nombre;
                        $datos['autor']=$paquete->autor;
                        $datos['descripcion']=$paquete->descripcion;
                        $datfachada = new DatFachada();
                        $fachadas = $datfachada->FindByPaquete($paquete->idpaquete);
                        foreach ($fachadas as $facha)
                        {
                                if($facha['idfachada'] == $fach)
                                        continue;
                                $servicio = Doctrine::getTable('DatServicio')->find ($facha['idservicio']);
                                $arr = explode("/",$servicio->uri);
                                $clase = $arr[count($arr)-2];
                                $datos['clases'][] = $clase;
                                $metodoName = $arr[count($arr)-1];
                                $datos['metodos'][] = $metodoName;
                                $uriNueva = $servicio->uri;
                                $arr = explode(".php",$uriNueva);
                                $uriNueva = $arr[0].".php";
                                $datos['paths'][] = $uriNueva;
                        }
                        $documentator= new DocumentatorModel();
                        $compiler = new CompilerModel();
                        $noError = true;
                        if($datos['metodos'])
                        {
                                foreach($datos['metodos'] as $i=>$method)
                                {
                                        $coment = $documentator->GetDocblock($datos['clases'][$i], $method);
                                        if($coment)
                                                if($coment->getTag('return'))
                                                {
                                                        $datos['comentarios'][] = $coment;
                                                        $eliminados[] = $method;
                                                }
                                                else
                                                        $noError = false;
                                        else
                                                $noError = false;
                                }
                                if($noError)
                                {
                                        $model->CreateServicePackage($datos, false);
                                        $model->EliminarFachada($fach);
                                }
                                else
                                {
                                        $no_eliminar[] = $fach;
                                }
                        }
                        else
                        {
                                $lastMensaje.= "<br/>El paquete $paquete->nombre ha sido eliminado.";
                                $model->EliminarFachada($fach);
                                $paquete->delete();
                        }
    }
                if(count($no_eliminar) > 0)
                {
                        $mensaje.= "Servicios no eliminados: ";
                        foreach ($no_eliminar as $fach)
                        {
                                $fachada = Doctrine::getTable('DatFachada')->find ($fach);
                                $servicio = Doctrine::getTable('DatServicio')->find ($fachada->idservicio);
                                $arr = explode("/",$servicio->uri);
                                $metodoName = $arr[count($arr)-1];
                                $mensaje.= "|$metodoName|";
                        }
                        if(count($eliminar) > 0)
                                $mensaje.= "<br/>El resto de los servicios fueron eliminados.";
                }
                else
                        $mensaje = "Servicios eliminados satisfactoriamente";
    $this->showMessage ($mensaje.$lastMensaje);
}
        
        function setsolutionAction(){
            $register = Zend_Registry::getInstance();
            $solucion = Doctrine::getTable('DatSolucion')->find ($this->_request->getPost ('idsolucion'));
            $register->session->solutionpath = $solucion->path;
            $register->session->solutionid = $this->_request->getPost ('idsolucion');
            $register->session->solutionconfig = $solucion->config;
            if(!is_dir($solucion->path."/ws"))
                mkdir($solucion->path."/ws");
            echo '{}';
        }
        
        function loadcomentarioAction(){
            $register = Zend_Registry::getInstance();
            include_once($register->session->solutionconfig);
            
            $path = $this->_request->getPost('path');
            $class = $this->_request->getPost('clase');
            $method = $this->_request->getPost('metodo');

            $documentator = new DocumentatorModel();
            $instrospector = new InstrospectorModel();
            try{
                $docblock= $documentator->GetDocblock($class, $method);
                $descripcion = "no tiene descripción";
                $retorno = "<undefined>";

                if($docblock){
                    if($docblock->getShortDescription()){
                        $descripcion = $docblock->getShortDescription();
                    }
                    if($docblock->getTag('return')){
                        $retorno = $docblock->getTag('return')->getType();
                    }
                 }
                $response = array();
                $params = $instrospector->getParamsByClassAndMethod($class, $method);
                foreach ($params as $i=>$param){
                    $p = array();
                    $p["param"] = $param->param;
                    $p["tipo"] = $param->type;
                    $response[]= $p;
                }
                echo json_encode(array('datos'=>$response,'cantidad_filas'=>sizeof($response), 'retorno'=>$retorno, 'descripcion'=>$descripcion));
            }
            catch (Exception $ee){
               
                $this->showMessage('Existen dependencias no resueltas en la solución.');
            }
        }

        function setcomentarioAction(){
            $register = Zend_Registry::getInstance();
            $compiler = new CompilerModel();
            $compiler->ResloverIncludePath($register->session->solutionpath );

            $seleccion = $this->_request->getPost('datos');
            $seleccion = str_replace("\\",'',$seleccion);
            $seleccion = json_decode($seleccion);

            $clase = $this->_request->getPost('clase');
            $metodo = $this->_request->getPost('metodo');
            $datos = array();
            $datos['retorno'] = $this->_request->getPost('retorno');
            if($this->_request->getPost('descripcion') != "no tiene descripción")
                $datos['descripcion'] = $this->_request->getPost('descripcion');
            $datos['tags'] = array();
            foreach($seleccion as $i=>$param){
                if($param->tipodato != "<undefined>"){
                    $item = array();
                    $item['nombre'] = $param->parametro;
                    $item['tipo'] = $param->tipodato;
                    $datos['tags'][] = $item;
                }
            }
            
            $documentator = new DocumentatorModel();
            $var = $documentator->SetDocblock($clase, $metodo, $datos);
            $code = "<?php" . PHP_EOL;
            $code = $code. $var;
            $code = $code. "?>";
            $file = fopen($this->_request->getPost('path'),"w");
            fputs($file,$code);
            echo "{}";
        }

        function loadtreeAction (){
            $result = null;
            $register = Zend_Registry::getInstance();

            $_POST ['path'] = ($_POST ['node'] == 'root') ? $register->session->solutionpath  : $_POST ['path'];


            switch ($this->_request->getPost ('type')) {
                case 'folder': {
                    $result = InstrospectorModel::getFilesByPath($this->_request->getPost('path'),
                                                                 $this->_request->getPost('only_folders'));
                    break;
                }
                case 'file': {
                    $result = InstrospectorModel::getClassesByFile($this->_request->getPost ('path'));
                    break;
                }
                case 'class': {
                    $result = InstrospectorModel::getMethodsByClass($this->_request->getPost ('checkeable'),
                                                                    $this->_request->getPost ('text'),
                                                                    $this->_request->getPost ('path'));
                    break;
                }
            }

            echo json_encode($result);
        }
    
        function loadservicesAction(){
            $register = Zend_Registry::getInstance();
            $model = new DatPaquete();
            $result = $model->LoadPackagesbyIdsolucion($register->session->solutionid);
            $response = array();
            foreach($result as $i=>$pack){
                foreach($pack['DatFachada'] as $j=>$serv){
                    $item = array();
                    $item['idfachada'] = $serv['idfachada'];
                    $item['idpaquete'] = $pack['idpaquete'] ;
                    $item['nombrepaquete'] = $pack['nombre'];
                    $item['idservicio'] = $serv['idservicio'];
                    $item['nombreservicio'] = $serv['DatServicio']['nombre'];
                    $item['uri'] = $pack['uri'];
                    $item['descripcion'] = $serv['DatServicio']['descripcion'];
                    $item['estado'] = $serv['DatServicio']['NomEstado']['nombre'];
                    $response[] = $item;
                }
            }

            echo json_encode(array('datos'=>$response,'cantidad_filas'=>sizeof($response)));
        }

        function loadproxiesAction(){
            $register = Zend_Registry::getInstance();
            $model = new DatProxy();
            $result = $model->LoadProxiesByIdSolucion($register->session->solutionid);
            //print_r($result);die;
            $proxies = array();
            foreach($result as $i=>$proxy){
                $item = array();
                $item['idproxy']= $proxy['idproxy'];
                $item['nombre']= $proxy['nombre'];
                $item['descripcion']= $proxy['descripcion'];
                $item['servicios'] = "";
                foreach($proxy['DatProxyfachada'] as $j=>$servi){
                    $item['servicios'].=$servi['DatFachada']['DatServicio']['nombre']."<br/> &nbsp; &nbsp; &nbsp;";
                }
                $proxies[] = $item;
            }
            echo json_encode(array('datos'=>$proxies,'cantidad_filas'=>sizeof($result)));
        }

        function createproxyAction(){
            $register = Zend_Registry::getInstance();
            $datos = $this->_request->getPost('datos');
            $datos = str_replace("\\",'',$datos);
            $datos = json_decode($datos);
            $servicios = $this->_request->getPost('seleccion');
            $servicios = str_replace("\\",'',$servicios);
            $servicios = json_decode($servicios);
            $model = new DatProxyModel();
            try{
                $model->AddProxy(array('services'=>$servicios, 'data'=>$datos, 'idsolution'=> $register->session->solutionid));
                echo json_encode(array("codigo"=>"1"));
            }
            catch(Exception $ee){
                print_r($ee);die;
                echo json_encode(array("codigo"=>"3"));
            }
        }

        function loadSolutionsAction () {
            $result->data = DatSolucionModel::getAll ();
            echo json_encode($result);
        }
        function loadpaquetesAction(){
            $register = Zend_Registry::getInstance();
            $idsolucion=$register->session->solutionid;
            $result->data = DatPaqueteModel::GetbyIdsolution($idsolucion);
            echo json_encode($result);
        }
        
        function loadinittreeAction (){
            $result = null;
            $register = Zend_Registry::getInstance();

            $_POST ['path'] = ($_POST ['node'] == 'root') ? $_SERVER ['DOCUMENT_ROOT'] : $_POST ['path'];


            switch ($this->_request->getPost ('type')) {
                case 'folder': {
                    $result = InstrospectorModel::getFilesByPath($this->_request->getPost('path'),
                                                                 $this->_request->getPost('only_folders'));
                    break;
                }
                case 'file': {
                    $result = InstrospectorModel::getClassesByFile($this->_request->getPost ('path'));
                    break;
                }
                case 'class': {
                    $result = InstrospectorModel::getMethodsByClass($this->_request->getPost ('checkeable'),
                                                                    $this->_request->getPost ('text'),
                                                                    $this->_request->getPost ('path'));
                    break;
                }
            }

            echo json_encode($result);
        }
        
        function addsolutionAction () {
            $new = new DatSolucion();
            $new->nombre = $this->_request->getPost ('solucion');
            $new->path = $this->_request->getPost ('path');
            $new->config = $this->_request->getPost ('path')."/ws/include_path.php";
            $prev_solution = DatSolucion::getSolutionByName ($this->_request->getPost ('solucion'));
            if ($prev_solution->toArray () == array ()) {
                $prev_solution = $new->getSolucionByPath($this->_request->getPost ('path'));
                if ($prev_solution == array ())
                {
                    $id = DatSolucionModel::Insertar ($new);
                    $register = Zend_Registry::getInstance();
                    $register->session->solutionpath = $this->_request->getPost ('path');
                    $register->session->solutionid = $id;

                    if(!is_dir($register->session->solutionpath ."/ws")){
                        mkdir($register->session->solutionpath ."/ws");
                    }
                    $compiler = new CompilerModel();
                    $compiler->ResloverIncludePath($register->session->solutionpath);
                    $register->session->solutionconfig = $register->session->solutionpath ."/ws/include_path.php";
                    echo json_encode(array("codigo"=>"1","message"=>"La solucion se ha creado correctamente."));
                }
                else echo json_encode(array("codigo"=>"2","message"=>"Ya existe una solucion en esa ruta."));
            } else echo json_encode(array("codigo"=>"2","message"=>"Ya existe una solucion con ese nombre."));
        }
    
        function downloadproxyAction(){
            $register = Zend_Registry::getInstance();
            include_once($register->session->solutionconfig);
            
            $seleccion = $this->_request->getPost('idproxies');
            $seleccion = str_replace("\\",'',$seleccion);
            $seleccion = json_decode($seleccion);
			$zip = new ZendExt_Zip();
			$model = new CompilerModel();
			foreach($seleccion as $idproxy)
			{
				$code = $model->DonwloadProxy($idproxy);
				$proxy = $model->BuscarProxy($idproxy);
				$solucion = $model->BuscarSolucion($idproxy);
				$name = 'Proxy_' . $proxy->nombre . '_' . $solucion->nombre . '.php';
				$zip->add_data($name, $code);
			}
			$data = $zip->download('proxies.zip');
        }

        function removeproxiesAction(){
            $seleccion = $this->_request->getPost('seleccion');
            $seleccion = str_replace("\\",'',$seleccion);
            $seleccion = json_decode($seleccion);
            foreach($seleccion as $i=>$idProxy){
                $proxy = DatProxy::getProxyById($idProxy);
                $proxy->Delete();
            }
            $this->showMessage ('Proxies eliminados correctamente.');
        }
		
        function removepackageAction(){
			$register = Zend_Registry::getInstance();
            include_once($register->session->solutionconfig);
			$idpaquete=$this->_request->getPost ('idpaquete');
			$paquete = Doctrine::getTable('DatPaquete')->find ($idpaquete);
			$model = new CompilerModel();
			$fachadas = $model->BuscarFachadasPaquete($idpaquete);
			$proxies = false;
			$eliminar = array();
			foreach ($fachadas as $fachada)
			{
				$proxy = $model->BuscarProxyFachada($fachada['idfachada']);
				if(!empty($proxy))
				{
					$proxies = true;
					break;
				}
				$eliminar[] = $fachada['idfachada'];
			}
			if($proxies == false)
			{
				foreach ($eliminar as $fach)
				{
					$model->EliminarFachada($fach);
				}
				echo json_encode(array("codigo"=>"1","message"=>"El paquete ".$paquete->nombre." fue eliminado."));
			}
			else
				echo json_encode(array("codigo"=>"2","message"=>"El paquete ".$paquete->nombre." no ha podido ser eliminado."));
        }
		
        function removesolutionAction(){
            $solucion = DatSolucion::getSolutionByName ($this->_request->getPost ('solution'));
            $a = $solucion->toArray();
            $model = new DatProxy();
            $proxies = $model->LoadProxiesByIdSolucion($a[0]['idsolucion']);
            foreach($proxies as $i=>$proxy){
                foreach($proxy['DatProxyfachada'] as $j=>$proxyfachada){
                    $elem = DatProxyfachada::getProxyfachadaById($proxyfachada['idproxyfachada']);
                    $elem->delete();
                }
                $p = DatProxy::getProxyById($proxy['idproxy']);
                $p->delete();
            }
            $sol = $solucion->toArray();
            if (is_dir($sol[0]['path'].'/ws')){
                $files = glob($sol[0]['path'].'/ws/*', GLOB_MARK );
                foreach( $files as $file ){
                    @unlink( $file );
                }
                @rmdir( $sol[0]['path'].'/ws' );
            }
            $solucion->delete ();
            $this->showMessage ('Solución eliminada correctamente.');
        }

        function loadparametrosAction(){
            $register = Zend_Registry::getInstance();
            include_once($register->session->solutionconfig);
            $idFachada = $this->_request->getPost ('idfachada');
            $fachada = new DatFachada();
            $servicio = $fachada->GetFachadabyId($idFachada);
            $uriServicio =$servicio[0]['DatServicio']['uri'];
            $nombreMetodo = substr($uriServicio,strripos ($uriServicio,'/') + 1);
            $uriServicio = substr($uriServicio,0,strripos ($uriServicio,'/'));
            $nombreClase = substr($uriServicio,strripos ($uriServicio,'/') + 1);
            $uriServicio = substr($uriServicio,0,strripos ($uriServicio,'/'));
            $introspector = new InstrospectorModel();
            $parametros = $introspector->getParamsByClassAndMethod($nombreClase,$nombreMetodo);
            $response = array();
            foreach ($parametros as $i=>$param){
                $elem = array();
                $elem["param"] = $param->param;
                $elem["tipo"] = $param->type;
                $response[] = $elem;
            }
            echo json_encode(array('datos'=>$response));
        }

        function probarservicioAction(){
            
            $model = new CompilerModel();
            $servicio = $this->_request->getPost ('idfachada');
            $parametros = $this->_request->getPost ('parametros');
            $parametros = str_replace("\\",'',$parametros);
            $result = $model->ProbarServicio($servicio,$parametros);
            $f = Doctrine::getTable('DatFachada')->find ($servicio);
            $servicio = Doctrine::getTable('DatServicio')->find ($f->idservicio);
            if(strlen($result)){
                if(substr($result, 0, 8) != 'ERROR!!!'){
                    $servicio->idestado = 3;
                    echo json_encode(array("codigo"=>"1","respuesta"=>$result));
                }
                else{
                    $servicio->idestado = 2;
                    echo json_encode(array("codigo"=>"2","respuesta"=>substr($result, 8 )));
                }
                $servicio->save();
            }
            else{
                echo json_encode(array("codigo"=>"3"));
            }
        }
		
        function pruebaAction()
        {
                echo '<pre>';
                $idfachada = 497;
                $model = new CompilerModel();
                $model->EliminarFachada($idfachada);
        }

    }
?>
