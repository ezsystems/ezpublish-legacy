<h1>System settings</h1>

<p>
eZ sys&trade; analyzes the system for various settings.
The system is checked to see whether a virtualhost-less setup is used
and sets the appropriate variables which can be easily fetched.
It also detects file and environment separators.
</p>

<p>These are some settings you can read in your code:</p>
<pre class="example">
include_once( "lib/ezutils/classes/ezsys.php" );

$sys =& eZSys::instance();

$sys->fileSeparator();
$sys->envSeparator();
$sys->siteDir();
$sys->wwwDir();
$sys->indexFile();
</pre>


<?php

// include_once( "lib/ezutils/classes/ezsys.php" );

// $sys =& eZSys::instance();

// print( "<table style=\"border: 1px black;\" cellspacing=\"4\">
// <tr><th>Attribute</th><th>Value</th></tr>" );
// print( "<tr><td>FileSeparator:</td><td>" . $sys->fileSeparator() . "</td></tr>" );
// print( "<tr><td>EnvSeparator:</td><td>" . $sys->envSeparator() . "</td></tr>" );
// print( "<tr><td>SiteDir:</td><td>" . $sys->siteDir() . "</td></tr>" );
// print( "<tr><td>WWWDir:</td><td>" . $sys->wwwDir() . "</td></tr>" );
// print( "<tr><td>IndexFile:</td><td>" . $sys->indexFile() . "</td></tr>" );
// print( "</table>" );


?>
