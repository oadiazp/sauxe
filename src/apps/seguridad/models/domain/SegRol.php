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
class SegRol extends BaseSegRol
{
	public function setUp(){
        parent::setUp();
		$this->hasMany('DatSistemaSegRol',array('local'=>'idrol','foreign'=>'idrol'));       
        $this->hasMany('DatEntidadSegUsuarioSegRol',array('local'=>'idrol','foreign'=>'idrol')); 
		$this->hasMany('SegRolNomDominio',array('local'=>'idrol','foreign'=>'idrol'));       
    }
  
	static public function cantrol($filtroDominio = null) {
        $query = Doctrine_Query::create();
        $cantFndes = $query	->select('count(r.idrol) as cant')
        					->from('SegRol r')
        					->innerjoin('r.SegRolNomDominio rd')
        					->where("rd.iddominio =?",$filtroDominio)
        					->execute();
        return $cantFndes[0]->cant;
    }
   static public function canttotalrol() {
        $query = Doctrine_Query::create();
        $cantFndes = $query	->select('count(r.idrol) as cant')
        					->from('SegRol r')
        					->execute();
        return $cantFndes[0]->cant;
    }

	      
    static public function obtenerrol($filtroDominio,$limit, $start){
        $query = Doctrine_Query::create();
        $roles = $query ->select('DISTINCT (r.idrol),r.denominacion, r.abreviatura, r.descripcion')
        				->from('SegRol r')
        				->innerjoin('r.SegRolNomDominio rd')
        				->where("rd.iddominio =?",$filtroDominio)
        				->orderby('r.idrol')
        				->limit($limit)
        				->offset($start)
        				->execute();
        return $roles;
    }

   /* static public function obtenerrolesasociados($limit, $start, $idusuario)
    {
        $query = Doctrine_Query::create();
        $roles = $query->select('DISTINCT (r.idrol),r.denominacion, r.abreviatura, r.descripcion, sr.idusuario')->from('SegRol r')->leftJoin('r.DatEntidadSegUsuarioSegRol sr ON r.idrol = sr.idrol AND sr.idusuario = ?', $idusuario)->orderby('r.idrol')->limit($limit)->offset($start)->execute();
        return $roles;
    }*/
    
	static public function obtenerCantRolesUsuario($idusuario){
	    	$query = Doctrine_Query::create();
		    $datos = $query ->select('count(r.idrol) as cant')
		    				->from('SegRol r')
		    				->innerjoin('r.DatEntidadSegUsuarioSegRol eur')
		    				->whereIn("eur.idusuario",$idusuario)
		    				->setHydrationMode( Doctrine :: HYDRATE_ARRAY )
		    				->execute();
		    return $datos[0]['cant'];
	    	}
    	
    static public function obtenerrolesasociados($limit, $start, $idusuario, $arrayRolesDominio) {
        $query = Doctrine_Query::create();
        $roles = $query ->select('DISTINCT (r.idrol),r.denominacion, r.abreviatura, r.descripcion, sr.idusuario')
        				->from('SegRol r')
        				->leftJoin("r.DatEntidadSegUsuarioSegRol sr ON r.idrol = sr.idrol AND sr.idusuario = '$idusuario'")
        				->whereIn("r.idrol",$arrayRolesDominio)
        				->setHydrationMode( Doctrine :: HYDRATE_ARRAY )
        				->orderby('r.idrol')
        				->limit($limit)
        				->offset($start)
        				->execute();
        return $roles;
    }
    
	static public function cantrolesDominio($arrayRolesDominio) {
        $query = Doctrine_Query::create();
        $cantFndes = $query	->select('count(r.idrol) as cant')
        					->from('SegRol r')
        					->whereIn("r.idrol",$arrayRolesDominio)
        					->execute();
        return $cantFndes[0]->cant;
    }
    
    static public function obtenerRolesBuscado($rolbuscado,$limit,$start,$idusuario) {
        $query = Doctrine_Query::create();
        $roles = $query ->select('DISTINCT (r.idrol),r.denominacion, r.abreviatura, r.descripcion, sr.idusuario')
        				->from('SegRol r')
        				->leftJoin('r.DatEntidadSegUsuarioSegRol sr ON r.idrol = sr.idrol AND sr.idusuario = ?', $idusuario)
        				->where("r.denominacion like '$rolbuscado%'")
        				->orderby('r.idrol')
        				->limit($limit)
        				->offset($start)
        				->execute();
        return $roles;
    }
        
    static public function cargarcomborol()
    {
        $query = Doctrine_Query::create();
        $roles = $query->select('idrol,denominacion')->from('SegRol')->orderby('idrol')->execute();
        return $roles;                    
    }
		
     static public function obtenerrolesusuario($idusuario)
    {
        $query = Doctrine_Query::create();
        $roles = $query->select('DISTINCT r.idrol id,r.denominacion text, r.abreviatura, eur.identidad')->from('SegRol r')->innerjoin('r.DatEntidadSegUsuarioSegRol eur ON r.idrol = eur.idrol')->where("eur.idusuario = ?",$idusuario)->execute(); 
        return $roles;
    }
    
    static public function obtenerrolesusuarioentidad($idusuario,$identidad)
    {
    $query = Doctrine_Query::create();
    $roles = $query->select('r.idrol id,r.denominacion text, r.abreviatura')->from('SegRol r')->innerjoin('r.DatEntidadSegUsuarioSegRol eur ON r.idrol = eur.idrol')->where("eur.idusuario = ? and eur.identidad = ?",array($idusuario,$identidad))->execute(); 
    return $roles;
    }
    
    static public function comprobarrol($denominacion,$abreviatura)
    {
    $query = Doctrine_Query::create();
        $roles = $query->select('r.idrol')->from('SegRol r')->where("r.denominacion = ? OR r.abreviatura = ?",array($denominacion,$abreviatura))->setHydrationMode( Doctrine :: HYDRATE_ARRAY )->execute(); 
        return $roles; 
    }
    
   	static public function obtenerrolBuscado($filtroDominio, $denominacion, $limit, $start){
	    $query = Doctrine_Query::create();
	    $datos = $query ->select('DISTINCT (r.idrol),r.denominacion, r.abreviatura, r.descripcion')
	    				->from('SegRol r')
	    				->innerjoin('r.SegRolNomDominio rd')
	    				->where("r.denominacion like '%$denominacion%' and rd.iddominio =?",$filtroDominio)
	        			->orderby('r.idrol')
	    				->limit($limit)
	    				->offset($start)
	    				->execute();
	    return $datos;
    }
    
    static public function cantrolBuscados($filtroDominio, $denominacion){
        $query = Doctrine_Query::create();
        $cant = $query  ->select("count(r.idrol) as cant")
        				->from('SegRol r')
        				->innerjoin('r.SegRolNomDominio rd')
	    				->where("r.denominacion like '%$denominacion%' and rd.iddominio =?",$filtroDominio)
        				->execute();
        return $cant[0]->cant;
    }
    
    static public function eliminarRoles($idrol)
    {
        $query = Doctrine_Query::create();
        $query->delete()->from('SegRol')->where("idrol =?",$idrol)->execute();
        return true;
    } 
}
