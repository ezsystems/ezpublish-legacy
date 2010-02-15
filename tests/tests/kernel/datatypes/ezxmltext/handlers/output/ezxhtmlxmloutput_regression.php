<?php
/**
 * File containing the eZXHTMLXMLOutputRegression class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZXHTMLXMLOutputRegression extends ezpDatabaseTestCase
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
        $XMLString = '<?xml version="1.0" encoding="utf-8"?> <section xmlns:image="http://ez.no/namespaces/ezpublish3/image/"
                    xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/"
                    xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/">
                    <paragraph><ul><li>
                    <paragraph><link target="_blank" url_id="296">Accéder à la plate-forme boursière</link></paragraph>
                    </li></ul></paragraph>
                    </section>';

        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->loadXML( $XMLString );
        $xpath = new DOMXpath( $dom );
        $element = $xpath->query( '/section/paragraph' )->item( 0 );

        $childrenOutput = array (
            0 =>
            array (
                0 => false,
                1 => '
            <ul>

            <li> <p>
            <a href="/" target="_blank">Accéder la plate-forme boursière</a>
            </p>
            </li>

            </ul>

            ',
            ),
        );

        $outputHandler = new eZXHTMLXMLOutput(
            $xmlData, false, null
        );

        $result = $outputHandler->renderParagraph(
            $element, $childrenOutput, array( 'classification' => '' )
        );

        $this->markTestIncomplete( 'DRAFT' );
    }
}