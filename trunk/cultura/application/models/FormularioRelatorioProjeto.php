<?php
require_once 'public/tcpdf/tcpdf.php';

require_once 'Formulario.php';
require_once 'Projeto.php';
require_once 'RelatorioFinal.php';
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

//		$tabRelatorio = new RelatorioFinal();
//		$this->relatorio = $tabRelatorio->fetchRow('id = ' .$this->projeto->idRelatorioFinal);
		

				

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
	 * Writes the Descricao block.
	 *
	 */
	private function writeDescricao() {
		$tabRelatorio = new RelatorioFinal();
		$this->relatorio = $tabRelatorio->fetchRow('id='.$this->projeto->idRelatorioFinal);
		
		$this->SetFont('vera', 'B', 11);
		$position = $this->increasePosition();
		$this->Cell(self::BLOCK_SIZE, 5, $position . '. DESCRIÇÃO', 'LTR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LBR', 1);
		$this->SetFont('vera','', 11);
		$this->Cell(self::BLOCK_SIZE, 5, 'Articulação com o ensino', 'LTR', 1);
		$this->writeInnerBlock('Disciplinas:', $this->relatorio->disciplinas);
		$this->writeInnerBlock('Estágio:', $this->relatorio->estagio);
		$this->writeInnerBlock('Crédito (flexibilização curricular)', $this->projeto->publicoAlvo);
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LBR', 1);
		$this->SetFont('vera','', 11);
		$this->Cell(self::BLOCK_SIZE, 5, 'Articulação com a pesquisa', 'LTR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LBR', 1);
		$this->SetY($this->GetY() + 4);
	}
	
	
	
	/**
	 * Write all the content and calls the output method.
	 *
	 */

	function Output($name = '', $dest = '') {
		$this->AddPage();

		$this->writeTitle(self::TITULO);
		$this->writeIdentificacao();
		$this->writeAreaTematica($this->projeto->idAreaTematica);
		$this->writeLinhaExtensao();
		$this->writeCoordenador();
		$this->writeDescricao();
		parent::Output($name, $dest);
	}
	
	

	
}