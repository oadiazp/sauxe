<?php 
class NomEstadoModel extends ZendExt_Model 
 { 
   public function setUp() 
    { 
        parent::ZendExt_Model(); 
    } 
 
   public function Insertar(NomEstado $NomEstado) 
    { 
            $NomEstado->save();
    } 
 
   public function Actualizar(NomEstado $NomEstado) 
    { 
            $NomEstado->save();
    } 
 
   public function Eliminar($NomEstado) 
    { 
            $NomEstado->delete();
    } 
 }

