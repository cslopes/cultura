<?php

require_once 'Proexc/Admin/Controller/Action.php';

require_once 'Zend/Validate/Alpha.php';

require_once 'Programa.php';

class Admin_ProgramaController extends Proexc_Admin_Controller_Action {

	function preDispatch() {
		parent::preDispatch();
		parent::checkAccess('programa', null);
	}
	
	function init() {
		parent::init();
		$this->view->modelName = 'programa';
	}
	
	function addAction()
	{
		$this->view->title = 'Cadastrar Programa';
			
		$this->form();
		
		$tabPrograma = new Programa();
		$this->view->modelList = $tabPrograma->fetchAll(null,'nome ASC');
			
		$this->render();
	}
	
	function editAction()
	{
		$this->view->title = 'Editar Área Temática';
			
		$this->form('edit');
			
		$this->render();
	}
	
	function deleteAction() {
		$idPrograma = $this->_request->getParam('id');
		
		$tabPrograma = new Programa();
		$tabPrograma->deleteById($idPrograma);
		
		$this->_redirect('/admin/programa/add');
	}

	private function form($tipo = 'add') {
		$tabPrograma = new Programa();
		
		if($this->_request->isPost()) {
			$errors = null;
			
			$idPrograma = (int) $this->_request->getPost('id');

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
					$tabPrograma->insert($data);
				else if($tipo == 'edit') {
					$tabPrograma->updateById($data, $idPrograma);
					$this->_redirect("/admin/programa/add");
				}
			}
			$this->view->errors = $errors;
		} else 
			$idPrograma = (int) $this->_request->getParam('id');
			
		$this->view->model = new stdClass();
		$this->view->model->id = null;
		$this->view->model->nome = null;
		
		if($idPrograma > 0) 
			$this->view->model = $tabPrograma->find($idPrograma)->current();
	}

}