<?php
//
// Definition of eZSOAPFault class
//
// Created on: <30-May-2002 15:51:41 bf>
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
  \class eZSOAPFault ezsoapfault.php
  \ingroup eZSOAP
  \brief eZSOAPFault handles SOAP fault messages

*/

class eZSOAPFault
{
    /*!
     Constructs a new eZSOAPFault object
    */
    function eZSOAPFault( $faultCode = "", $faultString = "" )
    {
        $this->FaultCode = $faultCode;
        $this->FaultString = $faultString;
    }

    /*!
     Returns the fauls code.
    */
    function faultCode()
    {
        return $this->FaultCode;
    }

    /*!
     Returns the fauls string.
    */
    function faultString()
    {
        return $this->FaultString;
    }

    /// Contains the fault code
    public $FaultCode;

    /// Contains the fault string
    public $FaultString;
}

?>
