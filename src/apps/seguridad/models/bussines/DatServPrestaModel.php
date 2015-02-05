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
  class DatServPrestaModel extends ZendExt_Model
  {
       public function DatServPrestaModel()
	   {
	      parent::ZendExt_Model();
	   }
	   
	   function insertarservicio($instance)
	   {
	     $instance->save();
	   }
       
	   function eliminarservicio($instance)
	   {
	     $instance->delete();
	   }
       
	   function modificarservicio($instance)
	   {
	     $instance->save();
	   }
	   
  
  }
?>