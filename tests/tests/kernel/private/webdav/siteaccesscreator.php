<?php
/**
 * File containing the class ezpSiteaccessCreator used to create siteaccesses for testing.
 *
 * @copyright Copyright (C) 1999-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

/**
 * This class requires the extension eZSiteAccessHelper.
 *
 * {@link http://svn.ez.no/svn/commercial/projects/qa/trunk/ezsiteaccesshelper/}.
 *
 * This class overwrites some methods from eZSiteAccessHelper.
 */
class ezpSiteAccessCreator extends eZSiteAccessHelper
{
    /**
     * Enables a siteaccess.
     *
     * @throws ezsahINIVariableNotSetException if the value exists in the array in the ini file
     * @throws ezsahINIVariableCouldNotSetException if the array could not be set in the ini file
     * @throws ezsahFileCouldNotWriteException if we cannot write the ini file to disk
     * @param string $siteAccessName
     **/
    public static function enableSiteAccess( $siteAccessName )
    {
        // call the parent
        parent::enableSiteAccess( $siteAccessName );

        // add a missing ini setting which the parent does not add
        $siteIniOverride = eZINI::instance( 'site.ini.append.php', self::$docRoot . 'settings/override/', null, false, null, true );
        ezsahINIHelper::addToINIArray( $siteIniOverride, 'SiteSettings', 'SiteList', $siteAccessName );
        $siteIniOverride->save();
    }

    /**
     * Disables a siteaccess.
     *
     * @throws ezsahINIVariableNotSetException if the value exists in the array in the ini file
     * @throws ezsahINIVariableCouldNotSetException if the array could not be set in the ini file
     * @throws ezsahFileCouldNotWriteException if we cannot write the ini file to disk
     * @param string $siteAccessName
     **/
    public static function disableSiteAccess( $siteAccessName )
    {
        // call the parent
        parent::disableSiteAccess( $siteAccessName );

        // delete a missing ini setting which the parent does not delete
        $siteIniOverride = eZINI::instance( 'site.ini.append.php', self::$docRoot . 'settings/override/', null, false, null, true );
        ezsahINIHelper::removeFromINIArray( $siteIniOverride, 'SiteSettings', 'SiteList', $siteAccessName );
        $siteIniOverride->save();
    }
}
?>
