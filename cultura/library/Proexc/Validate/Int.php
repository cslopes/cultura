<?php
//classe que herda da Zend_validate_int
	
require_once('Zend/Validate/Int.php');

class Proexc_Validate_Int extends Zend_Validate_Int{

	protected $_messageTemplates = array(
        self::NOT_INT => "'%value%' não aparenta ser um inteiro válido"
    );


}
