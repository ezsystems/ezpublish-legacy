<?php
//
// Created on: <28-May-2002 13:32:13 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

?>
<p>
This example sends a string, <b>This is a secret!</b>, to the SOAP server.
</p>
<?php
include_once( "lib/ezsoap/classes/ezsoapclient.php" );
include_once( "lib/ezsoap/classes/ezsoaprequest.php" );

$client = new eZSOAPClient( "nextgen.bf.dvh1.ez.no", "/sdk/ezsoap/view/echostringserver" );

$namespace = "http://soapinterop.org/";

$request = new eZSOAPRequest( "echoString", $namespace );

$request->addParameter( "inputString", "This is a secret!" );

$response =& $client->send( $request );

if ( $response->isFault() )
{
    print( "SOAP fault: " . $response->faultCode(). " - " . $response->faultString() . "" );
}
else
    print( "Returned SOAP value was: \"" . $response->value() . "\"" );

?>
