<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class HisIocexception extends BaseHisIocexception
{
	public function cantidad($idestructura, $idtipotraza, $idcategoria, $fecha_desde, $fecha_hasta, $campos)
	{
		$count = 0;
		$where = 't.idtipotraza = ?';
		$valores[$count] = $idtipotraza;
		if (isset($campos->idusuario) && $campos->idusuario)
		{
			$count++;
			$where .= ' AND t.usuario LIKE ?';
			$valores[$count] = '%'.$campos->idusuario.'%';
		}
		if ($idestructura != 0)
		{
			$count++;
			$where .= ' AND t.idestructuracomun = ?';
			$valores[$count] = $idestructura;			
		}
		if ($idcategoria != 0)
		{
			$count++;
			$where .= ' AND t.idcategoriatraza = ?';
			$valores[$count] = $idcategoria;			
		}
		if ($fecha_desde != 0)
		{
			$count++;
			$where .= ' AND fecha >= ?';
			$valores[$count] = $fecha_desde;
		}
		if ($fecha_hasta != 0)
		{
			$count++;
			$where .= ' AND fecha <= ?';
			$valores[$count] = $fecha_hasta;
		}
		$query = Doctrine_Query::create();
		$query = Doctrine_Query::create();
		return $query->select ('COUNT(t.idtraza) cantidad')
					 ->from('HisIocexception t')
					 ->where($where, $valores)
					 ->execute()
					 ->toArray();
	}
	
	public function select($idestructura, $idtipotraza, $idcategoria, $fecha_desde, $fecha_hasta, $offset, $limit, $campos)
	{ 
		$count = 0;
		$where = 't.idtipotraza = ?';
		$valores[$count] = $idtipotraza;
		if (isset($campos->idusuario) && $campos->idusuario)
		{
			$count++;
			$where .= ' AND t.usuario LIKE ?';
			$valores[$count] = '%'.$campos->idusuario.'%';
		}
		if ($idestructura != 0)
		{
			$count++;
			$where .= ' AND t.idestructuracomun = ?';
			$valores[$count] = $idestructura;			
		}
		if ($idcategoria != 0)
		{
			$count++;
			$where .= ' AND t.idcategoriatraza = ?';
			$valores[$count] = $idcategoria;			
		}
		if ($fecha_desde != 0)
		{
			$count++;
			$where .= ' AND fecha >= ?';
			$valores[$count] = $fecha_desde;
		}
		if ($fecha_hasta != 0)
		{
			$count++;
			$where .= ' AND fecha <= ?';
			$valores[$count] = $fecha_hasta;
		}
		$query = Doctrine_Query::create();
		return $query->select('t.*')
				 	 ->from('HisIocexception t')
				  	 ->where($where, $valores)
				  	 ->offset($offset)
				  	 ->limit($limit)
					 ->execute()->toArray(true);
	}
}