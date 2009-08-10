<?php

require_once 'Zend/Date.php';
require_once 'Zend/Validate/Int.php';
require_once 'Zend/Validate/NotEmpty.php';
require_once 'Zend/Validate/Date.php';
require_once 'Zend/Validate/Alpha.php';

require_once 'Zend/Validate/EmailAddress.php';
require_once 'Zend/Validate/Regex.php';
require_once 'Zend/Validate/Between.php';

require_once 'Proexc/Controller/Action.php';
require_once 'Proexc/Validate/Siape.php';
require_once 'Proexc/Validate/Alpha.php';
require_once 'Proexc/Validate/Date.php';

require_once 'Curso.php';
require_once 'CursoColaboradorDocente.php';
require_once 'ColaboradorDocente.php';
require_once 'AreaTematica.php';
require_once 'Coordenador.php';
require_once 'Departamento.php';
require_once 'Recursos.php';
require_once 'FormularioCurso.php';


class CursoController extends Proexc_Controller_Action {

	/**
	 * Verifica se o Coordenador logado tem acesso ao curso
	 */
	function preDispatch() {
		parent::preDispatch();
		
		$ok = 0;
		$tabCurso = new Curso();

		// Verifica se o coordenador tem acesso a ações para cursos fechados e não-validados
		if($this->_request->getActionName() == 'imprimirFormulario') {
			$cursos = $tabCurso->fetchClosedByCoordenador($this->user->id);


			
//			VERIFICAR ISSO VARGINHA SAFADO	
//			$curso = $tabCurso->find($this->_request->getParam('id'))->current();
//			if(!(($curso->fechado == 1) && ($curso->idCoordenador == $this->user->id)))
//				if(!$ok) $this->_redirect('/');

			

			
			foreach ($cursos as $curso) {
				if($curso->id == $this->_request->getParam('id')){
					$ok = 1;
					break;
				}
			}
			if(!$ok) $this->_redirect('/');
		} else
		
		// Verifica se o coordenador tem acesso a ações para cursos abertos e validados
		if($this->_request->getActionName() == 'relatorioFinal') {
			$cursos = $tabCurso->fetchOpenAndValidatedByCoordenador($this->user->id);

			foreach ($cursos as $curso) {
				if($curso->id == $this->_request->getParam('id')){
					$ok = 1;
					break;
				}
			}
			if(!$ok) $this->_redirect('/');
		} else
		
		// Verifica se o coordenador tem acesso a ações para cursos não-validados
		if($this->_request->getActionName() != 'add' && $this->_request->getActionName() != 'relatorioFinal') {
			$cursos = $tabCurso->fetchOpenAndUnvalidatedByCoordenador($this->user->id);

			foreach ($cursos as $curso) {
				if($curso->id == $this->_request->getParam('id')){
					$ok = 1;
					break;
				}
			}
			if(!$ok) $this->_redirect('/');
		}
	}

	/**
	 * Controller para criação de um novo curso. Requisita apenas o nome do curso e cria
	 * inserindo também o id do coordenador. Logo após criado o usuário é enviado para a tela
	 * de edição do curso.
	 */
	function addAction() {
		$this->view->title = "Cadastrar Novo Curso";

		$curso = new Curso();
		
		if($this->_request->isPost()) {
			$errors = null;

			$validator = new Zend_Validate_NotEmpty();
			$titulo = trim($this->_request->getPost('titulo'));
			if(!$validator->isValid($titulo)) {
				foreach ($validator->getMessages() as $message) $errors[] = $message;
			}

			if(!$errors) {
				$data = array(
					'titulo'		=> $titulo,
					'idCoordenador'	=> $this->user->id);

				$pk = $curso->insert($data);

				$this->_redirect('curso/geral/id/'.$pk);
			}
			$this->view->errors = $errors;
		}

		$this->view->curso = $curso;

		$this->render();
	}

	/**
	 * Controller para renomear o curso
	 */
	function renameAction() {
		$this->view->title = "Renomear Curso";

		$curso = new Curso();

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

				$pk = $curso->updateById($data, $id);

				$this->_redirect('index/listCursos');
			}
			$this->view->errors = $errors;
		} else {
			$id = (int) $this->_request->getParam('id', 0);
			if($id > 0) $this->view->curso = $curso->find($id)->current();
		}

		$this->render();
	}

	/**
	 * Formulário de curso para preenchimento dos dados gerais.
	 */
	function geralAction() {
		// Seta o título
		$this->view->title = "Geral";

		$curso = new Curso();
		
		$id = (int) $this->_request->getParam('id', 0);
		$this->view->curso = $curso->find($id)->current();

		if($this->_request->isPost()) {
			$errors = null;

			$id = (int) $this->_request->getPost('id');
			$idAreaTematica = (int) $this->_request->getPost('idAreaTematica');

			$validator = new Proexc_Validate_Date();
			$dataInicio = $this->_request->getPost('dataInicio');
			if(!$validator->isValid($dataInicio)) {
				foreach ($validator->getMessages() as $message) $errors[] = "Data de Início: ".$message;
			}else {
				$dataInicio = new Zend_Date($dataInicio);
				$dataInicio = $dataInicio->get('y-MM-dd');
			}

			$dataFinal = $this->_request->getPost('dataFinal');
			if(!$validator->isValid($dataFinal)) {
				foreach ($validator->getMessages() as $message) $errors[] = "Data Final: ".$message;
			}else {
				$dataFinal = new Zend_Date($dataFinal);
				$dataFinal = $dataFinal->get('y-MM-dd');
			}
				
			$validator = new Zend_Validate_Int();
			$cargaHoraria 	= $this->_request->getPost('cargaHoraria');
			if(!$validator->isValid($cargaHoraria))
				foreach ($validator->getMessages() as $message) $errors[] = "Carga Horária: ".$message;
			
			$validator = new Zend_Validate_NotEmpty();
			$horario 		= trim($this->_request->getPost('horario'));
			if(!$validator->isValid($horario))
				foreach ($validator->getMessages() as $message) $errors[] = "Horário: ".$message;
			
			$local 			= trim($this->_request->getPost('local'));
			if(!$validator->isValid($local))
				foreach ($validator->getMessages() as $message) $errors[] = "Local: ".$message;
			
			if(!$errors) {
				$data = array(
					'dataInicio'		=> $dataInicio,
					'dataFinal'			=> $dataFinal,
					'horario'			=> $horario,
					'cargaHoraria'		=> $cargaHoraria,
					'local'				=> $local,
					'idAreaTematica'	=> $idAreaTematica,
				);

				$curso->updateById($data, $id);

				$button = $this->_request->getPost('button');

				// Se clicou em próximo, segue para formulário de descricao
				if($button == 'Proximo') $this->_redirect('/curso/descricao/id/'.$id);
			}
			$this->view->errors = $errors;
			
			$this->view->curso->id				= $id;
			$this->view->curso->dataInicio		= $dataInicio;
			$this->view->curso->dataFinal		= $dataFinal;
			$this->view->curso->horario			= $horario;
			$this->view->curso->cargaHoraria	= $cargaHoraria;
			$this->view->curso->local			= $local;
			$this->view->curso->idAreaTematica	= $idAreaTematica;
			// Foi passado o id por 'GET'
		}

		// Dados para o combo de Area Temática
		$areaTematica = new AreaTematica();
		$this->view->areasTematicas = $areaTematica->fetchAll('id > 0','nome ASC');

		$this->render();
	}

	function equipeAction() {
		$this->view->title = 'Equipe';

		$curso = new Curso();
		
		$idCurso = (int) $this->_request->getParam('id', 0);
		$this->view->curso = $curso->find($idCurso)->current();

		if($this->_request->isPost()) {
			$idCurso = (int) $this->_request->getPost('id');
			$idViceCoordenador = (int) $this->_request->getPost('idViceCoordenador');
			$idViceCoordenador = $idViceCoordenador ? $idViceCoordenador : null;
			
			$validator = new Proexc_Validate_Alpha(true);
			$coordenadorArea = trim($this->_request->getPost('coordenadorArea'));
			if(!$validator->isValid($coordenadorArea)) {
				foreach ($validator->getMessages() as $message) $errors[] = $message;
			}

			$data = array(
				'idViceCoordenador'		=> $idViceCoordenador,
				'coordenadorArea'		=> $coordenadorArea
			);

			$curso->updateById($data, $idCurso);

			$button = $this->_request->getPost('button');

			// Se clicou em próximo, segue para formulário de parceiros
			if($button == 'Proximo') $this->_redirect('/curso/descricao/id/'.$idCurso);

			$this->view->curso->idViceCoordenador	= $idViceCoordenador;
			$this->view->curso->coordenadorArea		= $coordenadorArea;
			// Foi passado o id por 'GET'
		}

		// Preenche combo de possíveis vice coordenadores, ou seja, todos coordenadores
		// menos o coordenador logado.
		$coordenador = new Coordenador();
		$this->view->viceCoordenadores = $coordenador->fetchViceCoordenadores($this->user->id);

		// Preenche o campo colaboradores docentes
		$tabColaboradorDocente = new ColaboradorDocente();
		$this->view->colaboradoresDocentes = $tabColaboradorDocente->fetchColaboradoresDocentesByCurso($idCurso);

		$this->render();
	}

	function addColaboradorDocenteAction() {
		// Inicia
		$this->view->title = 'Professore Ministrante';
		$this->view->action = $this->view->baseUrl . "/curso/addColaboradorDocente";
		$this->formColaboradorDocente();
	}

	function editColaboradorDocenteAction() {
		$this->view->title = 'Colaborador Docente';
		$this->view->action = $this->view->baseUrl . "/curso/editColaboradorDocente";
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
			$idCurso = (int)$this->_request->getPost('id');
			$del = $this->_request->getPost('del');

			// Se clicou em 'Yes' e existe o Colaborador Docente
			if ($del == 'Yes' && $idColaboradorDocente > 0) {
				$db = $colaboradorDocente->getAdapter();
				$db->beginTransaction();
					
				try {
					$where[] = $db->quoteInto('idCurso = ?', $idCurso);
					$where[] = $db->quoteInto('idColaboradorDocente = ?', $idColaboradorDocente);
					$cursoColaboradorDocente = new CursoColaboradorDocente();
					$cursoColaboradorDocente->delete($where);

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
			$idCurso = (int)$this->_request->getParam('id');
			$idColaboradorDocente = (int)$this->_request->getParam('idColaboradorDocente');

			// Testa o id
			if ($idColaboradorDocente > 0) {
				// somente mostra se achou o tecnico
				$this->view->colaboradorDocente = $colaboradorDocente->fetchRow('id='.$idColaboradorDocente);
				$this->view->idCurso = $idCurso;

				if ($this->view->colaboradorDocente->id > 0) {
					$this->render();
					return;
				}
			}
		}
		// volta se não renderizou (se o colaborador não existe)
		$this->_redirect("curso/equipe/id/".$idCurso);
	}

	private function formColaboradorDocente($tipo = 'add') {
		$colaboradorDocente = new ColaboradorDocente();

		$this->view->colaboradorDocente = new stdClass();

		if($this->_request->isPost()) {
			$errors = null;

			$idCurso = (int) $this->_request->getPost('id');
			$idColaboradorDocente = (int) $this->_request->getPost('idColaboradorDocente');

			$validator = new Proexc_Validate_Alpha(true);
			$nome = trim($this->_request->getPost('nome'));
			if(!$validator->isValid($nome))
			foreach ($validator->getMessages() as $message) $errors[] = "Nome: ".$message;

			
			/**
			  Validação Retirada -> o Docente não precisa ser necessariamente da UFJF.
			*/
//			$validator = new Proexc_Validate_Siape();
     		$siape = trim($this->_request->getPost('siape'));
//			if(!$validator->isValid($siape))
//			foreach ($validator->getMessages() as $message) $errors[] = $message;
			
			
			$idDepartamento = (int) $this->_request->getPost('idDepartamento');

			$validator = new Zend_Validate_EmailAddress();
			$email = trim($this->_request->getPost('email'));
			if(!$validator->isValid($email))
			foreach ($validator->getMessages() as $message) $errors[] = "E-mail: ".$message;

			$validator = new Zend_Validate_Regex("/^\\(\\d{2}\\)\\d{4}-\\d{4}\$/");
			$telefone = trim($this->_request->getPost('telefone'));
			if(!$validator->isValid($telefone))
			foreach ($validator->getMessages() as $message) $errors[] = "Telefone: ".$message;

			$celular = trim($this->_request->getPost('celular'));
			if($celular && !$validator->isValid($celular))
			foreach ($validator->getMessages() as $message) $errors[] = $message;
			
			$validator = new Zend_Validate_Int();
			$cargaHorariaSemanal = trim($this->_request->getPost('cargaHorariaSemanal'));
			if(!$validator->isValid($cargaHorariaSemanal))
				foreach ($validator->getMessages() as $message) $errors[] = "Carga Horária: ".$message;
			
			$curriculo = $this->_request->getPost('curriculo');
				
			if(!$errors) {
				$db = $colaboradorDocente->getDefaultAdapter();
				$db->beginTransaction();

				try {
					// Cria o novo tecnico
					$data = array(
						'nome'					=> $nome,
						'siape'					=> 0000001, // !! alterado 
						'idDepartamento'		=> 01, // !! alterado
						'email'					=> $email,
						'telefone'				=> $telefone,
						'celular'				=> $celular,
						'cargaHorariaSemanal'	=> $cargaHorariaSemanal,
						'curriculo'             => $curriculo
					);

					// Se for inserção
					if($tipo == "add") {
						$idColaboradorDocente = $colaboradorDocente->insert($data);

						// Insere um novo registro na tabela curso_tecnico
						$cursoColaboradorDocente = new CursoColaboradorDocente();
						$data = array(
							'idCurso'				=> $idCurso,
							'idColaboradorDocente'	=> $idColaboradorDocente
						);

						$cursoColaboradorDocente->insert($data);

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

				$this->_redirect('/curso/equipe/id/'.$idCurso);
			}
			$this->view->errors = $errors;

			// Inicializa valores referentes ao colaborador Docente
			$this->view->colaboradorDocente->nome 					= $nome;
			/**/
			$this->view->colaboradorDocente->siape 					= $siape;
			$this->view->colaboradorDocente->idDepartamento			= $idDepartamento;
			
			$this->view->colaboradorDocente->email 					= $email;
			$this->view->colaboradorDocente->telefone 				= $telefone;
			$this->view->colaboradorDocente->celular 				= $celular;
			$this->view->colaboradorDocente->cargaHorariaSemanal	= $cargaHorariaSemanal;
			$this->view->colaboradorDocente->curriculo	            = $curriculo;
			// Foi passado o id por 'GET'
		} else {
			$idCurso = (int) $this->_request->getParam('id', 0);
			$idColaboradorDocente = (int) $this->_request->getParam('idColaboradorDocente', 0);

			// Inicializa valores referentes ao colaborador Docente
			$this->view->colaboradorDocente->nome = "";
			/***/
			$this->view->colaboradorDocente->siape = "";
			$this->view->colaboradorDocente->idDepartamento = "";
			
			$this->view->colaboradorDocente->email = "";
			$this->view->colaboradorDocente->telefone = "";
			$this->view->colaboradorDocente->celular = "";
			$this->view->colaboradorDocente->cargaHorariaSemanal = "";
			$this->view->colaboradorDocente->curriculo = "";
			if($idColaboradorDocente > 0) $this->view->colaboradorDocente = $colaboradorDocente->find($idColaboradorDocente)->current();
		}
		$this->view->idCurso = $idCurso;

		// Preenche combo de possíveis departamentos
		$departamento = new Departamento();
		$this->view->departamentos = $departamento->fetchAll();

		$this->render();
	}

	function descricaoAction () {
		// Seta o título
		$this->view->title = "Descrição do curso";

		$curso = new Curso();

		if($this->_request->isPost()) {
			$idCurso = (int) $this->_request->getPost('id');
			
			$publicoAlvo 		= $this->_request->getPost('publicoAlvo');
			$expectativaPublico = $this->_request->getPost('expectativaPublico');
			$descricao			= $this->_request->getPost('descricao');
			$conteudo			= $this->_request->getPost('descricao');
			$resumo				= $this->_request->getPost('resumo');
			$email				= trim($this->_request->getPost('email'));
			$telefone			= trim($this->_request->getPost('telefone'));
			$local				= trim($this->_request->getPost('local'));
			
			$errors = null;
						
			$validator = new Zend_Validate_Int();
			if(!$validator->isValid($expectativaPublico))
				foreach ($validator->getMessages() as $message) $errors[] = "Expectativa de Público: ".$message;

			$validator = new Zend_Validate_NotEmpty();
			if(($validator->isValid($local))|| ($validator->isValid($email)) || ($validator->isValid($telefone)) ) {}
			else {
				foreach ($validator->getMessages() as $message) $errors[] = $message;
			}
				
				
			if(!$errors) {
				$data = array(
					'publicoAlvo'			=> $publicoAlvo,
					'expectativaPublico'	=> $expectativaPublico,
					'descricao'				=> $descricao,
					'conteudo'				=> $conteudo,				
					'resumo'				=> $resumo,
					'localInformacoes'      => $local,
					'emailInformacoes'      => $email,
					'telInformacoes'        => $telefone 
				);

				$curso->updateById($data, $idCurso);

				$button = $this->_request->getPost('button');

				// Se clicou em próximo, segue para formulário de recursos
				if($button == 'Proximo') $this->_redirect('/curso/recursos/id/'.$idCurso);
			}
			$this->view->errors = $errors;
			// Foi passado o id por 'GET'
		} else {
			$idCurso = (int) $this->_request->getParam('id', 0);
		}

		if($idCurso > 0) $this->view->curso = $curso->find($idCurso)->current();

		$this->render();
	}

	function recursosAction () {
		// Seta o título
		$this->view->title = "Recursos";

		// Referencia à tabela recursos
		$tabRecursos = new Recursos();

		// Define uma instância do curso atual
		$idCurso = (int) $this->_request->getParam('id', 0);
		$tabCurso = new Curso();
		$curso = $tabCurso->find($idCurso)->current();

		if($this->_request->isPost()) {
			$possuiRecursos = (boolean) $this->_request->getPost('possuiRecursos');

			if($possuiRecursos) {
				$gestora = $this->_request->getPost('gestora');
				$ano = $this->_request->getPost('ano');
				$recursosExternosFonte = $this->_request->getPost('recursosExternosFonte');
				$recursosExternosValor = $this->_request->getPost('recursosExternosValor');
				$diariaExterno = $this->_request->getPost('diariaExterno');
				$passagemExterno = $this->_request->getPost('passagemExterno');
				$alimentacaoExterno = $this->_request->getPost('alimentacaoExterno');
				$bolsaDiscente = $this->_request->getPost('bolsaDiscente');
				$pagamentoCoordenador = $this->_request->getPost('pagamentoCoordenador');
				$pagamentoEquipe = $this->_request->getPost('pagamentoEquipe');
				$pagamentoPJExterno = $this->_request->getPost('pagamentoPJExterno');
				$pagamentoPFExterno = $this->_request->getPost('pagamentoPFExterno');
				$equipamentos = $this->_request->getPost('equipamentos');
				$material = $this->_request->getPost('material');

				$data = array(
					'gestora' 				=> $gestora,
					'ano' 					=> $ano,
					'recursosExternosFonte'	=> $recursosExternosFonte,
					'recursosExternosValor'	=> $recursosExternosValor,
					'diariaExterno'			=> $diariaExterno,
					'passagemExterno'		=> $passagemExterno,
					'alimentacaoExterno'	=> $alimentacaoExterno,
					'bolsaDiscente'			=> $bolsaDiscente,
					'pagamentoCoordenador'	=> $pagamentoCoordenador,
					'pagamentoEquipe'		=> $pagamentoEquipe,
					'pagamentoPJExterno'	=> $pagamentoPJExterno,
					'pagamentoPFExterno' 	=> $pagamentoPFExterno,
					'equipamentos' 			=> $equipamentos,
					'material'				=> $material
				);
					
				// Se existe um id de recurso já ligado a tabela atualiza na tabela de recurso
				if($curso->idRecursos) $tabRecursos->updateById($data, $curso->idRecursos);
				// Senão cria registro na tabela recursos e atualiza o id criado na tabela curso
				else {
					$db = $tabCurso->getAdapter();
					$db->beginTransaction();
					try {
						$curso->idRecursos = $tabRecursos->insert($data);
						$curso->save();
						$db->commit();
					} catch (Exception $e) {
						$db->rollBack();
						$this->view->error = $e->getMessage();
					}
				}

				$button = $this->_request->getPost('button');
			// Se usuário clicoe em não possui recursos
			} else 
				if($curso->idRecursos) {
					$db = $tabRecursos->getAdapter();
					$db->beginTransaction();
					try {
						$idRecursos = $curso->idRecursos;
						$curso->idRecursos = null;
						$curso->save();
						$tabRecursos->deleteById($idRecursos);
						$db->commit();
					} catch (Exception $e) {
						$db->rollBack();
						$this->view->error = $e->getMessage();
					}
				}
			$this->view->salvo = 1;
		}
		// Se existe o curso
		if($idCurso > 0) $this->view->recursos = $curso->findParentRecursos();

		if(!$this->view->recursos) {
			$this->view->recursos = new stdClass();
			$this->view->recursos->id = null;
			$this->view->recursos->gestora = null;
			$this->view->recursos->ano = null;
			$this->view->recursos->recursosExternosFonte = null;
		}

		// Preenche o campo idCurso
		$this->view->curso = $curso;


		$this->render();
	}

	function imprimirFormularioAction() {
		$this->_helper->viewRenderer->setNoRender(true);

		$id = (int) $this->_request->getParam('id', 0);
		if($id > 0) {
			$tabCurso = new Curso();
			$curso = $tabCurso->fetchRow('id = ' . $id);

			$formulario = new FormularioCurso($curso);
			$formulario->Output('formulario.pdf', 'D');
		}

	}

	function delAction() {
		// Título da página
		$this->view->title = "Apagar Curso";

		// Cria um objeto referente à tabela Curso
		$curso = new Curso();

		// Se a requisição for um método post
		if ($this->_request->isPost()) {
			// Pega os dados
			$idCurso = (int)$this->_request->getPost('id');
			$idRecursos = (int)$this->_request->getPost('idRecursos');
			$del = $this->_request->getPost('del');

			// Se clicou em 'Yes' e existe o Curso
			if ($del == 'Yes' && $idCurso > 0) {
				$db = $curso->getAdapter();
				$db->beginTransaction();
					
				try {
					$where = $db->quoteInto('id = ?', $idCurso);
					$curso->delete($where);

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
			$idCurso = (int)$this->_request->getParam('id');

			// Testa o id
			if ($idCurso > 0) {
				// somente mostra se achou o curso
				$this->view->curso = $curso->fetchRow('id='.$idCurso);

				if ($this->view->curso->id > 0) {
					$this->render();
					return;
				}
			}
		}
		// volta se não renderizou (se o curso não existe)
		$this->_redirect("index/listCursos");
	}

	function fecharAction() {
		// Título da página
		$this->view->title = "Concluir Curso";

		// Cria um objeto referente à tabela Curso
		$tabCurso = new Curso();
		
		// Pega os dados
		$idCurso = (int)$this->_request->getParam('id', 0);
		$curso = $tabCurso->find($idCurso)->current();
		
		// Se a requisição for um método post
		if ($this->_request->isPost()) {
			// Pega os dados
			$idCurso = (int)$this->_request->getPost('id');
			$fecha = $this->_request->getPost('fecha');

			// Se clicou em 'Yes' e existe o Projeto
			if ($fecha == 'Sim' && $idCurso > 0) {
//				$data = array(
//					"fechado"	=> 1
//				);
//				
//				$projeto->updateById($data, $idProjeto);
				$curso->fechado = 1;
				$curso->save();
						
			}
		// A transação é do tipo 'get'
		} else {
			if ($curso) {
				$this->view->curso = $curso;
				$this->render();
				return;
			}
		}
		// volta se não renderizou (se o projeto não existe)
		$this->_redirect("Index/listCursos");
	}
	
}