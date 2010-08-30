<?php
/**
 * File containing the eZClusterFileHandlerTest class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

/**
 * Abstract class that gathers common cluster file handlers tests
 */
abstract class eZClusterFileHandlerAbstractTest extends ezpDatabaseTestCase
{
    /**
     * Tests the cluster shutdown handler.
     * Handlers that support stalecache should cleanup their generating files if any
     */
    public function testShutdownHandler()
    {
        // start generation of a couple files
        $file1 = eZClusterFileHandler::instance( 'var/tests/cache/uncleanfile1.txt' );
        $file1->startCacheGeneration();
        $file1->storeContents( __METHOD__ );

        $file2 = eZClusterFileHandler::instance( 'var/tests/cache/uncleanfile2.txt' );
        $file2->startCacheGeneration();
        $file2->storeContents( __METHOD__ );

        $file3 = eZClusterFileHandler::instance( 'var/tests/cache/uncleanfile3.txt' );
        $file3->startCacheGeneration();
        $file3->storeContents( __METHOD__ );

        // terminate one of them
        $file2->endCacheGeneration();

        // check that the generating status is as expected
        $this->assertStringEndsWith(    '.generating', $file1->filePath, '$file1 is not generating' );
        $this->assertStringEndsNotWith( '.generating', $file2->filePath, '$file2 is generating' );
        $this->assertStringEndsWith(    '.generating', $file3->filePath, '$file3 is not generating' );

        // Call the cleanup handler called by eZExecution::cleanExit()
        eZClusterFileHandler::cleanupGeneratingFiles();

        // Check that all files are no longer marked as generating
        $this->assertStringEndsNotWith( '.generating', $file1->filePath, '$file1 is still generating' );
        $this->assertStringEndsNotWith( '.generating', $file2->filePath, '$file2 is still generating' );
        $this->assertStringEndsNotWith( '.generating', $file3->filePath, '$file3 is still generating' );
    }
}
?>