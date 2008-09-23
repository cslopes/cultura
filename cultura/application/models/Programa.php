<?php

require_once 'Proexc/Db/Table.php';

class Programa extends Proexc_Db_Table {
	protected $_name = 'programa';
	
	protected $_referenceMap = array(
		'Usuario' => array(
			'columns'		=> 'login',
			'refTableClass'	=> 'Usuario',
			'refColumns'	=> 'login'
		),
	);
	
}