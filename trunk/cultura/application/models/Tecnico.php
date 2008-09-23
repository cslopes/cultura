<?php

require_once 'Proexc/Db/Table.php';

class Tecnico extends Proexc_Db_Table {
	protected $_name = 'tecnico';

	protected $_referenceMap = array(
	'Departamento' => array(
	'columns'		=> 'idDepartamento',
	'refTableClass'	=> 'Departamento',
	'refColumns'	=> 'id'
		)
	);
	
	const COORDENADOR = 'coordenador';
	const COLABORADOR = 'colaborador';

	function fetchCoordenadoresTecnicosByProjeto($idProjeto) {
		$adapter = $this->getAdapter();
		$adapter->setFetchMode(Zend_Db::FETCH_OBJ);
		$sql = "SELECT tecnico.* FROM projeto_tecnico " .
			   "LEFT JOIN tecnico ON projeto_tecnico.idTecnico = tecnico.id " .
			   "WHERE projeto_tecnico.funcao = ? and projeto_tecnico.idProjeto = ?";
		return $adapter->fetchAll($sql, array(Tecnico::COORDENADOR, (int) $idProjeto));
	}
	
	function fetchColaboradoresTecnicosByProjeto($idProjeto) {
		$adapter = $this->getAdapter();
		$adapter->setFetchMode(Zend_Db::FETCH_OBJ);
		$sql = "SELECT tecnico.* FROM projeto_tecnico " .
			   "LEFT JOIN tecnico ON projeto_tecnico.idTecnico = tecnico.id " .
			   "WHERE projeto_tecnico.funcao = ? and projeto_tecnico.idProjeto = ?";
		return $adapter->fetchAll($sql, array(Tecnico::COLABORADOR, $idProjeto));
	}
}