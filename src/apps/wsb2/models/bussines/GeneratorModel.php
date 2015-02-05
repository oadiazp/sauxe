<?php

class GeneratorModel extends ZendExt_Model{
    function  __construct() {
        parent::ZendExt_Model();
    }

    function GeneratePackage($package){
        //creando clase
        $class = new Zend_CodeGenerator_Php_Class();
        $class->setName($package->name);
        //adicionando comentario a la clase
        $docblock = new Zend_CodeGenerator_Php_Docblock(array(
                        'shortDescription' => $package->name,
                        'longDescription'  => $package->description,
                        'tags'=>array(
                                    array(
                                        'name'        => 'Copyright',
                                        'description' => 'UCI'
                                    ),
                                    array(
                                        'name'        => 'Author',
                                        'description' => $package->author
                                    )
                                )
        ));
        $class->setDocblock($docblock);
        //adicionando herencia
        $class->setExtendedClass('ZendExt_Webservice');
        //adicionando el constructor
        $constructor = new Zend_CodeGenerator_Php_Method();
        $constructor->setName('__construct');
        $constructor->setBody("parent::ZendExt_Webservice();");
        $class->setMethod($constructor);
        //adicionando métodos
        foreach($package->services as $service){
            $method = new Zend_CodeGenerator_Php_Method();
            $method_docblock  = new Zend_CodeGenerator_Php_Docblock();
            $method->setName($service->name);
            $parametros = "(";
            $flag = false;
            foreach ($service->params as $param=>$type){
                if($flag){
                    $parametros.=',';
                }
                $parameter = new Zend_CodeGenerator_Php_Parameter();
                $parameter->setName($param);
                $method->setParameter($parameter);
                $flag=true;
                $parametros.='$'.$param;
                $method_docblock->setTag(array(
                    'name'=> 'param',
                    'description'=>$type.' $'.$param
                ));
            }
            $parametros.=")";
            $method->setBody('if (parent :: isValid (apache_request_headers ()))'.PHP_EOL."\t".'return $this->integrator->'.$service->subsystem.'->'.$service->name.$parametros.";".PHP_EOL);
            $method_docblock->setTag(array(
                        'name'=> 'return',
                        'description'=> $service->result
            ));
            $method->setDocblock($method_docblock);
            $class->setMethod($method);
        }

        //construyendo el includepath
        $include = "";
        foreach($package->includepath as $datatype){
            $include .= "include '".$datatype."';\n";
        }
        $include .= PHP_EOL.PHP_EOL;

        //salvando el fichero
        $file = new Zend_CodeGenerator_Php_File();
        
        $dir = $_SERVER['SCRIPT_FILENAME'];
        $dir = str_replace('web', 'apps', $dir);
        $dir = explode('index.php', $dir);
        $dir = $dir[0].'comun/recursos/templates/ServicePackageTemplate.txt';
        $template = file_get_contents($dir);
        $code = str_replace("[#Class#]", $class->generate(), $template);
        $code = str_replace("[#Includes#]", $include, $code);
        $code = str_replace("[#ClassName#]", $package->name, $code);
        $file->setBody($code);
        file_put_contents($_SERVER['DOCUMENT_ROOT'].$package->uri, $file->generate());
    }

    function GenerateDocblock($path, $class, $method, $docblock){
        try{
            @$reflectionclass = new Zend_Reflection_Class ($class);
            $metod = $reflectionclass->getMethod ($method);
            $stardocblockline = $metod->getStartLine(true)-1;
            $starmethodline = $metod->getStartLine()-1;
            $methoddocblock = new Zend_CodeGenerator_Php_Docblock();
            
            $methoddocblock->setShortDescription($docblock->description);
            $tags = array();
            foreach ($docblock->params as $param)
            {
            	$tags[] = new Zend_CodeGenerator_Php_Docblock_Tag_Param(array('description' => $param->description, 'paramName' =>$param->param, 'datatype'  => $param->type));
            }
            $tags[] = new Zend_CodeGenerator_Php_Docblock_Tag_Return(array('datatype'  => $docblock->result));
            $methoddocblock->setTags($tags);
            $code = file_get_contents($path);
            $lines = explode(PHP_EOL, $code);
            for($i = $stardocblockline ; $i<$starmethodline; $i++){
                unset ($lines[$i]);
            }
            $lines = array_filter($lines);
            $identation = explode(ltrim($lines[$starmethodline]), $lines[$starmethodline]);
            $methoddocblock->setIndentation($identation[0]);
            $lines[$starmethodline] = $methoddocblock->generate().$lines[$starmethodline];
            $code = implode(PHP_EOL, $lines);
            file_put_contents($path, $code);
            return true;
        }
        catch (Exception $ee){
            return false;
        }
    }

    function AddService(){
		
    }

    function RemoveService($package, $service){
        set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'].'/ws');
        $dir="../ws/".$package.".php";
        try{
            @$reflectionclass = new Zend_Reflection_Class ($package);
            $metod = $reflectionclass->getMethod ($service);
            $starmethodline = $metod->getStartLine(true)-1;
            $endmethodline = $metod->getEndLine()-1;
            
            $code = file_get_contents($dir);
            $lines = explode(PHP_EOL, $code);
            for($i = $starmethodline ; $i<=$endmethodline; $i++){
                unset ($lines[$i]);
            }
            $lines = array_filter($lines);
            $code = implode(PHP_EOL, $lines);
            file_put_contents($dir, $code);
            return true;
        }
        catch (Exception $ee){
            print_r($ee);die;
            return false;
        }
    }
}
?>
