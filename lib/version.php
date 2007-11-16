<?php
//
// Created on: <29-May-2002 10:38:45 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*!
  \brief contains the eZ Publish SDK version.
*/

class eZPublishSDK
{
    const VERSION_MAJOR = 4;
    const VERSION_MINOR = 0;
    const VERSION_RELEASE = 0;
    const VERSION_STATE = 'beta1';
    const VERSION_DEVELOPMENT = true;
    const VERSION_REVISION_STRING = '$Rev$';
    const VERSION_ALIAS = '4.0';
    const VERSION_REVISION = 19785;

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
//            $revision = eZPublishSDK::revision();
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
     \return the SVN revision number
    */
    static function revision()
    {
        return eZPublishSDK::VERSION_REVISION;
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
        //include_once( 'lib/ezdb/classes/ezdb.php' );
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
        //include_once( 'lib/ezdb/classes/ezdb.php' );
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
