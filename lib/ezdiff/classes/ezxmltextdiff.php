<?php
//
// Definition of eZXMLTextDiff class
//
// <creation-tag>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

/*! \file ezxmltextdiff.php
  eZXMLTextDiff class
*/

/*!
  \class eZXMLTextDiff ezxmltextdiff.php
  \ingroup eZDiff
  \brief eZXMLTextDiff contains differences in xml text.
*/

include_once( 'lib/ezutils/classes/ezdebug.php' );
include_once( 'lib/ezdiff/classes/ezdiffcontent.php' );

class eZXMLTextDiff extends eZDiffContent
{
    /*!
      Constructor
    */
    function eZXMLTextDiff()
    {
        eZDebug::writeNotice( "Init eZXMLTextDiff", "eZXMLTextDiff" );
    }
}
?>
