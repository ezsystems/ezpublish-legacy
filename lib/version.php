<?php
//
// Created on: <29-May-2002 10:38:45 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \brief contains the eZ publish SDK version.
*/

define( "EZ_SDK_VERSION_MAJOR", 3 );
define( "EZ_SDK_VERSION_MINOR", 2 );
define( "EZ_SDK_VERSION_DEVELOPMENT", false );
define( "EZ_SDK_VERSION_RELEASE", 6 );
define( "EZ_SDK_VERSION_REVISION_STRING", '$Rev$' );
define( 'EZ_SDK_VERSION_ALIAS', "3.2 release 6" );
define( "EZ_SDK_VERSION_REVISION", preg_replace( "#\\\$Rev:\s+([0-9]+)\s+\\\$#", '$1', EZ_SDK_VERSION_REVISION_STRING ) );

class eZPublishSDK
{
    /*!
      \return the SDK version as a string
      \param withRelease If true the release version is appended
      \param withAlias If true the alias is used instead
    */
    function version( $withRelease = true,
                      $asAlias = false )
    {
        $versionText = eZPublishSDK::majorVersion() . '.' . eZPublishSDK::minorVersion();
        $development = eZPublishSDK::developmentVersion();
        $revision = eZPublishSDK::revision();
        if ( $development !== false )
            $versionText .= '.' . $development;
        if ( $withRelease )
            $versionText .= "-" . eZPublishSDK::release();
        if ( $asAlias )
            $versionText = eZPublishSDK::alias();
        return $versionText;
    }

    /*!
     \return the major version
    */
    function majorVersion()
    {
        return EZ_SDK_VERSION_MAJOR;
    }

    /*!
     \return the minor version
    */
    function minorVersion()
    {
        return EZ_SDK_VERSION_MINOR;
    }

    /*!
     \return the development version or \c false if this is not a development version
    */
    function developmentVersion()
    {
        return EZ_SDK_VERSION_DEVELOPMENT;
    }

    /*!
     \return the release number
    */
    function release()
    {
        return EZ_SDK_VERSION_RELEASE;
    }

    /*!
     \return the SVN revision number
    */
    function revision()
    {
        return EZ_SDK_VERSION_REVISION;
    }

    /*!
     \return the alias name for the release, this is often used for beta releases and release candidates.
    */
    function alias()
    {
        return EZ_SDK_VERSION_ALIAS;
    }

    /*!
      \return the version of the database.
      \param withRelease If true the release version is appended
    */
    function databaseVersion( $withRelease = true )
    {
        include_once( 'lib/ezdb/classes/ezdb.php' );
        $db =& eZDB::instance();
        $rows =& $db->arrayQuery( "SELECT value as version FROM ezsite_date WHERE name='ezpublish-version'" );
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
    function databaseRelease()
    {
        include_once( 'lib/ezdb/classes/ezdb.php' );
        $db =& eZDB::instance();
        $rows =& $db->arrayQuery( "SELECT value as release FROM ezsite_date WHERE name='ezpublish-release'" );
        $relase = false;
        if ( count( $rows ) > 0 )
        {
            $relase = $rows[0]['release'];
        }
        return $release;
    }
}

?>
