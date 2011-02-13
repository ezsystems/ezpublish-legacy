<?php
/**
 * File containing the eZURLWildcardRegression class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZURLWildcardRegression extends ezpDatabaseTestCase
{

    /**
     * Wildcard used for the tests.
     * @var eZURLWildcard
     */
    protected $wildcard;

    /**
     * Test case setup
     */
    public function setUp()
    {
        parent::setUp();

        $this->wildcard = new eZURLWildcard( array(
            'source_url' => 'MyRootURL',
            'destination_url' => 'content/view/full/2',
            'type' => $type = eZURLWildcard::TYPE_DIRECT
            ) );
        $this->wildcard->store();
    }

    /**
     * Test case teardown
     */
    public function tearDown()
    {
        eZURLWildcard::removeByIDs( array( $this->wildcard->attribute( 'id' ) ) );
        parent::tearDown();
    }

    /**
     * Tests that wildcard matching is case insensitive.
     *
     * @link http://issues.ez.no/17524
     */
    public function testCaseInsensitiveMatch()
    {
        $uri = 'myROOTurl';
        eZURLWildcard::translate( $uri );

        $this->assertEquals( 'content/view/full/2', $uri );
    }
}
