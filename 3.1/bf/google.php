<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">
<head>
  <title>Google sÃ¸k</title>
</head>
<body>
<h2>Google sÃ¸k via eZ soap</h2>
<form method="get">
  <input type="text" size="40" name="q" value="<? print( $q ); ?>" />
  <input type="submit" value="SÃ¸k" />
</form>
<?php
include_once( "lib/ezsoap/classes/ezsoapclient.php" );
include_once( "lib/ezsoap/classes/ezsoaprequest.php" );

// Opprett SOAP klient for kommunikasjon mot Googles API
$client = new eZSOAPClient( "api.google.com", "/search/beta2" );

// Vi skal kalle opp funksjonen doGoogleSearch med namespace URI urn:GoogleSearch
$request = new eZSOAPRequest( "doGoogleSearch", "urn:GoogleSearch" );

if ( $q != "" )
{
    // Vi sender med vår nøkkel
    $request->addParameter( "key", "wyxk3M5MPjZsZ7C8H+Ey66FpFq6HyWRz" );
    // Søketeksten
    $request->addParameter( "q", $q );

    // Plassering i søket
    $request->addParameter( "start", 0 );
    // Antall treff pr side, 10 er maks
    $request->addParameter( "maxResults", 10 );

    // Standardverdier på diverse parameter
    $request->addParameter( "filter", true );
    $request->addParameter( "restrict", "" );
    $request->addParameter( "safesearch", false );
    $request->addParameter( "lr", "" );
    $request->addParameter( "ie", "latin1" );
    $request->addParameter( "oe", "latin1" );

    // Send selve kallet
    $response =& $client->send( $request );

    // Sjekk om det oppstod en feil
    if ( $response->isFault() )
    {
        print( "SOAP fault: " . $response->faultCode(). " - " . $response->faultString() . "" );
    }
    else
    {
        $value =& $response->value();

        // Alt gikk bra, skrif ut antall treff
        print( "<p>Antall treff: " . $value["estimatedTotalResultsCount"] . ".</p>" );

        // Skriv ut alle søketreff
        $searchResult = $value["resultElements"];
        foreach ( $searchResult as $item )
        {
            $snippet = $item["snippet"];
            $size = $item["cachedSize"];

            print( "<font color='green'>" . $item["title"] . "</font><br>" );
            print( "$snippet<br>" );

            $url = $item["URL"];
            print( "<a href='$url'>$url</a> $size<br><br>" );
        }
    }
}
?>
</body>
</html>
