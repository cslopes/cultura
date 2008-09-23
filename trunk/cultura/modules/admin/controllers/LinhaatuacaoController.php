<?php

require_once 'Proexc/Admin/Controller/Action.php';

require_once 'Zend/Validate/Alpha.php';

require_once 'LinhaAtuacao.php';

class Admin_LinhaAtuacaoController extends Proexc_Admin_Controller_Action {

	function preDispatch() {
		parent::preDispatch();
		parent::checkAccess('linhaAtuacao', null);
	}
	
	function init() {
		parent::init();
		$this->view->modelName = 'linhaAtuacao';
	}
	
	function addAction()
	{
		$this->view->title = 'Cadastrar Linha de Atuação';
			
		$this->form();
		
		$tabLinhaAtuacao = new LinhaAtuacao();
		$this->view->modelList = $tabLinhaAtuacao->fetchAll();
			
		$this->render();
	}
	
	function editAction()
	{
		$this->view->title = 'Editar Área Temática';
			
		$this->form('edit');
			
		$this->render();
	}
	
	function deleteAction() {
		$idLinhaAtuacao = $this->_request->getParam('id');
		
		$tabLinhaAtuacao = new LinhaAtuacao();
		$tabLinhaAtuacao->deleteById($idLinhaAtuacao);
		
		$this->_redirect('/admin/linhaAtuacao/add');
	}

	private function form($tipo = 'add') {
		$tabLinhaAtuacao = new LinhaAtuacao();
		
		if($this->_request->isPost()) {
			$errors = null;
			
			$idLinhaAtuacao = (int) $this->_request->getPost('id');

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
					$tabLinhaAtuacao->insert($data);
				else if($tipo == 'edit') {
					$tabLinhaAtuacao->updateById($data, $idLinhaAtuacao);
					$this->_redirect("/admin/linhaAtuacao/add");
				}
			}
			$this->view->errors = $errors;
		} else 
			$idLinhaAtuacao = (int) $this->_request->getParam('id');
			
		$this->view->model = new stdClass();
		$this->view->model->id = null;
		$this->view->model->nome = null;
		
		if($idLinhaAtuacao > 0) 
			$this->view->model = $tabLinhaAtuacao->find($idLinhaAtuacao)->current();
	}

}