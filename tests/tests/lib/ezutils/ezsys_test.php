<?php
/**
 * File containing the eZSysTest class
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

class eZSysTest extends ezpTestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZSysTest" );
    }

    /**
     * Test eZSys $AccessPath as it worked prior to 4.4 without propertied to
     * define scope of path with RemoveSiteAccessIfDefaultAccess=enabled
     */
    public function testIndexFileRemoveSiteAccessIfDefaultAccessEnabled()
    {
        // TEST SETUP --------------------------------------------------------
        $ini = eZINI::instance();

        $defaultAccess = $ini->variable( 'SiteSettings', 'DefaultAccess' );
        $this->setSiteAccess( $defaultAccess );

        // Make sure to preserve ini settings in case other tests depend on them
        $orgRemoveSiteaccess = $ini->variable( 'SiteAccessSettings', 'RemoveSiteAccessIfDefaultAccess' );

        // ENABLE RemoveSiteAccessIfDefaultAccess
        $ini->setVariable( 'SiteAccessSettings', 'RemoveSiteAccessIfDefaultAccess', 'enabled' );
        // -------------------------------------------------------------------

        // TEST --------------------------------------------------------------
        $indexFile = eZSys::indexFile();
        self::assertEquals( "", $indexFile );

        eZSys::addAccessPath( array( 'testing', 'indexFile' ) );
        $indexFile = eZSys::indexFile();
        self::assertEquals( "/testing/indexFile", $indexFile );
        // -------------------------------------------------------------------

        // TEST TEAR DOWN ----------------------------------------------------
        $ini->setVariable( 'SiteAccessSettings', 'RemoveSiteAccessIfDefaultAccess', $orgRemoveSiteaccess );
        eZSys::clearAccessPath();
        // -------------------------------------------------------------------
    }

    /**
     * Test eZSys $AccessPath as it worked prior to 4.4 without propertied to
     * define scope of path with RemoveSiteAccessIfDefaultAccess=disabled
     */
    public function testIndexFileRemoveSiteAccessIfDefaultAccessDisabled()
    {
        // TEST SETUP --------------------------------------------------------
        $ini = eZINI::instance();

        $defaultAccess = $ini->variable( 'SiteSettings', 'DefaultAccess' );
        $this->setSiteAccess( $defaultAccess );

        // Make sure to preserve ini settings in case other tests depend on them
        $orgRemoveSiteaccess = $ini->variable( 'SiteAccessSettings', 'RemoveSiteAccessIfDefaultAccess' );

        // DISABLE RemoveSiteAccessIfDefaultAccess
        $ini->setVariable( 'SiteAccessSettings', 'RemoveSiteAccessIfDefaultAccess', 'disabled' );
        // -------------------------------------------------------------------

        // TEST --------------------------------------------------------------
        $indexFile = eZSys::indexFile();
        self::assertEquals( "/$defaultAccess", $indexFile );

        eZSys::addAccessPath( array( 'testing', 'indexFile' ) );
        $indexFile = eZSys::indexFile();
        self::assertEquals( "/$defaultAccess/testing/indexFile", $indexFile );
        // -------------------------------------------------------------------

        // TEST TEAR DOWN ----------------------------------------------------
        $ini->setVariable( 'SiteAccessSettings', 'RemoveSiteAccessIfDefaultAccess', $orgRemoveSiteaccess );
        eZSys::clearAccessPath();
        // -------------------------------------------------------------------
    }

    public function testGlobBrace()
    {
        $pattern = "kernel/classes/ez{content,url}*.php";

        $files = eZSys::globBrace( $pattern );
        self::assertGreaterThan( 20, count( $files ), __METHOD__ . " test with GLOB_BRACE" );
    }

    public function testGlobBraceSupported()
    {
        if ( !defined( 'GLOB_BRACE' ) )
            self::markAsSkipped( "This test can only be run on systems supporting GLOB_BRACE." );

        $pattern = "kernel/classes/ez{content,url}*.php";

        $eZSysGlob = eZSys::globBrace( $pattern );
        $phpGlob = glob( $pattern, GLOB_BRACE );
        self::assertEquals( $eZSysGlob, $phpGlob, "Comparing glob() with eZSys::glob() using GLOB_BRACE" );

        $eZSysGlob = eZSys::globBrace( $pattern, GLOB_NOSORT );
        $phpGlob = glob( $pattern, GLOB_NOSORT | GLOB_BRACE );
        self::assertEquals( $eZSysGlob, $phpGlob, "Comparing glob() with eZSys::glob() using GLOB_MARK | GLOB_BRACE" );
    }

    /**
     * Test eZSys $AccessPath as it works as of 4.4 with propertied to
     * define scope of path with RemoveSiteAccessIfDefaultAccess=enabled
     */
    public function testIndexFileRemoveSiteAccessIfDefaultAccessEnabled2()
    {
        // TEST SETUP --------------------------------------------------------
        $ini = eZINI::instance();

        $defaultAccess = $ini->variable( 'SiteSettings', 'DefaultAccess' );
        $this->setSiteAccess( $defaultAccess );

        // Make sure to preserve ini settings in case other tests depend on them
        $orgRemoveSiteaccess = $ini->variable( 'SiteAccessSettings', 'RemoveSiteAccessIfDefaultAccess' );

        // ENABLE RemoveSiteAccessIfDefaultAccess
        $ini->setVariable( 'SiteAccessSettings', 'RemoveSiteAccessIfDefaultAccess', 'enabled' );
        // -------------------------------------------------------------------

        // TEST --------------------------------------------------------------
        $indexFile = eZSys::indexFile();
        self::assertEquals( "", $indexFile );

        eZSys::setAccessPath( array( 'testing', 'indexFile' ), 'test-path', false );
        $indexFile = eZSys::indexFile();
        self::assertEquals( "/testing/indexFile", $indexFile );
        // -------------------------------------------------------------------

        // TEST TEAR DOWN ----------------------------------------------------
        $ini->setVariable( 'SiteAccessSettings', 'RemoveSiteAccessIfDefaultAccess', $orgRemoveSiteaccess );
        eZSys::clearAccessPath( false );
        // -------------------------------------------------------------------
    }

    /**
     * Test eZSys $AccessPath as it works as of 4.4 with propertied to
     * define scope of path with RemoveSiteAccessIfDefaultAccess=disabled
     */
    public function testIndexFileRemoveSiteAccessIfDefaultAccessDisabled2()
    {
        // TEST SETUP --------------------------------------------------------
        $ini = eZINI::instance();

        $defaultAccess = $ini->variable( 'SiteSettings', 'DefaultAccess' );
        $this->setSiteAccess( $defaultAccess );

        // Make sure to preserve ini settings in case other tests depend on them
        $orgRemoveSiteaccess = $ini->variable( 'SiteAccessSettings', 'RemoveSiteAccessIfDefaultAccess' );

        // DISABLE RemoveSiteAccessIfDefaultAccess
        $ini->setVariable( 'SiteAccessSettings', 'RemoveSiteAccessIfDefaultAccess', 'disabled' );
        // -------------------------------------------------------------------

        // TEST --------------------------------------------------------------
        $indexFile = eZSys::indexFile();
        self::assertEquals( "/$defaultAccess", $indexFile );

        eZSys::setAccessPath( array( 'testing', 'indexFile' ), 'test-path', false );
        $indexFile = eZSys::indexFile();
        self::assertEquals( "/$defaultAccess/testing/indexFile", $indexFile );
        // -------------------------------------------------------------------

        // TEST TEAR DOWN ----------------------------------------------------
        $ini->setVariable( 'SiteAccessSettings', 'RemoveSiteAccessIfDefaultAccess', $orgRemoveSiteaccess );
        eZSys::clearAccessPath( false );
        // -------------------------------------------------------------------
    }

    /* -----------------------------------------------------------------------
     * HELPER FUNCTIONS
     * -----------------------------------------------------------------------
     */
     private function setSiteAccess( $accessName )
     {
         eZSiteAccess::change( array( 'name' => $accessName,
                                      'type' => eZSiteAccess::TYPE_URI,
                                      'uri_part' => array( $accessName ) ) );
     }
}

?>
