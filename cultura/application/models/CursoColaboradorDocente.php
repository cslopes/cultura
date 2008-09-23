<?php

require_once 'Proexc/Db/Table.php';

class CursoColaboradorDocente extends Proexc_Db_Table {
	protected $_name = 'curso_colaboradordocente';

	protected $_referenceMap = array(
		'Curso' => array(
			'columns'		=> 'idCurso',
			'refTableClass'	=> 'Curso',
			'refcolumns'	=> 'id'
		),
		'ColaboradorDocente' => array(
			'columns'		=> 'idColaboradorDocente',
			'refTableClass'	=> 'ColaboradorDocente',
			'refcolumns'	=> 'id'
		),
	);
}