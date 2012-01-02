<?php
/**
 * File containing the eZKernelTestSuite class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZDatatypeTestSuite extends ezpDatabaseTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZ Publish Datatypes Test Suite" );

        $this->addTestSuite( 'eZMatrixTypeTest' );
        $this->addTestSuite( 'eZStringTypeTest' );
        $this->addTestSuite( 'eZCountryTypeTest' );
        $this->addTestSuite( 'eZUserTest' );
//        $this->addTestSuite( 'eZLDAPUserTest' );
        $this->addTestSuite( 'eZTextFileUserTest' );
        $this->addTestSuite( 'eZEmailTypeTest' );
        $this->addTestSuite( 'eZXMLInputParserTest' );
        $this->addTestSuite( 'eZSimplifiedXMLInputParserRegression' );

        $this->addTestSuite( 'eZBinaryFileTypeRegression' );
        $this->addTestSuite( 'eZImageTypeRegression' );
        $this->addTestSuite( 'eZImageAliasHandlerRegression' );
        $this->addTestSuite( 'eZImageFileRegression' );
        $this->addTestSuite( 'eZMediaTypeRegression' );
        $this->addTestSuite( 'eZMultiPriceTypeRegression' );
        $this->addTestSuite( 'eZXMLTextRegression' );
        $this->addTestSuite( 'eZXMLInputParserRegression' );
        $this->addTestSuite( 'eZURLTypeRegression' );
        $this->addTestSuite( 'eZUserTypeRegression' );
        $this->addTestSuite( 'eZXHTMLXMLOutputRegression' );
        $this->addTestSuite( 'eZXMLTextTest' );

        $this->addTestSuite( 'eZObjectRelationListDatatypeRegression' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>
