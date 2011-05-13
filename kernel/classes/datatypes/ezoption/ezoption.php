<?php
/**
 * File containing the eZOption class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZOption ezoption.php
  \ingroup eZDatatype
  \brief eZOption handles option set datatypes

  \code

  $option = new eZOption( "Colour" );
  $option->addValue( "Red" );
  $option->addValue( "Green" );

  // Serialize the class to an XML document
  $xmlString = $option->xmlString();

  \endcode
*/

class eZOption
{
    function eZOption( $name )
    {
        $this->Name = $name;
        $this->Options = array();
        $this->OptionCount = 0;
    }

    /*!
     Sets the name of the option
    */
    function setName( $name )
    {
        $this->Name = $name;
    }


    /*!
     Returns the name of the option set.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
     Adds an option
    */
    function addOption( $valueArray )
    {
        $value = isset( $valueArray['value'] ) ? $valueArray['value'] : '';
        $additional_price = isset( $valueArray['additional_price'] ) ? $valueArray['additional_price'] : '';
        $this->Options[] = array( "id" => $this->OptionCount,
                                  "value" => $value,
                                  'additional_price' => $additional_price,
                                  "is_default" => false );

        $this->OptionCount += 1;
    }

    function insertOption( $valueArray, $beforeID )
    {
        array_splice( $this->Options, $beforeID, 0 ,  array( array( "id" => $this->OptionCount,
                                                                    "value" => $valueArray['value'],
                                                                    'additional_price' => $valueArray['additional_price'],
                                                                    "is_default" => false ) ) );
        $this->OptionCount += 1;
    }

    function removeOptions( $array_remove )
    {
        $shiftvalue = 0;
        foreach( $array_remove as $id )
        {
            array_splice( $this->Options, $id - $shiftvalue, 1 );
            $shiftvalue++;
        }
        $this->OptionCount -= $shiftvalue;
    }

    function attributes()
    {
        return array( 'name',
                      'option_list' );
    }

    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    function attribute( $name )
    {
        switch ( $name )
        {
            case "name" :
            {
                return $this->Name;
            }break;
            case "option_list" :
            {
                return $this->Options;
            }break;
            default:
            {
                eZDebug::writeError( "Attribute '$name' does not exist", __METHOD__ );
                return null;
            }break;
        }
    }

    /*!
     Will decode an xml string and initialize the eZ option object
    */
    function decodeXML( $xmlString )
    {
        if ( $xmlString != "" )
        {
            $dom = new DOMDocument( '1.0', 'utf-8' );
            $success = $dom->loadXML( $xmlString );

            // set the name of the node
            $nameNode = $dom->getElementsByTagName( "name" )->item( 0 );
            $this->setName( $nameNode->textContent );

            $optionNodes = $dom->getElementsByTagName( "option" );
            $this->OptionCount = 0;

            foreach ( $optionNodes as $optionNode )
            {
                $this->addOption( array( 'value' => $optionNode->textContent,
                                         'additional_price' => $optionNode->getAttribute( 'additional_price' ) ) );
            }
        }
        else
        {
            $this->addOption( "" );
            $this->addOption( "" );
        }
    }

    /*!
     Will return the XML string for this option set.
    */
    function xmlString( )
    {
        $doc = new DOMDocument( '1.0', 'utf-8' );

        $root = $doc->createElement( "ezoption" );
        $doc->appendChild( $root );

        $name = $doc->createElement( "name", $this->Name );
        $root->appendChild( $name );

        $options = $doc->createElement( "options" );
        $root->appendChild( $options );

        $id=0;
        foreach ( $this->Options as $option )
        {
            unset( $optionNode );
            $optionNode = $doc->createElement( "option", $option["value"] );
            $optionNode->setAttribute( "id", $option['id'] );
            $optionNode->setAttribute( 'additional_price', $option['additional_price'] );
            $options->appendChild( $optionNode );
        }

        $xml = $doc->saveXML();

        return $xml;
    }

    /// Contains the Option name
    public $Name;

    /// Contains the Options
    public $Options;

    /// Contains the option counter value
    public $OptionCount;
}

?>
