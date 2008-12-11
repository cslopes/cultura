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

require_once 'Evento.php';
require_once 'AreaTematica.php';
require_once 'Coordenador.php';
require_once 'Departamento.php';
require_once 'Recursos.php';
require_once 'FormularioEvento.php';

class EventoController extends Proexc_Controller_Action {

	/**
	 * Verifica se o Coordenador logado tem acesso ao evento
	 */
	function preDispatch() {
		parent::preDispatch();

		// Verifica se o coordenador tem acesso a ações para eventos fechados e não-validados
		if($this->_request->getActionName() == 'imprimirFormulario') {
			$tabEvento = new Evento();
			$eventos = $tabEvento->fetchClosedByCoordenador($this->user->id);
			$ok = 0;
			foreach ($eventos as $evento) {
				if($evento->id == $this->_request->getParam('id')){
					$ok = 1;
					break;
				}
			}
			if(!$ok) $this->_redirect('/');
		}
		
		// Verifica se o coordenador tem acesso a ações para eventos abertos e validados
		else if($this->_request->getActionName() == 'relatorioFinal') {
			$tabEvento = new Evento();
			$eventos = $tabEvento->fetchValidatedByCoordenador($this->user->id);
			$ok = 0;
			foreach ($eventos as $evento) {
				if($evento->id == $this->_request->getParam('id')){
					$ok = 1;
					break;
				}
			}
			if(!$ok) $this->_redirect('/');
		}
		
		// Verifica se o coordenador tem acesso a ações para eventos não-validados
		else if($this->_request->getActionName() != 'add') {
			$tabEvento = new Evento();
			$eventos = $tabEvento->fetchOpenAndUnvalidatedByCoordenador($this->user->id);
			$ok = 0;
			foreach ($eventos as $evento) {
				if($evento->id == $this->_request->getParam('id')){
					$ok = 1;
					break;
				}
			}
			if(!$ok) $this->_redirect('/');
		}
	}

		/**
	 * Controller para renomear o curso
	 */
	function renameAction() {
		$this->view->title = "Renomear Evento";

		$evento = new Evento();

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

				$pk = $evento->updateById($data, $id);

				$this->_redirect('index/listEventos');
			}
			$this->view->errors = $errors;
		} else {
			$id = (int) $this->_request->getParam('id', 0);
			if($id > 0) $this->view->evento = $evento->find($id)->current();
		}

		$this->render();
	}

	/**
	 * Controller para criação de um novo evento. Requisita apenas o nome do evento e cria
	 * inserindo também o id do coordenador. Logo após criado o usuário é enviado para a tela
	 * de edição do evento.
	 */
	function addAction() {
		$this->view->title = "Cadastrar Novo Evento";

		$evento = new Evento();

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

				$pk = $evento->insert($data);

				$this->_redirect('evento/geral/id/'.$pk);
			}
			$this->view->errors = $errors;
		}

		$this->view->evento = $evento;

		$this->render();
	}

	/**
	 * Formulário de evento para preenchimento dos dados gerais.
	 */
	function geralAction() {
		// Seta o título
		$this->view->title = "Geral";

		$evento = new Evento();

		if($this->_request->isPost()) {
			$errors = null;

			$id = (int) $this->_request->getPost('id');
			$idAreaTematica = (int) $this->_request->getPost('idAreaTematica');

			$validator = new Zend_Validate_Date();
			$dataInicio = $this->_request->getPost('dataInicio');
			if(!$validator->isValid($dataInicio)) {
				foreach ($validator->getMessages() as $message) $errors[] = $message;
			}else {
				$dataInicio = new Zend_Date($dataInicio);
				$dataInicio = $dataInicio->get('y-MM-dd');
			}

			$dataFinal = $this->_request->getPost('dataFinal');
			if(!$validator->isValid($dataFinal)) {
				foreach ($validator->getMessages() as $message) $errors[] = $message;
			}else {
				$dataFinal = new Zend_Date($dataFinal);
				$dataFinal = $dataFinal->get('y-MM-dd');
			}
				
			$validator = new Zend_Validate_Int();
			$cargaHoraria 	= $this->_request->getPost('cargaHoraria');
			if(!$validator->isValid($cargaHoraria))
				foreach ($validator->getMessages() as $message) $errors[] = $message;
			
			$validator = new Zend_Validate_Alpha(true);
			$especie = $this->_request->getPost('especie');
			if(!$validator->isValid($especie))
				foreach ($validator->getMessages() as $message) $errors[] = $message;
				
			$carater = $this->_request->getPost('carater');
			if(!$validator->isValid($carater))
				foreach ($validator->getMessages() as $message) $errors[] = $message;
				
			$validator = new Zend_Validate_NotEmpty();
			$horario = trim($this->_request->getPost('horario'));
			if(!$validator->isValid($horario))
				foreach ($validator->getMessages() as $message) $errors[] = $message;
			
			$local = trim($this->_request->getPost('local'));
			if(!$validator->isValid($local))
				foreach ($validator->getMessages() as $message) $errors[] = $message;
			
			if(!$errors) {
				$data = array(
					'especie'			=> $especie,
					'carater'			=> $carater,
					'dataInicio'		=> $dataInicio,
					'dataFinal'			=> $dataFinal,
					'horario'			=> $horario,
					'cargaHoraria'		=> $cargaHoraria,
					'local'				=> $local,
					'idAreaTematica'	=> $idAreaTematica
				);

				$evento->updateById($data, $id);

				$button = $this->_request->getPost('button');

				// Se clicou em próximo, segue para formulário de descricao
				if($button == 'Proximo') $this->_redirect('/evento/descricao/id/'.$id);
			}
			$this->view->errors = $errors;
			
			if($id > 0) $this->view->evento = $evento->find($id)->current();
			$this->view->evento->id				= $id;
			$this->view->evento->especie		= $especie;
			$this->view->evento->carater		= $carater;
			$this->view->evento->dataInicio		= $dataInicio;
			$this->view->evento->dataFinal		= $dataFinal;
			$this->view->evento->horario		= $horario;
			$this->view->evento->cargaHoraria	= $cargaHoraria;
			$this->view->evento->local			= $local;
			$this->view->evento->idAreaTematica	= $idAreaTematica;
			// Foi passado o id por 'GET'
		} else {
			$id = (int) $this->_request->getParam('id', 0);
			
			$this->view->evento 				= new stdClass();
			$this->view->evento->id				= "";
			$this->view->evento->especie		= "";
			$this->view->evento->carater		= "";
			$this->view->evento->dataInicio		= "";
			$this->view->evento->dataFinal		= "";
			$this->view->evento->horario		= "";
			$this->view->evento->cargaHoraria	= "";
			$this->view->evento->local			= "";
			$this->view->evento->idAreaTematica	= "";
			if($id > 0) $this->view->evento = $evento->find($id)->current();
		}

		// Dados para o combo de Area Temática
		$areaTematica = new AreaTematica();
		$this->view->areasTematicas = $areaTematica->fetchAll('id > 0','nome ASC');

		$this->render();
	}

	function descricaoAction () {
		// Seta o título
		$this->view->title = "Descrição do evento";

		$evento = new Evento();
		
		$idEvento = (int) $this->_request->getParam('id', 0);
		$this->view->evento = $evento->find($idEvento)->current();

		if($this->_request->isPost()) {
			$idEvento = (int) $this->_request->getPost('id');
			
			$publicoAlvo 						= $this->_request->getPost('publicoAlvo');
			$expectativaPublico 				= (int) $this->_request->getPost('expectativaPublico');
			$objetivos							= $this->_request->getPost('objetivos');
			$resumo								= $this->_request->getPost('resumo');
			$docentesEnvolvidos 				= (int) $this->_request->getPost('docentesEnvolvidos');
			$bolsistasGraduacaoEnvolvidos 		= (int) $this->_request->getPost('bolsistasGraduacaoEnvolvidos');
			$bolsistasPosGraduacaoEnvolvidos	= (int) $this->_request->getPost('bolsistasPosGraduacaoEnvolvidos');
			$voluntariosEnvolvidos 				= (int) $this->_request->getPost('voluntariosEnvolvidos');
			$tecnicosEnvolvidos 				= (int) $this->_request->getPost('tecnicosEnvolvidos');
			$comunidadeEnvolvida				= (int) $this->_request->getPost('comunidadeEnvolvida');
			
			$errors = null;

			$validator = new Zend_Validate_Int();
			if(!$validator->isValid($expectativaPublico))
				foreach ($validator->getMessages() as $message) $errors[] = $message;
			if(!$validator->isValid($docentesEnvolvidos))
				foreach ($validator->getMessages() as $message) $errors[] = $message;
			if(!$validator->isValid($bolsistasGraduacaoEnvolvidos))
				foreach ($validator->getMessages() as $message) $errors[] = $message;
			if(!$validator->isValid($bolsistasPosGraduacaoEnvolvidos))
				foreach ($validator->getMessages() as $message) $errors[] = $message;
			if(!$validator->isValid($voluntariosEnvolvidos))
				foreach ($validator->getMessages() as $message) $errors[] = $message;
			if(!$validator->isValid($tecnicosEnvolvidos))
				foreach ($validator->getMessages() as $message) $errors[] = $message;
			if(!$validator->isValid($comunidadeEnvolvida))
				foreach ($validator->getMessages() as $message) $errors[] = $message;
				
			if(!$errors) {
				$data = array(
					'publicoAlvo'						=> $publicoAlvo,
					'expectativaPublico'				=> $expectativaPublico,
					'objetivos'							=> $objetivos,
					'resumo'							=> $resumo,
					'docentesEnvolvidos'				=> $docentesEnvolvidos,
					'bolsistasGraduacaoEnvolvidos'		=> $bolsistasGraduacaoEnvolvidos,
					'bolsistasPosGraduacaoEnvolvidos'	=> $bolsistasPosGraduacaoEnvolvidos,
					'voluntariosEnvolvidos'				=> $voluntariosEnvolvidos,
					'tecnicosEnvolvidos'				=> $tecnicosEnvolvidos,
					'comunidadeEnvolvida'				=> $comunidadeEnvolvida
				);

				$evento->updateById($data, $idEvento);

				$button = $this->_request->getPost('button');

				// Se clicou em próximo, segue para formulário de recursos
				if($button == 'Proximo') $this->_redirect('/evento/recursos/id/'.$idEvento);
			}
			$this->view->errors = $errors;

			$this->view->evento->id									= $idEvento;
			$this->view->evento->publicoAlvo						= $publicoAlvo;
			$this->view->evento->expectativaPublico					= $expectativaPublico;
			$this->view->evento->objetivos							= $objetivos;
			$this->view->evento->resumo								= $resumo;
			$this->view->evento->docentesEnvolvidos					= $docentesEnvolvidos;
			$this->view->evento->bolsistasGraduacaoEnvolvidos		= $bolsistasGraduacaoEnvolvidos;
			$this->view->evento->bolsistasPosGraduacaoEnvolvidos	= $bolsistasPosGraduacaoEnvolvidos;
			$this->view->evento->voluntariosEnvolvidos				= $voluntariosEnvolvidos;
			$this->view->evento->tecnicosEnvolvidos					= $tecnicosEnvolvidos;
			$this->view->evento->comunidadeEnvolvida				= $comunidadeEnvolvida;
			// Foi passado o id por 'GET'
		}

		$this->render();
	}

	function recursosAction () {
		// Seta o título
		$this->view->title = "Recursos";

		// Referencia à tabela recursos
		$tabRecursos = new Recursos();

		// Define uma instância do evento atual
		$idEvento = (int) $this->_request->getParam('id', 0);
		$tabEvento = new Evento();
		$evento = $tabEvento->find($idEvento)->current();

		if($this->_request->isPost()) {
			$possuiRecursos = (boolean) $this->_request->getPost('possuiRecursos');

			if($possuiRecursos) {
				$gestora 				= $this->_request->getPost('gestora');
				$ano 					= $this->_request->getPost('ano');
				$recursosExternosFonte	= $this->_request->getPost('recursosExternosFonte');
				$recursosExternosValor 	= $this->_request->getPost('recursosExternosValor');
				$diariaExterno 			= $this->_request->getPost('diariaExterno');
				$passagemExterno 		= $this->_request->getPost('passagemExterno');
				$alimentacaoExterno 	= $this->_request->getPost('alimentacaoExterno');
				$bolsaDiscente 			= $this->_request->getPost('bolsaDiscente');
				$pagamentoCoordenador 	= $this->_request->getPost('pagamentoCoordenador');
				$pagamentoEquipe 		= $this->_request->getPost('pagamentoEquipe');
				$pagamentoPJExterno 	= $this->_request->getPost('pagamentoPJExterno');
				$pagamentoPFExterno 	= $this->_request->getPost('pagamentoPFExterno');
				$equipamentos 			= $this->_request->getPost('equipamentos');
				$material 				= $this->_request->getPost('material');

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
				if($evento->idRecursos) $tabRecursos->updateById($data, $evento->idRecursos);
				// Senão cria registro na tabela recursos e atualiza o id criado na tabela evento
				else {
					$db = $tabEvento->getAdapter();
					$db->beginTransaction();
					try {
						$evento->idRecursos = $tabRecursos->insert($data);
						$evento->save();
						$db->commit();
					} catch (Exception $e) {
						$db->rollBack();
						$this->view->error = $e->getMessage();
					}
				}

				$button = $this->_request->getPost('button');
			// Se usuário clicoe em não possui recursos
			} else 
				if($evento->idRecursos) {
					$db = $tabRecursos->getAdapter();
					$db->beginTransaction();
					try {
						$idRecursos = $evento->idRecursos;
						$evento->idRecursos = null;
						$evento->save();
						$tabRecursos->deleteById($idRecursos);
						$db->commit();
					} catch (Exception $e) {
						$db->rollBack();
						$this->view->error = $e->getMessage();
					}
				}
			$this->view->salvo = 1;
		}
		// Se existe o evento
		if($idEvento > 0) $this->view->recursos = $evento->findParentRecursos();

		if(!$this->view->recursos) {
			$this->view->recursos = new stdClass();
			$this->view->recursos->id = null;
			$this->view->recursos->gestora = null;
			$this->view->recursos->ano = null;
			$this->view->recursos->recursosExternosFonte = null;
		}

		// Preenche o campo idEvento
		$this->view->evento = $evento;


		$this->render();
	}

	function imprimirFormularioAction() {
		$this->_helper->viewRenderer->setNoRender(true);

		$id = (int) $this->_request->getParam('id', 0);
		if($id > 0) {
			$tabEvento = new Evento();
			$evento = $tabEvento->fetchRow('id = ' . $id);

			$formulario = new FormularioEvento($evento);
			$formulario->Output('formulario.pdf', 'D');
		}
	}

	function delAction() {
		// Título da página
		$this->view->title = "Apagar Evento";

		// Cria um objeto referente à tabela Evento
		$evento = new Evento();

		// Se a requisição for um método post
		if ($this->_request->isPost()) {
			// Pega os dados
			$idEvento = (int)$this->_request->getPost('id');
			$idRecursos = (int)$this->_request->getPost('idRecursos');
			$del = $this->_request->getPost('del');

			// Se clicou em 'Yes' e existe o Evento
			if ($del == 'Yes' && $idEvento > 0) {
				$db = $evento->getAdapter();
				$db->beginTransaction();
					
				try {
					$where = $db->quoteInto('id = ?', $idEvento);
					$evento->delete($where);

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
			$idEvento = (int)$this->_request->getParam('id');

			// Testa o id
			if ($idEvento > 0) {
				// somente mostra se achou o evento
				$this->view->evento = $evento->fetchRow('id='.$idEvento);

				if ($this->view->evento->id > 0) {
					$this->render();
					return;
				}
			}
		}
		// volta se não renderizou (se o evento não existe)
		$this->_redirect("index/listEventos");
	}

	function fecharAction() {
		// Título da página
		$this->view->title = "Concluir Evento";

		// Cria um objeto referente à tabela Evento
		$evento = new Evento();

		// Se a requisição for um método post
		if ($this->_request->isPost()) {
			// Pega os dados
			$idEvento = (int)$this->_request->getPost('id');
			$fecha = $this->_request->getPost('fecha');

			// Se clicou em 'Yes' e existe o Evento
			if ($fecha == 'Sim' && $idEvento > 0) {
				$data = array(
					"fechado"	=> 1
				);
				
				$evento->updateById($data, $idEvento);
			}
			// A transação é do tipo 'get'
		} else {
			// Pega os dados passados na url
			$idEvento = (int)$this->_request->getParam('id');

			// Testa o id
			if ($idEvento > 0) {
				// somente mostra se achou o evento
				$this->view->evento = $evento->fetchRow('id='.$idEvento);

				if ($this->view->evento->id > 0) {
					$this->render();
					return;
				}
			}
		}
		// volta se não renderizou (se o evento não existe)
		$this->_redirect("Index/listEventos");
	}
	
}