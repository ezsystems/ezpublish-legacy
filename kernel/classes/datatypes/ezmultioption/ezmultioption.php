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
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZMultiOption ezmultioption.php
  \ingroup eZKernel
  \brief eZOption handles option set datatypes

  \code

    include_once( "kernel/classes/datatypes/ezoption/ezmultioption.php" );
    $option = new eZOption( "Car" );
    $newID = $option->addMultiOption("name",$priority,false);
    $option->addOption( $newID, "Red", "12.50",false );
    $option->addValue( $newID, "Green", "18.50",false );

    $newID = $option->addMultiOption( "Size",$priority, false );
    $option->addOption( $newID, "Size - A", "" );
    $option->addValue( $newID, "Size - B", "" );

  // Serialize the class to an XML document
  $xmlString =& $option->xmlString();
  \endcode
*/

include_once( "lib/ezxml/classes/ezxml.php" );

class eZMultiOption
{
    /*!
     Constructor to initiealise the variable
    */
    function eZMultiOption( $name )
    {
        $this->Name = $name;
        $this->Options = array();
        $this->MultiOptionCount = 0;
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
     Adds an Multioption
    */
    function addMultiOption( $name, $multiOptionPriority, $defaultValue )
    {
        $this->MultiOptionCount += 1;
        $this->Options[$this->MultiOptionCount] =array( "id" => $this->MultiOptionCount,
                                                        "name" => $name,
                                                        'priority'=> $multiOptionPriority,
                                                        "default_value" => $defaultValue,
                                                        'optionlist' => array() );
        //     $this->MultiOptionCount += 1;
        return $this->MultiOptionCount;
    }

    /*!
    Adds an Option
    */
    function addOption( $newID, $optionValue, $optionAdditionalPrice )
    {
        $key=count( $this->Options[$newID]['optionlist'] ) + 1;
        $this->Options[$newID]['optionlist'][] = array( "id" => $key,
                                                        "value" => $optionValue,
                                                        'additional_price' => $optionAdditionalPrice);
    }

    /*!
    Change the id of multioption in ascending order.
    */
    function changeMultiOptionId()
    {
        $i = 1 ;
        foreach($this->Options as $key => $opt)
        {
            print_r("æ");
            $this->Options[$key][id] = $i++;
        }
        $this->MultiOptionCount = $i-1;
    }

    /*!
    Remove MultiOption
    */
    function removeMultiOptions( $array_remove )
    {
        $options =& $this->Options;
        foreach( $array_remove as $id )
        {
            unset( $options[$id-1] );
        }
        $options = array_values( $options );
        $this->changeMultiOptionId();
    }

    /*!
    RemoveOption
    */
    function removeOptions( $arrayRemove,$optionId )
    {
        $options =& $this->Options;
        foreach( $arrayRemove as  $id )
        {
            unset($options[$optionId]['optionlist'][$id-1] );
        }
        $options = array_values( $options );
        $i = 1;
        foreach( $options[$optionId]['optionlist'] as $key => $opt)
        {
            $options[$optionId]['optionlist'][$key]['id'] = $i;
            $i++;
        }
    }

    /*!
    Returns true if object have an attribute.
    */
    function hasAttribute( $name )
    {
        if ( $name == "name" )
            return true;
        else if ( $name == "multioption_list" )
            return true;
        else
            return false;
    }

    /*!
    Returns an attribute
    */
    function &attribute( $name )
    {
        switch ( $name )
        {
            case "name" :
            {
                return $this->Name;
            }break;

            case "multioption_list" :
            {
                return $this->Options;
            }break;
        }
    }

    /*!
    Will decode an xml string and initialize the eZ Multi option object
    */
    function decodeXML( $xmlString )
    {
        $this->OptionCount = 1;
        $this->Options = array();
        if ( $xmlString != "" )
        {
            $xml = new eZXML();
            $dom =& $xml->domTree( $xmlString );
            $root =& $dom->root();
            // set the name of the node
            $name =& $root->elementTextContentByName( "name" );
            $multioptionsNode =& $root->elementByName( "multioptions" );
            $multioptionsList =& $multioptionsNode->elementsByName( "multioption" );
            //Loop for MultiOptions
            foreach ( $multioptionsList as $multioption )
            {
                //   $multioptionName =& $multioption->attributeValue( "name" );
                $newID = $this->addMultiOption( $multioption->attributeValue( "name" ),
                                                $multioption->attributeValue( "priority" ),
                                                $multioption->attributeValue( "default_value" ) );
                $optionNode =& $multioption->elementsByName( "option" );
                //Loop for Options
                foreach( $optionNode as $option )
                {
                    $this->addOption( $newID, $option->attributeValue("value"), $option->attributeValue( "additional_price" ) );
                }
            }
        }
        else
        {
            //The control come here while creaging new object for MultiOption
            $nodeID = $this->addMultiOption( "", 0, false );
            $this->addOption( $nodeID, "", "" );
            $this->addOption( $nodeID, "", "" );
            $nodeID = $this->addMultiOption( "", 0, false );
            $this->addOption( $nodeID, "", "" );
            $this->addOption( $nodeID, "", "" );
        }
    }

    /*!
     Will return the XML string for this MultiOption set.
    */
    function &xmlString()
    {
        $doc = new eZDOMDocument( "MultiOption" );
        $root =& $doc->createElementNode( "ezmultioption" );
        $doc->setRoot( $root );
        $name =& $doc->createElementNode( "name" );
        $nameValue =& $doc->createTextNode( $this->Name );
        $name->appendChild( $nameValue );
        $root->appendChild( $name );
        $multioptions =& $doc->createElementNode( "multioptions" );
        $root->appendChild( $multioptions );
        foreach ( $this->Options as $multioption )
        {
            $multioptionNode =& $doc->createElementNode( "multioption" );
            $multioptionNode->appendAttribute( $doc->createAttributeNode( "id", $multioption['id'] ) );
            $multioptionNode->appendAttribute( $doc->createAttributeNode( "name", $multioption['name'] ) );
            $multioptionNode->appendAttribute( $doc->createAttributeNode( "priority", $multioption['priority'] ) );
            $multioptionNode->appendAttribute( $doc->createAttributeNode( 'default_value', $multioption['default_value'] ) );
            foreach( $multioption['optionlist'] as $option )
            {
                $optionNode =& $doc->createElementNode( "option" );
                $optionNode->appendAttribute( $doc->createAttributeNode( "id", $option['id'] ) );
                $optionNode->appendAttribute( $doc->createAttributeNode( "value", $option['value'] ) );
                $optionNode->appendAttribute( $doc->createAttributeNode( 'additional_price', $option['additional_price'] ) );
                $multioptionNode->appendChild( $optionNode );
            }
            $multioptions->appendChild( $multioptionNode );
        }
        $xml =& $doc->toString();
        return $xml;
    }

    //  Private declaration, it must not change.
    // Contains the Option name
    var $Name;
    /// Contains the Options
    var $Options;
    // Contains the option counter value
    var $MultiOptionCount;
}
?>
