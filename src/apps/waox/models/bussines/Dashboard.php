<?php

/**
 * @access public
 */
class Dashboard {
    /**
     * @AssociationType Chart
     * @AssociationMultiplicity 1..*
     * @AssociationKind Composition
     */
    public $charts = array();
    /**
     * @AssociationType Configuration
     * @AssociationMultiplicity 1
     * @AssociationKind Composition
     */
    public $config;
}
?>