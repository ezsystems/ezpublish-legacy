<?php
//
// $Id$
//
// Definition of eZElementType class
//
// Bård Farstad <bf@ez.no>
// Created on: <14-Feb-2002 10:04:29 bf>
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

/*!
  \class eZElementType ezelementtype.php
  \ingroup eZXML
  \brief eZElementType is the main element type object
*/

class eZElementType
{
    /*!
      Creates a new element type object
    */
    function eZElementType()
    {
    }

    /*!
      Returns the type name.
    */
    function isA()
    {
        return "mainType";
    }

    /*!
      Sets the type name
    */
    function setName( $name )
    {
        $this->Name = $name;
    }

    /// the name of the type
    public $Name;
}

?>
