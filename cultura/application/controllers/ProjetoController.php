<?php

require_once 'Zend/Date.php';
require_once 'Zend/Validate/Date.php';
require_once 'Zend/Validate/Alpha.php';
require_once 'Zend/Validate/EmailAddress.php';
require_once 'Zend/Validate/Regex.php';
require_once 'Zend/Validate/Between.php';
require_once 'Zend/Validate/NotEmpty.php';
require_once 'Zend/Validate/Int.php';

require_once 'Proexc/Controller/Action.php';
require_once 'Proexc/Validate/Siape.php';
require_once 'Proexc/Validate/Alpha.php';
require_once 'Proexc/Validate/Date.php';
require_once 'Proexc/Validate/Regex.php';
require_once 'Proexc/Validate/Int.php';
require_once 'Proexc/Validate/NotEmpty.php';
require_once 'Proexc/Validate/EmailAddress.php';

require_once 'Projeto.php';
require_once 'Programa.php';
require_once 'AreaTematica.php';
require_once 'LinhaAtuacao.php';
require_once 'Coordenador.php';
require_once 'ProjetoTecnico.php';
require_once 'Tecnico.php';
require_once 'Departamento.php';
require_once 'ColaboradorDocente.php';
require_once 'ProjetoColaboradorDocente.php';
require_once 'ColaboradorExterno.php';
require_once 'ProjetoColaboradorExterno.php';
require_once 'Parceiro.php';
require_once 'ProjetoParceiro.php';
require_once 'Recursos.php';
require_once 'FormularioProjeto.php';
require_once 'RelatorioFinal.php';
require_once 'FormularioRelatorioProjeto.php';
require_once 'Parceiro.php';

class ProjetoController extends Proexc_Controller_Action {

	/**
	 * Verifica se o Coordenador logado tem acesso ao projeto
	 */
	function preDispatch() {
		parent::preDispatch();


		

		// Verifica se o coordenador tem acesso a ações para projetos fechados e não-validados
		if($this->_request->getActionName() == 'imprimirFormulario') {
			$tabProjeto = new Projeto();
			$projetos = $tabProjeto->fetchClosedByCoordenador($this->user->id);
			$ok = 0;
			foreach ($projetos as $projeto) {
				if($projeto->id == $this->_request->getParam('id')){
					$ok = 1;
					break;
				}
			}
		}
		// Verifica se o coordenador tem acesso ao projeto e este está validado
		else if($this->_request->getActionName() == 'imprimirRelatorioProjeto') {
			$tabProjeto = new Projeto();
			$projeto = $tabProjeto->find($this->_request->getParam('id'))->current();
			
			$ok = ($projeto->idCoordenador == $this->user->id && $projeto->findParentRelatorioFinal()->fechado) ? 1 : 0;

			if(!$ok) $this->_redirect('/');
		}
		
		// Verifica se o coordenador tem acesso à edição de relatorio final
		else if($this->_request->getActionName() == 'relatorioFinal') {
			$tabProjeto = new Projeto();
			$projeto = $tabProjeto->find($this->_request->getParam('id'))->current();
			
			$ok = 0;
			
			// Verifica se o projeto está validado e o coordenador é dono
			if($projeto->idCoordenador == $this->user->id && $projeto->processo) {
				// Se tiver relatório já salvo...
				if($projeto->idRelatorioFinal) {
					// Verifica se o relatório está aberto
					if(!$projeto->findParentRelatorioFinal()->fechado) $ok = 1;
				// Senão pode editar
				}else {
					$ok = 1;
				}
			}
			
			if(!$ok) $this->_redirect('/');
		}
		
		//Verifica se o coordenador tem acesso a ação de fechar um relatório final
		elseif($this->_request->getActionName() == 'fecharRelatorio') {
			$tabProjeto = new Projeto();
			$projeto = $tabProjeto->find($this->_request->getParam('id'))->current();
			
			if($projeto->idCoordenador == $this->user->id && $projeto->idRelatorioFinal){
					$ok = 1;
			}else{
					$ok = 0;
				}
			
//			$ok = ($projeto->idCoordenador == $this->user->id && $projeto->idRelatorioFinal) ? 1 : 0; 

			if(!$ok) $this->_redirect('/');
		}
		
		// Verifica se o coordenador tem acesso a ações para projetos abertos e não-validados
		elseif($this->_request->getActionName() != 'add') {
			$tabProjeto = new Projeto();
			$projeto = $tabProjeto->find($this->_request->getParam('id'))->current();
			
			$ok = ($projeto->idCoordenador == $this->user->id && !$projeto->fechado) ? 1 : 0;
			if(!$ok) $this->_redirect('/');
		}
	}


	/**
	 * Controller para criação de um novo projeto. Requisita apenas o nome do projeto e cria
	 * inserindo também o id do coordenador. Logo após criado o usuário é enviado para a tela
	 * de edição do projeto.
	 */
	
	function addAction() {
		$this->view->title = "Cadastrar Novo Projeto";

		$projeto = new Projeto();

		if($this->_request->isPost()) {
			$errors = null;


			$validator = new Proexc_Validate_NotEmpty();

			$validator = new Zend_Validate_NotEmpty();

			$titulo = trim($this->_request->getPost('titulo'));
			if(!$validator->isValid($titulo)) {
				foreach ($validator->getMessages() as $message) $errors[] = $message;
			}

			if(!$errors) {
				$data = array(
					'titulo'		=> $titulo,
					'idCoordenador'	=> $this->user->id);

				$pk = $projeto->insert($data);

				$this->_redirect('projeto/geral/id/'.$pk);
			}
			$this->view->errors = $errors;
		}

		$this->view->projeto = $projeto;

		$this->render();
	}



	/**
	 * Controller para renomear o curso
	 */
	function renameAction() {
		$this->view->title = "Renomear Projeto";

		$projeto = new Projeto();

		if($this->_request->isPost()) {
			$errors = null;

			$id = (int) $this->_request->getPost('id');
			
			$validator = new Zend_Validate_NotEmpty();
			$titulo = trim($this->_request->getPost('titulo'));
			if(!$validator->isValid($titulo)) {
				foreach ($validator->getMessages() as $message) $errors[] = $message;
			}

			if(!$errors) {
				$data = array(
					'titulo'		=> $titulo);

				$pk = $projeto->updateById($data, $id);

				$this->_redirect('index/listProjetos');
			}
			$this->view->errors = $errors;
			$this->view->projeto = new stdClass();
			$this->view->projeto->id = $id;
			$this->view->projeto->titulo = $titulo;
		} else {
			$id = (int) $this->_request->getParam('id', 0);
			if($id > 0) $this->view->projeto = $projeto->find($id)->current();
		}

		$this->render();
	}


		/**
	 * Formulário de projeto para preenchimento dos dados gerais.
	 */
	function geralAction() {
		// Seta o título
		$this->view->title = "Geral";

		$projeto = new Projeto();

		if($this->_request->isPost()) {
			$errors = null;
			$id = (int) $this->_request->getPost('id');
			//$idPrograma = (int) $this->_request->getPost('idPrograma');
			$idAreaTematica = (int) $this->_request->getPost('idAreaTematica');
			$idLinhaAtuacao = (int) $this->_request->getPost('idLinhaAtuacao');
			$continuo = (boolean) $this->_request->getPost('continuo');

			$validator = new Proexc_Validate_Date();
			$dataInicio = $this->_request->getPost('dataInicio');
			if(!$validator->isValid($dataInicio)) {
				foreach ($validator->getMessages() as $message) $errors[] = $message;
			}else $dataInicio = new Zend_Date($dataInicio);

			$dataFinal = null;
			if(!$continuo) {
				$dataFinal = $this->_request->getPost('dataFinal');
				if(!$validator->isValid($dataFinal)) {
					foreach ($validator->getMessages() as $message) $errors[] = $message;
				}else {
					$dataFinal = new Zend_Date($dataFinal);
					$dataFinal = $dataFinal->get('y-M-d');
				}
			}

			if(!$errors) {
				$data = array(
				//					'idPrograma'		=> $idPrograma,
					'idAreaTematica'	=> $idAreaTematica,
					'idLinhaAtuacao'	=> $idLinhaAtuacao,
					'continuo'			=> $continuo,
					'dataInicio'		=> $dataInicio->get('y-M-d'),
					'dataFinal'			=> $dataFinal
				);

				$projeto->updateById($data, $id);

				$button = $this->_request->getPost('button');

				// Se clicou em próximo, segue para formulário de equipe
				if($button == 'Proximo') $this->_redirect('/projeto/equipe/id/'.$id);
			}
			$this->view->errors = $errors;
			// Foi passado o id por 'GET'
		} else {
			$id = (int) $this->_request->getParam('id', 0);
		}

		if($id > 0) $this->view->projeto = $projeto->find($id)->current();

		// Dados para o combo de Programa
		$programa = new Programa();
		$this->view->programas = $programa->fetchAll();

		// Dados para o combo de Area Temática
		$areaTematica = new AreaTematica();
		$this->view->areasTematicas = $areaTematica->fetchAll('id > 0','nome ASC');

		// Dados para o combo de Linha de Atuação
		$linhaAtuacao = new LinhaAtuacao();
		$this->view->linhasAtuacao = $linhaAtuacao->fetchAll('id > 0','nome ASC');

		$this->render();
	}

	function equipeAction() {
		$this->view->title = 'Equipe';

		$projeto = new Projeto();

		if($this->_request->isPost()) {
			$idProjeto = (int) $this->_request->getPost('id');
			$idViceCoordenador = (int) $this->_request->getPost('idViceCoordenador');
			$idCoordenadorTecnico = (int) $this->_request->getPost('idCoordenadorTecnico');

			$idViceCoordenador = $idViceCoordenador ? $idViceCoordenador : null;

			$data = array(
				'idViceCoordenador'		=> $idViceCoordenador,
			);

			$projeto->updateById($data, $idProjeto);

			$button = $this->_request->getPost('button');

			// Se clicou em próximo, segue para formulário de parceiros
			if($button == 'Proximo') $this->_redirect('/projeto/parceiros/id/'.$idProjeto);

			// Foi passado o id por 'GET'
		} else {
			$idProjeto = (int) $this->_request->getParam('id', 0);
		}

		if($idProjeto > 0) $this->view->projeto = $projeto->find($idProjeto)->current();

		// Preenche combo de possíveis vice coordenadores, ou seja, todos coordenadores
		// menos o coordenador logado.
		$coordenador = new Coordenador();
		$this->view->viceCoordenadores = $coordenador->fetchViceCoordenadores($this->user->id);

		// Preenche o campo de coordenador técnico
		$tabTecnico = new Tecnico();
		$this->view->coordenadoresTecnicos = $tabTecnico->fetchCoordenadoresTecnicosByProjeto($idProjeto);

		// Preenche o campo de tecnicos
		$this->view->colaboradoresTecnicos = $tabTecnico->fetchColaboradoresTecnicosByProjeto($idProjeto);
			
		// Preenche o campo colaboradores docentes
		$tabColaboradorDocente = new ColaboradorDocente();
		$this->view->colaboradoresDocentes = $tabColaboradorDocente->fetchColaboradoresDocentesByProjeto($idProjeto);

		// Preenche colaboradores externo
		$tabColaboradorExterno = new ColaboradorExterno();
		$this->view->colaboradoresExternos = $tabColaboradorExterno->fetchColaboradoresExternosByProjeto($idProjeto);

		$this->render();
	}

	function addCoordenadorTecnicoAction() {
		// Se já existir um coordenador técnico, não exibe
		//		$idProjeto = $this->_request->getParam('id');
		//		$projetoTecnico = new ProjetoTecnico();
		//		if($projetoTecnico->hasCoordenadorTecnico($idProjeto)) $this->_redirect('/');

		// Inicia
		$this->view->title = 'Coordenador Técnico';
		$this->view->action = $this->view->baseUrl . "/projeto/addCoordenadorTecnico";
		$this->formTecnico(Tecnico::COORDENADOR);
	}

	function addColaboradorTecnicoAction() {
		// Inicia
		$this->view->title = 'Colaborador Técnico';
		$this->view->action = $this->view->baseUrl . "/projeto/addColaboradorTecnico";
		$this->formTecnico(Tecnico::COLABORADOR);
	}

	function editCoordenadorTecnicoAction() {
		$this->view->title = 'Coordenador Técnico';
		$this->view->action = $this->view->baseUrl . "/projeto/editCoordenadorTecnico";
		$this->formTecnico(Tecnico::COORDENADOR, 'edit');
	}

	function editColaboradorTecnicoAction() {
		$this->view->title = 'Colaborador Técnico';
		$this->view->action = $this->view->baseUrl . "/projeto/editColaboradorTecnico";
		$this->formTecnico(Tecnico::COLABORADOR, 'edit');
	}

	function delCoordenadorTecnicoAction() {
		// Título da página
		$this->view->title = "Coordenador Técnico";

		$this->delTecnico(Tecnico::COORDENADOR);
	}

	function delColaboradorTecnicoAction() {
		// Título da página
		$this->view->title = "Colaborador Técnico";

		$this->delTecnico(Tecnico::COLABORADOR);
	}

	private function delTecnico($funcao) {
		// Cria um objeto referente à tabela Tecnico
		$tecnico = new Tecnico();

		// Se a requisição for um método post
		if ($this->_request->isPost()) {
			// Pega os dados
			$idTecnico = (int)$this->_request->getPost('idTecnico');
			$idProjeto = (int)$this->_request->getPost('id');
			$del = $this->_request->getPost('del');

			// Se clicou em 'Yes' e existe o técnico
			if ($del == 'Yes' && $idTecnico > 0) {
				$db = $tecnico->getAdapter();
				$db->beginTransaction();
					
				try {
					$where[] = $db->quoteInto('idProjeto = ?', $idProjeto);
					$where[] = $db->quoteInto('idTecnico = ?', $idTecnico);
					$where[] = $db->quoteInto('funcao = ?', $funcao);
					$projetoTecnico = new ProjetoTecnico();
					$projetoTecnico->delete($where);

					$where = $db->quoteInto('id = ?', $idTecnico);
					$tecnico->delete($where);

					$db->commit();
				} catch (Exception $e) {
					$db->rollBack();
					$this->view->error = $e->getMessage();
					return ;
				}
			}
			// A transação é do tipo 'get'
		} else {
			// Pega os dados passados na url
			$idProjeto = (int)$this->_request->getParam('id');
			$idTecnico = (int)$this->_request->getParam('idTecnico');

			// Testa o id
			if ($idTecnico > 0) {
				// somente mostra se achou o tecnico
				$this->view->tecnico = $tecnico->fetchRow('id='.$idTecnico);
				$this->view->idProjeto = $idProjeto;

				if ($this->view->tecnico->id > 0) {
					$this->render();
					return;
				}
			}
		}
		// volta se não renderizou (se o tecnico não existe)
		$this->_redirect("projeto/equipe/id/".$idProjeto);
	}

	private function formTecnico($funcao, $tipo = 'add') {
		$tecnico = new Tecnico();

		if($this->_request->isPost()) {
			$errors = null;

			$idProjeto = (int) $this->_request->getPost('id');
			$idTecnico = (int) $this->_request->getPost('idTecnico');

			$validator = new Proexc_Validate_Alpha(true);
			$nome = trim($this->_request->getPost('nome'));
			if(!$validator->isValid($nome))
			foreach ($validator->getMessages() as $message) $errors[] = $message;
			
			$validator = new Proexc_Validate_Siape();
			$siape = trim($this->_request->getPost('siape'));
			if(!$validator->isValid($siape))
			foreach ($validator->getMessages() as $message) $errors[] = $message;

			$idDepartamento = (int) $this->_request->getPost('idDepartamento');

			$validator = new Proexc_Validate_EmailAddress();
			$email = trim($this->_request->getPost('email'));
			if(!$validator->isValid($email))
			foreach ($validator->getMessages() as $message) $errors[] = $message;

			$validator = new Proexc_Validate_Regex("/^\\(\\d{2}\\)\\d{4}-\\d{4}\$/");
			$telefone = trim($this->_request->getPost('telefone'));
			if(!$validator->isValid($telefone))
			foreach ($validator->getMessages() as $message) $errors[] = $message;

			$telefonePublico = trim($this->_request->getPost('telefonePublico'));
			if($telefonePublico && !$validator->isValid($telefonePublico))
			foreach ($validator->getMessages() as $message) $errors[] = $message;

			$celular = trim($this->_request->getPost('celular'));
			if($celular && !$validator->isValid($celular))
			foreach ($validator->getMessages() as $message) $errors[] = $message;
			
			$validator = new Proexc_Validate_Int();
			$cargaHorariaSemanal = trim($this->_request->getPost('cargaHorariaSemanal'));
			if(!$validator->isValid($cargaHorariaSemanal))
				foreach ($validator->getMessages() as $message) $errors[] = $message;

			if(!$errors) {
				$db = $tecnico->getDefaultAdapter();
				$db->beginTransaction();

				try {
					// Cria o novo tecnico
					$data = array(
						'nome'					=> $nome,
						'siape'					=> $siape,
						'idDepartamento'		=> $idDepartamento,
						'email'					=> $email,
						'telefone'				=> $telefone,
						'telefonePublico'		=> $telefonePublico,
						'celular'				=> $celular,
						'cargaHorariaSemanal'	=> $cargaHorariaSemanal
					);

					// Se for inserção
					if($tipo == "add") {
						$idTecnico = $tecnico->insert($data);

						// Insere um novo registro na tabela projeto_tecnico
						$projetoTecnico = new ProjetoTecnico();
						$data = array(
							'idProjeto'	=> $idProjeto,
							'idTecnico'	=> $idTecnico,
							'funcao'	=> $funcao
						);

						$projetoTecnico->insert($data);

						// Se for atualização
					} elseif ($tipo == "edit") {
						$where[] = $db->quoteInto('id = ?', $idTecnico);

						$tecnico->update($data, $where);
					}

					// Commit
					$db->commit();
				} catch (Exception $e) {
					$db->rollBack();
					$this->view->errors = $e->getMessage();
					echo $e->getMessage();
				}

				$this->_redirect('/projeto/equipe/id/'.$idProjeto);
				// Foi passado o id por 'GET'
			}
			$this->view->errors = $errors;
			$this->view->tecnico = new stdClass();
			$this->view->tecnico->id			  		= $idTecnico;
			$this->view->tecnico->nome 			  		= $nome;
			$this->view->tecnico->siape					= $siape;
			$this->view->tecnico->idDepartamento  		= $idDepartamento;
			$this->view->tecnico->email 		  		= $email;
			$this->view->tecnico->telefone 		  		= $telefone;
			$this->view->tecnico->telefonePublico 		= $telefonePublico;
			$this->view->tecnico->celular		  		= $celular;
			$this->view->tecnico->cargaHorariaSemanal	= $cargaHorariaSemanal;
		} else {
			$idProjeto = (int) $this->_request->getParam('id', 0);
			$idTecnico = (int) $this->_request->getParam('idTecnico', 0);

			// Inicializa valores referentes ao tecnico
			$this->view->tecnico = new stdClass();
			$this->view->tecnico->nome 			  		= "";
			$this->view->tecnico->siape					= "";
			$this->view->tecnico->idDepartamento  		= "";
			$this->view->tecnico->email 		  		= "";
			$this->view->tecnico->telefone 		  		= "";
			$this->view->tecnico->telefonePublico 		= "";
			$this->view->tecnico->celular		  		= "";
			$this->view->tecnico->cargaHorariaSemanal	= "";
			if($idTecnico > 0) $this->view->tecnico = $tecnico->find($idTecnico)->current();
		}
		$this->view->idProjeto = $idProjeto;

		// Preenche combo de possíveis departamentos
		$departamento = new Departamento();
		$this->view->departamentos = $departamento->fetchAll();

		$this->render();
	}

	function addColaboradorDocenteAction() {
		// Inicia
		$this->view->title = 'Colaborador Docente';
		$this->view->action = $this->view->baseUrl . "/projeto/addColaboradorDocente";
		$this->formColaboradorDocente();
	}

	function editColaboradorDocenteAction() {
		$this->view->title = 'Colaborador Docente';
		$this->view->action = $this->view->baseUrl . "/projeto/editColaboradorDocente";
		$this->formColaboradorDocente('edit');
	}

	function delColaboradorDocenteAction() {
		// Título da página
		$this->view->title = "Colaborador Docente";

		// Cria um objeto referente à tabela ColaboradorDocente
		$colaboradorDocente = new ColaboradorDocente();

		// Se a requisição for um método post
		if ($this->_request->isPost()) {
			// Pega os dados
			$idColaboradorDocente = (int)$this->_request->getPost('idColaboradorDocente');
			$idProjeto = (int)$this->_request->getPost('id');
			$del = $this->_request->getPost('del');

			// Se clicou em 'Yes' e existe o Colaborador Docente
			if ($del == 'Yes' && $idColaboradorDocente > 0) {
				$db = $colaboradorDocente->getAdapter();
				$db->beginTransaction();
					
				try {
					$where[] = $db->quoteInto('idProjeto = ?', $idProjeto);
					$where[] = $db->quoteInto('idColaboradorDocente = ?', $idColaboradorDocente);
					$projetoColaboradorDocente = new ProjetoColaboradorDocente();
					$projetoColaboradorDocente->delete($where);

					$where = $db->quoteInto('id = ?', $idColaboradorDocente);
					$colaboradorDocente->delete($where);

					$db->commit();
				} catch (Exception $e) {
					$db->rollBack();
					$this->view->error = $e->getMessage();
					return ;
				}
			}
			// A transação é do tipo 'get'
		} else {
			// Pega os dados passados na url
			$idProjeto = (int)$this->_request->getParam('id');
			$idColaboradorDocente = (int)$this->_request->getParam('idColaboradorDocente');

			// Testa o id
			if ($idColaboradorDocente > 0) {
				// somente mostra se achou o tecnico
				$this->view->colaboradorDocente = $colaboradorDocente->fetchRow('id='.$idColaboradorDocente);
				$this->view->idProjeto = $idProjeto;

				if ($this->view->colaboradorDocente->id > 0) {
					$this->render();
					return;
				}
			}
		}
		// volta se não renderizou (se o colaborador não existe)
		$this->_redirect("projeto/equipe/id/".$idProjeto);
	}

	private function formColaboradorDocente($tipo = 'add') {
		$colaboradorDocente = new ColaboradorDocente();

		$this->view->colaboradorDocente = new stdClass();

		if($this->_request->isPost()) {
			$errors = null;

			$idProjeto = (int) $this->_request->getPost('id');
			$idColaboradorDocente = (int) $this->_request->getPost('idColaboradorDocente');

			$validator = new Proexc_Validate_Alpha(true);
			$nome = trim($this->_request->getPost('nome'));
			if(!$validator->isValid($nome))
			foreach ($validator->getMessages() as $message) $errors[] = $message;
			
			$validator = new Proexc_Validate_Siape();
			$siape = trim($this->_request->getPost('siape'));
			if(!$validator->isValid($siape))
			foreach ($validator->getMessages() as $message) $errors[] = $message;
			
			$idDepartamento = (int) $this->_request->getPost('idDepartamento');

			$validator = new Proexc_Validate_EmailAddress();
			$email = trim($this->_request->getPost('email'));
			if(!$validator->isValid($email))
			foreach ($validator->getMessages() as $message) $errors[] = $message;

			$validator = new Proexc_Validate_Regex("/^\\(\\d{2}\\)\\d{4}-\\d{4}\$/");
			$telefone = trim($this->_request->getPost('telefone'));
			if(!$validator->isValid($telefone))
			foreach ($validator->getMessages() as $message) $errors[] = $message;

			$celular = trim($this->_request->getPost('celular'));
			if($celular && !$validator->isValid($celular))
			foreach ($validator->getMessages() as $message) $errors[] = $message;
			
			$validator = new Proexc_Validate_Int();
			$cargaHorariaSemanal = trim($this->_request->getPost('cargaHorariaSemanal'));
			if(!$validator->isValid($cargaHorariaSemanal))
				foreach ($validator->getMessages() as $message) $errors[] = $message;
			
			if(!$errors) {
				$db = $colaboradorDocente->getDefaultAdapter();
				$db->beginTransaction();

				try {
					// Cria o novo tecnico
					$data = array(
						'nome'					=> $nome,
						'siape'					=> $siape,
						'idDepartamento'		=> $idDepartamento,
						'email'					=> $email,
						'telefone'				=> $telefone,
						'celular'				=> $celular,
						'cargaHorariaSemanal'	=> $cargaHorariaSemanal
					);

					// Se for inserção
					if($tipo == "add") {
						$idColaboradorDocente = $colaboradorDocente->insert($data);

						// Insere um novo registro na tabela projeto_tecnico
						$projetoColaboradorDocente = new ProjetoColaboradorDocente();
						$data = array(
							'idProjeto'				=> $idProjeto,
							'idColaboradorDocente'	=> $idColaboradorDocente
						);

						$projetoColaboradorDocente->insert($data);

						// Se for atualização
					} elseif ($tipo == "edit") {
						$where = $db->quoteInto('id = ?', $idColaboradorDocente);
							
						$colaboradorDocente->update($data, $where);
					}

					// Commit
					$db->commit();
				} catch (Exception $e) {
					$db->rollBack();
					echo $e->getMessage();
				}

				$this->_redirect('/projeto/equipe/id/'.$idProjeto);
			}
			$this->view->errors = $errors;

			// Inicializa valores referentes ao colaborador Docente
			$this->view->colaboradorDocente->nome 					= $nome;
			$this->view->colaboradorDocente->siape 					= $siape;
			$this->view->colaboradorDocente->idDepartamento			= $idDepartamento;
			$this->view->colaboradorDocente->email 					= $email;
			$this->view->colaboradorDocente->telefone 				= $telefone;
			$this->view->colaboradorDocente->celular 				= $celular;
			$this->view->colaboradorDocente->cargaHorariaSemanal	= $cargaHorariaSemanal;
			// Foi passado o id por 'GET'
		} else {
			$idProjeto = (int) $this->_request->getParam('id', 0);
			$idColaboradorDocente = (int) $this->_request->getParam('idColaboradorDocente', 0);

			// Inicializa valores referentes ao colaborador Docente
			$this->view->colaboradorDocente->nome = "";
			$this->view->colaboradorDocente->siape = "";
			$this->view->colaboradorDocente->idDepartamento = "";
			$this->view->colaboradorDocente->email = "";
			$this->view->colaboradorDocente->telefone = "";
			$this->view->colaboradorDocente->celular = "";
			$this->view->colaboradorDocente->cargaHorariaSemanal = "";
			if($idColaboradorDocente > 0) $this->view->colaboradorDocente = $colaboradorDocente->find($idColaboradorDocente)->current();
		}
		$this->view->idProjeto = $idProjeto;

		// Preenche combo de possíveis departamentos
		$departamento = new Departamento();
		$this->view->departamentos = $departamento->fetchAll();

		$this->render();
	}

	function addColaboradorExternoAction() {
		// Inicia
		$this->view->title = 'Colaborador Externo';
		$this->view->action = $this->view->baseUrl . "/projeto/addColaboradorExterno";
		$this->formColaboradorExterno();
	}

	function editColaboradorExternoAction() {
		$this->view->title = 'Colaborador Externo';
		$this->view->action = $this->view->baseUrl . "/projeto/editColaboradorExterno";
		$this->formColaboradorExterno('edit');
	}

	function delColaboradorExternoAction() {
		// Título da página
		$this->view->title = "Colaborador Externo";

		// Cria um objeto referente à tabela ColaboradorExterno
		$colaboradorExterno = new ColaboradorExterno();

		// Se a requisição for um método post
		if ($this->_request->isPost()) {
			// Pega os dados
			$idColaboradorExterno = (int)$this->_request->getPost('idColaboradorExterno');
			$idProjeto = (int)$this->_request->getPost('id');
			$del = $this->_request->getPost('del');

			// Se clicou em 'Yes' e existe o Colaborador Externo
			if ($del == 'Yes' && $idColaboradorExterno > 0) {
				$db = $colaboradorExterno->getAdapter();
				$db->beginTransaction();
					
				try {
					$where[] = $db->quoteInto('idProjeto = ?', $idProjeto);
					$where[] = $db->quoteInto('idColaboradorExterno = ?', $idColaboradorExterno);
					$projetoColaboradorExterno = new ProjetoColaboradorExterno();
					$projetoColaboradorExterno->delete($where);

					$where = $db->quoteInto('id = ?', $idColaboradorExterno);
					$colaboradorExterno->delete($where);

					$db->commit();
				} catch (Exception $e) {
					$db->rollBack();
					$this->view->error = $e->getMessage();
					return ;
				}
			}
			// A transação é do tipo 'get'
		} else {
			// Pega os dados passados na url
			$idProjeto = (int)$this->_request->getParam('id');
			$idColaboradorExterno = (int)$this->_request->getParam('idColaboradorExterno');

			// Testa o id
			if ($idColaboradorExterno > 0) {
				// somente mostra se achou o colaborador externo
				$this->view->colaboradorExterno = $colaboradorExterno->fetchRow('id='.$idColaboradorExterno);
				$this->view->idProjeto = $idProjeto;

				if ($this->view->colaboradorExterno->id > 0) {
					$this->render();
					return;
				}
			}
		}
		// volta se não renderizou (se o colaborador não existe)
		$this->_redirect("projeto/equipe/id/".$idProjeto);
	}

	private function formColaboradorExterno($tipo = 'add') {
		$colaboradorExterno = new ColaboradorExterno();

		$this->view->colaboradorExterno = new stdClass();

		if($this->_request->isPost()) {
			$errors = null;

			$idProjeto = (int) $this->_request->getPost('id');
			$idColaboradorExterno = (int) $this->_request->getPost('idColaboradorExterno');

			$validator = new Proexc_Validate_Alpha(true);
			$nome = trim($this->_request->getPost('nome'));
			if(!$validator->isValid($nome))
			foreach ($validator->getMessages() as $message) $errors[] = $message;

			$cpf = trim($this->_request->getPost('cpf'));

			$validator = new Proexc_Validate_EmailAddress();
			$email = trim($this->_request->getPost('email'));
			if(!$validator->isValid($email))
			foreach ($validator->getMessages() as $message) $errors[] = $message;

			$validator = new Proexc_Validate_Regex("/^\\(\\d{2}\\)\\d{4}-\\d{4}\$/");
			$telefone = trim($this->_request->getPost('telefone'));
			if(!$validator->isValid($telefone))
			foreach ($validator->getMessages() as $message) $errors[] = $message;

			$celular = trim($this->_request->getPost('celular'));
			if($celular && !$validator->isValid($celular))
			foreach ($validator->getMessages() as $message) $errors[] = $message;

			if(!$errors) {
				$db = $colaboradorExterno->getDefaultAdapter();
				$db->beginTransaction();

				try {
					// Cria o novo tecnico
					$data = array(
						'nome'		=> $nome,
						'cpf'		=> $cpf,
						'email'		=> $email,
						'telefone'	=> $telefone,
						'celular'	=> $celular
					);

					// Se for inserção
					if($tipo == "add") {
						$idColaboradorExterno = $colaboradorExterno->insert($data);

						// Insere um novo registro na tabela projeto_colaboradorexterno
						$projetoColaboradorExterno = new ProjetoColaboradorExterno();
						$data = array(
							'idProjeto'				=> $idProjeto,
							'idColaboradorExterno'	=> $idColaboradorExterno
						);

						$projetoColaboradorExterno->insert($data);

						// Se for atualização
					} elseif ($tipo == "edit") {
						$where = $db->quoteInto('id = ?', $idColaboradorExterno);
							
						$colaboradorExterno->update($data, $where);
					}

					// Commit
					$db->commit();
				} catch (Exception $e) {
					$db->rollBack();
					echo $e->getMessage();
				}

				$this->_redirect('/projeto/equipe/id/'.$idProjeto);
			}
			$this->view->errors = $errors;

			// Inicializa valores referentes ao colaborador Externo
			$this->view->colaboradorExterno->id			= $idColaboradorExterno;
			$this->view->colaboradorExterno->nome 		= $nome;
			$this->view->colaboradorExterno->cpf 		= $cpf;
			$this->view->colaboradorExterno->email 		= $email;
			$this->view->colaboradorExterno->telefone	= $telefone;
			$this->view->colaboradorExterno->celular	= $celular;
			// Foi passado o id por 'GET'
		} else {
			$idProjeto = (int) $this->_request->getParam('id', 0);
			$idColaboradorExterno = (int) $this->_request->getParam('idColaboradorExterno', 0);
				
			// Inicializa valores referentes ao colaborador Externo
			$this->view->colaboradorExterno->id			= "";
			$this->view->colaboradorExterno->nome 		= "";
			$this->view->colaboradorExterno->cpf 		= "";
			$this->view->colaboradorExterno->email 		= "";
			$this->view->colaboradorExterno->telefone	= "";
			$this->view->colaboradorExterno->celular	= "";
			if($idColaboradorExterno > 0) $this->view->colaboradorExterno = $colaboradorExterno->find($idColaboradorExterno)->current();
		}
		$this->view->idProjeto = $idProjeto;

		$this->render();
	}

	/**
	 * Controller parceiros
	 *
	 */
	function parceirosAction () {
		$this->view->title = 'Parceiros';

		$projeto = new Projeto();

		if($this->_request->isPost()) {
			$idProjeto = (int) $this->_request->getPost('id');
			$button = $this->_request->getPost('button');

			// Se clicou em próximo, segue para formulário de descricao do projeto
			if($button == 'Proximo') $this->_redirect('/projeto/descricao/id/'.$idProjeto);

			// Foi passado o id por 'GET'
		} else {
			$idProjeto = (int) $this->_request->getParam('id', 0);
		}

		if($idProjeto > 0) $this->view->projeto = $projeto->find($idProjeto)->current();

		// Preenche combo de possíveis parceiros
		$tabParceiro = new Parceiro();
		$this->view->parceiros = $tabParceiro->fetchParceirosByProjeto($idProjeto);

		$this->render();
	}

	/**
	 * Gerencia o formulário de parceiro externo
	 *
	 * @param $tipo Define o tipo do formulário, edição ou novo
	 */
	private function formParceiroExterno($tipo = 'add') {
		$parceiro = new Parceiro();

		$this->view->parceiro = new stdClass();

		if($this->_request->isPost()) {
			$errors = null;

			$idProjeto = (int) $this->_request->getPost('id');
			$idParceiro = (int) $this->_request->getPost('idParceiro');

			$validatorAlpha = new Proexc_Validate_Alpha(true);
			$nomeInstituicao = trim($this->_request->getPost('nomeInstituicao'));
			if(!$validatorAlpha->isValid($nomeInstituicao))
				foreach ($validatorAlpha->getMessages() as $message) $errors[] = $message;

			$validatorRequired = new Proexc_Validate_NotEmpty();
			$cnpj = trim($this->_request->getPost('cnpj'));
			if(!$validatorRequired->isValid($cnpj))
				foreach ($validatorRequired->getMessages() as $message) $errors[] = $message;

			$validatorTelefone = new Proexc_Validate_Regex("/^\\(\\d{2}\\)\\d{4}-\\d{4}\$/");
			$telefone = trim($this->_request->getPost('telefone'));
			if(!$validatorTelefone->isValid($telefone))
				foreach ($validatorTelefone->getMessages() as $message) $errors[] = $message;

			$nomeResponsavel = trim($this->_request->getPost('nomeResponsavel'));
			if(!$validatorAlpha->isValid($nomeResponsavel))
				foreach ($validatorAlpha->getMessages() as $message) $errors[] = $message;

			$nomeContato = trim($this->_request->getPost('nomeContato'));
			if(!$validatorAlpha->isValid($nomeContato))
				foreach ($validatorAlpha->getMessages() as $message) $errors[] = $message;

			$telefoneContato = trim($this->_request->getPost('telefoneContato'));
			if(!$validatorTelefone->isValid($telefoneContato))
				foreach ($validatorTelefone->getMessages() as $message) $errors[] = $message;

			$celularContato = trim($this->_request->getPost('celularContato'));
			if($celularContato && !$validatorTelefone->isValid($celularContato))
				foreach ($validatorTelefone->getMessages() as $message) $errors[] = $message;

			if(!$errors) {
				$db = $parceiro->getDefaultAdapter();
				$db->beginTransaction();

				try {
					// Cria o novo parceiro
					$data = array(
						'nomeInstituicao'	=> $nomeInstituicao,
						'cnpj'				=> $cnpj,
						'telefone'			=> $telefone,
						'nomeResponsavel'	=> $nomeResponsavel,
						'nomeContato'		=> $nomeContato,
						'telefoneContato'	=> $telefoneContato,
						'celularContato'	=> $celularContato
					);

					// Se for inserção
					if($tipo == "add") {
						$idParceiro = $parceiro->insert($data);

						// Insere um novo registro na tabela projeto_parceiro
						$projetoParceiro = new ProjetoParceiro();
						$data = array(
							'idProjeto'		=> $idProjeto,
							'idParceiro'	=> $idParceiro
						);

						$projetoParceiro->insert($data);

						// Se for atualização
					} elseif ($tipo == "edit") {
						$where = $db->quoteInto('id = ?', $idParceiro);
							
						$parceiro->update($data, $where);
					}

					// Commit
					$db->commit();
				} catch (Exception $e) {
					$db->rollBack();
					throw $e;
				}

				$this->_redirect('/projeto/parceiros/id/'.$idProjeto);
			}
			$this->view->errors = $errors;

			// Inicializa valores referentes ao colaborador Externo
			$this->view->parceiro->id 				= $idParceiro;
			$this->view->parceiro->nomeInstituicao 	= $nomeInstituicao;
			$this->view->parceiro->cnpj 			= $cnpj;
			$this->view->parceiro->telefone 		= $telefone;
			$this->view->parceiro->nomeResponsavel 	= $nomeResponsavel;
			$this->view->parceiro->nomeContato 		= $nomeContato;
			$this->view->parceiro->telefoneContato	= $telefoneContato;
			$this->view->parceiro->celularContato	= $celularContato;
		// Foi passado o id por 'GET'
		} else {
			$idProjeto = (int) $this->_request->getParam('id', 0);
			$idParceiro = (int) $this->_request->getParam('idParceiro', 0);

			// Inicializa valores referentes ao colaborador Externo
			$this->view->parceiro->nomeInstituicao 	= "";
			$this->view->parceiro->cnpj 			= "";
			$this->view->parceiro->telefone 		= "";
			$this->view->parceiro->nomeResponsavel 	= "";
			$this->view->parceiro->nomeContato 		= "";
			$this->view->parceiro->telefoneContato	= "";
			$this->view->parceiro->celularContato	= "";
			if($idParceiro > 0) $this->view->parceiro = $parceiro->find($idParceiro)->current();
		}
		$this->view->idProjeto = $idProjeto;

		$this->render();
	}

	function addParceiroExternoAction() {
		// Inicia
		$this->view->title = 'Parceiro Externo';
		$this->view->action = $this->view->baseUrl . "/projeto/addParceiroExterno";
		$this->formParceiroExterno();
	}

	function editParceiroExternoAction() {
		$this->view->title = 'Parceiro Externo';
		$this->view->action = $this->view->baseUrl . "/projeto/editParceiroExterno";
		$this->formParceiroExterno('edit');
	}

	function delParceiroExternoAction() {
		// Título da página
		$this->view->title = "Parceiro Externo";

		// Cria um objeto referente à tabela Parceiro
		$parceiro = new Parceiro();

		// Se a requisição for um método post
		if ($this->_request->isPost()) {
			// Pega os dados
			$idParceiro = (int)$this->_request->getPost('idParceiro');
			$idProjeto = (int)$this->_request->getPost('id');
			$del = $this->_request->getPost('del');

			// Se clicou em 'Yes' e existe o Colaborador Externo
			if ($del == 'Yes' && $idParceiro > 0) {
				$db = $parceiro->getAdapter();
				$db->beginTransaction();
					
				try {
					$where[] = $db->quoteInto('idProjeto = ?', $idProjeto);
					$where[] = $db->quoteInto('idParceiro = ?', $idParceiro);
					$projetoParceiro = new ProjetoParceiro();
					$projetoParceiro->delete($where);

					$where = $db->quoteInto('id = ?', $idParceiro);
					$parceiro->delete($where);

					$db->commit();
				} catch (Exception $e) {
					$db->rollBack();
					$this->view->error = $e->getMessage();
					return ;
				}
			}
			// A transação é do tipo 'get'
		} else {
			// Pega os dados passados na url
			$idProjeto = (int)$this->_request->getParam('id');
			$idParceiro = (int)$this->_request->getParam('idParceiro');

			// Testa o id
			if ($idParceiro > 0) {
				// somente mostra se achou o parceiro
				$this->view->parceiro = $parceiro->fetchRow('id='.$idParceiro);
				$this->view->idProjeto = $idProjeto;

				if ($this->view->parceiro->id > 0) {
					$this->render();
					return;
				}
			}
		}
		// volta se não renderizou (se o colaborador não existe)
		$this->_redirect("projeto/parceiros/id/".$idProjeto);
	}

	function descricaoAction () {
		// Seta o título
		$this->view->title = "Descrição do projeto";

		$projeto = new Projeto();

		if($this->_request->isPost()) {
			$idProjeto = (int) $this->_request->getPost('id');
			$fundamento = $this->_request->getPost('fundamento');
			$objetivos = $this->_request->getPost('objetivos');
			$metodologia = $this->_request->getPost('metodologia');
			$publicoAlvo = $this->_request->getPost('publicoAlvo');
			$pessoasAtendidas = $this->_request->getPost('pessoasAtendidas');
			$resumo = $this->_request->getPost('resumo');
			$bolsasJustificativa = $this->_request->getPost('bolsasJustificativa');
			$bolsasPretendidas = $this->_request->getPost('bolsasPretendidas');

			$errors = null;

			$validator = new Zend_Validate_Between(0, 200);
			if(!$validator->isValid($bolsasPretendidas))
			foreach ($validator->getMessages() as $message) $errors[] = $message;

			if(!$errors) {
				$data = array(
					'fundamento'			=> $fundamento,
					'objetivos' 			=> $objetivos,
					'metodologia'			=> $metodologia,
					'publicoAlvo'			=> $publicoAlvo,
					'pessoasAtendidas' 		=> $pessoasAtendidas,
					'resumo'				=> $resumo,
					'bolsasJustificativa'	=> $bolsasJustificativa,
					'bolsasPretendidas'		=> $bolsasPretendidas
				);

				$projeto->updateById($data, $idProjeto);

				$button = $this->_request->getPost('button');

				// Se clicou em próximo, segue para formulário de recursos
				if($button == 'Proximo') $this->_redirect('/projeto/recursos/id/'.$idProjeto);
			}
			$this->view->errors = $errors;
			// Foi passado o id por 'GET'
		} else {
			$idProjeto = (int) $this->_request->getParam('id', 0);
		}

		if($idProjeto > 0) $this->view->projeto = $projeto->find($idProjeto)->current();

		$this->render();
	}

	function recursosAction () {
		// Seta o título
		$this->view->title = "Recursos";

		// Referencia à tabela recursos
		$tabRecursos = new Recursos();

		// Define uma instância do projeto atual
		$idProjeto = (int) $this->_request->getParam('id', 0);
		$tabProjeto = new Projeto();
		$projeto = $tabProjeto->find($idProjeto)->current();

		if($this->_request->isPost()) {
			$possuiRecursos = (boolean) $this->_request->getPost('possuiRecursos');

			if($possuiRecursos) {
				//$idRecursos = (int) $this->_request->getPost('idRecursos');
				$gestora = $this->_request->getPost('gestora');
				$ano = $this->_request->getPost('ano');
				//$recursosUfjfFonte = $this->_request->getPost('recursosUfjfFonte');
				//$recursosUfjfValor = $this->_request->getPost('recursosUfjfValor');
				$recursosExternosFonte = $this->_request->getPost('recursosExternosFonte');
				$recursosExternosValor = $this->_request->getPost('recursosExternosValor');
				//$diariaUfjf = $this->_request->getPost('diariaUfjf');
				$diariaExterno = $this->_request->getPost('diariaExterno');
				//$passagemUfjf = $this->_request->getPost('passagemUfjf');
				$passagemExterno = $this->_request->getPost('passagemExterno');
				//$alimentacaoUfjf = $this->_request->getPost('alimentacaoUfjf');
				$alimentacaoExterno = $this->_request->getPost('alimentacaoExterno');
				$bolsaDiscente = $this->_request->getPost('bolsaDiscente');
				$pagamentoCoordenador = $this->_request->getPost('pagamentoCoordenador');
				$pagamentoEquipe = $this->_request->getPost('pagamentoEquipe');
				//$pagamentoPJUfjf = $this->_request->getPost('pagamentoPJUfjf');
				$pagamentoPJExterno = $this->_request->getPost('pagamentoPJExterno');
				//$pagamentoPFUfjf = $this->_request->getPost('pagamentoPFUfjf');
				$pagamentoPFExterno = $this->_request->getPost('pagamentoPFExterno');
				$equipamentos = $this->_request->getPost('equipamentos');
				$material = $this->_request->getPost('material');

				$data = array(
					'gestora' 				=> $gestora,
					'ano' 					=> $ano,
				//'recursosUfjfFonte'		=> $recursosUfjfFonte,
				//'recursosUfjfValor'		=> $recursosUfjfValor,
					'recursosExternosFonte'	=> $recursosExternosFonte,
					'recursosExternosValor'	=> $recursosExternosValor,
				//'diariaUfjf'			=> $diariaUfjf,
					'diariaExterno'			=> $diariaExterno,
				//'passagemUfjf'			=> $passagemUfjf,
					'passagemExterno'		=> $passagemExterno,
				//'alimentacaoUfjf'		=> $alimentacaoUfjf,
					'alimentacaoExterno'	=> $alimentacaoExterno,
					'bolsaDiscente'			=> $bolsaDiscente,
					'pagamentoCoordenador'	=> $pagamentoCoordenador,
					'pagamentoEquipe'		=> $pagamentoEquipe,
				//'pagamentoPJUfjf'		=> $pagamentoPJUfjf,
					'pagamentoPJExterno'	=> $pagamentoPJExterno,
				//'pagamentoPFUfjf'		=> $pagamentoPFUfjf,
					'pagamentoPFExterno' 	=> $pagamentoPFExterno,
					'equipamentos' 			=> $equipamentos,
					'material'				=> $material
				);
					
				// Se existe um id de recurso já ligado a tabela atualiza na tabela de recurso
				if($projeto->idRecursos) $tabRecursos->updateById($data, $projeto->idRecursos);
				// Senão cria registro na tabela recursos e atualiza o id criado na tabela projeto
				else {
					$db = $tabProjeto->getAdapter();
					$db->beginTransaction();
					try {
						$projeto->idRecursos = $tabRecursos->insert($data);
						$projeto->save();
						$db->commit();
					} catch (Exception $e) {
						$db->rollBack();
						$this->view->error = $e->getMessage();
					}
				}

				$button = $this->_request->getPost('button');
				// Se usuário clicoe em não possui recursos
			} else
			if($projeto->idRecursos) {
				$db = $tabRecursos->getAdapter();
				$db->beginTransaction();
				try {
					$idRecursos = $projeto->idRecursos;
					$projeto->idRecursos = null;
					$projeto->save();
					$tabRecursos->deleteById($idRecursos);
					$db->commit();
				} catch (Exception $e) {
					$db->rollBack();
					$this->view->error = $e->getMessage();
				}
			}
			$this->view->salvo = 1;
		}
		// Se existe o projeto
		if($idProjeto > 0) $this->view->recursos = $projeto->findParentRecursos();

		if(!$this->view->recursos) {
			$this->view->recursos = new stdClass();
			$this->view->recursos->id = null;
			$this->view->recursos->gestora = null;
			$this->view->recursos->ano = null;
			$this->view->recursos->recursosExternosFonte = null;
		}

		// Preenche o campo idProjeto
		$this->view->projeto = $projeto;


		$this->render();
	}

	function imprimirFormularioAction() {
		$this->_helper->viewRenderer->setNoRender(true);

		$id = (int) $this->_request->getParam('id', 0);
		if($id > 0) {
			$tabProjeto = new Projeto();
			$projeto = $tabProjeto->fetchRow('id = ' . $id);

			$formulario = new FormularioProjeto($projeto);
			$formulario->Output('formulario.pdf', 'D');
		}
	}


	
	function imprimirRelatorioProjetoAction(){
		$this->_helper->viewRenderer->setNoRender(true);
		
		$id = (int) $this->_request->getParam('id',0);
		if($id > 0){
			$tabProjeto = new Projeto();
			$projeto = $tabProjeto->fetchRow('id = '. $id);
			
			$formulario = new FormularioRelatorioProjeto($projeto);
			$formulario->Output('relatoriofinal.pdf','D');
		
		}
		
	}


	function delAction() {
		// Título da página
		$this->view->title = "Apagar Projeto";

		// Cria um objeto referente à tabela Projeto
		$projeto = new Projeto();

		// Se a requisição for um método post
		if ($this->_request->isPost()) {
			// Pega os dados
			$idProjeto = (int)$this->_request->getPost('id');
			$idRecursos = (int)$this->_request->getPost('idRecursos');
			$del = $this->_request->getPost('del');

			// Se clicou em 'Yes' e existe o Projeto
			if ($del == 'Yes' && $idProjeto > 0) {
				$db = $projeto->getAdapter();
				$db->beginTransaction();
					
				try {
					$where = $db->quoteInto('idProjeto = ?', $idProjeto);
					$projetoColaboradorDocente = new ProjetoColaboradorDocente();
					$projetoColaboradorDocente->delete($where);

					$projetoColaboradorExterno = new ProjetoColaboradorExterno();
					$projetoColaboradorExterno->delete($where);

					$projetoParceiro = new ProjetoParceiro();
					$projetoParceiro->delete($where);

					$projetoTecnico = new ProjetoTecnico();
					$projetoTecnico->delete($where);

					$where = $db->quoteInto('id = ?', $idProjeto);
					$projeto->delete($where);

					$where = $db->quoteInto('id = ?', $idRecursos);
					$recursos = new Recursos();
					$recursos->delete($where);

					$db->commit();
				} catch (Exception $e) {
					$this->_helper->viewRenderer->setNoRender(true);
					$db->rollBack();
					echo $e->getMessage();
					return ;
				}
			}
			// A transação é do tipo 'get'
		} else {
			// Pega os dados passados na url
			$idProjeto = (int)$this->_request->getParam('id');

			// Testa o id
			if ($idProjeto > 0) {
				// somente mostra se achou o projeto
				$this->view->projeto = $projeto->fetchRow('id='.$idProjeto);

				if ($this->view->projeto->id > 0) {
					$this->render();
					return;
				}
			}
		}
		// volta se não renderizou (se o projeto não existe)
		$this->_redirect("Index/listProjetos");
	}
	
	function fecharAction() {
		// Título da página
		$this->view->title = "Concluir Projeto";

		// Cria um objeto referente à tabela Projeto
		$tabProjeto = new Projeto();
		
		// Pega os dados
		$idProjeto = (int)$this->_request->getParam('id', 0);
		$projeto = $tabProjeto->find($idProjeto)->current();
		
		// Se a requisição for um método post
		if ($this->_request->isPost()) {
			// Pega os dados
			$idProjeto = (int)$this->_request->getPost('id');
			$fecha = $this->_request->getPost('fecha');

			// Se clicou em 'Yes' e existe o Projeto
			if ($fecha == 'Sim' && $idProjeto > 0) {
//				$data = array(
//					"fechado"	=> 1
//				);
//				
//				$projeto->updateById($data, $idProjeto);
				$projeto->fechado = 1;
				$projeto->save();
						
			}

			// A transação é do tipo 'get'
		} else {

			// Pega os dados passados na url
			$idProjeto = (int)$this->_request->getParam('id');
			// Testa o id

			if ($idProjeto > 0) {
			if ($projeto) {
				// somente mostra se achou o projeto
				$this->view->projeto = $projeto;

				if ($this->view->projeto->id > 0) {
					$this->render();
					return;
				}

				$this->view->projeto = $projeto;
				$this->render();
				return;
			}
		}
		// volta se não renderizou (se o projeto não existe)
		$this->_redirect("Index/listValidatedProjetos");
		}
	}
	
	function fecharRelatorioAction(){
		// Título da página
		$this->view->title = "Concluir Relatório Final de Projeto";

		// Cria um objeto referente à tabela Projeto
		$tabProjeto = new Projeto();
		
		//Cria um objeto referente à tabela RelatorioFinal
		$tabRelFinal = new RelatorioFinal();
		
		// Pega os dados
		$idProjeto = (int)$this->_request->getParam('id', 0);
		$projeto = $tabProjeto->find($idProjeto)->current();
		
		
		// Se a requisição for um método post
		if ($this->_request->isPost()) {
			// Pega os dados
			$idProjeto = (int)$this->_request->getPost('id');
			$fecha = $this->_request->getPost('fecha');
			// Se clicou em 'Yes' e existe o Relatorio
			if ($fecha == 'Sim' && $idProjeto > 0) {
				$relatorioFinal = $projeto->findParentRelatorioFinal();
				$relatorioFinal->fechado = 1;
				$relatorioFinal->save();
			}	
		
		// A transação é do tipo 'get'
		} else {

			// Pega os dados passados na url
			$idProjeto = (int)$this->_request->getParam('id');
			// Testa o id
			if ($idProjeto > 0) {
			if ($projeto) {
				// somente mostra se achou o projeto
				$this->view->projeto = $projeto;

				if ($this->view->projeto->id > 0) {
					$this->render();
					return;
				}

				$this->view->projeto = $projeto;
				$this->render();
				return;
			}
		}
		// volta se não renderizou (se o projeto não existe)
		$this->_redirect("Index/listValidatedProjetos");
		}
	
	
	

	}

	function relatorioFinalAction() {
		$this->view->title = "Relatório Final do Projeto";
		$tabRelatorioFinal = new RelatorioFinal();
		$tabProjeto = new Projeto();
		$tabParceiro = new Parceiro();

		if($this->_request->isPost()) {
			$idProjeto = (int) $this->_request->getPost('id');
			$projeto = $tabProjeto->find($idProjeto)->current();
		
			$errors = null;
			
			$renovado = (int) $this->_request->getPost('renovado'); 
			$disciplinas = $this->_request->getPost('disciplinas');
			$estagio = $this->_request->getPost('estagio');
			$credito = $this->_request->getPost('credito');
			$projetoPost = $this->_request->getPost('projeto');
			$docentes = $this->_request->getPost('docentes');
			$bolsistas = (int) $this->_request->getPost('bolsistas');
			$naoBolsistas = (int) $this->_request->getPost('naoBolsistas');
			$posGraduacao = (int) $this->_request->getPost('posGraduacao');
			$tecnicos = (int) $this->_request->getPost('tecnicos');
			$outrosEnvolvidos = (int) $this->_request->getPost('outrosEnvolvidos');
			$comunidade = (int) $this->_request->getPost('comunidade');
			$publicoAtingido = (int) $this->_request->getPost('publicoAtingido');
			$atendimentosGrupo = (int) $this->_request->getPost('atendimentosGrupo');
			$atendimentosIndividuais = (int) $this->_request->getPost('atendimentosIndividuais');
			$livro = (int) $this->_request->getPost('livro');
			$comunicacao = (int) $this->_request->getPost('atendimentosIndividuais');
			$programaRadio = (int) $this->_request->getPost('programaRadio');
			$capituloLivro = (int) $this->_request->getPost('capituloLivro');
			$relatorioTecnico = (int) $this->_request->getPost('relatorioTecnico');
			$programaTv = (int) $this->_request->getPost('programaTv');
			$anais = (int) $this->_request->getPost('anais');
			$filme = (int) $this->_request->getPost('filme');
			$aplicativo = (int) $this->_request->getPost('atendimentosIndividuais');
			$manual = (int) $this->_request->getPost('manual');
			$video = (int) $this->_request->getPost('video');
			$jogoEducativo = (int) $this->_request->getPost('jogoEducativo');
			$jornal = (int) $this->_request->getPost('jornal');
			$cd = (int) $this->_request->getPost('cd');
			$produtoArtistico = (int) $this->_request->getPost('produtoArtistico');
			$revista = (int) $this->_request->getPost('revista');
			$dvd = (int) $this->_request->getPost('dvd');
			$artigo = (int) $this->_request->getPost('artigo');
			$outrosAudiovisual = (int) $this->_request->getPost('outrosAudiovisual');
			$outrosProducao = (int) $this->_request->getPost('outrosProducao');
			$tipoProducao = $this->_request->getPost('tipoProducao');
			$detalheProducao = $this->_request->getPost('detalheProducao');
			$relatorioFinal = $this->_request->getPost('relatorioFinal');
			
			if(!$errors) {
				$data = array(
					
					'disciplinas'						=> $disciplinas,
					'estagio'							=> $estagio,
					'creditos'							=> $credito,
					'projeto'							=> $projetoPost,
					'docentesEnvolvidos'				=> $docentes,
					'alunosGraduacaoBolsistasEnvolvidos'=> $bolsistas,
					'alunosGraduacaoNaoBolsistasEnvolvidos'	=> $naoBolsistas,
					'alunosPosGraduacaoEnvolvidos'      => $posGraduacao,
					'tecnicosAdministrativosEnvolvidos'	=> $tecnicos,
					'pessoasOutrasIESEnvolvidas'  		=> $outrosEnvolvidos,
					'pessoasComunidadeEnvolvidas'		=> $comunidade,
					'publicoAtingido'   				=> $publicoAtingido,
					'atendimentosSemanaisGrupo'			=> $atendimentosGrupo,
					'atendimentosSemanaisIndividuais'   => $atendimentosIndividuais,
					'producaoLivros'					=> $livro,
					'producaoComunicacao'	   			=> $comunicacao,
					'producaoProgramasRadio'			=> $programaRadio,
					'producaoCapitulosLivros'			=> $capituloLivro,
					'producaoRelatoriosTecnicos'		=> $relatorioTecnico,
					'producaoProgramasTv'				=> $programaTv,
					'producaoAnais'						=> $anais,
					'producaoAudiovisualfilme'			=> $filme,
					'producaoAplicativosComputador'		=> $aplicativo,
					'producaoManuais'					=> $manual,
					'producaoAudiovisualVideos'			=> $video,
					'producaoJogosEducativos'			=> $jogoEducativo,
					'producaoJornais'		 			=> $jornal,
					'producaoAudiovisualCds'			=> $cd,
					'producaoProdutosArtisticos'		=> $produtoArtistico,
					'producaoRevistas'					=> $revista,
					'producaoAudiovisualDvds'			=> $dvd,
					'producaoArtigos'					=> $artigo,
					'producaoAudiovisualOutros'			=> $outrosAudiovisual,
					'producaoOutros'					=> $outrosProducao,
					'producaoOutrosTexto'				=> $tipoProducao,
					'producaoDetalhamento'				=> $detalheProducao,
					'relatorioFinal'					=> $relatorioFinal	
				);
				if($projeto->idRelatorioFinal != null) {
					$tabRelatorioFinal->updateById($data, $projeto->idRelatorioFinal);
				} else {
					$db = $tabRelatorioFinal->getDefaultAdapter();
					$db->beginTransaction();
					try{
						$idRelatorioFinal = $tabRelatorioFinal->insert($data);
						
						$dataProjeto = array(
							"idRelatorioFinal" => $idRelatorioFinal 
						);
						$tabProjeto->updateById($dataProjeto, $projeto->id);
						
						$db->commit();
					}catch(Exception $e) {
						$db->rollBack();
						$this->view->errors = $e->getMessage();
						return;
					}
				}
				$this->_redirect('Index/listValidatedProjetos');
			}
			$projeto = $tabProjeto->find($idProjeto)->current();
			$this->view->projeto = $projeto;
			// Aqui coloca na view os dados inseridos pelo usuário
			$this->view->relatorioFinal = $tabRelatorioFinal->find($projeto->idRelatorioFinal)->current();
			$this->render();
		// Get
		} else {
			$idProjeto = (int) $this->_request->getParam('id', 0);
			$projeto = $tabProjeto->find($idProjeto)->current();
			$parceiros = $tabParceiro->fetchParceirosByProjeto($idProjeto);
		
			if($projeto) {
				$this->view->projeto = $projeto;
				$this->view->parceiros = $parceiros;
				if($projeto->idRelatorioFinal)
					$this->view->relatorioFinal = $tabRelatorioFinal->find($projeto->idRelatorioFinal)->current();
				$this->render();
				return;
			}
		}
		//c
		//$this->_redirect("Index/listValidatedProjetos");;
	}
}
