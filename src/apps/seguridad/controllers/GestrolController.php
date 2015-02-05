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
    class GestrolController extends ZendExt_Controller_Secure
    {
        function init ()
        {
            parent::init();
        }
                
        function gestrolAction()
        {
            $this->render();
        }
        
        function insertarrolAction() {
            $rol = new SegRol();            
            $rol->denominacion = $this->_request->getPost('denominacion');
            $rol->descripcion = $this->_request->getPost('descripcion'); 
            $rol->abreviatura = $this->_request->getPost('abreviatura');
            if($this->verificarrol($rol->denominacion, ''))
                throw new ZendExt_Exception('SEG018'); 
            if($this->verificarrol('', $rol->abreviatura))
                throw new ZendExt_Exception('SEG019');

            $rol->save();

	    $objRolDominio = new SegRolNomDominio();
            $objRolDominio->idrol =  $rol->idrol;
            $objRolDominio->iddominio =  $this->global->Perfil->iddominio;
            $objRolDominio->save();		

            $identidad = $this->_request->getPost('identidad');     
            $array_sistfun = $this->_request->getPost('arraysistfun');
            $array_sistemas = $this->_request->getPost('arraysist');
            $sistfun = json_decode(stripslashes($array_sistfun));
            $sistemas = json_decode(stripslashes($array_sistemas)); 
            foreach ($sistemas as $valorsist)
            {      
                $rol_sist = new DatSistemaSegRol();
                $rol_sist->idsistema = $valorsist;
                $rol_sist->idrol = $rol->idrol ;
                $arrayrolsist[] = $rol_sist;                 
            }
            $arrayobjacciones = array(); 
            foreach ($sistfun as $valorsistfun)                      
                foreach($valorsistfun[1] as $fun)
                {  
                    $sistema_rol_fun = new DatSistemaSegRolDatFuncionalidad() ;                           
                    $sistema_rol_fun->idfuncionalidad = $fun;
                    $sistema_rol_fun->idrol=$rol->idrol;
                    $sistema_rol_fun->idsistema= $valorsistfun[0];
                    $arraysistemarolfun[] = $sistema_rol_fun;
                    $acciones = DatAccion::cargaraccion($fun,0,0) ;                       
                    $arrayacciones = $acciones->toArray();                     
                    if(count($arrayacciones))
                    {
                        foreach($arrayacciones as $accion)
                        {  
                            $objaccion = new DatSistemaSegRolDatFuncionalidadDatAccion();
                            $objaccion->idfuncionalidad = $fun;
                            $objaccion->idrol=$rol->idrol;
                            $objaccion->idsistema = $valorsistfun[0];
                            $objaccion->idaccion = $accion['idaccion'];  ;                            
                            $arrayobjacciones[] =  $objaccion;
                        }
                    }                                              
                }
            $rolmodel= new SegRolModel();     
            $rolmodel->insertarrol($arraysistemarolfun, $arrayrolsist, $arrayobjacciones);             
            $this->showMessage('El rol fue insertado satisfactoriamente.');       
        }
        
        
        function eliminarrolAction() {
            $roles = json_decode(stripslashes($this->_request->getPost('arrayRolesElim')));
            $bandera = false;
            foreach($roles as $idrol)
            {
             SegRol::eliminarRoles($idrol);
             $bandera = true;
            }
            if($bandera)
                $this->showMessage('Rol(es) eliminado(s) satisfactoriamente.'); 
        }
        
        function sistemasAdd($arrayGeneral, $arrayNuevo, $rol) {
        	$resultado = array();
            foreach($arrayNuevo as $valor)
            {
                $pos = $this->existesistema($arrayGeneral, $valor);
                if($pos == -1)
                {
                    $rol_sist = new DatSistemaSegRol() ;
                    $rol_sist->idsistema = $valor;
                    $rol_sist->idrol = $rol;

                    $resultado[] = $rol_sist;   
                }
            }
            return $resultado;             
        }
        
        function funcionalidadesAdd($arraysistfun, $rol) {
        	$array_rol_sist_func = array();
        	$arrayobjacciones = array();
            foreach($arraysistfun as $key=>$valor)
            {
                $funcionaludades = DatSistemaSegRolDatFuncionalidad::obtenerfunrol($rol,$valor[0]);  
                $arrayfuncionalidades = $funcionaludades->toArray(true);
                foreach($valor[1] as $funcionalidad)
                {  
                    $pos = $this->existefuncionalidad($arrayfuncionalidades,$funcionalidad);
                    if($pos == -1)
                    {
                        $rol_sist_func = new DatSistemaSegRolDatFuncionalidad() ;
                        $rol_sist_func->idsistema = $valor[0];
                        $rol_sist_func->idrol = $rol;
                        $rol_sist_func->idfuncionalidad = $funcionalidad;
                        $array_rol_sist_func[] = $rol_sist_func; 
                        $acciones = DatAccion::cargaraccion($funcionalidad,0,0) ;                       
                        $arrayacciones = $acciones->toArray();
                        if(count($arrayacciones))
                        {
                            foreach($arrayacciones as $accion)
                            {  
                                $objaccion = new DatSistemaSegRolDatFuncionalidadDatAccion();
                                $objaccion->idfuncionalidad = $funcionalidad;
                                $objaccion->idrol = $rol;
                                $objaccion->idsistema = $valor[0];
                                $objaccion->idaccion = $accion['idaccion'];                          
                                $arrayobjacciones[] =  $objaccion;
                            }
                        }  
                    }       
                }
            }      
            return $resultado[] = array($array_rol_sist_func,$arrayobjacciones);
        }
        
        function sistemasElim($arrayGeneral, $arrayNuevo) {
            if(!count($arrayNuevo))
                return null;
            foreach($arrayGeneral as $arrValor)
            {
                $pos = array_search($arrValor['idsistema'], $arrayNuevo);
                if($pos === false)
                {
                    $resultado[] = $arrValor['idsistema'];   
                }
            }
            return $resultado;             
        }
        
        function funcionalidadesElim($arraysistfun, $rol) {
        	$resultado = array();
            foreach($arraysistfun as $key=>$valor)
            {
                $funcionalidades = DatSistemaSegRolDatFuncionalidad::obtenerfunrol($rol,$valor[0]);  
                $arrayfuncionalidades = $funcionalidades->toArray(true);
                foreach($arrayfuncionalidades as $arrFunc)
                {
                    $pos = array_search($arrFunc['idfuncionalidad'],$valor[1]);
                    if($pos === false)                 
                        $resultado[] = array($arrFunc['idsistema'],$arrFunc['idfuncionalidad']); 
                }
            }            
            return $resultado;             
        }
        
        function modificarrolAction() {
                $identidad = $this->_request->getPost('identidad');
                $rol = $this->_request->getPost('idrol');
                $denominacion = $this->_request->getPost('denominacion');
                $abreviatura = $this->_request->getPost('abreviatura'); 
                $rol_mod = Doctrine::getTable('SegRol')->find($rol);
		$sistfun = json_decode(stripslashes($this->_request->getPost('arraysistfun')));
                $sistemas = json_decode(stripslashes( $this->_request->getPost('arraysist')));
                $arrayeliminar = json_decode(stripslashes($this->_request->getPost('arrayeliminar')));
                
                if($rol_mod->denominacion != $denominacion)
                {
                    if($this->verificarrol($denominacion, ''))
                        throw new ZendExt_Exception('SEG018'); 
                }
                if($rol_mod->abreviatura != $abreviatura)
                {
                    if($this->verificarrol('', $abreviatura))
                        throw new ZendExt_Exception('SEG019');
                }   
                $rol_mod->denominacion = $denominacion;
                $rol_mod->descripcion = $this->_request->getPost('descripcion'); 
                $rol_mod->abreviatura = $abreviatura;
                
                if(!count($sistemas) && count($arrayeliminar))
                {     
                    $cantidadrolsistemas = DatSistemaSegRolDatFuncionalidad::cantidadsisrol($rol);

                    if($cantidadrolsistemas <= count($arrayeliminar))
                    {
                        throw new ZendExt_Exception('SEG012');
                    }
                    else
                    {     
                        foreach($arrayeliminar as $valor)
                        {
                            DatSistemaSegRol::eliminarrolsistema($rol,$valor);
                        }
                    }
                    $this->showMessage('El rol fue modificado satisfactoriamente.');
                    return;
                }
                else
                {              
                    if(count($arrayeliminar))
                    {
                        foreach($arrayeliminar as $valor)
                            DatSistemaSegRol::eliminarrolsistema($rol,$valor); 
                    }
                    
                    $todoslossistemas = DatSistemaSegRol::sistemasdadorol($rol);
                    $arraySistemas = $todoslossistemas->toArray(true);                              
                    $arraysitemasAdd = $this->sistemasAdd($arraySistemas, $sistemas, $rol);
                    
                    $arrayfuncaccionesAdd = $this->funcionalidadesAdd($sistfun,$rol);
                    $arrayfuncionalidadesElim = $this->funcionalidadesElim($sistfun,$rol);   
		    if($rol == '10000000001')
			$this->validarFuncAdministracion($arrayfuncionalidadesElim);                        
                    $rolmodel_mod = new SegRolModel();         
                    $rolmodel_mod->modificarrol($rol_mod, $arraysitemasAdd, $arrayeliminar, $arrayfuncaccionesAdd, $arrayfuncionalidadesElim, $rol);            
                    $this->showMessage('El rol fue modificado satisfactoriamente.');
                    return;               
                }
        }
		
	private function validarFuncAdministracion($array) {
		foreach($array as $valores) {
			if($valores[1] == '19')
			throw new ZendExt_Exception('SEGROL01');
		}
	}        

        function verificarrol($denominacion,$abreviatura) {
         $rol = SegRol::comprobarrol($denominacion,$abreviatura);
         if($rol[0]['idrol'])
            return $rol;
         else 
           return 0;
        } 
        
        function cargarrolAction() {
            $start = $this->_request->getPost("start");
            $limit = $this->_request->getPost("limit");
            $denominacion = $this->_request->getPost("denrol");
	    $filtroDominio = $this->global->Perfil->iddominio;
            if($denominacion) {
                $result = SegRol::obtenerrolBuscado($filtroDominio, $denominacion, $limit, $start);
                $cantrol = SegRol::cantrolBuscados($filtroDominio, $denominacion);
                }
            else {
                $result = SegRol::obtenerrol($filtroDominio, $limit, $start);
                $cantrol = SegRol::cantrol($filtroDominio);
                }
            if(count($result)) {
                foreach($result as $key=>$rol) { 
                    $arrayroles[$key]['idrol'] = $rol->idrol; 
                    $arrayroles[$key]['denominacion'] = $rol->denominacion;
                    $arrayroles[$key]['abreviatura'] = $rol->abreviatura;
                    $arrayroles[$key]['descripcion'] = $rol->descripcion;
                }                 
                $result = array ('cantidad_filas' => $cantrol, 'datos' => $arrayroles); 
                echo json_encode($result);return;
            }
            else
              echo json_encode($result);return; 
        }
        
        function tieneFuncionalidades($idsistema, $rol)
        {
            return DatSistemaSegRolDatFuncionalidad::tieneFuncionalidad($rol, $idsistema); 
        }
        
        function cargarsistemafuncionalidadesAction() {
            $idnodo = $this->_request->getPost('node');
            $idsistema = $this->_request->getPost('idsistema');
            $rol = $this->_request->getPost('idrol');
            if($idnodo == 0) {
                    $sistemas = DatSistema::cargarArbolSistemasCompartimentacion($idnodo);
                    if(!count($sistemas)){ 
                            echo json_encode (array());return;
                    }
                }
            else 
                $sistemas = DatSistema::cargarArbolSistemasCompartimentacion($idsistema);                
            $a = 0;  
            if(count($sistemas))
                {
                    $sistemafunArr = array();    
                    foreach ($sistemas as $valor)
                    {                    
                        $sistemafunArr[$a]['id'] = $valor->id.'_'.$idnodo;
                        $sistemafunArr[$a]['idsistema'] = $valor->id;
                        $sistemafunArr[$a]['text'] = $valor->text;
                        if($this->tieneFuncionalidades($valor->id, $rol))
                            $sistemafunArr[$a]['tiene'] = 1;
                        if($valor->leaf) {
                        	if (!DatFuncionalidad::obtenercantfuncCompart($valor->id))
                        		$sistemafunArr[$a]['leaf'] = true;
                        	else
                        		$sistemafunArr[$a]['leaf'] = false;
                        } else $sistemafunArr[$a]['leaf'] = false;
                        $a ++;   
                    }
                    
                }
		
                if ($idsistema != 0)
                {
                $funcionalidad = DatFuncionalidad::cargarFuncionalidadesCompart($idsistema);   
                    if($funcionalidad->getData()!=NULL)
                    {
                        foreach ($funcionalidad as $valor)
                        {
                            $sistemafunArr[$a]['id'] = $valor->id.'_'.$idnodo;
                            $sistemafunArr[$a]['idfuncionalidad'] = $valor->id;
                            $sistemafunArr[$a]['text'] = $valor->text;
                            $sistemafunArr[$a]['leaf'] = true;
                            if($rol)
                                $sistemafunArr[$a]['checked'] = $this->chequear($valor->id,$rol,$idsistema)?true:false;
                            else
                                $sistemafunArr[$a]['checked'] =false;
                          $a++;
                        }
                    }
                } 
            echo json_encode ($sistemafunArr);return;      
        }
        
        function cargarentidadesAction()
        {           
            $idrol = $this->_request->getPost('idrol');
            if($idrol)
            {
                $entidadesrol = DatSistemaDatEntidadSegRol::cargarentidadesrol($idrol);
                $datosentidad = $this->integrator->metadatos->MostrarCamposEstructuraSeguridad($entidadesrol[0]->identidad);
                $arrayentidades[0]['id'] = $datosentidad[0]['id']; 
                $arrayentidades[0]['text'] = $datosentidad[0]['text'];
                $arrayentidades[0]['leaf'] = true;                                  
            }
            else
            {
                $entidadesbd = DatSistemaDatEntidad::cargarentidades();
                foreach($entidadesbd as $key=>$entidad)
                { 
                    $datosentidad = $this->integrator->metadatos->MostrarCamposEstructuraSeguridad($entidad->identidad);
                    $arrayentidades[$key]['id'] = $datosentidad[0]['id']; 
                    $arrayentidades[$key]['text'] = $datosentidad[0]['text'];
                    $arrayentidades[$key]['leaf'] = true;                               
                }
            }
            echo json_encode($arrayentidades);return;     
        }
    
        function chequear($id,$rol,$idsistema)
        {
            $funrol=DatSistemaSegRolDatFuncionalidad::obtenerfunrol($rol,$idsistema);
            $arreglo=$funrol->toArray();         
            foreach($arreglo as $val)
            {
                if($val['idfuncionalidad'] == $id)
                    return true;                    
            }
            return false;
        }
        
        function existesistema($arraysistemas,$sistema)
        {  
            foreach ($arraysistemas as $key => $sist)
            {    
                            
                if($sist['idsistema'] == $sistema)                                                        
                    return $key; 
            }
                                    
             return -1;
        }
        
        function existefuncionalidad($arrayfun,$fun)
        {
            foreach ($arrayfun as $key => $funcionalidad)                    
                if($funcionalidad['idfuncionalidad'] == $fun)                                                        
                    return $key;                 
             return -1;
        } 
        
        function cargaraccionesquetieneAction() {
            $idsistema = $this->_request->getPost('idsistema');
            $idrol = $this->_request->getPost('idrol');
            $idfuncionalidad = $this->_request->getPost('idfuncionalidad');
            $limit = $this->_request->getPost('limit');
            $start = $this->_request->getPost('start');
            $datoaccion = DatSistemaSegRolDatFuncionalidadDatAccion::cargaraccionesquetiene($idsistema,$idrol,$idfuncionalidad,$limit,$start);
            $cantfilas = DatSistemaSegRolDatFuncionalidadDatAccion::todaslasaccionesquetiene($idsistema,$idrol,$idfuncionalidad);
            $datos = $datoaccion->toArray();
            $result =  array('cantidad_filas' => $cantfilas, 'datos' => $datos);
            echo json_encode($result);return;
        } 
        
        function cargarsistemafuncAction() {
            $idnodo = $this->_request->getPost('node');
            $idsistema = $this->_request->getPost('idsistema');
            $rol = $this->_request->getPost('idrol');
            if($idnodo == 0 || $idsistema)
            {                       
                if($idnodo == 0)
                    $sistemas = DatSistema::cargarsistemasdelrol($idnodo, $rol);
                else 
                    $sistemas = DatSistema::cargarsistemasdelrol($idsistema, $rol);                   
                if(count($sistemas))
                {
                    foreach ($sistemas as $valores=>$valor)
                    {
                        $sistemaArr[$valores]['id'] = $valor['id'].'_'.$idnodo;
                        $sistemaArr[$valores]['idsistema'] = $valor['id'];
                        $sistemaArr[$valores]['text'] = $valor['text'];                            
                    }
                    echo json_encode ($sistemaArr);return;
                }                                    
                else
                {
                    $funcionalidad = DatSistemaSegRolDatFuncionalidad::cargarsist_funcionalidades($idsistema,$rol);
                    if($funcionalidad->getData()!=NULL)
                    {
                        foreach ($funcionalidad as $valores=>$valor)
                        {
                            $funcionalidadArr[$valores]['id'] = $valor->id.'_'.$idnodo;
                            $funcionalidadArr[$valores]['idfuncionalidad'] = $valor->id;
                            $funcionalidadArr[$valores]['text'] = $valor->text;
                            $funcionalidadArr[$valores]['idsistema'] = $valor->idsistema;
                            $funcionalidadArr[$valores]['leaf'] = true;                           
                        }
                        echo json_encode ($funcionalidadArr);
                        return;
                    }
                    else
                    {
                        $func=$funcionalidad->toArray();
                        echo json_encode ($func);return;
                    }
                }
            }         
        }
        
        function cargaraccionesquenotieneAction() {
            $idsistema = $this->_request->getPost('idsistema');
            $idfuncionalidad = $this->_request->getPost('idfuncionalidad');
            $idrol = $this->_request->getPost('idrol');
            $limit = $this->_request->getPost('limit');
            $start = $this->_request->getPost('start');
            $acciones = DatAccion::cargarAcciCompartimentacion($idfuncionalidad, $idrol);
            $arrayAcc = $this->arregloBidimensionalToUnidimensional($acciones);
            $accionesNoTiene = DatAccion::cargarAccionesNoTiene($idfuncionalidad, $arrayAcc, $limit, $start)->toArray();
            $cantidad = count($accionesNoTiene);
            echo json_encode(array('cantidad_filas' => $cantidad, 'datos' => $accionesNoTiene));
        }
        
	function arregloBidimensionalToUnidimensional($arrayAcciones) {
		$array = array();
		foreach ($arrayAcciones as $acciones)
			$array[] = $acciones->idaccion;
		return $array;
		}
       
        function adicionaraccionAction() {
           $idsistema = $this->_request->getPost('idsistema');
           $idfuncionalidad = $this->_request->getPost('idfuncionalidad');
           $idrol = $this->_request->getPost('idrol');   
           $accs = json_decode(stripslashes($this->_request->getPost('idaccion')));
            foreach($accs as $idaccion) {
		    $accion = new DatSistemaSegRolDatFuncionalidadDatAccion();
		    $accion->idsistema = $idsistema;
		    $accion->idfuncionalidad = $idfuncionalidad;
		    $accion->idrol = $idrol;
		    $accion->idaccion = $idaccion;
		    $acciones[] = $accion;
		    }
            $model = new SegRolModel();
            $model->adicionaraccion($acciones);
            echo "{'tiene':1}";            
            }

        function eliminaraccionAction() {
            $idsistema = $this->_request->getPost('idsistema');
            $idrol = $this->_request->getPost('idrol');
            $idfuncionalidad = $this->_request->getPost('idfuncionalidad');
            $accionesEliminar = json_decode(stripslashes($this->_request->getPost('idaccion')));
	    if($idrol == '10000000001')
		$this->verificarAcciones($accionesEliminar);
            DatSistemaSegRolDatFuncionalidadDatAccion::eliminaraccion($idsistema,$idfuncionalidad,$idrol,$accionesEliminar);
            echo "{'tiene':1}";            
        }

	private function verificarAcciones($accionesEliminar) {
		foreach($accionesEliminar as $valor)
		   if($valor == '70')
			throw new ZendExt_Exception('SEGROL02');
	}
    }
?>
