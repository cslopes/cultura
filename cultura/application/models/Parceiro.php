<?php

require_once 'Proexc/Db/Table.php';

class Parceiro extends Proexc_Db_Table {
	protected $_name = 'parceiro';
	
	function fetchParceirosByProjeto($idProjeto) {
		$adapter = $this->getAdapter();
		$adapter->setFetchMode(Zend_Db::FETCH_OBJ);
		$sql = "SELECT parceiro.* FROM projeto_parceiro " .
			   "LEFT JOIN parceiro ON projeto_parceiro.idParceiro = parceiro.id " .
			   "WHERE projeto_parceiro.idProjeto = ?";
		return $adapter->fetchAll($sql, (int) $idProjeto);
	}
}