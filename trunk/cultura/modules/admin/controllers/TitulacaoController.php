<?php

require_once 'Proexc/Admin/Controller/Action.php';

require_once 'Zend/Validate/Alpha.php';

require_once 'Titulacao.php';

class Admin_TitulacaoController extends Proexc_Admin_Controller_Action {

	function preDispatch() {
		parent::preDispatch();
		parent::checkAccess('titulacao', null);
	}
	
	function init() {
		parent::init();
		$this->view->modelName = 'titulacao';
	}
	
	function addAction()
	{
		$this->view->title = 'Cadastrar Titulação';
			
		$this->form();
		
		$tabTitulacao = new Titulacao();
		$this->view->modelList = $tabTitulacao->fetchAll();
			
		$this->render();
	}
	
	function editAction()
	{
		$this->view->title = 'Editar Titulação';
			
		$this->form('edit');
			
		$this->render();
	}
	
	function deleteAction() {
		$idTitulacao = $this->_request->getParam('id');
		
		$tabTitulacao = new Titulacao();
		$tabTitulacao->deleteById($idTitulacao);
		
		$this->_redirect('/admin/titulacao/add');
	}

	private function form($tipo = 'add') {
		$tabTitulacao = new Titulacao();
		
		if($this->_request->isPost()) {
			$errors = null;
			
			$idTitulacao = (int) $this->_request->getPost('id');

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
					$tabTitulacao->insert($data);
				else if($tipo == 'edit') {
					$tabTitulacao->updateById($data, $idTitulacao);
					$this->_redirect("/admin/titulacao/add");
				}
			}
			$this->view->errors = $errors;
		} else 
			$idTitulacao = (int) $this->_request->getParam('id');
			
		$this->view->model = new stdClass();
		$this->view->model->id = null;
		$this->view->model->nome = null;
		
		if($idTitulacao > 0) 
			$this->view->model = $tabTitulacao->find($idTitulacao)->current();
	}

}