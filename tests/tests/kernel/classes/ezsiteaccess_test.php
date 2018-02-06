<?php
/**
 * File containing the eZSiteaccess class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
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
