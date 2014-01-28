<?php
/**
 * File containing the eZUserCacheTest class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
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
