<?php
//
// $Id$
//
// Definition of eZSOAPParameter class
//
// Created on: <28-Feb-2002 17:07:23 bf>
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

//!! eZSOAP
//! eZSOAPParameter handles parameters to SOAP requests
/*!
  \code

  \endcode
*/


class eZSOAPParameter
{
    /*!
      Creates a new SOAP parameter object.
    */
    function eZSOAPParameter( $name, $value)
    {
        $this->Name = $name;
        $this->Value = $value;
    }

    /*!
      Sets the parameter name.
    */
    function setName( $name )
    {
        $this->Name = $name;
    }

    /*!
      Returns the parameter name.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Sets the parameter value
    */
    function setValue( $value )
    {

    }

    /*!
      Returns the parameter value.
    */
    function value()
    {
        return $this->Value;
    }

    /// The name of the parameter
    public $Name;

    /// The parameter value
    public $Value;
}

?>
