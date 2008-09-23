<?php

require_once 'Proexc/Db/Table.php';

class ProjetoParceiro extends Proexc_Db_Table {
	protected $_name = 'projeto_parceiro';

	protected $_referenceMap = array(
		'Projeto' => array(
			'columns'		=> 'idProjeto',
			'refTableClass'	=> 'Projeto',
			'refcolumns'	=> 'id'
		),
		'Parceiro' => array(
			'columns'		=> 'idParceiro',
			'refTableClass'	=> 'Parceiro',
			'refcolumns'	=> 'id'
		),
	);
}