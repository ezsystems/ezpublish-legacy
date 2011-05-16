<?php
/**
 * File containing the eZTemplateTestSuite class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZTemplateTestSuite extends ezpDatabaseTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZTemplateTestSuite Test Suite" );

        $this->addTestSuite( 'eZTemplateAttributeOperatorTest' );
        $this->addTestSuite( 'eZTemplateStringOperatorRegression' );
        $this->addTestSuite( 'eZTemplateTextOperatorRegression' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>
