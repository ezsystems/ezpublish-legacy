<?php
//
// Created on: <29-May-2002 10:38:45 bf>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
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
define( "EZ_SDK_VERSION_MINOR", 0 );
define( "EZ_SDK_VERSION_RELEASE", 1 );
define( "EZ_SDK_VERSION_ALIAS", '3.0' );

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
        $ver = eZPublishSDK::majorVersion() . "." . eZPublishSDK::minorVersion();
        if ( $withRelease )
            $ver .= "-" . eZPublishSDK::release();
        if ( $asAlias )
            $ver = eZPublishSDK::alias();
        return $ver;
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
     \return the release number
    */
    function release()
    {
        return EZ_SDK_VERSION_RELEASE;
    }

    /*!
     \return the alias name for the release, this is often used for beta releases and release candidates.
    */
    function alias()
    {
        return EZ_SDK_VERSION_ALIAS;
    }
}

?>
