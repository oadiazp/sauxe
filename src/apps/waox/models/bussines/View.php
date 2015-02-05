<?php

/**
 * @access public
 */
abstract class View {    
    /**
     * @AssociationType Store
     * @AssociationMultiplicity 1
     * @AssociationKind Composition
     */
    public $store;
    public $xtype;
    public $code;

    public function __construct ($pXml, $pView)
    {
        $this->code = (string) $pXml ['code'];
        $pXml = $pXml->$pView->config;
        $store = new Store ($pXml);
        $this->store = $store;

        foreach ($pXml->attributes () as $att => $value) {
            $v = (string) $value;
            $this->$att = $v;
        }
    }
}
?>