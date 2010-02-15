<?php
/**
 * File containing the eZXHTMLXMLOutputRegression class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZXHTMLXMLOutputRegression extends ezpTestCase
{
    protected $insertDefaultData = false;

    public function __construct()
    {
        parent::__construct();
        $this->setName( __CLASS__ . " tests" );
    }

    /**
     * Regression in renderParagraph() after preserveWhiteSpace=false was removed.
     *
     * @link http://issues.ez.no/15888
     */
    public function testRenderParagraph()
    {
        require_once( 'kernel/common/template.php' );

        $XMLString = '<?xml version="1.0" encoding="utf-8"?> <section xmlns:image="http://ez.no/namespaces/ezpublish3/image/"
                    xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/"
                    xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/">
                    <paragraph><ul><li>
                    <paragraph><link target="_blank" url_id="296">Accéder à la plate-forme boursière</link></paragraph>
                    </li></ul></paragraph>
                    </section>';

        $outputHandler = new eZXHTMLXMLOutput( $XMLString, false );
        $result = $outputHandler->outputText();

        $expectedResult = ' 
<ul>

<li> <a href="/" target="_blank">Accéder à la plate-forme boursière</a> </li>

</ul>
 ';

        $this->assertEquals( $expectedResult, $result );
    }
}