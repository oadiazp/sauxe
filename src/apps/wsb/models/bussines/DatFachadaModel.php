<?php 
class DatFachadaModel extends ZendExt_Model
 { 
   public function setUp() 
    { 
        parent::ZendExt_Model(); 
    } 
 
   public function Insertar(DatFachada $DatFachada)
    { 
            $DatFachada->save();
    } 
 
   public function Actualizar(DatFachada $DatFachada)
    { 
            $DatFachada->save();
    } 
 
   public function Eliminar($DatFachada)
    { 
            $DatFachada->delete();
    } 
 }

