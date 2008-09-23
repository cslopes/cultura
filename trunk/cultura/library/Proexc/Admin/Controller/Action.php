<?php

require_once 'Zend/Acl.php';
require_once 'Zend/Registry.php';

class Proexc_Admin_Controller_Action extends Zend_Controller_Action {
	protected $user;

	function init() {
		$this->initView();
		$this->view->baseUrl = $this->_request->getBaseUrl();
		$this->user = Zend_Auth::getInstance()->getIdentity();
		$this->view->user = $this->user;
	}

	function preDispatch() {
		$auth = Zend_Auth::getInstance();
		$acl = Zend_Registry::get('acl');
		if ((!$auth->hasIdentity()) || (!$acl->isAllowed($this->user->role, 'index'))) {
			if($auth->hasIdentity())
				$auth->getStorage()->clear();
			$this->_redirect('admin/auth/login');
		}
	}
	
	/**
	 * Verifica se a role do usuário logado tem acesso ao recurso $resource e
	 * privilégio $privilege. Se não tem acesso redireciona para página de erro
	 *
	 * @param Zend_Acl_Resource_Interface|string $resource
	 * @param string $privilege
	 */
	function checkAccess($resource, $privilege) {
		$acl = Zend_Registry::get('acl');
		if(!$acl->isAllowed($this->user->role, $resource, $privilege))
			$this->_redirect('admin/error/permissionDenied');
	}

}