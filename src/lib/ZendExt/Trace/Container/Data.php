<?php 
	/**
	 * ZendExt_Trace_Publisher_Data
	 * 
	 * Clase para la gestión de las trazas de datos
	 * 
	 * @author Omar Antonio Díaz Peña.
	 * @package Trace.
	 * @subpackage Publisher.
	 * @copyright ERP-Cuba.
	 * @version 1.0.0.
	 */
	class ZendExt_Trace_Container_Data extends ZendExt_Trace_Container_Log {
		/**
		 * Esquema
		 *
		 * @var String
		 */
		 private $_schema;
		 
		 /**
		  * Tabla
		  *
		  * @var String
		  */
		 private $_table;
		 
		 /**
		  * Acción
		  *
		  * @var String
		  */
		 private $_action;
		 
		 /**
		  * Constructor
		  *
		  * @param String $pSchema
		  * @param String $pTable
		  * @param String $pAction
		  * @param Int $pIdTipo
		  * @param String $pUser
		  * @param Int $pIdCommonStructure
		  * @param String $pFecha
		  * @param String $pHora
		  */
		 function __construct($pSchema, $pTable, $pAction, $pIdCategory, $pUser, $pIdCommonStructure, $pFecha, $pHora) {
		 	parent :: __constructor ($pIdCategory, $pUser, $pIdCommonStructure, $pFecha, $pHora);
		 	
		 	$this->_schema = $pSchema;
		 	$this->_table = $pTable;
		 	$this->_action = $pAction;
		 	//parent::setTraceType(1);
		 }
		 
		 /**
		  * Esquema
		  *
		  * @return String
		  */
		 function getSchema() {
		 	return $this->_schema;
		 }
		 
		 /**
		  * Accion
		  *
		  * @return String
		  */
		 function getAction() {
		 	return $this->_action;
		 }
		 
		 /**
		  * Tabla
		  *
		  * @return String
		  */
		 function getTable() {
		 	return $this->_table;
		 } 
	}
?>