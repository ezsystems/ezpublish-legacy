<?php
require_once 'Testing/Selenium.php';
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

PHPUnit_Util_Filter::addFileToFilter(__FILE__, 'PHPUNIT');

abstract class neoSeleniumTestCase extends PHPUnit_Extensions_SeleniumTestCase
{
    public function __construct()
    {
        parent::__construct();
    } 

    public function setUp()
    {
        $this->setBrowser("*firefox");
        $this->setBrowserUrl("http://");
        $this->setHost("");
        $this->setPort(4444);

    }


    public function waitForPageToLoad($time)
    {
        $this->__call("waitForPageToLoad", array($time));

        $this->assertFalse($this->isTextPresent("ezcTemplateException"), "Found an ezcTemplateException");
        $this->assertFalse($this->isTextPresent("ezcTemplateRuntimeException"), "Found an ezcTemplateRuntimeException");
        $this->assertFalse($this->isTextPresent("runtime_error"), "Found a runtime exception");
    }


}


?>
