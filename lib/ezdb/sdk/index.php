<?php

$infoArray = array();
$infoArray["name"] = "eZ db";
$infoArray["description"] = "
<h1>eZ db&trade;</h1>
<p>eZ db&trade; provides database wrapper functions.</p>
<p>The eZ db library provides a database independent framework for
  SQL databases. The current supported databases are:</p>
 <ul><li>PostgreSQL</li><li>MySQL</li><li>Oracle</li></ul>

<p>eZ db is designed to be used with the following type subset of SQL:
  int, float, varchar and text ( clob in oracle ).</p>

<p>To store date and time values int's are used. eZ locale is used to
  present the date and times on a localized format. That way we don't have
  to worry about the different date and time formats used in the different
  databases.</p>

<p>Auto incrementing numbers, sequences, are used to generate unique id's
  for a table row. This functionality is abstracted as it works different
  in the different databases.</p>

<p>Limit and offset functionality is also abstracted by the eZ db library.</p>

<p>eZ db is designed to use lowercase in all table/column names. This is
  done to prevent errors as the different databases handles this differently.
  Especially when returning the data as an associative array.</p>
";

$dependArray = array();
$dependArray[] = array( "uri" => "ezutils",
                        "name" => "eZ utils" );
$dependArray[] = array( "uri" => "ezi18n",
                        "name" => "eZ i18n" );

$infoArray["dependencies"] =& $dependArray;

$exampleArray = array();
$exampleArray[] = array( "uri" => "introduction",
                         "level" => 1,
                         "name" => "Introduction" );
$exampleArray[] = array( "uri" => "fetch",
                         "level" => 1,
                         "name" => "Fetch data" );
$exampleArray[] = array( "uri" => "store",
                         "level" => 1,
                         "name" => "Store data" );

$infoArray["features"] =& $exampleArray;

$docArray = array();
$docArray[] = array( "uri" => "eZDB",
                     "name" => "Main DB facade" );
$docArray[] = array( "uri" => "eZMySQLDB",
                     "name" => "MySQL DB interface" );
$docArray[] = array( "uri" => "eZOracleDB",
                     "name" => "Oracle DB interface" );
$docArray[] = array( "uri" => "eZPostgreSQLDB",
                     "name" => "PostgreSQL DB interface" );

$infoArray["doc"] =& $docArray;

?>
