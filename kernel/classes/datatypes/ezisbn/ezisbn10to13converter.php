<?php
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

class eZISBN10To13Converter
{
    /*!
     Constructor
     \param $script The variable is set earlier in the script and transfered to the class.
     \param $cli Is set earlier in the script, and used to send output / feedback to the user.
     \param $params custom parameters to the class. The Force parameter is now set as a
                    class variable for the other functions.
    */
    function eZISBN10To13Converter( $script, $cli, $params )
    {
        $this->Script = $script;
        $this->Cli = $cli;
        $this->AttributeArray = array();
        if ( isset( $params['force'] ) )
        {
            $this->Force = $params['force'];
        }
        else
        {
            $this->Force = false;
        }
    }


    /*!
      Add all classes. Will fetch all class attributes from the database that has the ISBN
      datatype and register it in a class variable AttributeArray for later processing.

      \return true if successfull and false if not.
     */
    function addAllClasses()
    {
        $db = eZDB::instance();
        $this->Cli->output( $this->Cli->stylize( 'strong', 'Fetch All' ) . ' classes:' );
        $sql = "SELECT id, data_int1 FROM ezcontentclass_attribute WHERE " .
               "data_type_string='ezisbn' and version='0'";

        $classAttributeList = $db->arrayQuery( $sql );
        $status = false;
        if ( count( $classAttributeList ) > 0 )
        {
            foreach ( $classAttributeList as $classAttributeItem )
            {
                $classAttributeID = $classAttributeItem['id'];
                $isIsbn13 = $classAttributeItem['data_int1'];
                $classAttribute = eZContentClassAttribute::fetch( $classAttributeID );

                if ( $this->Force === true or $isIsbn13 == 1 )
                {
                    $this->AttributeArray[$classAttributeID] = $classAttribute;
                    $status = true;
                }
                else
                {
                    $this->Cli->output( $this->Cli->stylize( 'warning', 'Warning:' ) . ' The Class id ' .
                                        $this->Cli->stylize( 'strong', $classAttribute->attribute( 'contentclass_id' ) ) . ' attribute id ' .
                                        $this->Cli->stylize( 'strong', $classAttributeID ) . ' is not set to ISBN-13. Use --force to set the ISBN-13 flag' );
                }

            }
        }

        return $status;
    }

    /*!
      Add all ezisbn class attributes from a class with a specific id and register them
      in a class variable AttributeArray for later processing.

      \return true if successfull and false if not.
     */
    function addClass( $classID )
    {
        $status = false;
        if ( is_numeric( $classID ) )
        {
            $class = eZContentClass::fetch( $classID );
            if ( $class instanceof eZContentClass )
            {
                $classFilter = array( 'data_type_string' => 'ezisbn' );
                $classAttributes = $class->fetchAttributes();
                $attributeFound = false;
                if ( count( $classAttributes ) > 0 )
                {
                    foreach ( $classAttributes as $attribute )
                    {
                        if ( $attribute->attribute( 'data_type_string' ) == 'ezisbn' )
                        {
                            if ( $attribute->attribute( 'data_int1' ) == 1 or $this->Force === true )
                            {
                                $attributeFound = true;
                                $this->AttributeArray[$attribute->attribute( 'id' )] = $attribute;
                            }
                            else
                            {
                                $this->Cli->output( $this->Cli->stylize( 'warning', 'Warning:' ) . ' The attribute id ' .
                                                    $this->Cli->stylize( 'strong', $attribute->attribute( 'id' ) ) . ' is not set to ISBN-13. Use --force to set the ISBN-13 flag' );
                            }
                        }
                    }
                }

                if ( $attributeFound === false )
                {
                    $this->Cli->output( $this->Cli->stylize( 'warning', 'Warning:' ) . ' Did not find any ISBN attributes in contentclass: ' .
                                        $this->Cli->stylize( 'strong', $classID ) . '.' );
                }
            }
            else
            {
                $this->Cli->output( $this->Cli->stylize( 'warning', 'Warning:' ) . ' the class id ' .
                                    $this->Cli->stylize( 'strong', $classID ) . ' does not exist.' );
            }
        }
        else if ( $classID !== null )
        {
            $status = true;
            $this->Cli->output( $this->Cli->stylize( 'error', 'Error:' ) . ' the class id need to be numeric.' );
        }

        return $status;
    }

     /*!
      Add one ezisbn class attribute with a specific class attribute id and register it
      in a class variable AttributeArray for later processing.

      \return true if successfull and false if not.
     */
    function addAttribute( $attributeID )
    {
        $status = false;
        if ( is_numeric( $attributeID ) )
        {
            $classAttribute = eZContentClassAttribute::fetch( $attributeID );
            if ( $classAttribute instanceof eZContentClassAttribute )
            {
                if ( $classAttribute->attribute( 'data_type_string' ) == 'ezisbn' )
                {
                    if ( $classAttribute->attribute( 'data_int1' ) == 1 or $this->Force === true )
                    {
                        $this->AttributeArray[$classAttribute->attribute( 'id' )] = $classAttribute;
                    }
                    else
                    {
                        $this->Cli->output( $this->Cli->stylize( 'warning', 'Warning:' ) . ' The attribute id ' .
                                            $this->Cli->stylize( 'strong', $attributeID ) . ' is not set to ISBN-13. Use --force to set the ISBN-13 flag' );
                    }
                }
                else
                {
                    $this->Cli->output( $this->Cli->stylize( 'warning', 'Warning:' ) . ' The attribute id ' .
                                        $this->Cli->stylize( 'strong', $attributeID ) . ' is not an ISBN datatype but of type ' .
                                        $this->Cli->stylize( 'strong', $classAttribute->attribute( 'data_type_string' ) ) . '.' );
                }
            }
            else
            {
                $this->Cli->output( $this->Cli->stylize( 'warning', 'Warning:' ) . ' The attribute id ' .
                                    $this->Cli->stylize( 'strong', $attributeID ) . ' does not exist.' );
            }
        }
        else if ( $attributeID !== null )
        {
            $this->Cli->output( $this->Cli->stylize( 'error', 'Error:' ) . ' the attribute id need to be numeric.' );
            $status = true;
        }

        return $status;
    }


    /*!
      \return count of the current amount of class attributes registered in the attribute array.
     */
    function attributeCount()
    {
        return count( $this->AttributeArray );
    }

    /*!
      Start processing the content object attributes.
     */
    function execute()
    {
        foreach ( $this->AttributeArray as $classAttribute )
        {
            $contentClass = eZContentClass::fetch( $classAttribute->attribute( 'contentclass_id' ) );
            $this->Cli->output( "Process class: " . $this->Cli->stylize( 'strong', $classAttribute->attribute( 'contentclass_id' ) ) .
                                " (" . $contentClass->attribute( 'name' ) . "), attribute id: " .
                                $this->Cli->stylize( 'strong', $classAttribute->attribute( 'id' ) ) .
                                " (" . $classAttribute->attribute( 'name' ) . "):" );

            $this->updateContentFromClassAttribute( $classAttribute->attribute( 'id' ) );
            $this->updateClassAttributeToISBN13( $classAttribute->attribute( 'id' ) );

            $this->Cli->output( " Finished." );
        }
    }

    /*!
      Update content in a ezisbn datatype for one specific class attribute id.
      \param $classAttributeID is the class attribute id for for the ISBN datatype.
     */
    function updateContentFromClassAttribute( $classAttributeID )
    {
        $asObject = true;

        $i = 0;
        $offset = 0;
        $countList = 0;
        $limit = 100;
        $conditions = array( "contentclassattribute_id" => $classAttributeID );
        $limitArray = array( 'offset' => $offset,
                             'limit' => $limit );

        $sortArray = array( 'id' => 'asc' );

        // Only fetch some objects each time to avoid memory problems.
        while ( true )
        {
            $contentObjectAttributeList = eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(),
                                                                      null,
                                                                      $conditions,
                                                                      $sortArray,
                                                                      $limitArray,
                                                                      $asObject );
            if ( count( $contentObjectAttributeList ) == 0 )
            {
                break;
            }
            foreach ( $contentObjectAttributeList as $contentObjectAttribute )
            {
                $this->updateContentObjectAttribute( $contentObjectAttribute );
            }
            $this->Cli->output( ".", false );
            $i++;
            if ( ( $i % 70 ) == 0 )
            {
                $this->Cli->output( ' ' . $this->Cli->stylize( 'strong', $i * $limit ) );
            }
            $countList = count( $contentObjectAttributeList );
            unset( $contentObjectList );
            $offset += $limit;
            $limitArray = array( 'offset' => $offset,
                                 'limit' => $limit );
        }
        $repeatLength = 70 - ( $i % 70 );
        $count = ( ( $i - 1 ) * $limit ) + $countList;
        $this->Cli->output( str_repeat( ' ', $repeatLength  ) . ' ' . $this->Cli->stylize( 'strong', $count ), false );
    }

    /*!
      Convert the ISBN number for a content object attribute with the specific
      content attribute id.
      \param $contentObjectAttribute Should be a object of eZContentObjectAttribute.
     */
    function updateContentObjectAttribute( $contentObjectAttribute )
    {
        $isbnNumber = $contentObjectAttribute->attribute( 'data_text' );
        $isbnValue = trim( $isbnNumber );
        $error = false;

        // If the number only consists of hyphen, it should be emty.
        if ( preg_match( "/^\-+$/", $isbnValue ) )
        {
            $emtyValue = '';
            $this->updateContentISBNNumber( $contentObjectAttribute, $emtyValue );
            return true;
        }
        // Validate the ISBN number.
        $digits = preg_replace( "/\-/", "", $isbnValue );

        if ( trim( $digits ) !=  "" )
        {
            // If the length of the number is 10, it is an ISBN-10 number and need
            // to be converted to ISBN-13.
            if ( strlen( $digits ) == 10 )
            {
                $ean = eZISBNType::convertISBN10toISBN13( $digits );
            }
            else if ( strlen( $digits ) == 13 )
            {
                $ean = $digits;
            }
            else
            {
                $error = true;
            }

            if ( $error === false )
            {
                $isbn13 = new eZISBN13();
                $formatedISBN13Value = $isbn13->formatedISBNValue( $ean, $error );
            }

            if ( $error === false )
            {
                $this->updateContentISBNNumber( $contentObjectAttribute, $formatedISBN13Value );
            }
            else
            {
                $this->Cli->output( $this->Cli->stylize( 'warning', 'Warning:' ) . ' ISBN: ' .
                                    $this->Cli->stylize( 'strong', $isbnNumber ) . ' is not valid. You need to update contentobject: ' .
                                    $this->Cli->stylize( 'strong', $contentObjectAttribute->attribute( 'contentobject_id' ) ) . ' version: ' .
                                    $this->Cli->stylize( 'strong', $contentObjectAttribute->attribute( 'version' ) ) . ' manually.' );
            }
        }
    }

    /*!
     Does the update of the class attribute directly to the database, which will only alter
     the attribute for if the ISBN datatype is ISBN-13.
     \param $classAttributeID is the Class attribute id for the ISBN datatype.
    */
    function updateClassAttributeToISBN13( $classAttributeID )
    {
        $db = eZDB::instance();
        $sql = "UPDATE ezcontentclass_attribute SET data_int1='1' WHERE id='" . $classAttributeID . "'";
        $db->query( $sql );
    }

    /*!
     Does the update of the content object attribute directly to the database, which will only alter
     the attribute for if the ISBN datatype is ISBN-13.
     \param $contentObjectAttribute Is an object of eZContentObjectAttribute.
     \param $formatedISBN13Value contains the formated version of the ISBN-13 number with hyphen as delimiter.
    */
    function updateContentISBNNumber( $contentObjectAttribute, $formatedISBN13Value )
    {
        $contentObjectAttributeID = $contentObjectAttribute->attribute( 'id' );
        $version = $contentObjectAttribute->attribute( 'version' );
        $db = eZDB::instance();
        $sql = "UPDATE ezcontentobject_attribute SET data_text='" . $db->escapeString( $formatedISBN13Value ) .
               "' WHERE id='" .  $contentObjectAttributeID . "' AND version='" . $version . "'" ;
        $db->query( $sql );
    }

    public $Cli;
    public $Script;
    public $AttributeArray;
}

?>