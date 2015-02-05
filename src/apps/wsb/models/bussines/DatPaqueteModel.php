<?php 
class DatPaqueteModel extends ZendExt_Model
 { 
   public function setUp() 
    { 
        parent::ZendExt_Model(); 
    } 
 
   public function Insertar(DatPaquete $DatPaquete)
    { 
            $DatPaquete->save();
    } 
 
   public function Actualizar(DatPaquete $DatPaquete)
    { 
            $DatPaquete->save();
    } 
 
   public function Eliminar($DatPaquete)
    { 
            $DatPaquete->delete();
    }

    public function GetAll(){
        $q = Doctrine_Query::create();
        return $q->select ('s.idpaquete, s.nombre as nombrepaquete')
              ->from ('DatPaquete s')
              ->execute ()->toArray();
    }

    public function GetbyIdsolution($idsolucion){
        $q = Doctrine_Query::create();
        return $q->select ('s.idpaquete, s.nombre as nombrepaquete')
              ->from ('DatPaquete s')
              ->where('s.idsolucion = ?',$idsolucion)
              ->execute ()->toArray();
    }

    public function AddPackage($datosBd)
    {
        $datPackage=new DatPaquete();
        $datPackage->idsolucion=$datosBd['idsolucion'];
        $datPackage->nombre=$datosBd['nombrepackage'];
        $datPackage->uri=$datosBd['uri'];
        $datPackage->save();
        $metodos=$datosBd['metodos'];
        
        foreach($metodos as $index=>$service)
        {
              $dat = new DatServicio();
              $servicio = $dat->GetDatServicebyURi($service['uri']);
              $relacion = new DatFachada();
              $relacion->idpaquete = $datPackage->idpaquete;
              if(!$servicio){
                  $servicio = new DatServicio();
                  $servicio->idestado=$service['idestado'];
                  $servicio->nombre=$service['nombremetodo'];
                  $servicio->uri=$service['uri'];
                  $servicio->descripcion=$service['descripcion'];
                  $servicio->save();
                  $relacion->idservicio = $servicio->idservicio;
              }
              else
              {
                  
                  $relacion->idservicio = $servicio[0]['idservicio'];
              }
              
              $relacion->save();
        }
    }
 }

