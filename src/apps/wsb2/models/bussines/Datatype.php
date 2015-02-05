<?php
    class Datatype {
        /**
         *
         * @var string
         */
        private $_name;

        /**
         *
         * @var Field[]
         */
        private $_fields;

        /**
         *
         * @var bool
         */
        private $_primary;

        public function setPrimary ($pPrimary) {
            $this->_primary = $pPrimary;
        }

        public function getPrimary () {
            return $this->_primary;
        }

        public function  __construct($pName, $pFields = array (), $pPrimary = false) {
            $this->_name = $pName;
            $this->_fields = $pFields;
            $this->_primary = $pPrimary;
        }

        /**
         *
         * @param Field $pField
         */
        public function addField ($pField) {
            $this->_fields [] = $pField;
        }

        /**
         *
         * @param int $pIndex
         * @param Field $pField
         */
        public function updField ($pIndex, $pField) {
            $this->_fields [$pIndex] = $pField;
        }

        /**
         *
         * @param int $pIndex
         */
        public function remField ($pIndex) {
            $this->_fields [$pIndex] = null;
            $this->_fields = array_filter($this->_fields);
        }

        /**
         *
         * @return SimpleXMLElement
         */
        public function toXml () {
            $result = new SimpleXMLElement();

            foreach ($this->_fields as $field) {
                $child = $result->addChild($field->get_name ());
                $child->addAttribute('datatype', $field->get_datatype ());
            }

            return $result;
        }

        /**
         *
         * @return string
         */
        public function get_name() {
            return $this->_name;
        }

        public function get_field ($pIndex) {
            return $this->_fields [$pIndex];
        }

        public function set_field ($pField, $pIndex) {
            $this->_fields [$pIndex] = $pField;
        }

        public function set_fields($_fields) {
            $this->_fields = $_fields;
        }

        /**
         *
         * @return Field[]
         */
        public function get_fields() {
            return $this->_fields;
        }

        /**
         *
         * @return array
         */
        public function toArray () {
            $json->name = $this->_name;
            $fields = array ();

            foreach ($this->_fields as $field) {
                $fields[] = $field->toArray ();
            }

            $json->fields = $fields;

            return $json;
        }

        function fieldExists ($pName) {
            foreach ($this->_fields as $field) {
                if ($field->get_name () == $pName)
                        return true;
            }

            return false;
        }
    }
?>
