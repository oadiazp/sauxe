<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Modulo extends BaseModulo
{
	public function setUp ()
	{
		$this->hasOne('Modulo as Padre', array('local'=>'idmodpadre', 'foreign'=>'idmodulo'));
		$this->hasMany('Modulo as Hijos', array ('local'=>'idmodulo','foreign'=>'idmodpadre'));
		$this->hasMany('Funcionalidad', array('local'=>'idmodulo','foreign'=>'idmodulo'));
	}
	
	static public function getArrayModulo($idnodo)
	{
		$q = Doctrine_Query::create();
        if ($idnodo)
            $result = $q->select('idmodulo id, nombre text, icono')
            			->from('Modulo')
            			->where("idmodpadre = ? and idmodpadre <> idmodulo", $idnodo)
            			->orderby('idmodulo')
            			->execute();
        else
            $result = $q->select('idmodulo id, nombre titulo, icono')
            			->from('Modulo')
            			->where("idmodpadre = idmodulo and idmodulo <> 0")
            			->orderby('idmodulo')
            			->execute();
        $resultado = $result->toArray();
	    return $resultado;
	}
	
	static public function getModulosPadres()
	{
        $q = Doctrine_Query::create();
        $result = $q->select('idmodulo, idmodpadre, nombre, icono')
        			->from('Modulo')
        			->where("idmodpadre = idmodulo and idmodulo <> 0")
        			->orderby('idmodulo')
        			->execute();
		return $result;
	}
	
	static public function getArrayFuncionalidad($idnodo)
	{
    	$q = Doctrine_Query::create();
        $result = $q->select("idfuncionalidad id, denominacion text, icono, true leaf, referencia")
        			->from('Funcionalidad')
        			->where("idmodulo = ?", $idnodo)
        			->orderby('idfuncionalidad')
        			->execute();
        $resultado = $result->toArray();
        $cant = count($resultado);
        for ($i = 0; $i < $cant; $i++)
            $resultado[$i]['referencia'] = '../../../' . $resultado[$i]['referencia'];
	    return $resultado;
	}
	
	static public function buscarUsuarioByAlias($alias)
    {
        $query = Doctrine_Query::create();
        $usuario = $query->select('idusuario, nombre, papell, sapell, alias, tema, idioma, portal')
           				 ->from('Usuario')
            			 ->where("alias = ?", $alias)
            			 ->orderby('alias')
            			 ->execute();
        return $usuario;
    }
}
