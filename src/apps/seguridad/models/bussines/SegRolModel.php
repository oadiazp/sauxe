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
    class SegRolModel extends ZendExt_Model
      {
           public function SegRolModel()
           {
              parent::ZendExt_Model();
           }
           
           public function insertarrol($arraysistemarolfun,$arrayrolsist, $arrayobjacciones)
           {
                    foreach($arrayrolsist as $valorsist)
                        $valorsist->save();
                    foreach($arraysistemarolfun as $valorsitrolfun)
                        $valorsitrolfun->save();
                        if($arrayobjacciones)
                    foreach($arrayobjacciones as $valorobjacciones){
                        $valorobjacciones->save();} 
           }
                     
           public function modificarrol($rol_mod, $arraysistemasAdd, $arraysistemasElim, $arrayfuncaccionesAdd, $arrayfuncionalidadesElim, $rol)
           {
                $rol_mod->save();
                if(count($arrayfuncionalidadesElim))
                {
                    foreach($arrayfuncionalidadesElim as $valorfunEliminar) 
                    {    
                        DatSistemaSegRolDatFuncionalidad::eliminarfuncionalidad($rol,$valorfunEliminar[1],$valorfunEliminar[0]);
                    }
                } 
                 
                if(count($arraysistemasElim))
                {
                    foreach($arraysistemasElim as $sistemaElim) 
                    {
                      DatSistemaSegRol::eliminarrol($sistemaElim,$rol);
                    }                                             
                }
                             
                  if(count($arraysistemasAdd))
                  {
                    foreach($arraysistemasAdd as $rolsistemaAdd)
                    {
                        $rolsistemaAdd->save();
                    }
                  }
                  elseif (!count($arrayfuncaccionesAdd))
                  		throw new ZendExt_Exception('SEGROL01');
                   
                if(count($arrayfuncaccionesAdd))
                {
                    foreach($arrayfuncaccionesAdd as $valor)
                        {
                            if(count($valor))
                            foreach($valor as $valores)
                            {
                                $valores->save();
                            }
                        }  
                }    
           }
           
           function adicionaraccion($arrayAcciones)
           {
               foreach($arrayAcciones as $objAccion){ 
               $objAccion->save();}
           } 
      }
?>