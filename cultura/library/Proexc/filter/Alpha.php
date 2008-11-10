<?php


require_once 'Zend/Filter/Alpha.php';



class Proexc_Filter_Alpha extends Zend_Filter_Alpha

{
    public $allowWhiteSpace;

    protected static $_unicodeEnabled;

    public function __construct($allowWhiteSpace = false)
    {
        $this->allowWhiteSpace = (boolean) $allowWhiteSpace;
        if (null === self::$_unicodeEnabled) {
            self::$_unicodeEnabled = (@preg_match('/\pL/u', 'a')) ? true : false;
        }
    }

    public function filter($value)
    {
        $whiteSpace = $this->allowWhiteSpace ? '\s' : '';
        if (!self::$_unicodeEnabled) {
            
            $pattern = '/[^a-zA-Z' . $whiteSpace . ']/';
        } else {
            $pattern = '/[^\p{L}' . $whiteSpace . ']/u';
        }

        return preg_replace($pattern, '', (string) $value);
    }
}
