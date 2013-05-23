<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
define('TESTS_PATH', dirname(__FILE__));

// Define application environment
define('APPLICATION_ENV', 'testing');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../../zend'),
    realpath(APPLICATION_PATH . '/../library'),
    realpath(TESTS_PATH),
    get_include_path(),
)));

_SF_Autloader_SetUp();

Zend_Session::$_unitTestEnabled = true;
Zend_Session::start();

function _SF_Autloader_SetUp() {
    require_once 'Zend/Loader/Autoloader.php';
    $loader = Zend_Loader_Autoloader::getInstance();
    $loader->registerNamespace('SF_');
    
    $loader->setFallbackAutoloader(true);
}

function _SF_Autloader_TearDown() {
    Zend_Loader_Autoloader::resetInstance();
    $loader = Zend_Loader_Autoloader::getInstance();
    $loader->registerNamespace('SF_');
    
    $loader->setFallbackAutoloader(true);
}
