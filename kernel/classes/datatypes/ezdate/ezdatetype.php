<?php
//
// Definition of eZDateType class
//
// Created on: <26-Apr-2002 16:53:04 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
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
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
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


    function validateDateTimeHTTPInput( $day, $month, $year, &$contentObjectAttribute )
    {
        include_once( 'lib/ezutils/classes/ezdatetimevalidator.php' );
        $state = eZDateTimeValidator::validateDate( $day, $month, $year );
        if ( $state == EZ_INPUT_VALIDATOR_STATE_INVALID )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Date is not valid.' ) );
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
        }
        return $state;
    }
    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_date_year_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_date_month_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_date_day_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $year  = $http->postVariable( $base . '_date_year_' . $contentObjectAttribute->attribute( 'id' ) );
            $month = $http->postVariable( $base . '_date_month_' . $contentObjectAttribute->attribute( 'id' ) );
            $day   = $http->postVariable( $base . '_date_day_' . $contentObjectAttribute->attribute( 'id' ) );
            $classAttribute =& $contentObjectAttribute->contentClassAttribute();

            if ( $year == '' or $month == '' or $day == '' )
            {
                if ( !( $year == '' and $month == '' and $day == '' ) or
                     ( !$classAttribute->attribute( 'is_information_collector' ) and
                       $contentObjectAttribute->validateIsRequired() ) )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'Missing date input.' ) );
                    return EZ_INPUT_VALIDATOR_STATE_INVALID;
                }
                else
                    return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
            else
            {
                return $this->validateDateTimeHTTPInput( $day, $month, $year, $contentObjectAttribute );
            }
        }
        else
            return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_date_year_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_date_month_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_date_day_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {

            $year  = $http->postVariable( $base . '_date_year_' . $contentObjectAttribute->attribute( 'id' ) );
            $month = $http->postVariable( $base . '_date_month_' . $contentObjectAttribute->attribute( 'id' ) );
            $day   = $http->postVariable( $base . '_date_day_' . $contentObjectAttribute->attribute( 'id' ) );
            $date = new eZDate();
            $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();

            if ( ( $year == '' and $month == '' and $day == '' ) or
                 !checkdate( $month, $day, $year ) or
                 $year < 1970 )
            {
                $date->setTimeStamp( 0 );
            }
            else
            {
                $date->setMDY( $month, $day, $year );
            }

            $contentObjectAttribute->setAttribute( 'data_int', $date->timeStamp() );
            return true;
        }
        return false;
    }

    /*!
     \reimp
    */
    function validateCollectionAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_date_year_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_date_month_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_date_day_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $year  = $http->postVariable( $base . '_date_year_' . $contentObjectAttribute->attribute( 'id' ) );
            $month = $http->postVariable( $base . '_date_month_' . $contentObjectAttribute->attribute( 'id' ) );
            $day   = $http->postVariable( $base . '_date_day_' . $contentObjectAttribute->attribute( 'id' ) );
            $classAttribute =& $contentObjectAttribute->contentClassAttribute();

            if ( $year == '' or $month == '' or $day == '' )
            {
                if ( !( $year == '' and $month == '' and $day == '' ) or
                     $contentObjectAttribute->validateIsRequired() )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'Missing date input.' ) );
                    return EZ_INPUT_VALIDATOR_STATE_INVALID;
                }
                else
                    return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
            else
            {
                return $this->validateDateTimeHTTPInput( $day, $month, $year, $contentObjectAttribute );
            }
        }
        else
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
    }

    /*!
     Fetches the http post variables for collected information
    */
    function fetchCollectionAttributeHTTPInput( &$collection, &$collectionAttribute, &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_date_year_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_date_month_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_date_day_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {

            $year  = $http->postVariable( $base . '_date_year_' . $contentObjectAttribute->attribute( 'id' ) );
            $month = $http->postVariable( $base . '_date_month_' . $contentObjectAttribute->attribute( 'id' ) );
            $day   = $http->postVariable( $base . '_date_day_' . $contentObjectAttribute->attribute( 'id' ) );
            $date = new eZDate();
            $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();

            if ( ( $year == '' and $month == '' and $day == '' ) or
                 !checkdate( $month, $day, $year ) or
                 $year < 1970 )
            {
                $date->setTimeStamp( 0 );
            }
            else
            {
                $date->setMDY( $month, $day, $year );
            }

            $collectionAttribute->setAttribute( 'data_int', $date->timeStamp() );
            return true;
        }
        return false;
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
     \reimp
    */
    function isInformationCollector()
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
     \return string representation of an contentobjectattribute data for simplified export

    */
    function toString( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_int' );
    }

    function fromString( &$contentObjectAttribute, $string )
    {
        return $contentObjectAttribute->setAttribute( 'data_int', $string );
    }

    /*!
     Returns the date.
    */
    function title( &$contentObjectAttribute )
    {
        $locale =& eZLocale::instance();
        $retVal = $contentObjectAttribute->attribute( "data_int" ) == 0 ? '' : $locale->formatDate( $contentObjectAttribute->attribute( "data_int" ) );
        return $retVal;
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
    function serializeContentObjectAttribute( &$package, &$objectAttribute )
    {
        $node = $this->createContentObjectAttributeDOMNode( $objectAttribute );

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
