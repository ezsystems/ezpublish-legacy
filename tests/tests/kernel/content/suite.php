<?php
/**
 * File containing the eZKernelTestSuite class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
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
        $this->addTestSuite( 'eZContentOperationCollectionTest' );
    }

    public static function suite()
    {
        return new self();
    }
}

?>
