<?php
    class Field {
        /**
         *
         * @var string
         */
        private $_name;

        /**
         *
         * @var Datatype
         */
        private $_datatype;

        public function  __construct($pName, $pDatatype) {
            $this->_datatype = $pDatatype;
            $this->_name = $pName;
        }

        /**
         *
         * @return string
         */
        public function get_name() {
            return $this->_name;
        }

        /**
         *
         * @return Datatype
         */
        public function get_datatype() {
            return $this->_datatype;
        }

        /**
         *
         * @param string $_name
         */
        public function set_name($_name) {
            $this->_name = $_name;
        }

        /**
         *
         * @param Datatype $_datatype
         */
        public function set_datatype($_datatype) {
            $this->_datatype = $_datatype;
        }

        public function toArray () {
            return array ('name' => $this->_name, 'datatype' => $this->_datatype);
        }
    }
?>
