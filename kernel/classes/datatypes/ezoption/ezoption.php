<?php
//
// Definition of eZOption class
//
// Created on: <28-Jun-2002 11:05:48 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
  \class eZOption ezoption.php
  \ingroup eZKernel
  \brief eZOption handles option set datatypes

  \code

  include_once( "kernel/classes/datatypes/ezoption/ezoption.php" );

  $option = new eZOption( "Colour" );
  $option->addValue( "Red" );
  $option->addValue( "Green" );

  // Serialize the class to an XML document
  $xmlString =& $option->xmlString();

  \endcode
*/

include_once( "lib/ezxml/classes/ezxml.php" );

class eZOption
{
    /*!
    */
    function eZOption( $name )
    {
        $this->Name = $name;
        $Options = array();
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
    function &name()
    {
        return $this->Name;
    }

    /*!
     Adds an option
    */
    function addOption( $valueArray )
    {
        $this->Options[] = array( "id" => $this->OptionCount,
                                  "value" => $valueArray['value'],
                                  'additional_price' => $valueArray['additional_price'],
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
        $options =& $this->Options;
        $shiftvalue = 0;
        foreach( $array_remove as $id )
        {
            array_splice( $options, $id - $shiftvalue, 1 );
            $shiftvalue++;
        }
        $this->OptionCount -= $shiftvalue;
    }
    function hasAttribute( $name )
    {
        if ( $name == "name" )
            return true;
        else
            if ( $name == "option_list" )
                return true;
            else
                return false;
    }

    function &attribute( $name )
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
        }
    }

    /*!
     Will decode an xml string and initialize the eZ option object
    */
    function decodeXML( $xmlString )
    {
        $xml = new eZXML();


        $dom =& $xml->domTree( $xmlString );

        if ( $xmlString != "" )
        {
            // set the name of the node
            $nameArray =& $dom->elementsByName( "name" );
            $this->setName( $nameArray[0]->textContent() );

            $optionArray =& $dom->elementsByName( "option" );
            $this->OptionCount = 0;
            foreach ( $optionArray as $option )
            {
//                 eZDebug::writeDebug( $option->attributeValue( 'additional_price' ), "attributeValue" );
                $this->addOption( array( 'value' => $option->textContent(),
                                         'additional_price' => $option->attributeValue( 'additional_price' ) ) );
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
    function &xmlString( )
    {
        $doc = new eZDOMDocument( "Option" );

        $root =& $doc->createElementNode( "ezoption" );
        $doc->setRoot( $root );

        $name =& $doc->createElementNode( "name" );
        $nameValue =& $doc->createTextNode( $this->Name );
        $name->appendChild( $nameValue );

        $name->setContent( $this->Name() );

        $root->appendChild( $name );

        $options =& $doc->createElementNode( "options" );

        $root->appendChild( $options );
        $id=0;
        foreach ( $this->Options as $option )
        {
            $optionNode =& $doc->createElementNode( "option" );
            $optionNode->appendAttribute( $doc->createAttributeNode( "id", $option['id'] ) );
            $optionNode->appendAttribute( $doc->createAttributeNode( 'additional_price', $option['additional_price'] ) );
            $optionValueNode =& $doc->createTextNode( $option["value"] );
            $optionNode->appendChild( $optionValueNode );

            $options->appendChild( $optionNode );
        }

        $xml =& $doc->toString();

        return $xml;
    }

    /// Contains the Option name
    var $Name;

    /// Contains the Options
    var $Options;

    /// Contains the option counter value
    var $OptionCount;
}

?>
