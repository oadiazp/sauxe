<?php 
class DatServicio extends BaseDatServicio
 { 
    public function setUp(){
        parent::setUp();
        $this->hasMany('DatFachada', array('local'=>'idservicio','foreign'=>'idfachada')); 
        $this->hasOne('NomEstado', array('local'=>'idestado','foreign'=>'idestado')); 
    }

    public function GetDatServicebyURi($uri){
        
        $query = Doctrine_Query :: create();
        $result = $query->from('DatServicio s')->where('s.uri = ?', $uri)->execute ();
        return $result->toArray();
    }
    



 
}//fin clase 
?>


