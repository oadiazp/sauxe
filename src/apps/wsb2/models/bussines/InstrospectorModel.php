<?php

class InstrospectorModel extends ZendExt_Model{
    function  __construct() {
        parent::ZendExt_Model();
    }

    function getParamsByClassAndMethod ($pClass, $pMethod){
        try {
            $result = array ();
            @$class = new Zend_Reflection_Class ($pClass);
            $method = $class->getMethod ($pMethod);
            $params = $method->getParameters();
            try{
                $docblock = $method->getDocblock();
                $docblockparams = $docblock->getTags('param');
            }catch(Exception $ee){
            }
            foreach ($params as $i=>$param) {
                $item->param = $param->getName();
                $item->type = '';
                $item->description = '';
                
                try{
                    if($param->getType())
                        $item->type = $param->getType();
                    if($docblockparams[$i]->getDescription())
                        $item->description = $docblockparams[$i]->getDescription();
                }
                catch(Exception $ee){
                }
                $result[] = $item;
                $item = null;
            }
            return $result;
        }
        catch (Exception $e) {
            return array ();
        }
    }

    function getReturnByClassAndMethod ($pClass, $pMethod) {
        try {
            @$reflection_class = new Zend_Reflection_Class ($pClass);
            return $reflection_class->getMethod($pMethod)->getDocblock()->getTag('return')->getType ();
        }
        catch (Exception $e) {
           return false;
        }
    }

    function getDocblockByClassAndMethod ($pClass, $pMethod) {
        @$reflection_class = new Zend_Reflection_Class ($pClass);
        return $reflection_class->getMethod ($pMethod)->getDocblock();
    }

    function getDescriptionByClassAndMethod ($pClass, $pMethod){
        try{
            @$reflection_class = new Zend_Reflection_Class ($pClass);
            return $reflection_class->getMethod($pMethod)->getDocblock()->getShortDescription();
        }
        catch(Exception $e){
            return "";
        }
    }

    function getFilebyClass($pClass){
        @$reflection_class = new Zend_Reflection_Class ($pClass);
        return $reflection_class->getDeclaringFile()->getFileName();
    }
}
?>
