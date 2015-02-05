<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class NomDominio extends BaseNomDominio
{
 static public function cantnomdominio()
    {

            $query = new Doctrine_Query ();        
            $cant = $query ->from('NomDominio')
                             ->count();    
            return $cant;                         
    }

	static public function cantNomDominioDadoNombre($nombredominio) {
		$query = new Doctrine_Query ();
		$datos = $query  ->select('count(iddominio) as cant')
				->from('NomDominio')
				->where("denominacion like '$nombredominio%'")
				->execute();
		return $datos[0]->cant;
	}
	
	static public function verificarNombreDominio($nombredominio) {
		$query = new Doctrine_Query ();
		$datos = $query  ->select('count(iddominio) as cant')
				->from('NomDominio')
				->where("denominacion=?",$nombredominio)
				->execute();
		return $datos[0]->cant;
	}
	
	static public function BuscarDomioDadoNombre($limit , $start, $nombredominio) {
		$query = new Doctrine_Query ();
		$datos = $query  ->select('d.iddominio, d.denominacion, d.seguridad, d.descripcion')
				->from('NomDominio d')
				->where("denominacion like '$nombredominio%'")
				->limit($limit)
                                ->offset($start)
				->setHydrationMode ( Doctrine :: HYDRATE_ARRAY )
				->execute();
		return $datos;
	}

}
