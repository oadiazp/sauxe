<?php

/**
 * @access private
 */
class Table {
	/**
	 * @AttributeType string
	 */
	private $_name;	
	
	/**
	 * @AssociationType Field
	 * @AssociationKind Composition
	 */
	private $_fields;
	/**
	 * @AssociationType Relation
	 * @AssociationKind Composition
	 */
	private $_relations;
	/**
	 * @AssociationType Behaivor
	 * @AssociationKind Composition
	 */
	private $_behaivors;

        private $_parent_table;

        public function __construct() {
            $this->_fields = array ();
            $this->_relations = array ();
        }


        public function get_parent_table() {
            return $this->_parent_table;
        }

        public function set_parent_table($_parent_table) {
            $this->_parent_table = $_parent_table;
        }
        
        public function get_name() {
            return $this->_name;
        }

        public function set_name($_name) {
            $this->_name = $_name;
        }

        public function get_fields() {
            return $this->_fields;
        }

        public function set_fields($_fields) {
            $this->_fields = $_fields;
        }

        public function get_relations() {
            return $this->_relations;
        }

        public function set_relations($_relations) {
            $this->_relations = $_relations;
        }

        public function get_behaivors() {
            return $this->_behaivors;
        }

        public function set_behaivors($_behaivors) {
            $this->_behaivors = $_behaivors;
        }

        public function map_fields ($pConn) {
            return $pConn->get_fields ($this->_name);
        }

        public function map_relations ($pConn) {
            return $pConn->get_relations ($this->_name);
        }


        public function get_classname () {
            return self :: classname ($this->_name);
        }

        public static function classname ($pTable) {
            $tmp = substr($pTable, strpos($pTable, '.') + 1);
            $parts = explode('_', $tmp);

            if (!is_array($parts))
                $parts = array ($parts);

            $result = '';

            foreach ($parts as $part) {
                $result .= ucfirst($part);
            }

            return $result;
        }
        
        public function _component_to_array ($pAtt) {
            $result = array (); $values = $this->$pAtt;
            
            if (is_array($values))
                foreach ($values as $value) {
                    $result[] = $value->to_array ();
                }

            return $result;
        }
        
        public function relations_to_array () {
            $result = array (); $values = $this->_relations;

            if (is_array($values))
                foreach ($values as $value) {
                    if ($value->get_mappeable ())
                        $result[] = $value->to_array ();
                }

            return $result;
        }

        public function to_array () {
            return array (
                'name' => $this->get_classname (),
                'table' => $this->_name,
                'parent_table' => ($this->_parent_table) ? $this->_parent_table : '',
                'fields' => $this->_component_to_array ('_fields'),
                'relations' => $this->relations_to_array ('_relations'),
                'behaivors' => $this->_component_to_array ('_behaivors')
            );
        }

        function find_relation ($pRelation) {
            foreach ($this->_relations as $relation) {
                if ($relation->hash () == $pRelation->hash ()) 
                        return $relation;
            }

            return false;
        }

        function add_relation ($pRelation) {
            $this->_relations [] = $pRelation;
        }

        function rem_relation ($pIndex) {
            $this->_relations [$pIndex] = null;
            $this->_relations = array_values(array_filter($this->_relations));
        }

        function get_pk_field () {
            $fields = $this->_fields;

            foreach ($fields as $field) {
                if ($field->get_primary_key ())
                        return $field;
            }
        }
}
?>