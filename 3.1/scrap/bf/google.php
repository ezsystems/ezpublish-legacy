<h1>Google search</h1>
<form method="post">
<input type="text" name="Text" value="<? print( $Text ); ?>"/>
<input type="submit"/>
</form>
<?php
include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezini.php" );
include_once( "lib/ezutils/classes/ezsys.php" );

include_once( "lib/ezsoap/classes/ezsoapclient.php" );
include_once( "lib/ezsoap/classes/ezsoaprequest.php" );



$client = new eZSOAPClient( "api.google.com", "/search/beta2" );


$request = new eZSOAPRequest( "doGoogleSearch", "urn:GoogleSearch" );

$request->addParameter( "key", "wyxk3M5MPjZsZ7C8H+Ey66FpFq6HyWRz" );
$request->addParameter( "q", "pink floyd" );

$request->addParameter( "start", 0 );
$request->addParameter( "maxResults", 10 );

$request->addParameter( "filter", false );
$request->addParameter( "restrict", "" );
$request->addParameter( "safesearch", false );
$request->addParameter( "lr", "" );
$request->addParameter( "ie", "latin1" );
$request->addParameter( "oe", "latin1" );


/*


$request = new eZSOAPRequest( "doSpellingSuggestion", "urn:GoogleSearch" );
$request->addParameter( "key", "wyxk3M5MPjZsZ7C8H+Ey66FpFq6HyWRz" );
$request->addParameter( "phrase", $Text );
*/

/*
$request = new eZSOAPRequest( "doGetCachedPage", "urn:GoogleSearch" );
$request->addParameter( "key", "wyxk3M5MPjZsZ7C8H+Ey66FpFq6HyWRz" );
$request->addParameter( "uri", "http://ez.no" );
*/

$response =& $client->send( $request );

if ( $response->isFault() )
{
    print( "SOAP fault: " . $response->faultCode(). " - " . $response->faultString() . "" );
}
else
    print( "Returned SOAP value was: \"" . $response->value() . "\"" );



    eZDebug::printReport( );
?>

"http://api.google.com/search/beta2"
