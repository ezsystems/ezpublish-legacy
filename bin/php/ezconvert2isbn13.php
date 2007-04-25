<?php
// Created on: <24-Apr-2007 09:53:50 bjorn>
//
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: n.n.n
// BUILD VERSION: nnnnn
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

/*! \file ezconvert2isbn13.php
*/
set_time_limit( 0 );

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'lib/ezdb/classes/ezdb.php' );

include_once( 'kernel/classes/ezscript.php' );
include_once( 'kernel/classes/ezcontentclass.php' );
include_once( 'kernel/classes/ezcontentclassattribute.php' );
include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentobjectattribute.php' );

include_once( 'kernel/classes/datatypes/ezisbn/ezisbntype.php' );
include_once( 'kernel/classes/datatypes/ezisbn/ezisbn13.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "eZ publish ISBN10 to ISBN13 converter\n\n" .
                                                         "Converts a ISBN10 number to ISBN 13\n" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[class-id:][attribute-id:][all-classes][f|force]",
                                "",
                                array( 'class-id' => 'The class id for the ISBN attribute.',
                                       'attribute-id' => 'The attribute id for the ISBN attribute which should be converted.',
                                       'all-classes' => 'Will convert all isbn attributes in all content classes.',
                                       'f' => 'Short alias for force',
                                       'force' => 'Will convert all attributes even if the class is set to isbn10.' ) );
$script->initialize();

$classID = $options['class-id'];
$attributeID = $options['attribute-id'];
$allClasses = $options['all-classes'];
$force = $options['force'];

$params = array( 'force' => $force );
$converter = new eZISBN10To13Converter( $script, $cli, $params );

$found = false;
if ( $allClasses === true )
{
    $allClassesStatus = $converter->addAllClasses();
    $found = true;
}
else
{
    if ( is_numeric( $classID ) )
    {
        $classStatus = $converter->addClass( $classID );
        $found = true;
    }

    if ( is_numeric( $attributeID ) )
    {
        $attributeStatus = $converter->addAttribute( $attributeID );
        $found = true;
    }
}

if ( $found == true )
{
    if ( $converter->attributeCount() > 0 )
    {
        $converter->execute();
    }
    else
    {
        $cli->output( 'Did not find any isbn attributes.' );
    }
}
else
{
    $script->showHelp();
}



class eZISBN10To13Converter
{
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

    function addAllClasses()
    {
        $db =& eZDB::instance();
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
                                        $this->Cli->stylize( 'strong', $classAttributeID ) . ' is not set to ISBN13. Use --force to set the ISBN13 flag' );
                }

            }
        }

        return $status;
    }

    function addClass( $classID )
    {
        $status = false;
        if ( is_numeric( $classID ) )
        {
            $class = eZContentClass::fetch( $classID );
            if ( get_class( $class ) == 'ezcontentclass' )
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
                                                    $this->Cli->stylize( 'strong', $attribute->attribute( 'id' ) ) . ' is not set to ISBN13. Use --force to set the ISBN13 flag' );
                            }
                        }
                    }
                }

                if ( $attributeFound === false )
                {
                    $this->Cli->output( $this->Cli->stylize( 'warning', 'Warning:' ) . ' Did not find any ezisbn attributes in contentclass: ' .
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

    function addAttribute( $attributeID )
    {
        $status = false;
        if ( is_numeric( $attributeID ) )
        {
            $classAttribute = eZContentClassAttribute::fetch( $attributeID );
            if ( get_class( $classAttribute ) == 'ezcontentclassattribute' )
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
                                            $this->Cli->stylize( 'strong', $attributeID ) . ' is not set to ISBN13. Use --force to set the ISBN13 flag' );
                    }
                }
                else
                {
                    $this->Cli->output( $this->Cli->stylize( 'warning', 'Warning:' ) . ' The attribute id ' .
                                        $this->Cli->stylize( 'strong', $attributeID ) . ' is not an isbn datatype but of type ' .
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

    function attributeCount()
    {
        return count( $this->AttributeArray );
    }

    function execute()
    {
        foreach ( $this->AttributeArray as $classAttribute )
        {
            $contentClass = eZContentClass::fetch( $classAttribute->attribute( 'contentclass_id' ) );
            $this->Cli->output( "Process class: " . $this->Cli->stylize( 'strong', $classAttribute->attribute( 'contentclass_id' ) ) .
                                " (" . $contentClass->attribute( 'name' ) . "), attribute id: " .
                                $this->Cli->stylize( 'strong', $classAttribute->attribute( 'id' ) ) .
                                " (" . $classAttribute->attribute( 'name' ) . "):" );
            $this->updateContentFromClass( $classAttribute->attribute( 'id' ) );
            $this->updateClassAttributeToISBN13( $classAttribute->attribute( 'id' ) );
            $this->Cli->output( " Finished." );

        }
    }

    function updateContentFromClass( $classAttributeID )
    {
        $asObject = true;

        $i = 0;
        $offset = 0;
        $limit = 100;
        $conditions = array( "contentclassattribute_id" => $classAttributeID );
        $limitArray = array( 'offset' => $offset,
                             'limit' => $limit );

        $sortArray = array( 'id' => 'asc' );
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
            unset( $contentObjectList );
            $offset += $limit;
            $limitArray = array( 'offset' => $offset,
                                 'limit' => $limit );
        }
        $repeatLength = 70 - ( $i % 70 );
        $this->Cli->output( str_repeat( ' ', $repeatLength  ) . ' ' . $this->Cli->stylize( 'strong', $i * $limit ), false );
    }

    function updateContentObjectAttribute( $contentObjectAttribute )
    {
        $isbnNumber =& $contentObjectAttribute->attribute( 'data_text' );

        if ( trim( $isbnNumber ) !=  "" )
        {
            $error = false;

            // Validate the isbn number.
            $digits = preg_replace( "/\-/", "", $isbnNumber );

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

    function updateClassAttributeToISBN13( $classAttributeID )
    {
        $db =& eZDB::instance();
        $sql = "UPDATE ezcontentclass_attribute SET data_int1='1' WHERE id='" . $classAttributeID . "'";
        $db->query( $sql );
    }

    function updateContentISBNNumber( $contentObjectAttribute, $formatedISBN13Value )
    {
        $contentObjectAttributeID =& $contentObjectAttribute->attribute( 'id' );
        $version =& $contentObjectAttribute->attribute( 'version' );
        $db =& eZDB::instance();
        $sql = "UPDATE ezcontentobject_attribute SET data_text='" . $db->escapeString( $formatedISBN13Value ) .
               "' WHERE id='" .  $contentObjectAttributeID . "' AND version='" . $version . "'" ;
        $db->query( $sql );
    }

    function convertISBN10to13( $isbnNumber )
    {
        $isbnNr = 978 . substr( $isbnNumber, 0, 9 );

        $weight13 = 1;
        $checksum13 = 0;
        $val = 0;

        for ( $i = 0; $i < 12; $i++ )
        {
            $val = $isbnNr{$i};
            $checksum13 = $checksum13 + $weight13 * $val;
            $weight13 = ( $weight13 + 2 ) % 4;
        }

        $checkDigit = 10 - ( ( $checksum13 % 10 ) ) % 10;
        $isbnNr .= $checkDigit;

        return $isbnNr;
    }


    var $Cli;
    var $Script;
    var $AttributeArray;
}

$script->shutdown();

?>
