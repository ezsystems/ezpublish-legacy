<?php
//
// Definition of eZDateType class
//
// Created on: <26-Apr-2002 16:53:04 bf>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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
  \class eZDateType ezdatetype.php
  \ingroup eZDatatype
  \brief Stores a date value

*/

include_once( "kernel/classes/ezdatatype.php" );

define( "EZ_DATATYPESTRING_DATE", "ezdate" );
define( 'EZ_DATATYPESTRING_DATE_DEFAULT', 'data_int1' );
define( 'EZ_DATATYPESTRING_DATE_DEFAULT_EMTPY', 0 );
define( 'EZ_DATATYPESTRING_DATE_DEFAULT_CURRENT_DATE', 1 );
include_once( "lib/ezlocale/classes/ezdate.php" );

class eZDateType extends eZDataType
{
    function eZDateType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_DATE, ezi18n( 'kernel/classes/datatypes', "Date", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $year = $http->postVariable( $base . "_date_year_" . $contentObjectAttribute->attribute( "id" ) );
        $month = $http->postVariable( $base . "_date_month_" . $contentObjectAttribute->attribute( "id" ) );
        $day = $http->postVariable( $base . "_date_day_" . $contentObjectAttribute->attribute( "id" ) );
        $date = $year.$month.$day;
        $classAttribute =& $contentObjectAttribute->contentClassAttribute();
        if ( $contentObjectAttribute->validateIsRequired() )
        {
            if ( $year == '' or $month == '' or $day == '' )
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Missing date input.' ) );
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }
        }
        else
        {
            if ( $year == '' and $month == '' and $day == '' )
            {
                return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
        }

        if ( !checkdate( $month, $day, $year ) || $year < 1970 )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Date is not valid.' ) );
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
        }

        $datetime = mktime( 0, 0, 0, $month, $day, $year );
        if ( $datetime !== false )
            return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
        return EZ_INPUT_VALIDATOR_STATE_INVALID;
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $year = $http->postVariable( $base . "_date_year_" . $contentObjectAttribute->attribute( "id" ) );
        $month = $http->postVariable( $base . "_date_month_" . $contentObjectAttribute->attribute( "id" ) );
        $day = $http->postVariable( $base . "_date_day_" . $contentObjectAttribute->attribute( "id" ) );
        $date = new eZDate();
        $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
        if ( ( $year == '' and $month == '' and $day == '' )
             or !checkdate( $month, $day, $year ) or $year < 1970 )
        {
//             if ( !$contentClassAttribute->attribute( "is_required" ) )
                $date->setTimeStamp( 0 );
//             else
//                 $date->setTimeStamp( mktime() );
        }
        else
            $date->setMDY( $month, $day, $year );
        $contentObjectAttribute->setAttribute( "data_int", $date->timeStamp() );

        return true;
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        $date = new eZDate( );
        $stamp = $contentObjectAttribute->attribute( 'data_int' );
        $date->setTimeStamp( $stamp );
        return $date;
    }

    /*!
     Set class attribute value for template version
    */
    function initializeClassAttribute( &$classAttribute )
    {
        if ( $classAttribute->attribute( EZ_DATATYPESTRING_DATE_DEFAULT ) == null )
            $classAttribute->setAttribute( EZ_DATATYPESTRING_DATE_DEFAULT, 0 );
        $classAttribute->store();
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( &$contentObjectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $dataInt = $originalContentObjectAttribute->attribute( "data_int" );
            $contentObjectAttribute->setAttribute( "data_int", $dataInt );
        }
        else
        {
            $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
            $defaultType = $contentClassAttribute->attribute( EZ_DATATYPESTRING_DATE_DEFAULT );
            if ( $defaultType == 1 )
                $contentObjectAttribute->setAttribute( "data_int", mktime() );
        }
    }

    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $default = $base . "_ezdate_default_" . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $default ) )
        {
            $defaultValue = $http->postVariable( $default );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_DATE_DEFAULT,  $defaultValue );
        }
        return true;
    }

    /*!
     \reimp
    */
    function isIndexable()
    {
        return true;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_int' );
    }

    /*!
     Returns the date.
    */
    function title( &$contentObjectAttribute )
    {
        $locale =& eZLocale::instance();
        return $locale->formatDate( $contentObjectAttribute->attribute( "data_int" ) );
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_int" ) != 0;
    }

    /*!
     \reimp
    */
    function sortKey( &$contentObjectAttribute )
    {
        return (int)$contentObjectAttribute->attribute( 'data_int' );
    }

    /*!
     \reimp
    */
    function sortKeyType()
    {
        return 'int';
    }

    /*!
     \reimp
    */
    function serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $defaultValue = $classAttribute->attribute( EZ_DATATYPESTRING_DATE_DEFAULT );
        switch ( $defaultValue )
        {
            case EZ_DATATYPESTRING_DATE_DEFAULT_EMTPY:
            {
                $attributeParametersNode->appendChild( eZDOMDocument::createElementNode( 'default-value',
                                                                                         array( 'type' => 'empty' ) ) );
            } break;
            case EZ_DATATYPESTRING_DATE_DEFAULT_CURRENT_DATE:
            {
                $attributeParametersNode->appendChild( eZDOMDocument::createElementNode( 'default-value',
                                                                                         array( 'type' => 'current-date' ) ) );
            } break;
        }
    }

    /*!
     \reimp
    */
    function unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $defaultNode =& $attributeParametersNode->elementByName( 'default-value' );
        $defaultValue = strtolower( $defaultNode->attributeValue( 'type' ) );
        switch ( $defaultValue )
        {
            case 'empty':
            {
                $classAttribute->setAttribute( EZ_DATATYPESTRING_DATE_DEFAULT, EZ_DATATYPESTRING_DATE_DEFAULT_EMTPY );
            } break;
            case 'current-date':
            {
                $classAttribute->setAttribute( EZ_DATATYPESTRING_DATE_DEFAULT, EZ_DATATYPESTRING_DATE_DEFAULT_CURRENT_DATE );
            } break;
        }
    }

    /*!
     \param package
     \param content attribute

     \return a DOM representation of the content object attribute
    */
    function &serializeContentObjectAttribute( &$package, &$objectAttribute )
    {
        $node = new eZDOMNode();

        $node->setPrefix( 'ezobject' );
        $node->setName( 'attribute' );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'id', $objectAttribute->attribute( 'id' ), 'ezremote' ) );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'identifier', $objectAttribute->contentClassAttributeIdentifier(), 'ezremote' ) );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'name', $objectAttribute->contentClassAttributeName() ) );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'type', $this->isA() ) );

        $stamp = $objectAttribute->attribute( 'data_int' );

        if ( !is_null( $stamp ) )
        {
            include_once( 'lib/ezlocale/classes/ezdateutils.php' );
            $node->appendChild( eZDOMDocument::createElementTextNode( 'date', eZDateUtils::rfc1123Date( $stamp ) ) );
        }
        return $node;
    }

    /*!
     \reimp
     \param package
     \param contentobject attribute object
     \param ezdomnode object
    */
    function unserializeContentObjectAttribute( &$package, &$objectAttribute, $attributeNode )
    {
        $timeNode = $attributeNode->elementByName( 'date' );
        if ( is_object( $timeNode ) )
            $timestampNode = $timeNode->firstChild();
        if ( is_object( $timestampNode ) )
        {
            include_once( 'lib/ezlocale/classes/ezdateutils.php' );
            $objectAttribute->setAttribute( 'data_int', eZDateUtils::textToDate( $timestampNode->content() ) );
        }
    }
}

eZDataType::register( EZ_DATATYPESTRING_DATE, "ezdatetype" );

?>
