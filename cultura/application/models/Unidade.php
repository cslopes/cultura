<?php

require_once 'Proexc/Db/Table.php';

class Unidade extends Proexc_Db_Table {
	protected $_name = 'unidade';

	protected $_referenceMap = array(
		'Usuario' => array(
			'columns'		=> 'login',
			'refTableClass'	=> 'Usuario',
			'refColumns'	=> 'login'
		),
	);
	
}