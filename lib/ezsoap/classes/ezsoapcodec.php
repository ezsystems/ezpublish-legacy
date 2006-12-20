<?php
//
// Definition of eZSOAPCodec class
//
// Created on: <03-Jan-2006 10:12:37 hovik>
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
