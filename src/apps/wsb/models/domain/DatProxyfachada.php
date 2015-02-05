<?php 
class DatProxyfachada extends BaseDatProxyfachada
 { 
   public function setUp() 
    {   parent::setUp(); 
         $this->hasOne('DatFachada', array('local'=>'idfachada','foreign'=>'idfachada')); 
         $this->hasOne('DatProxy', array('local'=>'idproxy','foreign'=>'idproxy')); 

    } 
	
	public function FindByProxy($idproxy)
	{
		$query = Doctrine_Query :: create();
		$result = $query->select ('pf.*','p.*','f.*')
		->from('DatProxyFachada pf')
		->innerjoin('pf.DatProxy p')
		->innerjoin('pf.DatFachada f')
		->where('pf.idproxy = ?', $idproxy)
		->execute();
		return $result->toArray (true);
	}
       public function getProxyfachadaById ($id) {
        $q = Doctrine_Query::create();
        return $q->from ('DatProxyfachada s')
              ->where ('s.idproxyfachada = ?', $id)
              ->execute();
    }
	
	public function FindByFachada($idfachada)
	{
		$query = Doctrine_Query :: create();
		$result = $query->select ('pf.*','p.*','f.*')
		->from('DatProxyFachada pf')
		->innerjoin('pf.DatProxy p')
		->innerjoin('pf.DatFachada f')
		->where('pf.idfachada = ?', $idfachada)
		->execute();
		return $result->toArray (true);
	}
 
 
}//fin clase 
?>


