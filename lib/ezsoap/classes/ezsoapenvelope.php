<?php
//
// Definition of eZSOAPEnvelope class
//
// Created on: <28-Feb-2002 15:54:36 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
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

/*! \defgroup eZSOAP SOAP communication library */

/*!
  \class eZSOAPEnvelope ezsoapenvelope.php
  \ingroup eZSOAP
  \brief SOAP envelope handling and definition

*/

//include_once( "lib/ezxml/classes/ezxml.php" );

//include_once( "lib/ezsoap/classes/ezsoapheader.php" );
//include_once( "lib/ezsoap/classes/ezsoapbody.php" );

class eZSOAPEnvelope
{
    const EZ_SOAP_ENV = "http://schemas.xmlsoap.org/soap/envelope/";
    const EZ_SOAP_ENC = "http://schemas.xmlsoap.org/soap/encoding/";
    const EZ_SOAP_SCHEMA_INSTANCE = "http://www.w3.org/2001/XMLSchema-instance";
    const EZ_SOAP_SCHEMA_DATA = "http://www.w3.org/2001/XMLSchema";

    const EZ_SOAP_ENV_PREFIX = "SOAP-ENV";
    const EZ_SOAP_ENC_PREFIX = "SOAP-ENC";
    const EZ_SOAP_XSI_PREFIX = "xsi";
    const EZ_SOAP_XSD_PREFIX = "xsd";

    const EZ_SOAP_INT = 1;
    const EZ_SOAP_STRING = 2;

    /*!
      Constructs a new SOAP envelope object.
    */
    function eZSOAPEnvelope( )
    {
        $this->Header = new eZSOAPHeader();
        $this->Body = new eZSOAPBody();
    }

    /// Contains the header object
    public $Header;

    /// Contains the body object
    public $Body;
}

?>
