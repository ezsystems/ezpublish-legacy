<?php
//
// Definition of eZSOAPServer class
//
// Created on: <14-May-2002 10:45:38 bf>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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
  \class eZSOAPServer ezsoapserver.php
  \ingroup eZSOAP
  \brief The class eZSOAPServer handles SOAP server requensts

  Sample code for a SOAP server with one function, addNumbers.
  \code
include_once( "lib/ezsoap/classes/ezsoapserver.php" );

$server = new eZSOAPServer( );
$server->registerFunction( "addNumbers", array( "valueA" => "integer", "valueB" => "integer" ) );
$server->registerObject( "Collection" );
$server->processRequest();

function addNumbers( $valueA, $valueB )
{
    $return = $valueA + $valueB;
    settype( $return, "integer" );
    return $return;
}

class Collection
{
    function Collection ()
    {

    }
    function subNumbers( $valueA, $valueB )
    {
        $return = $valueA - $valueB;
        settype( $return, "integer" );
        return $return;
    }
}
  \endcode
  \sa eZSOAPClient eZSOAPRequest eZSOAPResponse

*/

include_once( "lib/ezsoap/classes/ezsoaprequest.php" );
include_once( "lib/ezsoap/classes/ezsoapfault.php" );
include_once( "lib/ezsoap/classes/ezsoapresponse.php" );
include_once( "lib/ezxml/classes/ezxml.php" );

class eZSOAPServer
{
    /*!
      Creates a new eZSOAPServer object.
    */
    function eZSOAPServer()
    {
        global $HTTP_RAW_POST_DATA;
        $this->RawPostData = $HTTP_RAW_POST_DATA;
    }


    function showResponse( $functionName, $namespaceURI, &$value )
    {
        // Convert input data to XML
        $response = new eZSOAPResponse( $functionName, $namespaceURI );
        $response->setValue( $value );

        $payload = $response->payload();

        header( "SOAPServer: eZ soap" );
        header( "Content-Type: text/xml; charset=\"UTF-8\"" );
        Header( "Content-Length: " . strlen( $payload ) );

        ob_end_clean();

        print( $payload );
    }

    /*!
      Registers all functions of an object on the server.

      Returns false if the object could not be registered.
    */
    function registerObject( $objectName, $includeFile = null )
    {
        if ( file_exists( $includeFile ) )
            include_once( $includeFile );

        if ( class_exists( $objectName ) )
        {
            $methods = get_class_methods( $objectName );
            foreach ( $methods as $method)
            {
                if ( strcasecmp ( $objectName, $method ) )
                    $this->registerFunction( $objectName."::".$method );
            }
            return true;
        }
        else
        {
            return false;
        }
    }

    /*!
      Processes the SOAP request and prints out the
      propper response.
    */
    function processRequest()
    {
        global $HTTP_SERVER_VARS;

        if ( $HTTP_SERVER_VARS["REQUEST_METHOD"] != "POST" )
        {
            print( "Error: this web page does only understand POST methods" );
            exit();
        }

        $xmlData =& $this->stripHTTPHeader( $this->RawPostData );

        $xml = new eZXML();

        $dom =& $xml->domTree( $xmlData );

        // Check for non-parsing XML, to avoid call to non-object error.
        if ( !is_object( $dom ) )
        {
            $this->showResponse( 'unknown_function_name', 'unknown_namespace_uri',
                                 new eZSOAPFault( 'Server Error',
                                                  'Bad XML' ) );
            return;
        }

        // add namespace fetching on body
        // get the SOAP body
        $body =& $dom->elementsByName( "Body" );

        $children = $body[0]->children();

        if ( count( $children ) == 1 )
        {
            $requestNode =& $children[0];
            // get target namespace for request
            $functionName = $requestNode->name();
            $namespaceURI = $requestNode->namespaceURI();

            $params = array();
            // check parameters
            foreach ( $requestNode->children() as $parameterNode )
            {
                $params[] =& eZSOAPResponse::decodeDataTypes( $parameterNode );
            }

            list( $objectName, $objectFunctionName ) = preg_split('/::/', $functionName, 2, PREG_SPLIT_NO_EMPTY);
            if ( !$objectFunctionName and in_array( $functionName, $this->FunctionList ) &&
                 function_exists( $functionName ) )
            {
                $this->showResponse( $functionName, $namespaceURI,
                                     call_user_func_array( $functionName, $params ) );
            }
            else if ( $objectName and $objectFunctionName )
            {
                if ( !class_exists( $objectName ) )
                {
                    $this->showResponse( $functionName, $namespaceURI,
                                         new eZSOAPFault( 'Server Error',
                                                          'Object not found' ) );
                }
                else
                {
                    $object = new $objectName();
                    if ( !method_exists( $object, $objectFunctionName ) )
                    {
                        $this->showResponse( $functionName, $namespaceURI,
                                     new eZSOAPFault( 'Server Error',
                                                      'Objectmethod not found' ) );
                    }
                    else
                    {
                        $this->showResponse( $functionName, $namespaceURI,
                                     call_user_method_array( $objectFunctionName, $object, $params ) );
                    }
                }
            }
            else
            {
                $this->showResponse( $functionName, $namespaceURI,
                                     new eZSOAPFault( 'Server Error',
                                                      'Method not found' ) );
            }
        }
        else
        {
            // error
            $this->showResponse( $functionName, $namespaceURI,
                                 new eZSOAPFault( 'Server Error',
                                                  '"Body" element in the request '.
                                                  'has wrong number of children' ) );

        }
    }

    /*!
      Registers a new function on the server.

      Returns false if the function could not be registered.
    */
    function registerFunction( $name, $params=array() )
    {
        $this->FunctionList[] = $name;
    }


    /*!
      \static
      \private
      Strips the header information from the HTTP raw response.
    */
    function &stripHTTPHeader( $data )
    {
        $start = strpos( $data, "<?xml version=\"1.0\"?>" );
        $data = substr( $data, $start, strlen( $data ) - $start );
        return $data;
    }

    /// Contains a list over registered functions
    var $FunctionList;
    /// Contains the RAW HTTP post data information
    var $RawPostData;
}

?>
