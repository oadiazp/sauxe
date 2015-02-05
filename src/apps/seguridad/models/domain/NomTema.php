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
class NomTema extends BaseNomTema
{

	public function setUp()
	  {
	    parent::setUp();    
	    $this->hasMany('SegUsuario',array('local'=>'idtema','foreign'=>'idtema'));	     
	  }

	static public function cargarnomtema($limit,$start)
	{
		$query = new Doctrine_Query ();		
	    $result = $query ->select('t.idtema,t.denominacion,t.abreviatura, t.descripcion')->from('NomTema t')->limit($limit)
	        			 ->offset($start)
	        			 ->execute();	
	    return $result;        				 
	}
    
	static public function obtenercantnomtema()
	{
		$query = new Doctrine_Query ();		
	    $cant = $query ->from('NomTema')->count();	
	    return $cant;        				 
	}
    
    static  public  function comprobartema($denominacion,$abreviatura)
    {
    $query = Doctrine_Query::create();
    $cantidadtema = $query->from('NomTema')->where("denominacion = ? OR abreviatura = ?",array($denominacion,$abreviatura))->count();                      
    return $cantidadtema;
    }
    
	static public function cargarcombotema()
	{
		$query = new Doctrine_Query ();		
	    $result = $query ->select('t.idtema,t.denominacion')->from('NomTema t')->execute();	
	    return $result;        				 
	}
    
    static public function cargarTema()
    {
        $query = new Doctrine_Query ();        
        $result = $query ->select('t.idtema')->from('NomTema t')->execute();    
        return $result;                         
    }
}