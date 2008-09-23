<?php

require_once 'Zend/Auth/Adapter/Interface.php';
require_once 'Zend/Http/Client.php';
require_once 'Zend/Http/Response.php';
require_once 'Zend/Auth/Result.php';

class Proexc_Auth_Adapter_Siga implements Zend_Auth_Adapter_Interface {

	// @todo mudar url
	const SIGA_URL = 'http://cursosiga.ufjf.br/index.php/common/auth';
	protected $_identity = null;
	protected $_credential = null;
	protected $_hash = null;
	protected $_return_to_ok = null;
	protected $_return_to_fail = null;
	
	public function __construct($username, $password, $hash, $ok = '', $fail = '') {
		$this->_identity   	   = $username;
		$this->_credential 	   = $password;
		$this->_hash	   	   = $hash;
		$this->_return_to_ok   = $ok;
		$this->_return_to_fail = $fail;
	}
	
	public function authenticate() {
		$client = new Zend_Http_Client(self::SIGA_URL);
		$client->setParameterPost('uid', $this->_identity);
		$client->setParameterPost('pwd', $this->_credential);
		$client->setParameterPost('hash', $this->_hash);
		$client->setParameterPost('return_to_ok', $this->_return_to_ok);
		$client->setParameterPost('return_to_fail', $this->_return_to_fail);
		$response = $client->request(Zend_Http_Client::POST);
		
		$code = Zend_Auth_Result::FAILURE;
		$messages[] = 'Falha na autorização';
		if ($response->isSuccessful()) {
			$code = Zend_Auth_Result::SUCCESS;
			$messages[] = 'Autorização válida';
		}
		return new Zend_Auth_Result($code, $this->_identity, $messages);
	}
}