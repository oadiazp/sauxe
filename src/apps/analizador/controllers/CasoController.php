<?php
    class CasoController extends ZendExt_Controller_Secure {
        private $_excepciones;
        private $_rendimiento;
        
        public function init () {
            parent :: init ();
            $this->_excepciones = new DatExcepcionModel ();
            $this->_rendimiento = new DatRendimientoModel();
        }

        public function casoAction () {
            $this->render();
        }
        
        public function rendimientoAction () {
            $excepciones = $this->_rendimiento->cargar ($this->getRequest()->getParam('start'),
                                                        $this->getRequest()->getParam('limit'));
            $cant = $this->_rendimiento->contar ();
            
            echo json_encode(array ('data' => $excepciones, 'cant' => $cant));
        }

        public function estructurasAction () {
            $security = ZendExt_Aspect_Security_Sgis::getInstance();
            $idestructuracomun = $this->_request->getPost('node');
            $dominios = $security->getDomain($idestructuracomun);
            if (count($dominios))
                echo json_encode($dominios);
            else
                echo json_encode(array());
        }

        public function tiposAction () {
            $result = array ();

            $result[] = array ('tipo' => 'Excepciones');
            $result[] = array ('tipo' => 'Rendimiento');

            echo json_encode(array ('data' => $result));
        }

        public function categoriasAction () {
            $json->data = NomCategoriaModel::cargarCategorias ()->toArray ();
            echo json_encode($json);
        }
        
        public function eliminarrendimientoAction () {
            $except  = Doctrine :: getTable ('DatRendimiento')->find ($this->getRequest()->getParam('idcaso'));
            $except->delete ();
            ZendExt_MessageBox::show('Rendimiento eliminada correctamente.', ZendExt_MessageBox::INFO);
        }
        
        public function eliminarexcepcionAction () {
            $except  = Doctrine :: getTable ('DatExcepcion')->find ($this->getRequest()->getParam('idcaso'));
            $except->delete ();
            ZendExt_MessageBox::show('Excepción eliminada correctamente.', ZendExt_MessageBox::INFO);
        }

        public function modificarexcepcionAction () {
            $except  = Doctrine :: getTable ('DatExcepcion')->find ($this->getRequest()->getParam('idcaso'));
            $except->mensaje = $this->getRequest()->getParam('mensaje');
            $except->codigo = $this->getRequest()->getParam('codigo');
            $except->idcategoria = (!is_numeric($this->getRequest()->getParam('categoria'))) ? Doctrine :: getTable('NomCategoria')->findOneByCategoria ($this->getRequest()->getParam('categoria'))->idcategoria
                                                                                             : $this->getRequest()->getParam('categoria');
            $except->idestructuracomun = $this->getRequest()->getParam('estructura');
            $except->denominacion = $this->getRequest()->getParam('denominacion');
            $except->respuesta = $this->getRequest()->getParam('respuesta');
            $except->save ();
            
            ZendExt_MessageBox::show('Excepción modificada correctamente.', ZendExt_MessageBox::INFO);
        }
        
        public function modificarrendimientoAction () {
            $except  = Doctrine :: getTable ('DatRendimiento')->find ($this->getRequest()->getParam('idcaso'));
            $except->tiempo = $this->getRequest()->getParam('tiempo');
            $except->memoria = $this->getRequest()->getParam('memoria');
            $except->idcategoria = (!is_numeric($this->getRequest()->getParam('categoria'))) ? Doctrine :: getTable('NomCategoria')->findOneByCategoria ($this->getRequest()->getParam('categoria'))->idcategoria
                                                                                             : $this->getRequest()->getParam('categoria');
            $except->idestructuracomun = $this->getRequest()->getParam('estructura');
            $except->denominacion = $this->getRequest()->getParam('denominacion');
            $except->respuesta = $this->getRequest()->getParam('respuesta');
            $except->save ();
            
            ZendExt_MessageBox::show('Rendimiento modificada correctamente.', ZendExt_MessageBox::INFO);
        }

        public function adicionarexcepcionAction () {
            $except = new DatExcepcion();
            $except->mensaje = $this->getRequest()->getParam('mensaje');
            $except->codigo = $this->getRequest()->getParam('codigo');
            $except->idcategoria = $this->getRequest()->getParam('categoria');
            $except->idestructuracomun = $this->getRequest()->getParam('estructura');
            $except->denominacion = $this->getRequest()->getParam('denominacion');
            $except->respuesta = $this->getRequest()->getParam('respuesta');
            $except->save ();
            
            ZendExt_MessageBox::show('Excepción adicionada correctamente.', ZendExt_MessageBox::INFO);
        }
        
        public function adicionarrendimientoAction () {
            $except = new DatRendimiento();
            $except->memoriamin = $this->getRequest()->getParam('memoria');
            $except->tiempomin = $this->getRequest()->getParam('tiempo');
            $except->memoriamax = $this->getRequest()->getParam('memoriamax');
            $except->tiempomax = $this->getRequest()->getParam('tiempomax');
            $except->idcategoria = $this->getRequest()->getParam('categoria');
            $except->idestructuracomun = $this->getRequest()->getParam('estructura');
            $except->denominacion = $this->getRequest()->getParam('denominacion');
            $except->respuesta = $this->getRequest()->getParam('respuesta');
            $except->save ();
            
            ZendExt_MessageBox::show('Rendimiento adicionado correctamente.', ZendExt_MessageBox::INFO);
        }
        
        public function excepcionesAction () {
            $excepciones = $this->_excepciones->cargar ($this->getRequest()->getParam('start'),
                                                        $this->getRequest()->getParam('limit'));
            $cant = $this->_excepciones->contar ();
            
            echo json_encode(array ('data' => $excepciones, 'cant' => $cant));
        }
    }