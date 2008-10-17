﻿<?php
	//classe que herda de EmailAddress
	
	require_once 'Zend/Validate/Abstract.php';
	
	class Proexc_Validate_EmailAddress extends Zend_Validate_EmailAddress{
	
	const INVALID            = 'emailAddressInvalid';
    const INVALID_HOSTNAME   = 'emailAddressInvalidHostname';
    const INVALID_MX_RECORD  = 'emailAddressInvalidMxRecord';
    const DOT_ATOM           = 'emailAddressDotAtom';
    const QUOTED_STRING      = 'emailAddressQuotedString';
    const INVALID_LOCAL_PART = 'emailAddressInvalidLocalPart';

	
	protected $_messageTemplates = array(
        self::INVALID            => "'%value%' não é um endereço de email válido no formato local-part@hostname",
        self::INVALID_HOSTNAME   => "'%hostname%' não é um hostname válido para o email '%value%'",
        self::INVALID_MX_RECORD  => "'%hostname%' não aparenta ter um registro válido para o email '%value%'",
        self::DOT_ATOM           => "'%localPart%' falta o ponto",
        self::QUOTED_STRING      => "'%localPart%' não está num formato de string válido",
        self::INVALID_LOCAL_PART => "'%localPart%' não é uma parte local válida para o email '%value%'"
    );
	
	}


?>