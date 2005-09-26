<?php
//
// Definition of eZSOAPRequest class
//
// Created on: <19-Feb-2002 15:42:03 bf>
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
  \class eZSOAPRequest ezsoaprequest.php
  \ingroup eZSOAP
  \brief eZSOAPRequest handles SOAP request messages

*/

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezxml/classes/ezxml.php" );
include_once( "lib/ezsoap/classes/ezsoapparameter.php" );
include_once( "lib/ezsoap/classes/ezsoapenvelope.php" );

class eZSOAPRequest extends eZSOAPEnvelope
{
    /*!
     Constructs a new eZSOAPRequest object. You have to provide the request name
     and the target namespace for the request.

     \param name
     \param namespace
     \param parameters, assosiative array, example: array( 'param1' => 'value1, 'param2' => 'value2' )
    */
    function eZSOAPRequest( $name="", $namespace="", $parameters = array() )
    {
        $this->Name = $name;
        $this->Namespace = $namespace;

        // call the parents constructor
        $this->eZSOAPEnvelope();

        foreach( $parameters as $name => $value )
        {
            $this->addParameter( $name, $value );
        }
    }

    /*!
      Returns the request name.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Returns the request target namespace.
    */
    function namespace()
    {
        return $this->Namespace;
    }


    /*!
      Adds a new parameter to the request. You have to provide a prameter name
      and value.
    */
    function addParameter( $name, $value )
    {
        $this->Parameters[] = new eZSOAPParameter( $name, $value );
    }

    /*!
      Returns the request payload
    */
    function &payload()
    {
        $doc = new eZDOMDocument();
        $doc->setName( "eZSOAP message" );

        $root = $doc->createElementNodeNS( EZ_SOAP_ENV, "Envelope" );

        $root->appendAttribute( $doc->createAttributeNamespaceDefNode( EZ_SOAP_XSI_PREFIX, EZ_SOAP_SCHEMA_INSTANCE ) );
        $root->appendAttribute( $doc->createAttributeNamespaceDefNode( EZ_SOAP_XSD_PREFIX, EZ_SOAP_SCHEMA_DATA ) );
        $root->appendAttribute( $doc->createAttributeNamespaceDefNode( EZ_SOAP_ENC_PREFIX, EZ_SOAP_ENC ) );

        $root->setPrefix( EZ_SOAP_ENV_PREFIX );

        // add the body
        $body = $doc->createElementNode( "Body" );
        $body->appendAttribute( $doc->createAttributeNamespaceDefNode( "req", $this->Namespace ) );
        $body->setPrefix( EZ_SOAP_ENV_PREFIX );
        $root->appendChild( $body );

        // add the request
        $request = $doc->createElementNode( $this->Name );
        $request->setPrefix( "req" );

        // add the request parameters
        foreach ( $this->Parameters as $parameter )
        {
            unset( $param );
            $param =& $this->encodeValue( $parameter->name(), $parameter->value() );
//            $param->setPrefix( "req" );

            if ( $param == false )
                eZDebug::writeError( "Error enconding data for payload", "eZSOAPRequest::payload()" );
            $request->appendChild( $param );
        }

        $body->appendChild( $request );

        $doc->setRoot( $root );
        $ret = $doc->toString();

        return $ret;
    }

    /*!
      \private
      Encodes the PHP variables into SOAP types.
      TODO: encodeValue(...) in ezsoapresponse.php and ezsoaprequest.php should be moved to a common place,
      e.g. ezsoapcodec.php
    */
    function &encodeValue( $name, $value )
    {
        $returnValue = false;
        switch ( gettype( $value ) )
        {
            case "string" :
            {
                $node = eZDOMDocument::createElementNode( $name );
                $attr = eZDOMDocument::createAttributeNode( "type", EZ_SOAP_XSD_PREFIX . ":string" );
                $attr->setPrefix( EZ_SOAP_XSI_PREFIX );
                $node->appendAttribute( $attr );
                $node->appendChild( eZDOMDocument::createTextNode( $value ) );

                $returnValue =& $node;
            } break;

            case "boolean" :
            {
                $node = eZDOMDocument::createElementNode( $name );
                $attr = eZDOMDocument::createAttributeNode( "type", EZ_SOAP_XSD_PREFIX . ":boolean" );
                $attr->setPrefix( EZ_SOAP_XSI_PREFIX );
                $node->appendAttribute( $attr );
                if ( $value === true )
                    $node->appendChild( eZDOMDocument::createTextNode( "true" ) );
                else
                    $node->appendChild( eZDOMDocument::createTextNode( "false" ) );
                $returnValue =& $node;
            } break;

            case "integer" :
            {
                $node = eZDOMDocument::createElementNode( $name );
                $attr = eZDOMDocument::createAttributeNode( "type", EZ_SOAP_XSD_PREFIX . ":int" );
                $attr->setPrefix( EZ_SOAP_XSI_PREFIX );
                $node->appendAttribute( $attr );
                $node->appendChild( eZDOMDocument::createTextNode( $value ) );

                $returnValue =& $node;
            } break;

            case "double" :
            {
                $node = eZDOMDocument::createElementNode( $name );
                $attr = eZDOMDocument::createAttributeNode( "type", EZ_SOAP_XSD_PREFIX . ":float" );
                $attr->setPrefix( EZ_SOAP_XSI_PREFIX );
                $node->appendAttribute( $attr );
                $node->appendChild( eZDOMDocument::createTextNode( $value ) );

                $returnValue =& $node;
            } break;

            case "array" :
            {
                $arrayCount = count( $value );

                $isStruct = false;
                // Check for struct
                $i=0;
                reset( $value );
                while ( list( $key, $val ) = each ( $value ) )
                {
                    if ( $i != $key )
                        $isStruct = true;
                    $i++;
                }

                if ( $isStruct == true )
                {
                    $node = eZDOMDocument::createElementNode( $name );
                    // Type def
                    $typeAttr = eZDOMDocument::createAttributeNode( "type", EZ_SOAP_ENC_PREFIX . ":SOAPStruct" );
                    $typeAttr->setPrefix( EZ_SOAP_XSI_PREFIX );
                    $node->appendAttribute( $typeAttr );

                    reset( $value );
                    while ( list( $key, $val ) = each ( $value ) )
                    {
                        $subNode =& $this->encodeValue( $key, $val );
                        $node->appendChild( $subNode );
                    }
                    $returnValue =& $node;
                }
                else
                {
                    $node = eZDOMDocument::createElementNode( $name );
                    // Type def
                    $typeAttr = eZDOMDocument::createAttributeNode( "type", EZ_SOAP_ENC_PREFIX . ":Array" );
                    $typeAttr->setPrefix( EZ_SOAP_XSI_PREFIX );
                    $node->appendAttribute( $typeAttr );

                    // Array type def
                    $arrayTypeAttr = eZDOMDocument::createAttributeNode( "arrayType", EZ_SOAP_XSD_PREFIX . ":string[$arrayCount]" );
                    $arrayTypeAttr->setPrefix( EZ_SOAP_ENC_PREFIX );
                    $node->appendAttribute( $arrayTypeAttr );

                    foreach ( $value as $arrayItem )
                    {
                        $subNode =& $this->encodeValue( "item", $arrayItem );
                        $node->appendChild( $subNode );
                    }
                    $returnValue =& $node;
                }
            } break;
        }

        return $returnValue;
    }


    /// The request name
    var $Name;

    /// The request target namespace
    var $Namespace;

    /// Contains the request parameters
    var $Parameters = array();
}

?>
