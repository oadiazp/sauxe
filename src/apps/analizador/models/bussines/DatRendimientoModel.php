<?php

class DatRendimientoModel extends ZendExt_Model
{
public function __construct() {
        parent :: ZendExt_Model();
    }
    
    public function cargar($pStart, $pLimit) {
        $q = Doctrine_Query::create();
        
        $q->from('DatRendimiento e')
          ->offset($pStart)->limit($pLimit);
        
        $exceptions = $q->execute ()->toArray();
        
        $result = array ();
        
        foreach ($exceptions as $excepcion) {
            $excepcion['categoria'] = Doctrine::getTable ('NomCategoria')->find ($excepcion ['idcategoria'])->categoria;
            $tmp = $this->integrator->metadatos->DameEstructura ($excepcion ['idestructuracomun']);
            
            $excepcion['estructura'] = $tmp[0]->denominacion;
            $result [] = $excepcion;
        }
        
        return $result;
    }
    
    public function contar() {
        $q = Doctrine_Query::create();
        
        $q->select('count(idcaso) cantidad')->from ('DatRendimiento');
        
        return $q->execute()->getFirst()->cantidad;
    }
}

