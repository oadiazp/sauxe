<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class DatAccionDatReporte extends BaseDatAccionDatReporte
{
	public function setUp()
    {
    parent::setUp();
    $this->hasOne('DatAccion',array('local'=>'idaccion','foreign'=>'idaccion'));    
    }
    
	static public function cargaraccionesasociadasrep($idaccion) {
        $query = Doctrine_Query::create();
        $acciones = $query->select('a.idaccion,a.idreporte, a.denominacion')->from('DatAccionDatReporte a')->where('a.idaccion = ?',$idaccion)->execute()->toArray(true);
        return $acciones;
	}

	static public function eliminar($idaccion,$arrayrepaccelim) {
        $query = Doctrine_Query::create();
        $query	->delete()
        		->from('DatAccionDatReporte')
        		->where('idaccion = ?',$idaccion)
        		->whereIn('idreporte',$arrayrepaccelim)
        		->execute();
        return;
	}
}