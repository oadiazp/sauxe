<?php

/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garc�a Tejo
 * @author Julio Cesar Garc�a Mosquera  
 * @version 1.0-0
 */
class DatFuncionalidad extends BaseDatFuncionalidad
{

	public function setUp() {
    parent::setUp();    
    $this->hasMany('DatAccion',array('local'=>'idfuncionalidad','foreign'=>'idfuncionalidad')); 
    $this->hasMany('DatSistemaSegRolDatFuncionalidad',array('local'=>'idfuncionalidad','foreign'=>'idfuncionalidad')); 
    $this->hasOne('DatSistema',array('local'=>'idsistema','foreign'=>'idsistema'));  
    $this->hasMany('DatFuncionalidadCompartimentacion',array('local'=>'idfuncionalidad','foreign'=>'idfuncionalidad'));
    }
    
	static function cargarFuncionalidadesCompartimentacion($idsistema, $iddominioComp) {
			$query = Doctrine_Query::create();
	    	$global = ZendExt_GlobalConcept::getInstance();
	    	$iddominio = $global->Perfil->iddominio;	            
	    	$datos = $query->select('f.idfuncionalidad, f.idfuncionalidad id, f.denominacion text, true leaf, fc.idfuncionalidad')
	    					->from('DatFuncionalidad f')
	    					->leftJoin("f.DatFuncionalidadCompartimentacion fc ON (f.idfuncionalidad = fc.idfuncionalidad and fc.iddominio = '$iddominioComp')")
	    					->where("f.idsistema = ? and f.iddominio = ?", array($idsistema, $iddominio))
	    					->execute();
	    	return $datos;
		}
  
	static function cargarfuncionalidades($idsistema,$limit,$start)	{
		$query = Doctrine_Query::create();
    	$global = ZendExt_GlobalConcept::getInstance();
    	$iddominio = $global->Perfil->iddominio;	            
    	$datos = $query->select('idfuncionalidad id, referencia, denominacion text, descripcion, icono,true leaf, idsistema, index')
    					->from('DatFuncionalidad')
    					->where("idsistema = ? and iddominio = ?", array($idsistema, $iddominio))
    					->execute();
    	return $datos;
	}
    
	static function cargarFuncionalidadesCompart($idsistema) {
				$query = Doctrine_Query::create();
		    	$global = ZendExt_GlobalConcept::getInstance();

		    	$iddominio = $global->Perfil->iddominio;
		    		            
		    	$datos = $query->select('f.idfuncionalidad, f.idfuncionalidad id, f.denominacion text, true leaf, fc.idfuncionalidad')
		    					->from('DatFuncionalidad f')
		    					->innerJoin("f.DatFuncionalidadCompartimentacion fc")
		    					->where("f.idsistema = ? and fc.iddominio = ?", array($idsistema, $iddominio))
		    					->execute();
		    	return $datos;
			}
	
    function cargarfuncsistemarol($idsistema,$idrol)
    {
    $query = Doctrine_Query::create();
    $datos = $query->select('DISTINCT (f.idfuncionalidad) id, f.denominacion text, f.referencia, f.icono, true leaf, f.idsistema')->from('DatFuncionalidad f, f.DatSistemaSegRolDatFuncionalidad srf')->where("srf.idsistema = ? and srf.idrol = ?", array($idsistema,$idrol))->execute(); 
    return $datos;
    }
	
	static function obtenercantfunc($idsistema)
	{
    	$query = Doctrine_Query::create();
    	$global = ZendExt_GlobalConcept::getInstance();
    	$iddominio = $global->Perfil->iddominio;
    	$cantFndes = $query->select('count(f.idsistema) as cant')
	    					->from('DatFuncionalidad f')
	    					->where("f.idsistema = ? and f.iddominio = ?", array($idsistema, $iddominio))
	    					->execute();
    	return $cantFndes[0]->cant;
	} 

	static function obtenercantfuncCompart($idsistema)
	{
    	$query = Doctrine_Query::create();
    	$global = ZendExt_GlobalConcept::getInstance();
    	$iddominio = $global->Perfil->iddominio;
    	$cantFndes = $query->select('count(f.idsistema) as cant')
	    					->from('DatFuncionalidad f')
	    					->innerJoin("f.DatFuncionalidadCompartimentacion fc")
		    				->where("f.idsistema = ? and fc.iddominio = ?", array($idsistema, $iddominio))
	    					->execute();
    	return $cantFndes[0]->cant;
	}
        
	static public function buscarfuncionalidades($idsistema,$denominacion,$limit,$start)
	{
    	$query = Doctrine_Query::create();
    	$global = ZendExt_GlobalConcept::getInstance();
    	$iddominio = $global->Perfil->iddominio;	            
    	$datos = $query->select('idfuncionalidad id, referencia, denominacion text, descripcion, icono,idsistema,index')
    					->from('DatFuncionalidad')
    					->where("idsistema = ? and denominacion like '%$denominacion%' and iddominio = ?", array($idsistema, $iddominio))
    					->limit($limit)
    					->offset($start)
    					->execute();
    	return $datos;
	}

	static public function buscarfuncionalidadesgrid($idsistema,$limit,$start)
	{
    	$query = Doctrine_Query::create();
    	$global = ZendExt_GlobalConcept::getInstance();
    	$iddominio = $global->Perfil->iddominio;	            
    	$datos = $query->select('idfuncionalidad id, referencia, denominacion text, descripcion, icono,idsistema,index')
    					->from('DatFuncionalidad')
    					->where("idsistema = ? and iddominio = ?", array($idsistema, $iddominio))
    					->limit($limit)
    					->offset($start)
    					->execute();
    	return $datos;
	}
         
	static public function obtenercantfuncdenominacion($idsistema,$denominacion)
	{
		$query = Doctrine_Query::create();
    	$global = ZendExt_GlobalConcept::getInstance();
    	$iddominio = $global->Perfil->iddominio;
    	$cantFndes = $query->select('count(f.idsistema) as cant')
	    					->from('DatFuncionalidad f')
	    					->where("f.idsistema = ? and f.iddominio = ? and denominacion LIKE '%$denominacion%'", array($idsistema, $iddominio))
	    					->execute();
    	return $cantFndes[0]->cant;
	}
    
    static public function cargartodasacciones($idfuncionalidad) {
    $query = Doctrine_Query::create();         
    $datos = $query->select('a.idfuncionalidad, a.idaccion,a.denominacion')->from('DatAccion a')->where("a.idfuncionalidad=?",$idfuncionalidad)->execute();
    return $datos;
    }
    
	static public function obtenerFuncionalidad($idsistema) {
    $query = Doctrine_Query::create();         
    $datos = $query->select('f.idfuncionalidad id, f.denominacion text, f.referencia,f.descripcion,f.icono')->from('DatFuncionalidad f')->where("f.idsistema =?",$idsistema)->execute();  
    return $datos;
    }
    
    static public function obtenerFuncionalidades($idsistema,$idrol) { 
    $query = Doctrine_Query::create();         
    $datos = $query->select('f.idfuncionalidad id, f.denominacion text, f.referencia,f.descripcion,f.icono, f.index')->from('DatFuncionalidad f')->innerjoin('f.DatSistemaSegRolDatFuncionalidad srf')->where("srf.idsistema = ? and srf.idrol = ?",array($idsistema,$idrol))->execute();  
    return $datos;
    }
    
	static public function obtenerFuncionalidadess($idsistema) {
    $query = Doctrine_Query::create();         
    $datos = $query->select('f.idfuncionalidad id, f.denominacion text, f.referencia,f.descripcion,f.icono')->from('DatFuncionalidad f')->innerjoin('f.DatSistemaSegRolDatFuncionalidad srf')->where("srf.idsistema ={$idsistema}")->execute();  
    return $datos;
    }
	
	static public function accesoFuncionalidad($rol,$url) {
    $query = Doctrine_Query::create();         
    $datos = $query->select('f.idfuncionalidad id, f.denominacion text, f.referencia,f.descripcion,f.icono')->from('DatFuncionalidad f')->innerjoin('f.DatSistemaSegRolDatFuncionalidad rf ON f.idfuncionalidad = rf.idfuncionalidad')->where("rf.idrol =? and f.referencia=?",array($rol,$url))->execute();  
    return $datos;
    }
    
	static public function verificarfuncionalidad($denominacion) {
	$query = Doctrine_Query::create(); 
	$var = $query->from('DatFuncionalidad d')
                  ->where("d.denominacion = ?", $denominacion)->count();
	return $var;
	}
	
	static public function obtenerFuncionalidadesSistema($idsistema) {
		$query = Doctrine_Query::create();
		$datos = $query->select('a.idfuncionalidad id,a.idfuncionalidad,a.denominacion')->from('DatFuncionalidad a')->where("a.idsistema = ?", $idsistema)->setHydrationMode( Doctrine :: HYDRATE_ARRAY )->execute();
		return $datos;
		}	

	static public function getSistemId($uri) {
		$query = Doctrine_Query::create();
		$datos = $query->select('f.idfuncionalidad id,f.idsistema')->from('DatFuncionalidad f')->where("f.referencia = ?", $uri)->setHydrationMode( Doctrine :: HYDRATE_ARRAY )->execute();
		return $datos;
		}
	
}