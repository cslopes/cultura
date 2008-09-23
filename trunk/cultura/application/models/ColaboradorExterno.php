<?php

require_once 'Proexc/Db/Table.php';

class ColaboradorExterno extends Proexc_Db_Table {
	protected $_name = 'colaboradorexterno';

	function fetchColaboradoresExternosByProjeto($idProjeto) {
		$adapter = $this->getAdapter();
		$adapter->setFetchMode(Zend_Db::FETCH_OBJ);
		$sql = "SELECT colaboradorexterno.* FROM projeto_colaboradorexterno " .
			   "LEFT JOIN colaboradorexterno ON projeto_colaboradorexterno.idColaboradorExterno = colaboradorexterno.id " .
			   "WHERE projeto_colaboradorexterno.idProjeto = ?";
		return $adapter->fetchAll($sql, (int) $idProjeto);
	}
	
}