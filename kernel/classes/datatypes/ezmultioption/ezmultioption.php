<?php
//
// Definition of eZOption class
//
// Created on: <29-Jul-2004 15:52:24 gv>
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
  \brief Encapsulates multiple options in one datatype.

  This class encapsulates multiple options by storing an array
  with multioption elements each containing a list of options.
  Managing multioptions is done using addMultiOption(), removeMultiOption() and changeMultiOption().
  For a multioption you can add and remove options with addOption() and removeOptions().
  For storage use the xmlString() and decodeXML() methods.

  A \a multioption is a list of the group of options.
  All multioption's name are displayed simultaneously.

  Multioption consists of id, name, priority and default_option_id.
  - id                : is a unique representation of all multioptions.
  - name              : name of multioption.
  - priority          : Priority value is is used to sort the multioption after sorting
                        it will change the original priority value to 1,2,3...
  - default_option_id : is to displaying the default option from the list.
  - optionlist        : list of all option elements.

  An \a option is a list of values in which only one value can be selected
  at a time from drop down combobox.
  In the following e.g. Model-A,Model-B, Model-C are options for Model multioption
  and Red and Blue are options for Color multioption.

  Option consists of id, option_id, value and additional_price.
  - id               : unique representation of options in a multioption.
  - option_id        : option_id is unique among all options, it is used to
                       retrieve values for shop or similar operations.
  - value            : value is a string and it is a value used as display for that option.
  - additional_price : This is an additional price that will add to the original price
                       and will display in currency format for that value.
                       If the price value is not given then it will will not display
                       anything in that place.

  In the following example Model and Color is known as an multioption.
  Examples:
  1. If a motor car company is having different models and colors then it will be.

  \code
        CAR
         Model
             ----------------------
             |                 |\/|
             ----------------------
             | Model - A £ 100.00 |
             | Model - B £ 200.00 |
             | Model - C £ 300.00 |
             ----------------------

             Color
             -------------
             |        |\/|
             -------------
             |  Red      |
             |  Blue     |
             -------------
  \endcode

  The xmlstring for the above eg will look like as follow
  \code
  Array( [0] => Array(
           [id] => 1
           [name] => CAR
           [priority] => 1
           [default_option_id] => 2
           [optionlist] => Array( [0] => Array( [id] => 1 [option_id] => 1 [value] => Model-A [additional_price] => 100 )
                                  [1] => Array( [id] => 2 [option_id] => 2 [value] => Model-B [additional_price] => 200 )
                                  [2] => Array( [id] => 3 [option_id] => 5 [value] => Model-C [additional_price] => 300 )
                                )
                     )
         [1] => Array(
           [id] => 2
           [name] => Color
           [priority] => 2
           [default_option_id] => 1
           [optionlist] => Array( [0] => Array( [id] => 1 [option_id] => 3 [value] => Red [additional_price] => )
                                  [1] => Array( [id] => 2 [option_id] => 4 [value] => Blue[additional_price] => )
                                )
                     )
       )
  \endcode

  Example of how to crete an option, adding multioptions and options
  and finally retrieving the xml structure.
  \code
   include_once( "kernel/classes/datatypes/ezoption/ezmultioption.php" );
   $option = new eZOption( "Car" );
   $newID = $option->addMultiOption("Model",$priority,false);
      $option->addOption( $newID, "", "Model - A", "100", false );
      $option->addOption( $newID, "", "Model - B", "100", false );
      $option->addOption( $newID, "", "Model - C", "100", false );

   $newID = $option->addMultiOption( "Color", $priority, false );
      $option->addOption( $newID, "", "Red", "", false );
      $option->addOption( $newID, "", "Blue", "", false );
  // Serialize the class to an XML document
  $xmlString =& $option->xmlString();
  \endcode
*/

include_once( "lib/ezxml/classes/ezxml.php" );

class eZMultiOption
{
    /*!
     Initializes with empty multioption list.
    */
    function eZMultiOption( $name )
    {
        $this->Name = $name;
        $this->Options = array();
        $this->MultiOptionCount = 0;
        $this->OptionCounter = 0;
    }

    /*!
      Adds an Multioption named \a $name
      \param $name contains the name of multioption.
      \param $multiOptionPriority is stored for displaying the array in order.
      \param $defaultValue is stored to display the options by default.
      \return The ID of the multioption that was added.
    */
    function addMultiOption( $name, $multiOptionPriority, $defaultValue )
    {
        $this->MultiOptionCount += 1;
        $this->Options[$this->MultiOptionCount] = array( "id" => $this->MultiOptionCount,
                                                         "name" => $name,
                                                         'priority'=> $multiOptionPriority,
                                                         "default_option_id" => $defaultValue,
                                                         'optionlist' => array() );
        return $this->MultiOptionCount;
    }

    /*!
      Adds an Option to multioption \a $name
      \param $newID is the element key value to which the new option will be added.
      \param $optionValue is the original value to display for users.
      \param $optionAdditionalPrice is a price value that is used to store price of the option values.
    */
    function addOption( $newID, $OptionID, $optionValue, $optionAdditionalPrice )
    {
        $key = count( $this->Options[$newID]['optionlist'] ) + 1;
        if ( strlen( $OptionID ) == 0 )
        {
            $this->OptionCounter += 1;
            $OptionID = $this->OptionCounter;
        }
        $this->Options[$newID]['optionlist'][] = array( "id" => $key,
                                                        "option_id" => $OptionID,
                                                        "value" => $optionValue,
                                                        'additional_price' => $optionAdditionalPrice );
    }

    /*!
     Sorts the current multioption on the basis of it's priority value.
     After softing array it calles the function changeMultiOptionID,
     which again rearragnes the current priority value to 1,2,3......etc.
    */
    function sortMultiOptions()
    {
        usort( $this->Options, create_function( '$a, $b', 'if ( $a["priority"] == $b["priority"] ) { return 0; } return ( $a["priority"] < $b["priority"] ) ? -1 : 1;' ) );
        $this->changeMultiOptionID();
    }

    /*!
      Finds the largest \c option_id among the options and sets it as \a $this->OptionCounter
    */
    function resetOptionCounter()
    {
        $maxValue = 0;
        foreach ( $this->Options as $optionList )
        {
            foreach ( $optionList['optionlist'] as $option )
            {
                if ( $maxValue < $option['option_id'] )
                {
                    $maxValue = $option['option_id'];
                }
            }
        }
        $this->OptionCounter = $maxValue;
    }

    /*!
      Change the id of multioption in ascending order.
    */
    function changeMultiOptionId()
    {
        $i = 1 ;
        foreach ( $this->Options as $key => $opt )
        {
            $this->Options[$key]['id'] = $i++;
        }
        $this->MultiOptionCount = $i - 1;
    }

    /*!
      Remove MultiOption from the array.
      After calling this function all the options associated with that multioption will be removed.
      This function also calles to changeMultiOption to reset the key value of multioption array.
      \param $array_remove is the array of those multiOptions which is selected to remove.
      \sa removeOptions()
    */
    function removeMultiOptions( $array_remove )
    {
        $options =& $this->Options;
        foreach ( $array_remove as $id )
        {
            unset( $options[ $id - 1 ] );
        }
        $options = array_values( $options );
        $this->changeMultiOptionId();
    }

    /*!
      Remove Options from the multioption.
      This function first remove selected options and then reset the key value if all options for that multioption.
      \param $arrayRemove is a list of all array elements which is selected to remove from the multioptions.
      \param $optionid is the key value if multioption from which it is required to remove the options.
      \sa removeMultiOptions()
    */
    function removeOptions( $arrayRemove, $optionId )
    {
        $options =& $this->Options;
        foreach ( $arrayRemove as  $id )
        {
            unset( $options[$optionId]['optionlist'][$id - 1] );
        }
        $options = array_values( $options );
        $i = 1;
        foreach ( $options[$optionId]['optionlist'] as $key => $opt )
        {
            $options[$optionId]['optionlist'][$key]['id'] = $i;
            $i++;
        }
    }

    /*!
      Returns true if object have an attribute.
      The valid attributes are \c name and \c multioption_list.
      \param $name contains the name of attribute
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
    Returns an attribute. The valid attributes are \c name and \c multioption_list
    \a name contains the name of multioption
    \a multioption_list contains the list of all multioptions.
    */
    function &attribute( $name )
    {
        switch ( $name )
        {
            case "name" :
            {
                return $this->Name;
            } break;

            case "multioption_list" :
            {
                return $this->Options;
            } break;
        }
    }

    /*!
    Will decode an xml string and initialize the eZ Multi option object.
    If $xmlString is on empty then it will call addMultiOption() and addOption() functions
    to create new multioption else it will decode the xml string.
    \param $smlString contain the complete data structure for multioptions.
    \sa xmlString()
    */
    function decodeXML( $xmlString )
    {
        $this->OptionCounter = 0;
        $this->Options = array();
        if ( $xmlString != "" )
        {
            $xml = new eZXML();
            $dom =& $xml->domTree( $xmlString );
            $root =& $dom->root();
            // set the name of the node
            $this->name =& $root->elementTextContentByName( "name" );
            $this->OptionCounter =& $root->attributeValue("option_counter");
            $multioptionsNode =& $root->elementByName( "multioptions" );
            $multioptionsList =& $multioptionsNode->elementsByName( "multioption" );
            //Loop for MultiOptions
            foreach ( $multioptionsList as $multioption )
            {
                $newID = $this->addMultiOption( $multioption->attributeValue( "name" ),
                                                $multioption->attributeValue( "priority" ),
                                                $multioption->attributeValue( "default_option_id" ) );
                $optionNode =& $multioption->elementsByName( "option" );
                foreach ( $optionNode as $option )
                {
                    $this->addOption( $newID, $option->attributeValue( "option_id" ), $option->attributeValue( "value" ), $option->attributeValue( "additional_price" ) );
                }
            }
        }
        else
        {
            //The control come here while creaging new object for MultiOption
            $nodeID = $this->addMultiOption( "", 0, false );
            $this->addOption( $nodeID, "", "", "" );
            $this->addOption( $nodeID, "", "", "" );
            $nodeID = $this->addMultiOption( "", 0, false );
            $this->addOption( $nodeID, "", "", "" );
            $this->addOption( $nodeID, "", "", "" );
        }
    }

    /*!
     Will return the XML string for this MultiOption set.
     \sa decodeXML()
    */
    function &xmlString()
    {
        $doc = new eZDOMDocument( "MultiOption" );
        $root =& $doc->createElementNode( "ezmultioption" );
        $doc->setRoot( $root );
        $name =& $doc->createElementNode( "name" );
        $nameValue =& $doc->createTextNode( $this->Name );
        $name->appendChild( $nameValue );
        $optionCounter = $doc->createAttributeNode( 'option_counter', $this->OptionCounter );
        $root->appendAttribute( $optionCounter );

        $root->appendChild( $name );
        $multioptions =& $doc->createElementNode( "multioptions" );
        $root->appendChild( $multioptions );
        foreach ( $this->Options as $multioption )
        {
            $multioptionNode =& $doc->createElementNode( "multioption" );
            $multioptionNode->appendAttribute( $doc->createAttributeNode( "id", $multioption['id'] ) );
            $multioptionNode->appendAttribute( $doc->createAttributeNode( "name", $multioption['name'] ) );
            $multioptionNode->appendAttribute( $doc->createAttributeNode( "priority", $multioption['priority'] ) );
            $multioptionNode->appendAttribute( $doc->createAttributeNode( 'default_option_id', $multioption['default_option_id'] ) );
            foreach ( $multioption['optionlist'] as $option )
            {
                $optionNode =& $doc->createElementNode( "option" );
                $optionNode->appendAttribute( $doc->createAttributeNode( "id", $option['id'] ) );
                $optionNode->appendAttribute( $doc->createAttributeNode( "option_id", $option['option_id'] ) );
                $optionNode->appendAttribute( $doc->createAttributeNode( "value", $option['value'] ) );
                $optionNode->appendAttribute( $doc->createAttributeNode( 'additional_price', $option['additional_price'] ) );
                $multioptionNode->appendChild( $optionNode );
            }
            $multioptions->appendChild( $multioptionNode );
        }
        $xml =& $doc->toString();
        return $xml;
    }

    /// \privatesection
    /// Contains the Option name
    var $Name;
    /// Contains the Options
    var $Options;
    /// Contains the multioption counter value
    var $MultiOptionCount;
    /// Contains the option counter value
    var $OptionCounter;
}
?>
