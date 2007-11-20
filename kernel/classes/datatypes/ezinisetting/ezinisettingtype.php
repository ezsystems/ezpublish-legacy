<?php
//
// Definition of eZIniSettingType class
//
// Created on: <01-Oct-2002 11:18:14 kk>
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

/*!
  \class eZIniSettingType ezinisettingtype.php
  \ingroup eZDatatype
  \brief A content datatype for setting ini file settings

  Enable editing and versioning of ini files from the admin interface
*/

//include_once( 'kernel/classes/ezdatatype.php' );
//include_once( 'lib/ezutils/classes/ezintegervalidator.php' );
require_once( 'kernel/common/i18n.php' );

class eZIniSettingType extends eZDataType
{
    const DATA_TYPE_STRING = 'ezinisetting';

    const CLASS_TYPE = '_ezinisetting_type_';
    const CLASS_FILE = '_ezinisetting_file_';
    const CLASS_SECTION = '_ezinisetting_section_';
    const CLASS_PARAMETER = '_ezinisetting_parameter_';
    const CLASS_INI_INSTANCE = '_ezinisetting_ini_instance_';

    const CLASS_FILE_FIELD = 'data_text1';
    const CLASS_SECTION_FIELD = 'data_text2';
    const CLASS_PARAMETER_FIELD = 'data_text3';
    const CLASS_TYPE_FIELD = 'data_int1';
    const CLASS_INI_INSTANCE_FIELD = 'data_text4';
    const SITE_ACCESS_LIST_FIELD = 'data_text5';

    const CLASS_TYPE_ARRAY = 6;

    /*!
     Initializes with a string id and a description.
    */
    function eZIniSettingType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezi18n( 'kernel/classes/datatypes', 'Ini Setting', 'Datatype name' ),
                                                         array( 'translation_allowed' => false,
                                                                'serialize_supported' => true ) );
    }

    /*!
      \reimp
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_ini_setting_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $contentClassAttribute = $contentObjectAttribute->attribute( 'contentclass_attribute' );
            $iniFile = eZIniSettingType::iniFile( $contentClassAttribute );
            $iniSection = eZIniSettingType::iniSection( $contentClassAttribute );
            $iniParameterName = eZIniSettingType::iniParameterName( $contentClassAttribute );

            $config = eZINI::instance( $iniFile );
            if ( $config == null )
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                     'Could not locate the ini file.' ) );
                return eZInputValidator::STATE_INVALID;
            }

            if ( $contentClassAttribute->attribute( self::CLASS_TYPE_FIELD ) == self::CLASS_TYPE_ARRAY )
            {
                $iniArray = array();
   //             if ( eZIniSettingType::parseArrayInput( $contentObjectAttribute->attribute( 'data_text' ), $iniArray ) === false )
                if ( eZIniSettingType::parseArrayInput( $http->postVariable( $base . '_ini_setting_' . $contentObjectAttribute->attribute( 'id' ) ), $iniArray ) === false )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', 'Wrong text field value.' ) );

                    return eZInputValidator::STATE_INVALID;
                }
            }
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     \reimp
    */
    function validateClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $fileParam = $base . self::CLASS_FILE . $classAttribute->attribute( 'id' );
        $sectionParam = $base . self::CLASS_SECTION . $classAttribute->attribute( 'id' );
        $parameterParam = $base . self::CLASS_PARAMETER . $classAttribute->attribute( 'id' );
        $typeParam = $base . self::CLASS_TYPE . $classAttribute->attribute( 'id' );
        $iniInstanceParam = $base . self::CLASS_INI_INSTANCE . $classAttribute->attribute( 'id' );

        if ( $http->hasPostVariable( $fileParam ) &&
             $http->hasPostVariable( $sectionParam ) &&
             $http->hasPostVariable( $parameterParam ) &&
             $http->hasPostVariable( $typeParam ) &&
             $http->hasPostVariable( $iniInstanceParam ) )
        {
            $iniFile = $http->postVariable( $fileParam );
            $iniSection = $http->postVariable( $sectionParam );

            $config = eZINI::instance( $iniFile );
            if ( $config == null )
            {
                return eZInputValidator::STATE_INVALID;
            }

            if ( !$config->hasGroup( $iniSection ) )
            {
                return eZInputValidator::STATE_INVALID;
            }
            return eZInputValidator::STATE_ACCEPTED;
        }

        eZDebug::writeNotice( 'Could not validate parameters: ' . "\n" .
                              $fileParam . ': ' .  $http->postVariable( $fileParam ) . "\n" .
                              $sectionParam . ': ' .  $http->postVariable( $sectionParam ) . "\n" .
                              $parameterParam . ': ' .  $http->postVariable( $parameterParam ) . "\n" .
                              $typeParam . ': ' .  $http->postVariable( $typeParam ). "\n" .
                              $iniInstanceParam. ': '. $http->postVariable( $iniInstanceParam ), 'eZIniSettingType::validateClassAttributeHTTPInput',
                              'eZIniSettingType::validateClassAttributeHTTPInput' );
        return eZInputValidator::STATE_INVALID;
    }

    /*!
     \reimp
    */
    function initializeClassAttribute( $classAttribute )
    {
        eZIniSettingType::setSiteAccessList( $classAttribute );
    }

    /*!
     \reimp
    */
    function initializeObjectAttribute( $objectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $objectAttribute->setAttribute( 'data_text', $originalContentObjectAttribute->attribute( 'data_text' ) );
        }
        else
        {
            $contentClassAttribute = $objectAttribute->attribute( 'contentclass_attribute' );
            $iniInstanceArray = explode( ';', $contentClassAttribute->attribute( self::CLASS_INI_INSTANCE_FIELD ) );
            $siteAccessArray = explode( ';', $contentClassAttribute->attribute( self::SITE_ACCESS_LIST_FIELD ) );
            $filename = $contentClassAttribute->attribute( self::CLASS_FILE_FIELD );
            $section = $contentClassAttribute->attribute( self::CLASS_SECTION_FIELD );
            $parameter = $contentClassAttribute->attribute( self::CLASS_PARAMETER_FIELD );

            if ( ! in_array( 0, $iniInstanceArray ) )  /* Makes sure it check 'settings' and 'settings/override' last */
                array_unshift( $iniInstanceArray,  0 );
            array_unshift( $iniInstanceArray, -1 );

            $configArray = array();

            foreach ( $iniInstanceArray as $iniInstance )
            {
                if ( $iniInstance == -1 )
                    $path = 'settings';
                else if ( $iniInstance == 0 )
                    $path = 'settings/override';
                else
                    $path = 'settings/siteaccess/' . $siteAccessArray[$iniInstance];

                if ( !eZINI::parameterSet( $filename, $path, $section, $parameter ) )
                    continue;

                $config = eZINI::instance( $filename, $path, null, null, null, true );

                $configValue = $config->variable( $section, $parameter );

                if ( is_array( $configValue ) )
                {
                    foreach ( array_keys( $configValue ) as $key )
                    {
                        $configArray[$key] = $configValue[$key];
                    }
                }
                else
                {
                    $objectAttribute->setAttribute( 'data_text', $configValue );
                    eZDebug::writeNotice( 'Loaded following values from ' . $path . '/' . $filename . ":\n" .
                                          '    ' . $configValue,
                                          'eZIniSettingType::initializeObjectAttribute');
                }
            }

            if ( count( $configArray ) > 0 )
            {
                $data = '';
                foreach( array_keys( $configArray ) as $key )
                {
                    if ( is_int( $key ) )
                    {
                        $data .= '=' . $configArray[$key] . "\n" ;
                    }
                    else
                    {
                        $data .= $key . '=' . $configArray[$key] . "\n" ;
                    }
                }
                $objectAttribute->setAttribute( 'data_text', $data );
            }
        }
    }

    /*!
     \reimp
    */
    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $fileParam = $base . self::CLASS_FILE . $classAttribute->attribute( 'id' );
        $sectionParam = $base . self::CLASS_SECTION . $classAttribute->attribute( 'id' );
        $paramParam = $base . self::CLASS_PARAMETER . $classAttribute->attribute( 'id' );
        $typeParam = $base . self::CLASS_TYPE . $classAttribute->attribute( 'id' );
        $iniInstanceParam = $base . self::CLASS_INI_INSTANCE . $classAttribute->attribute( 'id' );

        if ( $http->hasPostVariable( $fileParam ) &&
             $http->hasPostVariable( $sectionParam ) &&
             $http->hasPostVariable( $paramParam ) &&
             $http->hasPostVariable( $typeParam ) &&
             $http->hasPostVariable( $iniInstanceParam ) )
        {
            $file = $http->postVariable( $fileParam );
            $section = $http->postVariable( $sectionParam );
            $parameter = $http->postVariable( $paramParam );
            $type = $http->postVariable( $typeParam );

            $iniInstanceArray = $http->postVariable( $iniInstanceParam );
            if ( is_array( $iniInstanceArray ) )
            {
                $iniInstance = '';
                foreach ( $iniInstanceArray as $idx => $instance )
                {
                    if ( $idx > 0 )
                        $iniInstance .= ';';
                    $iniInstance .= $instance;
                }
            }
            else
            {
                $iniInstance = $iniInstanceArray;
            }

            eZIniSettingType::setSiteAccessList( $classAttribute );
            $classAttribute->setAttribute( self::CLASS_FILE_FIELD, $file );
            $classAttribute->setAttribute( self::CLASS_SECTION_FIELD, $section );
            $classAttribute->setAttribute( self::CLASS_PARAMETER_FIELD, $parameter );
            $classAttribute->setAttribute( self::CLASS_TYPE_FIELD, $type );
            $classAttribute->setAttribute( self::CLASS_INI_INSTANCE_FIELD, $iniInstance );

            return true;
        }
        return false;
    }

    /*!
      \reimp
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_ini_setting_' . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $data = $http->postVariable( $base . '_ini_setting_' . $contentObjectAttribute->attribute( "id" ) );
            $contentObjectAttribute->setAttribute( 'data_text', trim( $data ) );
            if ( $http->hasPostVariable( $base . '_ini_setting_make_empty_array_' . $contentObjectAttribute->attribute( "id" ) ) )
            {
                $isChecked = $http->postVariable( $base . '_ini_setting_make_empty_array_' . $contentObjectAttribute->attribute( "id" ) );
                if ( isset( $isChecked ) )
                    $isChecked = 1;
                $contentObjectAttribute->setAttribute( 'data_int', $isChecked );
            }
            else
            {
                $contentObjectAttribute->setAttribute( 'data_int', 0 );
            }
            return true;
        }
        return false;
    }

    /*!
     \reimp
    */
    function onPublish( $contentObjectAttribute, $contentObject, $publishedNodes )
    {
        $contentClassAttribute = $contentObjectAttribute->attribute( 'contentclass_attribute' );
        $section = $contentClassAttribute->attribute( self::CLASS_SECTION_FIELD );
        $parameter = $contentClassAttribute->attribute( self::CLASS_PARAMETER_FIELD );
        $iniInstanceArray = explode( ';', $contentClassAttribute->attribute( self::CLASS_INI_INSTANCE_FIELD ) );
        $siteAccessArray = explode( ';', $contentClassAttribute->attribute( self::SITE_ACCESS_LIST_FIELD ) );
        $filename = $contentClassAttribute->attribute( self::CLASS_FILE_FIELD );
        $makeEmptyArray = $contentObjectAttribute->attribute( 'data_int' );

        foreach ( $iniInstanceArray as $iniInstance )
        {
            if ( $iniInstance == 0 )
                $path = 'settings/override';
            else
                $path = 'settings/siteaccess/' . $siteAccessArray[$iniInstance];

            $config = eZINI::instance( $filename . '.append', $path, null, false, null, true, true );

            if ( $config == null )
            {
                eZDebug::writeError( 'Could not open ' . $path . '/' . $filename );
                continue;
            }
            if ( $contentClassAttribute->attribute( self::CLASS_TYPE_FIELD ) == self::CLASS_TYPE_ARRAY )
            {
                if ( $contentObjectAttribute->attribute( 'data_text' ) != null )
                {
                    $iniArray = array();
                    eZIniSettingType::parseArrayInput( $contentObjectAttribute->attribute( 'data_text' ), $iniArray, $makeEmptyArray );
                    $config->setVariable( $section, $parameter, $iniArray );
                }
                else
                {
                    $config->removeSetting( $section, $parameter );
                }
            }
            else
            {
                $config->setVariable( $section, $parameter, $contentObjectAttribute->attribute( 'data_text' ) );
                eZDebug::writeNotice( 'Saved ini settings to file: ' . $path . '/' . $filename . "\n" .
                                      '                            ['. $section . ']' . "\n" .
                                      '                            ' . $parameter . '=' . $contentObjectAttribute->attribute( 'data_text' ),
                                      'eZIniSettingType::onPublish' );
            }
            $config->save();
        }
    }

    /*!
     \private
     Parse array input text into array with korrect keys.

     \param input text
     \param array to store parsed file to

     \return true if parsed successfully, false if illegal syntax
    */
    function parseArrayInput( $inputText, &$outputArray, $makeEmptyArray = false )
    {
        $lineArray = explode( "\n", $inputText );

        if( $makeEmptyArray )
        {
            $outputArray[] = "";
        }

        foreach ( array_keys( $lineArray ) as $key )
        {
            $line = str_replace( "\r", '', $lineArray[$key] );

            if ( strlen( $line ) <= 2 )
                continue;

            if ( strstr( $line, '=' ) === false )
                return false;

            $lineElements = explode( '=', $line );
            if ( count( $lineElements ) == 1 )
            {
                $outputArray[] = $lineElements[0];
            }
            else
            {
                if ( $lineElements[0] != '' )
                {
                    $outputArray[ $lineElements[0] ] = implode( '=', array_slice( $lineElements, 1 ) );
                }
                else
                    $outputArray[] = implode( '=', array_slice( $lineElements, 1 ) );
            }
        }
        return true;
    }

    /*!
     \reimp
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        $contentClassAttribute = $contentObjectAttribute->attribute( 'contentclass_attribute' );
        $section = $contentClassAttribute->attribute( self::CLASS_SECTION_FIELD );
        $parameter = $contentClassAttribute->attribute( self::CLASS_PARAMETER_FIELD );

        $iniInstanceArray = explode( ';', $contentClassAttribute->attribute( self::CLASS_INI_INSTANCE_FIELD ) );
        $siteAccessArray = explode( ';', $contentClassAttribute->attribute( self::SITE_ACCESS_LIST_FIELD ) );
        $filename = $contentClassAttribute->attribute( self::CLASS_FILE_FIELD );

        $modified = array();

        $contentObject = $contentObjectAttribute->attribute( 'object' );
        foreach ( $iniInstanceArray as $iniInstance )
        {
            if ( $iniInstance == 0 )
                $path = 'settings/override';
            else
                $path = 'settings/siteaccess/' . $siteAccessArray[$iniInstance];

            if ( !eZINI::parameterSet( $filename, $path, $section, $parameter ) )
                continue;

            $config = eZINI::instance( $filename, $path, null, null, null, true );

            if ( is_array( $config->variable( $section, $parameter ) ) )
            {
                $objectIniArray = array();
                eZIniSettingType::parseArrayInput( $contentObjectAttribute->attribute( 'data_text' ), $objectIniArray );
                $existingIniArray = $config->variable( $section, $parameter );
                foreach ( array_keys( $existingIniArray ) as $key )
                {
                    if ( !is_int( $key ) && $existingIniArray[$key] != $objectIniArray[$key] )
                    {
                        $modified[] = array( 'ini_value' => $parameter . '[' . $key . ']=' . $existingIniArray[$key],
                                             'file' => $path . '/' . $filename );
                    }
                }
            }
            else if ( $config->variable( $section, $parameter ) != $contentObjectAttribute->attribute( 'data_text' ) )
            {
                $modified[] = array( 'ini_value' => $parameter . '=' . $config->variable( $section, $parameter ),
                                     'file' => $path . '/' . $filename );
            }
        }

        $data = array( 'data' => $contentObjectAttribute->attribute( 'data_text' ),
                       'modified' => $modified );
        return $data;
    }

    /*!
      \reimp
    */
    function title( $contentObjectAttribute, $name = null )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        return true;
    }

    /*!
     \reimp
    */
    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $file = $classAttribute->attribute( self::CLASS_FILE_FIELD );
        $section = $classAttribute->attribute( self::CLASS_SECTION_FIELD );
        $parameter = $classAttribute->attribute( self::CLASS_PARAMETER_FIELD );
        $type = $classAttribute->attribute( self::CLASS_TYPE_FIELD );
        $iniInstance = $classAttribute->attribute( self::CLASS_INI_INSTANCE_FIELD );
        $siteAccess = $classAttribute->attribute( self::SITE_ACCESS_LIST_FIELD );

        $dom = $attributeParametersNode->ownerDocument;
        $fileNode = $dom->createElement( 'file', $file );
        $attributeParametersNode->appendChild( $fileNode );
        $sectionNode = $dom->createElement( 'section', $section );
        $attributeParametersNode->appendChild( $sectionNode );
        $parameterNode = $dom->createElement( 'parameter', $parameter );
        $attributeParametersNode->appendChild( $parameterNode );
        $typeNode = $dom->createElement( 'type', $type );
        $attributeParametersNode->appendChild( $typeNode );
        $iniInstanceNode = $dom->createElement( 'ini_instance', $iniInstance );
        $attributeParametersNode->appendChild( $iniInstanceNode );
        $siteAccessListNode = $dom->createElement( 'site_access_list', $siteAccess );
        $attributeParametersNode->appendChild( $siteAccessListNode );
    }

    /*!
     \reimp

     Use Override to do ini alterations if the specified site access does not exist
    */
    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $file = $attributeParametersNode->getElementsByTagName( 'file' )->item( 0 )->textContent;
        $section = $attributeParametersNode->getElementsByTagName( 'section' )->item( 0 )->textContent;
        $parameter = $attributeParametersNode->getElementsByTagName( 'parameter' )->item( 0 )->textContent;
        $type = $attributeParametersNode->getElementsByTagName( 'type' )->item( 0 )->textContent;

        $classAttribute->setAttribute( self::CLASS_FILE_FIELD, $file );
        $classAttribute->setAttribute( self::CLASS_SECTION_FIELD, $section );
        $classAttribute->setAttribute( self::CLASS_PARAMETER_FIELD, $parameter );
        $classAttribute->setAttribute( self::CLASS_TYPE_FIELD, $type );


        /* Get and check if site access settings exist in this setup */
        $remoteIniInstanceList = $attributeParametersNode->getElementsByTagName( 'ini_instance' )->item( 0 )->textContent;
        $remoteSiteAccessList = $attributeParametersNode->getElementsByTagName( 'site_access_list' )->item( 0 )->textContent;
        $remoteIniInstanceArray = explode( ';', $remoteIniInstanceList );
        $remoteSiteAccessArray = explode( ';', $remoteSiteAccessList );

        $config = eZINI::instance( 'site.ini' );
        $localSiteAccessArray = array_merge( array( 'override' ), $config->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' ) );

        $localIniInstanceArray = array();
        foreach ( $remoteIniInstanceArray as $remoteIniInstance )
        {
            if ( isset( $remoteSiteAccessArray[$remoteIniInstance] ) and in_array( $remoteSiteAccessArray[$remoteIniInstance], $localSiteAccessArray ) )
            {
                $localSiteAccessArray[] = array_keys( $localSiteAccessArray, $remoteSiteAccessArray[$remoteIniInstance] );
            }
        }

        if ( count( $localSiteAccessArray ) == 0 )
        {
            $localIniInstanceArray = array( 0 );
        }

        $iniInstance = '';
        foreach( $localIniInstanceArray as $idx => $localIniInstance )
        {
            if ( $idx > 0 )
                $iniInstance .= ';';
            $iniInstance .= $localIniInstance;
        }

        $siteAccess = '';
        foreach( $localSiteAccessArray as $idx => $localSiteAccess )
        {
            if ( $idx > 0 )
                $siteAccess .= ';';
            $siteAccess .= $localSiteAccess;
        }

        $classAttribute->setAttribute( self::CLASS_INI_INSTANCE_FIELD, $iniInstance );
        $classAttribute->setAttribute( self::SITE_ACCESS_LIST_FIELD, $siteAccess );
    }


    /*!
     \private
     Get Ini section parameter name

     \param Content Class Attribute
    */
    function iniParameterName( $contentClassAttribute )
    {
        return $contentClassAttribute->attribute( self::CLASS_PARAMETER_FIELD );
    }

    /*!
     \private
     Get ini settings file

     \param Content Class Attribute
    */
    function iniFile( $contentClassAttribute )
    {
        return $contentClassAttribute->attribute( self::CLASS_FILE_FIELD );
    }

    /*!
     \private
     Get Ini file section name

     \param Content Class Attribute
    */
    function iniSection( $contentClassAttribute )
    {
        return $contentClassAttribute->attribute( self::CLASS_SECTION_FIELD );
    }

    /*!
     \private
     \static
     Set site access list, including override option

     \param contentClassAttribute to set site access list and override options
    */
    function setSiteAccessList( $contentClassAttribute )
    {
        $config = eZINI::instance( 'site.ini' );
        $siteAccessArray = $config->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );
        $siteAccessList = 'override';
        foreach ( $siteAccessArray as $idx => $siteAccess )
        {
            $siteAccessList .= ';' . $siteAccess;
        }

        $contentClassAttribute->setAttribute( self::SITE_ACCESS_LIST_FIELD, $siteAccessList );
    }

    function toString( $contentObjectAttribute )
    {
        $makeEmptyArray = $contentObjectAttribute->attribute( 'data_int' );
        $value = $contentObjectAttribute->attribute( 'data_text' );
        return implode( '|', array( $value, $makeEmptyArray ) );
    }


    function fromString( $contentObjectAttribute, $string )
    {
        if ( $string == '' )
            return true;
        $iniData = explode( '|', $string );

        $contentObjectAttribute->setAttribute( 'data_text', $value );
        if ( isset ( $iniData[1] ) )
            $contentObjectAttribute->setAttribute( 'data_int', $makeEmptyArray );
        return true;
    }

    /*!
     \reimp
    */
    function serializeContentObjectAttribute( $package, $objectAttribute )
    {
        $node = $this->createContentObjectAttributeDOMNode( $objectAttribute );
        $makeEmptyArray = $objectAttribute->attribute( 'data_int' );
        $value = $objectAttribute->attribute( 'data_text' );

        $dom = $node->ownerDocument;

        $makeEmptyArrayNode = $dom->createElement( 'make_empty_array', $makeEmptyArray );
        $node->appendChild( $makeEmptyArrayNode );
        $valueNode = $dom->createElement( 'value', $value );
        $node->appendChild( $valueNode );

        return $node;
    }

    /*!
     \reimp
    */
    function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode )
    {
        $makeEmptyArray = $attributeNode->getElementsByTagName( 'make_empty_array' )->item( 0 )->textContent;
        $value = $attributeNode->getElementsByTagName( 'value' )->item( 0 )->textContent;

        if ( $makeEmptyArray === false )
            $makeEmptyArray = 0;

        if ( $value === false )
            $value = '';

        $objectAttribute->setAttribute( 'data_int', $makeEmptyArray );
        $objectAttribute->setAttribute( 'data_text', $value );
    }

    /*!
      \reimp
    */
    function diff( $old, $new, $options = false )
    {
        return null;
    }
}

eZDataType::register( eZIniSettingType::DATA_TYPE_STRING, 'eZIniSettingType' );

?>
