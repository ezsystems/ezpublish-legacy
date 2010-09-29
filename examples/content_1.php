<?php
/**
 * Stub on ezpContent
 * Illustrates attributes retrieval
 */
$out = new ezcConsoleOutput();

try {
    $folder = ezpContent::fromNodeId( 2 );
    $folder->setActiveLanguage( 'eng-GB' );
    $out->outputText( "Name: " );
    $out->outputLine( (string)$folder->fields->name );
} catch( Exception $e ) {
    $out->outputLine( "An exception has occured:" );
    $out->outputLine( $e->getMessage() );
}
?>