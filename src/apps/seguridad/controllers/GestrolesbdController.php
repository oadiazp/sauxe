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
	class GestrolesbdController extends ZendExt_Controller_Secure
	{
		protected $lista = array(
				'Tables'=>'TABLE',
				'Views'=>'VIEW',
				'Sequences'=>'SECUENCE',
				'Schemas'=>'SCHEMA',
				'Databases'=>'DATABASE',
				'Functions'=>'FUNCTION'
				);
				
		function init () {
			parent::init();
			$this->RSA = new ZendExt_RSA_Facade();
		}
		
		function gestrolesbdAction() {
			$this->render();
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
							$gestoresArr[$valores]['puerto'] = $valor->puerto;
							if($valor->gestor == 'pgsql')
								$gestoresArr[$valores]['icon'] = '../../views/images/pgadmin.png';
							else 
								$gestoresArr[$valores]['icon'] = '../../views/images/oracle.png';
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
 			 else {
				$user = $this->_request->getPost('user');
			 	$pass = $this->_request->getPost('passw');
			 	$gestor = $this->_request->getPost('gestor');
				$ipgestorbd = $this->_request->getPost('ipgestorbd');
				$idservidor = $this->_request->getPost('idservidor');
				$idgestor = $this->_request->getPost('idgestor');
				$puerto = $this->_request->getPost('puerto');
				$getDatabases = 'get' . ucfirst($gestor) . 'Databases';
			 	$arrayBD = $this->$getDatabases($gestor, $user, $pass, $ipgestorbd, $idgestor, $idservidor, $idsistema, $idnodo, $puerto);
			 	echo json_encode($arrayBD);

			}
		}
		
		private function getPgsqlDatabases($gestor, $user, $pass, $ipgestorbd, $idgestor, $idservidor, $idsistema, $idnodo, $puerto) {
			$bdArr = array();
			$dm = Doctrine_Manager::getInstance();
			$nameCurrentConn = $dm->getCurrentConnection()->getName();
			$conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd/template1", 'pg_catalog');
			$db = PgDatabase::getPgsqlDatabases($conn);
			$dm->setCurrentConnection($nameCurrentConn);
			if ($db->getData() != null) {
				foreach($db as $key=>$valor) {
					$bdArr[$key]['id'] = $valor->datname . '-' . $idnodo;
					$bdArr[$key]['text'] = $valor->datname;
					$bdArr[$key]['namebd'] = $valor->datname;
					$bdArr[$key]['gestor'] = $gestor;
					$bdArr[$key]['ipgestorbd'] = $ipgestorbd;
					$bdArr[$key]['puerto'] = $puerto;
					$bdArr[$key]['user'] = $user;
					$bdArr[$key]['passw'] = $this->RSA->encrypt($pass);
					$bdArr[$key]['idgestor'] = $idgestor;
					$bdArr[$key]['idservidor'] = $idservidor;
					$bdArr[$key]['icon'] = '../../views/images/server.PNG';
					$bdArr[$key]['type'] = $gestor;
					$bdArr[$key]['leaf'] = true;
					}
			}
            return $bdArr;
		}

		private function getOracleDatabases($gestor, $user, $pass, $ipgestorbd, $idgestor, $idservidor, $idsistema, $idnodo, $puerto, $SID = "orac") {
	                $oracle = new Oracle();
	                return $oracle->getOracleDatabases($gestor, $user, $pass, $ipgestorbd, $idgestor, $idservidor, $idsistema, $idnodo, $puerto, $SID);
	            }

		function loadInterfaceAction() {
			$opcion = $this->_request->getPost('tipo');
			$this->render($opcion);
		}
        
        function cargarRolesBDAction() {
            $gestor = $this->_request->getPost('gestor');
            $user = $this->_request->getPost('user');
            $pass = $this->RSA->decrypt($this->_request->getPost('passw'));
            $ipgestorbd = $this->_request->getPost('ip');
            $bd = $this->_request->getPost('bd');
            $limit = $this->_request->getPost('limit');
            $start = $this->_request->getPost('start');
            $nombreRol = $this->_request->getPost('nombreRol');
            $funcion = $gestor.'CargarRolesBD';
            $result = $this->$funcion($gestor, $user, $pass, $ipgestorbd, $bd, $limit, $start, $nombreRol);            
            echo json_encode($result);
        }
        
        private function pgsqlCargarRolesBD($gestor, $user, $pass, $ipgestorbd, $bd, $limit, $start, $nombreRol) {
            $dm = Doctrine_Manager::getInstance();
            $nameCurrentConn = $dm->getCurrentConnection()->getName();
            $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd/$bd", 'pg_catalog');
            if($nombreRol) {
	            $result = PgAuthid::getRolBDbyName($conn, $nombreRol, $limit, $start)->toArray();
	            $roles = $this->devolverDatosRol($result);
	            $cant =  PgAuthid::cantRolBDbyName($conn,$nombreRol);
	            }
            else {
	            $result = PgAuthid::getRolBD($conn, $limit, $start)->toArray();
	            $roles = $this->devolverDatosRol($result);
	            $cant = PgAuthid::cantRolBD($conn);
	            }
            $dm->setCurrentConnection($nameCurrentConn);
            return array('cantidad_filas' => $cant, 'datos' => $roles);	
        }

        function oracleCargarUsuariosBDAction() {
            $gestor = $this->_request->getPost('gestor');
            $puerto = $this->_request->getPost('puerto');
            $user = $this->_request->getPost('user');
            $passw = $this->_request->getPost('passw');
            $ipgestorbd = $this->_request->getPost('ip');
            $limit = $this->_request->getPost('limit');
            $start = $this->_request->getPost('start');
            $limit = $start + $limit-1;
            $SID = "orac";
            $oracle = new Oracle();
	        echo json_encode($oracle->getOracleUsers($gestor, $user, $passw, $ipgestorbd, $puerto, $SID, $limit, $start));
            }

        function oracleCargarRolesBDAction() {
            $gestor = $this->_request->getPost('gestor');
            $puerto = $this->_request->getPost('puerto');
            $user = $this->_request->getPost('user');
            $passw = $this->_request->getPost('passw');
            $ipgestorbd = $this->_request->getPost('ip');
            $limit = $this->_request->getPost('limit');
            $start = $this->_request->getPost('start');
            $limit = $start + $limit-1;
            $SID = "orac";
            $oracle = new Oracle();
	        echo json_encode($oracle->loadOracleRole($gestor, $user, $passw, $ipgestorbd, $puerto, $SID, $limit, $start));
            }

        function oracleInsertarUsuarioAction() {
            $oracle = new Oracle();
            $objInsert = new ZendExt_Db_Role_Oracle();
            $gestor = $this->_request->getPost('gestor');
            $puerto = $this->_request->getPost('puerto');
            $user = $this->_request->getPost('user');
            $pass = $this->RSA->decrypt($this->_request->getPost('passw'));
            $ipgestorbd = $this->_request->getPost('ip');
            $cadena = $this->_request->getPost('denominacion');
            $user_insert = strtoupper($cadena);
            $idservidor = $this->_request->getPost('idservidor');
            $idgestor = $this->_request->getPost('idgestor');
            $newpass = $this->_request->getPost('newpass');
            $nombre_externo = $this->_request->getPost('externo');  
            $radiobuton1 = $this->_request->getPost('radiobuton1');
            $radiobuton2 = $this->_request->getPost('radiobuton2');
            $radiobuton3 = $this->_request->getPost('radiobuton3');
            $SID = "orac";
            $conn = $oracle->getConexion($user, $pass, $ipgestorbd, $puerto, $SID);
            $objInsert->roleName = $user_insert;
            $objInsert->password = $newpass;
            $objInsert->type = 'OracleUser';
            if($radiobuton1 == on)
				$objInsert->opcion = 1;
            elseif($radiobuton2 == on)
            	$objInsert->opcion = 2;
            else{
            	$objInsert->opcion = 3;
            	$objInsert->externo = $nombre_externo;
            	}
            $objInsert->save($conn);
            oci_close($conn);
            $objRolesbd = new SegRolesbd();
            $objRolesbd->idservidor = $idservidor;
            $objRolesbd->idgestor = $idgestor;
            $objRolesbd->nombrerol = $user_insert;
            $objRolesbd->passw = $this->RSA->encrypt($newpass);
            $objRolesbd->save();
            $this->showMessage('Usuario insertado satisfactoriamente.');
            }

        function oracleEliminarUsuarioAction() {
        	$oracle = new Oracle();
            $gestor = $this->_request->getPost('gestor');
            $puerto = $this->_request->getPost('puerto');
            $user = $this->_request->getPost('user');
            $pass = $this->RSA->decrypt($this->_request->getPost('passw'));
            $ipgestorbd = $this->_request->getPost('ip');
            $userDelete = strtoupper($this->_request->getPost('userelim'));
            $idservidor = $this->_request->getPost('idservidor');
            $idgestor = $this->_request->getPost('idgestor');
            $SID = "orac";
            $conn = $oracle->getConexion($user, $pass, $ipgestorbd, $puerto, $SID);
            $objDelete = new ZendExt_Db_Role_Oracle();
            $objDelete->type = 'User';
            $objDelete->roleName = $userDelete;
            $objDelete->delete($conn);
            oci_close($conn);
            $exist = SegRolesbd::exist($userDelete, $idservidor, $idgestor);
            if(!$this->verifySistems($exist[0]->idrolesbd))
            	SegRolesbd::deleteRol($exist[0]->idrolesbd);
            $this->showMessage('Usuario eliminado satisfactoriamente.');
         }
         
        function oracleModificarUsuarioAction() {
        	$oracle = new Oracle();
            $gestor = $this->_request->getPost('gestor');
            $puerto = $this->_request->getPost('puerto');
            $user = $this->_request->getPost('user');
            $pass = $this->RSA->decrypt($this->_request->getPost('passw'));
            $ipgestorbd = $this->_request->getPost('ip');
            $idservidor = $this->_request->getPost('idservidor');
            $idgestor = $this->_request->getPost('idgestor');
            $userMod = strtoupper($this->_request->getPost('denominacion'));
            $newpass = $this->_request->getPost('newpass');
            $nombre_externo = $this->_request->getPost('externo');
            $radiobuton1 = $this->_request->getPost('radiobuton1');
            $radiobuton2 = $this->_request->getPost('radiobuton2');
            $radiobuton3 = $this->_request->getPost('radiobuton3');
            $SID = "orac";
            $conn = $oracle->getConexion($user, $pass, $ipgestorbd, $puerto, $SID); 
            $objMod = new ZendExt_Db_Role_Oracle();
			$objMod->roleName = $userMod;
			$objMod->new = false;
			$objMod->type = 'OracleUser';
            if($radiobuton1 == on){
				$objMod->password = $newpass;
              	$objMod->opcion = 1;
				$objMod->save($conn);
				oci_close($conn);
				$this->updateSegRolesbd($userMod, $userMod, $idservidor, $idgestor, $newpass);
            }
            else if ($radiobuton2 == on) {
				$objMod->opcion = 2;
				$objMod->save($conn);
				oci_close($conn);
            }
            else if ($radiobuton3 == on) {
				$objMod->opcion = 3;
				$objMod->save($conn);
				oci_close($conn);
            }
			$this->showMessage('Usuario modificado satisfactoriamente.');
        }

        function oracleInsertarRolAction() {
        	$oracle		 = new Oracle();
            $gestor 	 = $this->_request->getPost('gestor');
            $puerto 	 = $this->_request->getPost('puerto');
            $user 		 = $this->_request->getPost('user');
            $pass 		 = $this->RSA->decrypt($this->_request->getPost('passw'));
            $ipgestorbd  = $this->_request->getPost('ip');
            $rol_insert  = strtoupper($this->_request->getPost('denominacionR'));
            $pass_insert = $this->_request->getPost('newpass');
            $radiobuton1 = $this->_request->getPost('radiobuton7');
            $radiobuton2 = $this->_request->getPost('radiobuton4');
            $radiobuton3 = $this->_request->getPost('radiobuton5');
            $radiobuton4 = $this->_request->getPost('radiobuton6');
            $SID = "orac";
			$conn = $oracle->getConexion($user, $pass, $ipgestorbd, $puerto, $SID);
			if($oracle->exist($rol_insert,$conn))
				throw new ZendExt_Exception('SEGORAC01');
			$objInser = new ZendExt_Db_Role_Oracle();
			$objInser->roleName = $rol_insert;			
			$objInser->type = 'OracleRole'; 
            if($radiobuton1 == on)
				$objInser->opcion = 1;
                //$query = "CREATE ROLE $rol_insert NOT IDENTIFIED";
            else if ($radiobuton2 == on) {
            	$objInser->opcion = 2;
            	$objInser->password = $pass_insert;
            }
                //$query = "CREATE ROLE $rol_insert IDENTIFIED BY $pass_insert";
            else if ($radiobuton3 == on)
            	$objInser->opcion = 3;
                //$query = "CREATE ROLE $rol_insert IDENTIFIED EXTERNALLY";
            else if ($radiobuton4 == on)
            	$objInser->opcion = 4;
				//$query = "CREATE ROLE $rol_insert IDENTIFIED GLOBALLY";
				$objInser->save($conn);
				oci_close($conn);
				$this->showMessage('Rol insertado satisfactoriamente.');
            }

        //funcion de oracle para eliminar un rol
        //------------------------------------------------------------------------
        function oracleEliminarRolAction() {
            $gestor = $this->_request->getPost('gestor');
            $puerto = $this->_request->getPost('puerto');
            $user = $this->_request->getPost('user');
            $pass = $this->RSA->decrypt($this->_request->getPost('passw'));
            $ipgestorbd = $this->_request->getPost('ip');
            $SID = "orac";
            $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
            $conn = oci_connect("$user","$pass","$cadenaConex");

            $rol_eliminar=$this->_request->getPost('rol');

                  $query = "DROP ROLE $rol_eliminar";
                  $stid = oci_parse($conn, $query);
                  oci_execute($stid);

                  $this->showMessage('Rol eliminado satisfactoriamente.');

                  oci_close($conn);
            }

        //funcion de oracle para modificar un rol
        //-----------------------------------------------------------------------
        function oracleModificarRolAction() {
            $gestor = $this->_request->getPost('gestor');
            $puerto = $this->_request->getPost('puerto');
            $user = $this->_request->getPost('user');
            $pass = $this->RSA->decrypt($this->_request->getPost('passw'));
            $ipgestorbd = $this->_request->getPost('ip');
            $SID = "orac";
            $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
            $conn = oci_connect("$user","$pass","$cadenaConex");

            $cadena=$this->_request->getPost('rol');
			$rol_insert = strtoupper($cadena);
            $pass_insert=$this->_request->getPost('newpassr');
           
            $radiobuton1=$this->_request->getPost('radiobuton7');
            $radiobuton2=$this->_request->getPost('radiobuton4');
            $radiobuton3=$this->_request->getPost('radiobuton5');
            $radiobuton4=$this->_request->getPost('radiobuton6');

            if($radiobuton1==on){

              $query = "ALTER ROLE $rol_insert NOT IDENTIFIED";
              $stid = oci_parse($conn, $query);
              oci_execute($stid);
              $this->showMessage('Rol modificado satisfactoriamente.');
            }


            else if ($radiobuton2==on) {

              $query = "ALTER ROLE $rol_insert IDENTIFIED BY $pass_insert";
              $stid = oci_parse($conn, $query);
              oci_execute($stid);
              $this->showMessage('Rol modificado satisfactoriamente.');
            }


            else if ($radiobuton3==on) {

              $query = "ALTER ROLE $rol_insert IDENTIFIED EXTERNALLY";
              $stid = oci_parse($conn, $query);
              oci_execute($stid);
              $this->showMessage('Rol modificado satisfactoriamente.');
            }

            else if ($radiobuton4==on) {

              $query = "ALTER ROLE $rol_insert IDENTIFIED GLOBALLY";
              $stid = oci_parse($conn, $query);
              oci_execute($stid);
              $this->showMessage('Rol modificado satisfactoriamente.');
            }

           oci_close($conn);
        }
		
        //funcion de oracle para enviar a interfas el listado de roles que tiene un usuario
        //-----------------------------------------------------------------------
        function oracleMuestraRolesAsignadosUserAction() {

            $roles = array();
            $key=0;
            $check=false;
            $gestor = $this->_request->getPost('gestor');
            $puerto = $this->_request->getPost('puerto');
            $user = $this->_request->getPost('user');
            $pass = $this->RSA->decrypt($this->_request->getPost('passw'));
            $ipgestorbd = $this->_request->getPost('ip');
            $SID = "orac";
            $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
            $conn = oci_connect("$user","$pass","$cadenaConex");
            $limit = $this->_request->getPost('limit');
            $start = $this->_request->getPost('start');
            $limit=$start + $limit-1;

            $UsuarioRol = $this->_request->getPost('usuariorol');

            //$query="select * from dba_roles where role <> '$UsuarioRol'and PASSWORD_REQUIRED <> 'GLOBAL'";
            
           $query="select ROLE from
		            (
		            select rownum parte,ROLE
		            from (
		            select ROLE
		            from dba_roles where role <> '$UsuarioRol' and PASSWORD_REQUIRED <> 'GLOBAL'
		            )
		            )
		            where parte between $start and $limit";

            $stid = oci_parse($conn, $query);
            oci_execute($stid);
            
            $query1="select * from dba_role_privs where GRANTEE='$UsuarioRol'";
            $stid1 = oci_parse($conn, $query1);
         
            while ($row = oci_fetch_array($stid, OCI_ASSOC)) {
                
                $roles[$key]['rol'] = $row['ROLE'];
                $roles[$key]['grentee']=false;
                $roles[$key]['opcion']=false;
                $roles[$key]['pordefecto']=false;

                oci_execute($stid1);

                while ($row1 = oci_fetch_array($stid1, OCI_ASSOC)) {

                    if($row['ROLE']==$row1['GRANTED_ROLE']){

                        $roles[$key]['grentee']=true;

                        if($row1['ADMIN_OPTION']=="YES"){
                            $roles[$key]['opcion']=true;
                        }
                        if($row1['DEFAULT_ROLE']=="YES"){
                        $roles[$key]['pordefecto']=true;
                        }                        
                    }
                }                               
                $key++;
            }
			
            $arr=array();
            $consult_cant="select count (*) as cantidad from dba_roles where role <> '$UsuarioRol' and PASSWORD_REQUIRED <> 'GLOBAL'";
            $stid1 = oci_parse($conn, $consult_cant);
            oci_execute($stid1);
            $row = oci_fetch_array($stid1, OCI_ASSOC);
            $cantidad = $row['CANTIDAD'];

            $arr['cantidad_filas']  = $cantidad;
            $arr['datos'] = $roles;
			
          oci_close($conn);
          echo json_encode($arr);
        }

        //funcion de oracle para enviar a interfas el listado de roles que tiene un rol
        //------------------------------------------------------------------------

        function oracleMuestraRolesAsignadosRolAction() {

            $roles = array();
            $key=0;
            $check=false;
            $gestor = $this->_request->getPost('gestor');
            $puerto = $this->_request->getPost('puerto');
            $user = $this->_request->getPost('user');
            $pass = $this->RSA->decrypt($this->_request->getPost('passw'));
            $ipgestorbd = $this->_request->getPost('ip');
            $SID = "orac";
            $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
            $conn = oci_connect("$user","$pass","$cadenaConex");
            $limit = $this->_request->getPost('limit');
            $start = $this->_request->getPost('start');
            $limit=$start + $limit-1;

            $UsuarioRol = $this->_request->getPost('usuariorol');

            //$query="select * from dba_roles where role <> '$UsuarioRol'and PASSWORD_REQUIRED <> 'GLOBAL'";

            $query="select ROLE from
		            (
		            select rownum parte,ROLE
		            from (
		            select ROLE
		            from dba_roles where role <> '$UsuarioRol' and PASSWORD_REQUIRED <> 'GLOBAL'
		            )
		            )
		            where parte between $start and $limit";

            $stid = oci_parse($conn, $query);
            oci_execute($stid);

            $query1="select * from dba_role_privs where GRANTEE='$UsuarioRol'";
            $stid1 = oci_parse($conn, $query1);
           
            while ($row = oci_fetch_array($stid, OCI_ASSOC)) {

                $roles[$key]['rol'] = $row['ROLE'];
                $roles[$key]['grentee']=false;
                $roles[$key]['opcion']=false;
               
                oci_execute($stid1);

                while ($row1 = oci_fetch_array($stid1, OCI_ASSOC)) {

                    if($row['ROLE']==$row1['GRANTED_ROLE']){

                        $roles[$key]['grentee']=true;

                        if($row1['ADMIN_OPTION']=="YES"){
                            $roles[$key]['opcion']=true;
                        }  
                    }
                }
                $key++;
            }
			
            $arr=array();
            $consult_cant="select count (*) as cantidad from dba_roles where role <> '$UsuarioRol' and PASSWORD_REQUIRED <> 'GLOBAL'";
            $stid1 = oci_parse($conn, $consult_cant);
            oci_execute($stid1);
            $row = oci_fetch_array($stid1, OCI_ASSOC);
            $cantidad = $row['CANTIDAD'];

            $arr['cantidad_filas']  = $cantidad;
            $arr['datos'] = $roles;
			
          oci_close($conn);
          echo json_encode($arr);
        }

            
        //funcion de oracle para enviar a interfas el listado de privilegios que tiene un usuario o un rol
        //-----------------------------------------------------------------------
        function oracleMuestraPrivilegiosAsignadosAction() {
            $key = 0;
            $privilegios = array();
            $gestor = $this->_request->getPost('gestor');
            $puerto = $this->_request->getPost('puerto');
            $user = $this->_request->getPost('user');
            $pass = $this->RSA->decrypt($this->_request->getPost('passw'));
            $ipgestorbd = $this->_request->getPost('ip');
            $SID = "orac";
            $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
            $conn = oci_connect("$user","$pass","$cadenaConex");
            $limit = $this->_request->getPost('limit');
            $start = $this->_request->getPost('start');
            $limit=$start + $limit-1;

            $UsuarioRol = $this->_request->getPost('usuariorol');

			//$query="select distinct (PRIVILEGE) from dba_sys_privs order by PRIVILEGE";

           $query="select PRIVILEGE from
		            (
		            select rownum parte,
		            PRIVILEGE
		            from (
		            select distinct (PRIVILEGE)
		            from dba_sys_privs where PRIVILEGE <> 'ADMINISTER RESOURCE MANAGER' order by PRIVILEGE
		            )
		            )
		            where parte between $start and $limit";

            $stid = oci_parse($conn, $query);
            oci_execute($stid);

            $query1="select * from dba_sys_privs where GRANTEE='$UsuarioRol'";
            $stid1 = oci_parse($conn, $query1);
          
            while ($row = oci_fetch_array($stid, OCI_ASSOC)) {

                $privilegios[$key]['privilegio'] = $row['PRIVILEGE'];
                $privilegios[$key]['grantee']=false;
                $privilegios[$key]['opcion']=false;

                 oci_execute($stid1);
                 while ($row1 = oci_fetch_array($stid1, OCI_ASSOC)) {

                    if($row1['PRIVILEGE'] == $row['PRIVILEGE']){

                      $privilegios[$key]['grantee']=true;

                      if($row1['ADMIN_OPTION']=="YES"){
                        $privilegios[$key]['opcion']=true;
                      }
                    }
                }
               $key++;
            }
			
            $arr=array();
            $consult_cant="select count (distinct (PRIVILEGE)) as cantidad from dba_sys_privs order by PRIVILEGE";
            $stid1 = oci_parse($conn, $consult_cant);
            oci_execute($stid1);
            $row = oci_fetch_array($stid1, OCI_ASSOC);
            $cantidad = $row['CANTIDAD'];

            $arr['cantidad_filas']  = $cantidad;
            $arr['datos'] = $privilegios;
			
            oci_close($conn);
            echo json_encode($arr);

        }
        //Funcion de oracle para darle privilegios a un Usuario o Rol 
        //-----------------------------------------------------------------------
        function oracleDarPrivilegiosAction() {
            $arreglo_option = array();
            $gestor = $this->_request->getPost('gestor');
            $puerto = $this->_request->getPost('puerto');
            $user = $this->_request->getPost('user');
            $pass = $this->RSA->decrypt($this->_request->getPost('passw'));
            $ipgestorbd = $this->_request->getPost('ip');
            $SID = "orac";
            $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
            $conn = oci_connect("$user","$pass","$cadenaConex");

            $usuario = $this->_request->getPost('usuario');
            $arreglo = json_decode(stripslashes($this->_request->getPost('listadoPrivilegiosAcceso')));
            
                    
             foreach($arreglo as $datos) {
                         if ($datos[1]==false){
                             $query5="select * from dba_sys_privs where grantee = '$usuario' and privilege = '$datos[0]'";
			     $stid5 = oci_parse($conn, $query5);
                             oci_execute($stid5);
                             $row = oci_fetch_array($stid5, OCI_ASSOC);

                             if($row != Null){
                                 $query1="revoke $datos[0] from $usuario";
                                 $stid1 = oci_parse($conn, $query1);
                                 oci_execute($stid1);
				}
                            }
                     }

                     foreach($arreglo as $datos) {
                               if(($datos[1]==true) && ($datos[2]==true)){
                               $query2="GRANT $datos[0] TO $usuario WITH ADMIN OPTION";
			       $stid2 = oci_parse($conn, $query2);
			       oci_execute($stid2);
                                }
                     }

                        foreach($arreglo as $datos) {
                            if(($datos[1]==true) && ($datos[2]==false)){
                                $query5="select * from dba_sys_privs where grantee = '$usuario' and privilege = '$datos[0]'";
                                $stid5 = oci_parse($conn, $query5);
                                oci_execute($stid5);
				$row = oci_fetch_array($stid5, OCI_ASSOC);

                                if($row != Null){
                                 $query1="revoke $datos[0] from $usuario";
                                 $stid1 = oci_parse($conn, $query1);
                                 oci_execute($stid1);
				}

                             $query2="GRANT $datos[0] TO $usuario";
                             $stid2 = oci_parse($conn, $query2);
                             oci_execute($stid2);

			  }
                       }


         //   $this->showMessage('Privilegios asignados satisfactoriamente.');
            oci_close($conn);
        }
		
		
		 //Funcion de oracle para asignarle roles a un rol
        //-----------------------------------------------------------------------
        function oracleDarRolaRolAction() {
           
            $arreglo_option = array();
            $gestor = $this->_request->getPost('gestor');
            $puerto = $this->_request->getPost('puerto');
            $user = $this->_request->getPost('user');
            $pass = $this->RSA->decrypt($this->_request->getPost('passw'));
            $ipgestorbd = $this->_request->getPost('ip');
            $SID = "orac";
            $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
            $conn = oci_connect("$user","$pass","$cadenaConex");

            $usuario = $this->_request->getPost('usuario');
            $arreglo=json_decode(stripslashes($this->_request->getPost('listadoPrivilegiosAcceso')));
           
             foreach($arreglo as $datos) {
                         if ($datos[1]==false){
                             $query5="select * from dba_role_privs where grantee = '$usuario' and GRANTED_ROLE = '$datos[0]'";
			     $stid5 = oci_parse($conn, $query5);
                             oci_execute($stid5);
                             $row = oci_fetch_array($stid5, OCI_ASSOC);

                             if($row != Null){
                                 $query1="revoke $datos[0] from $usuario";
                                 $stid1 = oci_parse($conn, $query1);
                                 oci_execute($stid1);
				}
                            }
                     }

                     foreach($arreglo as $datos) {
                               if(($datos[1]==true) && ($datos[2]==true)){
                               $query2="GRANT $datos[0] TO $usuario WITH ADMIN OPTION";
			       $stid2 = oci_parse($conn, $query2);
			       oci_execute($stid2);
                                }
                     }

                        foreach($arreglo as $datos) {
                            if(($datos[1]==true) && ($datos[2]==false)){
                                $query5="select * from dba_role_privs where grantee = '$usuario' and GRANTED_ROLE = '$datos[0]'";
                                $stid5 = oci_parse($conn, $query5);
                                oci_execute($stid5);
				$row = oci_fetch_array($stid5, OCI_ASSOC);

                                if($row != Null){
                                 $query1="revoke $datos[0] from $usuario";
                                 $stid1 = oci_parse($conn, $query1);
                                 oci_execute($stid1);
				}

                             $query2="GRANT $datos[0] TO $usuario";
                             $stid2 = oci_parse($conn, $query2);
                             oci_execute($stid2);

			  }
                       }


          //  $this->showMessage('Privilegios asignados satisfactoriamente.');
            oci_close($conn);
        }
		
		
        //Funcion de oracle para asignar roles a un Usuario 
        //-----------------------------------------------------------------------
        function oracleAsignarRolesAUsuariosAction() {
            $arr = array();
            $auxiliar = array();
            $pos=0;
            $cadena=null;
            $gestor = $this->_request->getPost('gestor');
            $puerto = $this->_request->getPost('puerto');
            $user = $this->_request->getPost('user');
            $pass = $this->RSA->decrypt($this->_request->getPost('passw'));
            $ipgestorbd = $this->_request->getPost('ip');
            $SID = "orac";
            $cadenaConex = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL='TCP')(HOST=$ipgestorbd)(PORT=$puerto)))(CONNECT_DATA = (SID = $SID)))";
            $conn = oci_connect("$user","$pass","$cadenaConex");
		
            $usuario = $this->_request->getPost('usuario');
            $arreglo=json_decode(stripslashes($this->_request->getPost('listadoPrivilegiosAcceso')));
            $defecto=json_decode(stripslashes($this->_request->getPost('listadoDefecto')));
            $defectoFalse=json_decode(stripslashes($this->_request->getPost('listadoDefectoFalse')));


		    foreach($arreglo as $datos) {
                         if ($datos[1]==false){
                             $query5="select * from dba_role_privs where grantee = '$usuario' and GRANTED_ROLE = '$datos[0]'";
			     $stid5 = oci_parse($conn, $query5);
                             oci_execute($stid5);
                             $row = oci_fetch_array($stid5, OCI_ASSOC);
				 
                             if($row != Null){
                                 $query1="revoke $datos[0] from $usuario";
                                 $stid1 = oci_parse($conn, $query1);
                                 oci_execute($stid1);
				}
                            }
                     }

                     foreach($arreglo as $datos) {
                               if(($datos[1]==true) && ($datos[2]==true)){
                               $query2="GRANT $datos[0] TO $usuario WITH ADMIN OPTION";
			       $stid2 = oci_parse($conn, $query2);
			       oci_execute($stid2);
                                }
                     }

                        foreach($arreglo as $datos) {
                            if(($datos[1]==true) && ($datos[2]==false)){
                                $query5="select * from dba_role_privs where grantee = '$usuario' and GRANTED_ROLE = '$datos[0]'";
                                $stid5 = oci_parse($conn, $query5);
                                oci_execute($stid5);
				$row = oci_fetch_array($stid5, OCI_ASSOC);
				 
                                if($row != Null){
                                 $query1="revoke $datos[0] from $usuario";
                                 $stid1 = oci_parse($conn, $query1);
                                 oci_execute($stid1);
				}						 
				 
                             $query2="GRANT $datos[0] TO $usuario";
                             $stid2 = oci_parse($conn, $query2);
                             oci_execute($stid2);

			  }
                       }
                 

                        $query5="select GRANTED_ROLE,DEFAULT_ROLE,grantee from dba_role_privs where DEFAULT_ROLE='YES' and grantee = '$usuario'";
			$stid5 = oci_parse($conn, $query5);
			oci_execute($stid5);

		        while ($row = oci_fetch_array($stid5, OCI_ASSOC)) {
                                 $arr[$pos] = $row['GRANTED_ROLE'];
				 $pos++;
				}

                         
                       	$auxiliar=$arr;
			for($i=0;$i<count($arr);$i++){
                            for($j=0;$j<count($defectoFalse);$j++){
                               if ($arr[$i]==$defectoFalse[$j]){
                                    
                                    unset($auxiliar[$i]);
                                   
                                }
                            }
			}
                        
                        $arr = array_values($auxiliar);
                        
                       
                        $auxiliar = $arr;
                        for($i=0;$i<count($arr);$i++){
                            for($j=0;$j<count($defecto);$j++){
                               if ($arr[$i]==$defecto[$j]){
                                    
                                    unset($auxiliar[$i]);
                                }
                            }
			}

                        $arr = array_values($auxiliar);

                         

                        $cadena2 = implode(",", $defecto);
			$cadena1 = implode(",", $arr);
                       
                        if ($defecto[0]==null && $arr[0]!=null){
			$cadena=$cadena1;
			}
			if ($defecto[0]!=null && $arr[0]==null){
			$cadena=$cadena2;
			}
			if ($defecto[0]!=null && $arr[0]!=null){
			$cadena=$cadena2.",".$cadena1;
			}
			if ($defecto[0]==null && $arr[0]==null){
			$cadena="NONE";
			}


                        $query4="ALTER USER $usuario DEFAULT ROLE $cadena";
			$stid4 = oci_parse($conn, $query4);
			oci_execute($stid4);

                      
                      $auxiliar=null;
                     
            //$this->showMessage('Roles asignados satisfactoriamente.');
            oci_close($conn);
	}

        private function devolverDatosRol($array) {
         	foreach($array as $key=>$valores) {
	             $roles[$key]['oid'] = $valores['oid'];
	             $roles[$key]['rolname'] = $valores['rolname'];
	             $roles[$key]['rolsuper'] = $valores['rolsuper'];
	             $roles[$key]['rolinherit'] = $valores['rolinherit'];
	             $roles[$key]['rolcreaterole'] = $valores['rolcreaterole'];
	             $roles[$key]['rolcreatedb'] = $valores['rolcreatedb'];
	             $roles[$key]['rolcatupdate'] = $valores['rolcatupdate'];
	             $roles[$key]['rolcanlogin'] = $valores['rolcanlogin'];
	             $roles[$key]['rolpassword'] = $valores['rolpassword'];
	             if (isset($valores['rolvaliduntil'])) {
	              	$separar = explode(' ',$valores['rolvaliduntil']);
	              	$roles[$key]['fechainicio'] = $separar[0];
	              	$separar = explode(':',$separar[1]);
	              	$roles[$key]['horaaa'] = $separar[0].':'.$separar[1];
	             }
	             else {
	             	$roles[$key]['fechainicio'] = '';
	             	$roles[$key]['horaaa'] = '';
	             }
            }
         	return $roles;
        }
        
        function insertarRolBaseDatoAction() {
            $gestor = $this->_request->getPost('gestor');
            $function = $gestor.'Insert';
            $user = $this->_request->getPost('user');
            $pass = $this->RSA->decrypt($this->_request->getPost('passw'));
            $ipgestorbd = $this->_request->getPost('ip');
            $bd = $this->_request->getPost('bd');
            $idservidor = $this->_request->getPost('idservidor');
            $idgestor = $this->_request->getPost('idgestor');
            $roleName = $this->_request->getPost('rolname');
            $password = $this->_request->getPost('contrasena');
            $dateExpires = $this->_request->getPost('fechainicio');
            $hora = $this->_request->getPost('horaaa');
            $permisos = $this->_request->getPost('permisos');
            $rolsuper = $this->_request->getPost('rolsuper');
            $rolcreatedb = $this->_request->getPost('rolcreatedb');
            $rolcreaterole = $this->_request->getPost('rolcreaterole');
            $rolcatupdate = $this->_request->getPost('rolcatupdate');            
            $this->$function($ipgestorbd, $gestor, $bd, $user, $pass, $idservidor, $idgestor, $roleName, $password, $dateExpires, $hora, $permisos, $rolsuper, $rolcreatedb, $rolcreaterole, $rolcatupdate);
            $this->showMessage('Rol insertado satisfactoriamente');
        }
        
        function modificarRolBaseDatoAction() {
            $gestor = $this->_request->getPost('gestor');
            $user = $this->_request->getPost('user');
            $pass = $this->RSA->decrypt($this->_request->getPost('passw'));
            $ipgestorbd = $this->_request->getPost('ip');
            $bd = $this->_request->getPost('bd');
            $idservidor = $this->_request->getPost('idservidor');
            $idgestor = $this->_request->getPost('idgestor');
            $function = $gestor.'ModifyRole';            
            $oid = $this->_request->getPost('oid');
            $rolname = $this->_request->getPost('rolname');
            $password = $this->_request->getPost('contrasena');
            $dateExpires = $this->_request->getPost('fechainicio');
            $hora = $this->_request->getPost('horaaa');
            $permisos = $this->_request->getPost('permisos');
            $rolsuper = $this->_request->getPost('rolsuper');
            $rolcreatedb = $this->_request->getPost('rolcreatedb');
            $rolcreaterole = $this->_request->getPost('rolcreaterole');
            $rolcatupdate = $this->_request->getPost('rolcatupdate');
            $this->$function($ipgestorbd, $gestor, $bd, $user, $pass, $idservidor, $idgestor, $oid, $rolname, $password, $dateExpires, $hora, $permisos, $rolsuper, $rolcreatedb, $rolcreaterole, $rolcatupdate);
            $this->showMessage('Rol modificado satisfactoriamente');
        }
        
        function eliminarRolesDBAction() {
        	$gestor = $this->_request->getPost('gestor');
            $user = $this->_request->getPost('user');
            $pass = $this->RSA->decrypt($this->_request->getPost('passw'));
            $ipgestorbd = $this->_request->getPost('ip');
            $bd = $this->_request->getPost('bd');
            $function = $gestor.'DeleteRole';            
            $oid = $this->_request->getPost('oid');
            $rolname = $this->_request->getPost('rolname');
            $idservidor = $this->_request->getPost('idservidor');
            $idgestor = $this->_request->getPost('idgestor');
            $this->$function($ipgestorbd, $gestor, $bd, $idservidor, $idgestor, $user, $pass, $oid , $rolname);
            $this->showMessage('Rol eliminado satisfactoriamente');
        }
        
		private function pgsqlInsert($ipgestorbd, $gestor, $bd, $user, $pass, $idservidor, $idgestor, $roleName, $password, $dateExpires, $hora, $permisos, $rolsuper, $rolcreatedb, $rolcreaterole, $rolcatupdate) {
			$dm = Doctrine_Manager::getInstance();
			$nameCurrentConn = $dm->getCurrentConnection()->getName();
            $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd/$bd", 'pg_catalog');
        	$newObj = new ZendExt_Db_Role_Pgsql();
            $newObj->roleName = $roleName;
            $newObj->password = $password;
            $newObj->dateExpires = $dateExpires;
            if($hora)
                $newObj->timeExpires = $hora;
            if($permisos == 'on')
                $newObj->inherit = true;
            if($rolsuper == 'on')
                $newObj->superUser = true;
            if($rolcreatedb == 'on')
                $newObj->canCreateDb = true;
            if($rolcreaterole == 'on')
                $newObj->canCreateRole = true;
            if($rolcatupdate == 'on')
                $newObj->canUpdateCat = true;	
            $newObj->save($conn);
            $dm->setCurrentConnection($nameCurrentConn);
            $objRolesbd = new SegRolesbd();
            $objRolesbd->idservidor = $idservidor;
            $objRolesbd->idgestor = $idgestor;
            $objRolesbd->nombrerol = $roleName;            
            $objRolesbd->passw = $this->RSA->encrypt($password);
            $objRolesbd->save();
        }
        
        private function pgsqlModifyRole($ipgestorbd, $gestor, $bd, $user, $pass, $idservidor, $idgestor, $oid, $rolname, $password, $dateExpires, $hora, $permisos, $rolsuper, $rolcreatedb, $rolcreaterole, $rolcatupdate) {
        	$dm = Doctrine_Manager::getInstance();
			$nameCurrentConn = $dm->getCurrentConnection()->getName();
            $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd/$bd", 'pg_catalog');
        	$newObj = new ZendExt_Db_Role_Pgsql();
        	$result = $newObj->find($oid,$conn);
        	$name = $result->lastFindObject->rolname;
            $result->roleName = $rolname;
            $result->dateExpires = $dateExpires;
            $result->timeExpires = $hora;
            if($password)
                $result->password = $password;
            if($permisos == 'on')
                $result->inherit = true;
            else 
            	$result->inherit = false;
            if($rolsuper == 'on')
                $result->superUser = true;
            else 
            	$result->superUser = false;
            if($rolcreatedb == 'on')
                $result->canCreateDb = true;
            else 
            	$result->canCreateDb = false;
            if($rolcreaterole == 'on')
                $result->canCreateRole = true;
            else 
            	$result->canCreateRole = false;
            if($rolcatupdate == 'on')
                $result->canUpdateCat = true;
            else 
            	$result->canUpdateCat = false;
            $result->save($conn);
            $dm->setCurrentConnection($nameCurrentConn);
            $this->updateSegRolesbd($name, $rolname, $idservidor, $idgestor, $password);
        }
        
        private function updateSegRolesbd($name, $rolname, $idservidor, $idgestor, $password) {
	        if($name != $rolname){
	            	$exist = SegRolesbd::exist($name, $idservidor, $idgestor);
	            	if (count($exist)) {
	            		$objRolesbd = Doctrine::getTable('SegRolesbd')->find($exist[0]->idrolesbd);
		            	$objRolesbd->nombrerol = $rolname;
		            	if ($password)
		            		$objRolesbd->passw = $this->RSA->encrypt($password);  
		            	$objRolesbd->save(); return;
	            	}
	            	else {
	            		$obj = new SegRolesbd();
	            		$obj->idservidor = $idservidor;
	            		$obj->idgestor = $idgestor;
	            		$obj->nombrerol = $rolname;
	            		$obj->passw = $this->RSA->encrypt($password);
	            		$obj->save(); return;
	            	}
	            }
	            elseif($password) {
	            	$exist = SegRolesbd::exist($rolname, $idservidor, $idgestor);
	            	if (count($exist)) {
	            		$objRolesbd = Doctrine::getTable('SegRolesbd')->find($exist[0]->idrolesbd);
	            		$objRolesbd->passw = $this->RSA->encrypt($password);
	            		$objRolesbd->save(); return;
	            	}
	            	else {
	            		$obj = new SegRolesbd();
	            		$obj->idservidor = $idservidor;
	            		$obj->idgestor = $idgestor;
	            		$obj->nombrerol = $rolname;
	            		$obj->passw = $this->RSA->encrypt($password);
	            		$obj->save(); return;
	            	}
	            }
        }
        
        private function pgsqlDeleteRole($ipgestorbd, $gestor, $bd, $idservidor, $idgestor, $user, $pass, $oid , $rolname) {
        	$exist = SegRolesbd::exist($rolname, $idservidor, $idgestor);
        	if(!$this->verifySistems($exist[0]->idrolesbd)) {
        		SegRolesbd::deleteRol($exist[0]->idrolesbd);
        		$dm = Doctrine_Manager::getInstance();
				$nameCurrentConn = $dm->getCurrentConnection()->getName();
            	$conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd/$bd", 'pg_catalog');
	        	$newObj = new ZendExt_Db_Role_Pgsql();
	        	$result = $newObj->find($oid,$conn);
	        	$result->delete($conn);
	        	$dm->setCurrentConnection($nameCurrentConn);
        	}
        	else throw new ZendExt_Exception('SEGRBD01');
        }
        
        private function verifySistems($idrolesbd) {
        	if(DatSistemaDatServidores::countSistemsByIdrolesBd($idrolesbd) > 0)
        		return true;
        	return false;
        }
        
        function getcriteriosAction(){
        $datos = array(0=>array("criterio"=>"Tables"),1=>array("criterio"=>"Views"),2=>array("criterio"=>"Sequences"),3=>array("criterio"=>"Schemas"),4=>array("criterio"=>"Databases"),5=>array("criterio"=>"Functions"));
        echo json_encode($datos);
        }
        
        function configridAction(){
         $result = $result = array ('grid' => array ('columns' => array () ) );
         $criterio = $this->_request->getPost ( 'criterio' );
         switch ($criterio){
         case "Tables":{
            $fields =  array(0=>"name",1=>"OWN",2=>"SEL",3=>"INS",4=>"UPD",5=>"DEL",6=>"REF","TRIG");
         }break;
         case 'Views':{
             $fields =  array(0=>"name",1=>"OWN",2=>"SEL",3=>"INS",4=>"UPD",5=>"DEL",6=>"REF","TRIG");
         }break; 
         case "Sequences":{
             $fields =  array(0=>"name",1=>"SEL",2=>"UPD",3=>"USG"); 
         }break;     
         case "Schemas":{
             $fields =  array(0=>"name",1=>"OWN",2=>"CRT",3=>"USG"); 
         }break;    
         case "Databases":{
             $fields =  array(0=>"name",1=>"OWN",2=>"CONN",3=>"CRT",4=>"TMP");
         }break;   
         case "Functions":{
             $fields =  array(0=>"name",1=>"OWN",2=>"EXEC");
          }break;                
         }                   
         foreach ( $fields as $field ) { 
            $header = ucwords($field);
            if($field == 'name')
            	$result ['grid'] ['columns'] [] = array ('header' => $header, 'width' => 300, 'sortable' => true, 'dataIndex' => $field,'editor'=>true);
            else
            	$result ['grid'] ['columns'] [] = array ('header' => $header, 'width' => 50, 'sortable' => true, 'dataIndex' => $field,'editor'=>true);
            $result ['grid'] ['campos'] [] = $field;
        }
        echo json_encode ( $result );
        }
        
        function cargargriddatosAction() {
	        $limit = $this->_request->getPost ('limit');
	        $idrolselec = $this->_request->getPost ('idrolselec');
	        $offset = $this->_request->getPost ('start');
	        $rolbd = $this->_request->getPost ('rolbd');
	        $gestor = $this->_request->getPost('gestor');
	        $criterio = $this->_request->getPost('criterio'); 
	        $user = $this->_request->getPost('user');
	        $pass = $this->RSA->decrypt($this->_request->getPost('passw'));
	        $ipgestorbd = $this->_request->getPost('ip');
	        $bd = $this->_request->getPost('bd');
	        $esqSelected = $this->_request->getPost('esqSelected');
	        $dm = Doctrine_Manager::getInstance();
	        $nameCurrentConn = $dm->getCurrentConnection()->getName();
	        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd/$bd", 'pg_catalog');
	        $funcion = $gestor.'DataByCritery'; 
	        $result = $this->$funcion($conn, $limit, $offset, $criterio, $rolbd, $idrolselec, $esqSelected);
	        $dm->setCurrentConnection($nameCurrentConn);
	        echo json_encode($result);
        }
        
		private function pgsqlDataByCritery($conn, $limit, $offset, $criterio, $rolbd, $idrolselec, $esqSelected){
	         switch ($criterio){         	
	         case "Tables":{
	         	if ($esqSelected) {
	         		$result = PgNamespace::getInformationByCriteria($conn, $criterio, $esqSelected, $limit, $offset)->toArray(true);
	            	$filas = PgNamespace::getCantRecordsByCriteria($conn, $criterio, $esqSelected);
	         	}
	         	else {
	         		$result = PgNamespace::getInformation($conn, $criterio, $limit, $offset)->toArray(true);
	            	$filas = PgNamespace::getCantRecords($conn, $criterio);
	         	}
                	foreach($result as $key=>$valores){
                    	$datos[$key]['name'] = $valores['PgNamespace']['nspname'].'.'.$valores['name'];
                        if($idrolselec == $valores['relowner'])
                            $datos[$key]['OWN'] = true;
                        else 
                            $datos[$key]['OWN'] = false;
                        $result = $this->armarPermisos($valores['relacl'], $rolbd, $criterio);
                        if(isset($result['INS']))
                            $datos[$key]['INS'] = $result['INS']; else $datos[$key]['INS'] = false;
                        if(isset($result['SEL']))
			        		$datos[$key]['SEL'] = $result['SEL']; else $datos[$key]['SEL'] = false;
			       		if(isset($result['UPD']))
			        		$datos[$key]['UPD'] = $result['UPD']; else $datos[$key]['UPD'] = false;
			        	if(isset($result['DEL']))
			        		$datos[$key]['DEL'] = $result['DEL']; else $datos[$key]['DEL'] = false;
			        	if(isset($result['REF']))
			        		$datos[$key]['REF'] = $result['REF']; else $datos[$key]['REF'] = false;
			        	if(isset($result['TRIG']))
			        		$datos[$key]['TRIG'] = $result['TRIG']; else $datos[$key]['TRIG'] = false;
                        }
	            $array = array('cantidad'=>$filas,'datos'=>$datos);
	         }break;
	         case 'Views':{
	         	if ($esqSelected) {
	         		$result = PgNamespace::getInformationByCriteria($conn, $criterio, $esqSelected, $limit, $offset)->toArray(true);
	            	$filas = PgNamespace::getCantRecordsByCriteria($conn, $criterio, $esqSelected);
	         	}
	         	else {
	         		$result = PgNamespace::getInformation($conn, $criterio, $limit, $offset)->toArray(true);
	                $filas = PgNamespace::getCantRecords($conn, $criterio);
	         	}
	                        foreach($result as $key=>$valores){
	                            $datos[$key]['name'] = $valores['PgNamespace']['nspname'].'.'.$valores['name'];
	                            if($idrolselec == $valores['relowner'])
	                            	$datos[$key]['OWN'] = true;
	                            else 
	                            	$datos[$key]['OWN'] = false;
	                            $result = $this->armarPermisos($valores['relacl'], $rolbd, $criterio);
	                            if(isset($result['INS']))
	                            	$datos[$key]['INS'] = $result['INS']; else $datos[$key]['INS'] = false;
	                            if(isset($result['SEL']))
						        	$datos[$key]['SEL'] = $result['SEL']; else $datos[$key]['SEL'] = false;
						        if(isset($result['UPD']))
						        	$datos[$key]['UPD'] = $result['UPD']; else $datos[$key]['UPD'] = false;
						        if(isset($result['DEL']))
						        	$datos[$key]['DEL'] = $result['DEL']; else $datos[$key]['DEL'] = false;
						        if(isset($result['REF']))
						        	$datos[$key]['REF'] = $result['REF']; else $datos[$key]['REF'] = false;
						        if(isset($result['TRIG']))
						        	$datos[$key]['TRIG'] = $result['TRIG']; else $datos[$key]['TRIG'] = false;
	                        }
	            $array = array('cantidad'=>$filas,'datos'=>$datos);
	         }break; 
	         case "Sequences":{
	         	if ($esqSelected) {
	         		$result = PgNamespace::getInformationByCriteria($conn, $criterio, $esqSelected, $limit, $offset)->toArray(true);
	            	$filas = PgNamespace::getCantRecordsByCriteria($conn, $criterio, $esqSelected);
	         	}
	         	else {
	         		$result = PgNamespace::getInformation($conn, $criterio, $limit, $offset)->toArray(true);
	            	$filas = PgNamespace::getCantRecords($conn, $criterio);
	         	}
	                        foreach($result as $key=>$valores){
	                            $datos[$key]['name'] = $valores['PgNamespace']['nspname'].'.'.$valores['name'];
	                            if($idrolselec == $valores['relowner'])
	                            	$datos[$key]['OWN'] = true;
	                             else 
	                            	$datos[$key]['OWN'] = false;
	                            $result = $this->armarPermisos($valores['relacl'], $rolbd, $criterio);
						        if(isset($result['SEL']))
						        	$datos[$key]['SEL'] = $result['SEL']; else $datos[$key]['SEL'] = false;
						        if(isset($result['UPD']))
						        	$datos[$key]['UPD'] = $result['UPD']; else $datos[$key]['UPD'] = false;
						        if(isset($result['USG']))
						        	$datos[$key]['USG'] = $result['USG']; else $datos[$key]['USG'] = false;
	                        }
	            $array = array('cantidad'=>$filas,'datos'=>$datos);
	         }break;     
	         case "Schemas":{
	         	if ($esqSelected){
	         		$result = PgNamespace::getInformationSchemasByCriteria($conn, $esqSelected, $limit, $offset)->toArray(true);
	            	$filas = PgNamespace::getRecordsSchemasByCriteria($conn, $esqSelected);
	         	}
	         	else {
	         		$result = PgNamespace::getInformationSchemas($conn, $limit, $offset)->toArray(true);
	            	$filas = PgNamespace::getRecordsSchemas($conn);
	         	}
	                        foreach($result as $key=>$valores){
	                            $datos[$key]['name'] = $valores['nspname'];
	                            if($valores['nspowner'] == $idrolselec)
	                            	$datos[$key]['OWN'] = true;
	                             else 
	                            	$datos[$key]['OWN'] = false;
	                            $result = $this->armarPermisos($valores['nspacl'], $rolbd, $criterio);
	                            if(isset($result['CRT']))
						        	$datos[$key]['CRT'] = $result['CRT']; else $datos[$key]['CRT'] = false;
	                            if(isset($result['USG']))
						        	$datos[$key]['USG'] = $result['USG']; else $datos[$key]['USG'] = false;
	                        }
	                $array = array('cantidad'=>$filas,'datos'=>$datos);
	         }break;    
	         case "Databases":{
	         	if ($esqSelected) {
	         		$result = PgDatabase::getInformationDatabasesByCriteria($conn, $esqSelected, $limit, $offset)->toArray(true);
	            	$filas = PgDatabase::getRecordsDatabasesByCriteria($conn, $esqSelected);
	         	}
	         	else {
	         		$result = PgDatabase::getInformationDatabases($conn, $limit, $offset)->toArray(true);
	            	$filas = PgDatabase::getRecordsDatabases($conn);
	         	}
	                        foreach($result as $key=>$valores){
	                            $datos[$key]['name'] = $valores['datname'];
	                            if($valores['datdba'] == $idrolselec)
	                            	$datos[$key]['OWN'] = true;
	                             else 
	                            	$datos[$key]['OWN'] = false;
                            	$result = $this->armarPermisos($valores['datacl'], $rolbd, $criterio);
                            	if(isset($result['CRT']))
						        	$datos[$key]['CRT'] = $result['CRT']; else $datos[$key]['CRT'] = false;
						        if(isset($result['CONN']))
						        	$datos[$key]['CONN'] = $result['CONN']; else $datos[$key]['CONN'] = false;
						        if(isset($result['TMP']))
						        	$datos[$key]['TMP'] = $result['TMP']; else $datos[$key]['TMP'] = false;
	                        }
	                $array = array('cantidad'=>$filas,'datos'=>$datos);
	         }break;   
	         case "Functions":{
	         	if ($esqSelected) {
	         		$result = PgProc::getInformationByCriteria($conn, $esqSelected, $limit, $offset)->toArray(true);
	            	$filas = PgProc::getCantRecordsByCriteria($conn, $esqSelected);
	         	}
	         	else {
	         		$result = PgProc::getInformation($conn, $limit, $offset)->toArray(true);
	            	$filas = PgProc::getCantRecords($conn);
	         	}
                 foreach($result as $key=>$valores){
                     $datos[$key]['name'] = $valores['PgNamespace']['nspname'].'.'.$valores['proname'];
                     if($valores['proowner'] == $idrolselec)
                        $datos[$key]['OWN'] = true;
                     else 
                        $datos[$key]['OWN'] = false;
                     $result = $this->armarPermisos($valores['proacl'], $rolbd, $criterio);
                     if(isset($result['EXEC']))
				        $datos[$key]['EXEC'] = $result['EXEC']; else $datos[$key]['EXEC'] = false;
                     }
	                $array = array('cantidad'=>$filas,'datos'=>$datos);
	          }break;
	        }
	        return $array;
			}
        
        private function armarPermisos($permisos, $rolbd, $criterio) {
        $cadena = $this->armaCadena($permisos);
        $array = explode(',',$cadena);
        $result = array();
        foreach ($array as $valor) {
        	$valor = explode('=',$valor);
        	if($valor[0] == $rolbd){
        		$function = 'pgsql'.$criterio;
        		$result = $this->$function($valor[1]);
        		break;
        	}
        	else continue;        		
        	}
        return $result;	
        }
		
		private function armaCadena($nspacl){
        	for ($i = 1; $i < strlen($nspacl)-1; $i++)
        		$cadena .= $nspacl[$i];
        	return $cadena;
        }
        
		private function pgsqlTables($valor) {
        	$val = explode('/',$valor);
        	$result['INS'] = $this->validar($val[0], 'a');
        	$result['SEL'] = $this->validar($val[0], 'r');
        	$result['UPD'] = $this->validar($val[0], 'w');
        	$result['DEL'] = $this->validar($val[0], 'd');
        	$result['REF'] = $this->validar($val[0], 'x');
        	$result['TRIG'] = $this->validar($val[0],'t');
        	return $result;
	        }
        
	    private function pgsqlViews($valor) {
	    	$val = explode('/',$valor);
        	$result['INS'] = $this->validar($val[0], 'a');
        	$result['SEL'] = $this->validar($val[0], 'r');
        	$result['UPD'] = $this->validar($val[0], 'w');
        	$result['DEL'] = $this->validar($val[0], 'd');
        	$result['REF'] = $this->validar($val[0], 'x');
        	$result['TRIG'] = $this->validar($val[0],'t');
        	return $result;
	    	}    
	    
		private function pgsqlSequences($valor) {
    		$val = explode('/',$valor);
        	$result['SEL'] = $this->validar($val[0], 'r');
        	$result['UPD'] = $this->validar($val[0], 'w');
        	$result['USG'] = $this->validar($val[0],'U');
        	return $result;	
		    }

		private function pgsqlSchemas($valor) {
        	$val = explode('/',$valor);
        	$result['USG'] = $this->validar($val[0],'U');
        	$result['CRT'] = $this->validar($val[0],'C');
        	return $result;
	        }
		    
		private function pgsqlDatabases($valor) {
			$val = explode('/',$valor);
			$result['CRT'] = $this->validar($val[0],'C');
			$result['CONN'] = $this->validar($val[0],'c');
			$result['TMP'] = $this->validar($val[0],'T');
        	return $result;			    
			}

		private function pgsqlFunctions($valor) {
			$val = explode('/',$valor);
			$result['EXEC'] = $this->validar($val[0],'X');
        	return $result;	
			}
			
		private function validar($cadena, $caracter) {
			for($i =0 ; $i<strlen($cadena); $i++)
				if($cadena[$i] == $caracter)
					return true;
				return false;
			} 
		
		function modificarPermisosAction () {
			$arrayAccess = json_decode(stripslashes($this->_request->getPost ('acceso')));
			$arrayDeny = json_decode(stripslashes($this->_request->getPost ('denegado')));
			$usuariobd = $this->_request->getPost ('usuariobd');
	        $rolbd = $this->_request->getPost ('rolbd');
	        $gestor = $this->_request->getPost('gestor');
	        $criterio = $this->_request->getPost('criterio'); 
	        $user = $this->_request->getPost('user');
	        $pass = $this->RSA->decrypt($this->_request->getPost('passw'));
	        $ipgestorbd = $this->_request->getPost('ip');
	        $bd = $this->_request->getPost('bd');
	        $dm = Doctrine_Manager::getInstance();
	        $nameCurrentConn = $dm->getCurrentConnection()->getName();
	        $conn = $dm->openConnection("$gestor://$user:$pass@$ipgestorbd/$bd", 'pg_catalog');
	        $obj = new ZendExt_Db_Role_Pgsql();
	        $obj->modifyAccess($arrayAccess, $arrayDeny, $conn, $usuariobd, $this->lista[$criterio]);
	        $dm->setCurrentConnection($nameCurrentConn);
	        echo("{'codMsg':1,'mensaje':'Permisos asignados satisfactoriamente.'}");
		}
	}
?>
