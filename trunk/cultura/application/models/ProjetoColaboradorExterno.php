<?php

require_once 'Proexc/Db/Table.php';

class ProjetoColaboradorExterno extends Proexc_Db_Table {
	protected $_name = 'projeto_colaboradorexterno';

	protected $_referenceMap = array(
		'Projeto' => array(
			'columns'		=> 'idProjeto',
			'refTableClass'	=> 'Projeto',
			'refcolumns'	=> 'id'
		),
		'ColaboradorExterno' => array(
			'columns'		=> 'idColaboradorExterno',
			'refTableClass'	=> 'ColaboradorExterno',
			'refcolumns'	=> 'id'
		),
	);
}