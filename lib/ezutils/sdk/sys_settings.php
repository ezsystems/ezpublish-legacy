<?php

include_once( "lib/ezutils/classes/ezsys.php" );

$sys =& eZSys::instance();

print( "<table style=\"border: 1px black;\" cellspacing=\"4\">
<tr><th>Attribute</th><th>Value</th></tr>" );
print( "<tr><td>FileSeparator:</td><td>" . $sys->fileSeparator() . "</td></tr>" );
print( "<tr><td>EnvSeparator:</td><td>" . $sys->envSeparator() . "</td></tr>" );
print( "<tr><td>SiteDir:</td><td>" . $sys->siteDir() . "</td></tr>" );
print( "<tr><td>WWWDir:</td><td>" . $sys->wwwDir() . "</td></tr>" );
print( "<tr><td>IndexFile:</td><td>" . $sys->indexFile() . "</td></tr>" );
print( "</table>" );


?>
