<?php
class GestionartrazaController extends ZendExt_Controller_Secure {
	function init() {
		parent::init ();
	}
	
	function gestionartrazaAction() {
		$this->render ();
	}
	
	function confgridAction() {
		$result = array ('grid' => array ('columns' => array ( ) ) );
		$result ['grid'] ['columns'] [] = array ('id' => 'idtraza', 'hidden' => true, 'hideable' => false, 'dataIndex' => 'idtraza' );
		$result ['grid'] ['columns'] [] = array ('hidden' => true, 'hideable' => false, 'dataIndex' => 'idestructuracomun' );
		$result ['grid'] ['columns'] [] = array ('header' => 'Categor&iacute;a', 'width' => 122, 'sortable' => true, 'dataIndex' => 'categoria' );
		$result ['grid'] ['columns'] [] = array ('header' => 'Usuario', 'width' => 70, 'sortable' => true, 'dataIndex' => 'usuario' );
		$result ['grid'] ['columns'] [] = array ('header' => 'Fecha', 'width' => 70, 'sortable' => true, 'dataIndex' => 'fecha' );
		$result ['grid'] ['columns'] [] = array ('header' => 'Hora', 'width' => 60, 'sortable' => true, 'dataIndex' => 'hora' );
		$tmp = new stdClass();
		$result ['grid'] ['campos'] [] = $tmp->name = 'idestructuracomun';
		$tmp = new stdClass();
		$result ['grid'] ['campos'] [] = $tmp->name = 'categoria';
		$tmp = new stdClass();
		$result ['grid'] ['campos'] [] = $tmp->name = 'usuario';
		$tmp = new stdClass();
		$result ['grid'] ['campos'] [] = $tmp->name = 'fecha';
		$tmp = new stdClass();
		$result ['grid'] ['campos'] [] = $tmp->name = 'hora';
		$tmp = new stdClass();
		$traza = $this->_request->getPost ( 'tipo_traza' );
		$traceconfig = ZendExt_FastResponse::getXML ( 'traceconfig' );
		$contenedores = $traceconfig->containers;
		foreach ( $contenedores->children () as $contenedor ) {
			if ($traza == ( string ) $contenedor ['alias']) {
				$atributos = $contenedor->atts;
				foreach ( $atributos->children () as $column ) {
					$arr = array ('header' => ( string ) $column ['alias'], 'width' => 122, 'sortable' => true, 'dataIndex' => ( string ) $column ['att'] );
					$result ['grid'] ['columns'] [] = $arr;
					$tmp = new stdClass();
					$tmp->name = ( string ) $column ['att'];
					$result ['grid'] ['campos'] [] = $tmp;
				}
				break;
			}
		}
		echo (json_encode ( $result ));
	}
	
	function confformAction() {
		$traza = $this->_request->getPost ( 'tipo_traza' );
		$traceconfig = ZendExt_FastResponse::getXML ( 'traceconfig' );
		$contenedores = $traceconfig->containers;
		$cantidad = 1;
		$idoperador = 0;
		$result = array (array ('cantidad' => $cantidad ) );
		$result [] = array ('xtype' => 'TextField', 'fieldLabel' => 'Usuario', 'id' => 'idusuario' );
		foreach ( $contenedores->children () as $contenedor ) {
			if ($traza == ( string ) $contenedor ['alias']) {
				$atributos = $contenedor->atts;
				foreach ( $atributos->children () as $column ) {
					switch ( ( string ) $column ['type']) {
						case 'text' :
							$xtype = 'TextField';
						break;
						case 'bool' :
							$xtype = 'combo';
						break;
						case 'number' :
							$xtype = 'NumberField';
						break;
						default :
							$xtype = null;
						break;
					}
					if ($xtype) {
						if ($xtype == 'NumberField') {
							$idoperador ++;
							$arr = array ('xtype' => 'combo', 'fieldLabel' => 'Operador', 'id' => 'idcomp' . $idoperador, 'data' => $this->cargarcombooperador () );
							$result [] = $arr;
							$cantidad ++;
						}
						$arr = array ('xtype' => $xtype, 'fieldLabel' => ( string ) $column ['alias'], 'id' => 'id' . ( string ) $column ['att'] );
						if ($xtype == 'combo') {
							$arr ['hiddenName'] = $arr ['id'];
							$arr ['data'] = $this->cargarcombobool ();
						}
						$result [] = $arr;
						$cantidad ++;
					}
				}
				break;
			}
		}		
		$result [0] ['cantidad'] = $cantidad;
		echo (json_encode ( $result ));
	}
	
	function cargarcombooperador() {
		$result [0] = array ('>', '>' );
		$result [1] = array ('>=', '>=' );
		$result [2] = array ('=', '=' );
		$result [3] = array ('<=', '<=' );
		$result [4] = array ('<', '<' );
		return $result;
	}
	
	function cargarcombobool() {
		$result [0] = array (0, 0 );
		$result [1] = array (1, 1 );
		$result [2] = array ("Todas", 2 );
		return $result;
	}
	
	function cargargridAction() {
		$offset = 0;
		$idcategoria = 0;
		$fecha_desde = 0;
		$fecha_hasta = 0;
		if ($this->_request->getPost ( 'idtipotraza' ))
			$idtipotraza = $this->_request->getPost ( 'idtipotraza' );
		if ($this->_request->getPost ( 'tipotraza' ))
			$tipotraza = $this->_request->getPost ( 'tipotraza' );
		if ($this->_request->getPost ( 'idcategoria' ))
			$idcategoria = $this->_request->getPost ( 'idcategoria' );
		if ($this->_request->getPost ( 'fecha_desde' ))
			$fecha_desde = $this->_request->getPost ( 'fecha_desde' );
		if ($this->_request->getPost ( 'fecha_hasta' ))
			$fecha_hasta = $this->_request->getPost ( 'fecha_hasta' );
		if ($this->_request->getPost ( 'start' ))
			$offset = $this->_request->getPost ( 'start' );
		if ($this->_request->getPost ( 'limit' ))
			$limit = $this->_request->getPost ( 'limit' );
		if ($this->_request->getPost ( 'campos' ))
			$campos = json_decode ( str_replace ( '\\', '', $this->_request->getPost ( 'campos' ) ) );
		
		$class_name = $this->clasenombre ( $tipotraza );
		$Cantidad = $this->cantidad ( $class_name, $idtipotraza, $idcategoria, $fecha_desde, $fecha_hasta, $campos );
		$Trazas = $this->cargardatos ( $class_name, $idtipotraza, $idcategoria, $fecha_desde, $fecha_hasta, $offset, $limit, $campos );
		echo (json_encode ( array ('cantidad_trazas' => $Cantidad, 'trazas' => $Trazas ) ));
	}
	
	function cargardatos($class_name, $idtipotraza, $idcategoria, $fecha_desde, $fecha_hasta, $offset, $limit, $campos) {
		if (! $idtipotraza)
			$idtipotraza = 0;
		if (! $idcategoria)
			$idcategoria = 0;
		if (! $fecha_desde)
			$fecha_desde = 0;
		if (! $fecha_hasta)
			$fecha_hasta = 0;
		if (! $offset)
			$offset = 0;
		if (! $limit)
			$limit = 20;
		if (! $campos)
			$campos = new stdClass ( );
		$global = ZendExt_GlobalConcept::getInstance ();
		if ($global->Estructura->idestructura)
			$idestructura = $global->Estructura->idestructura; else
			$idestructura = 0;
		$clase = new $class_name ( );
		$Trazas = null;
		$Trazas = $clase->select ( $idestructura, $idtipotraza, $idcategoria, $fecha_desde, $fecha_hasta, $offset, $limit, $campos );
		$clase = null;
		$campos = null;
		$global = null;
		if ($Trazas) {
			foreach ( $Trazas as $t => $v ) {
				$Trazas [$t] ['categoria'] = HisTraza::categoria ( $v ['idtraza'] );
				unset ( $Trazas [$t] ['idtipotraza'], $Trazas [$t] ['idcategoriatraza'], $Trazas [$t] ['idtraza'] );
			}
			return $Trazas;
		}
		return array ( );
	}
	
	function cantidad($class_name, $idtipotraza, $idcategoria, $fecha_desde, $fecha_hasta, $campos) {
		if (! $idtipotraza)
			$idtipotraza = 0;
		if (! $idcategoria)
			$idcategoria = 0;
		if (! $fecha_desde)
			$fecha_desde = 0;
		if (! $fecha_hasta)
			$fecha_hasta = 0;
		if (! $campos)
			$campos = new stdClass ( );
		$global = ZendExt_GlobalConcept::getInstance ();
		if ($global->Estructura->idestructura)
			$idestructura = $global->Estructura->idestructura; else
			$idestructura = 0;
		$clase = new $class_name ( );
		$Cantidad = $clase->cantidad ( $idestructura, $idtipotraza, $idcategoria, $fecha_desde, $fecha_hasta, $campos );
		$clase = null;
		$campos = null;
		if ($Cantidad [0] ['cantidad'])
			return $Cantidad [0] ['cantidad'];
		return 0;
	}
	
	function clasenombre($tipotraza) {
		if (! $tipotraza)
			$tipotraza = "";
		
		$traceconfig = ZendExt_FastResponse::getXML ( 'traceconfig' );
		$contenedores = $traceconfig->containers;
		foreach ( $contenedores->children () as $contenedor ) {
			if ($tipotraza == ( string ) $contenedor ['alias']) {
				$class_name = ( string ) $contenedor ['doctrine'];
				break;
			}
		}
		if ($class_name)
			return $class_name;
		return "";
	}
	
	function cargarcombotipoAction() {
		$tipo = NomTipotraza::selectAlltipo ();
		echo (json_encode ( array ('tipo_traza' => $tipo ) ));
	}
	
	function cargarcombocategoriaAction() {
		$categoria = NomCategoriatraza::selectAllcategoria ();
		echo (json_encode ( array ('categorias' => $categoria ) ));
	}
	
	function exportarxmlAction() {
		$temp = $this->_request->getParam ( 'datos' );
		$campos = json_decode ( str_replace ( '\\', '', $temp ) );
		$temp = null;
		if ($campos) {
			$file_name = "Trazas de " . $campos [6] . " (" . date ( 'd-m-Y h-i-s' ) . ").xml";
			header ( 'Content-Type: application/octet-stream' );
			header ( 'Content-Type: application/force-download' );
			header ( "Content-Disposition: inline; filename=\"{$file_name}\"" );
			header ( 'Pragma: no-cache' );
			header ( 'Expires: 0' );
			$class_name = $this->clasenombre ( $campos [6] );
			$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?> \n" . "<xml> \n" . "	<trazas tipo=\"$campos[6]\"> \n";
			echo $xml;
			$xml = null;
			$cont = 0;
			$Trazas = $this->cargardatos ( $class_name, $campos [2], $campos [3], $campos [4], $campos [5], $campos [0], $campos [1], $campos [7] );
			if ($Trazas)
				foreach ( $Trazas as $traz ) {
					$cont ++;
					$xml = "		<traza_" . $cont . "> \n";
					foreach ( $traz as $name => $value )
						$xml .= "			<" . $name . ">" . $value . "</" . $name . "> \n";
					$xml .= "		</traza_" . $cont . "> \n";
					echo $xml;
				}
			$xml = "	</trazas> \n" . "</xml>";
			echo $xml;
		}
	}
}
?>
