<?php
//
// Created on: <16-May-2002 14:17:46 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

?>
<h1>Simple calculator</h1>
<p>
This simple calculator application sends two integer values to the server.
The server adds these numbers and returns the result. This shows how you can
add parameters to your request and how eZ soap&trade; handles the data types.
</p>

<h2>Sending request parameters</h2>
<p>
You can add request parameters with the <b>addParameter</b> function.
</p>

<pre class="example">
$request->addParameter( "valueA", 42 );
$request->addParameter( "valueB", 17 );
</pre>

<h2>Server handling of parameters</h2>
<p>
On the server you have to register the function with the parameters and the types for the
parameters. Since PHP is a loosely typed language we also have to set the type of the return
value so that eZ soap&trade; knows what type to encode the response as.
</p>

<pre class="example">
include_once( "lib/ezsoap/classes/ezsoapserver.php" );

$server = new eZSOAPServer( );
$server->registerFunction( "addNumbers", array( "valueA" => "integer", "valueB" => "integer" ) );
$server->processRequest();

function addNumbers( $valueA, $valueB )
{
    $return = $valueA + $valueB;
    settype( $return, "integer" );
    return $return;
}
</pre>

<?php
// <h2>The running example</h2>
// <?php
// include_once( "lib/ezsoap/classes/ezsoapclient.php" );
// include_once( "lib/ezsoap/classes/ezsoaprequest.php" );

// $client = new eZSOAPClient( eZSys::hostname(), eZSys::wwwDir() .  eZSys::indexFile() . "/sdk/ezsoap/view/server" );

// $namespace = "http://soapinterop.org/";

// $request = new eZSOAPRequest( "addNumbers", "http://calkulator.com/simplecalculator" );

// $request->addParameter( "valueA", 42 );
// $request->addParameter( "valueB", 17 );

// $response =& $client->send( $request );

// if ( $response->isFault() )
// {
//     print( "SOAP fault: " . $response->faultCode(). " - " . $response->faultString() . "" );
// }
// else
//     print( "Returned SOAP value was: \"" . $response->value() . "\"" );

?>
