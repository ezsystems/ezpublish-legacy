<?php
//
// $Id$
//
// Definition of eZSOAPClient class
//
// Bård Farstad <bf@ez.no>
// Created on: <19-Feb-2002 15:42:03 bf>
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZSOAPClient ezsoapclient.php
  \ingroup eZSOAP
  \brief eZSOAPClient is a class which can be used as a SOAP client

  eZSOAPClient handles communication with a SOAP server.

  \code

// include client classes
include_once( "lib/ezsoap/classes/ezsoapclient.php" );
include_once( "lib/ezsoap/classes/ezsoaprequest.php" );

// create a new client
$client = new eZSOAPClient( "nextgen.bf.dvh1.ez.no", "/sdk/ezsoap/view/server" );

$namespace = "http://soapinterop.org/";

// create the SOAP request object
$request = new eZSOAPRequest( "addNumbers", "http://calkulator.com/simplecalculator" );

// add parameters to the request
$request->addParameter( "valueA", 42 );
$request->addParameter( "valueB", 17 );

// send the request to the server and fetch the response
$response =& $client->send( $request );

// check if the server returned a fault, if not print out the result
if ( $response->isFault() )
{
    print( "SOAP fault: " . $response->faultCode(). " - " . $response->faultString() . "" );
}
else
    print( "Returned SOAP value was: \"" . $response->value() . "\"" );
  \endcode

  \sa eZSOAPServer eZSOAPRequest eZSOAPResponse

*/

include_once( "lib/ezsoap/classes/ezsoapresponse.php" );
include_once( "lib/ezutils/classes/ezdebug.php" );

class eZSOAPClient
{
    /*!
      Creates a new SOAP client.
    */
    function eZSOAPClient( $server, $path, $port=80 )
    {
        $this->Login = "";
        $this->Password = "";
        $this->Server = $server;
        $this->Path = $path;
        $this->Port = $port;
    }

    /*!
      Sends a SOAP message and returns the response object.
    */
    function &send( $request )
    {
        if ( $this->Timeout != 0 )
        {
            $fp = fsockopen( $this->Server,
            $this->Port,
            $this->errorNumber,
            $this->errorString,
            $this->Timeout );
        }
        else
        {
            $fp = fsockopen( $this->Server,
            $this->Port,
            $this->errorNumber,
            $this->errorString );
        }

        $payload =& $request->payload();

        eZDebug::writeNotice( $payload, "myload" );


        if ( $fp != 0 )
        {
            $authentification = "";

            if ( ( $this->login() != "" ) )
            {
                $authentification = "Authorization: Basic " . base64_encode( $this->login() . ":" . $this->password() ) . "\r\n" ;
            }

            $HTTPRequest = "POST " . $this->Path . " HTTP/1.0\r\n" .
                 "User-Agent: eZ soap client\r\n" .
                 "Host: " . $this->Server . "\r\n" .
                 $authentification .
                 "Content-Type: text/xml\r\n" .
                 "Content-Length: " . strlen( $payload ) . "\r\n\r\n" .
                 $payload;

            eZDebug::writeNotice( $HTTPRequest, "Request" );

            if ( !fputs( $fp, $HTTPRequest, strlen( $HTTPRequest ) ) )
            {
                $this->ErrorString = "<b>Error:</b> could not send the SOAP request. Could not write to the socket.";
                return 0;
            }
        }

        $rawResponse = "";

        // fetch the SOAP response
        while ( $data=&fread( $fp, 32768 ) )
        {
            $rawResponse .= $data;
        }
        eZDebug::writeNotice( $rawResponse, "Response" );

        $response = new eZSOAPResponse( );
        $response->decodeStream( $request, $rawResponse );

        // close the socket
        fclose( $fp );

        return $response;
    }

    /*!
     Sets the HTTP login
    */
    function setLogin( $login  )
    {
        $this->Login = $login;
    }

    /*!
      Returns the login, used for HTTP authentification
    */
    function login()
    {
        return $this->Login;
    }

    /*!
     Sets the HTTP password
    */
    function setPassword( $password  )
    {
        $this->Password = $password;
    }

    /*!
      Returns the password, used for HTTP authentification
    */
    function password()
    {
        return $this->Password;
    }

    /// The name or IP of the server to communicate with
    var $Server;
    /// The path to the SOAP server
    var $Path;
    /// The port of the server to communicate with.
    var $Port;
    /// How long to wait for the call.
    var $Timeout = 0;
    /// HTTP login for HTTP authentification
    var $Login;
    /// HTTP password for HTTP authentification
    var $Password;
}

?>
