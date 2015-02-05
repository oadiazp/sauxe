<?php 
class DatFachada extends BaseDatFachada
 { 
   public function setUp() 
    {   parent::setUp(); 
         $this->hasOne('DatServicio', array('local'=>'idservicio','foreign'=>'idservicio')); 
         $this->hasOne('DatPaquete', array('local'=>'idpaquete','foreign'=>'idpaquete')); 
         $this->hasMany('DatProxyfachada', array('local'=>'idfachada','foreign'=>'idfachada')); 

    } 

    public function GetFachadabyId($id){

        $query = Doctrine_Query :: create();
        $result = $query->from('DatFachada f')
                        ->innerJoin('f.DatServicio s')
                        ->where('f.idfachada = ?', $id)
                        ->execute ();
        return $result->toArray(true);
    }
 

 public function FindByServicio($idservicio)
	{
		$query = Doctrine_Query :: create();
		$result = $query->select ('f.*','s.*')
		->from('DatFachada f')
		->innerjoin('f.DatServicio s')
		->where('f.idservicio = ?', $idservicio)
		->execute();
		return $result->toArray (true);
	}
	
	public function FindByPaquete($idpaquete)
	{
		$query = Doctrine_Query :: create();
		$result = $query->select ('f.*','p.*')
		->from('DatFachada f')
		->innerjoin('f.DatPaquete p')
		->where('f.idpaquete = ?', $idpaquete)
		->execute();
		return $result->toArray (true);
	}

       public function AJS($id=null){

           $where = ($id)?"s.idfachada=$id":"";
           $query = Doctrine_Query :: create();
		$result = $query->select ('f.*','s.*')
		->from('DatFachada f')
		->innerjoin('f.DatServicio s')
		->where($where)
		->execute();
		return $result->toArray (true);
       }

	public function FindByPaqueteAndSercicio($idpaquete, $idservicio)
	{
		$query = Doctrine_Query :: create();
		$result = $query->select ('f.*','p.*','s.*')
		->from('DatFachada f')
		->innerjoin('f.DatPaquete p')
		->innerjoin('f.DatServicio s')
		->where("f.idpaquete = $idpaquete and f.idservicio = $idservicio")
		->execute();
		return $result->toArray (true);	
	}

}//fin clase 
?>


