<?php

/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien García Tejo
 * @author Julio Cesar García Mosquera  
 * @version 1.0-0
 */
class DatSistemaSegRolDatFuncionalidadDatAccion extends BaseDatSistemaSegRolDatFuncionalidadDatAccion
{

    public function setUp() {
    parent::setUp();     
    $this->hasOne('DatAccion', array('local'=>'idaccion', 'foreign'=>'idaccion')); 
    $this->hasOne('DatSistemaSegRolDatFuncionalidad', array('local'=>'idsistema', 'foreign'=>'idsistema'));
    $this->hasOne('DatSistemaSegRolDatFuncionalidad', array('local'=>'idfuncionalidad', 'foreign'=>'idfuncionalidad'));    
    $this->hasOne('DatSistemaSegRolDatFuncionalidad', array('local'=>'idrol', 'foreign'=>'idrol')); 
    }

	static public function cargaraccionesquetiene($idsistema,$idrol,$idfuncionalidad,$limit,$start) {
	    $query = Doctrine_Query::create();
	    $datos = $query->select('a.idaccion, a.denominacion,a.idfuncionalidad')->from('DatAccion a,a.DatSistemaSegRolDatFuncionalidadDatAccion s')
	           ->where("s.idsistema = ? and s.idrol=? and s.idfuncionalidad=?",array($idsistema,$idrol,$idfuncionalidad))->limit($limit)->offset($start)->execute();          
	    return $datos;
	    }

	static public function todaslasaccionesquetiene($idsistema,$idrol,$idfuncionalidad) {
	     $query = Doctrine_Query::create();
	     $cantidad = $query->select('count(s.idaccion) cant')
	     				->from('DatSistemaSegRolDatFuncionalidadDatAccion s')
	                    ->where("s.idsistema = ?  and s.idrol = ? and s.idfuncionalidad = ?",array($idsistema,$idrol,$idfuncionalidad))->execute();          
	     return $cantidad[0]->cant;
	    }

	static public function cargaraccionesquenotiene($idsistema,$idrol,$idfuncionalidad) {
	     $query = Doctrine_Query::create(); 
	     $acciones = $query->select('a.idaccion, a.idfuncionalidad')->from('DatAccion a,a.DatSistemaSegRolDatFuncionalidadDatAccion s')
	                       ->where("s.idsistema = ? and s.idrol=? and s.idfuncionalidad=?",array($idsistema,$idrol,$idfuncionalidad))->execute();           
	     return $acciones;
	    }

	static function eliminaraccion($idsistema,$idfuncionalidad,$idrol,$accionesEliminar) {
	        $query = Doctrine_Query::create();
	        $query->delete()->from('DatSistemaSegRolDatFuncionalidadDatAccion r')
	        				->where("r.idsistema = ? and r.idfuncionalidad = ? and r.idrol = ?",array($idsistema,$idfuncionalidad,$idrol))
	                        ->whereIn('r.idaccion',$accionesEliminar)
	                        ->execute();           
	        return true;
	    } 
    
	static function eliminarAccionesAutorizadas($arrayAccEliminar,$rolesDominio) {
	        $query = Doctrine_Query::create();
	        $query->delete()->from('DatSistemaSegRolDatFuncionalidadDatAccion r')
	                        ->whereIn('r.idaccion',$arrayAccEliminar)
	                        ->whereIn('r.idrol',$rolesDominio)
	                        ->execute();           
	        return true;
	    }
  
}