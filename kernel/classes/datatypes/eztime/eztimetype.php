<?php
//
// Definition of eZTimeType class
//
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

//!! eZKernel
//! The class eZTimeType

/*!

*/
include_once( "kernel/classes/ezdatatype.php" );
include_once( "lib/ezlocale/classes/eztime.php" );
include_once( "lib/ezlocale/classes/ezlocale.php" );

define( "EZ_DATATYPESTRING_TIME", "eztime" );
define( 'EZ_DATATYPESTRING_TIME_DEFAULT', 'data_int1' );
define( 'EZ_DATATYPESTRING_TIME_DEFAULT_EMTPY', 0 );
define( 'EZ_DATATYPESTRING_TIME_DEFAULT_CURRENT_DATE', 1 );

class eZTimeType extends eZDataType
{
    function eZTimeType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_TIME, ezi18n( 'kernel/classes/datatypes', "Time field", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $hour = $http->postVariable( $base . "_time_hour_" . $contentObjectAttribute->attribute( "id" ) );
        $minute = $http->postVariable( $base . "_time_minute_" . $contentObjectAttribute->attribute( "id" ) );
        $time =  $hour.':'.$minute;
        $classAttribute =& $contentObjectAttribute->contentClassAttribute();

        if ( ( $classAttribute->attribute( "is_required" ) == false ) and
             $hour == '' and $minute == '' )
        {
            return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
        }
        if ( $classAttribute->attribute( "is_required" ) and
             $hour == '' and $minute == '' )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Missing time input.' ) );
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
        }

        $datetime = mktime( $hour, $minute );
        if ( $datetime !== false )
            return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;

        return EZ_INPUT_VALIDATOR_STATE_INVALID;
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        $hour = $http->postVariable( $base . "_time_hour_" . $contentObjectAttribute->attribute( "id" ) );
        $minute = $http->postVariable( $base . "_time_minute_" . $contentObjectAttribute->attribute( "id" ) );

        $time = new eZTime( );
        $contentClassAttribute =& $contentObjectAttribute->contentClassAttribute();
        if ( $hour == '' and $minute == '' )
        {
//             if ( !$contentClassAttribute->attribute( "is_required" ) )
                $time->setTimeStamp( 0 );
//             else
//                 $time->setTimeStamp( mktime() );
        }
        else
            $time->setHMS( $hour, $minute, 0 );

        eZDebug::writeDebug( $time->timeStamp(), 'time' );
        $contentObjectAttribute->setAttribute( "data_int", $time->timeStamp() % eZTime::secondsPerDay() );
        return true;
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        $time = new eZTime( );
        $stamp = $contentObjectAttribute->attribute( 'data_int' ) % eZTime::secondsPerDay();
        $time->setTimeStamp( $stamp );
        return $time;
    }

    /*!
     \reimp
    */
    function &sortKey( &$contentObjectAttribute )
    {
        $gmtSeconds   = (int) $contentObjectAttribute->attribute( 'data_int' ) % eZTime::secondsPerDay();
        $localSeconds = ( $gmtSeconds + date( 'Z' ) ) % eZTime::secondsPerDay();

        return $localSeconds;
    }

    /*!
     \reimp
    */
    function &sortKeyType()
    {
        return 'int';
    }

    /*!
     Set class attribute value for template version
    */
    function initializeClassAttribute( &$classAttribute )
    {
        if ( $classAttribute->attribute( EZ_DATATYPESTRING_TIME_DEFAULT ) == null )
            $classAttribute->setAttribute( EZ_DATATYPESTRING_TIME_DEFAULT, 0 );
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
            $defaultType = $contentClassAttribute->attribute( EZ_DATATYPESTRING_TIME_DEFAULT );
            if ( $defaultType == 1 )
                $contentObjectAttribute->setAttribute( "data_int", mktime() );
        }
    }

    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $default = $base . "_eztime_default_" . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $default ) )
        {
            $defaultValue = $http->postVariable( $default );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_TIME_DEFAULT,  $defaultValue );
            return true;
        }
        return false;
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
        return "";
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_int' ) != 0;
    }

    /*!
     \reimp
    */
    function &serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $defaultValue = $classAttribute->attribute( EZ_DATATYPESTRING_TIME_DEFAULT );
        switch ( $defaultValue )
        {
            case EZ_DATATYPESTRING_TIME_DEFAULT_EMTPY:
            {
                $attributeParametersNode->appendChild( eZDOMDocument::createElementNode( 'default-value',
                                                                                         array( 'type' =>'empty' ) ) );
            } break;
            case EZ_DATATYPESTRING_TIME_DEFAULT_CURRENT_DATE:
            {
                $attributeParametersNode->appendChild( eZDOMDocument::createElementNode( 'default-value',
                                                                                         array( 'type' =>'current-date' ) ) );
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

eZDataType::register( EZ_DATATYPESTRING_TIME, "eztimetype" );

?>
