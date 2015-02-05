<?php
    class MySQL extends Driver {
        public function getShortName() {
            return "mysql";
        }

        public function getTables($pDb) {
            $sql = 'show tables';
            $result = $this->_conn->get_doctrine ()->execute ($sql)->fetchAll (Doctrine::FETCH_ASSOC);

            $tables = array ();
            foreach ($result as $table) {
                $t = new Table();
                $t->set_name($table["Tables_in_$pDb"]);
                $tables[] = $t; $t = null;
            }
            return $tables;
        }


        public function get_fields($pTable) {
       
        $sql = "SHOW COLUMNS FROM $pTable ";

        $result = $this->_conn->get_doctrine ()->execute ($sql)->fetchAll (Doctrine::FETCH_ASSOC);

        $fields = array ();

        foreach ($result as $f) {
            $ff = new Field ();
            $ff->set_name ($f['Field']);
            $ff->set_null ($f['Null'] != 'NO');
            $ff->set_type ($f ['Type']);
			
            $r = $this->_conn->get_doctrine ()->execute ($s)->fetchAll (Doctrine::FETCH_ASSOC);
            
            $ff->set_primary_key ($r [0]['Key ']);

            $fields [] = $ff; $ff = null;
        }

        return $fields;
    	}
    	
    	
    	function get_relations($pTable) {
    		
    	}
    	
    }
?>
