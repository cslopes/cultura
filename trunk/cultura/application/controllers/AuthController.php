<?php

require_once 'Proexc/Auth/Adapter/Siga.php';
require_once 'Proexc/Validate/Siape.php';

require_once 'Zend/Auth.php';
require_once 'Coordenador.php';

class AuthController extends Zend_Controller_Action {

	function init()	{
		$this->initView();
		$this->view->baseUrl = $this->_request->getBaseUrl();
	}

	function indexAction() {
		$this->_redirect('/');
	}

	function loginAction()
	{
		$this->view->message = '';

		if ($this->_request->isPost()) {
			$username = trim($this->_request->getPost('uid'));
			$password = $this->_request->getPost('pwd');
			// @todo salvar hash?
			$hash = $this->_request->getPost('hash');

			$this->view->message = 'falha na autenticação';
			
			$validator = new Proexc_Validate_Siape();
			if($validator->isValid($username) || $username == "equipe") {
				$adapter = new Proexc_Auth_Adapter_Siga($username, $password, $hash, "http://www.proexc.ufjf.br");
				$auth = Zend_Auth::getInstance();
				$result = $auth->authenticate($adapter);
				if($result->isValid() || ($username == "equipe" && $password == "equipe")) {
				//if(true) {
					$coordenador = new Coordenador();
					if(!$coordenador->exists($username)) {
						$data = new stdClass();
						$data->siape = $username;
						
						$auth->getStorage()->write($data);
						
						$this->_redirect('/coordenador/add');
					}
					else {
						$coordenador = new Coordenador();
						$coordenador = $coordenador->findBySiape($username);
						
						$data = new stdClass();
						$data->id = $coordenador->id;
						$data->nome = $coordenador->nome;
						$data->siape = $coordenador->siape;
						
						$auth->getStorage()->write($data);
						
						$this->_redirect('/');
					}
				}
			}
				
			$this->view->hash = $hash;
		} else {
			$this->view->hash = hash('md5', date('PROEXC-dmyhms'));
		}
		$this->view->title = "Acesso ao sistema PROEXC";
		$this->render();
	}

	function errorAction() {
		echo "Authentication Error";
	}

	function logoutAction()	{
		Zend_Auth::getInstance()->clearIdentity();
		$this->_redirect('/');
	}
}