<?php
//
// $Id$
//
// Definition of eZElementType class
//
// Bård Farstad <bf@ez.no>
// Created on: <14-Feb-2002 10:04:29 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2004 eZ systems as
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
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
    var $Name;
}

?>
