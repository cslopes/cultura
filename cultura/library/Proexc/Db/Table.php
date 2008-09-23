<?php

require_once 'Zend/Db/Table/Abstract.php';

class Proexc_Db_Table extends Zend_Db_Table_Abstract {

	public function deleteById($id) {
		$where = $this->_db->quoteInto("id = ?", $id);
		parent::delete($where);
	}

	public function updateById(array $data, $id) {
		$where = $this->_db->quoteInto("id = ?", $id);
		parent::update($data, $where);
	}

}