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
		$this->Cell(self::BLOCK_SIZE, 5, $position . '. IDENTIFICAÇÃO', 'LTRB', 1);
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
//		$this->Cell(self::BLOCK_SIZE, 4, '', 'LTR', 1);
		$this->SetFont('vera','', 11);
//		$this->Cell(self::BLOCK_SIZE, 5, 'Articulação com o ensino', 'LTR', 1);
//		$this->writeInnerBlock('Disciplinas:', $this->relatorio->disciplinas);
//		$this->writeInnerBlock('Estágio:', $this->relatorio->estagio);
//		$this->writeInnerBlock('Crédito (flexibilização curricular):', $this->projeto->creditos);
//		$this->Cell(self::BLOCK_SIZE, 4, '', 'LBR', 1);
//		$this->SetFont('vera','', 11);
//		$this->Cell(self::BLOCK_SIZE, 5, 'Articulação com a pesquisa', 'LTR', 1);
//		$this->writeInnerBlock('Projeto:', $this->relatorio->projeto);
//		$this->Cell(self::BLOCK_SIZE, 4, '', 'LBR', 1);
//		$this->SetFont('vera','', 11);
//		$this->Cell(self::BLOCK_SIZE, 5, 'Total de pessoas da equipe de trabalho envolvidas na execução', 'LTR', 1);
//		$this->writeInnerBlock('Docentes:', $this->relatorio->docentesEnvolvidos);
//		$this->writeInnerBlock('Alunos da Graduação', 'Bolsistas: '.$this->relatorio->alunosGraduacaoBolsistasEnvolvidos.'    Não Bolsistas: '.$this->relatorio->alunosGraduacaoNaoBolsistasEnvolvidos.'                                  ');
//		$this->writeInnerBlock('Alunos Pós-Graduação:', $this->relatorio->alunosPosGraduacaoEnvolvidos);
//		$this->writeInnerBlock('Técnicos Administrativos:', $this->relatorio->tecnicosAdministrativosEnvolvidos);
//		$this->writeInnerBlock('De outras IES ou orgãos:', $this->relatorio->pessoasOutrasIESEnvolvidas);
//		$this->writeInnerBlock('Da comunidade externa:', $this->relatorio->pessoasComunidadeEnvolvidas);
//		$this->writeInnerBlock('Público atingido:', $this->relatorio->publicoAtingido);
//		$this->Cell(self::BLOCK_SIZE, 4, '', 'LBR', 1);
		$descricao = "Articulação com o ensino: \n".
		"   Disciplinas: ".$this->relatorio->disciplinas."\n".
		"   Estágio: ".$this->relatorio->estagio."\n".
		"   Crédito (Flexibilização curricular): ".$this->relatorio->creditos."\n\n".
		"Articulação com a pesquisa: \n".
		"   Projeto: ".$this->relatorio->projeto."\n\n".
		"Total de pessoas da equipe de trabalho envolvidas na execução: \n".
		"   Alunos da Graduação: \n".
		"      Bolsistas: ".$this->relatorio->alunosGraduacaoBolsistasEnvolvidos."   Não Bolsistas: ".
		$this->relatorio->alunosGraduacaoNaoBolsistasEnvolvidos."\n".
		"   Docentes: ".$this->relatorio->docentesEnvolvidos."\n".
		"   Alunos Pós-Graduação: ".$this->relatorio->alunosPosGraduacaoEnvolvidos."\n". 
		"   Técnicos Administrativos: ".$this->relatorio->tecnicosAdministrativosEnvolvidos."\n".
		"   De outras IES ou orgãos: ".$this->relatorio->pessoasOutrasIESEnvolvidas."\n".
		"   Da comunidade externa: ".$this->relatorio->pessoasComunidadeEnvolvidas."\n".
		"   Público atingido: ".$this->relatorio->publicoAtingido."\n \n".
		"Aticulação Externa: \n".
		"   Parceiros Externos: \n\n".
		"Número de atendimentos por semana \n".
		"   Grupo: ".$this->relatorio->atendimentosSemanaisGrupo."   Individual: ".
		$this->relatorio->atendimentosSemanaisIndividuais."\n";
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LR', 1);
		$this->MultiCell(self::BLOCK_SIZE,1,$descricao,'LR','L');	
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LBR', 1);
		$this->SetY($this->GetY() + 4);
		$this->AddPage();
		
		$position = $this->increasePosition();
		$this->SetFont('vera', 'B', 11);
		$this->Cell(self::BLOCK_SIZE, 5, $position . '. MONITORAMENTO DA PRODUÇÃO', 'LTR', 1);
		$this->Cell(self::BLOCK_SIZE, 4, '', 'LR', 1);
		
		
		$descricao = "Livro: ".$this->relatorio->producaoLivros."   Comunicação: ".
		$this->relatorio->producaoComunicacao."\n".
		"Programa de Rádio: ".$this->relatorio->producaoProgramasRadio."   Capítulo de Livro: ".
		$this->relatorio->producaoCapitulosLivros."\n".
		"Relatório Técnico: ".$this->relatorio->producaoRelatoriosTecnicos."   Programas de TV: ".
		$this->relatorio->producaoProgramasTV."\n".
		"Anais: ".$this->relatorio->producaoAnais."   Produto Audiovisual - Filme: ".
		$this->relatorio->producaoAudiovisualFilme."\n".
		"Aplicativo para computador: ".$this->relatorio->producaoAplicativosComputador."   Manual: ".
		$this->relatorio->producaoManuais."\n".
		"Produto Audiovisual - Vídeo: ".$this->relatorio->producaoAudiovisualFilme."   Jogo Educativo: ".
		$this->relatorio->producaoJogosEducativos."\n".
		"Jornal: ".$this->relatorio->producaoJornais."   Produto Audiovisual - CD: ".
		$this->relatorio->producaoAudiovisualCDs."\n".
		"Produto Artístico: ".$this->relatorio->producaoProdutosArtisticos."   Revista: ".
		$this->relatorio->producaoRevistas."\n".
		"Produto Audiovisual - DVD".$this->relatorio->producaoAudiovisualDVDs."   Artigo: ".
		$this->relatorio->producaoArtigos."\n".
		"Produto Audiovisual - Outros: ".$this->relatorio->producaoAudiovisualOutros."\n".
		"Outros: ".$this->relatorio->producaoOutros."   ".$this->relatorio->producaoOutrosTexto."\n".
		"Detalhamento da Produção: ".$this->relatorio->producaoDetalhamento;
		$this->MultiCell(self::BLOCK_SIZE,1,$descricao,'LR','J');
		
		
		
		
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