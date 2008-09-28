<?php

require_once 'Proexc/Db/Table.php';

class Departamento extends Proexc_Db_Table {
	protected $_name = 'departamento';
	
	protected $_referenceMap = array(
		'Unidade' => array(
			'columns'		=> 'idUnidade',
			'refTableClass'	=> 'Unidade',
			'refColumns'	=> 'id'
		),
		'Usuario'	=> array(
			'columns'		=> 'login',
			'refTableClass'	=> 'Usuario',
			'refColumns'	=> 'login'
		)
	);
	
	public function fetchAllByUnidade($idUnidade) {
		$where = $this->getAdapter()->quoteInto('idUnidade = ?', $idUnidade);
		return $this->fetchAll($where, "nome");
	}
	
}

