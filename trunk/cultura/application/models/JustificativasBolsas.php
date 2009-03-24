<?php

require_once 'public/tcpdf/tcpdf.php';

require_once 'Projeto.php';

class JustificativasBolsas extends TCPDF {

	/**
	 * Width of the document
	 *
	 */
	const WIDTH = 210;

	/**
	 * Height of the document
	 *
	 */
	const HEIGHT = 297;

	/**
	 * The left margin of the document
	 *
	 */
	const MARGIN = 10;

	/**
	 * Block's size
	 *
	 */
	const BLOCK_SIZE = 190;

	/**
	 * Inner block's size
	 *
	 */
	const INNER_BLOCK_SIZE = 170;

	/**
	 * Inner margin
	 */
	const INNER_MARGIN = 20;

	
	
	/**
	 * Título do relatório
	 *
	 * @var String
	 */
	const TITULO = 'JUSTIFICATIVAS PARA AS BOLSAS DOS PROJETOS DE EXTENSÃO';

	/**
	 * Controls the begining height of the document
	 *
	 * @var float
	 */
	protected $height = 0;

	/**
	 * Indicate that in specific moment have a inner block
	 *
	 * @var boolean
	 */
	protected $haveInnerBlock = false;

	/**
	 * The initial position value of the inner block's y coordenate
	 *
	 * @var float
	 */
	protected $innerY1 = 0;

	/**
	 * The final position value of the inner block's y coordenate
	 *
	 * @var float
	 */
	protected $innerY2 = 0;

	/**
	 * Controla o número de exibição
	 *
	 * @var int
	 */
	protected $position = 0;
	
		/**
	 * Class constructor
	 *
	 * @param int $idCoordenador id do coordenador
	 * @param String $titulo Título do orientador
	 * @param String $orientation 'P' (Portrait) or 'L' (Landscape)
	 * @param String $unit The default is 'mm'
	 * @param String $format Format of the page, default is 'A4'
	 */
	function __construct($orientation = 'P', $unit = 'mm', $format = 'A4') {
		$tabProjeto = new Projeto();
		$this->projetoList = $tabProjeto->fetchAll('bolsasPretendidas > 0');
		
		
		parent::__construct('P', 'mm', 'A4');
	}
	
	 /* Document's header. Prints a rectangle around the page.
	 *
	 */
	function Header() {
		$this->Rect(2, 2, 206, 293);
	}

	/**
	 * Atribui valor ao título
	 *
	 * @param String $titulo
	 */
	function setTitulo($titulo) {
		$this->titulo = $titulo;
	}

	/**
	 * Document's footer. Prints page number at the end of each page.
	 *
	 */
	function Footer() {
		$this->SetY(-15);
		$this->SetFont('vera', 'I', 8);
		$this->Cell(0,10, self::TITULO. ' - Página '.$this->PageNo().'/{nb}',0,0,'C');
	}

	/**
	 * If the document has a inner block print the side lines of the outer block
	 *
	 */
	function AcceptPageBreak() {
		if($this->haveInnerBlock) {
			$this->Line(self::MARGIN, $this->innerY1, self::MARGIN, 274.5);
			$this->Line(self::MARGIN + self::BLOCK_SIZE, $this->innerY1, self::MARGIN + self::BLOCK_SIZE, 274.5);
			$this->innerY1 = 10;
		}
		return true;
	}
	
	protected function writeTitle($title) {
		$this->Image('./public/images/ufjf.PNG', 42.75, ($this->height += 4), 124.5, 15.25);
		$this->height += 15.25;
		$this->SetFont('vera', 'B', 9);
		$this->Text((self::WIDTH - $this->GetStringWidth($title)) / 2, ($this->height += 6), $title);
		$this->SetY(($this->height += 4));
	}
	
	/**
	 * Writes the Identificacao block
	 *
	 */
	private function writeIdentificacao($projeto) {
		$this->SetFont('vera', 'B', 9);
		$position = $this->increasePosition();
		$this->Cell(self::BLOCK_SIZE, 5, $position . '. IDENTIFICAÇÃO', 'LTR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LR', 1);
		$this->MultiCell(self::BLOCK_SIZE, 5, 'TÍTULO: ' . $projeto->titulo, 'LR', 1);
		$this->Cell(self::BLOCK_SIZE, 5, 'NÚMERO DO PROCESSO: ' . $projeto->processo, 'LBR', 1);
		$this->SetY($this->GetY() + 4);
	}
	
	protected function writeCoordenador($projeto) {
		$coordenador = $projeto->findParentCoordenador()->nome;
		$coord_tel = $projeto->findParentCoordenador()->telefone;
		$coord_cel = $projeto->findParentCoordenador()->celular;
		$coord_tel_publico = $projeto->findParentCoordenador()->telefonePublico;
		$coord_email = $projeto->findParentCoordenador()->email;
		$departamento = $projeto->findParentCoordenador()->findParentDepartamento()->nome;
		$unidade = $projeto->findParentCoordenador()->findParentDepartamento()->findParentUnidade()->nome;

		$this->SetFont('vera', 'B', 9);
		$this->Cell(self::BLOCK_SIZE, 5, ++$this->position . '. COORDENADOR', 'LTR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LR', 1);
		$sLabels = array('Nome: '.$coordenador, 'Unidade: '.$unidade, 'Departamento: '.$departamento, 'Telefone: '.$coord_tel, 'Celular: '.$coord_cel , 'Telefone Público: '.$coord_tel_publico,'E-mail: '.$coord_email);
		$x = self::BLOCK_SIZE + self::MARGIN;
		foreach ($sLabels as $sLabel) {
			$y = $this->GetY();
			$this->SetFont('vera', 'B', 9);
			$this->Cell($this->GetStringWidth($sLabel), 5, $sLabel, 'L');
			$this->SetFont('vera', '', 9);
			$this->Cell(self::BLOCK_SIZE - $this->GetStringWidth($sLabel) - 0.6, 5, '', '', 1);
			$this->Line($x, $y, $x, $y + 5);
		}
		$this->Line(self::MARGIN, $this->GetY(), $x, $this->GetY());
		$this->SetY($this->GetY() + 4);
	}
		
	/*
	 * writes the Bolsas block
	 * @param Projeto $projeto
	 */
	protected function writeBolsas($projeto){
		$this->SetFont('vera', 'B', 9);
		$position = $this->increasePosition();
		$this->Cell(self::BLOCK_SIZE, 5, $position . '. BOLSAS', 'LTR', 1);
		$this->Cell(self::BLOCK_SIZE, 5, 'Bolsas Pretendidas: ' . $projeto->bolsasPretendidas, 'LR', 1);
		$this->MultiCell(self::BLOCK_SIZE, 5, 'Justificativa para Bolsas: ' . $projeto->bolsasJustificativa, 'LBR', 1);
//		$this->writeInnerBlock('Justificativa para Bolsas:', $projeto->bolsasJustificativa);
		$this->SetY($this->GetY() + 4);
		
	}
	
	/*
	 * writes all about the Bolsas together
	 * @param Projeto $projeto
	 */
	protected function writeAll($projeto){
		$this->MultiCell(self::WIDTH,self::HEIGHT,$this->writeIdentificacao($projeto).$this->writeCoordenador($projeto).$this->writeBolsas($projeto),'LTRB');
	}
	
		
	 /* Writes the inner blocks
	 *
	 * @param string $title The title of the inner block
	 * @param string $text 	The text of the inner block
	 */
	protected function writeInnerBlock($title, $text) {
		$this->haveInnerBlock = true;
		$this->innerY1 = $this->GetY();

		$this->SetFont('vera', 'B', 9);
		$this->SetX(self::INNER_MARGIN);
		$this->Cell(self::INNER_BLOCK_SIZE, 5, $title, 'LTR', 2);

		$this->SetFont('vera', '', 9);
		$this->MultiCell(self::INNER_BLOCK_SIZE, 4, $text, 'LBR');
		$this->SetY($this->GetY() + 4);

		$this->innerY2 = $this->GetY();
		$this->Line(self::MARGIN, $this->innerY1, self::MARGIN, $this->innerY2);
		$this->Line(self::MARGIN + self::BLOCK_SIZE, $this->innerY1, self::MARGIN + self::BLOCK_SIZE, $this->innerY2);
		$this->haveInnerBlock = false;
	}
	
	 /* Imcrementa o contador de posição
	 *
	 * @return int
	 */
	protected function increasePosition() {
		return ++$this->position;
	}
	
		/**
	 * Write all the content and calls the output method.
	 *
	 */
	function Output($name = '', $dest = '') {
		$this->AliasNbPages();
		$this->AddPage();
		$this->writeTitle(self::TITULO);
		$i = 0;
		foreach ($this->projetoList as $projeto): {
			$this->writeIdentificacao($projeto);
			$this->writeCoordenador($projeto);
			$this->writeBolsas($projeto);
			$this->Cell(self::BLOCK_SIZE, 4, '', '', 1);
			$this->position = 0;
			if ($i < 1){
				$i++;
			}else{
				$i = 0;
				$this->addPage();
			}
		}
		endforeach;
		parent::Output($name, $dest);
	}
	
}
