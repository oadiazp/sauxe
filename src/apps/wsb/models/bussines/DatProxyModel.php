<?php 
class DatProxyModel extends ZendExt_Model 
 { 
    public function setUp(){
        parent::ZendExt_Model(); 
    } 
 
    public function Insertar(DatProxy $DatProxy){
            $DatProxy->save();
    } 

    public function Actualizar(DatProxy $DatProxy){
            $DatProxy->save();
    } 
 
    public function Eliminar($DatProxy){
            $DatProxy->delete();
    }

    public function AddProxy($datosBD){
        $datproxy = new DatProxy();
        $datproxy->nombre = $datosBD['data']->Nombreproxy;
        $datproxy->descripcion = $datosBD['data']->descripcionproxy;
        $datproxy->save();
        foreach($datosBD['services'] as $i=>$serv){
            $servpack = new DatProxyfachada();
            $servpack->idproxy = $datproxy->idproxy;
            $servpack->idfachada = $serv;
            $servpack->save();
        }
        
    }


 }

