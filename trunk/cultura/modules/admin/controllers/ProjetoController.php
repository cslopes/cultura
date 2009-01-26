<?php

require_once 'Zend/Validate/NotEmpty.php';

require_once 'Proexc/Admin/Controller/Action.php';

require_once 'Projeto.php';
require_once 'Coordenador.php';
require_once 'Programa.php';
require_once 'AreaTematica.php';
require_once 'LinhaAtuacao.php';
require_once 'Unidade.php';
require_once 'Departamento.php';

class Admin_ProjetoController extends Proexc_Admin_Controller_Action {

	function preDispatch() {
		parent::preDispatch();
		parent::checkAccess('projeto', null);
	}
	
	function validateAction() {
		$this->view->title = 'Validar Projeto';
			
		$tabProjeto = new Projeto();

		if($this->_request->isPost()) {
			$errors = null;
			
			$idProjeto = (int) $this->_request->getPost('idProjeto');
			$idPrograma = (int) $this->_request->getPost('idPrograma');
			$bolsasConcedidas = (int) $this->_request->getPost('bolsasConcedidas');
			
			$validator = new Zend_Validate_NotEmpty();
			$processo = trim($this->_request->getPost('processo'));
			if(!$validator->isValid($processo))
				foreach ($validator->getMessages() as $message) $errors[] = $message;
				
			// @todo Apenas redireciona, mudar isso para mostrar o erro. ou não?	
			if($errors) 
				$this->_redirect('admin/projeto/detail/id/' . $idProjeto);
				
			$data = array(
					'processo'			=> $processo,
					'bolsasConcedidas'	=> $bolsasConcedidas,
					'idPrograma'		=> $idPrograma
			);
			
			$tabProjeto->updateById($data, $idProjeto);
		}

		$this->view->projetos = $tabProjeto->fetchClosedAndUnvalidated();
		$this->render();
	}

	function detailAction() {
		$this->view->title = 'Detalhes do Projeto';

		$tabProjeto = new Projeto();

		$idProjeto = (int) $this->_request->getParam('id', 0);

		if($idProjeto > 0) {
			$projeto = $tabProjeto->find($idProjeto)->current();
			$this->view->projeto = $projeto;
		}

		$tabPrograma = new Programa();
		$this->view->programas = $tabPrograma->fetchAll();

		$this->render();
	}
	
	function listAction() {
		$this->view->title = 'Buscar Projeto';

		$params = $this->_request->getParams();
		$tabProjeto = new Projeto();
		if(isset($params['s'])) {
			$argument = $this->_request->getParam('s', null);
			$this->view->projetoList = $tabProjeto->findProjectByTitulo($argument);
		}
		else {
				$this->view->projetoList = $tabProjeto->fetchAll(null, 'titulo asc');
			}
	}
	
	
	function editResumeAction(){
		$this->view->title = 'Editar Resumo de Projeto';	
		$param = $this->_request->getParam('id');
		$tabProjeto = new Projeto();
		
		$this->view->projeto = $tabProjeto->find($param)->current();
		
		if($this->_request->isPost()) {
		
			
			$idProjeto = (int) $this->_request->getPost('idProjeto');
			$resumoProjeto =  $this->_request->getPost('resumo');
				
			$data = array(
					'resumo'			=> $resumoProjeto
					
			);
			
			$tabProjeto->updateById($data, $idProjeto);
			$this->_redirect('admin/projeto/list');
		}
		$this->render();
	}
	
	
}