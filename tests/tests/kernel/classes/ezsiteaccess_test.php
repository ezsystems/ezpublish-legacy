<?php
/**
 * File containing the eZSiteaccess class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package tests
 */

class eZSiteAccess_Test extends ezpTestCase
{
    public function __construct()
    {
        parent::__construct();
        $this->setName( "eZSiteaccess" );
    }

    /**
     * Test that global siteaccess is untouched when asking for a specific siteaccess settings
     * & reading settings from plain siteaccess
     */
    public function testGetINI()
    {
        $current = eZSiteAccess::current();
        $ini = eZSiteAccess::getIni('plain'/*, 'site.ini'*/);
        self::assertEquals( $current, eZSiteAccess::current() );

        // this is not totally correct way of testing, but one way of making "sure" we got correct sa
        self::assertEquals( 'plain', $ini->variable('DesignSettings', 'SiteDesign') );
    }

    /**
     * Test findPathToSiteAccess
     */
    public function testFindPathToSiteAccess()
    {
        $ini = eZINI::instance();
        $siteAccessList = $ini->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );

        $ini->setVariable( 'SiteAccessSettings', 'AvailableSiteAccessList', array('eng') );
        $path = eZSiteAccess::findPathToSiteAccess('plain');
        self::assertFalse( $path );

        $ini->setVariable( 'SiteAccessSettings', 'AvailableSiteAccessList', array('plain') );
        $path = eZSiteAccess::findPathToSiteAccess('plain');
        self::assertEquals( 'settings/siteaccess/plain', $path );

        $ini->setVariable( 'SiteAccessSettings', 'AvailableSiteAccessList', $siteAccessList );
    }
}

?>
