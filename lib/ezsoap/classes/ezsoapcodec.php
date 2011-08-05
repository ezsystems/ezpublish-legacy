<?php
/**
 * File containing the eZSOAPCodec class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
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
                $node = $doc->createElement( $name );
                $node->appendChild( $doc->createTextNode( $value ) );
                $node->setAttribute( eZSOAPEnvelope::XSI_PREFIX . ':type',
                                     eZSOAPEnvelope::XSD_PREFIX . ':string' );
                return $node;
            } break;

            case "boolean" :
            {
                $node = $doc->createElement( $name );
                $node->appendChild( $doc->createTextNode( $value ? 'true' : 'false' ) );
                $node->setAttribute( eZSOAPEnvelope::XSI_PREFIX . ':type',
                                     eZSOAPEnvelope::XSD_PREFIX . ':boolean' );
                return $node;
            } break;

            case "integer" :
            {
                $node = $doc->createElement( $name );
                $node->appendChild( $doc->createTextNode( $value ) );
                $node->setAttribute( eZSOAPEnvelope::XSI_PREFIX . ':type',
                                     eZSOAPEnvelope::XSD_PREFIX . ':int' );
                return $node;
            } break;

            case "double" :
            {
                $node = $doc->createElement( $name );
                $node->appendChild( $doc->createTextNode( $value ) );
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
