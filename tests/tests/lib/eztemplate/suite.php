<?php
/**
 * File containing the eZTemplateTestSuite class
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
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