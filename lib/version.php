<?php
/**
 * File containing the eZPublishSDK class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*!
  \brief contains the eZ Publish SDK version.
*/

class eZPublishSDK
{
    const VERSION_MAJOR = 4;
    const VERSION_MINOR = 7;
    const VERSION_RELEASE = 0;
    const VERSION_STATE = 'alpha1';
    const VERSION_DEVELOPMENT = true;
    const VERSION_ALIAS = '4.7';

    /*!
      \return the SDK version as a string
      \param withRelease If true the release version is appended
      \param withAlias If true the alias is used instead
    */
    static function version( $withRelease = true, $asAlias = false, $withState = true )
    {
        if ( $asAlias )
        {
            $versionText = eZPublishSDK::alias();
            if ( $withState )
                $versionText .= "-" . eZPublishSDK::state();
        }
        else
        {
            $versionText = eZPublishSDK::majorVersion() . '.' . eZPublishSDK::minorVersion();
//            $development = eZPublishSDK::developmentVersion();
//            if ( $development !== false )
//                $versionText .= '.' . $development;
            if ( $withRelease )
                $versionText .= "." . eZPublishSDK::release();
            if ( $withState )
                $versionText .= eZPublishSDK::state();
        }
        return $versionText;
    }

    /*!
     \return the major version
    */
    static function majorVersion()
    {
        return eZPublishSDK::VERSION_MAJOR;
    }

    /*!
     \return the minor version
    */
    static function minorVersion()
    {
        return eZPublishSDK::VERSION_MINOR;
    }

    /*!
     \return the state of the release
    */
    static function state()
    {
        return eZPublishSDK::VERSION_STATE;
    }

    /*!
     \return the development version or \c false if this is not a development version
    */
    static function developmentVersion()
    {
        return eZPublishSDK::VERSION_DEVELOPMENT;
    }

    /*!
     \return the release number
    */
    static function release()
    {
        return eZPublishSDK::VERSION_RELEASE;
    }

    /*!
     \return the alias name for the release, this is often used for beta releases and release candidates.
    */
    static function alias()
    {
        return eZPublishSDK::version();
    }

    /*!
      \return the version of the database.
      \param withRelease If true the release version is appended
    */
    static function databaseVersion( $withRelease = true )
    {
        $db = eZDB::instance();
        $rows = $db->arrayQuery( "SELECT value as version FROM ezsite_data WHERE name='ezpublish-version'" );
        $version = false;
        if ( count( $rows ) > 0 )
        {
            $version = $rows[0]['version'];
            if ( $withRelease )
            {
                $release = eZPublishSDK::databaseRelease();
                $version .= '-' . $release;
            }
        }
        return $version;
    }

    /*!
      \return the release of the database.
    */
    static function databaseRelease()
    {
        $db = eZDB::instance();
        $rows = $db->arrayQuery( "SELECT value as release FROM ezsite_data WHERE name='ezpublish-release'" );
        $release = false;
        if ( count( $rows ) > 0 )
        {
            $release = $rows[0]['release'];
        }
        return $release;
    }
}

?>
