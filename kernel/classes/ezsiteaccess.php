<?php
//
// Definition of eZSiteAccess class
//
// Created on: <22-���-2003 16:23:14 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
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

/*! \file ezsiteaccess.php
*/

/*!
  \class eZSiteAccess ezsiteaccess.php
  \brief The class eZSiteAccess does

*/

//include_once( 'lib/ezutils/classes/ezsys.php' );

class eZSiteAccess
{
    /*!
     Constructor
    */
    function eZSiteAccess()
    {
    }

    function siteAccessList()
    {
        //include_once( 'lib/ezutils/classes/ezsys.php' );

        $ini = eZINI::instance();
        $siteAccessArray = $ini->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );

        $siteAccessList = array();
        reset( $siteAccessArray );
        foreach ( array_keys( $siteAccessArray ) as $key )
        {
            $siteAccessItem = array();
            $siteAccessItem['name'] = $siteAccessArray[$key];
            $siteAccessItem['id'] = eZSys::ezcrc32( $siteAccessArray[$key] );
            $siteAccessList[] = $siteAccessItem;
        }
        if ( $serversiteaccess = eZSys::serverVariable( $ini->variable( 'SiteAccessSettings', 'ServerVariableName' ), true ) and !in_array( $serversiteaccess, $siteAccessArray ) )
        {
            $siteAccessItem = array();
            $siteAccessItem['name'] = $serversiteaccess;
            $siteAccessItem['id'] = eZSys::ezcrc32( $serversiteaccess );
            $siteAccessList[] = $siteAccessItem;
        }
        return $siteAccessList;
    }

    /*!
       Returns path to \a $siteAccess site access
    */
    static function findPathToSiteAccess( $siteAccess )
    {
        //include_once( 'lib/ezutils/classes/ezini.php' );
        $ini = eZINI::instance();
        $siteAccessList = $ini->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );
        if ( !in_array( $siteAccess, $siteAccessList )  )
            return false;

        $currentPath = 'settings/siteaccess/' . $siteAccess;
        if ( file_exists( $currentPath ) )
            return $currentPath;

        //include_once( 'lib/ezutils/classes/ezextension.php' );
        $activeExtensions = eZExtension::activeExtensions();
        $baseDir = eZExtension::baseDirectory();
        foreach ( $activeExtensions as $extension )
        {
            $currentPath = $baseDir . '/' . $extension . '/settings/siteaccess/' . $siteAccess;
            if ( file_exists( $currentPath ) )
                return $currentPath;
        }

        return 'settings/siteaccess/' . $siteAccess;
    }

}

?>
