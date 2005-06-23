<?php
//
// Definition of eZSetupFunctionCollection class
//
// Created on: <02-Nov-2004 13:23:10 dl>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezsetupfunctioncollection.php
*/

/*!
  \class eZSetupFunctionCollection ezsetupfunctioncollection.php
  \brief The class eZSetupFunctionCollection does

*/

include_once( 'kernel/error/errors.php' );
include_once( 'lib/version.php' );

class eZSetupFunctionCollection
{
    /*!
     Constructor
    */
    function eZSetupFunctionCollection()
    {
    }


    function &fetchFullVersionString()
    {
        $result = array( 'result' => eZPublishSDK::version() );
        return $result;
    }

    function &fetchMajorVersion()
    {
        $result = array( 'result' => eZPublishSDK::majorVersion() );
        return $result;
    }

    function &fetchMinorVersion()
    {
        $result = array( 'result' => eZPublishSDK::minorVersion() );
        return $result;
    }

    function &fetchRelease()
    {
        $result = array( 'result' => eZPublishSDK::release() );
        return $result;

    }

    function &fetchState()
    {
        $result = array( 'result' => eZPublishSDK::state() );
        return $result;

    }

    function &fetchIsDevelopment()
    {
        $result = array( 'result' => eZPublishSDK::developmentVersion() ? true : false );
        return $result;

    }

    function &fetchRevision()
    {
        $result = array( 'result' => eZPublishSDK::revision() );
        return $result;

    }

    function &fetchDatabaseVersion( $withRelease = true )
    {
        $result = array( 'result' => eZPublishSDK::databaseVersion( $withRelease ) );
        return $result;

    }

    function &fetchDatabaseRelease()
    {
        $result = array( 'result' => eZPublishSDK::databaseRelease() );
        return $result;

    }
}

?>
