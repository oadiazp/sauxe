<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class NomCategoriatraza extends BaseNomCategoriatraza
{
	function setUp ()
	{
		$this->hasMany ('HisTraza', array ('local' => 'idcategoriatraza',
										  'foreign' => 'idcategoriatraza'));
	}
	
	static public function selectAllcategoria()
	{
		$query = Doctrine_Query::create();
		  return  $query->select('c.idcategoriatraza, c.denominacion')
						->from('NomCategoriatraza c')
						->execute()->toArray();
	}
}
?>