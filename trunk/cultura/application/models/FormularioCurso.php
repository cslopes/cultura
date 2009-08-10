	<?php

require_once 'public/tcpdf/tcpdf.php';

require_once 'Formulario.php';
require_once 'Curso.php';
require_once 'Coordenador.php';



class FormularioCurso extends Formulario {
	/**
	 * Armazena o curso
	 *
	 * @var Curso
	 */
	private $curso;
	
	const TITULO = 'FORMULÁRIO DE INSCRIÇÃO DE CURSOS DE EXTENSÃO';
	
	/**
	 * Class constructor
	 *
	 * @param Curso $curso Curso a ser impresso no arquivo
	 * @param String $orientation 'P' (Portrait) or 'L' (Landscape)
	 * @param String $unit The default is 'mm'
	 * @param String $format Format of the page, default is 'A4'
	 */
	function __construct($curso, $orientation = 'P', $unit = 'mm', $format = 'A4') {
		$this->curso = $curso;
		
		parent::__construct($curso->idCoordenador, $this->curso->titulo, $orientation, $unit, $format);
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
		$this->Cell(self::BLOCK_SIZE, 5, 'TÍTULO: ' . $this->curso->titulo, 'LR', 1);
		$this->Cell(self::BLOCK_SIZE, 5, 'NÚMERO DO PROCESSO: ' . $this->curso->processo, 'LR', 1);
		
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LR', 1);
		$this->SetFont('vera', '', 11);
//		$this->Cell(self::BLOCK_SIZE, 5, 'INICIO: ' . date_format(date_create($this->curso->dataInicio), "d/m/Y"), 'LR', 1);
//		$this->Cell(self::BLOCK_SIZE, 5, 'FINAL: ' . date_format(date_create($this->curso->dataFinal), "d/m/Y"), 'LR', 1);
		$this->Cell(self::BLOCK_SIZE, 5, 'CARGA HORÁRIA: ' . $this->curso->cargaHoraria, 'LR', 1);
//		$this->Cell(self::BLOCK_SIZE, 5, 'EXPECTATIVA DE PÚBLICO: ' . $this->curso->expectativaPublico, 'LBR', 1);
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
		$this->writeInnerBlock('DESCRIÇÃO', $this->curso->descricao);
		$this->writeInnerBlock('PÚBLICO ALVO', $this->curso->publicoAlvo);
		$this->writeInnerBlock('EXPECTATIVA DE PÚBLICO', $this->curso->expectativaPublico);
		$this->writeInnerBlock('RESUMO', $this->curso->resumo);
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
		$this->writeAreaTematica($this->curso->idAreaTematica);
		$this->writeCoordenador();
		$this->writeDescricao();
		$this->writeResourcesTable($this->curso->idRecursos);
		$this->writeSignatures($this->curso->idRecursos);
		parent::Output($name, $dest);
	}
	
	
}
