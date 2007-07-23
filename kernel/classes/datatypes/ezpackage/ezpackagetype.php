<?php
//
// Definition of eZPackageType class
//
// Created on: <15-Oct-2003 13:17:04 wy>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
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

/*! \file ezpackagetype.php
*/

/*!
  \class eZPackageType ezpackagetype.php
  \ingroup eZDatatype
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
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Fetches the http post var string input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_ezpackage_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $data = $http->postVariable( $base . '_ezpackage_data_text_' . $contentObjectAttribute->attribute( 'id' ) );

            // Save in ini files if the package type is sitestyle.
            $classAttribute = $contentObjectAttribute->attribute( 'contentclass_attribute' );
            if ( $classAttribute->attribute( EZ_DATATYPESTRING_PACKAGE_TYPE_FIELD ) == 'sitestyle' )
            {
                $package = eZPackage::fetch( $data );
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
                    $currentSiteAccess = $http->hasPostVariable( 'CurrentSiteAccess' )
                                         ? $http->postVariable( 'CurrentSiteAccess' )
                                         : false;
                    $iniPath = 'settings/override';
                    if ( $currentSiteAccess != 'Global' and $currentSiteAccess !== false )
                    {
                        $data .= ':' . $currentSiteAccess;
                        $iniPath = 'settings/siteaccess/' . $currentSiteAccess;
                    }

                    $designINI = eZINI::instance( 'design.ini.append.php', $iniPath, null, false, null, true );
                    $designINI->setVariable( 'StylesheetSettings', 'SiteCSS', $siteCSS );
                    $designINI->setVariable( 'StylesheetSettings', 'ClassesCSS', $classesCSS );
                    $designINI->save();
                }
            }
            $contentObjectAttribute->setAttribute( 'data_text', $data );
        }
        return true;
    }

    /*!
     Does nothing since it uses the data_text field in the content object attribute.
     See fetchObjectAttributeHTTPInput for the actual storing.
    */
    function storeObjectAttribute( $attribute )
    {
        $ini = eZINI::instance();
        // Delete compiled template
        $siteINI = eZINI::instance();
        if ( $siteINI->hasVariable( 'FileSettings', 'CacheDir' ) )
        {
            $cacheDir = $siteINI->variable( 'FileSettings', 'CacheDir' );
            if ( $cacheDir[0] == "/" )
            {
                $cacheDir = eZDir::path( array( $cacheDir ) );
            }
            else
            {
                if ( $siteINI->hasVariable( 'FileSettings', 'VarDir' ) )
                {
                    $varDir = $siteINI->variable( 'FileSettings', 'VarDir' );
                    $cacheDir = eZDir::path( array( $varDir, $cacheDir ) );
                }
            }
        }
        else if ( $siteINI->hasVariable( 'FileSettings', 'VarDir' ) )
        {
            $varDir = $siteINI->variable( 'FileSettings', 'VarDir' );
            $cacheDir = $ini->variable( 'FileSettings', 'CacheDir' );
            $cacheDir = eZDir::path( array( $varDir, $cacheDir ) );
        }
        else
        {
            $cacheDir =  eZSys::cacheDirectory();
        }
        $compiledTemplateDir = $cacheDir ."/template/compiled";
        eZDir::unlinkWildcard( $compiledTemplateDir . "/", "*pagelayout*.*" );

        // Expire template block cache
        include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearTemplateBlockCacheIfNeeded( false );
    }

    /*!
     \reimp
    */
    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
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
    function objectAttributeContent( $contentObjectAttribute )
    {
        $packageName = $contentObjectAttribute->attribute( "data_text" );
        $package = eZPackage::fetch( $packageName );
        return $package;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    /*!
     Returns the content of the string for use as a title
    */
    function title( $contentObjectAttribute, $name = null )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
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
    function sortKey( &$contentObjectAttribute )
    {
        return strtolower( $contentObjectAttribute->attribute( 'data_text' ) );
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
    function serializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $type = $classAttribute->attribute( EZ_DATATYPESTRING_PACKAGE_TYPE_FIELD );
        $typeNode = $attributeParametersNode->ownerDocument->createElement( 'type', $type );
        $attributeParametersNode->appendChild( $typeNode );
    }

    /*!
     \reimp
    */
    function unserializeContentClassAttribute( &$classAttribute, &$attributeNode, &$attributeParametersNode )
    {
        $type = $attributeParametersNode->getElementsByTagName( 'type' )->item( 0 )->textContent;
        $classAttribute->setAttribute( EZ_DATATYPESTRING_PACKAGE_TYPE_FIELD, $type );
    }

    /*!
      \reimp
    */
    function diff( $old, $new, $options = false )
    {
        return null;
    }
}

eZDataType::register( EZ_DATATYPESTRING_EZ_PACKAGE, 'ezpackagetype' );

?>
