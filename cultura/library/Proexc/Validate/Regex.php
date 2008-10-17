<?php

//Classe que herda de Zend_Validate_Regex

require_once 'Zend/Validate/Abstract.php';
	
	class Proexc_Validate_Regex extends Zend_Validate_Regex {
	
	const NOT_MATCH = 'regexNotMatch';
	
	 protected $_messageTemplates = array(
        self::NOT_MATCH => "'ERRO : %value%' não esta no padrão esperado: '%pattern%'"
    );
	
	 protected $_messageVariables = array(
        'pattern' => '_pattern'
    );
	
	
	 public function isValid($value)
    {
        $valueString = (string) $value;

        $this->_setValue($valueString);

        $status = @preg_match($this->_pattern, $valueString);
        if (false === $status) {
            /**
             * @see Zend_Validate_Exception
             */
            require_once 'Zend/Validate/Exception.php';
            throw new Zend_Validate_Exception("ERRO: não está no padrão '$this->_pattern' o valor '$valueString'");
        }
        if (!$status) {
            $this->_error();
            return false;
        }
        return true;
    }



}


?>