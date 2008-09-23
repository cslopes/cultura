<?php


require_once 'Proexc/Validate/Siape.php';

require_once 'Zend/Auth.php';
require_once 'Zend/Auth/Adapter/DbTable.php';
require_once 'Zend/Registry.php';

require_once 'Coordenador.php';

class Admin_AuthController extends Zend_Controller_Action {

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
			$username = trim($this->_request->getPost('username'));
			$password = $this->_request->getPost('password');

			$this->view->message = 'falha na autenticação';
				
			$db = Zend_Registry::get('db');
			$adapter = new Zend_Auth_Adapter_DbTable($db, 'usuario', 'login', 'senha', 'MD5(?)');
			$adapter->setIdentity($username)
					->setCredential($password);

			$auth = Zend_Auth::getInstance();
			$result = $auth->authenticate($adapter);
			if($result->isValid()) {
				$resultRowObject = $adapter->getResultRowObject();
				
				$data = new stdClass();
				$data->role = $resultRowObject->role;
				$data->nome = $auth->getIdentity();
				$data->login = $auth->getIdentity();
				
				$auth->getStorage()->write($data);

				$this->_redirect('admin/');
			}
		}
		$this->view->title = "Admin";
		$this->render();
	}

	function errorAction() {
		echo "Authentication Error";
	}

	function logoutAction()	{
		Zend_Auth::getInstance()->clearIdentity();
		$this->_redirect('admin');
	}
}