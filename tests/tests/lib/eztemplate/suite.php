<?php
/**
 * File containing the eZTemplateTestSuite class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
        $this->addTestSuite( 'eZTemplateRegression' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>
