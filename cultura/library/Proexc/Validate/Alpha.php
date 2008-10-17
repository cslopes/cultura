<?php

require_once 'Zend/Validate/Abstract.php';

/**
 * Implementação  para fazer a validação dos dados
 *
 */
class Proexc_Validate_Alpha extends Zend_Validate_Abstract {
	const NUMERIC = 'numeric';
	const LENGTH  = 'length';
	
	protected $_messageTemplates = array(
		self::NUMERIC	=>"ERRO: Não coloque  caracteres numéricos",
		self::LENGTH    =>"ERRO: O texto não deve ser vazio "
	);
	
	//método de validação 
	public function isValid($value) {
		$this->_setValue($value);
		
		$isValid = true;
		
		if (is_numeric($value)) {
			$this->_error(self::NUMERIC);
			$isValid = false;
		}
		if (strlen($value) == 0) {
			$this->_error(self::LENGTH);
			$isValid = false;
		}
		
		return $isValid;
	}
	

	

}