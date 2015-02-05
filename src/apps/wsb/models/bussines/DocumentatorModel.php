<?php
class DocumentatorModel extends ZendExt_Model{
    function  __construct() {
        parent::ZendExt_Model();
    }

    function GetDocblock($class, $method){
        try
        {
            @$reflection_class = new Zend_Reflection_Class($class);
            return $reflection_class->getMethod ($method)->getDocblock();
            
        }
        catch(Exception $ee)
        {
            try{
                @$reflection_class = new Zend_Reflection_Class($class);
                return false;
            }
            catch(Exception $ee){
                throw new ZendExt_Exception("pinga cojone!!!!!!!!!");
            }
        }
    }
    
    function SetDocblock($class, $method, $datos){
        try
        {
            @$generator = Zend_CodeGenerator_Php_Class::fromReflection(new Zend_Reflection_Class($class));
            $metod = $generator->getMethod($method);
            $docblock = new Zend_CodeGenerator_Php_Docblock();
            $docblock->setShortDescription($datos['descripcion']);
            $tags = array();
            foreach ($datos['tags'] as $tag)
            {
            	$tags[] = new Zend_CodeGenerator_Php_Docblock_Tag_Param(array('description' => "--", 'paramName' =>"$".$tag['nombre'], 'datatype'  => $tag['tipo']));
            }
            $tags[] = new Zend_CodeGenerator_Php_Docblock_Tag_Return(array('datatype'  => $datos['retorno']));
            $docblock->setTags($tags);
            $metod->setDocblock($docblock);
            return $generator->generate();
        }
        catch(Exception $ee)
        {
            return false;
        }
    }

}
?>
