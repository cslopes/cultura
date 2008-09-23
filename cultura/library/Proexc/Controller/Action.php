<?php

class Proexc_Controller_Action extends Zend_Controller_Action {
	protected $user;

	function init() {
		$this->initView();
		$this->view->baseUrl = $this->_request->getBaseUrl();
		$this->user = Zend_Auth::getInstance()->getIdentity();
		$this->view->user = $this->user;
	}

	function preDispatch() {
		$auth = Zend_Auth::getInstance();
		if (!$auth->hasIdentity()) {
			$this->_redirect('auth/login');
		}
	}

}