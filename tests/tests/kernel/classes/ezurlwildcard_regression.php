<?php
/**
 * File containing the eZURLWildcardRegression class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
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
            'destination_url' => '/',
            'type' => eZURLWildcard::TYPE_DIRECT
            ) );
        $this->wildcard->store();
        eZURLWildcard::expireCache();
    }

    /**
     * Test case teardown
     */
    public function tearDown()
    {
        eZURLWildcard::removeByIDs( array( $this->wildcard->attribute( 'id' ) ) );
        eZURLWildcard::expireCache();
        clearstatcache();
        sleep( 1 );

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
        $this->assertTrue( eZURLWildcard::translate( $uri ) );
        $this->assertEquals( 'content/view/full/2', $uri );
    }
}
