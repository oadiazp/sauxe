<?php
    class DatatypeModel extends ZendExt_Model {
        function  __construct() {
            $dir = $_SERVER['SCRIPT_FILENAME'];
            $dir = str_replace('web', 'apps', $dir);
            $dir = explode('index.php', $dir);
            $this->_path = $dir[0].'comun/recursos/xml/datatypes.xml';

            DatatypeFactory::init ( $this->_path);

            parent :: ZendExt_Model();
        }

        private function _save () {
            DatatypeFactory::toXml()->asXML($this->_path);
        }

        function add ($pDatatype) {
            $datatype = new Datatype($pDatatype);

            DatatypeFactory::addDatatype($datatype);
            $this->_save();

            $this->updFile($datatype);
        }

        function all () {
            return array_values(DatatypeFactory::getHash());
        }

        function exists ($pDatatype) {
            return DatatypeFactory::datatypeExists($pDatatype);
        }

        private function updFile ($pDatatype) {
            $dir = $_SERVER['SCRIPT_FILENAME'];
            $dir = explode('index.php', $dir);
            $dir = str_replace('wsb2','ws', $dir [0]);

            $file  = new Zend_CodeGenerator_Php_File();
            $file->setClass(DatatypeFactory::getClassFromDatatype($pDatatype));

            $path = $dir . "datatypes/{$pDatatype->get_name ()}.php";
            file_put_contents($path, $file->generate());
        }

        function update ($pPrev, $pDatatype) {
            DatatypeFactory::updateDatatype($pPrev, $pDatatype);
            $this->_save();

            $datatype = DatatypeFactory::parse($pDatatype);
            $this->updFile($datatype);
        }

        function rem ($pDatatype) {
            $d = DatatypeFactory::parse($pDatatype);

            DatatypeFactory::removeDatatype($pDatatype);
            $this->_save();

            $dir = $_SERVER['SCRIPT_FILENAME'];
            $dir = explode('index.php', $dir);
            $dir = str_replace('wsb2','ws', $dir [0]);

            $path = "datatypes/{$d->get_name ()}.php";
            @unlink($path);
        }

        function addField ($pDt, $pName, $pDatatype) {
            $d = DatatypeFactory::parse($pDt);

            if ($d) {
                if (! $d->fieldExists($pName)) {
                    $f = new Field ($pName, $pDatatype);
                    $d->addField($f);
                    $this->_save();
                }
            }

            $this->updFile($d);
        }

        function updField ($pDt, $pName, $pDatatype, $pIndex) {
            $d = DatatypeFactory::parse($pDt);

            if ($d) {
                if (! $d->fieldExists($pName)) {
                    $d->set_field(new Field($pName, $pDatatype), $pIndex);
                    $this->_save();

                    $this->updFile($d);
                }
            }
        }

        function remField ($pIndex, $pDt) {
            $d = DatatypeFactory::parse($pDt);

            if ($d) {
                $ff  = $d->get_fields();
                $ff [$pIndex] = null;
                $ff = array_filter($ff);
                $d->set_fields($ff);
                $this->_save();

                $this->updFile($d);
            }
        }
    }
?>
