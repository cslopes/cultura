<?php

require_once 'Proexc/Db/Table.php';

class Coordenador extends Proexc_Db_Table {
	protected $_name = 'coordenador';

	protected $_referenceMap 	= array(
		'Departamento' => array(
			'columns' 		=> 'idDepartamento',
			'refTableClass'	=> 'Departamento',
			'refColumns'	=> 'id'
		),
		'Titulacao' => array(
			'columns'		=> 'idTitulacao',
			'refTableClass'	=> 'Titulacao',
			'refColumns'	=> 'id'
		)
	);
	
	public function findBySiape($siape) {
		$where = $this->getAdapter()->quoteInto('siape = ?', $siape);
		return $this->fetchRow($where);
	}
	
	public function exists($siape) {
		return $this->findBySiape($siape) ? true : false;
	}
	
	public function fetchViceCoordenadores($siape) {
		$where = $this->getAdapter()->quoteInto('id <> ?', $siape);
		return $this->fetchAll($where,'nome ASC');
	}
}