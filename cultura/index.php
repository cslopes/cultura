<?php
error_reporting(E_ALL|E_STRICT);
date_default_timezone_set('America/Sao_Paulo');

set_include_path('.' . PATH_SEPARATOR . './library/'
	 . PATH_SEPARATOR . './application/models/'
	 . PATH_SEPARATOR . './application/lib/'
	 . PATH_SEPARATOR . get_include_path());
include "Zend/Loader.php";

Zend_Loader::loadClass('Zend_Controller_Front');
Zend_Loader::loadClass('Zend_Registry');
Zend_Loader::loadClass('Zend_View');
Zend_Loader::loadClass('Zend_Config_Ini');
Zend_Loader::loadClass('Zend_Db');
Zend_Loader::loadClass('Zend_Db_Table');
Zend_Loader::loadClass('Zend_Debug');
Zend_Loader::loadClass('Zend_Auth');
Zend_Loader::loadClass('Zend_Acl');
Zend_Loader::loadClass('Zend_Acl_Role');
Zend_Loader::loadClass('Zend_Acl_Resource');
Zend_Loader::loadClass('Zend_Translate');
Zend_Loader::loadClass('Zend_Validate');
Zend_Loader::loadClass('Zend_Locale');
//Zend_Loader::loadClass('Zend_Session');

//Zend_Session::rememberMe(2);

// Zend Acl
// Define os acessos na área administrativa
$acl = new Zend_Acl();

$acl->addRole(new Zend_Acl_Role('admin'))
	->addRole(new Zend_Acl_Role('manager'));
	
$acl->add(new Zend_Acl_Resource('agenda'))
	->add(new Zend_Acl_Resource('areaTematica'))
	->add(new Zend_Acl_Resource('convenio'))
	->add(new Zend_Acl_Resource('curso'))
	->add(new Zend_Acl_Resource('departamento'))
	->add(new Zend_Acl_Resource('evento'))
	->add(new Zend_Acl_Resource('linhaAtuacao'))
	->add(new Zend_Acl_Resource('programa'))
	->add(new Zend_Acl_Resource('projeto'))
	->add(new Zend_Acl_Resource('titulacao'))
	->add(new Zend_Acl_Resource('unidade'))
	->add(new Zend_Acl_Resource('usuario'))
	->add(new Zend_Acl_Resource('index'));
	
$acl->allow('admin');

$acl->allow('manager');
$acl->deny('manager', 'usuario');

Zend_Registry::set('acl', $acl);





// load configuration
$config = new Zend_Config_Ini('./application/config.ini', 'general');
Zend_Registry::set('config', $config);


// setup database
$db = Zend_Db::factory($config->database);
Zend_Db_Table::setDefaultAdapter($db);
Zend_Registry::set('db', $db);

// setup controller
$frontController = Zend_Controller_Front::getInstance();
$frontController->throwExceptions(true);
$frontController->setControllerDirectory('./application/controllers');
$frontController->addControllerDirectory('./modules/admin/controllers', 'admin');

// run!
$frontController->dispatch();
