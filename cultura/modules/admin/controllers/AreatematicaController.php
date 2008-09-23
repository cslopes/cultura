<?php

require_once 'Proexc/Admin/Controller/Action.php';

require_once 'Zend/Validate/Alpha.php';

require_once 'AreaTematica.php';

class Admin_AreatematicaController extends Proexc_Admin_Controller_Action {

	function preDispatch() {
		parent::preDispatch();
		parent::checkAccess('areaTematica', null);
	}
	
	function init() {
		parent::init();
		$this->view->modelName = 'areaTematica';
	}
	
	function addAction()
	{
		$this->view->title = 'Cadastrar Área Temática';
			
		$this->form();
		
		$tabAreaTematica = new AreaTematica();
		$this->view->modelList = $tabAreaTematica->fetchAll();
			
		$this->render();
	}
	
	function editAction()
	{
		$this->view->title = 'Editar Área Temática';
			
		$this->form('edit');
			
		$this->render();
	}
	
	function deleteAction() {
		$idAreaTematica = $this->_request->getParam('id');
		
		$tabAreaTematica = new AreaTematica();
		$tabAreaTematica->deleteById($idAreaTematica);
		
		$this->_redirect('/admin/areaTematica/add');
	}

	private function form($tipo = 'add') {
		$tabAreaTematica = new AreaTematica();
		
		if($this->_request->isPost()) {
			$errors = null;
			
			$idAreaTematica = (int) $this->_request->getPost('id');

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
					$tabAreaTematica->insert($data);
				else if($tipo == 'edit') {
					$tabAreaTematica->updateById($data, $idAreaTematica);
					$this->_redirect("/admin/areaTematica/add");
				}
			}
			$this->view->errors = $errors;
		} else 
			$idAreaTematica = (int) $this->_request->getParam('id');
			
		$this->view->model = new stdClass();
		$this->view->model->id = null;
		$this->view->model->nome = null;
		
		if($idAreaTematica > 0) 
			$this->view->model = $tabAreaTematica->find($idAreaTematica)->current();
	}

}