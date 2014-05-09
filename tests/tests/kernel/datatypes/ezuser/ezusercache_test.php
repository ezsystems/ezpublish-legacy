<?php
/**
 * File containing the eZUserCacheTest class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */

class eZUserCacheTest extends ezpDatabaseTestCase
{
    private $userId;

    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZUser Cache Unit Tests" );
    }

    public function setUp()
    {
        $this->userId = 12345;
        parent::setUp();
    }

    public function tearDown()
    {
        eZUser::purgeUserCacheByUserId( $this->userId );
        eZDir::cleanupEmptyDirectories( eZUser::getCacheDir( $this->userId ) );
        parent::tearDown();
    }

    /**
     * eZUser: do not generate cache content for invalid user
     *
     * @link http://issues.ez.no/22181
     */
    public function testNullUserCache()
    {
        eZUser::instance( $this->userId );

        $cacheFilePath = eZUser::getCacheDir( $this->userId ) . "/user-data-{$this->userId}.cache.php";
        $this->assertFalse( file_exists( $cacheFilePath ) );
    }
}
?>
