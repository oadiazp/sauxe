<?php

/**
 * @access public
 */
class Store {        
    public $root = 'data';
    public $xtype = 'jsonstore';
    public $fields = array ();

    

    public function __construct ($pXml)
    {
    	foreach ($pXml->attributes () as $att => $value) {    		

			if (eregi('Field$', $att)) {
				$v = (string) $value;
				$this->fields[] = array ('name' => $v);
			}
		}
    }

    public function toString ()
    {
    	return json_encode ($this);
    }
}
?>