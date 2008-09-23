<?php

require_once 'Proexc/Admin/Controller/Action.php';

require_once 'Zend/Validate/NotEmpty.php';

require_once 'Usuario.php';

class Admin_UsuarioController extends Proexc_Admin_Controller_Action {

	function preDispatch() {
		parent::preDispatch();
		parent::checkAccess('usuario', null);
	}
	
	function addAction()
	{
		$this->view->title = 'Cadastrar Usuário';
			
		$this->form();

		$this->render();
	}

	function listAction() {
		$this->view->title = 'Buscar Usuário';

		$params = $this->_request->getParams();
		
		if(isset($params['s'])) {
			$argument = $this->_request->getParam('s', null);
			
			$tabUsuario = new Usuario();
			$this->view->usuarioList = $tabUsuario->findByNome($argument);
		}
	}

	function editAction()
	{
		$this->view->title = 'Editar Usuário';
			
		$this->form('edit');
			
		$this->render();
	}

	function deleteAction() {
		$login = $this->_request->getParam('login');

		$tabUsuario = new Usuario();
		$tabUsuario->deleteByLogin($login);

		$this->_redirect('/admin/Usuario/list');
	}

	private function form($tipo = 'add') {
		$tabUsuario = new Usuario();

		if($this->_request->isPost()) {
			$errors = null;
				
			$validator = new Zend_Validate_NotEmpty();
			$nome = $this->_request->getPost('nome');
			if(!$validator->isValid($nome))
			foreach ($validator->getMessages() as $message) $errors[] = $message;
				
			$login = $this->_request->getPost('login');
			if(!$validator->isValid($login))
			foreach ($validator->getMessages() as $message) $errors[] = $message;
			
			$role = $this->_request->getPost('role');
			
			$password = $this->_request->getPost('pwd');
			$password2 = $this->_request->getPost('pwd2');

			if(!$validator->isValid($password))
			foreach ($validator->getMessages() as $message) $errors[] = $message;
			if($password != $password2) $errors[] = "Passwords diferentes";

			if(!$errors) {
				$data = array(
    				'nome'	=> $nome,
					'login'	=> $login,
					'role'	=> $role,
					'senha'	=> md5($password)
				);

				if($tipo == 'add')
				$tabUsuario->insert($data);
				else if($tipo == 'edit') {
					$tabUsuario->updateByLogin($data, $login);
					$this->_redirect("/admin/usuario/add");
				}
			}
			$this->view->errors = $errors;
		} 
		$login = $this->_request->getParam('login');
			
		$this->view->usuario 			= new stdClass();
		$this->view->usuario->nome 		= null;
		$this->view->usuario->login 	= null;
		$this->view->usuario->password 	= null;

		if($login != null)
			$this->view->usuario = $tabUsuario->findByLogin($login);
	}

}