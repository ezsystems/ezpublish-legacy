<?php
//
// Definition of eZWeb class
//
// Created on: <14-Dec-2002 18:13:53 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
  \class eZWeb ezweb.php
  \ingroup eZUtils
  \brief Easy access to various browser settings

*/

class eZWeb
{
    function eZWeb()
    {
    }

    function init()
    {
        $userAgent = eZSys::serverVariable( 'HTTP_USER_AGENT' );
        eZDebugSetting::writeDebug( 'lib-ezutil-web', $userAgent, 'user agent' );
    }

    /*!
     Returns the only legal instance of the eZWeb class.
     \static
    */
    static function instance()
    {
        if ( empty( $GLOBALS["eZWebInstance"] ) )
        {
            $GLOBALS["eZWebInstance"] = new eZWeb();
        }
        return $GLOBALS["eZWebInstance"];
    }

    /// \privatesection
}

?>
