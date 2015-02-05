<?php
/**
 * ZendExt_Trace_Container_Log
 *
 * Clase base de las trazas
 * 
 * @author Omar Antonio D�az Pe�a.
 * @copyright ERP-Cuba. 
 * @version 1.0.0
 */
	
class ZendExt_Trace_Container_Log {
	/**
	 * Fecha de la traza
	 *
	 * @var String
	 */
	protected $_date;
	
	/**
	 * Hora
	 *
	 * @var String
	 */
	protected $_time;
	
	/**
	 * Identificador de la categoría de la traza
	 *
	 * @var Int.
	 */
	protected $_id_trace_category;
	
	/**
	 * Identificador de tipo
	 * 
	 * @var Int
	 */
	protected $_id_type;
	
	/**
	 * Usuario
	 *
	 * @var String
	 */
	protected $_user;
	
	/**
	 * Identificador de estructura común
	 *  
	 * @var Int
	 */
	protected $_id_common_structure;
	
	/**
	 * Constructor
	 *
	 * @param Int $pIdTraza
	 * @param Int $pIdTipo
	 * @param String $pFecha
	 * @param String $pHora
	 */
	function __construct ($pIdTraceCategory, $pUser, $pIdCommonStructure, $pFecha = null, $pHora = null){
		$this->_date = ($pFecha == null) ? date ('d/m/Y') : $pFecha;
		$this->_time = ($pHora == null) ? date ('H:i:s') : $pHora;
		
		$this->_user = $pUser;
		$this->_id_common_structure = ($pIdCommonStructure != null) ? $pIdCommonStructure
																	: -1;
		//$tmp = $this->getIdTraceCategoryByAlias($pIdTraceCategory);
		
		$this->_id_trace_category = ($pIdTraceCategory == 0) ? 98 : $pIdTraceCategory;
		
		
	}
	
	function getIdTraceCategoryByAlias ($pSubsistema) {
		$query = Doctrine_Query :: create ();
		
		$query->from ('NomCategoriatraza ct')
			  ->where ('ct.denominacion = ?', $pSubsistema);
			  
		return $query->execute ()->toArray ();
	}
	
	/**
	 * Identificador de estructura común.
	 *
	 * @return Int
	 */
	function getCommonStructure() {
		return $this->_id_common_structure;
	}
	
	/**
	 * Obtiene la fecha
	 *
	 * @return String
	 */
	function getDate () {
		return $this->_date;	
	}
	
	/**
	 * Obtiene la hora
	 *
	 * @return String
	 */
	function getTime () {
		return $this->_time;	
	}
	
	/**
	 * Identificador de la traza
	 *
	 * @return Int
	 */
	function getIdTraceCategory () {
		return $this->_id_trace_category;	
	}
	
	/**
	 * Identificador del tipo de la traza
	 *
	 * @return Int
	 */
	function getIdType () {
		return $this->_id_type;	
	}
	
	/**
	 * Devolver el usuario.
	 *
	 * @return String.
	 */
	function getUser () {
		return $this->_user;
	}
	
	/**
	 * Fija el tipo de traza
	 *
	 * @param Int $pType
	 */
	function setTraceType ($pType) {
		$this->_id_type = $pType;		
	}	
}
?>