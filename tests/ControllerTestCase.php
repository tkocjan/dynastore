<?php
//require_once 'Zend/Application.php';
//require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

class ControllerTestCase extends Zend_Test_PHPUnit_ControllerTestCase
{
    public $application;

    public function setUp()
    {
        error_log(__METHOD__.": entry");
        $this->application = new Zend_Application(
            'testing',
            APPLICATION_PATH . '/configs/application.ini'
        );

        $this->bootstrap = array($this, 'appBootstrap');
        parent::setUp();
    }

    public function tearDown()
    {
        $this->resetRequest()->resetResponse();
        $this->request->setPost(array());
        $this->request->setQuery(array());
    }

    public function appBootstrap()
    {
        error_log(__METHOD__.": entry");
        $this->application->bootstrap();
    }
}
