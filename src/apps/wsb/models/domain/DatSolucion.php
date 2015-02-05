<?php 
    class DatSolucion extends BaseDatSolucion{
        public function setUp(){
            parent::setUp();
            $this->hasMany('DatPaquete', array('local'=>'idsolucion','foreign'=>'idsolucion'));
        }

        function getSolutionByName ($pName) {
            $q = Doctrine_Query::create();
            $result = $q->from ('DatSolucion s')
                  ->where ('s.nombre = ?', $pName)
                  ->execute();
            return $result;
        }
	
	function getSolucionByPath($path){
            $q = Doctrine_Query::create();
            $result = $q->from ('DatSolucion s')
                  ->where ('s.path = ?', $path)
                  ->execute();
            return $result->toArray();
	}
    }
?>


