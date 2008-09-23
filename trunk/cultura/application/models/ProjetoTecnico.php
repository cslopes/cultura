<?php

require_once 'Proexc/Db/Table.php';

class ProjetoTecnico extends Proexc_Db_Table {
	protected $_name = 'projeto_tecnico';

	protected $_referenceMap = array(
	'Projeto' => array(
	'columns'		=> 'idProjeto',
	'refTableClass'	=> 'Projeto',
	'refcolumns'	=> 'id'
		),
	'Tecnico' => array(
	'columns'		=> 'idTecnico',
	'refTableClass'	=> 'Tecnico',
	'refcolumns'	=> 'id'
		),
	);

	/**
	 * Retorna um coordenador técnico do projeto
	 *
	 * @param int $idProjeto
	 * @return Zend_Db_Row_Abstract | false retorna a linha do coordenador
	 */
	public function findCoordenadorTecnico($idProjeto) {
		$where = array( 
			'idProjeto = ?'	=> $idProjeto,
			'funcao = ?'	=> 'coordenador'
		);
		return $this->fetchRow($where);
	}

	/**
	 * Verifica se já existe coordenador técnico para este projeto
	 *
	 * @param int $idProjeto
	 * @return boolean true se existe coordenador tecnico para o projeto
	 */
	public function hasCoordenadorTecnico($idProjeto) {
		return $this->findCoordenadorTecnico($idProjeto) ? true : false;
	}
}