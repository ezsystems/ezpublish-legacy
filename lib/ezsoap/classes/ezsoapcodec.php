<?php
//
// Definition of eZSOAPCodec class
//
// Created on: <03-Jan-2006 10:12:37 hovik>
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

/*! \file ezsoapcodec.php
*/

/*!
  \class eZSOAPCodec ezsoapcodec.php
  \brief The class eZSOAPCodec does

*/

class eZSOAPCodec
{
    /*!
     Constructor
    */
    function eZSOAPCodec()
    {
    }

    /*!
      \static
      Encodes a PHP variable into a SOAP datatype.
    */
    function encodeValue( $name, $value )
    {
        switch ( gettype( $value ) )
        {
            case "string" :
            {
                $node = eZDOMDocument::createElementNode( $name );
                $attr = eZDOMDocument::createAttributeNode( "type", EZ_SOAP_XSD_PREFIX . ":string" );
                $attr->setPrefix( EZ_SOAP_XSI_PREFIX );
                $node->appendAttribute( $attr );
                $node->appendChild( eZDOMDocument::createTextNode( $value ) );

                return $node;
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

                return $node;
            } break;

            case "integer" :
            {
                $node = eZDOMDocument::createElementNode( $name );
                $attr = eZDOMDocument::createAttributeNode( "type", EZ_SOAP_XSD_PREFIX . ":int" );
                $attr->setPrefix( EZ_SOAP_XSI_PREFIX );
                $node->appendAttribute( $attr );
                $node->appendChild( eZDOMDocument::createTextNode( $value ) );

                return $node;
            } break;

            case "double" :
            {
                $node = eZDOMDocument::createElementNode( $name );
                $attr = eZDOMDocument::createAttributeNode( "type", EZ_SOAP_XSD_PREFIX . ":float" );
                $attr->setPrefix( EZ_SOAP_XSI_PREFIX );
                $node->appendAttribute( $attr );
                $node->appendChild( eZDOMDocument::createTextNode( $value ) );

                return $node;
            } break;

            case "array" :
            {
                $arrayCount = count( $value );

                $isStruct = false;
                // Check for struct
                $i = 0;
                foreach( $value as $key => $val )
                {
                    if ( $i !== $key )
                    {
                        $isStruct = true;
                        break;
                    }
                    $i++;
                }

                if ( $isStruct == true )
                {
                    $node = eZDOMDocument::createElementNode( $name );
                    // Type def
                    $typeAttr = eZDOMDocument::createAttributeNode( "type", EZ_SOAP_ENC_PREFIX . ":SOAPStruct" );
                    $typeAttr->setPrefix( EZ_SOAP_XSI_PREFIX );
                    $node->appendAttribute( $typeAttr );

                    foreach( $value as $key => $val )
                    {
                        $subNode = eZSOAPCodec::encodeValue( (string)$key, $val );
                        $node->appendChild( $subNode );
                        unset( $subNode );
                    }
                    return $node;
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
                        $subNode = eZSOAPCodec::encodeValue( "item", $arrayItem );
                        $node->appendChild( $subNode );
                        unset( $subNode );
                    }

                    return  $node;
                }
            } break;
        }

        return false;
    }
}

?>
