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
class SegCertificado extends BaseSegCertificado
{

	public function setUp()
  	{
    	parent::setUp();    
    	$this->hasOne('SegUsuario',array('local'=>'idusuario','foreign'=>'idusuario'));   
  	}
  	
  	public function existecertificado($idusuario)
  	{
  	$query = Doctrine_Query::create();
	$certificado = $query->select('c.idcertificado, c.idusuario')->from('SegCertificado c')->where("c.idusuario = ?",$idusuario)->execute()->toArray(true);     			      
    return $certificado;
  	}
 		
	public function verificarcertificado($certificado)
	{
			$query = Doctrine_Query::create();
            $certificado = $query->select('idusuario,idcertificado')->from('SegCertificado')->where("valor = ?", $certificado)->execute();
            return $certificado;
	}
    
    static public function existcertificado($certificado) 
    {
            $query = Doctrine_Query::create();
            $certificado = $query->from('SegCertificado')->where("valor = ?", $certificado)->count();
            return $certificado;
    }

}