<?php
//
// Definition of eZIniSettingType class
//
// Created on: <01-Oct-2002 11:18:14 kk>
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

/*!
  \class eZIniSettingType ezinisettingtype.php
  \ingroup eZDatatype
  \brief A content datatype for setting ini file settings

  Enable editing and versioning of ini files from the admin interface
*/

include_once( 'kernel/classes/ezdatatype.php' );
include_once( 'lib/ezutils/classes/ezintegervalidator.php' );
include_once( 'kernel/common/i18n.php' );

define( 'EZ_DATATYPESTRING_INISETTING', 'ezinisetting' );

define( 'EZ_DATATYPEINISETTING_CLASS_TYPE', '_ezinisetting_type_' );
define( 'EZ_DATATYPEINISETTING_CLASS_FILE', '_ezinisetting_file_' );
define( 'EZ_DATATYPEINISETTING_CLASS_SECTION', '_ezinisetting_section_' );
define( 'EZ_DATATYPEINISETTING_CLASS_PARAMETER', '_ezinisetting_parameter_' );
define( 'EZ_DATATYPEINISETTING_CLASS_INI_INSTANCE', '_ezinisetting_ini_instance_' );

define( 'EZ_DATATYPEINISETTING_CLASS_FILE_FIELD', 'data_text1' );
define( 'EZ_DATATYPEINISETTING_CLASS_SECTION_FIELD', 'data_text2' );
define( 'EZ_DATATYPEINISETTING_CLASS_PARAMETER_FIELD', 'data_text3' );
define( 'EZ_DATATYPEINISETTING_CLASS_TYPE_FIELD', 'data_int1' );
define( 'EZ_DATATYPEINISETTING_CLASS_INI_INSTANCE_FIELD', 'data_text4' );
define( 'EZ_DATATYPEINISETTING_CLASS_SITE_ACCESS_LIST_FIELD', 'data_text5' );

define( 'EZ_DATATYPEINISETTING_CLASS_TYPE_ARRAY', 6 );

class eZIniSettingType extends eZDataType
{
    /*!
     Initializes with a string id and a description.
    */
    function eZIniSettingType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_INISETTING, ezi18n( 'kernel/classes/datatypes', 'Ini Setting', 'Datatype name' ),
                                                      array( 'serialize_supported' => true ) );
    }

    /*!
      \reimp
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_ini_setting_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $contentClassAttribute =& $contentObjectAttribute->attribute( 'contentclass_attribute' );
            $iniFile =& eZIniSettingType::iniFile( $contentClassAttribute );
            $iniSection =& eZIniSettingType::iniSection( $contentClassAttribute );
            $iniParameterName =& eZIniSettingType::iniParameterName( $contentClassAttribute );

            $config =& eZINI::instance( $iniFile );
            if ( $config == null )
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                     'Could not locate the ini file.' ) );
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }

            if ( $contentClassAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_TYPE_FIELD ) == EZ_DATATYPEINISETTING_CLASS_TYPE_ARRAY )
            {
                $iniArray = array();
                if ( eZIniSettingType::parseArrayInput( $contentObjectAttribute->attribute( 'data_text' ), $iniArray ) === false )
                    return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }
        }
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     \reimp
    */
    function validateClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $fileParam = $base . EZ_DATATYPEINISETTING_CLASS_FILE . $classAttribute->attribute( 'id' );
        $sectionParam = $base . EZ_DATATYPEINISETTING_CLASS_SECTION . $classAttribute->attribute( 'id' );
        $parameterParam = $base . EZ_DATATYPEINISETTING_CLASS_PARAMETER . $classAttribute->attribute( 'id' );
        $typeParam = $base . EZ_DATATYPEINISETTING_CLASS_TYPE . $classAttribute->attribute( 'id' );
        $iniInstanceParam = $base . EZ_DATATYPEINISETTING_CLASS_INI_INSTANCE . $classAttribute->attribute( 'id' );

        if ( $http->hasPostVariable( $fileParam ) &&
             $http->hasPostVariable( $sectionParam ) &&
             $http->hasPostVariable( $parameterParam ) &&
             $http->hasPostVariable( $typeParam ) &&
             $http->hasPostVariable( $iniInstanceParam ) )
        {
            $iniFile =& $http->postVariable( $fileParam );
            $iniSection =& $http->postVariable( $sectionParam );

            $config =& eZIni::instance( $iniFile );
            if ( $config == null )
            {
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }

            if ( !$config->hasGroup( $iniSection ) )
            {
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }
            return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
        }

        eZDebug::writeNotice( 'Could not validate parameters: ' . "\n" .
                              $fileParam . ': ' .  $http->postVariable( $fileParam ) . "\n" .
                              $sectionParam . ': ' .  $http->postVariable( $sectionParam ) . "\n" .
                              $parameterParam . ': ' .  $http->postVariable( $parameterParam ) . "\n" .
                              $typeParam . ': ' .  $http->postVariable( $typeParam ). "\n" .
                              $iniInstanceParam. ': '. $http->postVariable( $iniInstanceParam ), 'eZIniSettingType::validateClassAttributeHTTPInput',
                              'eZIniSettingType::validateClassAttributeHTTPInput' );
        return EZ_INPUT_VALIDATOR_STATE_INVALID;
    }

    /*!
     \reimp
    */
    function initializeClassAttribute( &$classAttribute )
    {
        eZIniSettingType::setSiteAccessList( $classAttribute );
    }

    /*!
     \reimp
    */
    function initializeObjectAttribute( &$objectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $objectAttribute->setAttribute( 'data_text', $originalContentObjectAttribute->attribute( 'data_text' ) );
        }
        else
        {
            $contentClassAttribute =& $objectAttribute->attribute( 'contentclass_attribute' );
            $iniInstanceArray = explode( ';', $contentClassAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_INI_INSTANCE_FIELD ) );
            $siteAccessArray = explode( ';', $contentClassAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_SITE_ACCESS_LIST_FIELD ) );
            $filename =& $contentClassAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_FILE_FIELD );
            $section =& $contentClassAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_SECTION_FIELD );
            $parameter =& $contentClassAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_PARAMETER_FIELD );

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

                $config =& eZINI::instance( $filename, $path, null, null, null, true );

                $configValue =& $config->variable( $section, $parameter );

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
	function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
	{
        $fileParam = $base . EZ_DATATYPEINISETTING_CLASS_FILE . $classAttribute->attribute( 'id' );
        $sectionParam = $base . EZ_DATATYPEINISETTING_CLASS_SECTION . $classAttribute->attribute( 'id' );
        $paramParam = $base . EZ_DATATYPEINISETTING_CLASS_PARAMETER . $classAttribute->attribute( 'id' );
        $typeParam = $base . EZ_DATATYPEINISETTING_CLASS_TYPE . $classAttribute->attribute( 'id' );
        $iniInstanceParam = $base . EZ_DATATYPEINISETTING_CLASS_INI_INSTANCE . $classAttribute->attribute( 'id' );

        if ( $http->hasPostVariable( $fileParam ) &&
             $http->hasPostVariable( $sectionParam ) &&
             $http->hasPostVariable( $paramParam ) &&
             $http->hasPostVariable( $typeParam ) &&
             $http->hasPostVariable( $iniInstanceParam ) )
        {
            $file =& $http->postVariable( $fileParam );
            $section =& $http->postVariable( $sectionParam );
            $parameter =& $http->postVariable( $paramParam );
            $type =& $http->postVariable( $typeParam );

            $iniInstanceArray =& $http->postVariable( $iniInstanceParam );
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
            $classAttribute->setAttribute( EZ_DATATYPEINISETTING_CLASS_FILE_FIELD, $file );
            $classAttribute->setAttribute( EZ_DATATYPEINISETTING_CLASS_SECTION_FIELD, $section );
            $classAttribute->setAttribute( EZ_DATATYPEINISETTING_CLASS_PARAMETER_FIELD, $parameter );
            $classAttribute->setAttribute( EZ_DATATYPEINISETTING_CLASS_TYPE_FIELD, $type );
            $classAttribute->setAttribute( EZ_DATATYPEINISETTING_CLASS_INI_INSTANCE_FIELD, $iniInstance );

            return true;
        }
        return false;
    }

    /*!
      \reimp
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_ini_setting_' . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $data =& $http->postVariable( $base . '_ini_setting_' . $contentObjectAttribute->attribute( "id" ) );
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
    function onPublish( &$contentObjectAttribute, &$contentObject, &$publishedNodes )
    {
        $contentClassAttribute =& $contentObjectAttribute->attribute( 'contentclass_attribute' );
        $section =& $contentClassAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_SECTION_FIELD );
        $parameter =& $contentClassAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_PARAMETER_FIELD );
        $iniInstanceArray = explode( ';', $contentClassAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_INI_INSTANCE_FIELD ) );
        $siteAccessArray = explode( ';', $contentClassAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_SITE_ACCESS_LIST_FIELD ) );
        $filename =& $contentClassAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_FILE_FIELD );
        $makeEmptyArray = $contentObjectAttribute->attribute( 'data_int' );

        foreach ( $iniInstanceArray as $iniInstance )
        {
            if ( $iniInstance == 0 )
                $path = 'settings/override';
            else
                $path = 'settings/siteaccess/' . $siteAccessArray[$iniInstance];

            $config =& eZINI::instance( $filename . '.append.php', $path, null, null, null, true );

            if ( $config == null )
            {
                eZDebug::writeError( 'Could not open ' . $path . '/' . $filename );
                continue;
            }
            if ( $contentClassAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_TYPE_FIELD ) == EZ_DATATYPEINISETTING_CLASS_TYPE_ARRAY )
            {
                if ( $contentObjectAttribute->attribute( 'data_text' ) != null )
                {
                    $iniArray = array();
                    eZIniSettingType::parseArrayInput( $contentObjectAttribute->attribute( 'data_text' ), $iniArray, $makeEmptyArray );
                    $config->setVariable( $section, $parameter, $iniArray );
                }
                else
                {
                    $config->removeGroup( $section );
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
    function parseArrayInput( &$inputText, &$outputArray, $makeEmptyArray = false )
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
                $outputArray[$lineElements[0]] = implode( '=', array_slice( $lineElements, 1 ) );
            }
        }
        return true;
    }

    /*!
     \reimp
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        $contentClassAttribute =& $contentObjectAttribute->attribute( 'contentclass_attribute' );
        $section =& $contentClassAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_SECTION_FIELD );
        $parameter =& $contentClassAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_PARAMETER_FIELD );

        $iniInstanceArray = explode( ';', $contentClassAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_INI_INSTANCE_FIELD ) );
        $siteAccessArray = explode( ';', $contentClassAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_SITE_ACCESS_LIST_FIELD ) );
        $filename =& $contentClassAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_FILE_FIELD );

        $modified = array();

        $contentObject =& $contentObjectAttribute->attribute( 'object' );
        foreach ( $iniInstanceArray as $iniInstance )
        {
            if ( $iniInstance == 0 )
                $path = 'settings/override';
            else
                $path = 'settings/siteaccess/' . $siteAccessArray[$iniInstance];

            if ( !eZINI::parameterSet( $filename, $path, $section, $parameter ) )
                continue;

            $config =& eZINI::instance( $filename, $path, null, null, null, true );

            if ( is_array( $config->variable( $section, $parameter ) ) )
            {
                $objectIniArray = array();
                eZIniSettingType::parseArrayInput( $contentObjectAttribute->attribute( 'data_text' ), $objectIniArray );
                $existingIniArray =& $config->variable( $section, $parameter );
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

        return array( 'data' => $contentObjectAttribute->attribute( 'data_text' ),
                      'modified' => $modified );
    }

    /*!
      \reimp
    */
    function title( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        return true;
    }

    /*!
     \reimp
    */
    function &serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        include_once( 'lib/ezxml/classes/ezdomdocument.php' );

        $file =& $classAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_FILE_FIELD );
        $section =& $classAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_SECTION_FIELD );
        $parameter =& $classAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_PARAMETER_FIELD );
        $type =& $classAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_TYPE_FIELD );
        $iniInstance =& $classAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_INI_INSTANCE_FIELD );
        $siteAccess =& $classAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_SITE_ACCESS_LIST_FIELD );

        $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'file', $file ) );
        $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'section', $section ) );
        $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'parameter', $parameter ) );
        $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'type', $type ) );
        $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'ini_instance', $iniInstance ) );
        $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'site_access_list', $siteAccess ) );
    }

    /*!
     \reimp

     Use Override to do ini alterations if the specified site access does not exist
    */
    function &unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $file = $attributeParametersNode->elementTextContentByName( 'file' );
        $section = $attributeParametersNode->elementTextContentByName( 'section' );
        $parameter = $attributeParametersNode->elementTextContentByName( 'parameter' );
        $type = $attributeParametersNode->elementTextContentByName( 'type' );

        $classAttribute->setAttribute( EZ_DATATYPEINISETTING_CLASS_FILE_FIELD, $file );
        $classAttribute->setAttribute( EZ_DATATYPEINISETTING_CLASS_SECTION_FIELD, $section );
        $classAttribute->setAttribute( EZ_DATATYPEINISETTING_CLASS_PARAMETER_FIELD, $parameter );
        $classAttribute->setAttribute( EZ_DATATYPEINISETTING_CLASS_TYPE_FIELD, $type );


        /* Get and check if site access settings exist in this setup */
        $remoteIniInstanceList = $attributeParametersNode->elementTextContentByName( 'ini_instance' );
        $remoteSiteAccessList = $attributeParametersNode->elementTextContentByName( 'site_access_list' );
        $remoteIniInstanceArray = explode( ';', $iniInstanceList );
        $remoteSiteAccessArray = explode( ';', $siteAccessList );

        $config =& eZINI::instance( 'site.ini' );
        $localSiteAccessArray = array_merge( array( 'override' ), $config->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' ) );

        $localIniInstanceArray = array();
        foreach ( $remoteIniInstanceArray as $remoteIniInstance )
        {
            if ( in_array( $remoteSiteAccessArray[$remoteIniInstance], $localSiteAccessArray ) )
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

        $classAttribute->setAttribute( EZ_DATATYPEINISETTING_CLASS_INI_INSTANCE_FIELD, $iniInstance );
        $classAttribute->setAttribute( EZ_DATATYPEINISETTING_CLASS_SITE_ACCESS_LIST_FIELD, $siteAccess );
    }


    /*!
     \private
     Get Ini section parameter name

     \param Content Class Attribute
    */
    function &iniParameterName( &$contentClassAttribute )
    {
        return $contentClassAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_PARAMETER_FIELD );
    }

    /*!
     \private
     Get ini settings file

     \param Content Class Attribute
    */
    function &iniFile( &$contentClassAttribute )
    {
        return $contentClassAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_FILE_FIELD );
    }

    /*!
     \private
     Get Ini file section name

     \param Content Class Attribute
    */
    function &iniSection( &$contentClassAttribute )
    {
        return $contentClassAttribute->attribute( EZ_DATATYPEINISETTING_CLASS_SECTION_FIELD );
    }

    /*!
     \private
     \static
     Set site access list, including override option

     \param contentClassAttribute to set site access list and override options
    */
    function setSiteAccessList( &$contentClassAttribute )
    {
        $config =& eZINI::instance( 'site.ini' );
        $siteAccessArray = $config->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );
        $siteAccessList = 'override';
        foreach ( $siteAccessArray as $idx => $siteAccess )
        {
            $siteAccessList .= ';' . $siteAccess;
        }

        $contentClassAttribute->setAttribute( EZ_DATATYPEINISETTING_CLASS_SITE_ACCESS_LIST_FIELD, $siteAccessList );
    }

}

eZDataType::register( EZ_DATATYPESTRING_INISETTING, 'ezinisettingtype' );

?>
