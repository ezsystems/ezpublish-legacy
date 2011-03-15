<?php
/**
 * File containing the eZKernelTestSuite class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZKernelContentTestSuite extends ezpDatabaseTestSuite
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZ Publish Kernel Content Module Test Suite" );

        $this->addTestSuite( 'ezpContentPublishingBehaviourTest' );
        $this->addTestSuite( 'eZContentOperationDeleteObjectRegression' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>
