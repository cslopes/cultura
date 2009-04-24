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

class FormularioParceiros extends Formulario {
	/**
	 * Armazena o projeto
	 *
	 * @var Projeto
	 */
	private $projeto;
	
	const TITULO = 'FORMULÁRIO DE EDIÇÃO DE PARCEIRO EM PROJETO DE EXTENSÃO';
	
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
/*	private function writeIdentificacao() {
		$this->SetFont('vera', 'B', 10);
		$position = $this->increasePosition();
		$this->Cell(self::BLOCK_SIZE, 5, $position . '. IDENTIFICAÇÃO', 'LTR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LR', 1);
		$this->Cell(self::BLOCK_SIZE, 5, 'TÍTULO: ' . $this->projeto->titulo, 'LR', 1);
		$this->Cell(self::BLOCK_SIZE, 5, 'NÚMERO DO PROCESSO: ' . $this->projeto->processo, 'LBR', 1);
		$this->SetY($this->GetY() + 4);
	} */

	function Footer() {
		$this->SetY(-15);
		$this->SetFont('vera', 'I', 8);
		$data = new Zend_Date();
		$this->Cell(0,10,$this->projeto->titulo . ' - em '.$data->get('d-MM-y'),0,0,'C');
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


	private function writePartnersPages() {
		$tabParceiros = new Parceiro();
		$parceiros = $tabParceiros->fetchParceirosByProjeto($this->projeto->id);

		foreach ($parceiros as $parceiro) {
			$this->AddPage();
			$this->height = 10;
			$this->writeTitle(self::TITULO);

			$this->Ln(20);
			$this->SetFont('vera', 'B', 10);
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
//		$this->writeIdentificacao();
		$this->writePartners();
		$this->writePartnersPages();
		parent::Output($name, $dest);
	}
	
	
}