<?php
//
// Definition of eZIniSettingType class
//
// Created on: <01-Oct-2002 11:18:14 kk>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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

/*!
  \class eZIniSettingType ezinisettingtype.php
  \ingroup eZKernel
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

define( 'EZ_DATATYPEINISETTING_CLASS_FILE_FIELD', 'data_text1' );
define( 'EZ_DATATYPEINISETTING_CLASS_SECTION_FIELD', 'data_text2' );
define( 'EZ_DATATYPEINISETTING_CLASS_PARAMETER_FIELD', 'data_text3' );
define( 'EZ_DATATYPEINISETTING_CLASS_TYPE_FIELD', 'data_text4' );



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
                                                                     'Could not locate ini file' ) );
                return EZ_INPUT_VALIDATOR_STATE_INVALID;
            }

            $previousVariable =& $config->variable( $iniSection, $iniParameterName );

            if ( $previousVariable == null )
            {
                return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }

            if ( eZIniSettingType::objectAttributeContent( $contentClassAttribute ) != $previousVariable )
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                     'Previous version does not match settings in file.' ) );
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

        if ( $http->hasPostVariable( $fileParam ) &&
             $http->hasPostVariable( $sectionParam ) &&
             $http->hasPostVariable( $parameterParam ) &&
             $http->hasPostVariable( $typeParam ) )
        {
            $iniFile =& $http->postVariable( $base . EZ_DATATYPEINISETTING_CLASS_FILE . $classAttribute->attribute( 'id' ) );
            $iniSection =& $http->postVariable( $base . EZ_DATATYPEINISETTING_CLASS_SECTION . $classAttribute->attribute( 'id' ) );
//            $iniParameter =& $http->postVariable( $base . EZ_DATATYPEINISETTING_CLASS_PARAMETER . $classAttribute->attribute( 'id' ) );

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
                              $typeParam . ': ' .  $http->postVariable( $typeParam ) );
        return EZ_INPUT_VALIDATOR_STATE_INVALID;
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
        if ( $http->hasPostVariable( $fileParam ) &&
             $http->hasPostVariable( $sectionParam ) &&
             $http->hasPostVariable( $paramParam ) &&
             $http->hasPostVariable( $typeParam ) )
        {
            $file =& $http->postVariable( $fileParam );
            $section =& $http->postVariable( $sectionParam );
            $parameter =& $http->postVariable( $paramParam );
            $type =& $http->postVariable( $typeParam );

            $classAttribute->setAttribute( EZ_DATATYPEINISETTING_CLASS_FILE_FIELD, $file );
            $classAttribute->setAttribute( EZ_DATATYPEINISETTING_CLASS_SECTION_FIELD, $section );
            $classAttribute->setAttribute( EZ_DATATYPEINISETTING_CLASS_PARAMETER_FIELD, $parameter );
            $classAttribute->setAttribute( EZ_DATATYPEINISETTING_CLASS_TYPE_FIELD, $type );

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
            $contentObjectAttribute->setAttribute( 'data_text', $data );
        }
        return false;
    }

    /*!
     \reimp
    */
    function storeObjectAttribute( &$contentObjectattribute )
    {
    }

    /*!
     \reimp
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    /*!
      \reimp
    */
    function title( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    /*!
     \return a DOM representation of the content object attribute
    */
    function &serializeContentObjectAttribute( $objectAttribute )
    {
        include_once( 'lib/ezxml/classes/ezdomdocument.php' );
        include_once( 'lib/ezxml/classes/ezdomnode.php' );

        $node = new eZDOMNode();
        $node->setName( 'attribute' );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'name', $objectAttribute->contentClassAttributeName() ) );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'type', 'ezinisetting' ) );
        $node->appendAttribute( eZDOMDocument::createAttributeNode( 'value', $objectAttribute->attribute( "data_text" ) ) );

        return $node;
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

        $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'file', $file ) );
        $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'section', $section ) );
        $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'parameter', $parameter ) );
        $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'type', $type ) );
    }

    /*!
     \reimp
    */
    function &unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $file = $attributeParametersNode->elementTextContentByName( 'file' );
        $section = $attributeParametersNode->elementTextContentByName( 'section' );
        $parameter = $attributeParametersNode->elementTextContentByName( 'parameter' );
        $type =$attributeParametersNode->elementTextContentByName( 'type' );

        $classAttribute->setAttribute( EZ_DATATYPEINISETTING_CLASS_FILE_FIELD, $file );
        $classAttribute->setAttribute( EZ_DATATYPEINISETTING_CLASS_SECTION_FIELD, $section );
        $classAttribute->setAttribute( EZ_DATATYPEINISETTING_CLASS_PARAMETER_FIELD, $parameter );
        $classAttribute->setAttribute( EZ_DATATYPEINISETTING_CLASS_TYPE_FIELD, $type );
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

}

eZDataType::register( EZ_DATATYPESTRING_INISETTING, 'ezinisettingtype' );

?>
