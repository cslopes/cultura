<?php

require_once 'Zend/Validate/Abstract.php';

/**
 * Implementação da Zend_Filter_Interface para fazer a validação do siape
 *
 */
class Proexc_Validate_Siape extends Zend_Validate_Abstract {
	const NUMERIC = 'numeric';
	const LENGTH  = 'length';
	
	protected $_messageTemplates = array(
		self::LENGTH 	=> "SIAPE de tamanho inválido",
		self::NUMERIC	=> "SIAPE deve conter apenas caracteres numéricos"
	);
	
	public function isValid($value) {
		$this->_setValue($value);
		
		$isValid = true;
		
		if (!is_numeric($value)) {
			$this->_error(self::NUMERIC);
			$isValid = false;
		}
		if (strlen($value) != 7) {
			$this->_error(self::LENGTH);
			$isValid = false;
		}
		
		return $isValid;
	}
}