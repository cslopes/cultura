<?php

	class Proexc_Validate_Date extends Zend_Validate_Abstract {
	
	
	const INVALID = "dataInvalida";
	const FALSEfORMAT = "falsoformatodedata";
	const NOT_YYY_MM_DD = "NãoEstaNoFormatoYYYMMDD";
	
	
	protected $_locale;
	
	protected $_messageTemplates = array(
        self::NOT_YYYY_MM_DD => "'%value%' não está no formato YYYY-MM-DD",
        self::INVALID        => "'%value%' não aparenta estar em algum formato válido",
        self::FALSEFORMAT    => "'%value%' does not fit given date format"
    );
	
	


	
	
	
	}


?>