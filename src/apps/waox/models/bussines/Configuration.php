<?php

/**
 * @access public
 */
class Configuration {
    private $name;
    private $countSpaces;
    /**
     * @AssociationType Dashboard
     * @AssociationMultiplicity 1
     */
    public $config;
    /**
     * @AssociationType Chart
     * @AssociationMultiplicity 1..*
     * @AssociationKind Composition
     */
    public $charts = array();
}
?>