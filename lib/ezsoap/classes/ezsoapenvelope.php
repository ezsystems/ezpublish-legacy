<?php
//
// Definition of eZSOAPEnvelope class
//
// Created on: <28-Feb-2002 15:54:36 bf>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \defgroup eZSOAP SOAP communication library */

/*!
  \class eZSOAPEnvelope ezsoapenvelope.php
  \ingroup eZSOAP
  \brief SOAP envelope handling and definition

*/

include_once( "lib/ezxml/classes/ezxml.php" );

include_once( "lib/ezsoap/classes/ezsoapheader.php" );
include_once( "lib/ezsoap/classes/ezsoapbody.php" );

define( "EZ_SOAP_ENV", "http://schemas.xmlsoap.org/soap/envelope/" );
define( "EZ_SOAP_ENC", "http://schemas.xmlsoap.org/soap/encoding/" );
define( "EZ_SOAP_SCHEMA_INSTANCE", "http://www.w3.org/2001/XMLSchema-instance" );
define( "EZ_SOAP_SCHEMA_DATA", "http://www.w3.org/2001/XMLSchema" );

define( "EZ_SOAP_ENV_PREFIX", "SOAP-ENV" );
define( "EZ_SOAP_ENC_PREFIX", "SOAP-ENC" );
define( "EZ_SOAP_XSI_PREFIX", "xsi" );
define( "EZ_SOAP_XSD_PREFIX", "xsd" );

define( "EZ_SOAP_INT", 1 );
define( "EZ_SOAP_STRING", 2 );


class eZSOAPEnvelope
{
    /*!
      Constructs a new SOAP envelope object.
    */
    function eZSOAPEnvelope( )
    {
        $this->Header = new eZSOAPHeader();
        $this->Body = new eZSOAPBody();
    }

    /// Contains the header object
    var $Header;

    /// Contains the body object
    var $Body;
}

?>
