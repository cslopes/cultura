<?php

require_once 'Proexc/Db/Table.php';

class AreaTematica extends Proexc_Db_Table {
	protected $_name 			= 'areatematica';
	
	protected $_referenceMap = array(
		'Usuario' => array(
			'columns'		=> 'login',
			'refTableClass'	=> 'Usuario',
			'refColumns'	=> 'login'
		),
	);
	
}