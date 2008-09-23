<?php

require_once 'Proexc/Db/Table.php';

class LinhaAtuacao extends Proexc_Db_Table {
	protected $_name = 'linhaatuacao';

	protected $_referenceMap = array(
		'Usuario' => array(
			'columns'		=> 'login',
			'refTableClass'	=> 'Usuario',
			'refColumns'	=> 'login'
		),
	);
	
}