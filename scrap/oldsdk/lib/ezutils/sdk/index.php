<?php

$infoArray = array();
$infoArray["name"] = "eZ utils";
$infoArray["description"] = "
<h1>eZ utils&trade;</h1>
<p>
eZ utils&trade; is a collection of small utility classes that are very useful when doing
PHP development. The utils handle debug information, ini file access, system information
and http tools.
</p>

<p>eZ ini&trade; provides functionality to access and write .ini style configuration files. The
files are compiled to PHP files, so the library is very fast.
</p>

<p>eZ debug&trade; is used to handle debug information.</p>
<ul>
<li>It can display information on screen and/or write it to log files.</li>
<li>You can enable on-screen debug information for specific IP addresses.</li>
<li>Timing points can be placed in the code to time the different sections of code.</li>
<li>PHP errors can be captured and handled by eZ debug.</li>
</ul>

<p>
eZ sys&trade; analyzes the system for various settings.
The system is checked to see whether a virtualhost-less setup is used
and sets the appropriate variables which can be easily fetched.
It also detects file and environment separators.
</p>

<p>
eZ module&trade; is interface for doing separated module/view handling. Each module is a separate
group of views and functions. Each view is linked to a URL in the web page, but may also be reused
by other modules/views. The class makes sure that the code doesn't have to read any URLs or HTTP
variables. Instead it relies on having more abstract ways of doing this, such as actions.
</p>

<p>
eZ httptool&trade; has various classes for HTTP access. It can read post, get and session variables,
make objects persist between page views, fetch posted files and elegantly handle URIs.
</p>
";

$exampleArray = array();
$exampleArray[] = array( "uri" => "ini_introduction",
                         "name" => "Introduction to INI files" );
$exampleArray[] = array( "uri" => "ini_read",
                         "name" => "Reading from INI files" );
$exampleArray[] = array( "uri" => "debug_settings",
                         "level" => 0,
                         "name" => "Debugging" );
$exampleArray[] = array( "uri" => "sys_settings",
                         "name" => "System settings" );
$exampleArray[] = array( "uri" => "module_handling",
                         "name" => "Module handling" );

$exampleArray[] = array( "level" => 0,
                         "name" => "HTTP" );
$exampleArray[] = array( "uri" => "http_variables",
                         "level" => 0,
                         "name" => "Fetching variables" );
$exampleArray[] = array( "uri" => "http_files",
                         "level" => 0,
                         "name" => "Fetching files" );
$exampleArray[] = array( "uri" => "http_persistence",
                         "level" => 0,
                         "name" => "Object persistence" );
$exampleArray[] = array( "uri" => "uri",
                         "level" => 0,
                         "name" => "URI management" );

$infoArray["features"] =& $exampleArray;

$docArray = array();
$docArray[] = array( "uri" => "eZINI",
                     "name" => "INI file reader" );
$docArray[] = array( "uri" => "eZDebug",
                     "name" => "Debug/log handler" );
$docArray[] = array( "uri" => "eZSys",
                     "name" => "System analyzer" );
$docArray[] = array( "uri" => "eZModule",
                     "name" => "Module system handler" );
$docArray[] = array( "uri" => "eZProcess",
                     "name" => "Process runner" );
$docArray[] = array( "uri" => "eZHTTPTool",
                     "name" => "HTTP variable access" );
$docArray[] = array( "uri" => "eZHTTPFile",
                     "name" => "HTTP file handler" );
$docArray[] = array( "uri" => "eZHTTPPersistence",
                     "name" => "HTTP persistence" );
$docArray[] = array( "uri" => "eZURI",
                     "name" => "URI handler" );

$infoArray["doc"] =& $docArray;

?>
