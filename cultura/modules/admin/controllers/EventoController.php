<?php

require_once 'Zend/Validate/NotEmpty.php';

require_once 'Proexc/Admin/Controller/Action.php';

require_once 'Evento.php';
require_once 'Coordenador.php';
require_once 'AreaTematica.php';
require_once 'Unidade.php';
require_once 'Departamento.php';

class Admin_EventoController extends Proexc_Admin_Controller_Action {

	function preDispatch() {
		parent::preDispatch();
		parent::checkAccess('evento', null);
	}
	
	function validateAction() {
		$this->view->title = 'Validar Evento';
			
		$tabEvento = new Evento();

		if($this->_request->isPost()) {
			$idEvento = (int) $this->_request->getPost('idEvento');
				
			$validator = new Zend_Validate_NotEmpty();
			$processo = trim($this->_request->getPost('processo'));
			if(!$validator->isValid($processo))
				foreach ($validator->getMessages() as $message) $errors[] = $message;
				
			// @todo Apenas redireciona, mudar isso para mostrar o erro. ou não?	
			if($errors) 
				$this->_redirect('admin/evento/detail/id/' . $idEvento);
			
			$data = array(
					'processo'			=> $processo
			);
			
			$tabEvento->updateById($data, $idEvento);
		}

		$this->view->eventos = $tabEvento->fetchClosedAndUnvalidated();
		$this->render();
	}

	function detailAction() {
		$this->view->title = 'Detalhes do Evento';

		$tabEvento = new Evento();

		$idEvento = (int) $this->_request->getParam('id', 0);

		if($idEvento > 0) {
			$evento = $tabEvento->find($idEvento)->current();
			$this->view->evento = $evento;
		}

		$this->render();
	}
	
	function listAction() {
		$this->view->title = 'Buscar Evento';

		$params = $this->_request->getParams();
		
		if(isset($params['s'])) {
			$argument = $this->_request->getParam('s', null);
			
			$tabEvento = new Evento();
			$this->view->eventoList = $tabEvento->findByNome($argument);
		}
	}
}