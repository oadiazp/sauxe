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
class NomIdiomaModel extends ZendExt_Model 
{

   public function NomIdiomaModel()
    {
    parent::ZendExt_Model();
    }
    
   function insertaridioma($idioma)
    {
    $idioma->save();
    }
   
   function modificaridioma($idioma)
    {
    $idioma->save();
    }
    
   function eliminaridioma($idioma)
    {
    $idioma->delete();
    }
}