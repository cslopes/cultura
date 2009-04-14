<?php

require_once 'public/tcpdf/tcpdf.php';

require_once 'AreaTematica.php';
require_once 'Coordenador.php';
require_once 'Unidade.php';
require_once 'Departamento.php';
require_once 'Recursos.php';

require_once 'Zend/Date.php';

class Formulario extends TCPDF {

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

	protected $coordenador;
	
	/**
	 * Título do relatório
	 *
	 * @var String
	 */
	private $titulo;

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
	function __construct($idCoordenador, $titulo, $orientation = 'P', $unit = 'mm', $format = 'A4') {
		$tabCoordenador = new Coordenador();
		$this->coordenador = $tabCoordenador->fetchRow('id='.$idCoordenador);
		
		$this->titulo = $titulo;

		parent::__construct('P', 'mm', 'A4');
	}
	

	/**
	 * Document's header. Prints a rectangle around the page.
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
		$data = new Zend_Date();
		$this->Cell(0,10,$this->titulo . ' - Página '.$this->PageNo().'/{nb}'.' em '.$data->get('d-MM-y'),0,0,'C');
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

	/**
	 * Prints a colored table
	 */
	protected function FancyTable($header, $data, $width)
	{
		// Controls InnerBlock's beginning position and prints the block
		$this->haveInnerBlock = true;
		$this->innerY1 = $this->GetY();
		$this->SetX(self::INNER_MARGIN);

		//Colors, line width and bold font
		$this->SetFillColor(10,10,10);
		$this->SetTextColor(255);
		$this->SetDrawColor(150,150,150);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');

		//Header
		for($i=0;$i<count($width);$i++)
			$this->Cell($width[$i],7,$header[$i],1,0,'C',1);
		$this->Ln();

		//Color and font restoration
		$this->SetDrawColor(0,0,0);
		$this->SetFillColor(200,200,200);
		$this->SetTextColor(0);
		$this->SetFont('');
		//Data
		$fill=0;
		foreach($data as $row)
		{
			$this->SetX(self::INNER_MARGIN);
			for($i = 0; $i < count($width); $i++)
			$this->Cell($width[$i],6,$row[$i],'LR',0,'L',$fill);
			$this->Ln();
			$fill=!$fill;
		}
		$this->SetX(self::INNER_MARGIN);
		$this->Cell(array_sum($width),0,'','T', 1);

		// Controls and prints the inner block
		$this->innerY2 = $this->GetY();
		$this->Line(self::MARGIN, $this->innerY1, self::MARGIN, $this->innerY2);
		$this->Line(self::MARGIN + self::BLOCK_SIZE, $this->innerY1, self::MARGIN + self::BLOCK_SIZE, $this->innerY2);
		$this->haveInnerBlock = false;
	}

	/**
	 * Writes the title
	 *
	 */
	protected function writeTitle($title) {
		$this->Image('./public/images/ufjf.PNG', 42.75, ($this->height += 4), 124.5, 15.25);
		$this->height += 15.25;
		$this->SetFont('vera', 'B', 9);
		$this->Text((self::WIDTH - $this->GetStringWidth($title)) / 2, ($this->height += 6), $title);
		$this->SetY(($this->height += 4));
	}

	/**
	 *  Writes the Area Tematica block
	 *
	 */
	protected function writeAreaTematica($idAreaTematica) {
		if(!$idAreaTematica) throw new Exception('Não foi definida uma área temática.');
		
		$tabAreaTematica = new AreaTematica();
		
		$areaTematica = $tabAreaTematica->fetchRow('id='.$idAreaTematica);

		$this->SetFont('vera', 'B', 11);
		$this->Cell(self::BLOCK_SIZE, 5, ++$this->position . '. ÁREA TEMÁTICA', 'LTR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LR', 1);
		$this->SetFont('vera', '', 9);
		$this->Cell(self::BLOCK_SIZE, 4, $areaTematica->nome, 'LR', 1);
		$this->Cell(self::BLOCK_SIZE, 2, '', 'LBR', 1);
		$this->SetY($this->GetY() + 4);
	}

	/**
	 * Writes the Coordenador's block
	 *
	 */
	protected function writeCoordenador() {
		$tabDepartamento = new Departamento();
		$departamento = $tabDepartamento->fetchRow('id='.$this->coordenador->idDepartamento);

		$tabUnidade = new Unidade();
		$unidade = $tabUnidade->fetchRow('id='.$departamento->idUnidade);

		$this->SetFont('vera', 'B', 11);
		$this->Cell(self::BLOCK_SIZE, 5, ++$this->position . '. COORDENADOR', 'LTR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LR', 1);
		$sLabels = array('Nome: '.$this->coordenador->nome, 'Unidade: '.$unidade->nome, 'Departamento: '.$departamento->nome, 'Telefone: '.$this->coordenador->telefone, 'Celular: '.$this->coordenador->celular , 'Telefone Público: '.$this->coordenador->telefonePublico,'E-mail: '.$this->coordenador->email);
		$x = self::BLOCK_SIZE + self::MARGIN;
		foreach ($sLabels as $sLabel) {
			$y = $this->GetY();
			$this->SetFont('vera', 'B', 11);
			$this->Cell($this->GetStringWidth($sLabel), 5, $sLabel, 'L');
			$this->SetFont('vera', '', 11);
			$this->Cell(self::BLOCK_SIZE - $this->GetStringWidth($sLabel) - 0.6, 5, '', '', 1);
			$this->Line($x, $y, $x, $y + 5);
		}
		$this->Line(self::MARGIN, $this->GetY(), $x, $this->GetY());
		$this->SetY($this->GetY() + 4);
	}

	/**
	 * Writes the inner blocks
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

	protected function writeResourcesTable($idRecursos) {
		if($idRecursos) {
			$tabRecursos = new Recursos();
			$tabRecursos = $tabRecursos->find($idRecursos)->current();
			
			$this->AddPage();
			
			$width1 = 140;
			$width2 = 60;
			
			$recursos 				= $tabRecursos->recursosExternosValor;
			$diariaExterno 			= $tabRecursos->diariaExterno;
			$passagemExterno 		= $tabRecursos->passagemExterno;
			$alimentacaoExterno 	= $tabRecursos->alimentacaoExterno;
			$bolsaDiscente 			= $tabRecursos->bolsaDiscente;
			$pagamentoCoordenador	= $tabRecursos->pagamentoCoordenador;
			$pagamentoEquipe 		= $tabRecursos->pagamentoEquipe;
			$pagamentoPJExterno 	= $tabRecursos->pagamentoPJExterno;
			$pagamentoPFExterno 	= $tabRecursos->pagamentoPFExterno;
			$equipamentos 			= $tabRecursos->equipamentos;
			$material 				= $tabRecursos->material;
			
			$subtotal = $diariaExterno   +   $passagemExterno   +   $alimentacaoExterno   +   $bolsaDiscente +
						$pagamentoCoordenador + $pagamentoEquipe + $pagamentoPFExterno + $pagamentoPJExterno +
						$equipamentos + $material;
			$total = $subtotal + ($recursos * 0.15);
			
			$this->SetFont('vera', 'B', 11);
			$this->Cell(self::BLOCK_SIZE, 5, ++$this->position . '. PLANILHA DE RECURSOS', 'LTR', 1);
			$this->Cell(self::BLOCK_SIZE, 4, '', 'LR', 1);

			$this->SetFont('vera', '', 9);
			$this->Cell(self::BLOCK_SIZE - $width1, 4, 'Fundação gestora: ', 'L', 0, 'R');
			$this->Cell($width1, 4, $tabRecursos->gestora, 'R', 1, 'L');
			$this->Cell(self::BLOCK_SIZE - $width1, 4, 'Ano: ', 'L', 0, 'R');
			$this->Cell($width1, 4, $tabRecursos->ano, 'R', 1, 'L');
			$this->Cell(self::BLOCK_SIZE, 4, '', 'LR', 1);
			
			$this->SetFont('vera', 'B', 9);
			$this->Cell(self::BLOCK_SIZE, 4, 'Previsão da Receita', 'LR', 1, 'L');
			$this->Cell(self::BLOCK_SIZE - $width1, 4, '', 'L', 0, 'R');
			$this->Cell(70, 4, 'FONTE', '', 0, 'L');
			$this->Cell(70, 4, 'R$', 'R', 1, 'L');
			$this->SetFont('vera', '', 9);
			$this->Cell(self::BLOCK_SIZE - $width1, 4, 'Recursos externos ', 'L', 0, 'L');
			$this->Cell(70, 4, $tabRecursos->recursosExternosFonte, '', 0, 'L');
			$this->Cell(70, 4, $recursos, 'R', 1, 'L');
			$this->Cell(self::BLOCK_SIZE, 4, '', 'LR', 1);
			
			$this->SetFont('vera', 'B', 9);
			$this->Cell(self::BLOCK_SIZE, 4, 'Elementos de despesa', 'LR', 1, 'L');
			$this->SetFont('vera', '', 9);
			$this->Cell(self::BLOCK_SIZE - $width2, 4, 'Diárias', 'L', 0, 'L');
			$this->Cell($width2, 4, $diariaExterno, 'R', 1, 'L');
			$this->Cell(self::BLOCK_SIZE - $width2, 4, 'Passagens / locomoção', 'L', 0, 'L');
			$this->Cell($width2, 4, $passagemExterno, 'R', 1, 'L');
			$this->Cell(self::BLOCK_SIZE - $width2, 4, 'Alimentação', 'L', 0, 'L');
			$this->Cell($width2, 4, $alimentacaoExterno, 'R', 1, 'L');
			$this->Cell(self::BLOCK_SIZE - $width2, 4, 'Bolsas para discentes', 'L', 0, 'L');
			$this->Cell($width2, 4, $bolsaDiscente, 'R', 1, 'L');
			$this->Cell(self::BLOCK_SIZE - $width2, 4, 'Pagamento do coordenador', 'L', 0, 'L');
			$this->Cell($width2, 4, $pagamentoCoordenador, 'R', 1, 'L');
			$this->Cell(self::BLOCK_SIZE - $width2, 4, 'Pagamento da equipe (UFJF)', 'L', 0, 'L');
			$this->Cell($width2, 4, $pagamentoEquipe, 'R', 1, 'L');
			$this->Cell(self::BLOCK_SIZE - $width2, 4, 'Pagamento de Pessoa Jurídica', 'L', 0, 'L');
			$this->Cell($width2, 4, $pagamentoPJExterno, 'R', 1, 'L');
			$this->Cell(self::BLOCK_SIZE - $width2, 4, 'Pagamento de Pessoa Física', 'L', 0, 'L');
			$this->Cell($width2, 4, $pagamentoPFExterno, 'R', 1, 'L');
			$this->Cell(self::BLOCK_SIZE - $width2, 4, 'Equipamentos / Material permanente', 'L', 0, 'L');
			$this->Cell($width2, 4, $equipamentos, 'R', 1, 'L');
			$this->Cell(self::BLOCK_SIZE - $width2, 4, 'Material de consumo', 'L', 0, 'L');
			$this->Cell($width2, 4, $material, 'R', 1, 'L');
			
			$this->SetFont('vera', 'B', 9);
			$this->Cell(self::BLOCK_SIZE - $width2, 4, 'Subtotal', 'L', 0, 'L');
			$this->Cell($width2, 4, $subtotal, 'R', 1, 'L');

			$this->SetFont('vera', '', 9);
			$this->Cell(self::BLOCK_SIZE - $width2, 4, 'Taxa fundo de fomento UFJF 6,6%(Resolução 07/2000 - UFJF)', 'L', 0, 'L');
			$this->Cell($width2, 4, $recursos * 0.066, 'R', 1, 'L');
			$this->Cell(self::BLOCK_SIZE - $width2, 4, 'Taxa Unidade 3,4%(Resolução 07/2000 - UFJF)', 'L', 0, 'L');
			$this->Cell($width2, 4, $recursos * 0.034, 'R', 1, 'L');
			$this->Cell(self::BLOCK_SIZE - $width2, 4, 'Taxas fundação 5%', 'L', 0, 'L');
			$this->Cell($width2, 4, $recursos * 0.05, 'R', 1, 'L');
			
			$this->SetFont('vera', 'B', 9);
			$this->Cell(self::BLOCK_SIZE - $width2, 4, 'Total', 'L', 0, 'L');
			$this->Cell($width2, 4, $total, 'R', 1, 'L');
			
			$this->Cell(self::BLOCK_SIZE, 2, '', 'LBR', 1);
			$this->SetY($this->GetY() + 4);
		}
	}

	protected function writeSignatures($idRecursos = false) {
		$this->AddPage();

		$this->SetFont('vera', 'B', 11);
		$this->Cell(self::BLOCK_SIZE, 5, 'ASSINATURA DO COORDENADOR', 'LTR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LR', 1);

		$this->SetFont('vera', '', 9);
		$this->Cell(self::BLOCK_SIZE, 4, 'Em __/__/____', 'LR', 1, 'C');
		$this->Cell(self::BLOCK_SIZE, 5, '', 'LR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '____________________________________________________________', 'LR', 1, 'C');
		$this->Cell(self::BLOCK_SIZE, 4, 'Coordenador do Projeto', 'LR', 1, 'C');
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LBR', 1);
		$this->SetY($this->GetY() + 4);

		$this->SetFont('vera', 'B', 11);
		$this->Cell(self::BLOCK_SIZE, 5, 'APROVAÇÕES', 'LTR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LR', 1);

		$this->SetFont('vera', '', 9);
		$this->Cell(self::BLOCK_SIZE, 4, 'Aprovação do Departamento em __/__/____', 'LR', 1, 'C');
		$this->Cell(self::BLOCK_SIZE, 5, '', 'LR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '____________________________________________________________', 'LR', 1, 'C');
		$this->Cell(self::BLOCK_SIZE, 4, 'Chefe do Departamento', 'LR', 1, 'C');
		$this->Cell(self::BLOCK_SIZE, 15, '', 'LR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, 'Aprovação no Conselho de Unidade __/__/____', 'LR', 1, 'C');
		$this->Cell(self::BLOCK_SIZE, 5, '', 'LR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '____________________________________________________________', 'LR', 1, 'C');
		$this->Cell(self::BLOCK_SIZE, 4, 'Chefe de Unidade', 'LR', 1, 'C');
		$this->Cell(self::BLOCK_SIZE, 15, '', 'LR', 1);
		if($idRecursos) {
			$this->Cell(self::BLOCK_SIZE, 4, 'Ciente em __/__/____', 'LR', 1, 'C');
			$this->Cell(self::BLOCK_SIZE, 5, '', 'LR', 1);
			$this->Cell(self::BLOCK_SIZE, 4, '____________________________________________________________', 'LR', 1, 'C');
			$this->Cell(self::BLOCK_SIZE, 4, 'Fundação Gestora', 'LR', 1, 'C');
			$this->Cell(self::BLOCK_SIZE, 15, '', 'LR', 1);
		}
		//$this->Cell(self::BLOCK_SIZE, 4, 'De acordo', 'LR', 1, 'C');
		//$this->Cell(self::BLOCK_SIZE, 5, '', 'LR', 1);
		//$this->Cell(self::BLOCK_SIZE, 4, '____________________________________________________________', 'LR', 1, 'C');
		//$this->Cell(self::BLOCK_SIZE, 4, 'Coordenação do Projeto de Controle Interno e de Fundações / PROFIC', 'LR', 1, 'C');
		$this->Cell(self::BLOCK_SIZE, 5, '', 'LBR', 1);
	}

	/**
	 * Imcrementa o contador de posição
	 *
	 * @return int
	 */
	protected function increasePosition() {
		return ++$this->position;
	}

}

