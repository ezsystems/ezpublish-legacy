<?php

include_once( "lib/eztemplate/classes/eztemplate.php" );


$tpl = new eZTemplate();

// $quotes = array( "'a simple \\'test'",
//                  '"another quote \\"test"',
//                  "'a'",
//                  '"b"' );

// foreach( $quotes as $quote_str )
// {
//     $quote_end = $tpl->quoteEndPos( $quote_str, 1, strlen( $quote_str ), $quote_str[0] );
//     print( "Quote end=$quote_end, quote=/" . substr( $quote_str, 1, $quote_end - 1 ) . "/, orig=/$quote_str/\n" );
// }

// $idents = array( "one",
//                  "2",
//                  "thr33",
//                  "and-a-4",
//                  "an_advanced_identifier234_a-b_c - - -" );
// foreach( $idents as $ident_str )
// {
//     $ident_end = $tpl->identifierEndPos( $ident_str, 0, strlen( $ident_str ) );
//     print( "Identifier end=$ident_end, identifier=/" . substr( $ident_str, 0, $ident_end - 0 ) . "/, orig=/$ident_str/\n" );
// }

// $numerics = array( "1",
//                    "100",
//                    "1.2",
//                    ".2",
//                    ".",
//                    "1.2.3" );
// foreach( $numerics as $numeric_str )
// {
//     $numeric_end = $tpl->numericEndPos( $numeric_str, 0, strlen( $numeric_str ) );
//     print( "Numeric end=$numeric_end, numeric=/" . substr( $numeric_str, 0, $numeric_end - 0 ) . "/, orig=/$numeric_str/\n" );
// }

$vars = array( "gt",
               "lt",
               "lt()",
               "lt(1,2)",
               "lt(1,2)|gt(1)",
               "\$my_advanced",
               "\$Name:var",
               "\$Name:space:var",
               "\$Name:var.attr",
               "\$Name:var.attr.\$index.first",
               "\$Name:var.attr[\$index.first]",
               "\$Name:var.attr[\$index].first",
               "\$nspace:myvar[\$index]",
               "\$nspace:myvar[\$index|gt(4)|choose('a','b')]",
               "\$nspace:myvar[\$index[\$index2.abc[\$index3]]]",
               "\$a|test(,,b)",
               "\$b|image(abc)|do_it"
               );
// $vars = array( "array(1,2,4).1" );
$vars = array( "\$a.a.abc" );
foreach( $vars as $var_str )
{
    $var_struct = $tpl->parseVariableTag( $var_str, 0, $var_end, strlen( $var_str ), "" );
    print( "Var end=$var_end, var=/" . substr( $var_str, 0, $var_end - 0 ) . "/, orig=/$var_str/\n" );
    print_r( $var_struct );
}

eZDebug::printReport( false, false );


?>
