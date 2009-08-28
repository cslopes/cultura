<?php

require_once 'Proexc/Db/Table.php';

class Disciplina extends Proexc_Db_Table {
	protected $_name = 'disciplina';
	
	protected $_referenceMap 	= array(
	'RelatorioFinal' => array(
	'columns' 		=> 'idRelatorioFinal',
	'refTableClass'	=> 'RelatorioFinal',
	'refColumns'	=> 'id'
		)
	);	

	public function fetchDisciplinasByRelatorio($idRelatorio){
		$where[] = $this->getAdapter()->quoteInto('idRelatorioFinal = ?', $idRelatorio);
		return $this->fetchAll($where);
	}
	
}