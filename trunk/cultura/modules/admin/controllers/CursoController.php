<?php

require_once 'Zend/Validate/NotEmpty.php';

require_once 'Proexc/Admin/Controller/Action.php';

require_once 'Curso.php';
require_once 'Coordenador.php';
require_once 'AreaTematica.php';
require_once 'LinhaAtuacao.php';
require_once 'Unidade.php';
require_once 'Departamento.php';

class Admin_CursoController extends Proexc_Admin_Controller_Action {

	function preDispatch() {
		parent::preDispatch();
		parent::checkAccess('curso', null);
	}
	
	function validateAction() {
		$this->view->title = 'Validar Curso';
			
		$tabCurso = new Curso();

		if($this->_request->isPost()) {
			$errors = null;
			
			$idCurso = (int) $this->_request->getPost('idCurso');
			
			$validator = new Zend_Validate_NotEmpty();
			$processo = trim($this->_request->getPost('processo'));
			if(!$validator->isValid($processo))
				foreach ($validator->getMessages() as $message) $errors[] = $message;
				
			// @todo Apenas redireciona, mudar isso para mostrar o erro. ou não?	
			if($errors) 
				$this->_redirect('admin/curso/detail/id/' . $idCurso);

			$data = array(
				'processo'			=> $processo
			);
			
			$tabCurso->updateById($data, $idCurso);
		}

		$this->view->cursos = $tabCurso->fetchClosedAndUnvalidated();
		$this->render();
	}

	function detailAction() {
		$this->view->title = 'Detalhes do Curso';

		$tabCurso = new Curso();

		$idCurso = (int) $this->_request->getParam('id', 0);

		if($idCurso > 0) {
			$curso = $tabCurso->find($idCurso)->current();
			$this->view->curso = $curso;
		}

		$this->render();
	}
	
	function listAction() {
		$this->view->title = 'Buscar Curso';

		$params = $this->_request->getParams();
		
		if(isset($params['s'])) {
			$argument = $this->_request->getParam('s', null);
			
			$tabCurso = new Curso();
			$this->view->cursoList = $tabCurso->findByNome($argument);
		}
	}
}