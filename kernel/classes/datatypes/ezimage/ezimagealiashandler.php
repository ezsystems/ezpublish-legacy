<?php
//
// Definition of eZImageAliasHandler class
//
// Created on: <16-Oct-2003 09:34:25 bf>
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

/*!
  \class eZImageAliasHandler ezimagealiashandler.php
  \ingroup eZKernel
  \brief The class eZImage handles images

*/
include_once( 'lib/ezfile/classes/ezfilehandler.php' );
include_once( "lib/ezxml/classes/ezxml.php" );
include_once( "kernel/classes/datatypes/ezimage/ezimagefile.php" );

class eZImageAliasHandler
{
    function eZImageAliasHandler( &$contentObjectAttribute )
    {
        $this->ContentObjectAttribute =& $contentObjectAttribute;
    }

    function attributes()
    {
        include_once( 'kernel/common/image.php' );
        $imageManager =& imageInit();
        $aliasList = $imageManager->aliasList();
        return array_merge( array( 'alternative_text',
                                   'original_filename',
                                   'is_valid' ),
                            array_keys( $aliasList ) );
    }

    function hasAttribute( $attributeName )
    {
        if ( in_array( $attributeName,
                       array( 'alternative_text',
                              'original_filename',
                              'is_valid' ) ) )
            return true;
        include_once( 'kernel/common/image.php' );
        $imageManager =& imageInit();
        if ( $imageManager->hasAlias( $attributeName ) )
            return true;
        return false;
    }

    function &attribute( $attributeName )
    {
        if ( in_array( $attributeName,
                       array( 'alternative_text',
                              'original_filename',
                              'is_valid' ) ) )
        {
            $originalAttribute =& $this->attributeFromOriginal( $attributeName );
            return $originalAttribute;
        }
        $aliasName = $attributeName;
        return $this->imageAlias( $aliasName );
    }

//     function &copyOfFilename()
//     {
//         $contentObjectAttribute =& $this->ContentObjectAttribute;
//         $copyOf = false;
//         if ( is_array( $contentObjectAttribute->DataTypeCustom ) and
//              isset( $contentObjectAttribute->DataTypeCustom['copy_of'] ) )
//             $copyOf = $contentObjectAttribute->DataTypeCustom['copy_of'];
//         return $copyOf;
//     }

    function &originalAttributeData()
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( isset( $contentObjectAttribute->DataTypeCustom['original_data'] ) )
            return $contentObjectAttribute->DataTypeCustom['original_data'];
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();
        $originalData = array( 'attribute_id' => false,
                               'attribute_version' => false,
                               'attribute_language' => false );
        $contentObjectAttribute->DataTypeCustom['original_data'] =& $originalData;
        return $originalData;
    }

    function setOriginalAttributeData( $originalData )
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();
        $contentObjectAttribute->DataTypeCustom['original_data'] =& $originalData;

        $domTree =& $this->domTree();
        $imageOriginalArray =& $domTree->elementsByName( "original" );
        if ( isset( $imageOriginalArray[0] ) )
            $this->createOriginalAttributeXMLData( $imageOriginalArray[0], $originalData );
    }

    function setOriginalAttributeDataFromAttribute( &$contentObjectAttribute )
    {
        $originalImageHandler =& $contentObjectAttribute->attribute( 'content' );
        $originalAttributeData =& $originalImageHandler->originalAttributeData();
        $domTree =& $originalImageHandler->domTree();
        $this->setDOMTree( $domTree );
        if ( $originalAttributeData['attribute_id'] )
        {
            $this->setOriginalAttributeData( $originalAttributeData );
        }
        else
        {
            $this->setOriginalAttributeDataValues( $contentObjectAttribute->attribute( 'id' ),
                                                   $contentObjectAttribute->attribute( 'version' ),
                                                   $contentObjectAttribute->attribute( 'language_code' ),
                                                   false );
        }
    }

    function setOriginalAttributeDataValues( $attributeID, $attributeVersion, $attributeLanguage )
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();
        $originalData = array( 'attribute_id' => $attributeID,
                               'attribute_version' => $attributeVersion,
                               'attribute_language' => $attributeLanguage );
        $this->setOriginalAttributeData( $originalData );
    }

    function isImageOwner()
    {
        $originalData = $this->originalAttributeData();
        return ( $originalData['attribute_id'] === false );
    }

//     function hasFileCopy()
//     {
//         $originalData = $this->originalAttributeData();
//         return $originalData['has_file_copy'];
//     }

//     function setHasFileCopy( $hasFileCopy )
//     {
//         $originalData =& $this->originalAttributeData();
//         $originalData['has_file_copy'] = $hasFileCopy;
//     }

//     function setCopyOfFilename( $copyOf )
//     {
//         $contentObjectAttribute =& $this->ContentObjectAttribute;
//         if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
//             $contentObjectAttribute->DataTypeCustom = array();
//         $aliasList =& $this->aliasList();
//         $contentObjectAttribute->DataTypeCustom['copy_of'] = $copyOf;
//         $this->recreateDOMTree();
//         $this->setStorageRequired();
//     }

    function imageSerialNumber()
    {
        $serialNumber = $this->imageSerialNumberRaw();
        if ( $serialNumber < 1 )
            $serialNumber = 1;
        return $serialNumber;
    }

    function &imageSerialNumberRaw()
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( isset( $contentObjectAttribute->DataTypeCustom['serial_number'] ) and
             $contentObjectAttribute->DataTypeCustom['serial_number'] >= 0 )
            return $contentObjectAttribute->DataTypeCustom['serial_number'];
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();
        $contentObjectAttribute->DataTypeCustom['serial_number'] = 0;
        return $contentObjectAttribute->DataTypeCustom['serial_number'];
    }

    function increaseImageSerialNumber()
    {
        $serialNumber =& $this->imageSerialNumberRaw();
        ++$serialNumber;
    }

    function resetImageSerialNumber()
    {
        $serialNumber =& $this->imageSerialNumberRaw();
        $serialNumber = 0;
    }

    function setImageSerialNumber( $number )
    {
        $serialNumber =& $this->imageSerialNumberRaw();
        $serialNumber = $number;
    }

    function &attributeFromOriginal( $attributeName )
    {
        $originalAlias =& $this->attribute( 'original' );
        if ( $originalAlias )
            return $originalAlias[$attributeName];
        return null;
    }

    function setAttribute( $attributeName, $attributeValue )
    {
        if ( in_array( $attributeName,
                       array( 'alternative_text',
                              'original_filename' ) ) )
        {
            $aliasList =& $this->aliasList();
            foreach ( array_keys( $aliasList ) as $aliasName )
            {
                $alias =& $aliasList[$aliasName];
                $alias[$attributeName] = $attributeValue;
            }
            if ( $attributeName == 'alternative_text' )
            {
                $text = $this->displayText( $attributeValue );
                foreach ( array_keys( $aliasList ) as $aliasName )
                {
                    $alias =& $aliasList[$aliasName];
                    $alias['text'] = $text;
                }
            }
            $this->recreateDOMTree();
            $this->setStorageRequired();
            return true;
        }
        return false;
    }

    function displayText( $alternativeText = null )
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( $alternativeText === null )
            $text = $this->attribute( 'alternative_text' );
        else
            $text = $alternativeText;
        if ( !$text )
        {
            $contentVersion =& eZContentObjectVersion::fetchVersion( $contentObjectAttribute->attribute( 'version' ),
                                                                     $contentObjectAttribute->attribute( 'contentobject_id' ),
                                                                     true );
            if ( $contentVersion )
                $text = $contentVersion->versionName( $contentObjectAttribute->attribute( 'language_code' ) );
        }
        return $text;
    }

    function removeAllAliases( &$contentObjectAttribute )
    {
        $files = eZImageFile::fetchForContentObjectAttribute( $contentObjectAttribute->attribute( 'id' ) );
        $dirs = array();
        foreach ( $files as $filepath )
        {
            if ( unlink( $filepath ) )
            {
                $dirs[] = eZDir::dirpath( $filepath );
            }
            else
            {
                eZDebug::writeError( "Image file $filepath does not exist, could not remove from disk",
                                     'eZImageAliasHandler::removeAllAliases' );
            }
        }
        $dirs = array_unique( $dirs );
        foreach ( $dirs as $dirpath )
        {
            eZDir::cleanupEmptyDirectories( $dirpath );
        }
        eZImageFile::removeForContentObjectAttribute( $contentObjectAttribute->attribute( 'id' ) );
    }

    function removeAliases()
    {
        $aliasList =& $this->aliasList();
        $alternativeText = false;
//         $copyOfFilename = $this->copyOfFilename();
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( $this->isImageOwner() )
        {
            foreach ( array_keys( $aliasList ) as $aliasName )
            {
                $alias =& $aliasList[$aliasName];
                if ( $aliasName == 'original' )
                    $alternativeText = $alias['alternative_text'];
                if ( $alias['is_valid'] )
                {
                    $filepath = $alias['url'];
                    if ( unlink( $filepath ) )
                    {
                        eZImageFile::removeFilepath( $contentObjectAttribute->attribute( 'id' ), $filepath );
                        eZDir::cleanupEmptyDirectories( $filepath );
                    }
                    else
                    {
                        eZDebug::writeError( "Image file $filepath for alias $aliasName does not exist, could not remove from disk",
                                             'eZImageAliasHandler::removeAliases' );
                    }
                }
            }
        }
        unset( $aliasList );
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();

        $doc = new eZDOMDocument();
        $imageNode =& $doc->createElementNode( "ezimage" );
        $doc->setRoot( $imageNode );

        $imageNode->appendAttribute( $doc->createAttributeNode( 'serial_number', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'is_valid', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'filename', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'suffix', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'basename', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'dirpath', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'url', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'original_filename', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'mime_type', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'width', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'height', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'alternative_text', $alternativeText ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'alias_key', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'timestamp', false ) );

        $contentObjectAttribute->DataTypeCustom['dom_tree'] =& $doc;
        unset( $contentObjectAttribute->DataTypeCustom['alias_list'] );
        $this->storeDOMTree( $doc );
    }

    function updateAliasPath( $dirpath, $name )
    {
        if ( !file_exists( $dirpath ) )
        {
            eZDir::mkdir( $dirpath, eZDir::directoryPermission(), true );
        }
        include_once( 'lib/ezutils/classes/ezmimetype.php' );
        $aliasList =& $this->aliasList();
//         $hasFileCopy = $this->hasFileCopy();
        $this->resetImageSerialNumber();
        foreach ( array_keys( $aliasList ) as $aliasName )
        {
            $alias =& $aliasList[$aliasName];
            if ( $alias['dirpath'] != $dirpath )
            {
                $oldDirpath = $alias['url'];
                $oldURL = $alias['url'];
                $basename = $name;
                if ( $aliasName != 'original' )
                    $basename .= '_' . $aliasName;
                eZMimeType::changeFileData( $alias, $dirpath, $basename );
                $url = $alias['url'];
                if ( $this->isImageOwner() )
                {
                    if ( $oldURL == '' )
                    {
                        continue;
                    }
                    eZFileHandler::move( $oldURL, $alias['url'] );
                    eZDir::cleanupEmptyDirectories( $oldDirpath );
                    eZImageFile::moveFilepath( $this->ContentObjectAttribute->attribute( 'id' ), $oldURL, $alias['url'] );
                }
                else
                {
//                     $hasFileCopy = true;
                    eZFileHandler::linkCopy( $oldURL, $alias['url'], false );
                    eZImageFile::appendFilepath( $this->ContentObjectAttribute->attribute( 'id' ), $alias['url'] );
                }
            }
        }
//         $this->setHasFileCopy( $hasFileCopy );
        $this->recreateDOMTree();
        $this->setStorageRequired();
    }

    function directoryPath()
    {
        $aliasList =& $this->aliasList();
        if ( isset( $aliasList['original'] ) )
        {
            return $aliasList['original']['dirpath'];
        }
        return false;
    }

    /*!
    */
    function &imageAlias( $aliasName )
    {
        include_once( 'kernel/common/image.php' );
        $imageManager =& imageInit();
        if ( !$imageManager->hasAlias( $aliasName ) )
            return null;
        $aliasList =& $this->aliasList();
        if ( array_key_exists( $aliasName, $aliasList ) )
        {
            $alias =& $aliasList[$aliasName];
            return $alias;
        }
        else
        {
            $imageManager =& imageInit();
            if ( $imageManager->hasAlias( $aliasName ) )
            {
                $original =& $aliasList['original'];
                $basename = $original['basename'];
                if ( $imageManager->createImageAlias( $aliasName, $aliasList,
                                                      array( 'basename' => $basename ) ) )
                {
                    $text = $this->displayText( $original['alternative_text'] );
                    $originalFilename = $original['original_filename'];
                    foreach ( array_keys( $aliasList ) as $aliasName )
                    {
                        $alias =& $aliasList[$aliasName];
                        $alias['original_filename'] = $originalFilename;
                        $alias['text'] = $text;
                        if ( $alias['is_new'] )
                            eZImageFile::appendFilepath( $this->ContentObjectAttribute->attribute( 'id' ), $alias['url'] );
                    }
                    $this->addImageAliases( $aliasList );
                    return $aliasList[$aliasName];
                }
            }
        }
        return null;
    }

    function createOriginalAttributeXMLData( &$originalNode, $originalData )
    {
        $originalNode->removeAttributes();
        $originalNode->appendAttribute( eZDOMDocument::createAttributeNode( 'attribute_id', $originalData['attribute_id'] ) );
        $originalNode->appendAttribute( eZDOMDocument::createAttributeNode( 'attribute_version', $originalData['attribute_version'] ) );
        $originalNode->appendAttribute( eZDOMDocument::createAttributeNode( 'attribute_language', $originalData['attribute_language'] ) );
//         $originalNode->appendAttribute( eZDOMDocument::createAttributeNode( 'has_file_copy', $originalData['has_file_copy'] ) );
    }

    function recreateDOMTree()
    {
        $aliasList =& $this->aliasList();

        $doc = new eZDOMDocument();
        $imageNode =& $doc->createElementNode( "ezimage" );
        $doc->setRoot( $imageNode );

        $originalNode =& $doc->createElementNode( "original" );
        $imageNode->appendChild( $originalNode );

        include_once( 'kernel/common/image.php' );
        $imageManager =& imageInit();

        $aliasName = 'original';

        $originalData = $this->originalAttributeData();
        $this->createOriginalAttributeXMLData( $originalNode, $originalData );

        $imageNode->appendAttribute( $doc->createAttributeNode( 'serial_number', $this->imageSerialNumber() ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'is_valid', $aliasList[$aliasName]['is_valid'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'filename', $aliasList[$aliasName]['filename'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'suffix', $aliasList[$aliasName]['suffix'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'basename', $aliasList[$aliasName]['basename'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'dirpath', $aliasList[$aliasName]['dirpath'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'url', $aliasList[$aliasName]['url'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'original_filename', $aliasList[$aliasName]['original_filename'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'mime_type', $aliasList[$aliasName]['mime_type'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'width', $aliasList[$aliasName]['width'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'height', $aliasList[$aliasName]['height'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'alternative_text', $aliasList[$aliasName]['alternative_text'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'alias_key', $imageManager->createImageAliasKey( $imageManager->alias( $aliasName ) ) ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'timestamp', $aliasList[$aliasName]['timestamp'] ) );

        $filename = $aliasList[$aliasName]['filename'];
        if ( $filename )
        {
            include_once( 'lib/ezutils/classes/ezmimetype.php' );
            $mimeData =& eZMimeType::findByFileContents( $filename );

            $imageManager->analyzeImage( $mimeData );

            $this->createImageInformationNode( $imageNode, $mimeData );
        }

        foreach ( array_keys( $aliasList ) as $aliasName )
        {
            if ( $aliasName == 'original' )
                continue;
            $imageAlias =& $aliasList[$aliasName];
            $this->addImageAliasToXML( $doc, $imageAlias );
        }

        $this->setDOMTree( $doc );
    }

    /*!
     Sets the uploaded HTTP file object to \a $httpFile.
     This object is used to store information about the image file until the content object attribute is to be stored.
     \sa httpFile
    */
    function setHTTPFile( &$httpFile )
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();
        $contentObjectAttribute->DataTypeCustom['http_file'] =& $httpFile;
    }

    /*!
     \return the stored HTTP file object or \c false if no object is previously stored.
     \sa setHTTPFile
    */
    function &httpFile( $release = false )
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( is_array( $contentObjectAttribute->DataTypeCustom ) and
             isset( $contentObjectAttribute->DataTypeCustom['http_file'] ) )
        {
            $httpFile =& $contentObjectAttribute->DataTypeCustom['http_file'];
            if ( $release )
                unset( $contentObjectAttribute->DataTypeCustom['http_file'] );
            return $httpFile;
        }
        return false;
    }

    /*!
     \return the DOM tree for the current content object attribute.
     \note It will cache the result in the DataTypeCustom member variable of the
           content object attribute in the 'dom_tree' key.
    */
    function &domTree()
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();
        if ( isset( $contentObjectAttribute->DataTypeCustom['dom_tree'] ) )
            return $contentObjectAttribute->DataTypeCustom['dom_tree'];

        $xml = new eZXML();
        $xmlString =& $contentObjectAttribute->attribute( 'data_text' );
        $domTree =& $xml->domTree( $xmlString );
        if ( $domTree == false )
        {
            $this->generateXMLData();
            $domTree =& $xml->domTree( $xmlString );
        }
//         if ( $domTree == false )
//         {
//             $domTree = new eZDOMNode();
//         }
        $contentObjectAttribute->DataTypeCustom['dom_tree'] =& $domTree;

        return $domTree;
    }

    function &aliasList()
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( isset( $contentObjectAttribute->DataTypeCustom['alias_list'] ) )
        {
            $aliasList =& $contentObjectAttribute->DataTypeCustom['alias_list'];
            return $aliasList;
        }
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();

        $xml = new eZXML();
        $xmlString =& $contentObjectAttribute->attribute( 'data_text' );
        $domTree =& $xml->domTree( $xmlString );

        if ( $domTree == false )
        {
            $this->generateXMLData();
            $domTree =& $xml->domTree( $xmlString );
        }

        $contentObjectAttribute->DataTypeCustom['dom_tree'] =& $domTree;

        $imageNodeArray =& $domTree->elementsByName( "ezimage" );
        $imageInfoNodeArray =& $domTree->elementsByName( "information" );
        $imageVariationNodeArray =& $domTree->elementsByName( "alias" );
        $imageOriginalArray =& $domTree->elementsByName( "original" );

        $aliasList = array();

        $aliasEntry = array();

        $alternativeText = $imageNodeArray[0]->attributeValue( 'alternative_text' );
        $originalFilename = $imageNodeArray[0]->attributeValue( 'original_filename' );
        $basename = $imageNodeArray[0]->attributeValue( 'basename' );
        $displayText = $this->displayText( $alternativeText );

        if ( isset( $imageOriginalArray[0] ) )
        {
            $originalData = array( 'attribute_id' => $imageOriginalArray[0]->attributeValue( 'attribute_id' ),
                                   'attribute_version' => $imageOriginalArray[0]->attributeValue( 'attribute_version' ),
                                   'attribute_language' => $imageOriginalArray[0]->attributeValue( 'attribute_language' ) );
//                                    'has_file_copy' => $imageOriginalArray[0]->attributeValue( 'has_file_copy' ) );
            $this->setOriginalAttributeData( $originalData );
        }

        $aliasEntry['name'] = 'original';
        $aliasEntry['width'] = $imageNodeArray[0]->attributeValue( 'width' );
        $aliasEntry['height'] = $imageNodeArray[0]->attributeValue( 'height' );
        $aliasEntry['mime_type'] = $imageNodeArray[0]->attributeValue( 'mime_type' );
        $aliasEntry['filename'] = $imageNodeArray[0]->attributeValue( 'filename' );
        $aliasEntry['suffix'] = $imageNodeArray[0]->attributeValue( 'suffix' );
        $aliasEntry['dirpath'] = $imageNodeArray[0]->attributeValue( 'dirpath' );
        $aliasEntry['basename'] = $basename;
        $aliasEntry['alternative_text'] = $alternativeText;
        $aliasEntry['text'] = $displayText;
        $aliasEntry['original_filename'] = $originalFilename;
        $aliasEntry['url'] = $imageNodeArray[0]->attributeValue( 'url' );
        $aliasEntry['alias_key'] = $imageNodeArray[0]->attributeValue( 'alias_key' );
        $aliasEntry['timestamp'] = $imageNodeArray[0]->attributeValue( 'timestamp' );
        $aliasEntry['full_path'] =& $aliasEntry['url'];
        $aliasEntry['is_valid'] = $imageNodeArray[0]->attributeValue( 'is_valid' );
        $aliasEntry['is_new'] = false;

        $imageInformation = false;
        if ( count( $imageInfoNodeArray ) > 0 )
        {
            $imageInfoNode =& $imageInfoNodeArray[0];
            $this->parseInformationNode( $imageInfoNode, $imageInformation );
        }
        $aliasEntry['info'] =& $imageInformation;

        $serialNumber = $imageNodeArray[0]->attributeValue( 'serial_number' );
        if ( $serialNumber )
            $this->setImageSerialNumber( $serialNumber );

        $aliasList['original'] = $aliasEntry;

        if ( is_array( $imageVariationNodeArray ) )
        {
            foreach ( $imageVariationNodeArray as $imageVariation )
            {
                $aliasEntry = array();
                $aliasEntry['name'] = $imageVariation->attributeValue( 'name' );
                $aliasEntry['width'] = $imageVariation->attributeValue( 'width' );
                $aliasEntry['height'] = $imageVariation->attributeValue( 'height' );
                $aliasEntry['mime_type'] = $imageVariation->attributeValue( 'mime_type' );
                $aliasEntry['filename'] = $imageVariation->attributeValue( 'filename' );
                $aliasEntry['suffix'] = $imageVariation->attributeValue( 'suffix' );
                $aliasEntry['dirpath'] = $imageVariation->attributeValue( 'dirpath' );
                $aliasEntry['alias_key'] = $imageVariation->attributeValue( 'alias_key' );
                $aliasEntry['timestamp'] = $imageVariation->attributeValue( 'timestamp' );
                $aliasEntry['basename'] = $basename;
                $aliasEntry['alternative_text'] = $alternativeText;
                $aliasEntry['text'] = $displayText;
                $aliasEntry['original_filename'] = $originalFilename;
                $aliasEntry['url'] = $imageVariation->attributeValue( 'url' );
                $aliasEntry['full_path'] =& $aliasEntry['url'];
                $aliasEntry['is_new'] = false;
                $aliasEntry['is_valid'] = $imageVariation->attributeValue( 'is_valid' );
                $aliasEntry['info'] =& $imageInformation;

                include_once( 'kernel/common/image.php' );
                $imageManager =& imageInit();
                if ( $imageManager->isImageAliasValid( $aliasEntry ) )
                {
                    $aliasList[$aliasEntry['name']] = $aliasEntry;
                }
            }
        }
        $contentObjectAttribute->DataTypeCustom['alias_list'] =& $aliasList;
        return $aliasList;
    }

    function parseInformationNode( &$imageInfoNode, &$imageInformation )
    {
        $imageInformation = array();
        $attributes =& $imageInfoNode->attributes();
        foreach ( $attributes as $attribute )
        {
            $imageInformation[$attribute->name()] = $attribute->content();
        }
        $children =& $imageInfoNode->children();
        foreach ( $children as $child )
        {
            if ( $child->name() == 'array' )
            {
                $name = $child->attributeValue( 'name' );
                $items = $child->elementsByName( 'item' );
                $array = array();
                foreach ( $items as $item )
                {
                    $array[$item->attributeValue( 'key' )] = $item->textContent();
                }
                ksort( $array );
                $imageInformation[$name] = $array;
            }
            else if ( $child->name() == 'serialized' )
            {
                $name = $child->attributeValue( 'name' );
                $data = $child->attributeValue( 'data' );
                $imageInformation[$name] = unserialize( $data );
            }
        }
    }

    function imageName( &$contentObjectAttribute, &$contentVersion )
    {
        $objectName = $contentVersion->attribute( 'version_name' );
        if ( !$objectName )
        {
            $objectName = $contentVersion->attribute( 'name' );
            if ( !$objectName )
            {
                $objectName = $this->attribute( 'alternative_text' );
                if ( !$objectName )
                {
                    $objectName = ezi18n( 'kernel/classes/datatypes', 'image', 'Default image name' );
                }
            }
        }
        $objectName = eZImageAliasHandler::normalizeImageName( $objectName );
        $objectName .= $this->imageSerialNumber();
        return $objectName;
    }

    function imageNameByNode( &$contentObjectAttribute, &$mainNode )
    {
        $objectName = $mainNode->attribute( 'name' );
        if ( !$objectName )
        {
            $objectName = $this->attribute( 'alternative_text' );
            if ( !$objectName )
            {
                $objectName = ezi18n( 'kernel/classes/datatypes', 'image', 'Default image name' );
            }
        }
        $objectName = eZImageAliasHandler::normalizeImageName( $objectName );
//         $objectName .= $this->imageSerialNumber();
        return $objectName;
    }

    function normalizeImageName( $imageName )
    {
        $imageName = strtolower( $imageName );
        $imageName = preg_replace( array( "#[^a-z0-9_ ]#" ,
                                          "/ /",
                                          "/__+/",
                                          "/^_|_$/" ),
                                   array( "",
                                          "_",
                                          "_",
                                          "" ),
                                   $imageName );
        return $imageName;
    }

    function imagePath( &$contentObjectAttribute, &$contentVersion, $isImageOwner = null )
    {
        $useVersion = false;
        if ( $isImageOwner === null )
            $isImageOwner = $this->isImageOwner();
        if ( $contentVersion->attribute( 'status' ) == EZ_VERSION_STATUS_PUBLISHED or
             !$isImageOwner )
        {
            $contentObject =& $contentVersion->attribute( 'contentobject' );
            $mainNode =& $contentObject->attribute( 'main_node' );
            if ( !$mainNode )
            {
                $ini =& eZINI::instance( 'image.ini' );
                $contentImageSubtree = $ini->variable( 'FileSettings', 'VersionedImages' );
                $pathString = $contentImageSubtree;
                $useVersion = true;
            }
            else
            {
                $ini =& eZINI::instance( 'image.ini' );
                $contentImageSubtree = $ini->variable( 'FileSettings', 'PublishedImages' );
                $pathString = $contentImageSubtree . '/' . $mainNode->attribute( 'path_identification_string' );
            }
        }
        else
        {
            $ini =& eZINI::instance( 'image.ini' );
            $contentImageSubtree = $ini->variable( 'FileSettings', 'VersionedImages' );
            $pathString = $contentImageSubtree;
            $useVersion = true;
        }
        if ( $useVersion )
            $identifierString = $contentObjectAttribute->attribute( 'id' ) . '/' . $contentObjectAttribute->attribute( 'version' ) . '-' . $contentObjectAttribute->attribute( 'language_code' );
        else
            $identifierString = $contentObjectAttribute->attribute( 'id' ) . '-' . $contentObjectAttribute->attribute( 'version' ) . '-' . $contentObjectAttribute->attribute( 'language_code' );
        $imagePath = eZSys::storageDirectory() . '/' . $pathString . '/' . $identifierString;
        return $imagePath;
    }

    function imagePathByNode( &$contentObjectAttribute, &$mainNode )
    {
        $pathString = $mainNode->attribute( 'path_identification_string' );
        $ini =& eZINI::instance( 'image.ini' );
        $contentImageSubtree = $ini->variable( 'FileSettings', 'PublishedImages' );
        $imagePath = eZSys::storageDirectory() . '/' . $contentImageSubtree . '/' . $pathString . '/' . $contentObjectAttribute->attribute( 'id' ) . '-' . $contentObjectAttribute->attribute( 'version' ) . '-' . $contentObjectAttribute->attribute( 'language_code' );
        return $imagePath;
    }

    /*!
     Fetches image information from the old 3.2 image system and creates new information.
    */
    function generateXMLData()
    {
        include_once( "lib/ezdb/classes/ezdb.php" );

        $db =& eZDB::instance();

        $contentObjectAttribute =& $this->ContentObjectAttribute;
        $attributeID = $contentObjectAttribute->attribute( 'id' );
        $attributeVersion = $contentObjectAttribute->attribute( 'version' );

        if ( is_numeric( $attributeID ) )
        {
            $imageRow =& $db->arrayQuery( "SELECT * FROM ezimage
                                           WHERE contentobject_attribute_id=$attributeID AND
                                                 version=$attributeVersion" );
        }

        $doc = new eZDOMDocument();
        $imageNode =& $doc->createElementNode( "ezimage" );
        $doc->setRoot( $imageNode );

        $isValid = false;
        $fileName = false;
        $suffix = false;
        $baseName = false;
        $dirPath = false;
        $filePath = false;
        $originalFileName = false;
        $mimeType = false;
        $width = false;
        $height = false;
        $altText = false;

        include_once( 'lib/ezutils/classes/ezmimetype.php' );
        if ( count( $imageRow ) == 1 )
        {
            $fileName = $imageRow[0]['filename'];
            $originalFileName = $imageRow[0]['original_filename'];
            $mimeType = $imageRow[0]['mime_type'];
            $altText = $imageRow[0]['alternative_text'];

            $dirPath = eZSys::storageDirectory() . '/original/image';
            $filePath = $dirPath . '/' . $fileName;
            $baseName = $fileName;
            $dotPosition = strrpos( $fileName, '.' );
            if ( $dotPosition !== false )
            {
                $baseName = substr( $fileName, 0, $dotPosition );
                $suffix = substr( $fileName, $dotPosition + 1 );
            }

            $width = false;
            $height = false;
            if ( !file_exists( $filePath ) )
            {
                $referenceDirPath = eZSys::storageDirectory() . '/reference/image';
                $suffixList = array( 'jpg', 'png', 'gif' );
                foreach ( $suffixList as $suffix )
                {
                    $referenceFilePath = $referenceDirPath . '/' . $baseName . '.' . $suffix;
                    if ( file_exists( $referenceFilePath ) )
                    {
                        $filePath = $referenceFilePath;
                        $dirPath = $referenceDirPath;
                        break;
                    }
                }
            }

            if ( file_exists( $filePath ) )
            {
                $isValid = true;
                $info = getimagesize( $filePath );
                if ( $info )
                {
                    $width = $info[0];
                    $height = $info[1];
                }
                $mimeInfo = eZMimeType::findByFileContents( $filePath );
                $mimeType = $mimeInfo['name'];

                $newFilePath = $filePath;
                $newSuffix = $suffix;
                $contentVersion =& eZContentObjectVersion::fetchVersion( $contentObjectAttribute->attribute( 'version' ),
                                                                         $contentObjectAttribute->attribute( 'contentobject_id' ) );
                if ( $contentVersion )
                {
                    $objectName = $this->imageName( $contentObjectAttribute, $contentVersion );
                    $objectPathString = $this->imagePath( $contentObjectAttribute, $contentVersion );

                    $newDirPath =  $objectPathString;
                    $newFileName = $objectName . '.' . $mimeInfo['suffix'];
                    $newSuffix = $mimeInfo['suffix'];
                    $newFilePath = $newDirPath . '/' . $newFileName;
                    $newBaseName = $objectName;
                }

                if ( $newFilePath != $filePath )
                {
                    if ( !file_exists( $newDirPath ) )
                    {
                        include_once( 'lib/ezfile/classes/ezdir.php' );
                        eZDir::mkdir( $newDirPath, eZDir::directoryPermission(), true );
                    }
                    eZFileHandler::copy( $filePath, $newFilePath );
                    $filePath = $newFilePath;
                    $fileName = $newFileName;
                    $suffix = $newSuffix;
                    $dirPath = $newDirPath;
                    $baseName = $newBaseName;
                }
            }

        /*
        // Fetch variations
        $imageVariationRowArray =& $db->arrayQuery( "SELECT * FROM ezimagevariation
                                           WHERE contentobject_attribute_id=$attributeID AND
                                                 version=$attributeVersion" );

        foreach ( $imageVariationRowArray as $variationRow )
        {
            unset( $imageVariationNode );
            $imageVariationNode =& $doc->createElementNode( "variation" );
            $imageNode->appendChild( $imageVariationNode );

            $imageVariationNode->appendAttribute( $doc->createAttributeNode( 'name', 'medium' ) );

            $imageVariationNode->appendAttribute( $doc->createAttributeNode( 'filename', $variationRow['filename'] ) );
            $imageVariationNode->appendAttribute( $doc->createAttributeNode( 'additional_path', $variationRow['additional_path'] ) );
            $imageVariationNode->appendAttribute( $doc->createAttributeNode( 'width', $variationRow['width'] ) );
            $imageVariationNode->appendAttribute( $doc->createAttributeNode( 'height', $variationRow['height'] ) );

        }
        */
        }
        include_once( 'kernel/common/image.php' );
        $imageManager =& imageInit();

        $mimeData = eZMimeType::findByFileContents( $fileName );

        $imageManager->analyzeImage( $mimeData );

        $imageNode->appendAttribute( $doc->createAttributeNode( 'serial_number', false ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'is_valid', $isValid ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'filename', $fileName ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'suffix', $suffix ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'basename', $baseName ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'dirpath', $dirPath ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'url', $filePath ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'original_filename', $originalFileName ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'mime_type', $mimeType ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'width', $width ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'height', $height ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'alternative_text', $altText ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'alias_key', $imageManager->createImageAliasKey( $imageManager->alias( 'original' ) ) ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'timestamp', time() ) );

        $this->createImageInformationNode( $imageNode, $mimeData );

        $this->storeDOMTree( $doc );

        eZImageFile::appendFilepath( $contentObjectAttribute->attribute( 'id' ), $filePath );
    }

    /*!
     Initializes the content object attribute \a $contentObjectAttribute with the uploaded HTTP file \a $httpFile.
     Optionally you may also specify the alternative text in the parameter \a $imageAltText.
    */
    function initializeFromHTTPFile( &$httpFile, $imageAltText = false )
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        $this->increaseImageSerialNumber();

        include_once( 'lib/ezutils/classes/ezmimetype.php' );
        $mimeData = eZMimeType::findByFileContents( $httpFile->attribute( 'filename' ) );
        if ( !$mimeData['is_valid'] )
        {
            $mimeData = eZMimeType::findByName( $httpFile->attribute( 'mime_type' ) );
            if ( !$mimeData['is_valid'] )
            {
                $mimeData = eZMimeType::findByURL( $httpFile->attribute( 'original_filename' ) );
            }
        }
        $contentVersion =& eZContentObjectVersion::fetchVersion( $contentObjectAttribute->attribute( 'version' ),
                                                                 $contentObjectAttribute->attribute( 'contentobject_id' ) );
        $objectName = $this->imageName( $contentObjectAttribute, $contentVersion );
        $objectPathString = $this->imagePath( $contentObjectAttribute, $contentVersion, true );

        eZMimeType::changeBaseName( $mimeData, $objectName );
        eZMimeType::changeDirectoryPath( $mimeData, $objectPathString );

        $this->removeAliases();

        $httpFile->store( false, false, $mimeData );

        $originalFilename = $httpFile->attribute( 'original_filename' );
        return $this->initialize( $mimeData, $originalFilename, $imageAltText );
    }

    /*!
     Initializes the content object attribute \a $contentObjectAttribute with the filename \a $filename.
     Optionally you may also specify the alternative text in the parameter \a $imageAltText.
     \sa initialize
    */
    function initializeFromFile( $filename, $imageAltText = false, $originalFilename = false )
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( !file_exists( $filename ) )
        {
            eZDebug::writeError( "The image $filename does not exists, cannot initialize image attribute with it",
                                 'eZImageAliasHandler::initializeFromFile' );
            return false;
        }

        $this->increaseImageSerialNumber();

        if ( !$originalFilename )
            $originalFilename = $filename;
        include_once( 'lib/ezutils/classes/ezmimetype.php' );
        $mimeData = eZMimeType::findByFileContents( $originalFilename );
        $contentVersion =& eZContentObjectVersion::fetchVersion( $contentObjectAttribute->attribute( 'version' ),
                                                                 $contentObjectAttribute->attribute( 'contentobject_id' ) );
        $objectName = $this->imageName( $contentObjectAttribute, $contentVersion );
        $objectPathString = $this->imagePath( $contentObjectAttribute, $contentVersion, true );

        eZMimeType::changeBaseName( $mimeData, $objectName );
        eZDebug::writeDebug( $mimeData, 'mimeData' );
        eZMimeType::changeDirectoryPath( $mimeData, $objectPathString );
        eZDebug::writeDebug( $mimeData, 'mimeData' );
        if ( !file_exists( $mimeData['dirpath'] ) )
        {
            eZDir::mkdir( $mimeData['dirpath'], false, true );
        }
        eZFileHandler::copy( $filename, $mimeData['url'] );
        $this->removeAliases();

        return $this->initialize( $mimeData, $filename, $imageAltText );
    }

    /*!
     Makes sure the attribute contains the image file mentioned in \a $mimeData.
     This involves removing any previous image (and image aliases), increasing
     the image name counter, figuring out the image size and creating
     the internal XML structure.
     \return \c true on success.
    */
    function initialize( $mimeData, $originalFilename, $imageAltText = false )
    {
        include_once( 'kernel/common/image.php' );
        $imageManager =& imageInit();

        $aliasList = array( 'original' => $mimeData );
        $aliasList['original']['alternative_text'] = $imageAltText;
        if ( $imageManager->createImageAlias( 'original', $aliasList, array( 'basename' => $mimeData['basename'] ) ) )
        {
            $mimeData = $aliasList['original'];
            $mimeData['name'] = $mimeData['mime_type'];
        }

        $imageManager->analyzeImage( $mimeData );

        $doc = new eZDOMDocument();
        $imageNode =& $doc->createElementNode( "ezimage" );
        $doc->setRoot( $imageNode );

        $width = false;
        $height = false;
        $info = getimagesize( $mimeData['url'] );
        if ( $info )
        {
            $width = $info[0];
            $height = $info[1];
        }

        $this->setOriginalAttributeDataValues( false, false, false );

        $originalNode =& $doc->createElementNode( "original" );
        $imageNode->appendChild( $originalNode );
        $this->createOriginalAttributeXMLData( $originalNode, $this->originalAttributeData() );

        $imageNode->appendAttribute( $doc->createAttributeNode( 'serial_number', $this->imageSerialNumber() ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'is_valid', true ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'filename', $mimeData['filename'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'suffix', $mimeData['suffix'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'basename', $mimeData['basename'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'dirpath', $mimeData['dirpath'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'url', $mimeData['url'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'original_filename', $originalFilename ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'mime_type', $mimeData['name'] ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'width', $width ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'height', $height ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'alternative_text', $imageAltText ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'alias_key', $imageManager->createImageAliasKey( $imageManager->alias( 'original' ) ) ) );
        $imageNode->appendAttribute( $doc->createAttributeNode( 'timestamp', time() ) );

        $this->createImageInformationNode( $imageNode, $mimeData );

        $this->setDOMTree( $doc );

        eZImageFile::appendFilepath( $this->ContentObjectAttribute->attribute( 'id' ), $mimeData['url'] );
        return true;
    }

    function createImageInformationNode( &$imageNode, &$mimeData )
    {
        if ( isset( $mimeData['info'] ) and
             $mimeData['info'] )
        {
            $imageInfoNode =& eZDOMDocument::createElementNode( 'information' );
            $info = $mimeData['info'];
            foreach ( $info as $infoItemName => $infoItem )
            {
                if ( is_array( $infoItem ) )
                {
                    $hasScalarValues = true;
                    foreach ( $infoItem as $infoArrayItem )
                    {
                        if ( is_array( $infoArrayItem ) )
                        {
                            $hasScalarValues = false;
                            break;
                        }
                    }
                    if ( !$hasScalarValues )
                    {
                        $serializedNode =& eZDOMDocument::createElementNode( 'serialized',
                                                                             array( 'name' => $infoItemName,
                                                                                    'data' => serialize( $infoItem ) ) );
                        $imageInfoNode->appendChild( $serializedNode );
                    }
                    else
                    {
                        $arrayNode =& eZDOMDocument::createElementNode( 'array',
                                                                        array( 'name' => $infoItemName ) );
                        $imageInfoNode->appendChild( $arrayNode );
                        foreach ( $infoItem as $infoArrayKey => $infoArrayItem )
                        {
                            $arrayItemNode =& eZDOMDocument::createElementNode( 'item',
                                                                                array( 'key' => $infoArrayKey ) );
                            $arrayItemNode->appendChild( eZDOMDocument::createTextNode( $infoArrayItem ) );
                            $arrayNode->appendChild( $arrayItemNode );
                        }
                    }
                }
                else
                {
                    $imageInfoNode->appendAttribute( eZDOMDocument::createAttributeNode( $infoItemName, $infoItem ) );
                }
            }
            $imageNode->appendChild( $imageInfoNode );
        }
    }

    /*!
     Adds all the new image alias structures in \a $imageAliasList to the content object attribute.
    */
    function addImageAliases( &$imageAliasList )
    {
        $domTree =& $this->domTree();
        foreach ( array_keys( $imageAliasList ) as $imageAliasName )
        {
            $imageAlias =& $imageAliasList[$imageAliasName];
            if ( $imageAlias['is_new'] )
            {
                $this->addImageAliasToXML( $domTree, $imageAlias );
                $imageAlias['is_new'] = false;
            }
        }
        $this->storeDOMTree( $domTree );
    }

    /*!
     Adds the image alias structure \a $imageAlias to the content object attribute.
    */
    function addImageAlias( $imageAlias )
    {
        $domTree =& $this->domTree();
        $this->addImageAliasToXML( $domTree, $imageAlias );
        $this->storeDOMTree( $domTree );
    }

    /*!
     Adds the image alias structure \a $imageAlias to the XML DOM document \a $domTree.
    */
    function addImageAliasToXML( &$domTree, $imageAlias )
    {
        $imageVariationNodeArray =& $domTree->elementsByName( 'alias' );
        $imageNode = false;
        if ( is_array( $imageVariationNodeArray ) )
        {
            foreach ( array_keys( $imageVariationNodeArray ) as $imageVariationKey )
            {
                $imageVariation =& $imageVariationNodeArray[$imageVariationKey];
                $aliasEntryName = $imageVariation->attributeValue( 'name' );
                if ( $aliasEntryName == $imageAlias['name'] )
                {
                    $imageNode =& $imageVariation;
                    break;
                }
            }
        }
        if ( !$imageNode )
        {
            $rootNode =& $domTree->root();
            $imageNode =& $domTree->createElementNode( "alias" );
            $rootNode->appendChild( $imageNode );
        }
        else
        {
            $imageNode->removeNamedAttribute( 'name' );
            $imageNode->removeNamedAttribute( 'filename' );
            $imageNode->removeNamedAttribute( 'suffix' );
            $imageNode->removeNamedAttribute( 'dirpath' );
            $imageNode->removeNamedAttribute( 'url' );
            $imageNode->removeNamedAttribute( 'mime_type' );
            $imageNode->removeNamedAttribute( 'width' );
            $imageNode->removeNamedAttribute( 'height' );
            $imageNode->removeNamedAttribute( 'alias_key' );
            $imageNode->removeNamedAttribute( 'timestamp' );
            $imageNode->removeNamedAttribute( 'is_valid' );
        }
        $imageNode->appendAttribute( $domTree->createAttributeNode( 'name', $imageAlias['name']) );
        $imageNode->appendAttribute( $domTree->createAttributeNode( 'filename', $imageAlias['filename']) );
        $imageNode->appendAttribute( $domTree->createAttributeNode( 'suffix', $imageAlias['suffix']) );
        $imageNode->appendAttribute( $domTree->createAttributeNode( 'dirpath', $imageAlias['dirpath'] ) );
        $imageNode->appendAttribute( $domTree->createAttributeNode( 'url', $imageAlias['url'] ) );
        $imageNode->appendAttribute( $domTree->createAttributeNode( 'mime_type', $imageAlias['mime_type'] ) );
        $imageNode->appendAttribute( $domTree->createAttributeNode( 'width', $imageAlias['width'] ) );
        $imageNode->appendAttribute( $domTree->createAttributeNode( 'height', $imageAlias['height'] ) );
        $imageNode->appendAttribute( $domTree->createAttributeNode( 'alias_key', $imageAlias['alias_key']) );
        $imageNode->appendAttribute( $domTree->createAttributeNode( 'timestamp', $imageAlias['timestamp']) );
        $imageNode->appendAttribute( $domTree->createAttributeNode( 'is_valid', $imageAlias['is_valid']) );
    }

    /*!
     Sets the XML DOM document \a $domTree as the current DOM document.
    */
    function setDOMTree( &$domTree )
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();
        $contentObjectAttribute->DataTypeCustom['dom_tree'] =& $domTree;
        $contentObjectAttribute->DataTypeCustom['is_storage_required'] = true;
    }

    /*!
     Stores the XML DOM document \a $domTree to the content object attribute.
    */
    function storeDOMTree( &$domTree, $storeAttribute = true )
    {
        if ( !$domTree )
            return false;
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();
        $contentObjectAttribute->DataTypeCustom['dom_tree'] =& $domTree;
        $contentObjectAttribute->DataTypeCustom['is_storage_required'] = false;

        $xmlString = $domTree->toString();
        $contentObjectAttribute->setAttribute( 'data_text', $xmlString );
        if ( $storeAttribute )
            $contentObjectAttribute->storeData();
        return true;
    }

    /*!
     Stores the data in the image alias handler to the content object attribute.
     \sa isStorageRequired, setStorageRequired
    */
    function store()
    {
        $domTree =& $this->domTree();
        if ( $domTree )
            $this->storeDOMTree( $domTree, true );
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();
        $contentObjectAttribute->DataTypeCustom['is_storage_required'] = false;
    }

    /*!
     \return \c true if the image alias handler is required to store it's contents.
     \sa setStorageRequired, store
    */
    function isStorageRequired()
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( is_array( $contentObjectAttribute->DataTypeCustom ) and
             isset( $contentObjectAttribute->DataTypeCustom['is_storage_required'] ) )
            return $contentObjectAttribute->DataTypeCustom['is_storage_required'];
        return false;
    }

    /*!
     Sets whether storage of the image alias data is required or not.
     \sa isStorageRequired, store
    */
    function setStorageRequired( $require = true )
    {
        $contentObjectAttribute =& $this->ContentObjectAttribute;
        if ( !is_array( $contentObjectAttribute->DataTypeCustom ) )
            $contentObjectAttribute->DataTypeCustom = array();
        $contentObjectAttribute->DataTypeCustom['is_storage_required'] = $require;
    }

    /// \privatesection
    /// Contains a reference to the object attribute
    var $ContentObjectAttribute;
}
?>
