<?php

require_once 'Proexc/Db/Table.php';

class Evento extends Proexc_Db_Table {
	protected $_name = 'evento';

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
		'Recursos' => array(
			'columns'		=> 'idRecursos',
			'refTableClass'	=> 'Recursos',
			'refcolumns'	=> 'id'
		)
	);
	
	const CARATER_LOCAL			= "Local";
	const CARATER_REGIONAL		= "Regional";
	const CARATER_NACIONAL 		= "Nacional";
	const CARATER_INTERNACIONAL	= "Internacional";

	/**
	 * Returns a set of evento by idCoordenador
	 *
	 * @param int $idCoordenador
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function fetchByCoordenador($idCoordenador) {
		$where = $this->getAdapter()->quoteInto('idCoordenador = ?', $idCoordenador);
		return $this->fetchAll($where);
	}
	
	/**
	 * Returns a set of unvalidated evento 
	 *
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function fetchUnvalidated() {
		$where = 'processo is NULL';
		return $this->fetchAll($where, 'modificadoEm DESC');
	}
	
	/**
	 * Returns a set of closed and unvalidated evento 
	 *
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function fetchClosedAndUnvalidated() {
		$where[] = 'processo is NULL';
		$where[] = 'fechado = 1';
		return $this->fetchAll($where, 'modificadoEm DESC');
	}
	
	/**
	 * Returns a set of unvalidated evento by idCoordenador
	 *
	 * @param int $idCoordenador
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function fetchUnvalidatedByCoordenador($idCoordenador) {
		$where[] = $this->getAdapter()->quoteInto('idCoordenador = ?', $idCoordenador);
		$where[] = 'processo is NULL';
		return $this->fetchAll($where);
	}
	
	/**
	 * Returns a set of unvalidated and closed evento by idCoordenador
	 *
	 * @param int $idCoordenador
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function fetchClosedAndUnvalidatedByCoordenador($idCoordenador) {
		$where[] = $this->getAdapter()->quoteInto('idCoordenador = ?', $idCoordenador);
		$where[] = 'processo is NULL';
		$where[] = 'fechado = 1';
		return $this->fetchAll($where);
	}
	
	/**
	 * Returns a set of unvalidated and open evento by idCoordenador
	 *
	 * @param int $idCoordenador
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function fetchOpenAndUnvalidatedByCoordenador($idCoordenador) {
		$where[] = $this->getAdapter()->quoteInto('idCoordenador = ?', $idCoordenador);
		$where[] = 'processo is NULL';
		$where[] = 'fechado = 0';
		return $this->fetchAll($where);
	}
	
	/**
	 * Returns a set of validated and open evento by idCoordenador
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
	 * Returns a set of validated evento by idCoordenador
	 *
	 * @param int $idCoordenador
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function fetchValidatedByCoordenador($idCoordenador) {
		$where[] = $this->getAdapter()->quoteInto('idCoordenador = ?', $idCoordenador);
		$where[] = 'processo IS NOT NULL';
		return $this->fetchAll($where);
	}

	/**
	 * Returns a set of closed evento by idCoordenador
	 *
	 * @param int $idCoordenador
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function fetchClosedByCoordenador($idCoordenador) {
		$where[] = $this->getAdapter()->quoteInto('idCoordenador = ?', $idCoordenador);
		$where[] = 'fechado = 1';
		return $this->fetchAll($where);
	}

	/**
	 * Returns a set of validated evento by nome
	 *
	 * @param String $titulo
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function findByTitulo($titulo) {
		$titulo = "%" . $titulo . "%";
		$where[] = $this->getAdapter()->quoteInto('titulo LIKE ?', $titulo);
		$where[] = 'processo is NOT NULL';
		return $this->fetchAll($where);
	}
	
	/* * Returns a set of validated and unvalidated evento by nome
	 *
	 * @param String $titulo
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function findEventoByTitulo($titulo) {
		$titulo = "%" . $titulo . "%";
		$where[] = $this->getAdapter()->quoteInto('titulo LIKE ?', $titulo);
		return $this->fetchAll($where);
	}
	
	
	
	/**
	 * Returns a set of validated evento by resumo
	 *
	 * @param String $resumo
	 */
	public function findByResumo($resumo) {
		$resumo = "%" . $resumo . "%";
		$where[] = $this->getAdapter()->quoteInto('resumo LIKE ?', $resumo);
		$where[] = 'processo is NOT NULL';
		return $this->fetchAll($where);
	}
	
}