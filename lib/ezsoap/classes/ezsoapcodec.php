<?php
//
// Definition of eZSOAPCodec class
//
// Created on: <03-Jan-2006 10:12:37 hovik>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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

/*! \file
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
    static function encodeValue( $doc, $name, $value )
    {
        switch ( gettype( $value ) )
        {
            case "string" :
            {
                $node = $doc->createElement( $name, $value );
                $node->setAttribute( eZSOAPEnvelope::XSI_PREFIX . ':type',
                                     eZSOAPEnvelope::XSD_PREFIX . ':string' );
                return $node;
            } break;

            case "boolean" :
            {
                $node = $doc->createElement( $name, $value ? 'true' : 'false' );
                $node->setAttribute( eZSOAPEnvelope::XSI_PREFIX . ':type',
                                     eZSOAPEnvelope::XSD_PREFIX . ':boolean' );
                return $node;
            } break;

            case "integer" :
            {
                $node = $doc->createElement( $name, $value );
                $node->setAttribute( eZSOAPEnvelope::XSI_PREFIX . ':type',
                                     eZSOAPEnvelope::XSD_PREFIX . ':int' );
                return $node;
            } break;

            case "double" :
            {
                $node = $doc->createElement( $name, $value );
                $node->setAttribute( eZSOAPEnvelope::XSI_PREFIX . ':type',
                                     eZSOAPEnvelope::XSD_PREFIX . ':float' );
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
                    $node = $doc->createElement( $name );
                    $node->setAttribute( eZSOAPEnvelope::XSI_PREFIX . ':type',
                                         eZSOAPEnvelope::ENC_PREFIX . ':SOAPStruct' );

                    foreach( $value as $key => $val )
                    {
                        $subNode = eZSOAPCodec::encodeValue( $doc, (string)$key, $val );
                        $node->appendChild( $subNode );
                    }
                    return $node;
                }
                else
                {
                    $node = $doc->createElement( $name );
                    $node->setAttribute( eZSOAPEnvelope::XSI_PREFIX . ':type',
                                         eZSOAPEnvelope::ENC_PREFIX . ':Array' );
                    $node->setAttribute( eZSOAPEnvelope::ENC_PREFIX . ':arrayType',
                                         eZSOAPEnvelope::XSD_PREFIX . ":string[$arrayCount]" );

                    foreach ( $value as $arrayItem )
                    {
                        $subNode = eZSOAPCodec::encodeValue( $doc, "item", $arrayItem );
                        $node->appendChild( $subNode );
                    }

                    return  $node;
                }
            } break;
        }

        return false;
    }
}

?>
