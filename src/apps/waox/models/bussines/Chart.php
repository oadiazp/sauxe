<?php

/**
 * @access public
 */
class Chart {
    private $name;
    private $eventCode;
    /**
     * @AssociationType Dashboard
     * @AssociationMultiplicity 1
     */
    public $charts;
    /**
     * @AssociationType View
     * @AssociationMultiplicity 1..*
     * @AssociationKind Composition
     */
    public $view = array();
}
?>