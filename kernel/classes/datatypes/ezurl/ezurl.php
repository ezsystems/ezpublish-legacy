<?php
//
// Definition of eZURL class
//
// Created on: <08-Oct-2002 19:44:48 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
  \class eZURL ezurl.php
  \ingroup eZKernel
  \brief A class which handles central storage of urls

  URLs can be stored using eZURL. When registering URL's
  to eZURL you will get a URL ID which is used to identify
  URLs.

*/

include_once( 'kernel/classes/ezpersistentobject.php' );

class eZURL extends eZPersistentObject
{
    /*!
    */
    function eZURL()
    {
    }

    /*!
     \static
     Registers a URL to the URL database. The URL id is
     returned if successful. False is returned if not.
    */
    function registerURL( $url )
    {
        $urlID = false;
        $db =& eZDB::instance();

        // check if URL already exists
        $checkURLQuery = "SELECT id FROM ezurl WHERE url='$url'";
        $urlArray =& $db->arrayQuery( $checkURLQuery );

        if ( count( $urlArray ) == 0 )
        {
            // store URL
            $insertURLQuery = "INSERT INTO ezurl ( url  ) VALUES ( '$url' )";
            $db->query( $insertURLQuery );

            $urlID = $db->lastSerialID( 'ezurl', 'id' );
        }
        else
        {
            $urlID = $urlArray[0]['id'];
        }
        return $urlID;
    }

    /*!
     \static
     Returns the URL with the given ID. False is returned if the ID
     does not exits.
    */
    function &url( $id )
    {
        $db =& eZDB::instance();

        $url = false;
        $checkURLQuery = "SELECT url FROM ezurl WHERE id='$id'";
        $urlArray =& $db->arrayQuery( $checkURLQuery );

        if ( count( $urlArray ) == 1 )
        {
            $url =& $urlArray[0]['url'];
        }
        return $url;
    }
}

?>
