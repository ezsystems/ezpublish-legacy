<?php
//
// $Id$
//
// Definition of eZSimpleType class
//
// Bård Farstad <bf@ez.no>
// Created on: <14-Feb-2002 10:09:40 bf>
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
  \class eZSimpleType ezsimpletype.php
  \ingroup eZXML
  \brief eZSimpleType implements XML schema simple type
*/

include_once( "lib/ezxml/classes/ezelementtype.php" );

class eZSimpleType extends eZElementType
{
    /*!
      Creates a new simple type object
    */
    function eZSimpleType()
    {

    }

    /*!
      Returns the type name.
    */
    function isA()
    {
        return "simpleType";
    }


}

?>
