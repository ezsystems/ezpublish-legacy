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

$request = new eZSOAPRequest( "echoStringArray", $namespace );

$request->addParameter( "inputStringArray", array( "text input a", "input b", "input c", "foo", "bar" ) );

$response =& $client->send( $request );

if ( $response->isFault() )
{
    print( "SOAP fault: " . $response->faultCode(). " - " . $response->faultString() . "" );
}
else
{
    print( "Returned SOAP value was: \"" . $response->value() . "\"<br/>" );

    foreach ( $response->value() as $item )
    {
        print( "&nbsp;" . $item . "<br/>" );
    }
}

?>
