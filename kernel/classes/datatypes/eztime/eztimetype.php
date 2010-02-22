<?php
//
// Definition of eZTimeType class
//
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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
  \class eZTimeType eztimetype.php
  \ingroup eZDatatype
  \brief Stores a time value

*/

class eZTimeType extends eZDataType
{
    const DATA_TYPE_STRING = "eztime";
    const DEFAULT_FIELD = 'data_int1';
    const USE_SECONDS_FIELD = 'data_int2';
    const DEFAULT_EMTPY = 0;
    const DEFAULT_CURRENT_DATE = 1;

    function eZTimeType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezpI18n::tr( 'kernel/classes/datatypes', "Time", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Private method only for use inside this class
    */
    function validateTimeHTTPInput( $hours, $minute, $second, $contentObjectAttribute )
    {
        $state = eZDateTimeValidator::validateTime( $hours, $minute, $second );
        if ( $state == eZInputValidator::STATE_INVALID )
        {
            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                 'Invalid time.' ) );
            return eZInputValidator::STATE_INVALID;
        }
        return $state;
    }

    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $classAttribute = $contentObjectAttribute->contentClassAttribute();
        $useSeconds = ( $classAttribute->attribute( self::USE_SECONDS_FIELD ) == 1 );

        if ( $http->hasPostVariable( $base . '_time_hour_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_time_minute_' . $contentObjectAttribute->attribute( 'id' ) ) and
             ( !$useSeconds or $http->hasPostVariable( $base . '_time_second_' . $contentObjectAttribute->attribute( 'id' ) ) ) )
        {
            $hours  = $http->postVariable( $base . '_time_hour_' . $contentObjectAttribute->attribute( 'id' ) );
            $minute = $http->postVariable( $base . '_time_minute_' . $contentObjectAttribute->attribute( 'id' ) );
            $second = $useSeconds ? $http->postVariable( $base . '_time_second_' . $contentObjectAttribute->attribute( 'id' ) ) : 0;

            if ( $hours == '' or $minute == '' or ( $useSeconds and $second == '' ) )
            {
                if ( !( $hours == '' and
                        $minute == '' and
                        ( !$useSeconds or $second == '' ) ) or
                     ( !$classAttribute->attribute( 'is_information_collector' ) and
                       $contentObjectAttribute->validateIsRequired() ) )
                {
                    $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                         'Time input required.' ) );
                    return eZInputValidator::STATE_INVALID;
                }
                else
                    return eZInputValidator::STATE_ACCEPTED;
            }
            else
            {
                return $this->validateTimeHTTPInput( $hours, $minute, $second, $contentObjectAttribute );
            }
        }
        else if ( !$classAttribute->attribute( 'is_information_collector' ) and $contentObjectAttribute->validateIsRequired() )
        {
            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes', 'Time input required.' ) );
            return eZInputValidator::STATE_INVALID;
        }
        else
            return eZInputValidator::STATE_ACCEPTED;
    }

    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $classAttribute = $contentObjectAttribute->contentClassAttribute();
        $useSeconds = ( $classAttribute->attribute( self::USE_SECONDS_FIELD ) == 1 );

        if ( $http->hasPostVariable( $base . '_time_hour_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_time_minute_' . $contentObjectAttribute->attribute( 'id' ) ) and
             ( !$useSeconds or $http->hasPostVariable( $base . '_time_second_' . $contentObjectAttribute->attribute( 'id' ) ) ) )
        {
            $hours  = $http->postVariable( $base . '_time_hour_' . $contentObjectAttribute->attribute( 'id' ) );
            $minute = $http->postVariable( $base . '_time_minute_' . $contentObjectAttribute->attribute( 'id' ) );
            $second = $useSeconds ? $http->postVariable( $base . '_time_second_' . $contentObjectAttribute->attribute( 'id' ) ) : 0;

            if ( $hours != '' or $minute != '' or ( $useSeconds and $second != '' ) )
            {
                $time = new eZTime();
                $time->setHMS( $hours, $minute, $second );
                $contentObjectAttribute->setAttribute( 'data_int', $time->timeOfDay() );
            }
            else
            {
                $contentObjectAttribute->setAttribute( 'data_int', null );
            }
            return true;
        }
        return false;
    }

    function validateCollectionAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $classAttribute = $contentObjectAttribute->contentClassAttribute();
        $useSeconds = ( $classAttribute->attribute( self::USE_SECONDS_FIELD ) == 1 );

        if ( $http->hasPostVariable( $base . '_time_hour_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_time_minute_' . $contentObjectAttribute->attribute( 'id' ) ) and
             ( !$useSeconds or $http->hasPostVariable( $base . '_time_second_' . $contentObjectAttribute->attribute( 'id' ) ) ) )
        {
            $hours  = $http->postVariable( $base . '_time_hour_' . $contentObjectAttribute->attribute( 'id' ) );
            $minute = $http->postVariable( $base . '_time_minute_' . $contentObjectAttribute->attribute( 'id' ) );
            $second = $useSeconds ? $http->postVariable( $base . '_time_second_' . $contentObjectAttribute->attribute( 'id' ) ) : 0;

            if ( $hours == '' or $minute == '' or ( $useSeconds and $second == '' ) )
            {
                if ( !( $hours == '' and $minute == '' and ( !$useSeconds or $second == '' ) ) or
                     $contentObjectAttribute->validateIsRequired() )
                {
                    $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                         'Time input required.' ) );
                    return eZInputValidator::STATE_INVALID;
                }
                else
                    return eZInputValidator::STATE_ACCEPTED;
            }
            else
            {
                return $this->validateTimeHTTPInput( $hours, $minute, $second, $contentObjectAttribute );
            }
        }
        else
            return eZInputValidator::STATE_INVALID;
    }

   /*!
    Fetches the http post variables for collected information
   */
    function fetchCollectionAttributeHTTPInput( $collection, $collectionAttribute, $http, $base, $contentObjectAttribute )
    {
        $classAttribute = $contentObjectAttribute->contentClassAttribute();
        $useSeconds = ( $classAttribute->attribute( self::USE_SECONDS_FIELD ) == 1 );

        if ( $http->hasPostVariable( $base . '_time_hour_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_time_minute_' . $contentObjectAttribute->attribute( 'id' ) ) and
             ( !$useSeconds or $http->hasPostVariable( $base . '_time_second_' . $contentObjectAttribute->attribute( 'id' ) ) ) )
        {
            $hours  = $http->postVariable( $base . '_time_hour_' . $contentObjectAttribute->attribute( 'id' ) );
            $minute = $http->postVariable( $base . '_time_minute_' . $contentObjectAttribute->attribute( 'id' ) );
            $second = $useSeconds ? $http->postVariable( $base . '_time_second_' . $contentObjectAttribute->attribute( 'id' ) ) : 0;

            if ( $hours != '' or $minute != '' or ( $useSeconds and $second != '' ) )
            {
                $time = new eZTime();
                $time->setHMS( $hours, $minute, $second );
                $collectionAttribute->setAttribute( 'data_int', $time->timeOfDay() );
            }
            else
                $collectionAttribute->setAttribute( 'data_int', null );
            return true;
        }
        return false;
    }

    /*!
     Returns the content.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        $stamp = $contentObjectAttribute->attribute( 'data_int' );

        if ( $stamp !== null )
        {
            $time = new eZTime( $stamp );

        }
        else
            $time = array( 'timestamp' => '',
                           'time_of_day' => '',
                           'hour' => '',
                           'minute' => '',
                           'second' => '',
                           'is_valid' => false );
        return $time;
    }

    function sortKey( $contentObjectAttribute )
    {
        $timestamp = $contentObjectAttribute->attribute( 'data_int' );
        if ( $timestamp !== null )
        {
            $time = new eZTime( $timestamp );
            return $time->timeOfDay();
        }
        else
            return 0;
    }

    function sortKeyType()
    {
        return 'int';
    }

    /*!
     \return string representation of an contentobjectattribute data for simplified export

    */
    function toString( $contentObjectAttribute )
    {
        $time = $contentObjectAttribute->attribute( 'content' );
        if ( is_object( $time ) )
        {
            return $time->attribute( 'hour' ) . ':' . $time->attribute( 'minute' ) . ':' . $time->attribute( 'second' );
        }
        else
            return '';
    }

    function fromString( $contentObjectAttribute, $string )
    {
        if ( $string != '' )
        {
            list( $hour, $minute, $second ) = explode( ':', $string );
            if ( $hour == '' || $minute == '' )
                return false;
            if ( $second == '' )
            {
               $second = 0;
           }
            $time = new eZTime();
            $time->setHMS( $hour, $minute, $second );
            $contentObjectAttribute->setAttribute( 'data_int', $time->timeOfDay() );
        }

        return true;
    }

    function isInformationCollector()
    {
        return true;
    }

    /*!
     Set class attribute value for template version
    */
    function initializeClassAttribute( $classAttribute )
    {
        if ( $classAttribute->attribute( self::DEFAULT_FIELD ) == null )
            $classAttribute->setAttribute( self::DEFAULT_FIELD, 0 );
        $classAttribute->store();
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $dataInt = $originalContentObjectAttribute->attribute( 'data_int' );
            $contentObjectAttribute->setAttribute( 'data_int', $dataInt );
        }
        else
        {
            $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
            $defaultType = $contentClassAttribute->attribute( self::DEFAULT_FIELD );

            if ( $defaultType == 1 )
            {
                $time = new eZTime();
                $contentObjectAttribute->setAttribute( 'data_int', $time->timeOfDay() );
            }
        }
    }

    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $default = $base . "_eztime_default_" . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $default ) )
        {
            $defaultValue = $http->postVariable( $default );
            $classAttribute->setAttribute( self::DEFAULT_FIELD,  $defaultValue );

            $useSeconds = $base . "_eztime_use_seconds_" . $classAttribute->attribute( 'id' );
            $classAttribute->setAttribute( self::USE_SECONDS_FIELD, $http->hasPostVariable( $useSeconds ) ? 1 : 0 );

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
    function title( $contentObjectAttribute, $name = null )
    {
        $timestamp = $contentObjectAttribute->attribute( 'data_int' );
        $locale = eZLocale::instance();

        if ( $timestamp !== null )
        {
            $time = new eZTime( $timestamp );
            return $locale->formatTime( $time->timeStamp() );
        }
        return '';
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_int' ) !== null;
    }

    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $dom = $attributeParametersNode->ownerDocument;

        $defaultValue = $classAttribute->attribute( self::DEFAULT_FIELD );
        $defaultValueNode = $dom->createElement( 'default-value' );
        switch ( $defaultValue )
        {
            case self::DEFAULT_EMTPY:
                $defaultValueNode->setAttribute( 'type', 'empty' );
                break;

            case self::DEFAULT_CURRENT_DATE:
                $defaultValueNode->setAttribute( 'type', 'current-date' );
                break;
        }
        $attributeParametersNode->appendChild( $defaultValueNode );

        $useSeconds = $classAttribute->attribute( self::USE_SECONDS_FIELD );
        $useSecondsNode = $dom->createElement( 'use-seconds' );
        $useSecondsNode->appendChild( $dom->createTextNode( $useSeconds ) );
        $attributeParametersNode->appendChild( $useSecondsNode );
    }

    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $defaultNode = $attributeParametersNode->getElementsByTagName( 'default-value' )->item( 0 );
        $defaultValue = strtolower( $defaultNode->getAttribute( 'type' ) );
        switch ( $defaultValue )
        {
            case 'empty':
            {
                $classAttribute->setAttribute( self::DEFAULT_FIELD, self::DEFAULT_EMTPY );
            } break;
            case 'current-date':
            {
                $classAttribute->setAttribute( self::DEFAULT_FIELD, self::DEFAULT_CURRENT_DATE );
            } break;
        }

        $useSecondsNode = $attributeParametersNode->getElementsByTagName( 'use-seconds' )->item( 0 );
        if ( $useSecondsNode && $useSecondsNode->textContent === '1'  )
        {
            $classAttribute->setAttribute( self::USE_SECONDS_FIELD, 1 );
        }
    }

    /*!
     \param package
     \param content attribute

     \return a DOM representation of the content object attribute
    */
    function serializeContentObjectAttribute( $package, $objectAttribute )
    {
        $node = $this->createContentObjectAttributeDOMNode( $objectAttribute );

        $stamp = $objectAttribute->attribute( 'data_int' );

        if ( $stamp !== null )
        {
            $dom = $node->ownerDocument;
            $dateNode = $dom->createElement( 'time' );
            $dateNode->appendChild( $dom->createTextNode( eZDateUtils::rfc1123Date( $stamp ) ) );
            $node->appendChild( $dateNode );
        }
        return $node;
    }

    /*!
     \param package
     \param contentobject attribute object
     \param ezdomnode object
    */
    function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode )
    {
        $timeNode = $attributeNode->getElementsByTagName( 'time' )->item( 0 );
        if ( is_object( $timeNode ) )
        {
            $timestamp = eZDateUtils::textToDate( $timeNode->textContent );
            $timeOfDay = null;
            if ( $timestamp >= 0 )
            {
                $time = new eZTime( $timestamp );
                $timeOfDay = $time->timeOfDay();
            }
            $objectAttribute->setAttribute( 'data_int', $timeOfDay );
        }
    }

    function supportsBatchInitializeObjectAttribute()
    {
        return true;
    }

    function batchInitializeObjectAttributeData( $classAttribute )
    {
        $defaultType = $classAttribute->attribute( self::DEFAULT_FIELD );

        if ( $defaultType == 1 )
        {
            $time = new eZTime();
            $default = $time->timeOfDay();
            return array( 'data_int' => $default, 'sort_key_int' => $default );
        }

        return array();
    }
}

eZDataType::register( eZTimeType::DATA_TYPE_STRING, "eZTimeType" );

?>
