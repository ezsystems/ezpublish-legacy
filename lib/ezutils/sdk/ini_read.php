<?php

include_once( "lib/ezutils/classes/ezini.php" );

$ini =& eZINI::instance( "test.ini", "lib/ezutils/sdk" );

$variables = array( "Group1" => array( "Variable1",
                                       "Variable2",
                                       "Variable3" ),
                    "Group2" => array( "Variable1" ) );

print( "<p>Test example, variables are from the ini file test.ini</p>
<table cellpadding=\"3\" cellspacing=\"0\" width=\"100%\">
<tr>
  <th>Group</th><th>Variable</th><th>Value</th>
</tr>" );

$i = 0;
foreach( $variables as $variable_group => $variable_item )
{
    $items = $variable_item;
    foreach( $items as $item )
    {
        $list_class = ( $i % 2 == 0 ? "bglight" : "bgdark" );
        $var = $ini->variable( $variable_group, $item );
        print( "<tr><td class=\"$list_class\">$variable_group</td><td class=\"$list_class\">$item</td><td class=\"$list_class\">\"" );
//         if ( is_array( $var ) )
//             print_r( $var );
//         else
        $var = preg_replace( "#\n#", "^J", $var );
        $var = preg_replace( "#\r#", "^M", $var );
        print( $var );
        print( "\"</td><td class=\"$list_class\" width=\"99%\"></td></tr>" );
        ++$i;
    }
}

print( "</table>\n" );
print( "<p>Reading ordered variables from group <b>Group1</b></p>
<pre>" );
$vars = $ini->group( "Group1", true );
print_r( $vars );

print( "</pre>
<h2>Contents of test.ini</h2>" );
print( "<pre class=\"example\">" );
readfile( "lib/ezutils/sdk/test.ini" );
print( "</pre>" );

$Result["title"] = "Reading from INI file";

?>
