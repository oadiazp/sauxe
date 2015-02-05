<?php
require_once(realpath(dirname(__FILE__)) . '/Driver.php');

/**
 * @access public
 */
class ODBC extends Driver {
	
		public function  __construct($pConn) {
			parent :: __construct ($pConn);
		}
        public function getShortName() {

        }

        public function getTables($pDb) {

        }

        public function get_fields($pTable) {

        }

        public function get_relations($pTable) {

        }

    }
?>
