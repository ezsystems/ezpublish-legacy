<?php
//
// Definition of eZCountryType class
//
// Created on: <20-Feb-2006 11:11:19 vs>
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
  \class eZCountryType ezcountrytype.php
  \ingroup eZDatatype
  \brief A content datatype that contains country.

  The list of countries is fetched from contenet.ini.
  Country is stored as text string.
*/

include_once( 'kernel/classes/ezdatatype.php' );
include_once( 'lib/ezutils/classes/ezintegervalidator.php' );
include_once( 'kernel/common/i18n.php' );

define( 'EZ_DATATYPESTRING_COUNTRY', 'ezcountry' );
define( 'EZ_DATATYPESTRING_COUNTRY_DEFAULT_LIST_FIELD', 'data_text5' );
define( 'EZ_DATATYPESTRING_COUNTRY_MULTIPLE_CHOICE_FIELD', 'data_int1' );

class eZCountryType extends eZDataType
{
    function eZCountryType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_COUNTRY, ezi18n( 'kernel/classes/datatypes', 'Country', 'Datatype name' ),
                           array( 'serialize_supported' => true,
                                  'object_serialize_map' => array( 'data_text' => 'country' ) ) );
    }

    /*
      Returns all country list with using translation.ts
    */
    function fetchCountriesForCurrentLanguage()
    {
        if ( isset( $GLOBALS['eZCountryList'] ) )
        {
            return $GLOBALS['eZCountryList'];
        }

        $countryList = array();
        $countryINI =& eZINI::instance( 'content.ini' );
        foreach ( $countryINI->variable( 'CountrySettings', 'Countries' ) as $key => $value )
        {
            $countryList[$key] = ezi18n( 'kernel/content/datatypes/ezcountry', $value );
        }
        natcasesort($countryList);
        $GLOBALS['eZCountryList'] = $countryList;
        return $countryList;
    }

    /*
      Returns Country by name
    */
    function fetchCountryByName( $name )
    {
        if ( !$name )
            return false ;

        $countries = eZCountryType::fetchCountriesForCurrentLanguage();
        $result = false;
        foreach ( array_keys( $countries ) as $countryKey )
        {
            if ( $countries[$countryKey] == $name )
            {
                $result = array( $countryKey => $name );
                break;
            }
        }

        return $result;
    }

    /*
      Returns country by code
    */
    function fetchCountryByCode( $code )
    {
        if ( !$code )
            return false;

        $code = strtoupper( $code );
        $countries = eZCountryType::fetchCountriesForCurrentLanguage();
        $country = isset( $countries[$code] ) ? $countries[$code] : null;
        if ( !$country )
            return false;

        $result = array( $code => $name );

        return $result;
    }

    /*!
     \reimp
    */
    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $classAttributeID = $classAttribute->attribute( 'id' );
        $content = $classAttribute->content();

        if ( $http->hasPostVariable( $base . '_ezcountry_multiple_choice_value_' . $classAttribute->attribute( 'id' ) . '_exists' ) )
        {
             $content['multiple_choice'] = $http->hasPostVariable( $base . "_ezcountry_ismultiple_value_" . $classAttributeID ) ? 1 : 0;
        }

        if ( $http->hasPostVariable( $base . '_ezcountry_default_selection_value_' . $classAttribute->attribute( 'id' ) . '_exists' ) )
        {
            if ( $http->hasPostVariable( $base . "_ezcountry_default_country_list_". $classAttributeID ) )
            {
                $defaultValues = $http->postVariable( $base . "_ezcountry_default_country_list_". $classAttributeID );
                $defaultList = array();
                $countries = eZCountryType::fetchCountriesForCurrentLanguage();
                foreach ( $defaultValues as $defaultCountry )
                {
                    if ( trim( $defaultCountry ) != '' )
                        $defaultList[$defaultCountry] = isset( $countries[$defaultCountry] ) ? $countries[$defaultCountry] : '';
                }
                $content['default_countries'] = $defaultList;
            }
            else
            {
                $content['default_countries'] = array();
            }
        }
        $classAttribute->setContent( $content );
        $classAttribute->store();
        return true;
    }

    /*!
     \reimp
    */
    function preStoreClassAttribute( &$classAttribute, $version )
    {
        $content = $classAttribute->content();
        return eZCountryType::storeClassAttributeContent( $classAttribute, $content );
    }

    function storeClassAttributeContent( &$classAttribute, $content )
    {
        if ( is_array( $content ) )
        {
            $multipleChoice = $content['multiple_choice'];
            $defaultCountryList = $content['default_countries'];
            $defaultCountry = implode( ',', array_keys( $defaultCountryList ) );

            $classAttribute->setAttribute( EZ_DATATYPESTRING_COUNTRY_DEFAULT_LIST_FIELD, $defaultCountry );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_COUNTRY_MULTIPLE_CHOICE_FIELD, $multipleChoice );
        }
        return false;
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( &$contentObjectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $dataText = $originalContentObjectAttribute->content();
            $contentObjectAttribute->setContent( $dataText );
        }
        else
        {
            $default = array( 'value' => array() );
            $contentObjectAttribute->setContent( $default );
        }
    }

    /*!
     \reimp
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( !$contentObjectAttribute->validateIsRequired() )
            return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;

        if ( $http->hasPostVariable( $base . '_country_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $data = $http->postVariable( $base . '_country_' . $contentObjectAttribute->attribute( 'id' ) );

            if ( count( $data ) > 0 and $data[0] != '' )
                return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
        }

        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                             'Input required.' ) );
        return EZ_INPUT_VALIDATOR_STATE_INVALID;
    }

    /*!
     \reimp
    */
    function validateCollectionAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( !$contentObjectAttribute->validateIsRequired() )
            return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;

        if ( $http->hasPostVariable( $base . '_country_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $data = $http->postVariable( $base . '_country_' . $contentObjectAttribute->attribute( 'id' ) );

            if ( count( $data ) > 0 and $data[0] != '' )
                return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
        }

        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                             'Input required.' ) );
        return EZ_INPUT_VALIDATOR_STATE_INVALID;
    }

    /*!
     Fetches the http post var and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_country_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $data = $http->postVariable( $base . '_country_' . $contentObjectAttribute->attribute( 'id' ) );
            $defaultList = array();
            $countries = eZCountryType::fetchCountriesForCurrentLanguage();
            if ( is_array( $data ) )
            {
                foreach ( $data as $defaultCountry )
                {
                    if ( trim( $defaultCountry ) != '' )
                        $defaultList[$defaultCountry] = isset( $countries[$defaultCountry] ) ? $countries[$defaultCountry] : '';
                }
            }
            else
            {
                foreach ( array_keys( $countries ) as $key )
                {
                    $country = $countries[$key];
                    if ( $country == $data )
                    {
                        $defaultList[$key] = $country;
                    }
                }
            }
            $content = array( 'value' => $defaultList );

            $contentObjectAttribute->setContent( $content );
        }
        else
        {
            $content = array( 'value' => array() );
            $contentObjectAttribute->setContent( $content );
        }
        return true;
    }

    /*!
     Fetches the http post variables for collected information
    */
    function fetchCollectionAttributeHTTPInput( &$collection, &$collectionAttribute, &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_country_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $dataText = $http->postVariable( $base . "_country_" . $contentObjectAttribute->attribute( "id" ) );

            $value = implode( ',', $dataText );
            $collectionAttribute->setAttribute( 'data_text', $value );
            return true;
        }
        return false;
    }

    /*!
     \reimp
    */
    function storeObjectAttribute( &$contentObjectAttribute )
    {
        $content = $contentObjectAttribute->content();

        $valueArray = $content['value'];
        $value = is_array( $valueArray ) ? implode( ',', array_keys( $valueArray ) ) : $valueArray;

        $contentObjectAttribute->setAttribute( "data_text", $value );
    }

    /*!
     \reimp
     Simple string insertion is supported.
    */
    function isSimpleStringInsertionSupported()
    {
        return true;
    }

    /*!
     \reimp
    */
    function insertSimpleString( &$object, $objectVersion, $objectLanguage,
                                 &$objectAttribute, $string,
                                 &$result )
    {
        $result = array( 'errors' => array(),
                         'require_storage' => true );
        $content = array( 'value' => $string );
        $objectAttribute->setContent( $content );
        return true;
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        $value = $contentObjectAttribute->attribute( 'data_text' );

        $countryList = explode( ',', $value );
        $countries = eZCountryType::fetchCountriesForCurrentLanguage();
        $resultList = array();
        foreach ( array_keys( $countryList ) as $defaultKey )
        {
            $dCountry = $countryList[$defaultKey];
            $resultList[$dCountry] = isset( $countries[$dCountry] ) ? $countries[$dCountry] : '';
        }
        // Supporting of previous version format.
        // For backwards compatability.
        if ( count( $resultList ) == 1 and $resultList[$value] == '' )
            $resultList = $value;

        $content = array( 'value' => $resultList );
        return $content;
    }

    /*!
     \reimp
    */
    function &classAttributeContent( &$classAttribute )
    {
        $defaultCountry = $classAttribute->attribute( EZ_DATATYPESTRING_COUNTRY_DEFAULT_LIST_FIELD );
        $multipleChoice = $classAttribute->attribute( EZ_DATATYPESTRING_COUNTRY_MULTIPLE_CHOICE_FIELD );
        $defaultCountryList = explode( ',', $defaultCountry );
        $countries = eZCountryType::fetchCountriesForCurrentLanguage();
        $resultList = array();
        foreach ( array_keys( $defaultCountryList ) as $defaultKey )
        {
            $dCountry = $defaultCountryList[$defaultKey];
            $resultList[$dCountry] = isset( $countries[$dCountry] ) ? $countries[$dCountry] : '';
        }

        $content = array( 'default_countries' => $resultList,
                          'multiple_choice' => $multipleChoice );

        return $content;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( &$contentObjectAttribute )
    {
        $content = $contentObjectAttribute->content();
        $content['value'] = is_array( $content['value'] ) ? implode( ',', $content['value'] ) : $content['value'];
        return $content['value'];
    }

    /*!
     \return string representation of an contentobjectattribute data for simplified export

    */
    function toString( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    function fromString( &$contentObjectAttribute, $string )
    {
        return $contentObjectAttribute->setAttribute( 'data_text', $string );
    }

    /*!
     Returns the country for use as a title
    */
    function title( &$contentObjectAttribute )
    {
        $content = $contentObjectAttribute->content();
        $content['value'] = is_array( $content['value'] ) ? implode( ',', $content['value'] ) : $content['value'];
        return $content['value'];
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        $content = $contentObjectAttribute->content();
        $result = ( ( !is_array( $content['value'] ) and trim( $content['value'] ) != '' ) or ( is_array( $content['value'] ) and count( $content['value'] ) > 0 ) );
        return $result;
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
     \reimp
    */
    function sortKey( &$contentObjectAttribute )
    {
        include_once( 'lib/ezi18n/classes/ezchartransform.php' );
        $trans =& eZCharTransform::instance();
        $content = $contentObjectAttribute->content();
        $content['value'] = is_array( $content['value'] ) ? implode( ',', $content['value'] ) : $content['value'];

        return $trans->transformByGroup( $content['value'], 'lowercase' );
    }

    /*!
     \reimp
    */
    function sortKeyType()
    {
        return 'string';
    }

    /*!
      \reimp
    */
    function diff( $old, $new, $options = false )
    {
        return null;
    }
}

eZDataType::register( EZ_DATATYPESTRING_COUNTRY, 'ezcountrytype' );

?>
