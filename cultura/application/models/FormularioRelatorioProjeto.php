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
				
		parent::__construct($projeto->idCoordenador, $this->projeto->titulo, $orientation, $unit, $format);
		$this->AddPage();
		$this->Output();
	}
	
	

	
}

?>