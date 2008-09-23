<?php

require_once 'Proexc/Admin/Controller/Action.php';

require_once 'Zend/Validate/Alpha.php';
require_once 'Zend/Json/Encoder.php';

require_once 'Departamento.php';
require_once 'Unidade.php';

class Admin_DepartamentoController extends Proexc_Admin_Controller_Action {

	function preDispatch() {
		parent::preDispatch();
		parent::checkAccess('departamento', null);
	}
	
	function init() {
		parent::init();
		$this->view->modelName = 'departamento';
	}
	
	function addAction()
	{
		$this->view->title = 'Cadastrar Departamento/Setor';
			
		$this->form();
		
		$tabDepartamento = new Departamento();
		$this->view->modelList = $tabDepartamento->fetchAll();
			
		if(!$this->_request->isPost())
			$this->render();
	}
	
	function editAction()
	{
		$this->view->title = 'Editar Departamento/Setor';
			
		$this->form('edit');
			
		$this->render();
	}
	
	function deleteAction() {
		$this->_helper->viewRenderer->setNoRender(true);
		
		$idDepartamento = $this->_request->getParam('id');
		
		$tabDepartamento = new Departamento();
		$tabDepartamento->deleteById($idDepartamento);
	}

	function fillAction() {
		$this->_helper->viewRenderer->setNoRender(true);
		
		if($this->_request->isPost()) {
			$idUnidade = $this->_request->getPost("idUnidade");
			
			$tabDepartamento = new Departamento();
			$departamentoList = $tabDepartamento->fetchAllByUnidade($idUnidade);
			
//			$theValue = new Zend_Db_Table_Rowset_Abstract();
			
			$this->getResponse()->setHeader("content-type", "text/json-comment-filtered");
			$this->getResponse()->setBody(Zend_Json_Encoder::encode($departamentoList->toArray()));
		}
	}
	
	private function form($tipo = 'add') {
		$this->_helper->viewRenderer->setNoRender(true);
		
		$tabDepartamento = new Departamento();
		
		if($this->_request->isPost()) {
			$errors = null;
			
			$idDepartamento = (int) $this->_request->getPost('id');
			$idUnidade		= (int) $this->_request->getPost('idUnidade');

			$validator = new Zend_Validate_Alpha(true);
			$nome = $this->_request->getPost('nome');
			if(!$validator->isValid($nome))
				foreach ($validator->getMessages() as $message) $errors[] = $message;

			if(!$errors) {
				$data = array(
    				'nome'		=> $nome,
					'idUnidade'	=> $idUnidade,
					'login'		=> $this->user->login
				);

				if($tipo == 'add')
					$tabDepartamento->insert($data);
				else if($tipo == 'edit') {
					$tabDepartamento->updateById($data, $idDepartamento);
					$this->_redirect("/admin/departamento/add");
				}
				
				$this->getResponse()->setBody("Inserido com sucesso!");
			} else {
//			$this->view->errors = $errors;
				$this->getResponse()->setHttpResponseCode(404);
				$this->getResponse()->setBody($errors);
			}
		} else 
			$idDepartamento = (int) $this->_request->getParam('id');
			
		$tabUnidade = new Unidade();
		$this->view->unidadeList = $tabUnidade->fetchAll(null, 'nome');
					
		$this->view->model = new stdClass();
		$this->view->model->id = null;
		$this->view->model->idUnidade = null;
		$this->view->model->nome = null;
		
		if($idDepartamento > 0) 
			$this->view->model = $tabDepartamento->find($idDepartamento)->current();
	}

}