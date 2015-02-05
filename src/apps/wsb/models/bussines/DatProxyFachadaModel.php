<?php 
class DatProxyFachadaModel extends ZendExt_Model
 { 
   public function setUp() 
    { 
        parent::ZendExt_Model(); 
    } 
 
   public function Insertar(DatProxyfachada $DatProxyfachada)
    { 
            $DatProxyfachada->save();
    } 
 
   public function Actualizar(DatProxyfachada $DatProxyfachada)
    { 
            $DatProxyfachada->save();
    } 
 
   public function Eliminar($DatProxyfachada)
    { 
            $DatProxyfachada->delete();
    } 
 }

