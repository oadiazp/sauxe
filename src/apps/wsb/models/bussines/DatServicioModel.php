<?php 
class DatServicioModel extends ZendExt_Model
 { 
   public function setUp() 
    { 
        parent::ZendExt_Model(); 
    } 
 
   public function Insertar(DatServicio $DatServicio)
    { 
            $DatServicio->save();
    } 
 
   public function Actualizar(DatServicio $DatServicio)
    { 
            $DatServicio->save();
    } 
 
   public function Eliminar($DatServicio)
    { 
            $DatServicio->delete();
    } 
 }

