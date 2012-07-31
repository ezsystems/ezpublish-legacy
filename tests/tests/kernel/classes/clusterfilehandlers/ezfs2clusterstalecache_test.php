<?php
/**
 * File containing the eZFS2ClusterStaleCacheTest class.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package tests
 */

/**
 * This test applies to the stalecache features for the eZFS2 cluster handler
 * @package tests
 * @group cluster
 * @group eZFS2
 */
class eZFS2ClusterStaleCacheTest extends eZClusterStaleCacheTest
{
    protected $clusterClass = 'eZFS2FileHandler';

    /**
     * Test setup
     *
     * Load an instance of file.ini
     **/
    public function setUp()
    {
        parent::setUp();

        // We need to clear the existing handler if it was loaded before the INI
        // settings changes
        eZClusterFileHandler::resetHandler();

        // Load database parameters for cluster
        // The same DSN than the relational database is used
        ezpINIHelper::setINISetting( 'file.ini', 'ClusteringSettings', 'FileHandler', $this->clusterClass );
    }

    public function tearDown()
    {
        ezpINIHelper::restoreINISettings();
        eZClusterFileHandler::resetHandler();

        parent::tearDown();
    }
}
?>
