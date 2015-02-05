<?php
	class IndexController extends ZendExt_Controller_Secure 
	{
		public function init ()
		{
			$this->model = new PortalModel();
			parent::init ();
		}
		
		public function cargardominioAction()
		{
			$security = ZendExt_Aspect_Security_Sgis::getInstance();
			$idestructuracomun = $this->_request->getPost('node');
			$dominios = $security->getDomain($idestructuracomun);
			if (count($dominios))
				echo json_encode($dominios);
			else
				echo json_encode(array());
		}
		
		public function entraralsistemaAction()
		{
			$register = Zend_Registry::getInstance();
			$tipoacceso = $this->_request->getPost('tipoacceso');
			if ($tipoacceso == 'nuevo' && !$register->session->close) {
				$register->session->close = true;
				echo json_encode(array('reload' => false));
			}
			else {
				$_SERVER['HTTP_X_REQUESTED_WITH'] = 1;
				$identidad = $this->_request->getPost('dominio');
				$integrator = ZendExt_IoC::getInstance();
				$access = $integrator->seguridad->VerificarAccesoEntidad($register->session->certificado, $identidad);
				if (!$access)
					throw new ZendExt_Exception ('EP009');
				if (!isset($register->session->entidad) || $identidad != $register->session->entidad->idestructura) {
					$estructura = $integrator->metadatos->DameEstructura($identidad);
					$register->session->entidad = $estructura[0];
					$cacheObj = ZendExt_Cache::getInstance();
					$cacheData = $cacheObj->load(session_id());
					$cacheData->entidad = $estructura[0];
					$cacheObj->save($cacheData, session_id(), 25200);
					$register->session->idestructura = $identidad;
				}
				echo json_encode(array('reload' => true));
			}
		}

		protected function getUsuario()
		{
			$registro = Zend_Registry::getInstance();
			$alias = $registro->session->usuario;
			$usuario = $this->model->buscarUsuarioByAlias($alias);
			if($usuario)
			{
				$usrArray = $usuario->toArray();
				$registro->session->perfil = $usrArray[0];
			    return $usrArray[0];
			}
			else
				throw new ZendExt_Exception('EP001');
		}
		
		public function indexAction ()
		{
			$perfil = $this->getPerfil();
			if ($perfil->tema)
			{
				//$this->view->dir_ext_ccs = $this->view->dir_ext_ccs . $perfil->tema . '/';
				$identidad = $this->global->Perfil->accesodirecto;
				$this->view->accesodirecto = $identidad;
				if ($identidad != 0) {
					$register = Zend_Registry::getInstance();
					$integrator = ZendExt_IoC::getInstance();
					$access = $integrator->seguridad->VerificarAccesoEntidad($register->session->certificado, $identidad);
					if (!$access)
						throw new ZendExt_Exception ('EP009');
					if (!isset($register->session->entidad) || $identidad != $register->session->entidad->idestructura) {
						$estructura = $integrator->metadatos->DameEstructura($identidad);
						$register->session->entidad = $estructura[0];
						$cacheObj = ZendExt_Cache::getInstance();
						$cacheData = $cacheObj->load(session_id());
						$cacheData->entidad = $estructura[0];
						$cacheObj->save($cacheData, session_id(), 25200);
						$register->session->idestructura = $identidad;
					}
				}
				//$dir_fich = str_replace('//','/',$_SERVER['DOCUMENT_ROOT'] . $this->view->dir_ext_ccs);
				//if (file_exists($dir_fich))
					$this->render();
				//else
					//throw new ZendExt_Exception('EP003');
			}
			else
				throw new ZendExt_Exception('EP002');
		}

		public function closeportalAction()
		{
			$registro = Zend_Registry::getInstance();
			$registro->session->close = true;
			echo json_encode(array('close'=>true));
		}
	}
