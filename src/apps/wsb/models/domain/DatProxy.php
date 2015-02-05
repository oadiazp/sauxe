<?php 
class DatProxy extends BaseDatProxy
 { 
    public function setUp(){
       parent::setUp();
       $this->hasMany('DatProxyfachada', array('local'=>'idproxy','foreign'=>'idproxy')); 
    } 
    public function LoadProxiesByIdSolucion($idsolution){
        $query = Doctrine_Query :: create();
        $result = $query->select ('p.*,s.*,f.*,e.*,Se.*')
                        ->from('DatProxy p')
                        ->innerJoin('p.DatProxyfachada s')
                        ->innerJoin('s.DatFachada f')
                        ->innerJoin('f.DatServicio Se')
                        ->innerJoin('f.DatPaquete e')
                        ->where('e.idsolucion = ?',$idsolution)
                        ->execute ();
        return $result->toArray (true);
    }
    public function getProxyById ($id) {
        $q = Doctrine_Query::create();
        return $q->from ('DatProxy s')
              ->where ('s.idproxy = ?', $id)
              ->execute();
    }
 
}//fin clase 
?>


