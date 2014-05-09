<?php
/**
 * File containing the eZSysTest class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package tests
 */

class eZSysTest extends ezpTestCase
{
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

    public function testIsSSLNow()
    {
        $ini = eZINI::instance();

        self::assertFalse( eZSys::isSSLNow() );

        $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
        self::assertTrue( eZSys::isSSLNow() );
        unset( $_SERVER['HTTP_X_FORWARDED_PROTO'] );

        $_SERVER['HTTP_X_FORWARDED_PORT'] = $ini->variable( 'SiteSettings', 'SSLPort' );
        self::assertTrue( eZSys::isSSLNow() );
        unset( $_SERVER['HTTP_X_FORWARDED_PORT'] );

        $_SERVER['HTTP_X_FORWARDED_SERVER'] = $ini->variable( 'SiteSettings', 'SSLProxyServerName' );
        self::assertTrue( eZSys::isSSLNow() );
        unset( $_SERVER['HTTP_X_FORWARDED_SERVER'] );
    }

    public function testServerProtocol()
    {
        self::assertEquals( 'http', eZSys::serverProtocol() );

        $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
        self::assertEquals( 'https', eZSys::serverProtocol() );
        unset( $_SERVER['HTTP_X_FORWARDED_PROTO'] );
    }
    
    /**
     * Tests the protected static method getValidwwwDir of the eZSys class
     *
     * @dataProvider providerForTestGetValidWwwDir
     */
    public function testGetValidWwwDir( $expected, $phpSelf, $scriptFileName, $index )
    {
        $method = new ReflectionMethod( 'eZSys', 'getValidwwwDir' );
        $method->setAccessible( true );

        self::assertEquals( $expected, $method->invoke( null, $phpSelf, $scriptFileName, $index ) );
    }

    /**
     * @return array
     */
    public function providerForTestGetValidWwwDir()
    {
        return array(
            // expected value, script called, path of the script, name of the index
            array( false, '', '/some/test/path/index.php', 'index.php' ),
            array( false, '/index.php', '/some/test/path/index.php', 'indexfail.php' ),
            array( null, '/index.php', '/some/test/path/indexfail.php', 'index.php' ),
            array( '', '/index.php', '/some/test/path/index.php', 'index.php' ),
            array( '~user', '/~user/index.php', '/some/test/path/user/public_html/index.php', 'index.php' ),
            array( 'path', '/path/index.php', '/some/test/path/index.php', 'index.php' ),
            array( 'path', '/path/index.php', '\some\test\path\index.php', 'index.php' ),
            array( null, "/~a\"><body onload=\"alert('Xss')\">/index.php", '/some/test/path/user/public_html/index.php', 'index.php' )
        );
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
