<?php
//
// Definition of eZDateTimeType class
//
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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
  \class eZDateTimeType ezdatetimetype.php
  \ingroup eZDatatype
  \brief Stores a date and time value

*/

class eZDateTimeType extends eZDataType
{
    const DATA_TYPE_STRING = 'ezdatetime';

    const DEFAULT_FIELD = 'data_int1';

    const USE_SECONDS_FIELD = 'data_int2';

    const ADJUSTMENT_FIELD = 'data_text5';

    const DEFAULT_EMTPY = 0;

    const DEFAULT_CURRENT_DATE = 1;

    const DEFAULT_ADJUSTMENT = 2;

    function eZDateTimeType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezi18n( 'kernel/classes/datatypes', "Date and time", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Private method only for use inside this class
    */
    function validateDateTimeHTTPInput( $day, $month, $year, $hour, $minute, $second, $contentObjectAttribute )
    {
        $state = eZDateTimeValidator::validateDate( $day, $month, $year );
        if ( $state == eZInputValidator::STATE_INVALID )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Date is not valid.' ) );
            return eZInputValidator::STATE_INVALID;
        }

        $state = eZDateTimeValidator::validateTime( $hour, $minute, $second );

        if ( $state == eZInputValidator::STATE_INVALID )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Time is not valid.' ) );
            return eZInputValidator::STATE_INVALID;
        }
        return $state;
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $classAttribute = $contentObjectAttribute->contentClassAttribute();
        $useSeconds = ( $classAttribute->attribute( self::USE_SECONDS_FIELD ) == 1 );

        if ( $http->hasPostVariable( $base . '_datetime_year_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_datetime_month_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_datetime_day_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_datetime_hour_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_datetime_minute_' . $contentObjectAttribute->attribute( 'id' ) ) and
             ( !$useSeconds or $http->hasPostVariable( $base . '_datetime_second_' . $contentObjectAttribute->attribute( 'id' ) ) ) )
        {
            $year   = $http->postVariable( $base . '_datetime_year_' . $contentObjectAttribute->attribute( 'id' ) );
            $month  = $http->postVariable( $base . '_datetime_month_' . $contentObjectAttribute->attribute( 'id' ) );
            $day    = $http->postVariable( $base . '_datetime_day_' . $contentObjectAttribute->attribute( 'id' ) );
            $hour   = $http->postVariable( $base . '_datetime_hour_' . $contentObjectAttribute->attribute( 'id' ) );
            $minute = $http->postVariable( $base . '_datetime_minute_' . $contentObjectAttribute->attribute( 'id' ) );
            $second = $useSeconds ? $http->postVariable( $base . '_datetime_second_' . $contentObjectAttribute->attribute( 'id' ) ) : 0;

            if ( $year == '' or
                 $month == '' or
                 $day == '' or
                 $hour == '' or
                 $minute == '' or
                 ( $useSeconds and $second == '' ) )
            {
                if ( !( $year == '' and
                        $month == '' and
                        $day == '' and
                        $hour == '' and
                        $minute == '' and
                        ( !$useSeconds or $second == '' ) ) or
                     ( !$classAttribute->attribute( 'is_information_collector' ) and
                       $contentObjectAttribute->validateIsRequired() ) )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'Missing datetime input.' ) );
                    return eZInputValidator::STATE_INVALID;
                }
                else
                    return eZInputValidator::STATE_ACCEPTED;
            }
            else
            {
                return $this->validateDateTimeHTTPInput( $day, $month, $year, $hour, $minute, $second, $contentObjectAttribute );
            }
        }
        else if ( !$classAttribute->attribute( 'is_information_collector' ) and $contentObjectAttribute->validateIsRequired() )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', 'Missing datetime input.' ) );
            return eZInputValidator::STATE_INVALID;
        }
        else
            return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
        $useSeconds = ( $contentClassAttribute->attribute( self::USE_SECONDS_FIELD ) == 1 );

        if ( $http->hasPostVariable( $base . '_datetime_year_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_datetime_month_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_datetime_day_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_datetime_hour_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_datetime_minute_' . $contentObjectAttribute->attribute( 'id' ) ) and
             ( !$useSeconds or $http->hasPostVariable( $base . '_datetime_second_' . $contentObjectAttribute->attribute( 'id' ) ) ) )
        {
            $year   = $http->postVariable( $base . '_datetime_year_' . $contentObjectAttribute->attribute( 'id' ) );
            $month  = $http->postVariable( $base . '_datetime_month_' . $contentObjectAttribute->attribute( 'id' ) );
            $day    = $http->postVariable( $base . '_datetime_day_' . $contentObjectAttribute->attribute( 'id' ) );
            $hour   = $http->postVariable( $base . '_datetime_hour_' . $contentObjectAttribute->attribute( 'id' ) );
            $minute = $http->postVariable( $base . '_datetime_minute_' . $contentObjectAttribute->attribute( 'id' ) );
            $second = $useSeconds ? $http->postVariable( $base . '_datetime_second_' . $contentObjectAttribute->attribute( 'id' ) ) : 0;

            $dateTime = new eZDateTime();

            if ( ( $year == '' and $month == '' and $day == '' and
                   $hour == '' and $minute == '' and ( !$useSeconds or $second == '' ) ) or
                 !checkdate( $month, $day, $year ) or $year < 1970 )
            {
                    $dateTime->setTimeStamp( 0 );
            }
            else
            {
                $dateTime->setMDYHMS( $month, $day, $year, $hour, $minute, $second );
            }

            $contentObjectAttribute->setAttribute( 'data_int', $dateTime->timeStamp() );
            return true;
        }
        return false;
    }

    function validateCollectionAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
        $useSeconds = ( $contentClassAttribute->attribute( self::USE_SECONDS_FIELD ) == 1 );

        if ( $http->hasPostVariable( $base . '_datetime_year_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_datetime_month_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_datetime_day_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_datetime_hour_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_datetime_minute_' . $contentObjectAttribute->attribute( 'id' ) ) and
             ( !$useSeconds or $http->hasPostVariable( $base . '_datetime_second_' . $contentObjectAttribute->attribute( 'id' ) ) ) )

        {
            $year   = $http->postVariable( $base . '_datetime_year_' . $contentObjectAttribute->attribute( 'id' ) );
            $month  = $http->postVariable( $base . '_datetime_month_' . $contentObjectAttribute->attribute( 'id' ) );
            $day    = $http->postVariable( $base . '_datetime_day_' . $contentObjectAttribute->attribute( 'id' ) );
            $hour   = $http->postVariable( $base . '_datetime_hour_' . $contentObjectAttribute->attribute( 'id' ) );
            $minute = $http->postVariable( $base . '_datetime_minute_' . $contentObjectAttribute->attribute( 'id' ) );
            $second = $useSeconds ? $http->postVariable( $base . '_datetime_second_' . $contentObjectAttribute->attribute( 'id' ) ) : 0;

            if ( $year == '' or
                 $month == '' or
                 $day == '' or
                 $hour == '' or
                 $minute == '' or
                 ( $useSeconds and $second == '' ) )
            {
                if ( !( $year == '' and
                        $month == '' and
                        $day == '' and
                        $hour == '' and
                        $minute == '' and
                        ( !$useSeconds or $second == '' ) ) or
                     $contentObjectAttribute->validateIsRequired() )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'Missing datetime input.' ) );
                    return eZInputValidator::STATE_INVALID;
                }
                else
                    return eZInputValidator::STATE_ACCEPTED;
            }
            else
            {
                return $this->validateDateTimeHTTPInput( $day, $month, $year, $hour, $minute, $second, $contentObjectAttribute );
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
        $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
        $useSeconds = ( $contentClassAttribute->attribute( self::USE_SECONDS_FIELD ) == 1 );

        if ( $http->hasPostVariable( $base . '_datetime_year_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_datetime_month_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_datetime_day_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_datetime_hour_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_datetime_minute_' . $contentObjectAttribute->attribute( 'id' ) ) and
             ( !$useSeconds or $http->hasPostVariable( $base . '_datetime_second_' . $contentObjectAttribute->attribute( 'id' ) ) ) )
        {
            $year   = $http->postVariable( $base . '_datetime_year_' . $contentObjectAttribute->attribute( 'id' ) );
            $month  = $http->postVariable( $base . '_datetime_month_' . $contentObjectAttribute->attribute( 'id' ) );
            $day    = $http->postVariable( $base . '_datetime_day_' . $contentObjectAttribute->attribute( 'id' ) );
            $hour   = $http->postVariable( $base . '_datetime_hour_' . $contentObjectAttribute->attribute( 'id' ) );
            $minute = $http->postVariable( $base . '_datetime_minute_' . $contentObjectAttribute->attribute( 'id' ) );
            $second = $useSeconds ? $http->postVariable( $base . '_datetime_second_' . $contentObjectAttribute->attribute( 'id' ) ) : 0;

            $dateTime = new eZDateTime();
            $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
            if ( ( $year == '' and $month == ''and $day == '' and
                   $hour == '' and $minute == '' and ( !$useSeconds or $second == '' ) ) or
                 !checkdate( $month, $day, $year ) or $year < 1970 )
            {
                    $dateTime->setTimeStamp( 0 );
            }
            else
            {
                $dateTime->setMDYHMS( $month, $day, $year, $hour, $minute, $second );
            }

            $collectionAttribute->setAttribute( 'data_int', $dateTime->timeStamp() );
            return true;
        }
        return false;
    }

    /*!
     Returns the content.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        $dateTime = new eZDateTime();
        $stamp = $contentObjectAttribute->attribute( 'data_int' );
        $dateTime->setTimeStamp( $stamp );
        return $dateTime;
    }

    function isIndexable()
    {
        return true;
    }

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

    function fromString( $contentObjectAttribute, $string )
    {
        return $contentObjectAttribute->setAttribute( 'data_int', $string );
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

    function parseXML( $xmlText )
    {
        $dom = new DOMDocument;
        $success = $dom->loadXML( $xmlText );
        return $dom;
    }

    function classAttributeContent( $classAttribute )
    {
        $xmlText = $classAttribute->attribute( 'data_text5' );
        if ( trim( $xmlText ) == '' )
        {
            $classAttrContent = eZDateTimeType::defaultClassAttributeContent();
            return $classAttrContent;
        }
        $doc = eZDateTimeType::parseXML( $xmlText );
        $root = $doc->documentElement;
        $type = $root->getElementsByTagName( 'year' )->item( 0 );
        if ( $type )
        {
            $content['year'] = $type->getAttribute( 'value' );
        }
        $type = $root->getElementsByTagName( 'month' )->item( 0 );
        if ( $type )
        {
            $content['month'] = $type->getAttribute( 'value' );
        }
        $type = $root->getElementsByTagName( 'day' )->item( 0 );
        if ( $type )
        {
            $content['day'] = $type->getAttribute( 'value' );
        }
        $type = $root->getElementsByTagName( 'hour' )->item( 0 );
        if ( $type )
        {
            $content['hour'] = $type->getAttribute( 'value' );
        }
        $type = $root->getElementsByTagName( 'minute' )->item( 0 );
        if ( $type )
        {
            $content['minute'] = $type->getAttribute( 'value' );
        }
        $type = $root->getElementsByTagName( 'second' )->item( 0 );
        if ( $type )
        {
            $content['second'] = $type->getAttribute( 'value' );
        }
        return $content;
    }

    function defaultClassAttributeContent()
    {
        return array( 'year' => '',
                      'month' => '',
                      'day' => '',
                      'hour' => '',
                      'minute' => '',
                      'second' => '' );
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $dataInt = $originalContentObjectAttribute->attribute( "data_int" );
            $contentObjectAttribute->setAttribute( "data_int", $dataInt );
        }
        else
        {
            $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
            $defaultType = $contentClassAttribute->attribute( self::DEFAULT_FIELD );
            if ( $defaultType == self::DEFAULT_CURRENT_DATE )
            {
                $contentObjectAttribute->setAttribute( "data_int", time() );
            }
            else if ( $defaultType == self::DEFAULT_ADJUSTMENT )
            {
                $adjustments = $this->classAttributeContent( $contentClassAttribute );
                $value = new eZDateTime();
                $secondAdjustment = $contentClassAttribute->attribute( self::USE_SECONDS_FIELD ) == 1 ? $adjustments['second'] : 0;
                $value->adjustDateTime( $adjustments['hour'], $adjustments['minute'], $secondAdjustment, $adjustments['month'], $adjustments['day'], $adjustments['year'] );
                $contentObjectAttribute->setAttribute( "data_int", $value->timeStamp() );
            }
            else
                $contentObjectAttribute->setAttribute( "data_int", 0 );
        }
    }

    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $default = $base . "_ezdatetime_default_" . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $default ) )
        {
            $defaultValue = $http->postVariable( $default );
            $classAttribute->setAttribute( self::DEFAULT_FIELD,  $defaultValue );
            if ( $defaultValue == self::DEFAULT_ADJUSTMENT )
            {
                $doc = new DOMDocument( '1.0', 'utf-8' );
                $root = $doc->createElement( 'adjustment' );
                $contentList = eZDateTimeType::contentObjectArrayXMLMap();
                foreach ( $contentList as $key => $value )
                {
                    $postValue = $http->postVariable( $base . '_ezdatetime_' . $value . '_' . $classAttribute->attribute( 'id' ) );
                    unset( $elementType );
                    $elementType = $doc->createElement( $key );
                    $elementType->setAttribute( 'value', $postValue );
                    $root->appendChild( $elementType );
                }
                $doc->appendChild( $root );
                $docText = $doc->saveXML();
                $classAttribute->setAttribute( self::ADJUSTMENT_FIELD , $docText );
            }

            $useSeconds = $base . "_ezdatetime_use_seconds_" . $classAttribute->attribute( 'id' );
            $classAttribute->setAttribute( self::USE_SECONDS_FIELD, $http->hasPostVariable( $useSeconds ) ? 1 : 0 );
        }

        return true;
    }

    function contentObjectArrayXMLMap()
    {
        return array( 'year' => 'year',
                      'month' => 'month',
                      'day' => 'day',
                      'hour' => 'hour',
                      'minute' => 'minute',
                      'second' => 'second' );
    }


    /*!
     Returns the date.
    */
    function title( $contentObjectAttribute, $name = null )
    {
        $locale = eZLocale::instance();
        $retVal = $contentObjectAttribute->attribute( "data_int" ) == 0 ? '' : $locale->formatDateTime( $contentObjectAttribute->attribute( "data_int" ) );
        return $retVal;
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_int" ) != 0;
    }

    function sortKey( $contentObjectAttribute )
    {
        return (int)$contentObjectAttribute->attribute( 'data_int' );
    }

    function sortKeyType()
    {
        return 'int';
    }

    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $dom = $attributeParametersNode->ownerDocument;
        $defaultValue = $classAttribute->attribute( self::DEFAULT_FIELD );
        $defaultValueNode = $dom->createElement( 'default-value' );

        switch ( $defaultValue )
        {
            case self::DEFAULT_CURRENT_DATE:
            {
                $defaultValueNode->setAttribute( 'type', 'current-date' );
            } break;
            case self::DEFAULT_ADJUSTMENT:
            {
                $defaultValueNode->setAttribute( 'type', 'adjustment' );

                $adjustDOMValue = new DOMDocument( '1.0', 'utf-8' );
                $adjustValue = $classAttribute->attribute( self::ADJUSTMENT_FIELD );
                $success = $adjustDOMValue->loadXML( $adjustValue );

                if ( $success )
                {
                    $adjustmentNode = $adjustDOMValue->getElementsByTagName( 'adjustment' )->item( 0 );

                    if ( $adjustmentNode )
                    {
                        $importedAdjustmentNode = $dom->importNode( $adjustmentNode, true );
                        $defaultValueNode->appendChild( $importedAdjustmentNode );
                    }
                }
            } break;
            case self::DEFAULT_EMTPY:
            {
                $defaultValueNode->setAttribute( 'type', 'empty' );
            } break;
            default:
            {
                eZDebug::writeError( 'Unknown type of DateTime default value. Empty type used instead.',
                                    'eZDateTimeType::serializeContentClassAttribute()' );
                $defaultValueNode->setAttribute( 'type', 'empty' );
            } break;
        }
        $attributeParametersNode->appendChild( $defaultValueNode );

        $useSeconds = $classAttribute->attribute( self::USE_SECONDS_FIELD );
        $useSecondsNode = $dom->createElement( 'use-seconds' );
        $useSecondsNode->appendChild( $dom->createTextNode( $useSeconds ) );
        $attributeParametersNode->appendChild( $useSecondsNode );
    }

    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $defaultValue = '';
        $defaultNode = $attributeParametersNode->getElementsByTagName( 'default-value' )->item( 0 );
        if ( $defaultNode )
        {
            $defaultValue = strtolower( $defaultNode->getAttribute( 'type' ) );
        }
        switch ( $defaultValue )
        {
            case 'current-date':
            {
                $classAttribute->setAttribute( self::DEFAULT_FIELD, self::DEFAULT_CURRENT_DATE );
            } break;
            case 'adjustment':
            {
                $adjustmentValue = '';
                $adjustmentNode = $defaultNode->getElementsByTagName( 'adjustment' )->item( 0 );
                if ( $adjustmentNode )
                {
                    $adjustmentDOMValue = new DOMDocument( '1.0', 'utf-8' );
                    $importedAdjustmentNode = $adjustmentDOMValue->importNode( $adjustmentNode, true );
                    $adjustmentDOMValue->appendChild( $importedAdjustmentNode );
                    $adjustmentValue = $adjustmentDOMValue->saveXML();
                }

                $classAttribute->setAttribute( self::DEFAULT_FIELD, self::DEFAULT_ADJUSTMENT );
                $classAttribute->setAttribute( self::ADJUSTMENT_FIELD, $adjustmentValue );
            } break;
            case 'empty':
            {
                $classAttribute->setAttribute( self::DEFAULT_FIELD, self::DEFAULT_EMTPY );
            } break;
            default:
            {
                eZDebug::writeError( 'Type of DateTime default value is not set. Empty type used as default.',
                                    'eZDateTimeType::unserializeContentClassAttribute()' );
                $classAttribute->setAttribute( self::DEFAULT_FIELD, self::DEFAULT_EMTPY );
            } break;
        }

        $useSecondsNode = $attributeParametersNode->getElementsByTagName( 'use-seconds' )->item( 0 );
        if ( $useSecondsNode && $useSecondsNode->textContent === '1' )
        {
            $classAttribute->setAttribute( self::USE_SECONDS_FIELD, 1 );
        }
    }

    /*!
     \return a DOM representation of the content object attribute
    */
    function serializeContentObjectAttribute( $package, $objectAttribute )
    {
        $node = $this->createContentObjectAttributeDOMNode( $objectAttribute );

        $stamp = $objectAttribute->attribute( 'data_int' );

        if ( $stamp !== null )
        {
            $dom = $node->ownerDocument;
            $dateTimeNode = $dom->createElement( 'date_time' );
            $dateTimeNode->appendChild( $dom->createTextNode( eZDateUtils::rfc1123Date( $stamp ) ) );
            $node->appendChild( $dateTimeNode );
        }
        return $node;
    }

    function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode )
    {
        $dateTimeNode = $attributeNode->getElementsByTagName( 'date_time' )->item( 0 );
        if ( is_object( $dateTimeNode ) )
        {
            $timestamp = eZDateUtils::textToDate( $dateTimeNode->textContent );
            $objectAttribute->setAttribute( 'data_int', $timestamp );
        }
    }

    function supportsBatchInitializeObjectAttribute()
    {
        return true;
    }

    function batchInitializeObjectAttributeData( $classAttribute )
    {
        $defaultType = $classAttribute->attribute( self::DEFAULT_FIELD );

        switch( $defaultType )
        {
            case self::DEFAULT_CURRENT_DATE:
            {
                $default = time();
            } break;

            case self::DEFAULT_ADJUSTMENT:
            {
                $adjustments = $this->classAttributeContent( $classAttribute );
                $value = new eZDateTime();
                $secondAdjustment = $classAttribute->attribute( self::USE_SECONDS_FIELD ) == 1 ? $adjustments['second'] : 0;
                $value->adjustDateTime( $adjustments['hour'], $adjustments['minute'], $secondAdjustment, $adjustments['month'], $adjustments['day'], $adjustments['year'] );

                $default = $value->timeStamp();
            } break;

            default:
            {
                $default = 0;
            }
        }

        return array( 'data_int' => $default, 'sort_key_int' => $default );
    }
}

eZDataType::register( eZDateTimeType::DATA_TYPE_STRING, "eZDateTimeType" );

?>
