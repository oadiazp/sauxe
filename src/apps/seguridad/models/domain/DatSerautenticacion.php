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
class DatSerautenticacion extends BaseDatSerautenticacion
{

	public function setUp()
	  {
	    parent::setUp();    
	    $this->hasOne('DatServidor',array('local'=>'idservidor','foreign'=>'idservidor')); 
	    $this->hasMany('SegUsuario',array('local'=>'idservidor','foreign'=>'idusuario','refClass'=>'SegUsuarioDatSerautenticacion')); 
	  }
    static public function obtenercantidad($idservidor)
    {
		$query = Doctrine_Query::create();
		$cant = $query->from('SegUsuarioDatSerautenticacion us')
			  ->where("us.idservidor = ?", $idservidor)->count();                       
		return $cant;
    }
	static public function cargarservidoresauth($limit,$start)
	{
	            $q = Doctrine_Query::create();
	            if(($limit!=0))
	            $result = $q->select('s.idservidor id,s.idservidor, s.denominacion text,s.tiposervidor, s.descripcion, s.ip')->from('DatServidor s ')->orderby('idservidor')->limit($limit)->offset($start)->execute();
	            else 
	            $result = $q->select('s.idservidor id,s.idservidor, s.denominacion text')->from('DatServidor s')->innerJoin('s.DatServidorbd b')->orderby('s.idservidor')->execute();	
			    return $result;
		}
      
}