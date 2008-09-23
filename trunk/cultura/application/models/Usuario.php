<?php

require_once 'Proexc/Db/Table.php';

class Usuario extends Proexc_Db_Table {
	protected $_name 			= 'usuario';
	
	/**
	 * Retorna conjunto de usuarios por nome
	 *
	 * @param String $nome
	 * @return Zend_Db_Table_Rowset_Abstract
	 */
	public function findByNome($nome) {
		$nome = "%" . $nome . "%";
		$where = $this->getAdapter()->quoteInto("nome LIKE ?", $nome);
		return $this->fetchAll($where);
	}
	
	/**
	 * Deleta o registro pelo login
	 *
	 * @param String $login
	 * @return int Funcionou
	 */
	public function deleteByLogin($login) {
		$where = $this->getAdapter()->quoteInto("login = ?", $login);
		return $this->delete($where);
	}
	
	/**
	 * Retorna usuario por login
	 *
	 * @param String $login
	 * @return 
	 */
	public function findByLogin($login) {
		$where = $this->getAdapter()->quoteInto("login = ?", $login);
		return $this->fetchRow($where);
	}
	
	/**
	 * Faz update pelo login
	 *
	 * @param array $data
	 * @param String $login
	 */
	public function updateByLogin(array $data, $login) {
		$where = $this->_db->quoteInto("login = ?", $login);
		parent::update($data, $where);
	}
		
}
