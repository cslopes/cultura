<?php

require_once 'Proexc/Db/Table.php';

class Curso extends Proexc_Db_Table {
	protected $_name = 'curso';

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

	/**
	 * Returns a set of curso by idCoordenador
	 *
	 * @param int $idCoordenador
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function fetchByCoordenador($idCoordenador) {
		$where = $this->getAdapter()->quoteInto('idCoordenador = ?', $idCoordenador);
		return $this->fetchAll($where);
	}
	
	/**
	 * Returns a set of unvalidated curso 
	 *
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function fetchUnvalidated() {
		$where = 'processo is NULL';
		return $this->fetchAll($where, 'modificadoEm DESC');
	}
	
	/**
	 * Returns a set of closed and unvalidated curso 
	 *
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function fetchClosedAndUnvalidated() {
		$where[] = 'processo is NULL';
		$where[] = 'fechado = 1';
		return $this->fetchAll($where, 'modificadoEm DESC');
	}
	
	/**
	 * Returns a set of unvalidated curso by idCoordenador
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
	 * Returns a set of closed and unvalidated curso by idCoordenador
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
	 * Returns a set of open and unvalidated curso by idCoordenador
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
	 * Returns a set of open and validated curso by idCoordenador
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
	 * Returns a set of validated curso by idCoordenador
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
	 * Returns a set of closed curso by idCoordenador
	 *
	 * @param int $idCoordenador
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function fetchClosedByCoordenador($idCoordenador) {
		$where[] = $this->getAdapter()->quoteInto('idCoordenador = ?', $idCoordenador);
		$where[] = "fechado = 1"; 
		return $this->fetchAll($where);
	}
	
	/**
	 * Returns a set of validated curso by nome
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
	
	/**
	 * Returns a set of validated and unvalidated cursos by titulo
	 *
	 * @param String $titulo
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function findCursoByTitulo($titulo) {
			$titulo = "%" . $titulo . "%";
			$where[] = $this->getAdapter()->quoteInto('titulo LIKE ?', $titulo);
			return $this->fetchAll($where);
		}
	
	
	
	/**
	 * Returns a set of validated curso by resumo
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