<?php
//
// $Id$
//
// Definition of eZSOAPResponse class
//
// Bård Farstad <bf@ez.no>
// Created on: <19-Feb-2002 16:51:10 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

/*!
  \class eZSOAPResponse ezsoapresponse.php
  \ingroup eZSOAP
  \brief eZSOAPResponse handles SOAP response packages

  \todo Add encoding checks with schema validation.
*/

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezxml/classes/ezxml.php" );
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
        $stream =& $this->stripHTTPHeader( $stream );

        $xml = new eZXML();

        $dom =& $xml->domTree( $stream );
        $this->DOMDocument =& $dom;

        eZDebug::writeNotice( $request->name() );

        if ( get_class( $dom ) == "ezdomdocument" )
        {
            // check for fault
            $response =& $dom->elementsByNameNS( "Fault", EZ_SOAP_ENV );

            if ( count( $response ) == 1 )
            {
                $this->IsFault = 1;
                $faultStringArray =& $dom->elementsByName( "faultstring" );
                $this->FaultString = $faultStringArray[0]->textContent();

                $faultCodeArray =& $dom->elementsByName( "faultcode" );
                $this->FaultCode = $faultCodeArray[0]->textContent();
                return;
            }

            // get the response
            $response =& $dom->elementsByNameNS( $request->name() . "Response", $request->namespace() );

            $response =& $response[0];

            if ( get_class( $response ) == "ezdomnode" )
            {
                $returnObject =& $dom->elementsByName( "return" );

                $this->Value =& $this->decodeDataTypes( $returnObject[0] );
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
      \private
	  Decodes a DOM node and returns the PHP datatype instance of it.
    */
    function &decodeDataTypes( &$node, $type="" )
    {
        $returnValue = false;

        $attr = $node->attributeValueNS( "type", EZ_SOAP_SCHEMA_INSTANCE );

        $dataType = $type;
        $attrParts = explode( ":", $attr );
        if ( $attrParts[1] )
        {
            $dataType = $attrParts[1];
        }

        $typeNamespacePrefix = $this->DOMDocument->namespaceByAlias( $attrParts[0] );

        // check that this is a namespace type definition
/*                if ( ( $typeNamespacePrefix == EZ_SOAP_SCHEMA_DATA ) ||
                     ( $typeNamespacePrefix == EZ_SOAP_ENC )
                     )
TODO: add encoding checks with schema validation.
*/
        if ( 1 )
        {
            switch ( $dataType )
            {
                case "string" :
                {
                    $returnValue =& $node->textContent();
                }break;

                case "int" :
                {
                    $returnValue =& $node->textContent();
                }break;

                case "float" :
                {
                    $returnValue =& $node->textContent();
                }break;

                case "boolean" :
                {
                    if ( $node->textContent() == "true" )
                        $returnValue = true;
                    else
                        $returnValue = false;
                }break;

                case "base64" :
                {
                    $returnValue =& base64_decode( $node->textContent() );
                }break;

                case "Array" :
                {
                    // Get array type
                    $arrayType = $node->attributeValueNS( "arrayType", EZ_SOAP_ENC );

                    $arrayTypeParts = explode( ":", $arrayType );

                    preg_match( "#(.*)\[(.*)\]#",  $arrayTypeParts[1], $matches );

                    $type = $matches[1];
                    $count = $matches[2];

                    $returnValue = array();
                    $children =& $node->children();
                    for ( $i=0; $i<$count; $i++ )
                    {
                        $returnValue[] =& $this->decodeDataTypes( $children[$i], $type );
                    }
                }break;

                case "SOAPStruct" :
                {
                    $returnValue = array();
                    $children =& $node->children();

                    reset( $children );
                    while ( list( $key, $child ) = each( $children ) )
                    {
                        $returnValue[$child->name()] =& $this->decodeDataTypes( $child );
                    }
                }break;

                default:
                {
                    foreach ( $node->children() as $childNode )
                    {
                        // check data type for child
                        $attr = $childNode->attributeValueNS( "type", EZ_SOAP_SCHEMA_INSTANCE );

                        $dataType = false;
                        $attrParts = explode( ":", $attr );
                        $dataType = $attrParts[1];


                        $returnValue[$childNode->name()] =& $this->decodeDataTypes( $childNode );
                    }

                } break;

/*                        default:
                        {
                            eZDebug::writeError( "Unkown datatype in result: $dataType", "eZSOAPResponse::decodeStream()" );
                        } break;*/
            }
        }
        else
        {
            eZDebug::writeError( "Unknown data encoding, could not decode stream", "eZSOAPResponse::decodeStream()" );
        }

        return $returnValue;
    }

    /*!
      Returns the XML payload for the response.
    */
    function &payload( )
    {
        $doc = new eZDOMDocument();
        $doc->setName( "eZSOAP message" );

        $root =& $doc->createElementNodeNS( EZ_SOAP_ENV, "Envelope" );

        $root->appendAttribute( $doc->createAttributeNamespaceDefNode( EZ_SOAP_XSI_PREFIX, EZ_SOAP_SCHEMA_INSTANCE ) );
        $root->appendAttribute( $doc->createAttributeNamespaceDefNode( EZ_SOAP_XSD_PREFIX, EZ_SOAP_SCHEMA_DATA ) );
        $root->appendAttribute( $doc->createAttributeNamespaceDefNode( EZ_SOAP_ENC_PREFIX, EZ_SOAP_ENC ) );
        $root->setPrefix( EZ_SOAP_ENV_PREFIX );

        // add the body
        $body =& $doc->createElementNode( "Body" );

        $body->setPrefix( EZ_SOAP_ENV_PREFIX );
        $root->appendChild( $body );

        // add the request
        $responseName = $this->Name . "Response";
        $response =& $doc->createElementNode( $responseName );
        $response->setPrefix( "resp" );
        $response->appendAttribute( $doc->createAttributeNamespaceDefNode( "resp", $this->Namespace ) );

        $return =& $doc->createElementNode( "return" );
        $return->setPrefix( "resp" );

        $value = $this->encodeValue( "return", $this->Value );

        $body->appendChild( $response );
        $response->appendChild( $value );

        $doc->setRoot( $root );
        $ret =& $doc->toString();

        return $ret;
    }

    /*!
      \static
      \private
      Strips the header information from the HTTP raw response.
    */
    function &stripHTTPHeader( $data )
    {
        $start = strpos( $data, "<?xml" );
        $data =& substr( $data, $start, strlen( $data ) - $start );

        return $data;
    }

    /*!
      Encodes a PHP variable into a SOAP datatype.
    */
    function &encodeValue( $name, $value )
    {
        $returnValue = false;
        switch ( gettype( $value ) )
        {
            case "string" :
            {
                $node =& eZDOMDocument::createElementNode( $name );
                $attr =& eZDOMDocument::createAttributeNode( "type", EZ_SOAP_XSD_PREFIX . ":string" );
                $attr->setPrefix( EZ_SOAP_XSI_PREFIX );
                $node->appendAttribute( $attr );
                $node->appendChild( eZDOMDocument::createTextNode( $value ) );

                $returnValue =& $node;
            } break;

            case "integer" :
            {
                $node =& eZDOMDocument::createElementNode( $name );
                $attr =& eZDOMDocument::createAttributeNode( "type", EZ_SOAP_XSD_PREFIX . ":int" );
                $attr->setPrefix( EZ_SOAP_XSI_PREFIX );
                $node->appendAttribute( $attr );
                $node->appendChild( eZDOMDocument::createTextNode( $value ) );

                $returnValue =& $node;
            } break;

            case "double" :
            {
                $node =& eZDOMDocument::createElementNode( $name );
                $attr =& eZDOMDocument::createAttributeNode( "type", EZ_SOAP_XSD_PREFIX . ":float" );
                $attr->setPrefix( EZ_SOAP_XSI_PREFIX );
                $node->appendAttribute( $attr );
                $node->appendChild( eZDOMDocument::createTextNode( $value ) );

                $returnValue =& $node;
            } break;
        }

        return $returnValue;
    }

    /*!
      Returns the response value.
    */
    function &value()
    {
        return $this->Value;
    }

    /*!
     Sets the value of the response.
    */
    function setValue( $value )
    {
        $this->Value =& $value;
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
    var $Value = false;
    /// Contains the response type
    var $Type = false;
    /// Contains fault string
    var $FaultString = false;
    /// Contains the fault code
    var $FaultCode = false;
    /// Contains true if the response was an fault
    var $IsFault = false;
    /// Contains the name of the response, i.e. function call name
    var $Name;
    /// Contains the target namespace for the response
    var $Namespace;

    /// Contains the DOM document for the current SOAP response
    var $DOMDocument = false;
}

?>
