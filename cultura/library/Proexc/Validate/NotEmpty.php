<?php

	require_once('Proexc/Validate/NotEmpty.php');
	
	Class Proexc_Validate_NotEmpty extends Zend_Validate_NotEmpty {
	
	protected $_messageTemplates = array(
        self::IS_EMPTY => "Valor é vazio e isso não pode acontecer "
    );
	
	
	
	}