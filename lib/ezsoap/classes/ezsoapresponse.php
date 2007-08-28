<?php
//
// $Id$
//
// Definition of eZSOAPResponse class
//
// Bård Farstad <bf@ez.no>
// Created on: <19-Feb-2002 16:51:10 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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
  \class eZSOAPResponse ezsoapresponse.php
  \ingroup eZSOAP
  \brief eZSOAPResponse handles SOAP response packages

*/

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( 'lib/ezsoap/classes/ezsoapcodec.php' );
include_once( "lib/ezsoap/classes/ezsoapenvelope.php" );

class eZSOAPResponse extends eZSOAPEnvelope
{
    /*!
      Constructs a new SOAP response
    */
    function eZSOAPResponse( $name="", $namespace="" )
    {
        $this->Name = $name;
        $this->Namespace = $namespace;

        // call the parents constructor
        $this->eZSOAPEnvelope();
    }

    /*!
      Decodes the SOAP response stream
    */
    function decodeStream( $request, $stream )
    {
        $dom = new DOMDocument( "1.0" );

        $dom->loadXML( $this->stripHTTPHeader( $stream ) );
        $this->DOMDocument = $dom;

        if ( !empty( $dom ) )
        {
            // check for fault
            $response = $dom->getElementsByTagNameNS( EZ_SOAP_ENV, 'Fault' );

            if ( count( $response ) == 1 )
            {
                $this->IsFault = 1;
                foreach( $dom->getElementsByTagName( "faultstring" ) as $faultNode )
                {
                    $this->FaultString = $faultNode->textContent;
                    break;
                }

                foreach( $dom->getElementsByTagName( "faultcode" ) as $faultNode )
                {
                    $this->FaultCode = $faultNode->textContent;
                    break;
                }
                return;
            }

            // get the response
            $response = $dom->getElementsByTagNameNS( $request->namespace(), $request->name() . "Response" );

            $response = $response[0];

            if ( !empty( $response ) )
            {
                /* Cut from the SOAP spec:
                The method response is viewed as a single struct containing an accessor
                for the return value and each [out] or [in/out] parameter.
                The first accessor is the return value followed by the parameters
                in the same order as in the method signature.

                Each parameter accessor has a name corresponding to the name
                of the parameter and type corresponding to the type of the parameter.
                The name of the return value accessor is not significant.
                Likewise, the name of the struct is not significant.
                However, a convention is to name it after the method name
                with the string "Response" appended.
                */

                $responseAccessors = $response->childNodes;
                if ( count($responseAccessors) > 0 )
                {
                    $returnObject = $responseAccessors[0];
                    $this->Value  = $this->decodeDataTypes( $returnObject );
                }
            }
            else
            {
                eZDebug::writeError( "Got error from server" );
            }
        }
        else
        {
            eZDebug::writeError( "Could not process XML in response" );
        }
    }

    /*!
      \static
      Decodes a DOM node and returns the PHP datatype instance of it.
    */
    function decodeDataTypes( $node, $type="" )
    {
        $returnValue = false;

        $attributeValue = '';
        $attribute = $node->getAttributeNodeNS( EZ_SOAP_SCHEMA_INSTANCE, 'type' );
        if ( !$attribute )
        {
            $attribute = $node->getAttributeNodeNS( 'http://www.w3.org/1999/XMLSchema-instance', 'type' );
        }
        $attributeValue = $attribute->value;

        $dataType = $type;
        $attrParts = explode( ":", $attributeValue );
        if ( $attrParts[1] )
        {
            $dataType = $attrParts[1];
        }

/*
        $typeNamespacePrefix = $this->DOMDocument->namespaceByAlias( $attrParts[0] );

        check that this is a namespace type definition
                if ( ( $typeNamespacePrefix == EZ_SOAP_SCHEMA_DATA ) ||
                     ( $typeNamespacePrefix == EZ_SOAP_ENC )
                     )
TODO: add encoding checks with schema validation.
*/

        switch ( $dataType )
        {
            case "string" :
            case "int" :
            case "float" :
            case 'double' :
            {
                $returnValue = $node->textContent;
            } break;

            case "boolean" :
            {
                if ( $node->textContent == "true" )
                    $returnValue = true;
                else
                    $returnValue = false;
            } break;

            case "base64" :
            {
                $returnValue = base64_decode( $node->textContent );
            } break;

            case "Array" :
            {
                // Get array type
                $attayType = $node->getAttributeNodeNS( EZ_SOAP_ENC, 'arrayType' )->value;
                $arrayTypeParts = explode( ":", $arrayType );

                preg_match( "#(.*)\[(.*)\]#",  $arrayTypeParts[1], $matches );

                $type = $matches[1];
                $count = $matches[2];

                $returnValue = array();
                foreach( $node->children() as $child )
                {
                    $returnValue[] = eZSOAPResponse::decodeDataTypes( $child, $type );
                }
            }break;

            case "SOAPStruct" :
            {
                $returnValue = array();

                foreach( $node->children() as $child )
                {
                    $returnValue[$child->name()] = eZSOAPResponse::decodeDataTypes( $child );
                }
            }break;

            default:
            {
                foreach ( $node->children() as $childNode )
                {
                    // check data type for child
                    $attr = $childNode->getAttributeNodeNS( EZ_SOAP_SCHEMA_INSTANCE, 'type' )->value;

                    $dataType = false;
                    $attrParts = explode( ":", $attr );
                    $dataType = $attrParts[1];


                    $returnValue[$childNode->name()] = eZSOAPResponse::decodeDataTypes( $childNode );
                }

            } break;
        }

        return $returnValue;
    }

    /*!
      Returns the XML payload for the response.
    */
    function payload( )
    {
        $doc = new DOMDocument();
        $doc->name = "eZSOAP message";

        $root = $doc->createElementNS( EZ_SOAP_ENV, EZ_SOAP_ENV_PREFIX . ':Envelope' );

        $root->setAttribute( 'xmlns:' . EZ_SOAP_XSI_PREFIX, EZ_SOAP_SCHEMA_INSTANCE );
        $root->setAttribute( 'xmlns:' . EZ_SOAP_XSD_PREFIX, EZ_SOAP_SCHEMA_DATA );
        $root->setAttribute( 'xmlns:' . EZ_SOAP_ENC_PREFIX, EZ_SOAP_ENC );

        // add the body
        $body = $doc->createElement(  EZ_SOAP_ENV_PREFIX . ':Body' );
        $root->appendChild( $body );

        // Check if it's a fault
        if ( $this->Value instanceof eZSOAPFault )
        {
            $fault = $doc->createElement( EZ_SOAP_ENV_PREFIX . ':Fault' );

            $faultCodeNode = $doc->createElement( "faultcode", $this->Value->faultCode() );
            $fault->appendChild( $faultCodeNode );

            $faultStringNode = $doc->createElement( "faultstring", $this->Value->faultString() );
            $fault->appendChild( $faultStringNode );

            $body->appendChild( $fault );
        }
        else
        {
            // add the request
            $responseName = $this->Name . "Response";
            $response = $doc->createElement( $responseName );
            $response->prefix = "resp";
            $response->setAttribute( 'xmlns:' . "resp", $this->Namespace );

            $return = $doc->createElement( "return" );
            $return->prefix = "resp";

            $value = eZSOAPCodec::encodeValue( "return", $this->Value );

            $body->appendChild( $response );

            $response->appendChild( $value );
        }

        $doc->appendChild( $root );

        return $doc->saveXML();
    }

    /*!
      \static
      \private
      Strips the header information from the HTTP raw response.
    */
    function stripHTTPHeader( $data )
    {
        $missingxml = false;
        $start = strpos( $data, "<?xml" );
        if ( $start == 0 )
        {
            eZDebug::writeWarning( "missing <?xml ...> in HTTP response, attempting workaround",
                                   "eZSoapResponse::stripHTTPHeader" );
            $start = strpos( $data, "<E:Envelope" );
            $missingxml = true;
        }
        $data = substr( $data, $start, strlen( $data ) - $start );

        if ( $missingxml == true )
        {
            $data = '<?xml version="1.0"?>' . $data;
        }

        return $data;
    }

    /*!
      Returns the response value.
    */
    function value()
    {
        return $this->Value;
    }

    /*!
     Sets the value of the response.
    */
    function setValue( $value )
    {
        $this->Value = $value;
    }

    /*!
     Returns true if the response was a fault
    */
    function isFault()
    {
        return $this->IsFault;
    }

    /*!
     Returns the fault code
    */
    function faultCode()
    {
        return $this->FaultCode;
    }

    /*!
     Returns the fault string
    */
    function faultString()
    {
        return $this->FaultString;
    }

    /// Contains the response value
    public $Value = false;
    /// Contains the response type
    public $Type = false;
    /// Contains fault string
    public $FaultString = false;
    /// Contains the fault code
    public $FaultCode = false;
    /// Contains true if the response was an fault
    public $IsFault = false;
    /// Contains the name of the response, i.e. function call name
    public $Name;
    /// Contains the target namespace for the response
    public $Namespace;

    /// Contains the DOM document for the current SOAP response
    public $DOMDocument = false;
}

?>
