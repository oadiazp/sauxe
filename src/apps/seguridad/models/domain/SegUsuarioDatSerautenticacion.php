<?php

/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien García Tejo
 * @author Julio Cesar García Mosquera  
 *@author Noel Jesus Rivero Pino
 * @version 1.0-0
 */
class SegUsuarioDatSerautenticacion extends BaseSegUsuarioDatSerautenticacion
{

  	static public function eliminarusuarioservidoraut($idusuario)
  	{
            $q = Doctrine_Query::create();  
            $q->delete()->from('SegUsuarioDatSerautenticacion')->where("idusuario=? ",$idusuario)->execute();           
  	}
	
    static public function cantservidoraut($idusuario)
    {
    $q = Doctrine_Query::create();   
    $result = $q->select('s.idservidor')->from('SegUsuarioDatSerautenticacion s')->where("s.idusuario = ?",$idusuario)->execute();
    return $result;
    }	
	static public function obtenercantservuser($idservidor)
    {
		$query = Doctrine_Query::create();
        $cantServUsers = $query->from('DatSerautenticacion sa, sa.SegUsuarioDatSerautenticacion su')
		    ->where("su.idservidor = ?", $idservidor)
		    ->count();            
            return $cantServUsers;
    }

    
}