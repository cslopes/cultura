<?php

require_once 'public/tcpdf/tcpdf.php';

require_once 'Formulario.php';
require_once 'Evento.php';
require_once 'Coordenador.php';

class FormularioEvento extends Formulario {
	/**
	 * Armazena o evento
	 *
	 * @var Evento
	 */
	private $evento;
	
	const TITULO = 'FORMULÁRIO DE INSCRIÇÃO DE EVENTOS DE EXTENSÃO';
	
	/**
	 * Class constructor
	 *
	 * @param Evento $evento Evento a ser impresso no arquivo
	 * @param String $orientation 'P' (Portrait) or 'L' (Landscape)
	 * @param String $unit The default is 'mm'
	 * @param String $format Format of the page, default is 'A4'
	 */
	function __construct($evento, $orientation = 'P', $unit = 'mm', $format = 'A4') {
		$this->evento = $evento;
		
		parent::__construct($evento->idCoordenador, $this->evento->titulo, $orientation, $unit, $format);
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
		$this->Cell(self::BLOCK_SIZE, 5, 'TÍTULO: ' . $this->evento->titulo, 'LR', 1);
		$this->Cell(self::BLOCK_SIZE, 5, 'NÚMERO DO PROCESSO: ' . $this->evento->processo, 'LBR', 1);
		$this->SetY($this->GetY() + 4);
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
		$this->writeInnerBlock('PÚBLICO ALVO', $this->evento->publicoAlvo);
		$this->writeInnerBlock('OBJETIVOS', $this->evento->objetivos);
		$this->writeInnerBlock('RESUMO', $this->evento->resumo);
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LBR', 1);
		$this->SetY($this->GetY() + 4);
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
		$this->writeAreaTematica($this->evento->idAreaTematica);
		$this->writeCoordenador();
		$this->writeDescricao();
		$this->writeResourcesTable($this->evento->idRecursos);
		$this->writeSignatures($this->evento->idRecursos);
		parent::Output($name, $dest);
	}
	
	
}