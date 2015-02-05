<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class SegRolNomDominio extends BaseSegRolNomDominio
{

	public function setUp(){
	    parent::setUp();    
	    $this->hasOne('SegRol',array('local'=>'idrol','foreign'=>'idrol')); 
	    }

	static public function RolesdelDominio($filtroDominio){
        $query = Doctrine_Query::create();
        $roles = $query ->select('DISTINCT (rd.idrol) idrol')
        				->from('SegRolNomDominio rd')
        				->where("rd.iddominio = ?",$filtroDominio)
        				->setHydrationMode( Doctrine :: HYDRATE_ARRAY )
        				->execute();
        return $roles;
    }
}
