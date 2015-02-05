<?php
class ActivarModel extends ZendExt_Model {
    public function __construct() {
        parent :: ZendExt_Model();
    }
    
    public function cambiar ($pName, $pEnabled) {
        $lower = strtolower($pName);
        
        if ($pEnabled == '1')
            $sql = "ALTER TABLE mod_traza.his_$lower DISABLE TRIGGER \"t_analisis_$lower\";";
        else 
            $sql = "ALTER TABLE mod_traza.his_$lower ENABLE TRIGGER \"t_analisis_$lower\";";
        
//        die ($sql);
        
        $this->conn->exec ($sql);
    }
}
?>
