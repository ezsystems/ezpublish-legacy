<?php
//
// Definition of eZSessionCache class
//
// Created on: <15-Jan-2003 15:15:49 bf>
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

/*! \file ezsessioncache.php
*/

/*!
  \class eZSessionCache ezsessioncache.php
  \brief The class eZContentCache handles cache coherence of data stored in session

  The session cache is used to cache values, mostly fetched from database, pr session.
  This is to limit the number of database calls done pr page to a minimum. You can
  for example use this to cache the roles of the current user. This class handles
  expiry of this cached information.

  \code

if ( eZSessionCache::isExpired( EZ_SESSION_CACHE_USER_INFO ) )
{
    // Regenerate the cached values
    // ...

    // Set caching to valid
    eZSessionCache::setIsValid( EZ_SESSION_CACHE_USER_INFO );
}

// Update cached values
// ...

// Expire cache, next time a session is fetched it is expired
eZSessionCache::expireSessions( EZ_SESSION_CACHE_USER_INFO );

  \endcode
*/

define( "EZ_SESSION_CACHE_USER_ROLES",           "0000000000000000000000000000001" );
define( "EZ_SESSION_CACHE_USER_INFO",            "0000000000000000000000000000010" );
define( "EZ_SESSION_CACHE_USER_GROUPS",          "0000000000000000000000000000100" );
define( "EZ_SESSION_CACHE_USER_DISCOUNT_RULES",  "0000000000000000000000000001000" );
define( "EZ_SESSION_CACHE_CLASSES_LIST",         "0000000000000000000000000010000" );

class eZSessionCache
{
    function eZSessionCache()
    {

    }

    /*!
     Returns true if the session cache with the given key is expired.
    */
    function isExpired( $key )
    {
        global $eZSessionCacheMask;
        if ( bindec( $eZSessionCacheMask ) & bindec( $key ) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /*!
     Sets the session cache matching the current key to valid for the current session.
    */
    function setIsValid( $key )
    {
        global $eZSessionCacheMask;

        // invert mask
        $mask = ~ bindec( $key );
        // filter
        $eZSessionCacheMask = decbin( bindec( $eZSessionCacheMask ) & $mask );
    }

    /*!
     Will expire all sessions for the current key.
    */
    function expireSessions( $key )
    {
        $decKey = 0;
        if ( is_array( $key ) )
        {
            foreach ( $key  as $mask )
            {
                $decKey |= bindec( $mask );
            }
        }
        else
        {
            $decKey = bindec( $key );
        }
        $db =& eZDB::instance();
        $db->query( "UPDATE ezsession SET cache_mask_1 = ( cache_mask_1 | $decKey )" );

        // expire current session as well
        global $eZSessionCacheMask;

        $mask = bindec( $eZSessionCacheMask );

        $eZSessionCacheMask = decbin( $mask | $decKey );
    }
}

?>
