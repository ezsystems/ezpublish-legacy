<?php

include_once( "lib/ezsoap/classes/ezsoapclient.php" );
include_once( "lib/ezsoap/classes/ezsoaprequest.php" );

/*
 WSDL: http://easysoap.sourceforge.net/interop.wsdl
 Endpoint: http://easysoap.sourceforge.net/cgi-bin/interopserver
 SOAPAction: "urn:soapinterop"
 Namespace: http://soapinterop.org/
*/

$client = new eZSOAPClient( "easysoap.sourceforge.net", "/cgi-bin/interopserver" );

$namespace = "http://soapinterop.org/";

$request = new eZSOAPRequest( "echoString", $namespace );

$request->addParameter( "inputString", "This is what I send.." );

$result =& $client->send( $request );

print( "SOAP response: \"" . $result . "\"" );

?>
