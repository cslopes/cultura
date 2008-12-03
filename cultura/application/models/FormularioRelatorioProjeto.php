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
	
	const TITULO = 'RELAT�RIO FINAL DE PROJETOS DE EXTENS�O';
	
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
		$this->Cell(self::BLOCK_SIZE, 5, $position . '. IDENTIFICA��O', 'LTRB', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LR', 1);
		$this->Cell(self::BLOCK_SIZE, 5, 'T�TULO: ' . $this->projeto->titulo, 'LR', 1);
		$this->Cell(self::BLOCK_SIZE, 5, 'N�MERO DO PROCESSO: ' . $this->projeto->processo, 'LBR', 1);
		$this->SetY($this->GetY() + 4);
	}

	/**
	 * Writes the Linha de Extensao block
	 *
	 */
	private function writeLinhaExtensao() {
		if(!$this->projeto->idLinhaAtuacao) throw new Exception('N�o foi definida uma linha de extens�o');
		
		$tabLinhaAtuacao = new LinhaAtuacao();
		$linhaAtuacao = $tabLinhaAtuacao->fetchRow('id='.$this->projeto->idLinhaAtuacao);

		$this->SetFont('vera', 'B', 11);
		$position = $this->increasePosition();
		$this->Cell(self::BLOCK_SIZE, 5, $position . '. LINHA DE EXTENS�O', 'LTR', 1);
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
		$tabParceiro = new Parceiro();
		$parceiros = $tabParceiro->fetchParceirosByProjeto($this->projeto->id);

		$this->SetFont('vera', 'B', 11);
		$position = $this->increasePosition();
		$this->Cell(self::BLOCK_SIZE, 5, $position . '. DESCRI��O', 'LTR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LR', 1);
		$this->SetFont('vera','B', 10);
		$this->Cell(self::BLOCK_SIZE, 5, 'Articula��o com o ensino', 'LTR', 1);
		$this->SetFont('vera','',10);
		$this->Cell(self::BLOCK_SIZE, 5, 'Disciplinas: '.$this->relatorio->disciplinas,'LR',1);
		$this->Cell(self::BLOCK_SIZE, 5, 'Est�gio: '.$this->relatorio->estagio,'LR',1);
		$this->Cell(self::BLOCK_SIZE,5,'Cr�dito (flexibiliza��o curricular): '.$this->relatorio->creditos,'LR',1);
		$this->SetFont('vera','B', 10);
		$this->Cell(self::BLOCK_SIZE, 5, 'Articula��o com a pesquisa', 'LTR', 1);
		$this->SetFont('vera','',10);
		$this->Cell(self::BLOCK_SIZE,5,'Projeto: '.$this->relatorio->projeto,'LR',1);
		$this->SetFont('vera','B', 10);
		$this->Cell(self::BLOCK_SIZE, 5, 'Total de pessoas da equipe de trabalho envolvidas na execu��o', 'LTR', 1);
		$this->SetFont('vera','',10);
		$this->Cell(self::BLOCK_SIZE,5,'Docentes: '.$this->relatorio->docentesEnvolvidos,'LR',1);
		$this->SetFont('vera','B', 10);
		$this->Cell(self::BLOCK_SIZE,5,'Alunos da Gradua��o','LTR',1);
		$this->SetFont('vera','', 10);
		$this->Cell(self::BLOCK_SIZE,5,'Bolsistas: '.$this->relatorio->alunosGraduacaoBolsistasEnvolvidos,'LR',1);
		$this->Cell(self::BLOCK_SIZE,5,'N�o - Bolsistas: '.$this->relatorio->alunosGraduacaoNaoBolsistasEnvolvidos,'LBR',1);
		$this->Cell(self::BLOCK_SIZE,5,'Alunos P�s-Gradua��o: '.$this->relatorio->alunosPosGraduacaoEnvolvidos,'LR',1);
		$this->Cell(self::BLOCK_SIZE,5,'T�cnicos Administrativos: '.$this->relatorio->tecnicosAdministrativosEnvolvidos,'LR',1);
		$this->Cell(self::BLOCK_SIZE,5,'De outras IES ou org�os: '.$this->relatorio->pessoasOutrasIESEnvolvidas,'LR',1);
		$this->Cell(self::BLOCK_SIZE,5,'Da comunidade externa: '.$this->relatorio->pessoasComunidadeEnvolvidas,'LR',1);
		$this->Cell(self::BLOCK_SIZE,5,'P�blico atingido: '.$this->relatorio->publicoAtingido,'LR',1);
		$this->SetFont('vera','B', 10);
		$this->Cell(self::BLOCK_SIZE,5,'Aricula��o Externa (Parceiros Externos)','LTR',1);
		$this->SetFont('vera','', 10);
//		foreach ($parceiros as $parceiro){
//			$this->Cell(self::BLOCK_SIZE,5,$parceiro,'LR',1);
//		}
//		$this->Cell(self::BLOCK_SIZE,5,$parceiro,'LR',1);
		$this->Cell(self::BLOCK_SIZE,5,'N�mero de atendimentos por semana','LTR',1);
		$this->SetFont('vera','', 10);
		$this->Cell(self::BLOCK_SIZE,5,'Individuais: '.$this->relatorio->atendimentosSemanaisIndividuais,'LR',1);
		$this->Cell(self::BLOCK_SIZE,5,'Grupo: '.$this->relatorio->atendimentosSemanaisGrupo,'LR',1);
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LBR', 1);
		$this->SetY($this->GetY() + 4);
		$this->AddPage();
		
		$position = $this->increasePosition();
		$this->SetFont('vera', 'B', 11);
		$this->Cell(self::BLOCK_SIZE, 5, $position . '. MONITORAMENTO DA PRODU��O', 'LTR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LR', 1);
		$this->SetFont('vera', 11);
		$this->Cell((self::BLOCK_SIZE/3),5,'Livro: '.$this->relatorio->producaoLivros,'LTRB',0);
		$this->Cell((self::BLOCK_SIZE/3),5,'Comunica��o: '.$this->relatorio->producaoComunicacao,'LTRB',0);
		$this->Cell((self::BLOCK_SIZE/3),5,'Programa de R�dio: '.$this->relatorio->producaoProgramasRadio,'LTRB',1);
		$this->Cell((self::BLOCK_SIZE/3),5,'Cap�tulo de Livro: '.$this->relatorio->producaoCapitulosLivros,'LTRB',0);
		$this->Cell((self::BLOCK_SIZE/3),5,'Relat�rio T�cnico: '.$this->relatorio->producaoRelatoriosTecnicos,'LTRB',0);
		$this->Cell((self::BLOCK_SIZE/3),5,'Programas de TV: '.$this->relatorio->producaoProgramasTV,'LTRB',1);
		$this->Cell((self::BLOCK_SIZE/3),5,'Anais: '.$this->relatorio->producaoAnais,'LTRB',0);
		$this->Cell((self::BLOCK_SIZE/3),5,'Produto Audiovisual - Filme: '.$this->relatorio->producaoAudiovisualFilme,'LTRB',0);
		$this->Cell((self::BLOCK_SIZE/3),5,'Aplicativo para computador: '.$this->relatorio->producaoAplicativosComputador,'LTRB',1);
		$this->Cell((self::BLOCK_SIZE/3),5,'Manual: '.$this->relatorio->producaoManuais,'LTRB',0);
		$this->Cell((self::BLOCK_SIZE/3),5,'Produto Audiovisual - V�deo: '.$this->relatorio->producaoAudiovisualVideos,'LTRB',0);
		$this->Cell((self::BLOCK_SIZE/3),5,'Jogo Educativo: '.$this->relatorio->producaoJogosEducativos,'LTRB',1);
		$this->Cell((self::BLOCK_SIZE/3),5,'Jornal: '.$this->relatorio->producaoJornais,'LTRB',0);
		$this->Cell((self::BLOCK_SIZE/3),5,'Produto Audiovisual - CD: '.$this->relatorio->producaoAudiovisualCDs,'LTRB',0);
		$this->Cell((self::BLOCK_SIZE/3),5,'Produto Art�stico: '.$this->relatorio->producaoProdutosArtisticos,'LTRB',1);
		$this->Cell((self::BLOCK_SIZE/3),5,'Revista: '.$this->relatorio->producaoRevistas,'LTRB',0);
		$this->Cell((self::BLOCK_SIZE/3),5,'Produto Audiovisual - DVD: '.$this->relatorio->producaoAudiovisualDVDs,'LTRB',0);
		$this->Cell((self::BLOCK_SIZE/3),5,'Artigo: '.$this->relatorio->producaoArtigos,'LTRB',1);
		$this->Cell((self::BLOCK_SIZE/3),5,'Produto Audiovisual - Outros: '.$this->relatorio->producaoAudiovisualOutros,'LTRB',0);
		$this->Cell((self::BLOCK_SIZE/3),5,'Outros: '.$this->relatorio->producaoOutros,'LTB',0);
		$this->Cell((self::BLOCK_SIZE/3),5,$this->relatorio->producaoOutrosTexto,'LTRB',1);
		$this->Cell(self::BLOCK_SIZE,5,'Detalhamento da Produ��o: ','LTR',1);
		$this->SetFont('vera','',11);
		$this->MultiCell(self::BLOCK_SIZE,1,$this->relatorio->producaoDetalhamento,'LRB','L');
		$this->SetFont('vera','B',11);
		$this->Cell(self::BLOCK_SIZE,5,"Relat�rio Final: ",'LR',1);
		$this->SetFont('vera','',11);
		$this->MultiCell(self::BLOCK_SIZE,1,$this->relatorio->relatorioFinal,'LRB','L');
		$this->Cell(self::BLOCK_SIZE, 6, '', '', 1);
	
	}
	
protected function writeSignatures($idRecursos = false) {
//		$this->AddPage();

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
		$this->Cell(self::BLOCK_SIZE, 5, 'APROVA��ES', 'LTR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LR', 1);

		$this->SetFont('vera', '', 9);
		$this->Cell(self::BLOCK_SIZE, 4, 'Aprova��o do Departamento em __/__/____', 'LR', 1, 'C');
		$this->Cell(self::BLOCK_SIZE, 5, '', 'LR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '____________________________________________________________', 'LR', 1, 'C');
		$this->Cell(self::BLOCK_SIZE, 4, 'Chefe do Departamento', 'LR', 1, 'C');
		$this->Cell(self::BLOCK_SIZE, 15, '', 'LR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, 'Aprova��o no Conselho de Unidade __/__/____', 'LR', 1, 'C');
		$this->Cell(self::BLOCK_SIZE, 5, '', 'LR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '____________________________________________________________', 'LR', 1, 'C');
		$this->Cell(self::BLOCK_SIZE, 4, 'Chefe de Unidade', 'LR', 1, 'C');
		$this->Cell(self::BLOCK_SIZE, 15, '', 'LR', 1);
		if($idRecursos) {
			$this->Cell(self::BLOCK_SIZE, 4, 'Ciente em __/__/____', 'LR', 1, 'C');
			$this->Cell(self::BLOCK_SIZE, 5, '', 'LR', 1);
			$this->Cell(self::BLOCK_SIZE, 4, '____________________________________________________________', 'LR', 1, 'C');
			$this->Cell(self::BLOCK_SIZE, 4, 'Funda��o Gestora', 'LR', 1, 'C');
			$this->Cell(self::BLOCK_SIZE, 15, '', 'LR', 1);
		}
		//$this->Cell(self::BLOCK_SIZE, 4, 'De acordo', 'LR', 1, 'C');
		//$this->Cell(self::BLOCK_SIZE, 5, '', 'LR', 1);
		//$this->Cell(self::BLOCK_SIZE, 4, '____________________________________________________________', 'LR', 1, 'C');
		//$this->Cell(self::BLOCK_SIZE, 4, 'Coordena��o do Projeto de Controle Interno e de Funda��es / PROFIC', 'LR', 1, 'C');
		$this->Cell(self::BLOCK_SIZE, 5, '', 'LBR', 1);
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
		$this->writeSignatures($this->projeto->idRecursos);
		parent::Output($name, $dest);
	}
	
	

	
}