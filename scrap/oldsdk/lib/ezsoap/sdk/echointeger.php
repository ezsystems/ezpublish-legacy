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

$request = new eZSOAPRequest( "echoInteger", $namespace );

$request->addParameter( "inputInteger", 42 );

$response =& $client->send( $request );

if ( $response->isFault() )
{
    print( "SOAP fault: " . $response->faultCode(). " - " . $response->faultString() . "" );
}
else
    print( "Returned SOAP value was: \"" . $response->value() . "\"" );

?>
