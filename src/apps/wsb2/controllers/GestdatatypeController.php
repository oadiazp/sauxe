<?php
class GestdatatypeController extends ZendExt_Controller_Secure {
    /**
     *
     * @var DatatypeModel
     */
    private $_datatype;
    
    public function init () {
        parent::init();
        $this->_datatype = new DatatypeModel ();
    }
    
    public function gestdatatypeAction () {
        $this->render ();
    }
    
    
        function datatypesAction () {
            $result = array ();

            $datatypes = (DatatypeFactory::getHash());

            foreach ($datatypes as $datatype) {
                if ($this->getRequest()->getParam('all')) {
                    $dt=$datatype->toArray (); $result[] = $dt; $dt = null;
                    $dt=array ('name' => $datatype->get_name () . '[]'); $result[] = $dt; $dt = null;
                } else {
                   if (! $datatype->getPrimary ()) {
                       $dt=$datatype->toArray (); $result[] = $dt; $dt = null;
                   }
               }
            }

            $json->datatypes = $result;
            echo json_encode($json);
        }

        function adddatatypeAction () {
            if (! $this->_datatype->exists ($this->getRequest()->getPost ('type'))) {
                $this->_datatype->add ($this->getRequest()->getPost ('type'));
                 ZendExt_MessageBox::show('Tipo de dato actualizado correctamente.', ZendExt_MessageBox::INFO);
            } else ZendExt_MessageBox::show('Ese tipo de dato ya existe.', ZendExt_MessageBox::ERROR);
        }

        function upddatatypeAction () {
            $prev = $this->getRequest()->getParam('prev');
            $datatype = $this->getRequest()->getParam('type');

            $this->_datatype->update ($prev, $datatype);
            ZendExt_MessageBox::show('Tipo de dato actualizado correctamente.', ZendExt_MessageBox::INFO);
        }

        function remdatatypeAction () {
            $this->_datatype->rem ($this->getRequest()->getParam('datatype'));
            ZendExt_MessageBox::show('Tipo de dato eliminado correctamente.', ZendExt_MessageBox::INFO);
        }

        function addfieldAction () {
            $this->_datatype->addField ($this->getRequest()->getParam('dt'),
                                                                         $this->getRequest()->getParam('name'),
                                                                         $this->getRequest()->getParam('datatype'));
            ZendExt_MessageBox::show('Campo  adicionado correctamente.', ZendExt_MessageBox::INFO);
        }

        function updfieldAction () {
            $this->_datatype->updField ($this->getRequest()->getParam('dt'),
                                                                         $this->getRequest()->getParam('name'),
                                                                         $this->getRequest()->getParam('datatype'),
                                                                        $this->getRequest()->getParam('index'));
            ZendExt_MessageBox::show('Campo  modificado  correctamente.', ZendExt_MessageBox::INFO);
        }

        function remfieldAction () {
            $this->_datatype->remField ($this->getRequest()->getParam('index'),
                                                                          $this->getRequest()->getParam('dt'));
            ZendExt_MessageBox::show('Campo  eliminado  correctamente.', ZendExt_MessageBox::INFO);
        }
    
    
}