<?php

/**
 * @access public
 */
class ColumnView extends View {
    public $xField;
    public $yField;
    public $yAxis;
    public $xtype = 'waox.chrLineChart';
    public $store;

    public function __construct ($pXml, $pView)
    {
    	parent :: __construct ($pXml, $pView);
    }
}
?>