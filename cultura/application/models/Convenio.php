<?php

require_once 'Proexc/Db/Table.php';


class Convenio extends Proexc_Db_Table {
	protected $_name 			= 'convenio';
	
	protected $_referenceMap = array(
		'Usuario' => array(
			'columns'		=> 'login',
			'refTableClass'	=> 'Usuario',
			'refColumns'	=> 'login'
		),
	);
	
	/**
	 * Retorna conjunto de convenios por nome
	 *
	 * @param String $nome
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function findByNome($nome) {
		$nome = "%" . $nome . "%";
		$where = $this->getAdapter()->quoteInto("nome LIKE ?", $nome);
		return $this->fetchAll($where,'nome');
	}
	
	/**
	 * Retorna os anos em que há convenios encerrando.
	 *
	 */
	public function getYears(){
		$stmt = $this->getAdapter()->query("SELECT DISTINCT EXTRACT(YEAR FROM dataFinal)FROM convenio WHERE dataFinal IS NOT NULL ORDER BY dataFinal ASC ") ;
		return $stmt->fetchAll();	
		/**/
	}

	public function findFinishConvenios($ano,$mes){
		$where[] = $this->getAdapter()->quoteInto("EXTRACT(YEAR FROM dataFinal) = ?",$ano);
		$where[] = $this->getAdapter()->quoteInto("EXTRACT(MONTH FROM dataFinal) = ?",$mes);
		return $this->fetchAll($where,'dataFinal ASC');
		}
}
