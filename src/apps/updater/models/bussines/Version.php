<?php
class Version {
    private $name;
    private $values;
    
    public function __construct ($pName) {
        $this->name = $pName;
        $this->values = explode('.', substr($pName, 1));
    }
    
    public function getName() {
        return $this->name;
    }

    public function getValues() {
        return $this->values;
    }
    
    /**
     * @param Version $pParam
     * @return int
     */
    public function compareTo ($pVersion) {
        $my_values = $this->values; $other_values = $pVersion->getValues ();
        
         for ($i = 0; $i < count($my_values); $i++) {
             if ($my_values [$i] > $other_values [$i])
                 return 1;
             else if ($my_values [$i] < $other_values [$i]) {
                 return -1;
             }
         }
         
         return 0;
    }
}
?>
