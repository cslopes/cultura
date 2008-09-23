<?php

require_once 'Proexc/Db/Table.php';

class Titulacao extends Proexc_Db_Table {
	protected $_name = 'titulacao';

	protected $_referenceMap = array(
		'Usuario' => array(
			'columns'		=> 'login',
			'refTableClass'	=> 'Usuario',
			'refColumns'	=> 'login'
		),
	);
	
}