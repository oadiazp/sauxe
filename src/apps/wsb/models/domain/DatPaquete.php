<?php 
class DatPaquete extends BaseDatPaquete
 { 
    public function setUp(){
        parent::setUp();
        $this->hasOne('DatSolucion', array('local'=>'idsolucion','foreign'=>'idsolucion')); 
        $this->hasMany('DatFachada', array('local'=>'idpaquete','foreign'=>'idpaquete')); 
    }

    public function LoadPackagesbyIdsolucion($idsolucion){
        
        $query = Doctrine_Query :: create();
        $result = $query->select ('p.*,f.*,s.*,e.*')
                        ->from('DatPaquete p')
                        ->innerJoin('p.DatFachada f')
                        ->innerJoin('f.DatServicio s')
                        ->innerJoin('s.NomEstado e')
                        ->where('p.idsolucion = ?',$idsolucion)
                        ->execute ();
        
        //print_r($result->toArray (true));die;
        return $result->toArray (true);
    }
 
}//fin clase 
?>


