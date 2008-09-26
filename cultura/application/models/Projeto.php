<?php

require_once 'Proexc/Db/Table.php';

class Projeto extends Proexc_Db_Table {
	protected $_name = 'projeto';

	// mapeamentos
	protected $_referenceMap 	= array(
	'AreaTematica' => array(
	'columns' 		=> 'idAreaTematica',
	'refTableClass'	=> 'AreaTematica',
	'refColumns'	=> 'id'
		),
	'Coordenador' => array(
	'columns'		=> 'idCoordenador',
	'refTableClass'	=> 'Coordenador',
	'refcolumns'	=> 'id'
		),
	'LinhaAtuacao' => array(
	'columns'		=> 'idLinhaAtuacao',
	'refTableClass'	=> 'LinhaAtuacao',
	'refcolumns'	=> 'id'
		),
	'Programa' => array(
	'columns'		=> 'idPrograma',
	'refTableClass'	=> 'Programa',
	'refcolumns'	=> 'id'
		),
	'Recursos' => array(
	'columns'		=> 'idRecursos',
	'refTableClass'	=> 'Recursos',
	'refcolumns'	=> 'id'
		),
	'ViceCoordenador' => array(
	'columns'		=> 'idViceCoordenador',
	'refTableClass'	=> 'Coordenador',
	'refcolumns'	=> 'id'
		)
	);

	/**
	 * Returns a set of projeto by idCoordenador
	 *
	 * @param int $idCoordenador
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function fetchByCoordenador($idCoordenador) {
		$where = $this->getAdapter()->quoteInto('idCoordenador = ?', $idCoordenador);
		return $this->fetchAll($where);
	}
	
	/**
	 * Returns a set of unvalidated projeto 
	 *
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function fetchUnvalidated() {
		$where = 'processo is Null';
		return $this->fetchAll($where, 'modificadoEm DESC');
	}
	
	/**
	 * Return a set of unvalidated and closed projeto
	 * 
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function fetchClosedAndUnvalidated() {
		$where[] = 'processo IS NULL';
		$where[] = 'fechado = 1';
		return $this->fetchAll($where, 'modificadoEm DESC');
	}
	
	/**
	 * Returns a set of unvalidated projeto by idCoordenador
	 *
	 * @param int $idCoordenador
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function fetchUnvalidatedByCoordenador($idCoordenador) {
		$where[] = $this->getAdapter()->quoteInto('idCoordenador = ?', $idCoordenador);
		$where[] = 'processo is Null';
		return $this->fetchAll($where);
	}
	
	/**
	 * Returns a set of closed and unvalidated projeto by idCoordenador
	 *
	 * @param int $idCoordenador
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function fetchClosedAndUnvalidatedByCoordenador($idCoordenador) {
		$where[] = $this->getAdapter()->quoteInto('idCoordenador = ?', $idCoordenador);
		$where[] = 'processo IS NULL';
		$where[] = 'fechado = 1';
		return $this->fetchAll($where);
	}
	
	/**
	 * Returns a set of open and unvalidated projeto by idCoordenador
	 *
	 * @param int $idCoordenador
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function fetchOpenAndUnvalidatedByCoordenador($idCoordenador) {
		$where[] = $this->getAdapter()->quoteInto('idCoordenador = ?', $idCoordenador);
		$where[] = 'processo IS NULL';
		$where[] = 'fechado = 0';
		return $this->fetchAll($where);
	}
	
	/**
	 * Returns a set of open and validated projeto by idCoordenador
	 *
	 * @param int $idCoordenador
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function fetchOpenAndValidatedByCoordenador($idCoordenador) {
		$where[] = $this->getAdapter()->quoteInto('idCoordenador = ?', $idCoordenador);
		$where[] = 'processo IS NOT NULL';
		$where[] = 'fechado = 0';
		return $this->fetchAll($where);
	}
	
	/**
	 * Returns a set of validated projeto by idCoordenador
	 *
	 * @param int $idCoordenador
	 * @return Zend_Db_Table_Rowset_Abstract
	 * 
	 */
	public function fetchValidatedByCoordenador($idCoordenador) {
		$where[] = $this->getAdapter()->quoteInto('idCoordenador = ?', $idCoordenador);
		$where[] = 'processo IS NOT NULL';
		return $this->fetchAll($where);
	}
	
	/**
	 * Returns a set of closed projeto by idCoordenador
	 *
	 * @param int $idCoordenador
	 * @return Zend_Db_Table_Rowset_Abstract
	 * 
	 */
	public function fetchClosedByCoordenador($idCoordenador) {
		$where[] = $this->getAdapter()->quoteInto('idCoordenador = ?', $idCoordenador);
		$where[] = "fechado = 1";
		return $this->fetchAll($where, 'modificadoEm DESC');
	}

	/**
	 * Returns a set of validated projeto by nome
	 *
	 * @param String $titulo
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function findByTitulo($titulo) {
		$titulo = "%" . $titulo . "%";
		$where[] = $this->getAdapter()->quoteInto('titulo LIKE ?', $titulo);
		$where[] = 'processo IS NOT NULL';
		return $this->fetchAll($where);
	}
	
	/**
	 * Returns a set of validated projeto by resumo
	 *
	 * @param String $resumo
	 */
	public function findByResumo($resumo) {
		$resumo = "%" . $resumo . "%";
		$where[] = $this->getAdapter()->quoteInto('resumo LIKE ?', $resumo);
		$where[] = 'processo IS NOT NULL';
		return $this->fetchAll($where);
	}
	
}