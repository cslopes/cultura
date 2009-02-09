<?php

require_once 'Proexc/Admin/Controller/Action.php';

require_once 'Zend/Validate/NotEmpty.php';
require_once 'Zend/Validate/Date.php';
require_once 'Zend/Date.php';
require_once 'Zend/Registry.php';

require_once 'Agenda.php';

class Admin_AgendaController extends Proexc_Admin_Controller_Action {

	function preDispatch() {
		parent::preDispatch();
		parent::checkAccess('agenda', null);
	}
	
	function addAction()
	{
		$this->view->title = 'Cadastrar Agenda';
			
		$this->form();

		$this->render();
	}

	function indexAction() {
		$this->view->title = 'Buscar Agenda';

		$params = $this->_request->getParams();
		
		$tabAgenda = new Agenda();
		if(isset($params['s'])) {
			$argument = $this->_request->getParam('s', null);
			$this->view->agendaList = $tabAgenda->findByTitulo($argument);
		} else {
			$this->view->agendaList = $tabAgenda->fetchAll(null, 'dataAtualizacao desc', 10);
		}
	}
	
	function setAtivoAction() {
		$this->_helper->viewRenderer->setNoRender(true);
		
		$idAgenda = (int) $this->_request->getParam('idAgenda');
		$ativo = (int) $this->_request->getParam('ativo', 1);
		
		$tabAgenda = new Agenda();
		$agenda = $tabAgenda->find($idAgenda)->current();
		$agenda->ativo = $ativo;
		if($agenda->save() > 0) $this->_response->append('status', 'salvo');
	}

	function editAction()
	{
		$this->view->title = 'Editar Agenda';
			
		$this->form('edit');
			
		$this->render();
	}

	function deleteAction() {
		$idAgenda = $this->_request->getParam('id');

		$tabAgenda = new Agenda();
		$tabAgenda->deleteById($idAgenda);

		$this->_redirect('/admin/Agenda/list');
	}

	private function form($tipo = 'add') {
		$tabAgenda = new Agenda();

		if($this->_request->isPost()) {
			$errors = null;
				
			$idAgenda = (int) $this->_request->getPost('idAgenda');

			$validator = new Zend_Validate_NotEmpty();
			$titulo = $this->_request->getPost('titulo');
			if(!$validator->isValid($titulo))
				foreach ($validator->getMessages() as $message) $errors['titulo'][] = $message;
			
			$textoImagem = trim($this->_request->getPost('textoImagem'));
			if($textoImagem == '') $textoImagem = null;				
			
			$tituloImagem = trim($this->_request->getPost('tituloImagem'));
			if($tituloImagem == '') $tituloImagem = null;
				
			$descricao = $this->_request->getPost('descricao');
			$descricao = str_replace("\\\"", "\"", $descricao);
			$ativo = (int) $this->_request->getPost('ativo');
			
			if(!$errors) {
				$data = array(
    				'titulo'		=> $titulo,
					'tituloImagem'	=> $tituloImagem,
					'descricao'		=> $descricao,
					'ativo'			=> $ativo,
					'login'			=> $this->user->login,
					'textoImagem'   => $textoImagem	
				);

				if($tipo == 'add') {
					$data['timestamp'] = date("y/m/d H:i:s");					
					$tabAgenda->insert($data);
				}
				else if($tipo == 'edit') 
					$tabAgenda->updateById($data, $idAgenda);
					
				$this->_redirect("/admin/agenda");
			}
			$this->view->errors = $errors;
		}
		//Get
		 else
			$idAgenda = (int) $this->_request->getParam('id');
			
		$this->view->agenda = new stdClass();
		$this->view->agenda->id 			= null;
		$this->view->agenda->titulo 		= null;
		$this->view->agenda->tituloImagem	= null;
		$this->view->agenda->descricao 		= null;
		$this->view->agenda->ativo 			= null;
		$this->view->agenda->textoImagem	= null;
		
		if($idAgenda > 0)
			$this->view->agenda = $tabAgenda->find($idAgenda)->current();
	}

}