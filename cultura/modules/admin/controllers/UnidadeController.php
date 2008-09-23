<?php

require_once 'Proexc/Admin/Controller/Action.php';

require_once 'Zend/Validate/Alpha.php';

require_once 'Unidade.php';

class Admin_UnidadeController extends Proexc_Admin_Controller_Action {

	function preDispatch() {
		parent::preDispatch();
		parent::checkAccess('unidade', null);
	}
	
	function init() {
		parent::init();
		$this->view->modelName = 'unidade';
	}
	
	function addAction()
	{
		$this->view->title = 'Cadastrar Unidade/Orgão';
			
		$this->form();
		
		$tabUnidade = new Unidade();
		$this->view->modelList = $tabUnidade->fetchAll(null, "nome");
			
		$this->render();
	}
	
	function editAction()
	{
		$this->view->title = 'Editar Unidade/Orgão';
			
		$this->form('edit');
			
		$this->render();
	}
	
	function deleteAction() {
		$idUnidade = $this->_request->getParam('id');
		
		$tabUnidade = new Unidade();
		$tabUnidade->deleteById($idUnidade);
		
		$this->_redirect('/admin/unidade/add');
	}

	private function form($tipo = 'add') {
		$tabUnidade = new Unidade();
		
		if($this->_request->isPost()) {
			$errors = null;
			
			$idUnidade = (int) $this->_request->getPost('id');

			$validator = new Zend_Validate_Alpha(true);
			$nome = $this->_request->getPost('nome');
			if(!$validator->isValid($nome))
			foreach ($validator->getMessages() as $message) $errors[] = $message;

			if(!$errors) {
				$data = array(
    				'nome'	=>$nome,
					'login'	=>$this->user->login
				);

				if($tipo == 'add')
					$tabUnidade->insert($data);
				else if($tipo == 'edit') {
					$tabUnidade->updateById($data, $idUnidade);
					$this->_redirect("/admin/unidade/add");
				}
			}
			$this->view->errors = $errors;
		} else 
			$idUnidade = (int) $this->_request->getParam('id');
			
		$this->view->model = new stdClass();
		$this->view->model->id = null;
		$this->view->model->nome = null;
		
		if($idUnidade > 0) 
			$this->view->model = $tabUnidade->find($idUnidade)->current();
	}

}