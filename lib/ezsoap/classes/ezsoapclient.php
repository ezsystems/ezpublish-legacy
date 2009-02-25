<?php
//
// $Id$
//
// Definition of eZSOAPClient class
//
// Bård Farstad <bf@ez.no>
// Created on: <19-Feb-2002 15:42:03 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*!
  \class eZSOAPClient ezsoapclient.php
  \ingroup eZSOAP
  \brief eZSOAPClient is a class which can be used as a SOAP client

  eZSOAPClient handles communication with a SOAP server.

  \code

// include client classes
//include_once( "lib/ezsoap/classes/ezsoapclient.php" );
//include_once( "lib/ezsoap/classes/ezsoaprequest.php" );

// create a new client
$client = new eZSOAPClient( "nextgen.bf.dvh1.ez.no", "/sdk/ezsoap/view/server" );

$namespace = "http://soapinterop.org/";

// create the SOAP request object
$request = new eZSOAPRequest( "addNumbers", "http://calkulator.com/simplecalculator" );

// add parameters to the request
$request->addParameter( "valueA", 42 );
$request->addParameter( "valueB", 17 );

// send the request to the server and fetch the response
$response = $client->send( $request );

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

//include_once( "lib/ezsoap/classes/ezsoapresponse.php" );
require_once( "lib/ezutils/classes/ezdebug.php" );

class eZSOAPClient
{
    /*!
      Creates a new SOAP client.

      \param $server The remote server to connect to
      \param $path The path to the SOAP service on the remote server
      \param $port The port to connect to, 80 by default. You can use 'ssl' as well to specify that you want to use port 443 over SSL,
                   but omit the last parameter $useSSL of this method then or set it to true. When $port equals 443, SSL will also be
                   used if $useSSL is omitted or set to true.
      \param $useSSL If we need to connect to the remote server with (https://) or without (http://) SSL
    */
    function eZSOAPClient( $server, $path = '/', $port = 80, $useSSL = null )
    {
        $this->Login = "";
        $this->Password = "";
        $this->Server = $server;
        $this->Path = $path;
        $this->Port = $port;
        if ( is_numeric( $port ) )
        {
            $this->Port = $port;

            if ( $port == 443 )
            {
                $this->UseSSL = true;
            }
        }
        elseif ( strtolower( $port ) == 'ssl' )
        {
            $this->UseSSL = true;
            $this->Port = 443;
        }
        else
        {
            $this->Port = 80;
        }

        if ( $useSSL === true )
        {
            $this->UseSSL = true;
        }
        else if ( $useSSL === false )
        {
            $this->UseSSL = false;
        }
    }

    /*!
      Sends a SOAP message and returns the response object.
    */
    function send( $request )
    {
        if ( !$this->UseSSL || !in_array( "curl", get_loaded_extensions() ) )
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

            if ( $fp == 0 )
            {
                $this->ErrorString = '<b>Error:</b> eZSOAPClient::send() : Unable to open connection to ' . $this->Server . '.';
                return 0;
            }

            $payload = $request->payload();

            $authentification = "";
            if ( ( $this->login() != "" ) )
            {
                $authentification = "Authorization: Basic " . base64_encode( $this->login() . ":" . $this->password() ) . "\r\n" ;
            }

            $HTTPRequest = "POST " . $this->Path . " HTTP/1.0\r\n" .
                "User-Agent: eZ soap client\r\n" .
                "Host: " . $this->Server . ":" . $this->Port . "\r\n" .
                $authentification .
                "Content-Type: text/xml\r\n" .
                "SOAPAction: \"" . $request->namespace() . '/' . $request->name() . "\"\r\n" .
                "Content-Length: " . strlen( $payload ) . "\r\n\r\n" .
                $payload;

            if ( !fputs( $fp, $HTTPRequest, strlen( $HTTPRequest ) ) )
            {
                $this->ErrorString = "<b>Error:</b> could not send the SOAP request. Could not write to the socket.";
                $response = 0;
                return $response;
            }

            $rawResponse = "";
            // fetch the SOAP response
            while ( $data = fread( $fp, 32768 ) )
            {
                $rawResponse .= $data;
            }

            // close the socket
            fclose( $fp );
        }
        else //SOAP With SSL
        {
            if ( $request instanceof eZSOAPRequest )
            {
                $URL = "https://" . $this->Server . ":" . $this->Port . $this->Path;
                $ch = curl_init ( $URL );
                if ( $this->Timeout != 0 )
                {
                    curl_setopt( $ch, CURLOPT_TIMEOUT, $this->TimeOut );
                }
                $payload = $request->payload();

                if ( $ch != 0 )
                {
                    $HTTPCall = "POST " . $this->Path . " HTTP/1.0\r\n" .
                        "User-Agent: eZ soap client\r\n" .
                        "Host: " . $this->Server . ":" . $this->Port . "\r\n" .
                        "Content-Type: text/xml\r\n" .
                        "SOAPAction: \"" . $request->namespace() . '/' . $request->name() . "\"\r\n" .
                        "Content-Length: " . strlen( $payload ) . "\r\n";
                    if ( $this->login() != '' )
                    {
                        $HTTPCall .= "Authorization: Basic " .  base64_encode( $this->login() . ":" . $this->Password() ) . "\r\n";
                    }
                    $HTTPCall .= "\r\n" . $payload;

                    curl_setopt( $ch, CURLOPT_URL, $URL );
                    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
                    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 1 );
                    curl_setopt( $ch, CURLOPT_HEADER, 1 );
                    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                    curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $HTTPCall );  // Don't use CURLOPT_CUSTOMREQUEST without making sure your server supports the custom request method first.
                    unset( $rawResponse );

                    if ( $ch != 0 )
                    {
                        $rawResponse = curl_exec( $ch );
                    }
                    if ( !$rawResponse )
                    {
                        $this->ErrorString = "<b>Error:</b> could not send the XML-SOAP with SSL call. Could not write to the socket.";
                        $response = 0;
                        return $response;
                    }
                }

                curl_close( $ch );
            }
        }
        $response = new eZSOAPResponse();
        $response->decodeStream( $request, $rawResponse );
        return $response;
    }

    /*!
     Set timeout value

     \param timeout value in seconds. Set to 0 for unlimited.
    */
    function setTimeout( $timeout )
    {
        $this->Timeout = $timeout;
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
    public $Server;
    /// The path to the SOAP server
    public $Path;
    /// The port of the server to communicate with.
    public $Port;
    /// How long to wait for the call.
    public $Timeout = 0;
    /// HTTP login for HTTP authentification
    public $Login;
    /// HTTP password for HTTP authentification
    public $Password;
    private $UseSSL;
}

?>
