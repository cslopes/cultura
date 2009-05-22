<?php

	require_once ('Zend/Validate/Date.php');
	class Proexc_Validate_Date extends Zend_Validate_Date {
	
	protected $_messageTemplates = array(
//        self::NOT_YYYY_MM_DD => "'%value%' não está no formato DD/MM/AA ou está em branco.",
//        self::INVALID        => "'%value%' não aparenta estar em algum formato válido",
 //       self::FALSEFORMAT    => "'%value%' does not fit given date format"
 
		self::NOT_YYYY_MM_DD => "não está no formato DD/MM/AA ou está em branco.",
        self::INVALID        => "não aparenta estar em algum formato válido",
    );
	
	


	
	
	
	}
