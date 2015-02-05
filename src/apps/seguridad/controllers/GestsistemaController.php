<?php

/*
 *Componente para gestinar los sistemas.
 *
 * @package SIGIS
 * @copyright UCID-ERP Cuba
 * @author Oiner Gomez Baryolo    
 * @author Darien Garc�a Tejo
 * @author Julio Cesar Garc�a Mosquera  
 * @version 1.0-0
 */
	class GestsistemaController extends ZendExt_Controller_Secure
	{
		function init ()
		{
			parent::init();			
		}	
			
		function gestsistemaAction() {
			$this->render();
		}
			
        function insertarsistemaAction() {
            $sistema = new DatSistema();
            $idpadre = $this->_request->getPost('idpadre');
            $idservidorauth = $this->_request->getPost('idservidor');
            $sistema->denominacion = $this->_request->getPost('denominacion');
            $sistema->abreviatura = $this->_request->getPost('abreviatura');
            $sistema->descripcion = $this->_request->getPost('descripcion');
            $sistema->icono = $this->_request->getPost('icono');
            $sistema->iddominio = $this->global->Perfil->iddominio;
            $servidorweb = $this->_request->getPost('servidorweb');
			$arraySistemas = array();
			$arraySistemas = DatSistema::obtenerSistemasV($idpadre);
			$denom = true;
			$abrev = true;
			if( $idpadre != 0)
			{
				foreach($arraySistemas as $sistemas)
				{
					if($sistemas['denominacion'] == $sistema->denominacion)
					{
						$denom = false;
						break;	
					}
					if($sistemas['abreviatura'] == $sistema->abreviatura)
					{
						$abrev = false;
						break;	
					}
				}
				if(!$denom)
					throw new ZendExt_Exception('SEG046');
				else if(!$abrev)	
					throw new ZendExt_Exception('SEG047');
				else
				{
					if($idpadre == 0)
					{
						if($servidorweb)
						$sistema->externa = $servidorweb;
						$sistema->idpadre = $sistema->idsistema;
						$sistema->save();
					}
					else 
					{
						$sistema->idpadre = $idpadre;
						$servwebpadre = DatSistema::buscarservidorweb($idpadre);
						if(count($servwebpadre))
						$sistema->externa = $servwebpadre[0]->externa;
						$sistema->save();
					}

		  			$objSistemaComp = new DatSistemaCompartimentacion();
					$objSistemaComp->idsistema = $sistema->idsistema;
					$objSistemaComp->iddominio = $this->global->Perfil->iddominio;
					$objSistemaComp->save();

					$servidoresdeco = json_decode(stripcslashes($this->_request->getPost('servidores')));
					$arrayObjServidores = array();            
					if(count($servidoresdeco))
					{
						$arrayObjServidores = array();
						foreach($servidoresdeco as $servidor)
						{
						 $servidorsistema = new DatSistemaDatServidores();
						 $servidorsistema->idservidor = $servidor[2];
						 $idgestor = explode('-',$servidor[3]);                 
						 $servidorsistema->idgestor = $idgestor[0];                 
						 $nombreDb = explode('-', $servidor[4]);
						 $servidorsistema->idbd = $this->insertaNomBaseDato($nombreDb[0]);
						 $nameesquema = explode('-',$servidor[5]);
						 $servidorsistema->idesquema = $this->insertaNomEsquema($nameesquema[0]);                 
						 $servidorsistema->idsistema = $sistema->idsistema;
						 $ideolesbd = explode('-',$servidor[6]);
		                 $servidorsistema->idrolesbd = $ideolesbd[0];
						 $arrayObjServidores[] = $servidorsistema;                                        
						}    
					}
					$model = new DatSistemaModel();                            
					$model->insertarsistema($arrayObjServidores,$sistema);
					$this->showMessage('El sistema fue insertado satisfactoriamente.');
				}	
			}
			else
			{
				$arraySistemasP = DatSistema::obtenerSistemasP();
				$denom = true;
				$abrev = true;
				foreach($arraySistemasP as $sistemas)
				{
					if($sistemas['denominacion'] == $sistema->denominacion)
					{
						$denom = false;
						break;	
					}
					if($sistemas['abreviatura'] == $sistema->abreviatura)
					{
						$abrev = false;
						break;	
					}
				}
				if(!$denom)
					throw new ZendExt_Exception('SEG046');
				else if(!$abrev)	
					throw new ZendExt_Exception('SEG047');
				else
				{
					if($idpadre == 0)
					{
						if($servidorweb)
						$sistema->externa = $servidorweb;
						$sistema->idpadre = $sistema->idsistema;
						$sistema->save();
					}
					else 
					{
						$sistema->idpadre = $idpadre;
						$servwebpadre = DatSistema::buscarservidorweb($idpadre);
						if(count($servwebpadre))
						$sistema->externa = $servwebpadre[0]->externa;
						$sistema->save();
					}

		    $objSistemaComp = new DatSistemaCompartimentacion();
            $objSistemaComp->idsistema = $sistema->idsistema;
            $objSistemaComp->iddominio = $this->global->Perfil->iddominio;
            $objSistemaComp->save();

					$servidoresdeco = json_decode(stripcslashes($this->_request->getPost('servidores')));
					$arrayObjServidores = array();            
					if(count($servidoresdeco))
					{
						$arrayObjServidores = array();
						foreach($servidoresdeco as $servidor)
						{
						 $servidorsistema = new DatSistemaDatServidores();
						 $servidorsistema->idservidor = $servidor[2];
						 $idgestor = explode('-',$servidor[3]);                 
						 $servidorsistema->idgestor = $idgestor[0];                 
						 $nombreDb = explode('-', $servidor[4]);
						 $servidorsistema->idbd = $this->insertaNomBaseDato($nombreDb[0]);
						 $nameesquema = explode('-',$servidor[5]);
						 $servidorsistema->idesquema = $this->insertaNomEsquema($nameesquema[0]);                 
						 $servidorsistema->idsistema = $sistema->idsistema;
						 $ideolesbd = explode('-',$servidor[6]);
		                 $servidorsistema->idrolesbd = $ideolesbd[0];
						 $arrayObjServidores[] = $servidorsistema;                                        
						}    
					}
					$model = new DatSistemaModel();                            
					$model->insertarsistema($arrayObjServidores,$sistema);
					$this->showMessage('El sistema fue insertado satisfactoriamente.');
				}
			}
				
		}
                
        function modificarsistemaAction() {
        	$idsistema = $this->_request->getPost('idsistema');
            $sistema = Doctrine::getTable('DatSistema')->find($idsistema);
            $idpadre = $sistema->idpadre;
            $sistema->denominacion = $this->_request->getPost('denominacion');        
            $sistema->abreviatura = $this->_request->getPost('abreviatura');
            $sistema->descripcion = $this->_request->getPost('descripcion');
            $externa = $this->_request->getPost('servidorweb');
            $sistema->icono = $this->_request->getPost('icono');
			$arraySistemas = array();
			$arraySistemas = DatSistema::obtenerSistemasV($idpadre);
			$denom = true;
			$abrev = true;
			if( $idpadre != 0) {
				$arraySistemasP = DatSistema::obtenerSistemasP();
				$auxden = 0;
				$auxabv = 0;
				foreach($arraySistemasP as $aux2) {
					if ($aux2['idsistema'] == $idsistema) {
						$auxden = $aux2['denominacion']; 
						$auxabv = $aux2['abreviatura'];
						}	
					}
				$denom = true;
				$abrev = true;
				if($sistema->denominacion != $auxden) {
					foreach($arraySistemasP as $sistemas) {
						if($sistemas['denominacion'] == $sistema->denominacion) {
							$denom = false;
							break;	
							}
						}
					}
				if($sistema->abreviatura != $abrev) {
					foreach($arraySistemasP as $sistemas) {
						if($sistemas['abreviatura'] == $sistema->abreviatura) {
							$abrev = false;
							break;	
						}
					}
				}
				if(!$denom)
					throw new ZendExt_Exception('SEG046');
				else if(!$abrev)	
					throw new ZendExt_Exception('SEG047');
				else {
					if($externa) {
		            	$sistema->externa = $externa;
		            	$this->modificarhijos($idsistema,$externa);
		            	}
		            $esquemasEliminados = json_decode(stripcslashes($this->_request->getPost('esquemasEliminados')));
		            if (count($esquemasEliminados) > 0) {
			            foreach ($esquemasEliminados as $esquemaEl) {
			            	$esquemaElArr = explode('_', $esquemaEl);
			            	$esquemaELObj = Doctrine::getTable('DatSistemaDatServidores')->findByDql('idsistema = ? and idservidor = ? and idgestor = ? and idbd = ? and idesquema = ?', $esquemaElArr);
			            	$esquemaELObj[0]->delete();
			            }
		            }
		            $servidoresdeco = json_decode(stripcslashes($this->_request->getPost('servidores')));
		            $arrayObjServidores = array();            
		            if(count($servidoresdeco) > 0) {
		                $arrayObjServidores = array();
		                foreach($servidoresdeco as $servidor) {
		                 $servidorsistema = new DatSistemaDatServidores();
		                 $servidorsistema->idservidor = $servidor[2];
		                 $idgestor = explode('-',$servidor[3]);                 
		                 $servidorsistema->idgestor = $idgestor[0];
		                 $nombreDb = explode('-', $servidor[4]);
		                 $servidorsistema->idbd = $this->insertaNomBaseDato($nombreDb[0]);
		                 $nameesquema = explode('-',$servidor[5]);
		                 $servidorsistema->idesquema = $this->insertaNomEsquema($nameesquema[0]);                 
		                 $servidorsistema->idsistema = $sistema->idsistema;
		                 $ideolesbd = explode('-',$servidor[6]);
		                 $servidorsistema->idrolesbd = $ideolesbd[0];
		                 $arrayObjServidores[] = $servidorsistema;                                        
		                }    
		            }
		            $model = new DatSistemaModel();
		            $model->modificarsistema($arrayObjServidores,$sistema);
		            $this->showMessage('El sistema fue modificado satisfactoriamente.'); 
				}	
			}
        }
        
		function verificarsistema($denominacion,$abreviatura) {
	        $datossistema = DatSistema::verificarsistema($denominacion,$abreviatura);
	        if($datossistema)
	            return 1;
	        else 
	           return 0;
        } 
        
        function insertaNomBaseDato($db) {
        	$idbd = DatBd::buscarnombd($db,1,0);
        	if(count($idbd))
        		return  $idbd[0]->idbd;
        	else 
        		{
        		$objBD = new DatBd();
        		$objBD->denominacion = $db;
        		$objBD->descripcion = $db;
        		$objBD->save();
        		return $objBD->idbd;
        		}       	
        }
        
		function insertaNomEsquema($esquema) {
        	$idesquema = DatEsquema::buscarnomesquemas($esquema,1,0);
        	if(count($idesquema))
        		return  $idesquema[0]->idesquema;
        	else 
        		{
        		$objEsquemas = new DatEsquema();
        		$objEsquemas->denominacion = $esquema;
        		$objEsquemas->descripcion = $esquema;
        		$objEsquemas->save();
        		return $objEsquemas->idesquema;
        		}       	
        }
        
        function eliminarsistemaAction() {
            $this->sistemaeliminar($this->_request->getPost('idsistema'));
            $this->showMessage('El sistema fue eliminado satisfactoriamente.');
        }
				
		function cargarsistemaAction() {
				$sistemas = DatSistema::cargarsistema($this->_request->getPost('node'));
				if($sistemas->count())
				{
					foreach($sistemas as $valores=>$valor)
						{			
							$sistemaArr[$valores]['id'] = $valor->id;
							$sistemaArr[$valores]['text'] = $valor->text;
							$sistemaArr[$valores]['abreviatura'] = $valor->abreviatura;
							$sistemaArr[$valores]['descripcion'] = $valor->descripcion;
							$sistemaArr[$valores]['idpadre'] = $valor->idpadre;
							$sistemaArr[$valores]['icono'] = $valor->icono;
                            $sistemaArr[$valores]['servidorweb'] = $valor->externa;
                            $sistemaArr[$valores]['leaf'] = $valor->leaf;
						}
						echo json_encode ($sistemaArr);return;
			    }
			    else
			    { 
					$sist = $sistemas->toArray();
					echo json_encode ($sist);return;
                }
		}
		
		function comprobar($idesquema,$idsistema,$idservidor,$idgestor,$idbd) {
		 $esquema=DatSistemaDatServidores::obteneresquemasmarcados($idsistema,$idservidor,$idgestor,$idbd);				
		 $esquemas=$esquema->toArray(true);
		 foreach($esquemas as $valor)
		 {
		  if($valor['idesquema']==$idesquema)
			return true;
		 }
			return false;
		}
				
		function cargarservidoresAction() {
				$idnodo = $this->_request->getPost('node');
				$accion = $this->_request->getPost('accion');
				$idsistema = $this->_request->getPost('idsistema');
				if(!$idnodo)
				{
					$servidores = DatServidor::cargarservidores(0,0);
					if($servidores->getData()!=NULL)
					{
						foreach ($servidores as $valores=>$valor)
						{
							$servidoresArr[$valores]['id'] = $valor->id;
							$servidoresArr[$valores]['idservidor'] = $valor->id;								
							$servidoresArr[$valores]['text'] = $valor->text;
							$servidoresArr[$valores]['type'] = 'servidores';
							$servidoresArr[$valores]['icon'] = '../../views/images/server-white.PNG';
						}
						echo json_encode ($servidoresArr);return;
					 }
					 else
					 {
						$serv=$servidores->toArray();
						echo json_encode ($serv);return;						
					 }
			   }
			   elseif($accion == 'cargargestores')
			   {
			   		$idservidor = $this->_request->getPost('idservidor');
					$gestores = DatGestor::cargargestores($idservidor,0,0);
					if($gestores->getData()!=NULL)
					{
						foreach ($gestores as $valores=>$valor)
						{			
							$gestoresArr[$valores]['id'] = $valor->idgestor.'-'.$idnodo;
							$gestoresArr[$valores]['text'] = $valor->gestor;
							$gestoresArr[$valores]['idgestor'] = $valor->idgestor;
							$gestoresArr[$valores]['idservidor'] = $idservidor;
							$gestoresArr[$valores]['gestor'] = $valor->gestor;
							if($valor->gestor == 'pgsql')
								$gestoresArr[$valores]['icon'] = '../../views/images/pgadmin.png';
							else 
								$gestoresArr[$valores]['icon'] = '../../views/images/oracle.png';
							$gestoresArr[$valores]['puerto'] = $valor->puerto;
							$gestoresArr[$valores]['type'] = 'gestor';
							$gestoresArr[$valores]['ipgestorbd'] = $valor->DatGestorDatServidorbd[0]->DatServidorbd->DatServidor->ip;
						}
					 echo json_encode ($gestoresArr);return;
			        }
				    else
				 	{
						$gest=$gestores->toArray();
						echo json_encode ($gest);return;
				 	}
			   }				 
 			 elseif($accion == 'cargarbd')
			 {
				$user = $this->_request->getPost('user');
			 	$pass = $this->_request->getPost('passw');
			 	$gestor = $this->_request->getPost('gestor');
				$ipgestorbd = $this->_request->getPost('ipgestorbd');
				$idservidor = $this->_request->getPost('idservidor');
				$idgestor = $this->_request->getPost('idgestor');
				$puerto = $this->_request->getPost('puerto');
				$getDatabases = 'get' . ucfirst($gestor) . 'Databases';
			 	$arrayBD = $this->$getDatabases($gestor, $user, $pass, $ipgestorbd, $idgestor, $idservidor, $idsistema, $idnodo, $puerto);
			 	$this->verificarBasesDatos($arrayBD, $idservidor, $idgestor);			 	
			 	echo json_encode($arrayBD);

			}
 			elseif($accion == 'cargaresquemas') {
 				$RSA = new ZendExt_RSA_Facade();
				$user = $this->_request->getPost('user');
			 	$pass = $RSA->decrypt($this->_request->getPost('passw'));
			 	$gestor = $this->_request->getPost('gestor');
				$ipgestorbd = $this->_request->getPost('ipgestorbd');
				$namebd = $this->_request->getPost('namebd');
				$idsistema = $this->_request->getPost('idsistema');
				$idservidor = $this->_request->getPost('idservidor');
				$idgestor = $this->_request->getPost('idgestor');
				$puerto = $this->_request->getPost('puerto');
				$getSchemas = 'get' . ucfirst($gestor) . 'Schemas';
			 	$schemasArr = $this->$getSchemas($gestor, $user, $pass, $ipgestorbd, $namebd, $idsistema, $idnodo, $puerto);
			 	$this->verificarEsquemas($schemasArr, $idservidor, $idgestor, $namebd);				
				echo json_encode($schemasArr);
			 }
		}
		
		private function getPgsqlDatabases($gestor, $user, $pass, $ipgestorbd, $idgestor, $idservidor, $idsistema, $idnodo, $puerto) {
			$bdArr = array();
			$rsa = new ZendExt_RSA_Facade();
			$dm = Doctrine_Manager::getInstance();
			$nameCurrentConn = $dm->getCurrentConnection()->getName();
			$conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd/template1", 'pg_catalog');
			$db = PgDatabase::getPgsqlDatabases($conn);
			$dm->setCurrentConnection($nameCurrentConn);
			$key = 0;
			if ($db->getData() != null) {
				foreach($db as $valor) {
					$bdArr[$key]['id'] = $valor->datname . '-' . $idnodo;
					$bdArr[$key]['text'] = $valor->datname;
					$bdArr[$key]['namebd'] = $valor->datname;
					$bdArr[$key]['gestor'] = $gestor;
					$bdArr[$key]['ipgestorbd'] = $ipgestorbd;
					$bdArr[$key]['puerto'] = $puerto;
					$bdArr[$key]['user'] = $user;
					$bdArr[$key]['passw'] = $rsa->encrypt($pass);
					$bdArr[$key]['idgestor'] = $idgestor;
					$bdArr[$key]['idservidor'] = $idservidor;
					$bdArr[$key]['type'] = 'bd';
					$bdArr[$key]['icon'] = '../../views/images/server.PNG';
					$key++;
					}
			}
			$rolesbd = SegRolesbd::loadRoleBD($idservidor, $idgestor);
			$RolBD = $this->verifyRoles($rolesbd, $gestor, $user, $pass, $ipgestorbd);
			if ( count($RolBD) ) {
					foreach ($RolBD as $roles) {
						$bdArr[$key]['id'] = $roles->id . '-' . $idnodo;
						$bdArr[$key]['idrolbd'] = $roles->id;
						$bdArr[$key]['text'] = $roles->rolname;
						$bdArr[$key]['leaf'] = true;
						if( DatSistemaDatServidores::verifyCheckedRole( $roles->id, $idsistema) ) {
							$bdArr[$key]['checked'] = true;
							$bdArr[$key]['marcado'] = true;
						}
						else 
							$bdArr[$key]['checked'] = false;
						$bdArr[$key]['rol'] = true;
						$bdArr[$key]['type'] = 'roles';
						$bdArr[$key]['icon'] = '../../views/images/usuario.png';
						$key++;
					}
			}
			
			return $bdArr;
		}
		
		private function verifyRoles($array, $gestor, $user, $pass, $ipgestorbd) {
			$arrayResult = array();
			$arrayDelete = array();
			$arrayReturn = array();
			$dm = Doctrine_Manager::getInstance();
			$nameCurrentConn = $dm->getCurrentConnection()->getName();
			$conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd/template1", 'pg_catalog');
			$arrayRolesGestor = PgAuthid::getRoles($conn);
			$dm->setCurrentConnection($nameCurrentConn);
			$arrayRolesGestor = $this->convetToUnidimensional($arrayRolesGestor);
			$arrayRoles = $this->convetToUnidimensional($array);
			$arrayDelete = array_diff($arrayRoles, $arrayRolesGestor);
			$arrayReturn = array_intersect($arrayRoles, $arrayRolesGestor);
			if( count($arrayDelete) )
				SegRolesbd::deleteRoles($arrayDelete);
			if(count($arrayReturn))
				$arrayReturn = SegRolesbd::getRolInformation($arrayReturn);
			return $arrayReturn;			
		}
		
		private function convetToUnidimensional($arrayRoles) {
			$result = array();
			foreach ($arrayRoles as $rol)
				$result[] = $rol->rolname;
			return $result;
		}
		
		private function getPgsqlSchemas($gestor, $user, $pass, $ipgestorbd, $namebd, $idsistema, $idnodo, $puerto) {
			$schemasArr = array();
			$dm = Doctrine_Manager::getInstance();
			$nameCurrentConn = $dm->getCurrentConnection()->getName(); 
			$conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd/$namebd", 'information_schema');
			$schemas = Schemata::getPgsqlSchemasByDb($conn, $namebd);
			$dm->setCurrentConnection($nameCurrentConn);
			foreach($schemas as $key=>$esquemas) {
					$schemasArr[$key]['id'] =  $esquemas['id'] . '-' . $idnodo;
					$schemasArr[$key]['text'] =  $esquemas['text'];
					$schemasArr[$key]['leaf'] =  true;
					$schemasArr[$key]['type'] =  'schemas';
					$schemasArr[$key]['icon'] = '../../views/images/schemas.PNG';
					$marcado = DatSistemaDatServidores::chequeado($idsistema, $ipgestorbd, $gestor, $namebd, $esquemas['esquema']);
					if($marcado->getData() != null) {
						$schemasArr[$key]['checked'] = true;
						$schemasArr[$key]['marcado'] = $marcado[0]->idsistema .'_'. $marcado[0]->idservidor .'_'. $marcado[0]->idgestor .'_'. $marcado[0]->idbd .'_'. $marcado[0]->idesquema;
					}
					else
						$schemasArr[$key]['checked'] = false;
				}
			return $schemasArr;
		}
		
		private function getOracleDatabases($gestor, $user, $pass, $ipgestorbd, $idgestor, $idservidor, $idsistema, $idnodo, $puerto) {
			$rsa = new ZendExt_RSA_Facade();
			$SID = "orac";
			$cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
			$bdArr = array();
			$key = 0;
			$conn = oci_connect("$user","$pass","$cadenaConex");
			$query = 'select name from sys.v_$database'; 
	        $stid = oci_parse($conn, $query);
	        oci_execute($stid);
	            while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
				$bdArr[$key]['id'] = $row['NAME'] . '-' . $idnodo;
				$bdArr[$key]['text'] = $row['NAME'];
				$bdArr[$key]['namebd'] = $row['NAME'];
				$bdArr[$key]['gestor'] = $gestor;
				$bdArr[$key]['ipgestorbd'] = $ipgestorbd;
				$bdArr[$key]['puerto'] = $puerto;
				$bdArr[$key]['user'] = $user;
				$bdArr[$key]['passw'] = $rsa->encrypt($pass);
				$bdArr[$key]['idgestor'] = $idgestor;
				$bdArr[$key]['idservidor'] = $idservidor;
				$key++;
				}
	        oci_close($conn);
                return	$bdArr;
		}
		
        private function getOracleSchemas($gestor, $user, $pass, $ipgestorbd, $namebd, $idsistema, $idnodo, $puerto) {
            $SID = "orac";
            $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";

            $key = 0;
            $schemasArr = array();
            $conn = oci_connect("$user","$pass","$cadenaConex");
            $valor = 'XS$NULL';
            $query = "select username from dba_users where username <> all
                                    ('SYS', 'SYSTEM', 'DBSNMP', 'SYSMAN', 'OUTLN', 'MDSYS',
                                    'ORDSYS', 'EXFSYS', 'DMSYS', 'WMSYS', 'WKSYS', 'CTXSYS',
                                    'ANONYMOUS', 'XDB', 'WKPROXY', 'ORDPLUGINS', 'DIP',
                                    'SI_INFORMTN_SCHEMA', 'OLAPSYS', 'MDDATA', 'WK_TEST',
                                    'MGMT_VIEW', 'TSMSYS', 'FLOWS_FILES', 'FLOWS_030000',
                                    'OWBSYS', 'SCOTT', 'ORACLE_OCM', '$valor', 'APEX_PUBLIC_USER',
                                    'SPATIAL_CSW_ADMIN_USR', 'SPATIAL_WFS_ADMIN_USR', 'WORKFLOW') ";
            $stid = oci_parse($conn, $query);
            oci_execute($stid);
              while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
              
                        $schemasArr[$key]['id'] =  $row['USERNAME'] . '-' . $idnodo;
                        $schemasArr[$key]['text'] =  $row['USERNAME'];
                        $schemasArr[$key]['leaf'] =  true;
                        $marcado = DatSistemaDatServidores::chequeado($idsistema, $ipgestorbd, $gestor, $namebd, $row['USERNAME']);
                        //echo '<pre>';print_r();die;
                        if($marcado->getData() != null) {
                                $schemasArr[$key]['checked'] = true;
                                $schemasArr[$key]['marcado'] = $marcado[0]->idsistema .'_'. $marcado[0]->idservidor .'_'. $marcado[0]->idgestor .'_'. $marcado[0]->idbd .'_'. $marcado[0]->idesquema;
                        }
                        else
                                $schemasArr[$key]['checked'] = false;
                $key++;
                }
        oci_close($conn);
        return $schemasArr;

      }
		
		private function verificarBasesDatos($bdObj, $idservidor, $idgestor) {
			$bdArr = array();
			foreach ($bdObj as $bd) {
				if ($bd['type'] == 'bd')
					$bdArr[] = $bd['namebd'];
			}
			$bdNoUsadasObj = DatSistemaDatServidores::obtenerBdNoUsadas($bdArr, $idservidor, $idgestor);
			if (count($bdNoUsadasObj)) {
				$bdNUArr = array();
				foreach ($bdNoUsadasObj as $bdNoUsada)
					$bdNUArr[] = $bdNoUsada->idbd;
				$bdUsadasObj = DatSistemaDatServidores::obtenerBdUsadas($bdNUArr, $idservidor, $idgestor);
				if (count($bdUsadasObj)) {
					$bdBorrar = array();
					foreach ($bdNUArr as $idbd) {
						$isBdUsada = false;
						foreach ($bdUsadasObj as $bdUsada)
							if ($bdUsada->idbd == $idbd) {
								$isBdUsada = true;
								break;
							}
						if (!$isBdUsada)
							$bdBorrar[] = $idbd;
					}
				}
				else $bdBorrar = $bdNUArr;
				$esquemas = DatSistemaDatServidores::obtenerEsquemas($bdNUArr, $idservidor, $idgestor);
				$esqBorrar = array();
				if (count($esquemas)) {
					$arrayEsq = array();
					foreach ($esquemas as $esq)
						$arrayEsq[] = $esq->idesquema;
					$arrayEsqUsados = DatSistemaDatServidores::obtenerEsquemasUsados($arrayEsq, $idservidor, $idgestor);
					if (count($arrayEsqUsados)) {
						foreach ($arrayEsq as $idEsq) {
							$isEsqUsado = false;
							foreach ($arrayEsqUsados as $esqUsado)
								if ($esqUsado->idesquema == $idEsq) {
									$isEsqUsado = true;
									break;
								}
							if (!$isEsqUsado)
								$esqBorrar[] = $idEsq;
						}
					}
					else $esqBorrar = $arrayEsq;
				}
				DatSistemaDatServidores::borrarBdFisicamente($bdNUArr, $idservidor, $idgestor);
				if (count($esqBorrar))
					DatEsquema::borrarEsqFisicamente($esqBorrar);
				if (count($bdBorrar))
					DatBd::borrarBdFisicamente($bdBorrar);
			}
		}
        
		private function verificarEsquemas($schemasArr, $idservidor, $idgestor, $namebd) {
			$bdObj = DatBd::buscarnombd($namebd,1,0);
			if ($bdObj->getData() != null) {
				$idbd = $bdObj[0]->idbd;
				$esqArr = array();
				foreach ($schemasArr as $esq)
					$esqArr[] = $esq['text'];
				$esqNoUsadosObj = DatSistemaDatServidores::obtenerEsquemasNoUsados($esqArr, $idservidor, $idgestor, $idbd);
				if (count($esqNoUsadosObj)) {
					$esqNUArr = array();
					foreach ($esqNoUsadosObj as $esqNoUsado)
						$esqNUArr[] = $esqNoUsado->idesquema; //esquemas no usados en ese servidor
					$esqUsadosObj = DatSistemaDatServidores::obtenerEsquemasUsadosByBD($esqNUArr, $idservidor, $idgestor, $idbd);
					$esqBorrar = array();
					if (count($esqUsadosObj)) { // esquemas usuados en otro servidor
						foreach ($esqNUArr as $idesq) {
							$isEsqUsado = false;
							foreach ($esqUsadosObj as $esqUsado)
								if ($esqUsado->idesquema == $idesq) {
									$isEsqUsado = true;
									break;
								}
							if (!$isEsqUsado)
								$esqBorrar[] = $idesq;
						}
					}
					else $esqBorrar = $esqNUArr;
					DatSistemaDatServidores::borrarEsqFisicamente($esqNUArr, $idservidor, $idgestor, $idbd);
					if (count($esqBorrar))
						DatEsquema::borrarEsqFisicamente($esqBorrar);
				}
				else {
					$esqUsadosByOther = DatSistemaDatServidores::existEsquemasUsadosByBD($esqArr, $idservidor, $idgestor, $idbd);
					if(count($esqUsadosByOther)){
					$esqUsadosByOther = $this->arrayUnidimencional($esqUsadosByOther);
					$arrayEsqUsados = array_diff($esqArr,$esqUsadosByOther);
					$esqUsados = DatSistemaDatServidores::existEsquemasUsedByBD($arrayEsqUsados, $idservidor, $idgestor, $idbd);
					$esqUsados = $this->arrayUnidimencional($esqUsados);
					$arrayNotUsed = array_diff($arrayEsqUsados,$esqUsados);
					if(count($arrayNotUsed))
						DatEsquema::deleteSchemas($arrayNotUsed);
					}
					else {
						$esqUsados = DatSistemaDatServidores::existEsquemasUsedByBD($esqArr, $idservidor, $idgestor, $idbd);
						if(!count($esqUsados))
							DatEsquema::deleteSchemas($esqArr);
					}
				}
			}
		}
		
		private function arrayUnidimencional($esqUsados) {
			$array = array();
			foreach ($esqUsados as $valores)
				$array[] = $valores->text;
			return $array;
		}
		
        function sistemaeliminar($idsistema) {
            DatSistema::eliminarsistema($idsistema);           
            $sistemashijos = DatSistema::obtenersistemashijos($idsistema);
            if(count($sistemashijos))
            { 
                foreach ($sistemashijos as $hijo) 
                {
                    if($idsistema != $hijo->idsistema)
                    {
                        $this->sistemaeliminar($hijo->idsistema);
                    }        
                }
                return true; 
            }
            else 
                return true; 
        }
        
        function modificarhijos($idsistema,$externa) { 
       $canthijos = DatSistema::obtenersistemashijos($idsistema);
       if(count($canthijos))
           { 
                foreach($canthijos as $hijos)
                {
                 $hijos->externa =  $externa;
                 $idsistema = $hijos->idsistema;
                 $hijos->save();
                 $this->modificarhijos($idsistema,$externa);
                }
           } 
       }
       
		function devolverXML($idsistema) {
			$sistemas = DatSistema::obtenersistemaexportarxml($idsistema)->toArray(true);
			if(count($sistemas)) {
				$menu = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><menu>";
				foreach ($sistemas as $mdf)
					$this->subsistemasxml($mdf,$menu);
				$menu .= "</menu>";
				return $menu;
			} else {
				$menu1 = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><menu></menu>";
				return $menu1;
			}
		}
    
		private function subsistemasxml($raiz, &$menu) {
			$menu .= "<MenuItem name=\"{$raiz['denominacion']}\" id=\"{$raiz['idsistema']}\" externa=\"{$raiz['externa']}\" icon=\"{$raiz['icono']}\"  status=\"{$raiz['descripcion']}\">" ;
			$this->funcionalidadesxml($raiz['idsistema'], $menu);
			$sistemashijos = DatSistema::cargarsistemahijjos($raiz['idsistema']);
			if(count($sistemashijos)) {
				foreach ($sistemashijos as $hijo)
					if($raiz['idsistema'] != $hijo['idsistema'])
						$this->subsistemasxml($hijo,$menu);
			}
			$menu .= "</MenuItem>";
		}
        
		private function funcionalidadesxml ($idsistema, &$menu) {
			$funcsistema = DatFuncionalidad::obtenerFuncionalidad($idsistema);
			if(count($funcsistema))
				foreach($funcsistema as $funcionalidades) {
					$menu .= "<MenuItem name=\"{$funcionalidades->text}\" id=\"{$funcionalidades->idfuncionalidad}\"  src=\"{$funcionalidades->referencia}\" icon=\"{$funcionalidades->icono}\"  status=\"{$funcionalidades->descripcion}\" index=\"{$funcionalidades->index}\">";
					$this->accionesxml($funcionalidades->idfuncionalidad, $menu);
					$menu .= "</MenuItem>";
				}
		}

		private function accionesxml ($idfuncionalidad, &$menu) {
			$acciones = DatAccion::obtenerAcciones($idfuncionalidad);
			if(count($acciones))
				foreach($acciones as $accion)
					$menu .= "<MenuItem name=\"{$accion->denominacion}\" id=\"{$accion->idaccion}\"  icon=\"{$accion->icono}\"  status=\"{$accion->descripcion}\" index=\"{$accion->abreviatura}\"/>" ;
		}
    
		function exportarsistemaAction() {
			$idsistema = $this->_request->getPost('idsistema');
			$xml = $this->devolverXML($idsistema);
			$file_name = 'sistemas.xml';
			header('Content-Type: application/octet-stream');
			header('Content-Type: application/force-download');
			header("Content-Disposition: inline; filename=\"{$file_name}\"");
			header('Pragma: no-cache');
			header('Expires: 0');
			echo $xml;
		}
    
		function importarXMLAction() {
			$idsistema= $this->_request->getPost('idsistema');	 
			$dir_file = $_FILES['fileUpload']['tmp_name'];
			if(file_exists($dir_file)) {
				$xmlcargado = simplexml_load_file($dir_file);
				$array = $this->arrayainsertar($xmlcargado, $idsistema);
				$this->showMessage('Se ha importado satisfactoriamente el sistema.');
			}
		}

		function arrayainsertar($xmlcargado, $idpadre) {
			foreach($xmlcargado as $xml) {
				if(!(string)$xml['src'] && !(string)$xml['index']) {
					$sistema = new DatSistema();
					$sistema->denominacion = (string)$xml['name'];
					$sistema->descripcion = (string)$xml['status'];
					$sistema->icono = (string)$xml['icon'];  
					$sistema->externa = (string)$xml['externa'];
					$sistema->iddominio = $this->global->Perfil->iddominio;
					if($idpadre)            
						$sistema->idpadre = $idpadre;
					$sistema->save();
					$sistemaCompartimentacion = new DatSistemaCompartimentacion();
					$sistemaCompartimentacion->idsistema = $sistema->idsistema;
					$sistemaCompartimentacion->iddominio = $this->global->Perfil->iddominio;
					$sistemaCompartimentacion->save();
					if(count($xml->MenuItem))
						$this->arrayainsertar($xml->MenuItem, $sistema->idsistema);
				} elseif (!(string)$xml['src']) {
					$accion = new DatAccion();
					$accion->denominacion = (string)$xml['name'];
					$accion->descripcion = (string)$xml['status'];
					$accion->icono = (string)$xml['icon']; 
					$accion->abreviatura = (string)$xml['index'];
					$accion->idfuncionalidad = $idpadre; 
					$accion->iddominio = $this->global->Perfil->iddominio;
					$accion->save();
					$accionCompartimentacion = new DatAccionCompartimentacion();
					$accionCompartimentacion->idaccion = $accion->idaccion;
					$accionCompartimentacion->iddominio = $this->global->Perfil->iddominio;
					$accionCompartimentacion->save();
				} else {
					$funcionalidad = new DatFuncionalidad();
					$funcionalidad->denominacion = (string)$xml['name'];
					$funcionalidad->descripcion = (string)$xml['status'];
					$funcionalidad->icono = (string)$xml['icon']; 
					$funcionalidad->referencia = (string)$xml['src'];
					$funcionalidad->index = (string)$xml['index'];
					$funcionalidad->idsistema = $idpadre; 
					$funcionalidad->iddominio = $this->global->Perfil->iddominio;
					$funcionalidad->save();
					$funcionalidadCompartimentacion = new DatFuncionalidadCompartimentacion();
					$funcionalidadCompartimentacion->idfuncionalidad = $funcionalidad->idfuncionalidad;
					$funcionalidadCompartimentacion->iddominio = $this->global->Perfil->iddominio;
					$funcionalidadCompartimentacion->save();
					if(count($xml->MenuItem))
						$this->arrayainsertar($xml->MenuItem, $funcionalidad->idfuncionalidad);
					}
	     		}
		}
  }
?>
