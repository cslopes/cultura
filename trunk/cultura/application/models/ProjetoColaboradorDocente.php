<?php

require_once 'Proexc/Db/Table.php';

class ProjetoColaboradorDocente extends Proexc_Db_Table {
	protected $_name = 'projeto_colaboradordocente';

	protected $_referenceMap = array(
		'Projeto' => array(
			'columns'		=> 'idProjeto',
			'refTableClass'	=> 'Projeto',
			'refcolumns'	=> 'id'
		),
		'ColaboradorDocente' => array(
			'columns'		=> 'idColaboradorDocente',
			'refTableClass'	=> 'ColaboradorDocente',
			'refcolumns'	=> 'id'
		),
	);
}