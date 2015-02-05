<?php
    class DatatypeFactory {
        private static $_hash;
        private static $_xml;


        public function init ($pPath) {
            self :: $_xml = simplexml_load_file($pPath);

            if (!self::$_xml) {
                debug (libxml_get_last_error());
            }

            foreach (self ::$_xml->children () as $child) {
               $datatye = self::_load ($child); $name = $datatye->get_name ();

               if (!  isset (self::$_hash [$name]))
                self::$_hash [$name] = $datatye;
            }
        }

       /**
        *
        * @param SimpleXMLElement $pXml
        */
        private  static function _load ($pXml) {
            if (self::$_hash [$pXml->getName()])
                    return self::$_hash [$pXml->getName()];

            $datatype = new Datatype($pXml->getName());
            $datatype->setPrimary(isset ($pXml ['primary']));

            $fields = $pXml->children();

            foreach ($fields as $field) {
                $d = (string) $field ['datatype'];
                $f = new Field($field->getName(), $d);
                $datatype->addField($f);
            }

            return $datatype;
        }

        /**
         *
         * @param Datatype $pDatatype
         */
        public static function addDatatype ($pDatatype) {
            if (self::$_hash [$pDatatype->get_name()])
                    return;

            self::$_hash [$pDatatype->get_name()] = $pDatatype;
        }

        /**
         *
         * @param string $pString
         * @return Datatype
         */
        public static  function  parse ($pString) {
            if (! self :: $_hash [$pString])
                return;

           return self :: $_hash [$pString];
        }


        public static function updateDatatype ($pOldName, $pNewName) {
            self::$_hash [$pNewName] = self::$_hash [$pOldName];
            self::$_hash [$pOldName] = null; self :: $_hash = array_filter(self :: $_hash); 
        }


        public static  function removeDatatype ($pDatatype) {
             if ($pDatatype instanceof Datatype) {
                 self :: $_hash [$pDatatype->get_name()] = null;
             } else {
                 self :: $_hash [$pDatatype] = null;
             }

             self :: $_hash = array_filter(self :: $_hash);
        }

        public static function getHash () {
            return self::$_hash;
        }

        public static function toXml () {
            $xml = new SimpleXMLElement('<root> </root>');

            foreach (self::$_hash as $key => $value) {
                $child = $xml->addChild($key);

                if ($value->getPrimary ()) {
                    $child->addAttribute('primary', 'true');
                }

                if ($value->get_fields ()) {
                    $fields = $value->get_fields ();

                    foreach ($fields as $field) {
                        $f = $child->addChild($field->get_name ());
                        $f->addAttribute('datatype', $field->get_datatype ());
                    }
                }
            }

            return $xml;
        }

        public static function  datatypeExists ($pDatatype) {
            return isset (self::$_hash [$pDatatype]);
        }

        /**
         *
         * @param Datatype $pDatatype
         * @return Zend_CodeGenerator_Php_Class
         */
        public static function getClassFromDatatype ($pDatatype) {
            $result = new Zend_CodeGenerator_Php_Class();

            $result->setName($pDatatype->get_name());
            $fields = $pDatatype->get_fields();

            foreach ($fields as $field) {
                $property = new Zend_CodeGenerator_Php_Property();
                $docblock = new Zend_CodeGenerator_Php_Docblock();
                $tag = new Zend_CodeGenerator_Php_Docblock_Tag();
                $tag->setName('var')->setDescription($field->get_datatype ());
                $docblock->setTag($tag);

                $property->setVisibility('var');
                $property->setName($field->get_name ());
                $property->setDocblock($docblock);
                $result->setProperty($property);
            }

            return $result;
        }
    }
?>
