<?php

require_once 'Proexc/Db/Table.php';

class Agenda extends Proexc_Db_Table {
	protected $_name 			= 'agenda';
	
	protected $_referenceMap = array(
		'Usuario' => array(
			'columns'		=> 'login',
			'refTableClass'	=> 'Usuario',
			'refColumns'	=> 'login'
		),
	);
	
	/**
	 * Retorna conjunto de agendas por titulo
	 *
	 * @param String $nome
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function findByTitulo($titulo) {
		$titulo = "%" . $titulo . "%";
		$where = $this->getAdapter()->quoteInto("titulo LIKE ?", $titulo);
		return $this->fetchAll($where);
	}
	
}