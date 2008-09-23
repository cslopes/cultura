<?php

require_once 'Proexc/Db/Table.php';

class ColaboradorDocente extends Proexc_Db_Table {
	protected $_name = 'colaboradordocente';

	protected $_referenceMap 	= array(
		'Departamento' => array(
			'columns' 		=> 'idDepartamento',
			'refTableClass'	=> 'Departamento',
			'refColumns'	=> 'id'
		)
	);
	
	function fetchColaboradoresDocentesByProjeto($idProjeto) {
		$adapter = $this->getAdapter();
		$adapter->setFetchMode(Zend_Db::FETCH_OBJ);
		$sql = "SELECT colaboradordocente.* FROM projeto_colaboradordocente " .
			   "LEFT JOIN colaboradordocente ON projeto_colaboradordocente.idColaboradorDocente = colaboradordocente.id " .
			   "WHERE projeto_colaboradordocente.idProjeto = ?";
		return $adapter->fetchAll($sql, (int) $idProjeto);
	}

	function fetchColaboradoresDocentesByCurso($idCurso) {
		$adapter = $this->getAdapter();
		$adapter->setFetchMode(Zend_Db::FETCH_OBJ);
		$sql = "SELECT colaboradordocente.* FROM curso_colaboradordocente " .
			   "LEFT JOIN colaboradordocente ON curso_colaboradordocente.idColaboradorDocente = colaboradordocente.id " .
			   "WHERE curso_colaboradordocente.idCurso = ?";
		return $adapter->fetchAll($sql, (int) $idCurso);
	}
}