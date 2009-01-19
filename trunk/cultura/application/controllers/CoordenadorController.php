<?php

require_once 'Proexc/Controller/Action.php';
require_once 'Zend/Auth.php';
require_once 'Zend/Validate/Alpha.php';
require_once 'Zend/Validate/Regex.php';
require_once 'Zend/Validate/EmailAddress.php';
require_once 'Zend/Validate/Int.php';

require_once 'Departamento.php';
require_once 'Titulacao.php';
require_once 'Coordenador.php';

class CoordenadorController extends Proexc_Controller_Action {

	function editAction() {
		$this->view->title = "Atualizar Dados";

		$coordenador = new Coordenador();
		$row = $coordenador->findBySiape($this->user->siape);

		$this->initForm($row);
		
		if($this->_request->isPost()) {
			$data = $this->getValidatedData();
			
			if($data) {
				$coordenador->updateById($data, $row->id);
				$this->_redirect('/');
			}
		}

		// Inicializa action e botao
		$this->view->action = 'edit';
		$this->view->buttonText = 'Atualizar dados';

		$this->render();
	}

	function addAction() {
		$this->view->title = "Cadastrar Coordenador";

		$coordenador = new Coordenador();

		$this->initForm();
		
		if($this->_request->isPost()) {
			$data = $this->getValidatedData();

			if($data) {
				$coordenador->insert($data);
				$row = $coordenador->findBySiape($data['siape']);
			
				$dataSession = new stdClass();
				$dataSession->id = $row->id;
				$dataSession->nome = $row->nome;
				$dataSession->siape = $row->siape;
				
				$auth = Zend_Auth::getInstance();
				$auth->getStorage()->write($dataSession);
					
				$this->_redirect("/");
			}
				
		}

		// Inicializa action e botao
		$this->view->action = 'add';
		$this->view->buttonText = 'Cadastrar';
		
		$this->view->user->nome = $this->user->siape;
		
		$this->render();
	}

	private function getValidatedData() {
		$erros = null;
		$validator = new Zend_Validate_Alpha(true);
		
		$siape = $this->user->siape;
		$nome = trim($this->_request->getPost('nome'));
		if(!$validator->isValid($nome)) {
			foreach ($validator->getMessages() as $message) {
				$erros[] = $message;
			}
		}
		$idDepartamento = $this->_request->getPost('idDepartamento');
		$idTitulacao = $this->_request->getPost('idTitulacao');
		
		$validator = new Zend_Validate_Regex("/^\\(\\d{2}\\)\\d{4}-\\d{4}\$/");
		$telefone = trim($this->_request->getPost('telefone'));
		if(!$validator->isValid($telefone)) {
			foreach ($validator->getMessages() as $message) {
				$erros[] = $message;
			}
		}
		$telefonePublico = trim($this->_request->getPost('telefonePublico'));
		if(!$validator->isValid($telefonePublico)) {
			foreach ($validator->getMessages() as $message) {
				$erros[] = $message;
			}
		}
		$celular = trim($this->_request->getPost('celular'));
		if($celular && !$validator->isValid($celular)) {
			foreach ($validator->getMessages() as $message) {
				$erros[] = $message;
			}
		}
		
		$validator = new Zend_Validate_EmailAddress();
		$email = trim($this->_request->getPost('email'));
		if(!$validator->isValid($email)) {
			foreach ($validator->getMessages() as $message) {
				$erros[] = $message;
			}
		}
		
		$validator = new Zend_Validate_Int();
		$cargaHorariaSemanal = trim($this->_request->getPost('cargaHorariaSemanal'));
		if(!$validator->isValid($cargaHorariaSemanal))
			foreach ($validator->getMessages() as $message)
				$erros[] = $message;
			
		if($erros)  {
			$this->view->erros = $erros;
			
			$this->view->coordenador->nome 					= $nome;
			$this->view->coordenador->idDepartamento 		= $idDepartamento;
			$this->view->coordenador->idTitulacao			= $idTitulacao;
			$this->view->coordenador->telefone				= $telefone;
			$this->view->coordenador->telefonePublico		= $telefonePublico;
			$this->view->coordenador->email					= $email;
			$this->view->coordenador->celular				= $celular;
			$this->view->coordenador->cargaHorariaSemanal	= $cargaHorariaSemanal;
			return ;
		}
		
		$data = array(
			'siape'					=> $siape,
			'nome' 					=> $nome,
			'idDepartamento'		=> $idDepartamento,
			'idTitulacao'			=> $idTitulacao,
			'telefone'				=> $telefone,
			'telefonePublico'		=> $telefonePublico,
			'celular'				=> $celular,
			'email'					=> $email,
			'cargaHorariaSemanal'	=> $cargaHorariaSemanal
		);

		return $data;
	}

	private function initForm($coordenador = null) {
		// Inicializa dados do combo Departamento
		$departamento = new Departamento();
		$this->view->departamentos = $departamento->fetchAll(null, 'nome ASC');

		// Inicializa dados do combo Titulacao
		$titulacao = new Titulacao();
		$this->view->titulacoes = $titulacao->fetchAll();

		// Inicializa dados do Coordenador
		$this->view->coordenador = $coordenador;
		if($coordenador == null) {
			// Inicializa dados do formul�rio como vazio
			$this->view->coordenador = new stdClass();
			$this->view->coordenador->siape 		  		= $this->user->siape;
			$this->view->coordenador->nome 			  		= '';
			$this->view->coordenador->idDepartamento  		= '';
			$this->view->coordenador->idTitulacao 	  		= '';
			$this->view->coordenador->telefone 		  		= '';
			$this->view->coordenador->telefonePublico 		= '';
			$this->view->coordenador->celular		  		= '';
			$this->view->coordenador->email 		  		= '';
			$this->view->coordenador->cargaHorariaSemanal	= '';
		}
	}
}