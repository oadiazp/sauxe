<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class HisTraza extends BaseHisTraza
{
	function setUp () {
		$this->hasOne ('NomCategoriatraza', array ('local' => 'idcategoriatraza',
							   'foreign' => 'idcategoriatraza'));

		$this->hasOne ('NomTipotraza', array ('local' => 'idtipotraza',
						      'foreign' => 'idtipotraza'));
	}
	
	static public function categoria($idtraza) 
	{
		$query = Doctrine_Query::create();
		$categoria = $query->select('t.idtraza, c.denominacion')
						   ->from('HisTraza t, t.NomCategoriatraza c')
						   ->where('t.idtraza = ?',$idtraza)
						   ->execute()
						   ->toArray(true);
		$query->free();
		return $categoria[0]['NomCategoriatraza']['denominacion'];
	}
}