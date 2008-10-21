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


class FormularioRelatorioProjeto extends Formulario {
	
	/**
	 * Armazena o projeto
	 *
	 * @var Projeto
	 */
	private $projeto;
	private $relatorio;
	
	const TITULO = 'RELATÓRIO FINAL DE PROJETOS DE EXTENSÃO';
	
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
		$tabRelatorio = new RelatorioFinal();
		$this->relatorio = $tabRelatorio->fetchRow('id = ' .$this->projeto->idRelatorioFinal);
		
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
	
 	/* Write all the content and calls the output method.
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
//		$this->writePartnersPages();
		parent::Output($name, $dest);
	}

	
	
}

?>