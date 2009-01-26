<?php
//
// Definition of eZOption class
//
// Created on: <29-Jul-2004 15:52:24 gv>
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

/*!
  \class eZMultiOption ezmultioption.php
  \ingroup eZDatatype
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
   //include_once( "kernel/classes/datatypes/ezoption/ezmultioption.php" );
   $option = new eZOption( "Car" );
   $newID = $option->addMultiOption("Model",$priority,false);
      $option->addOption( $newID, "", "Model - A", "100", false );
      $option->addOption( $newID, "", "Model - B", "100", false );
      $option->addOption( $newID, "", "Model - C", "100", false );

   $newID = $option->addMultiOption( "Color", $priority, false );
      $option->addOption( $newID, "", "Red", "", false );
      $option->addOption( $newID, "", "Blue", "", false );
  // Serialize the class to an XML document
  $xmlString = $option->xmlString();
  \endcode
*/

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
        foreach ( $array_remove as $id )
        {
            unset( $this->Options[ $id - 1 ] );
        }
        $this->Options = array_values( $this->Options );
        $this->changeMultiOptionId();
    }

    /*!
      Remove Options from the multioption.
      This function first remove selected options and then reset the key value if all options for that multioption.
      \param $arrayRemove is a list of all array elements which is selected to remove from the multioptions.
      \param $optionId is the key value if multioption from which it is required to remove the options.
      \sa removeMultiOptions()
    */
    function removeOptions( $arrayRemove, $optionId )
    {
        foreach ( $arrayRemove as  $id )
        {
            unset( $this->Options[$optionId]['optionlist'][$id - 1] );
        }
        $this->Options = array_values( $this->Options );
        $i = 1;
        foreach ( $this->Options[$optionId]['optionlist'] as $key => $opt )
        {
            $this->Options[$optionId]['optionlist'][$key]['id'] = $i;
            $i++;
        }
    }

    /*!
     \return list of supported attributes
    */
    function attributes()
    {
        return array( 'name',
                      'multioption_list' );
    }

    /*!
      Returns true if object have an attribute.
      The valid attributes are \c name and \c multioption_list.
      \param $name contains the name of attribute
    */
    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    /*!
    Returns an attribute. The valid attributes are \c name and \c multioption_list
    \a name contains the name of multioption
    \a multioption_list contains the list of all multioptions.
    */
    function attribute( $name )
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
            default:
            {
                eZDebug::writeError( "Attribute '$name' does not exist", 'eZMultiOption::attribute' );
                return null;
            }break;
        }
    }

    /*!
    Will decode an xml string and initialize the eZ Multi option object.
    If $xmlString is on empty then it will call addMultiOption() and addOption() functions
    to create new multioption else it will decode the xml string.
    \param $xmlString contains the complete data structure for multioptions.
    \sa xmlString()
    */
    function decodeXML( $xmlString )
    {
        $this->OptionCounter = 0;
        $this->Options = array();
        if ( $xmlString != "" )
        {
            $dom = new DOMDocument( '1.0', 'utf-8' );
            $success = $dom->loadXML( $xmlString );

            $root = $dom->documentElement;
            // set the name of the node
            $this->Name = $root->getElementsByTagName( "name" )->item( 0 )->textContent;
            $this->OptionCounter = $root->getAttribute( "option_counter" );
            $multioptionsNode = $root->getElementsByTagName( "multioptions" )->item( 0 );
            $multioptionsList = $multioptionsNode->getElementsByTagName( "multioption" );
            //Loop for MultiOptions
            foreach ( $multioptionsList as $multioption )
            {
                $newID = $this->addMultiOption( $multioption->getAttribute( "name" ),
                                                $multioption->getAttribute( "priority" ),
                                                $multioption->getAttribute( "default_option_id" ) );
                $optionNode = $multioption->getElementsByTagName( "option" );
                foreach ( $optionNode as $option )
                {
                    $this->addOption( $newID, $option->getAttribute( "option_id" ), $option->getAttribute( "value" ), $option->getAttribute( "additional_price" ) );
                }
            }
        }
        else
        {
            //The control come here while creaging new object for MultiOption
            $nodeID = $this->addMultiOption( "", 0, false );
            $this->addOption( $nodeID, "", "", "" );
        }
    }

    /*!
     Will return the XML string for this MultiOption set.
     \sa decodeXML()
    */
    function xmlString()
    {
        $doc = new DOMDocument( '1.0', 'utf-8' );
        $root = $doc->createElement( "ezmultioption" );
        $root->setAttribute( 'option_counter', $this->OptionCounter );
        $doc->appendChild( $root );

        $nameNode = $doc->createElement( 'name' );
        $nameNode->appendChild( $doc->createTextNode( $this->Name ) );
        $root->appendChild( $nameNode );

        $multiOptionsNode = $doc->createElement( "multioptions" );
        $root->appendChild( $multiOptionsNode );

        foreach ( $this->Options as $multioption )
        {
            unset( $multioptionNode );
            $multioptionNode = $doc->createElement( "multioption" );
            $multioptionNode->setAttribute( "id", $multioption['id'] );
            $multioptionNode->setAttribute( "name", $multioption['name'] );
            $multioptionNode->setAttribute( "priority", $multioption['priority'] );
            $multioptionNode->setAttribute( 'default_option_id', $multioption['default_option_id'] );
            foreach ( $multioption['optionlist'] as $option )
            {
                unset( $optionNode );
                $optionNode = $doc->createElement( "option" );
                $optionNode->setAttribute( "id", $option['id'] );
                $optionNode->setAttribute( "option_id", $option['option_id'] );
                $optionNode->setAttribute( "value", $option['value'] );
                $optionNode->setAttribute( 'additional_price', $option['additional_price'] );
                $multioptionNode->appendChild( $optionNode );
            }
            $multiOptionsNode->appendChild( $multioptionNode );
        }
        $xml = $doc->saveXML();
        return $xml;
    }

    /// \privatesection
    /// Contains the Option name
    public $Name;
    /// Contains the Options
    public $Options;
    /// Contains the multioption counter value
    public $MultiOptionCount;
    /// Contains the option counter value
    public $OptionCounter;
}
?>
