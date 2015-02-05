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
class DatAccion extends BaseDatAccion {

	public function setUp(){
    parent::setUp();
    $this->hasOne('DatFuncionalidad',array('local'=>'idfuncionalidad','foreign'=>'idfuncionalidad'));    
    $this->hasMany('DatSistemaSegRolDatFuncionalidadDatAccion',array('local'=>'idaccion','foreign'=>'idaccion'));     
    $this->hasMany('DatAccionDatReporte',array('local'=>'idaccion','foreign'=>'idaccion'));    
	$this->hasMany('DatAccionCompartimentacion',array('local'=>'idaccion','foreign'=>'idaccion'));
    }

static function cargarAccionesCompartimentacion($idfuncionalidad, $iddominioComp) {
				$query = Doctrine_Query::create();
		    	$global = ZendExt_GlobalConcept::getInstance();
		    	$iddominio = $global->Perfil->iddominio;	            
		    	$datos = $query->select('a.idaccion, a.idaccion id, a.denominacion text, true leaf, ac.idaccion')
		    					->from('DatAccion a')
		    					->leftJoin("a.DatAccionCompartimentacion ac ON (a.idaccion = ac.idaccion and ac.iddominio = '$iddominioComp')")
		    					->where("a.idfuncionalidad = ? and a.iddominio = ?", array($idfuncionalidad, $iddominio))
		    					->execute();
		    	return $datos;
			}
  
	/*static public function cargaraccion($idfuncionalidad,$limit,$start)
	{
	            $query = Doctrine_Query::create();
	            $fndes = $query->select('a.idaccion id,a.idaccion,a.denominacion,a.abreviatura,a.descripcion,a.idfuncionalidad')->from('DatAccion a')->where("a.idfuncionalidad = ?", $idfuncionalidad)->orderby('idaccion')->limit($limit)->offset($start)->execute();
	            return $fndes;
		}*/
	
	static public function obtenerAccionesFuncionalidad($idfuncionalidad) {
		            $query = Doctrine_Query::create();
		            $datos = $query->select('a.idaccion id,a.idaccion,a.denominacion,a.abreviatura')->from('DatAccion a')->where("a.idfuncionalidad = ?", $idfuncionalidad)->setHydrationMode( Doctrine :: HYDRATE_ARRAY )->execute();
		            return $datos;
			}	
			
	static function cargarAcciFuncCompartimentacion($idfuncionalidad) {
					$query = Doctrine_Query::create();
			    	$global = ZendExt_GlobalConcept::getInstance();
			    	$iddominio = $global->Perfil->iddominio;	            
			    	$datos = $query->select('a.idaccion, a.idaccion id, a.denominacion text, true leaf, ac.idaccion')
			    					->from('DatAccion a')
			    					->innerJoin("a.DatAccionCompartimentacion ac")
			    					->where("a.idfuncionalidad = ? and a.iddominio = ?", array($idfuncionalidad, $iddominio))
			    					->execute();
			    	return $datos;
				}
    
	static function cargarAcciCompartimentacion($idfuncionalidad, $idrol) {
						$query = Doctrine_Query::create();
				    	$global = ZendExt_GlobalConcept::getInstance();
				    	$iddominio = $global->Perfil->iddominio;	            
				    	$datos = $query->select('a.idaccion, a.idaccion id, a.denominacion text, true leaf, ac.idaccion, ra.idaccion')
				    					->from('DatAccion a')
				    					->innerJoin("a.DatAccionCompartimentacion ac ON a.idaccion = ac.idaccion")
				    					->innerJoin("a.DatSistemaSegRolDatFuncionalidadDatAccion ra ON a.idaccion = ra.idaccion")
				    					->where("a.idfuncionalidad = ? and ac.iddominio = ? and ra.idrol= ?", array($idfuncionalidad, $iddominio, $idrol))
				    					->execute();
				    	return $datos;
					}

	static function cargarAccionesNoTiene($idfuncionalidad, $arrayAcc, $limit, $start) {
							$query = Doctrine_Query::create();
					    	$global = ZendExt_GlobalConcept::getInstance();
					    	$iddominio = $global->Perfil->iddominio;	            
					    	$datos = $query->select('a.idaccion, a.idaccion id, a.denominacion,a.idfuncionalidad, ac.idaccion')
					    					->from('DatAccion a')
					    					->innerJoin("a.DatAccionCompartimentacion ac ON a.idaccion = ac.idaccion")
					    					->where("a.idfuncionalidad = ? and ac.iddominio = ?", array($idfuncionalidad, $iddominio))
					    					->whereNotIn('ac.idaccion',$arrayAcc)
					    					->limit($limit)
            								->offset($start)
					    					->execute();
					    	return $datos;
						}
					
	static public function cargaraccion($idfuncionalidad,$limit,$start)	{
		$global = ZendExt_GlobalConcept::getInstance();
    	$iddominio = $global->Perfil->iddominio;
        $query = Doctrine_Query::create();
        $datos = $query->select('a.idaccion id,a.denominacion,a.abreviatura,a.descripcion,a.idfuncionalidad')
            			->from('DatAccion a')
            			->where("a.idfuncionalidad = ? and a.iddominio = ?", array($idfuncionalidad, $iddominio))
            			->limit($limit)
            			->offset($start)
            			->execute();
        return $datos;
	}
	
	static public function cargarAccionByIdFunc($idfuncionalidad){
		$global = ZendExt_GlobalConcept::getInstance();
    	$iddominio = $global->Perfil->iddominio;
        $query = Doctrine_Query::create();
        $datos = $query->select('a.idaccion, a.denominacion')
            			->from('DatAccion a')
            			->where("a.idfuncionalidad = ? and a.iddominio = ?", array($idfuncionalidad, $iddominio))
            			->execute();
        return $datos;
	}

	static public function obtenerAcciones($idfuncionalidad){
       		$query = Doctrine_Query::create();
        	$datos = $query->select('a.idaccion, a.denominacion, a.abreviatura, a.descripcion, a.icono')
            			->from('DatAccion a')
            			->where("a.idfuncionalidad = ?", $idfuncionalidad)
            			->execute();
        	return $datos;
	}	
		
	/*static function obtenercantaccion($idfuncionalidad)
	{
	            $query = Doctrine_Query::create();
	            $cantFndes = $query->from('DatAccion a')
	            				   ->where("a.idfuncionalidad = ?", $idfuncionalidad)
	            			       ->count();
	            return $cantFndes;
		}*/

	static function verificaraddaccion($denominacion,$abreviatura)
	{
	$query=Doctrine_Query::create();
	$var = $query->from('DatAccion d')
                  ->where("d.denominacion = ? or d.abreviatura = ?", array($denominacion, $abreviatura))->count();
	return $var;
	}
		
	/*static public function buscaraccion($idfuncionalidad,$denominacion,$limit,$start)  
	{
            $query = Doctrine_Query::create();
            $datos = $query->select('a.idaccion id,a.denominacion,a.abreviatura,a.descripcion,a.idfuncionalidad')->from('DatAccion a')->where("a.idfuncionalidad = '$idfuncionalidad' and a.denominacion like '$denominacion%'" )->limit($limit)->offset($start)->execute();
            return $datos;
	} 
		
	static public function obtenercantaccionbuscadas($idfuncionalidad,$denominacion)
	{
	  $query = Doctrine_Query::create();
            $cant = $query->from('DatAccion')
            			   ->where("idfuncionalidad = '$idfuncionalidad' and denominacion like '$denominacion%'" )
            			   ->count();
            return $cant;
	}*/
	
	static public function cargarAccionByArrayIdFunc(array $arrIdFuncionalidad)	{
		$global = ZendExt_GlobalConcept::getInstance();
    	$iddominio = $global->Perfil->iddominio;
        $query = Doctrine_Query::create();
        $datos = $query->select('a.idaccion id')
            			->from('DatAccion a')
            			->where("a.iddominio = ?", $iddominio)
            			->whereIn("a.idfuncionalidad", $arrIdFuncionalidad)
            			->setHydrationMode( Doctrine :: HYDRATE_ARRAY )
            			->execute();
        return $datos;
	}
		
	static function obtenercantaccion($idfuncionalidad)	{
		$global = ZendExt_GlobalConcept::getInstance();
    	$iddominio = $global->Perfil->iddominio;
	            $query = Doctrine_Query::create();
	            $cantFndes = $query->select('count(a.idaccion) as cant')
	            					->from('DatAccion a')
	            				   ->where("a.idfuncionalidad = ? and a.iddominio = ?", array($idfuncionalidad, $iddominio))
	            			       ->execute();
	            return $cantFndes[0]->cant;
	}
		
	static public function buscaraccion($idfuncionalidad,$denominacion,$limit,$start){
		$global = ZendExt_GlobalConcept::getInstance();
    	$iddominio = $global->Perfil->iddominio;
        $query = Doctrine_Query::create();
        $datos = $query->select('a.idaccion id,a.denominacion,a.abreviatura,a.descripcion,a.idfuncionalidad')
            			->from('DatAccion a')
            			->where("a.idfuncionalidad = ? and a.denominacion like '$denominacion%' and a.iddominio = ?", array($idfuncionalidad, $iddominio))
            			->limit($limit)
            			->offset($start)
            			->execute();
        return $datos;
	} 
		
	static public function obtenercantaccionbuscadas($idfuncionalidad,$denominacion){
    	$global = ZendExt_GlobalConcept::getInstance();
    	$iddominio = $global->Perfil->iddominio;
	            $query = Doctrine_Query::create();
	            $cantFndes = $query->select('count(a.idaccion) as cant')
	            					->from('DatAccion a')
	            				   ->where("a.idfuncionalidad = ? and a.iddominio = ? and denominacion like '$denominacion%'", array($idfuncionalidad, $iddominio))
	            			       ->execute();
	            return $cantFndes[0]->cant;
	}
	
    static public function cargaracciones($idsistema,$idfuncionalidad,$idrol){
        $query = Doctrine_Query::create();
        $datos = $query->select('a.denominacion, a.idaccion')->from('DatAccion a, a.DatSistemaSegRolDatFuncionalidadDatAccion esfa')
                       ->where("esfa.idsistema = ? and esfa.idfuncionalidad = ? and esfa.idrol = ?",array($idsistema,$idfuncionalidad,$idrol))
                       ->execute(); 
        return $datos;
    }
    
    static public function cargaraccionesservice($idfuncionalidad,$idrol){
    $query = Doctrine_Query::create();
    $datos = $query->select('a.abreviatura, a.idaccion')->from('DatAccion a, a.DatSistemaSegRolDatFuncionalidadDatAccion sfa')
               ->where("sfa.idfuncionalidad = ? and sfa.idrol = ?",array($idfuncionalidad,$idrol))
               ->execute(); 
    return $datos;
    }
	
	static public function accesoAccion($rol,$denominacion){
        $query = Doctrine_Query::create();
        $datos = $query->select('a.denominacion, a.idaccion')->from('DatAccion a')->innerjoin('a.DatSistemaSegRolDatFuncionalidadDatAccion ra ON a.idaccion = ra.idaccion')
                       ->where("ra.idrol = ? and a.denominacion = ?",array($rol,$denominacion))
                       ->execute(); 
        return $datos;
    }
    
	static public function cargaraccionesrep($limit,$start)
	{
        $query = Doctrine_Query::create();
        $acciones = $query->select('a.idaccion ,a.denominacion, a.abreviatura,a.descripcion')->from('DatAccion a')->orderby('a.idaccion')->limit($limit)->offset($start)->execute()->toArray(true);
        return $acciones;
	}
	static public function cantaccionesBuscadas()
	{
	  $query = Doctrine_Query::create();
            $cant = $query->from('DatAccion')
						  ->count();
            return $cant;
	}
	
	static public function cargaraccionesrepAbrev($abrev,$limit,$start)
	{
        $query = Doctrine_Query::create();
        $acciones = $query->select('a.idaccion ,a.denominacion, a.abreviatura,a.descripcion')->from('DatAccion a')->where("a.abreviatura like '$abrev%'")->orderby('a.idaccion')->limit($limit)->offset($start)->execute()->toArray(true);
        return $acciones;
	}
	
	static public function cantaccionesBuscadasAbrev($abrev)
	{
	  $query = Doctrine_Query::create();
            $cant = $query->from('DatAccion')
            			  ->where("abreviatura like '$abrev%'")
						  ->count();
            return $cant;
	}
}
