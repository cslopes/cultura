<?php
//classe que herda da Zend_validate_int

class Proexc_Validate_int extends Zend_Validate_Int{

	const NOT_INT = 'notInt';
	
	protected $_messageTemplates = array(
        self::NOT_INT => "'%value%' não aparenta ser um inteiro válido"
    );


}

?>