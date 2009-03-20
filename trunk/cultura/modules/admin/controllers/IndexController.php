<?php

require_once 'Proexc/Admin/Controller/Action.php';

require_once 'Projeto.php';
require_once 'FormularioProjeto.php';
require_once 'JustificativasBolsas.php';

class Admin_IndexController extends Proexc_Admin_Controller_Action {

	function preDispatch() {
		parent::preDispatch();
		parent::checkAccess('index', null);
	}
    function indexAction()
    {
    	$this->view->title = 'Admin';
    }
    
    function justBolsasAction() {
	$this->_helper->viewRenderer->setNoRender(true);

		$tabProjeto = new Projeto();
		$projeto = $tabProjeto->fetchAll();
		$this->view->projeto = $projeto;

		$justificativas = new JustificativasBolsas();
		$justificativas->Output('justificativas.pdf', 'D');
	
}
    

}