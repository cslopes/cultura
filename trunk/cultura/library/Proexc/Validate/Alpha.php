<?php

require_once 'Zend/Validate/Alpha.php';

/**
 * Implementação  para fazer a validação dos dados
 *
 */
class Proexc_Validate_Alpha extends Zend_Validate_Alpha {

	protected $_messageTemplates = array(
        self::NOT_ALPHA    => "'%value%' teste",
        self::STRING_EMPTY => "'%value%' teste"
    );

}