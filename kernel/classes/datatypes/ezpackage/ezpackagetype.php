<?php
//
// Definition of eZPackageType class
//
// Created on: <15-Oct-2003 13:17:04 wy>
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezpackagetype.php
*/

/*!
  \class eZPackageType ezpackagetype.php
  \brief The class eZPackageType does

*/
include_once( 'kernel/classes/ezdatatype.php' );
include_once( 'kernel/classes/ezpackage.php' );
include_once( 'kernel/common/i18n.php' );

define( 'EZ_DATATYPESTRING_EZ_PACKAGE', 'ezpackage' );
define( 'EZ_DATATYPESTRING_PACKAGE_TYPE_FIELD', 'data_text1' );
define( 'EZ_DATATYPESTRING_PACKAGE_TYPE_VARIABLE', '_ezpackage_type_' );
define( 'EZ_DATATYPESTRING_PACKAGE_VIEW_MODE_FIELD', 'data_int1' );
define( 'EZ_DATATYPESTRING_PACKAGE_VIEW_MODE_VARIABLE', '_ezpackage_view_mode_' );

class eZPackageType extends eZDataType
{
    /*!
     Constructor
    */
    function eZPackageType()
    {
        $this->eZDataType( EZ_DATATYPESTRING_EZ_PACKAGE, ezi18n( 'kernel/classes/datatypes', 'Package', 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( &$contentObjectAttribute, $currentVersion, &$originalContentObjectAttribute )
    {
    }

    /*!
     \reimp
    */
    function validateObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Fetches the http post var string input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( &$http, $base, &$contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_ezpackage_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $data =& $http->postVariable( $base . '_ezpackage_data_text_' . $contentObjectAttribute->attribute( 'id' ) );
            $contentObjectAttribute->setAttribute( 'data_text', $data );

            // Save in ini files if the package type is sitestyle.
            $classAttribute =& $contentObjectAttribute->attribute( 'contentclass_attribute' );
            if ( $classAttribute->attribute( EZ_DATATYPESTRING_PACKAGE_TYPE_FIELD ) == 'sitestyle' )
            {
                $package =& eZPackage::fetch( $data );
                if ( $package )
                {
                    $fileList = $package->fileList( 'default' );
                    foreach ( array_keys( $fileList ) as $key )
                    {
                        $file =& $fileList[$key];
                        $fileIdentifier = $file["variable-name"];
                        if ( $fileIdentifier == 'sitecssfile' )
                        {
                            $siteCSS = $package->fileItemPath( $file, 'default' );
                        }
                        else if ( $fileIdentifier == 'classescssfile' )
                        {
                            $classesCSS = $package->fileItemPath( $file, 'default' );
                        }
                    }

                    $iniPath = 'settings/override';
                    $designINI =& eZIni::instance( 'design.ini.append.php', $iniPath, null, false, null, true );
                    $designINI->setVariable( 'StylesheetSettings', 'SiteCSS', $siteCSS );
                    $designINI->setVariable( 'StylesheetSettings', 'ClassesCSS', $classesCSS );
                    $designINI->save();
                }
            }
        }
        return true;
    }

    /*!
     Does nothing since it uses the data_text field in the content object attribute.
     See fetchObjectAttributeHTTPInput for the actual storing.
    */
    function storeObjectAttribute( &$attribute )
    {
    }

    /*!
     \reimp
    */
    function fetchClassAttributeHTTPInput( &$http, $base, &$classAttribute )
    {
        $packageTypeName = $base . EZ_DATATYPESTRING_PACKAGE_TYPE_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $packageTypeName ) )
        {
            $packageTypeValue = $http->postVariable( $packageTypeName );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_PACKAGE_TYPE_FIELD, $packageTypeValue );
        }
        $packageViewModeName = $base . EZ_DATATYPESTRING_PACKAGE_VIEW_MODE_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $packageViewModeName ) )
        {
            $packageViewModeValue = $http->postVariable( $packageViewModeName );
            $classAttribute->setAttribute( EZ_DATATYPESTRING_PACKAGE_VIEW_MODE_FIELD, $packageViewModeValue );
        }
        return true;
    }

    /*!
     Returns the content.
    */
    function &objectAttributeContent( &$contentObjectAttribute )
    {
        $packageName = $contentObjectAttribute->attribute( "data_text" );

        $package =& eZPackage::fetch( $packageName );
        if ( !$package )
            return false;
        return $package;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    /*!
     Returns the content of the string for use as a title
    */
    function title( &$contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    function hasObjectAttributeContent( &$contentObjectAttribute )
    {
        return trim( $contentObjectAttribute->attribute( 'data_text' ) ) != '';
    }

    /*!
     \reimp
    */
    function isIndexable()
    {
        return false;
    }

    /*!
     \reimp
    */
    function &sortKey( &$contentObjectAttribute )
    {
        return strtolower( $contentObjectAttribute->attribute( 'data_text' ) );
    }

    /*!
     \reimp
    */
    function &sortKeyType()
    {
        return 'string';
    }

    /*!
     \reimp
    */
    function &serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $type = $classAttribute->attribute( EZ_DATATYPESTRING_PACKAGE_TYPE_FIELD );
        $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'type', $type ) );
    }

    /*!
     \reimp
    */
    function &unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $type = $attributeParametersNode->elementTextContentByName( 'type' );
        $classAttribute->setAttribute( EZ_DATATYPESTRING_PACKAGE_TYPE_FIELD, $type );
    }
}

eZDataType::register( EZ_DATATYPESTRING_EZ_PACKAGE, 'ezpackagetype' );

?>
