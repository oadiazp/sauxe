<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class ActivarController extends ZendExt_Controller_Secure {
    public function init () {
        parent::init();
    }
    
    public function activarAction () {
        $this->render ();
    }
    
    function cambiarAction () {
        $path = substr(__FILE__, 0, strpos(__FILE__, 'controllers')) . 'comun/recursos/xml/analizador.xml';
        $xml = simplexml_load_file($path);
        
        $item = $this->getRequest()->getParam('name');
        $status = $this->getRequest()->getParam('enabled');
        
        $cat = $xml->$item;
        
        if ($status == '0') {
            $cat ['enabled'] = 'true';
        } else {
            $cat ['enabled'] = 'false';
        }
        
        $model = new ActivarModel ();
        $model->cambiar($item, $status);
        
        file_put_contents($path, $xml->asXml ());
    }
    
    public function respuestaAction () {
        $this->render ();
    }




    public function cargarAction () {
        $path = substr(__FILE__, 0, strpos(__FILE__, 'controllers')) . 'comun/recursos/xml/analizador.xml';
        
        $xml = simplexml_load_file($path);
        
        $result = array ();
        
        foreach ($xml->children () as $child) {
            $item->name = (string) $child ['name'];
            $item->enabled = (int)((string) $child ['enabled'] == 'true');
            $result [] = $item; $item = null;
        }
        
        echo json_encode(array ('data' => $result));
    }
}
?>
