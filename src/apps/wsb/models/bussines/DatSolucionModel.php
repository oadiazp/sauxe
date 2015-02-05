<?php 
class DatSolucionModel extends ZendExt_Model 
 { 
   public function setUp() 
    { 
        parent::ZendExt_Model(); 
    } 
 
   public function Insertar(DatSolucion $DatSolucion) 
    { 
            $DatSolucion->save();
            return $DatSolucion->idsolucion;
    } 
 
   public function Actualizar(DatSolucion $DatSolucion) 
    { 
            $DatSolucion->save();
    } 
 
   public function Eliminar($DatSolucion) 
    { 
            $DatSolucion->delete();
    }

    function getAll(){
       
        $q = Doctrine_Query::create();
        return $q->select ('s.idsolucion, s.nombre as solucion')
              ->from ('DatSolucion s')
              ->execute ()->toArray();
    }
 }

