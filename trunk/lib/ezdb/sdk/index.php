<?php

$infoArray = array();
$infoArray["name"] = "eZ db";
$infoArray["description"] = "
<h1>eZ db&trade;</h1>
<p>eZ db is a database abstraction library. It provides a uniform interface
to MySQL and PostgreSQL.</p>
<p>The eZ db library provides a database independent framework for
  SQL databases. The current supported databases are:</p>
 <ul><li>PostgreSQL</li><li>MySQL</li></ul>

<p>To make it easier to support the different databases we have defined a subset
of SQL data types to use. The types used are:</p>
<ul>
<li>int - integers, date and time as UNIX timestamp, enums and boolean</li>
<li>float - float and prices</li>
<li>varchar - short text strings (less than 255 characters)</li>
<li>text - large text objects like article contents</li>
</ul>

<p>To store date and time values ints are used. eZ locale is used to
  present the date and times on a localized format. That way we don't have
  to worry about the different date and time formats used in the different
  databases.</p>

<p>Auto incrementing numbers, sequences, are used to generate unique ids
  for a table row. This functionality is abstracted as it works differently
  in the different databases.</p>

<p>Limit and offset functionality is also abstracted by the eZ db library.</p>

<p>eZ db is designed to use lowercase in all table/column names. This is
  done to prevent errors as the different databases handle this differently,
  especially when returning the data as an associative array.</p>

<h2>Useful links</h2>
<ul>
<li><a href='http://www.mysql.com'>MySQL</a></li>
<li><a href='http://postgresql.org'>PostgreSQL</a></li>
</ul>
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
$docArray[] = array( "uri" => "eZPostgreSQLDB",
                     "name" => "PostgreSQL DB interface" );

$infoArray["doc"] =& $docArray;

?>
