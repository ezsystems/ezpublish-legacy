<?php
//
// Definition of eZDateTimeType class
//
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

//!! eZKernel
//! The class eZDateTimeType
/*!

*/

include_once( "kernel/classes/ezdatatype.php" );
include_once( "lib/ezlocale/classes/ezdatetime.php" );

define( "EZ_DATATYPESTRING_DATETIME", "ezdatetime" );
define( 'EZ_DATATYPESTRING_DATETIME_DEFAULT', 'data_int1' );
define( 'EZ_DATATYPESTRING_DATETIME_DEFAULT_EMTPY', 0 );
define( 'EZ_DATATYPESTRING_DATETIME_DEFAULT_CURRENT_DATE', 1 );


class eZDateTimeType extends eZDataType
{
    function eZDateTimeType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_DATETIME, ezi18n( 'kernel/classes/datatypes', "Datetime field", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $year = $http->postVariable( $base . "_datetime_year_" . $contentObjectAttribute->attribute( "id" ) );
        $month = $http->postVariable( $base . "_datetime_month_" . $contentObjectAttribute->attribute( "id" ) );
        $day = $http->postVariable( $base . "_datetime_day_" . $contentObjectAttribute->attribute( "id" ) );
        $hour = $http->postVariable( $base . "_datetime_hour_" . $contentObjectAttribute->attribute( "id" ) );
        $minute = $http->postVariable( $base . "_datetime_minute_" . $contentObjectAttribute->attribute( "id" ) );
        $classAttribute =& $contentObjectAttribute->contentClassAttribute();

        if ( ( $classAttribute->attribute( "is_required" ) == false ) and
             $year == '' and $month == '' and $day == '' and
             $hour == '' and $minute == '' )
        {
            return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
        }
        if ( $classAttribute->attribute( "is_required" ) and
             $year == '' and $month == '' and $day == '' and
             $hour == '' and $minute == '' )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Missing datetime input.' ) );
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
        }

        $datetime = mktime( $hour, $minute, 0, $month, $day, $year );
        if ( $datetime !== false )
            return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
        return EZ_INPUT_VALIDATOR_STATE_INVALID;
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $year = $http->postVariable( $base . "_datetime_year_" . $contentObjectAttribute->attribute( "id" ) );
        $month = $http->postVariable( $base . "_datetime_month_" . $contentObjectAttribute->attribute( "id" ) );
        $day = $http->postVariable( $base . "_datetime_day_" . $contentObjectAttribute->attribute( "id" ) );
        $hour = $http->postVariable( $base . "_datetime_hour_" . $contentObjectAttribute->attribute( "id" ) );
        $minute = $http->postVariable( $base . "_datetime_minute_" . $contentObjectAttribute->attribute( "id" ) );
        $dateTime = new eZDateTime();
        $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
        if ( $year == '' and $month == '' and $day == '' and
             $hour == '' and $minute == '' )
        {
//             if ( !$contentClassAttribute->attribute( "is_required" ) )
                $dateTime->setTimeStamp( 0 );
//             else
//                 $dateTime->setTimeStamp( mktime() );
        }
        else
            $dateTime->setMDYHMS( $month, $day, $year, $hour, $minute, 0 );
        eZDebug::writeDebug( $dateTime->timeStamp(), 'datetime' );
        $contentObjectAttribute->setAttribute( "data_int", $dateTime->timeStamp() );
        return true;
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        $dateTime = new eZDateTime();
        $stamp = $contentObjectAttribute->attribute( 'data_int' );
        $dateTime->setTimeStamp( $stamp );
        return $dateTime;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    /*!
     Set class attribute value for template version
    */
    function initializeClassAttribute( &$classAttribute )
    {
        if ( $classAttribute->attribute( EZ_DATATYPESTRING_DATETIME_DEFAULT ) == null )
            $classAttribute->setAttribute( EZ_DATATYPESTRING_DATETIME_DEFAULT, 0 );
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
            $defaultType = $contentClassAttribute->attribute( EZ_DATATYPESTRING_DATETIME_DEFAULT );
            if ( $defaultType == 1 )
                $contentObjectAttribute->setAttribute( "data_int", mktime() );
        }
    }

    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $default = $base . "_ezdatetime_default_" . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $default ) )
        {
            $defaultValue = $http->postVariable( $default );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_DATETIME_DEFAULT,  $defaultValue );
        }
        return true;
    }

    /*!
     Returns the date.
    */
    function title( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_int" );
    }

    /*!
     \reimp
    */
    function &sortKey( &$contentObjectAttribute )
    {
        return (int)$contentObjectAttribute->attribute( 'data_int' );
    }

        /*!
     \reimp
    */
    function &sortKeyType()
    {
        return 'int';
    }

    /*!
     \reimp
    */
    function &serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $defaultValue = $classAttribute->attribute( EZ_DATATYPESTRING_DATETIME_DEFAULT );
        switch ( $defaultValue )
        {
            case EZ_DATATYPESTRING_DATETIME_DEFAULT_EMTPY:
            {
                $attributeParametersNode->appendChild( eZDOMDocument::createElementNode( 'default-value',
                                                                                         array( 'type' => 'empty' ) ) );
            } break;
            case EZ_DATATYPESTRING_DATETIME_DEFAULT_CURRENT_DATE:
            {
                $attributeParametersNode->appendChild( eZDOMDocument::createElementNode( 'default-value',
                                                                                         array( 'type' => 'current-date' ) ) );
            } break;
        }
    }

    /*!
     \reimp
    */
    function &unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
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
}

eZDataType::register( EZ_DATATYPESTRING_DATETIME, "ezdatetimetype" );

?>
