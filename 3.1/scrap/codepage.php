<?php

include_once( "lib/ezi18n/classes/ezcodepage.php" );

$codepage1 = new eZCodePage( "cp850", false );
$codepage2 = new eZCodePage( "iso-8859-15", false );

$string = "aaaaabcdef¤";

$result = "";

for( $i = 0; $i < strlen( $string ); ++$i )
{
    $char = $string[$i];
    $char_uni = $codepage1->charToUnicode( $char );
    $char_result = $codepage2->unicodeToChar( $char_uni );
    $result .= $char_result;
}

print( "orig  : $string\n");
print( "result: $result\n");

?>
