<?php

require_once 'Proexc/Admin/Controller/Action.php';

require_once 'Zend/Validate/NotEmpty.php';
require_once 'Zend/Validate/Date.php';
require_once 'Zend/Date.php';

require_once 'Convenio.php';

class Admin_ConvenioController extends Proexc_Admin_Controller_Action {

	function preDispatch() {
		parent::preDispatch();
		parent::checkAccess('convenio', null);
	}
	
	function addAction()
	{
		$this->view->title = 'Cadastrar Convênio';
			
		$this->form();

		$this->render();
	}

	function listAction() {
		$this->view->title = 'Buscar Convênio';

		$params = $this->_request->getParams();
		
		if(isset($params['s'])) {
			$argument = $this->_request->getParam('s', null);
			
			$tabConvenio = new Convenio();
			$this->view->convenioList = $tabConvenio->findByNome($argument);
		}
	}
	
	function listTerminoAction(){
		$this->view->title = 'Consultar Término de Convênios';
		$tabConvenio = new Convenio();
		
		$this->view->anos = $tabConvenio->getYears() ;
		
			
	
	}

	function editAction()
	{
		$this->view->title = 'Editar Convênio';
			
		$this->form('edit');
			
		$this->render();
	}

	function deleteAction() {
		$idConvenio = $this->_request->getParam('id');

		$tabConvenio = new Convenio();
		$tabConvenio->deleteById($idConvenio);

		$this->_redirect('/admin/Convenio/list');
	}

	private function form($tipo = 'add') {
		$tabConvenio = new Convenio();

		if($this->_request->isPost()) {
			$errors = null;
				
			$idConvenio = (int) $this->_request->getPost('id');

			$validator = new Zend_Validate_NotEmpty();
			$nome = $this->_request->getPost('nome');
			if(!$validator->isValid($nome))
			foreach ($validator->getMessages() as $message) $errors[] = $message;
				
			$registro = $this->_request->getPost('registro');
				
			$descricao = $this->_request->getPost('descricao');
				
			$validator = new Zend_Validate_Date();
			$dataInicio = $this->_request->getPost('dataInicio');
			if(!$validator->isValid($dataInicio)) {
				foreach ($validator->getMessages() as $message) $errors[] = $message;
			}else $dataInicio = new Zend_Date($dataInicio);

			$dataFinal = $this->_request->getPost('dataFinal');
			if($dataFinal) {
				if(!$validator->isValid($dataFinal))
				foreach ($validator->getMessages() as $message) $errors[] = $message;
				else {
					$dataFinal = new Zend_Date($dataFinal);
					$dataFinal = $dataFinal->get('y-M-d');
				}
			} else $dataFinal = null;
				

			if(!$errors) {
				$data = array(
    				'nome'			=> $nome,
					'registro'		=> $registro,
					'descricao'		=> $descricao,
					'dataInicio'	=> $dataInicio->get('y-M-d'),
					'dataFinal'		=> $dataFinal,
					'login'			=> $this->user->login
				);

				if($tipo == 'add')
				$tabConvenio->insert($data);
				else if($tipo == 'edit') {
					$tabConvenio->updateById($data, $idConvenio);
					$this->_redirect("/admin/convenio/add");
				}
			}
			$this->view->errors = $errors;
		} else
		$idConvenio = (int) $this->_request->getParam('id');
			
		$this->view->convenio = new stdClass();
		$this->view->convenio->id = null;
		$this->view->convenio->nome = null;
		$this->view->convenio->registro = null;
		$this->view->convenio->descricao = null;
		$this->view->convenio->dataInicio = null;
		$this->view->convenio->dataFinal = null;

		if($idConvenio > 0)
		$this->view->convenio = $tabConvenio->find($idConvenio)->current();
	}

}