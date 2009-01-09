<?php

require_once 'public/tcpdf/tcpdf.php';

require_once 'Formulario.php';
require_once 'Projeto.php';
require_once 'Coordenador.php';
require_once 'Tecnico.php';
require_once 'ColaboradorDocente.php';
require_once 'ColaboradorExterno.php';
require_once 'LinhaAtuacao.php';
require_once 'Parceiro.php';

class FormularioProjeto extends Formulario {
	/**
	 * Armazena o projeto
	 *
	 * @var Projeto
	 */
	private $projeto;
	
	const TITULO = 'FORMULÁRIO DE INSCRIÇÃO DE PROJETOS DE EXTENSÃO';
	
	/**
	 * Class constructor
	 *
	 * @param Projeto $projeto Projeto a ser impresso no arquivo
	 * @param String $orientation 'P' (Portrait) or 'L' (Landscape)
	 * @param String $unit The default is 'mm'
	 * @param String $format Format of the page, default is 'A4'
	 */
	function __construct($projeto, $orientation = 'P', $unit = 'mm', $format = 'A4') {
		$this->projeto = $projeto;
		
		parent::__construct($projeto->idCoordenador, $this->projeto->titulo, $orientation, $unit, $format);
	}

	/**
	 * Writes the Identificacao block
	 *
	 */
	private function writeIdentificacao() {
		$this->SetFont('vera', 'B', 11);
		$position = $this->increasePosition();
		$this->Cell(self::BLOCK_SIZE, 5, $position . '. IDENTIFICAÇÃO', 'LTR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LR', 1);
		$this->Cell(self::BLOCK_SIZE, 5, 'TÍTULO: ' . $this->projeto->titulo, 'LR', 1);
		$this->Cell(self::BLOCK_SIZE, 5, 'NÚMERO DO PROCESSO: ' . $this->projeto->processo, 'LBR', 1);
		$this->SetY($this->GetY() + 4);
	}

	/**
	 * Writes the Linha de Extensao block
	 *
	 */
	private function writeLinhaExtensao() {
		if(!$this->projeto->idLinhaAtuacao) throw new Exception('Não foi definida uma linha de extensão');
		
		$tabLinhaAtuacao = new LinhaAtuacao();
		$linhaAtuacao = $tabLinhaAtuacao->fetchRow('id='.$this->projeto->idLinhaAtuacao);

		$this->SetFont('vera', 'B', 11);
		$position = $this->increasePosition();
		$this->Cell(self::BLOCK_SIZE, 5, $position . '. LINHA DE EXTENSÃO', 'LTR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LR', 1);
		$this->SetFont('vera', '', 9);
		$this->Cell(self::BLOCK_SIZE, 4, $linhaAtuacao->nome, 'LR', 1);
		$this->Cell(self::BLOCK_SIZE, 2, '', 'LBR', 1);
		$this->SetY($this->GetY() + 4);
	}

	/**
	 * Writes the Equipe's block
	 *
	 */
	private function writeEquipe() {
		$viceCoordenador = null;

		if($this->projeto->idViceCoordenador > 0) {
			$tabCoordenador = new Coordenador();
			$viceCoordenador = $tabCoordenador->fetchRow('id='.$this->projeto->idViceCoordenador);
		}

		$tabTecnico = new Tecnico();
		$coordenadoresTecnicos = $tabTecnico->fetchCoordenadoresTecnicosByProjeto($this->projeto->id);
		$colaboradoresTecnicos = $tabTecnico->fetchColaboradoresTecnicosByProjeto($this->projeto->id);

		$tabColaboradorDocente = new ColaboradorDocente();
		$colaboradoresDocentes = $tabColaboradorDocente->fetchColaboradoresDocentesByProjeto($this->projeto->id);

		$tabColaboradorExterno = new ColaboradorExterno();
		$colaboradoresExternos = $tabColaboradorExterno->fetchColaboradoresExternosByProjeto($this->projeto->id);

		$header = array('Nome', 'Função');
		$width = array(120, self::INNER_BLOCK_SIZE - 120);
		$data = null;

		if($viceCoordenador)
			$data = array(array($viceCoordenador->nome, 'Vice Coordenador'));
		foreach ($coordenadoresTecnicos as $coordenadorTecnico)
			$data[] = array($coordenadorTecnico->nome, 'Coordenador Técnico');
		foreach ($colaboradoresDocentes as $colaboradorDocente)
			$data[] = array($colaboradorDocente->nome, 'Colaborador Docente');
		foreach ($colaboradoresTecnicos as $colaboradorTecnico)
			$data[] = array($colaboradorTecnico->nome, 'Colaborador Técnico');
		foreach ($colaboradoresExternos as $colaboradorExterno)
			$data[] = array($colaboradorExterno->nome, 'Colaborador Externo');

		if (!$data) return;
			
		$this->SetFont('vera', 'B', 11);
		$position =  $this->increasePosition();
		$this->Cell(self::BLOCK_SIZE, 5, $position . '. EQUIPE', 'LTR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LR', 1);

		$this->FancyTable($header, $data, $width);

		$this->Cell(self::BLOCK_SIZE, 4, '', 'LBR', 1);
		$this->SetY($this->GetY() + 4);
	}

	private function writePartners() {
		$tabParceiros = new Parceiro();
		$parceiros = $tabParceiros->fetchParceirosByProjeto($this->projeto->id);

		$header = array('Nome', 'Responsável', 'Telefone');
		$width = array(80, self::INNER_BLOCK_SIZE - 120, 40);
		$data = null;

		foreach ($parceiros as $parceiro)
		$data[] = array($parceiro->nomeInstituicao, $parceiro->nomeResponsavel, $parceiro->telefoneContato);

		if($data) {
			$this->SetFont('vera', 'B', 11);
			$position = $this->increasePosition();
			$this->Cell(self::BLOCK_SIZE, 5, $position . '. PARCEIROS EXTERNOS', 'LTR', 1);
			$this->Cell(self::BLOCK_SIZE, 4, '', 'LR', 1);

			$this->FancyTable($header, $data, $width);

			$this->Cell(self::BLOCK_SIZE, 4, '', 'LBR', 1);
			$this->SetY($this->GetY() + 4);
		}
	}

	/**
	 * Writes the Descricao block.
	 *
	 */
	private function writeDescricao() {
		$this->SetFont('vera', 'B', 11);
		$position = $this->increasePosition();
		$this->Cell(self::BLOCK_SIZE, 5, $position . '. DESCRIÇÃO', 'LTR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LR', 1);
		$this->writeInnerBlock('FUNDAMENTAÇÃO TEÓRICA', $this->projeto->fundamento);
		$this->writeInnerBlock('OBJETIVOS', $this->projeto->objetivos);
		$this->writeInnerBlock('METODOLOGIA', $this->projeto->metodologia);
		$this->writeInnerBlock('PÚBLICO ALVO', $this->projeto->publicoAlvo);
		$this->writeInnerBlock('RESUMO', $this->projeto->resumo);
		$this->writeInnerBlock('BOLSAS PRETENDIDAS', $this->projeto->bolsasPretendidas);
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LBR', 1);
		$this->SetY($this->GetY() + 4);
	}

	private function writePartnersPages() {
		$tabParceiros = new Parceiro();
		$parceiros = $tabParceiros->fetchParceirosByProjeto($this->projeto->id);

		foreach ($parceiros as $parceiro) {
			$this->AddPage();
			$this->height = 10;
			$this->writeTitle(self::TITULO);

			$this->Ln(20);
			$this->SetFont('vera', 'B', 11);
			$this->Cell(self::BLOCK_SIZE, 5, 'A parceria só será firmada após assinatura do convênio pelas partes.', '', 1);
			$this->Ln(10);
			$this->Cell(self::BLOCK_SIZE, 5, 'PROJETO DE EXTENSÃO: ' . $this->projeto->titulo, '', 1);
			$this->Ln(20);
			$this->Cell(self::BLOCK_SIZE, 5, 'COORDENADOR: ' . $this->coordenador->nome, '', 1);
			$this->Ln(10);
			$this->Cell(self::BLOCK_SIZE, 5, 'Ciente, ', '', 1);
			$this->Cell(self::BLOCK_SIZE, 4, '___________________________________________________________', '', 1, 'C');
			$this->Cell(self::BLOCK_SIZE, 4, 'Assinatura', '', 1, 'C');
			$this->Ln(20);
			$this->Cell(self::BLOCK_SIZE, 5, 'PARCEIRO EXTERNO', '', 1);
			$this->Cell(self::BLOCK_SIZE, 5, 'INSTITUIÇÃO: ' . $parceiro->nomeInstituicao, '', 1);
			$this->Cell(self::BLOCK_SIZE, 5, 'RESPONSÁVEL: ' . $parceiro->nomeResponsavel, '', 1);
			$this->Ln(10);
			$this->Cell(self::BLOCK_SIZE, 5, 'Ciente, ', '', 1);
			$this->Cell(self::BLOCK_SIZE, 4, '___________________________________________________________', '', 1, 'C');
			$this->Cell(self::BLOCK_SIZE, 4, 'Assinatura', '', 1, 'C');
		}
	}

	/**
	 * Write all the content and calls the output method.
	 *
	 */
	function Output($name = '', $dest = '') {
		$this->AliasNbPages();
		$this->AddPage();
		$this->writeTitle(self::TITULO);
		$this->writeIdentificacao();
		$this->writeAreaTematica($this->projeto->idAreaTematica);
		$this->writeLinhaExtensao();
		$this->writeCoordenador();
		$this->writeEquipe();
		$this->writePartners();
		$this->writeDescricao();
		$this->writeResourcesTable($this->projeto->idRecursos);
		$this->writeSignatures($this->projeto->idRecursos);
		$this->writePartnersPages();
		parent::Output($name, $dest);
	}
	
	
}