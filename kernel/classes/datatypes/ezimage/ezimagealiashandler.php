<?php
//
// Definition of eZImageAliasHandler class
//
// Created on: <16-Oct-2003 09:34:25 bf>
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
  \class eZImageAliasHandler ezimagealiashandler.php
  \ingroup eZDatatype
  \brief Internal manager for the eZImage datatype

  Takes care of image conversion and serialization from and to
  the internal XML format.

  \note This handler was introduced in eZ Publish 3.3 and will detect older
        eZImage structures and convert them on the fly.

  \note The XML storage was improved in 3.8, from then it always stores the
        attribute ID, version and language in the <original> tag.
        This was required to get the new multi-language features to work.
*/

class eZImageAliasHandler
{
    /*!
     Creates the handler and creates a reference to the contentobject attribute that created it.
    */
    function eZImageAliasHandler( $contentObjectAttribute )
    {
        $this->ContentObjectAttributeData = array();
        if ( is_object( $contentObjectAttribute ) )
        {
            $this->ContentObjectAttributeData['id'] = $contentObjectAttribute->attribute( 'id' );
            $this->ContentObjectAttributeData['contentobject_id'] = $contentObjectAttribute->attribute( 'contentobject_id' );
            $this->ContentObjectAttributeData['version'] = $contentObjectAttribute->attribute( 'version' );
            $this->ContentObjectAttributeData['language_code'] = $contentObjectAttribute->attribute( 'language_code' );
            $this->ContentObjectAttributeData['can_translate'] = $contentObjectAttribute->attribute( 'can_translate' );
            $this->ContentObjectAttributeData['data_text'] = $contentObjectAttribute->attribute( 'data_text' );
            $this->ContentObjectAttributeData['DataTypeCustom'] = $contentObjectAttribute->DataTypeCustom;
            if ( !is_array( $this->ContentObjectAttributeData['DataTypeCustom'] ) )
            {
                $this->ContentObjectAttributeData['DataTypeCustom'] = array();
            }
        }
        else
        {
            eZDebug::writeWarning( 'Invalid eZContentObjectAttribute', 'eZImageAliasHandler::eZImageAliasHandler' );
        }
    }

    /*!
     Lists all available image aliases as attributes as well as:
     - alternative_text - The alternative text input by the user, can be empty
     - original_filename - The name of image which it had on the users disk before it was uploaded
     - is_valid - A boolean which says if there is an image here or not.
    */
    function attributes()
    {
        require_once( 'kernel/common/image.php' );
        $imageManager = imageInit();
        $aliasList = $imageManager->aliasList();
        return array_merge( array( 'alternative_text',
                                   'original_filename',
                                   'is_valid' ),
                            array_keys( $aliasList ) );
    }

    /*!
     \return true if the attribute named \a $attributeName exists.
     See eZImageAliasHandler::attributes() for which attributes are available.
    */
    function hasAttribute( $attributeName )
    {
        if ( in_array( $attributeName,
                       array( 'alternative_text',
                              'original_filename',
                              'is_valid' ) ) )
            return true;
        require_once( 'kernel/common/image.php' );
        $imageManager = imageInit();
        if ( $imageManager->hasAlias( $attributeName ) )
            return true;
        return false;
    }

    /*!
     \return the value of the attribute named \a $attributeName.
     See eZImageAliasHandler::attributes() for which attributes are available.
    */
    function attribute( $attributeName )
    {
        if ( in_array( $attributeName,
                       array( 'alternative_text',
                              'original_filename',
                              'is_valid' ) ) )
        {
            return $this->attributeFromOriginal( $attributeName );
        }
        $aliasName = $attributeName;
        return $this->imageAlias( $aliasName );
    }

    /*!
     \return The value of the attribute named \a $attributeName from the 'original' image alias.

     This is a quick way for extracting information from the 'original' image alias.
    */
    function &attributeFromOriginal( $attributeName )
    {
        $originalAlias = $this->attribute( 'original' );
        if ( $originalAlias )
            return $originalAlias[$attributeName];
        $retValue = null;
        return $retValue;
    }

    /*!
     Sets the attribute named \a $attributeName to have the value \a $attributeValue.

     The following attributes can be set:
     - alternative text
     - original_filename
    */
    function setAttribute( $attributeName, $attributeValue )
    {
        if ( in_array( $attributeName,
                       array( 'alternative_text',
                              'original_filename' ) ) )
        {
            $aliasList = $this->aliasList();
            foreach ( array_keys( $aliasList ) as $aliasName )
            {
                $this->setAliasAttribute( $aliasName, $attributeName, $attributeValue );
            }
            if ( $attributeName == 'alternative_text' )
            {
                $text = $this->displayText( $attributeValue );
                foreach ( array_keys( $aliasList ) as $aliasName )
                {
                    $this->setAliasAttribute( $aliasName, 'text', $text );
                }
            }
            $this->recreateDOMTree();
            $this->setStorageRequired();
            return true;
        }
        return false;
    }

    /*!
     \return \c true if this is considered to be owner of the image.

     It will be considered an owner if attribute data is not a copy
     of another attribute. For instance each time a new image is uploaded
     this will return \c true.
    */
    function isImageOwner()
    {
        $originalData = $this->originalAttributeData();
        return ( $originalData['attribute_id'] == false ||
                 ( $originalData['attribute_id'] == $this->ContentObjectAttributeData['id'] &&
                   $originalData['attribute_version'] == $this->ContentObjectAttributeData['version'] &&
                   $originalData['attribute_language'] == $this->ContentObjectAttributeData['language_code'] ) );
    }

    /*!
     \return The current serial number, the value will be 1 or higher.

     The serial number is used to create unique filenames for uploaded images,
     it will be increased each time an image is uploaded.


     \note This was required to get around the problem where browsers
           caches image information, if two images were uploaded in one version (e.g. a draft)
           the browser would not load the new image since it thought it had not changed.
    */
    function imageSerialNumber()
    {
        $serialNumber = $this->imageSerialNumberRaw();
        if ( $serialNumber < 1 )
        {
            $serialNumber = 1;
        }
        return $serialNumber;
    }

    /*!
     Increases the serial by one.
    */
    function increaseImageSerialNumber()
    {
        $this->setImageSerialNumber( $this->imageSerialNumberRaw() + 1 );
    }

    /*!
     Resets the serial number to zero.
    */
    function resetImageSerialNumber()
    {
        $this->setImageSerialNumber( 0 );
    }

    /*!
     \return A text string which can be used as display for the image.

     The text string will either contain the alternative text from the attribute
     or the parameter \a $alternativeText if it is set.
    */
    function displayText( $alternativeText = null )
    {
        if ( $alternativeText === null )
            $text = $this->attribute( 'alternative_text' );
        else
            $text = $alternativeText;

        return $text;
    }

    /*!
     \return The full directory path to the image, this includes the var and storage directory.
    */
    function directoryPath()
    {
        $aliasList = $this->aliasList();
        if ( isset( $aliasList['original'] ) )
        {
            return $aliasList['original']['dirpath'];
        }
        return false;
    }

    /*!
     \return A normalized name for the image.

     The image name will generated from the name of the current version.
     If this is empty it will use the object name or the alternative text.

     This ensures that the image has a name which corresponds to the object it belongs to.

     The normalization ensures that the name only contains filename and URL friendly characters.
    */
    function imageName( $contentObjectAttribute, $contentVersion, $language = false )
    {
        if ( $language === false )
        {
            if ( is_object( $contentObjectAttribute ) ) // for backward compatibility when eZImageAliasHandler used $this->contentObjectAttribute
            {
                $language = $contentObjectAttribute->attribute( 'language_code' );
            }
            else
            {
                $language = $contentObjectAttribute['language_code'];
            }
        }
        $objectName = $contentVersion->versionName( $language );
        if ( !$objectName )
        {
            $objectName = $contentVersion->name( $language );
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

    /*!
     \return A normalized name for the image based on a node.

     Similar to \a imageName() but fetches name information from the node \a $mainNode.

     The normalization ensures that the name only contains filename and URL friendly characters.
    */
    function imageNameByNode( $contentObjectAttribute, $mainNode, $language = false )
    {
        if ( $language === false )
        {
            if ( is_object( $contentObjectAttribute ) ) // for backward compatibility when eZImageAliasHandler used $this->contentObjectAttribute
            {
                $language = $contentObjectAttribute->attribute( 'language_code' );
            }
            else
            {
                $language = $contentObjectAttribute['language_code'];
            }
        }
        $objectName = $mainNode->getName( $language );
        if ( !$objectName )
        {
            $objectName = $this->attribute( 'alternative_text' );
            if ( !$objectName )
            {
                $objectName = ezi18n( 'kernel/classes/datatypes', 'image', 'Default image name' );
            }
        }
        $objectName = eZImageAliasHandler::normalizeImageName( $objectName );
        return $objectName;
    }

    /*!
     \return The storage path for the image.

     The path is calculated by using information from the current object and version.
     If the object is in the node tree it will contain a path that matches the node path,
     if not it will be placed in the versioned storage repository.
    */
    function imagePath( $contentObjectAttribute, $contentVersion, $isImageOwner = null )
    {
        $useVersion = false;
        if ( $isImageOwner === null )
            $isImageOwner = $this->isImageOwner();
        if ( $contentVersion->attribute( 'status' ) == eZContentObjectVersion::STATUS_PUBLISHED or
             !$isImageOwner )
        {
            $contentObject = $contentVersion->attribute( 'contentobject' );
            $mainNode = $contentObject->attribute( 'main_node' );
            if ( !$mainNode )
            {
                $ini = eZINI::instance( 'image.ini' );
                $contentImageSubtree = $ini->variable( 'FileSettings', 'VersionedImages' );
                $pathString = $contentImageSubtree;
                $useVersion = true;
            }
            else
            {
                $ini = eZINI::instance( 'image.ini' );
                $contentImageSubtree = $ini->variable( 'FileSettings', 'PublishedImages' );
                $pathString = $mainNode->pathWithNames();
                $pathString = function_exists( 'mb_strtolower' ) ? mb_strtolower( $pathString ) : strtolower( $pathString );
                $pathString = $contentImageSubtree . '/' . $pathString;
            }
        }
        else
        {
            $ini = eZINI::instance( 'image.ini' );
            $contentImageSubtree = $ini->variable( 'FileSettings', 'VersionedImages' );
            $pathString = $contentImageSubtree;
            $useVersion = true;
        }
        $attributeData = $this->originalAttributeData();
        $attributeID = $attributeData['attribute_id'];
        $attributeVersion = $attributeData['attribute_version'];
        $attributeLanguage = $attributeData['attribute_language'];
        if ( $useVersion )
            $identifierString = $attributeID . '/' . $attributeVersion . '-' . $attributeLanguage;
        else
            $identifierString = $attributeID . '-' . $attributeVersion . '-' . $attributeLanguage;
        $imagePath = eZSys::storageDirectory() . '/' . $pathString . '/' . $identifierString;
        return $imagePath;
    }

    /*!
     \return The storage path for the image based on a node.

     Similar to \a imagePath() but fetches name information from the node \a $mainNode.
    */
    function imagePathByNode( $contentObjectAttribute, $mainNode )
    {
        $pathString = $mainNode->pathWithNames();
        $pathString = function_exists( 'mb_strtolower' ) ? mb_strtolower( $pathString ) : strtolower( $pathString );

        $ini = eZINI::instance( 'image.ini' );
        $contentImageSubtree = $ini->variable( 'FileSettings', 'PublishedImages' );
        $attributeData = $this->originalAttributeData();
        $attributeID = $attributeData['attribute_id'];
        $attributeVersion = $attributeData['attribute_version'];
        $attributeLanguage = $attributeData['attribute_language'];
        $pathParts = array( eZSys::storageDirectory(), $contentImageSubtree );
        if ( $pathString != '' )
        {
            $pathParts[] = $pathString;
        }
        $pathParts[] = $attributeID . '-' . $attributeVersion . '-' . $attributeLanguage;
        $imagePath = implode( '/', $pathParts );
        return $imagePath;
    }

    /*!
     \return The image alias structure for the alias named \a $aliasName.

     This will create the image alias if it does not exist yet, this can involve
     running image operations to for instance scale the image.
    */
    function imageAlias( $aliasName )
    {
        require_once( 'kernel/common/image.php' );
        $imageManager = imageInit();
        if ( !$imageManager->hasAlias( $aliasName ) )
        {
            return null;
        }

        $aliasList = $this->aliasList();
        if ( array_key_exists( $aliasName, $aliasList ) )
        {
            return $aliasList[$aliasName];
        }
        else
        {
            $imageManager = imageInit();
            if ( $imageManager->hasAlias( $aliasName ) )
            {
                $original = $aliasList['original'];
                $basename = $original['basename'];
                if ( $imageManager->createImageAlias( $aliasName, $aliasList,
                                                      array( 'basename' => $basename ) ) )
                {
                    $text = $this->displayText( $original['alternative_text'] );
                    $originalFilename = $original['original_filename'];
                    foreach ( $aliasList as $aliasKey => $alias )
                    {
                        $alias['original_filename'] = $originalFilename;
                        $alias['text'] = $text;
                        if ( $alias['url'] )
                        {
                            $aliasFile = eZClusterFileHandler::instance( $alias['url'] );
                            if( $aliasFile->exists() )
                                $alias['filesize'] = $aliasFile->size();
                        }
                        if ( $alias['is_new'] )
                        {
                            eZImageFile::appendFilepath( $this->ContentObjectAttributeData['id'], $alias['url'] );
                        }
                        $aliasList[$aliasKey] = $alias;
                    }
                    $this->setAliasList( $aliasList );
                    $this->addImageAliases( $aliasList );
                    $aliasList = $this->aliasList();
                    return $aliasList[$aliasName];
                }
            }
        }

        return null;
    }

    /*!
     Set alias list. Set alias list to current object

     \param aliasList alias list
    */
    protected function setAliasList( $aliasList )
    {
        $this->ContentObjectAttributeData['DataTypeCustom']['alias_list'] = $aliasList;
    }

    /*
     Set alias variation

     \param alias name
     \param variation array
    */
    protected function setAliasVariation( $aliasName, $variation )
    {
        $this->ContentObjectAttributeData['DataTypeCustom']['alias_list'][$aliasName] = $variation;
    }

    /*!
     Set alias value.

     \param aliasName alias name
     \param attributeName attribute name
     \param value attribute value
    */
    protected function setAliasAttribute( $aliasName, $attributeName, $value )
    {
        $this->ContentObjectAttributeData['DataTypeCustom']['alias_list'][$aliasName][$attributeName] = $value;
    }

    /*!
     \private
     \return A list of aliases structures for the current attribute.ezxml

     The first this is called the XML data will be parsed into the internal
     structures. Subsequent calls will simply return the internal structure.
    */
    function aliasList()
    {
        if ( isset( $this->ContentObjectAttributeData['DataTypeCustom']['alias_list'] ) )
        {
            return $this->ContentObjectAttributeData['DataTypeCustom']['alias_list'];
        }

        eZDebug::accumulatorStart('imageparse', 'XML', 'Image XML parsing' );

        $domTree = new DOMDocument( '1.0', 'utf-8' );

        $xmlString = $this->ContentObjectAttributeData['data_text'];

        $success = false;
        if ( $xmlString != '' )
        {
            $success = $domTree->loadXML( $xmlString );
        }

        if ( !$success )
        {
            $this->generateXMLData();
            $xmlString = $this->ContentObjectAttributeData['data_text'];
            $success = $domTree->loadXML( $xmlString );
        }

        $this->ContentObjectAttributeData['DataTypeCustom']['dom_tree'] = $domTree;
        $imageNodeArray = $domTree->getElementsByTagName( "ezimage" );

        $aliasList = array();

        // I think this is a bug in the plain package or related to the bug I filed
        // about the image datatype serialization
        // http://ez.no/bugs/view/8821 ezpm Error: (eZFileHandler::copy) Unable to open source file in read mode
        if ( $imageNodeArray->length == 0 )
        {
            return $aliasList;
        }

        $imageNode = $imageNodeArray->item( 0 );

        $imageInfoNodeArray = $imageNode->getElementsByTagName( "information" );
        $imageVariationNodeArray = $imageNode->getElementsByTagName( "alias" );
        $imageOriginalArray = $imageNode->getElementsByTagName( "original" );

        $aliasEntry = array();

        $alternativeText = $imageNode->getAttribute( 'alternative_text' );
        $originalFilename = $imageNode->getAttribute( 'original_filename' );
        $basename = $imageNode->getAttribute( 'basename' );
        $displayText = $this->displayText( $alternativeText );

        $originalData = array( 'attribute_id' => '',
                               'attribute_version' => '',
                               'attribute_language' => '' );
        if ( $imageOriginalArray->length > 0 )
        {
            $imageOriginalNode = $imageOriginalArray->item( 0 );
            $originalData = array( 'attribute_id' => $imageOriginalNode->getAttribute( 'attribute_id' ),
                                   'attribute_version' => $imageOriginalNode->getAttribute( 'attribute_version' ),
                                   'attribute_language' => $imageOriginalNode->getAttribute( 'attribute_language' ) );
        }
        if ( strlen( $originalData['attribute_id'] ) == 0 ||
             strlen( $originalData['attribute_version'] ) == 0 ||
             strlen( $originalData['attribute_language'] ) == 0 )
        {
            $originalData = array( 'attribute_id' => $this->ContentObjectAttributeData['id'],
                                   'attribute_version' => $this->ContentObjectAttributeData['version'],
                                   'attribute_language' => $this->ContentObjectAttributeData['language_code'] );
        }
        $this->setOriginalAttributeData( $originalData );

        $aliasEntry['name'] = 'original';
        $aliasEntry['width'] = $imageNode->getAttribute( 'width' );
        $aliasEntry['height'] = $imageNode->getAttribute( 'height' );
        $aliasEntry['mime_type'] = $imageNode->getAttribute( 'mime_type' );
        $aliasEntry['filename'] = $imageNode->getAttribute( 'filename' );
        $aliasEntry['suffix'] = $imageNode->getAttribute( 'suffix' );
        $aliasEntry['dirpath'] = $imageNode->getAttribute( 'dirpath' );
        $aliasEntry['basename'] = $basename;
        $aliasEntry['alternative_text'] = $alternativeText;
        $aliasEntry['text'] = $displayText;
        $aliasEntry['original_filename'] = $originalFilename;
        $aliasEntry['url'] = $imageNode->getAttribute( 'url' );
        $aliasEntry['alias_key'] = $imageNode->getAttribute( 'alias_key' );
        $aliasEntry['timestamp'] = $imageNode->getAttribute( 'timestamp' );
        $aliasEntry['full_path'] = $aliasEntry['url'];
        $aliasEntry['is_valid'] = $imageNode->getAttribute( 'is_valid' );
        $aliasEntry['is_new'] = false;
        $aliasEntry['filesize'] = false;

        if ( $aliasEntry['url'] )
        {
            $aliasFile = eZClusterFileHandler::instance( $aliasEntry['url'] );

            if ( $aliasFile->exists() )
                $aliasEntry['filesize'] = $aliasFile->size();
        }

        $imageInformation = false;
        if ( $imageInfoNodeArray->length > 0 )
        {
            $imageInfoNode = $imageInfoNodeArray->item( 0 );
            $imageInformation = $this->parseInformationNode( $imageInfoNode );
        }
        $aliasEntry['info'] = $imageInformation;

        $serialNumber = $imageNode->getAttribute( 'serial_number' );
        if ( $serialNumber )
        {
            $this->setImageSerialNumber( $serialNumber );
        }

        $aliasList['original'] = $aliasEntry;

        if ( $imageVariationNodeArray->length > 0 )
        {
            foreach ( $imageVariationNodeArray as $imageVariation )
            {
                $aliasEntry = array();
                $aliasEntry['name'] = $imageVariation->getAttribute( 'name' );
                $aliasEntry['width'] = $imageVariation->getAttribute( 'width' );
                $aliasEntry['height'] = $imageVariation->getAttribute( 'height' );
                $aliasEntry['mime_type'] = $imageVariation->getAttribute( 'mime_type' );
                $aliasEntry['filename'] = $imageVariation->getAttribute( 'filename' );
                $aliasEntry['suffix'] = $imageVariation->getAttribute( 'suffix' );
                $aliasEntry['dirpath'] = $imageVariation->getAttribute( 'dirpath' );
                $aliasEntry['alias_key'] = $imageVariation->getAttribute( 'alias_key' );
                $aliasEntry['timestamp'] = $imageVariation->getAttribute( 'timestamp' );
                $aliasEntry['is_valid'] = $imageVariation->getAttribute( 'is_valid' );
                $aliasEntry['url'] = $imageVariation->getAttribute( 'url' );
                $aliasEntry['basename'] = $basename;
                $aliasEntry['alternative_text'] = $alternativeText;
                $aliasEntry['text'] = $displayText;
                $aliasEntry['original_filename'] = $originalFilename;
                $aliasEntry['full_path'] = $aliasEntry['url'];
                $aliasEntry['is_new'] = false;
                $aliasEntry['info'] = $imageInformation;

                if ( $aliasEntry['url'] )
                {
                    $aliasFile = eZClusterFileHandler::instance( $aliasEntry['url'] );

                    if ( $aliasFile->exists() )
                        $aliasEntry['filesize'] = $aliasFile->size();
                }

                require_once( 'kernel/common/image.php' );
                $imageManager = imageInit();
                if ( $imageManager->isImageAliasValid( $aliasEntry ) )
                {
                    $aliasList[$aliasEntry['name']] = $aliasEntry;
                }
            }
        }
        $this->setAliasList( $aliasList );
        eZDebug::accumulatorStop( 'imageparse' );
        return $aliasList;
    }

   /*!
     \static
     Removes all image alias files which the attribute refers to.

     If you want to remove the alias information use removeAliases().
    */
    static function removeAllAliases( $contentObjectAttribute )
    {
        $handler = $contentObjectAttribute->attribute( 'content' );
        if ( !$handler->isImageOwner() )
        {
            return;
        }
        $attributeData = $handler->originalAttributeData();
        $files = eZImageFile::fetchForContentObjectAttribute( $attributeData['attribute_id'], false );
        $dirs = array();

        foreach ( $files as $filepath )
        {
            $file = eZClusterFileHandler::instance( $filepath );
            if ( $file->exists() )
            {
                // FIXME: optimize not to use recursive delete
                $file->delete();
                $dirs[] = eZDir::dirpath( $filepath );
            }
        }
        $dirs = array_unique( $dirs );
        foreach ( $dirs as $dirpath )
        {
            eZDir::cleanupEmptyDirectories( $dirpath );
        }
        eZImageFile::removeForContentObjectAttribute( $attributeData['attribute_id'] );
    }

    /*!
     Removes all the image aliases and their information.

     The stored images will also be removed if the attribute is the owner
     of the images.

     After the images are removed the attribute will contained an internal structures with empty data.

     \note Transaction unsafe.
    */
    function removeAliases( $contentObjectAttribute )
    {
        $aliasList = $this->aliasList();
        $alternativeText = false;

        $contentObjectAttributeVersion = $this->ContentObjectAttributeData['version'];
        $contentObjectAttributeID = $this->ContentObjectAttributeData['id'];

        $isImageOwner = $this->isImageOwner();
        foreach ( $aliasList as $aliasName => $alias )
        {
            $dirpath = $alias['dirpath'];
            $doNotDelete = false; // Do not delete files from storage

            if ( $aliasName == 'original' )
                $alternativeText = $alias['alternative_text'];
            if ( $alias['is_valid'] )
            {
                $filepath = $alias['url'];

                // Fetch ezimage attributes that have $filepath.
                // Always returns current attribute (array of $contentObjectAttributeID and $contentObjectAttributeVersion)
                $dbResult = eZImageFile::fetchImageAttributesByFilepath( $filepath, $contentObjectAttributeID );
                // Check if there are the attributes.
                if ( count( $dbResult ) > 0 )
                {
                    $doNotDelete = true;
                    foreach ( $dbResult as $res )
                    {
                        // If attr is current
                        if ( $res['id'] == $contentObjectAttributeID and
                             $res['version'] == $contentObjectAttributeVersion )
                            continue;

                        eZImageFile::appendFilepath( $res['id'], $filepath, true );
                    }
                }

                if ( !$doNotDelete )
                {
                    $file = eZClusterFileHandler::instance( $filepath );
                    if ( $file->exists() )
                    {
                        $file->delete();
                        eZImageFile::removeFilepath( $contentObjectAttributeID, $filepath );
                        eZDir::cleanupEmptyDirectories( $dirpath );
                    }
                    else
                    {
                        eZDebug::writeError( "Image file $filepath for alias $aliasName does not exist, could not remove from disk",
                                             'eZImageAliasHandler::removeAliases' );
                    }
                }
            }
        }

        $doc = new DOMDocument( '1.0', 'utf-8' );
        $imageNode = $doc->createElement( "ezimage" );
        $doc->appendChild( $imageNode );

        $imageNode->setAttribute( 'serial_number', false );
        $imageNode->setAttribute( 'is_valid', false );
        $imageNode->setAttribute( 'filename', false );
        $imageNode->setAttribute( 'suffix', false );
        $imageNode->setAttribute( 'basename', false );
        $imageNode->setAttribute( 'dirpath', false );
        $imageNode->setAttribute( 'url', false );
        $imageNode->setAttribute( 'original_filename', false );
        $imageNode->setAttribute( 'mime_type', false );
        $imageNode->setAttribute( 'width', false );
        $imageNode->setAttribute( 'height', false );
        $imageNode->setAttribute( 'alternative_text', $alternativeText );
        $imageNode->setAttribute( 'alias_key', false );
        $imageNode->setAttribute( 'timestamp', false );

        $this->ContentObjectAttributeData['DataTypeCustom']['dom_tree'] = $doc;
        unset( $this->ContentObjectAttributeData['DataTypeCustom']['alias_list'] );

        $this->storeDOMTree( $doc, true, $contentObjectAttribute );
    }

    /*!
     Will update the path for images to point to the new path \a $dirpath and filename \a $name.

     This is usually called when the object contain the image attribute is moved in the tree.
    */
    function updateAliasPath( $dirpath, $name )
    {
        if ( !file_exists( $dirpath ) )
        {
            eZDir::mkdir( $dirpath, false, true );
        }
        $aliasList = $this->aliasList();
        $this->resetImageSerialNumber();

        foreach ( $aliasList as $aliasName => $alias )
        {
            if ( $alias['dirpath'] != $dirpath )
            {
                $oldDirpath = $alias['url'];
                $oldURL = $alias['url'];
                $basename = $name;
                if ( $aliasName != 'original' )
                    $basename .= '_' . $aliasName;
                eZMimeType::changeFileData( $alias, $dirpath, $basename );
                $alias['full_path'] = $alias['url'];
                if ( $this->isImageOwner() )
                {
                    if ( $oldURL == '' )
                    {
                        continue;
                    }

                    $fileHandler = eZClusterFileHandler::instance();
                    $fileHandler->fileMove( $oldURL, $alias['url'] );

                    eZDir::cleanupEmptyDirectories( $oldDirpath );
                    eZImageFile::moveFilepath( $this->ContentObjectAttributeData['id'], $oldURL, $alias['url'] );
                }
                else
                {
                    $fileHandler = eZClusterFileHandler::instance();
                    $fileHandler->fileLinkCopy( $oldURL, $alias['url'], false );
                    eZImageFile::appendFilepath( $this->ContentObjectAttributeData['id'], $alias['url'] );
                }
                $this->setAliasVariation( $aliasName, $alias );
            }
        }

        $this->recreateDOMTree();
        $this->setStorageRequired();
    }

    /*!
     \private
     Creates XML attributes containing information on the original image attribute.

     The new attributes will be appended to \a $originalNode.
    */
    function createOriginalAttributeXMLData( $originalNode, $originalData )
    {
        $originalNode->setAttribute( 'attribute_id', $originalData['attribute_id'] );
        $originalNode->setAttribute( 'attribute_version', $originalData['attribute_version'] );
        $originalNode->setAttribute( 'attribute_language', $originalData['attribute_language'] );
    }

    /*!
     \private
     Recreates the DOM tree from the internal array structures and stores the DOM tree
     in the 'data_text' field of the attribute.
    */
    function recreateDOMTree()
    {
        $aliasList = $this->aliasList();
        $doc = new DOMDocument( '1.0', 'utf-8' );
        $imageNode = $doc->createElement( "ezimage" );
        $doc->appendChild( $imageNode );

        $originalNode = $doc->createElement( "original" );
        $imageNode->appendChild( $originalNode );

        require_once( 'kernel/common/image.php' );
        $imageManager = imageInit();

        $aliasName = 'original';

        $originalData = $this->originalAttributeData();
        $this->createOriginalAttributeXMLData( $originalNode, $originalData );

        $imageNode->setAttribute( 'serial_number', $this->imageSerialNumber() );
        $imageNode->setAttribute( 'is_valid', $aliasList[$aliasName]['is_valid'] );
        $imageNode->setAttribute( 'filename', $aliasList[$aliasName]['filename'] );
        $imageNode->setAttribute( 'suffix', $aliasList[$aliasName]['suffix'] );
        $imageNode->setAttribute( 'basename', $aliasList[$aliasName]['basename'] );
        $imageNode->setAttribute( 'dirpath', $aliasList[$aliasName]['dirpath'] );
        $imageNode->setAttribute( 'url', $aliasList[$aliasName]['url'] );
        $imageNode->setAttribute( 'original_filename', $aliasList[$aliasName]['original_filename'] );
        $imageNode->setAttribute( 'mime_type', $aliasList[$aliasName]['mime_type'] );
        $imageNode->setAttribute( 'width', $aliasList[$aliasName]['width'] );
        $imageNode->setAttribute( 'height', $aliasList[$aliasName]['height'] );
        $imageNode->setAttribute( 'alternative_text', $aliasList[$aliasName]['alternative_text'] );
        $imageNode->setAttribute( 'alias_key', $imageManager->createImageAliasKey( $imageManager->alias( $aliasName ) ) );
        $imageNode->setAttribute( 'timestamp', $aliasList[$aliasName]['timestamp'] );

        $filename = $aliasList[$aliasName]['url'];
        if ( $filename )
        {
            $imageFile = eZClusterFileHandler::instance( $filename );

            $fetchedFilePath = $imageFile->fetchUnique();

            //(Cluster) Get mime data of real file, and fetch info by image analizer.
            $mimeDataTemp = eZMimeType::findByFileContents( $fetchedFilePath );
            $imageManager->analyzeImage( $mimeDataTemp );

            //(Cluster) Get mime data of a file which does not really exist on file system. We need this to build correct imageInformationNode.
            $mimeData = eZMimeType::findByURL( $filename );
            if ( isset( $mimeDataTemp['info'] ) )
                $mimeData['info'] = $mimeDataTemp['info'];

            $this->createImageInformationNode( $imageNode, $mimeData );
            $imageFile->fileDeleteLocal( $fetchedFilePath );
        }

        foreach ( $aliasList as $aliasName => $alias )
        {
            if ( $aliasName == 'original' )
                continue;
            $this->addImageAliasToXML( $doc, $alias );
        }

        $this->setDOMTree( $doc );
    }

    /*!
     \return the DOM tree for the current content object attribute.
     \note It will cache the result in the DataTypeCustom member variable of the
           content object attribute in the 'dom_tree' key.
    */
    function domTree()
    {
        $contentObjectAttributeData = $this->ContentObjectAttributeData;
        if ( isset( $contentObjectAttributeData['DataTypeCustom']['dom_tree'] ) )
        {
            return $contentObjectAttributeData['DataTypeCustom']['dom_tree'];
        }

        $dom = new DOMDocument( '1.0', 'utf-8' );
        $xmlString = $contentObjectAttributeData['data_text'];
        $success = $xmlString == '' ? false : $dom->loadXML( $xmlString );
        if ( !$success )
        {
            $this->generateXMLData();
            $xmlString = $this->ContentObjectAttributeData['data_text'];
            $success = $dom->loadXML( $xmlString );
        }

        $contentObjectAttributeData['DataTypeCustom']['dom_tree'] = $dom;

        return $dom;
    }

    /*!
     \private
     Parses the information node and generates the internal information structures.

     The information node contains information from the image itself, for instance
     EXIF data from a JPEG or TIFF image.

     \param $imageInfoNode

     \return $imageInformation array
    */
    function parseInformationNode( $imageInfoNode )
    {
        $imageInformation = array();

        $attributes = $imageInfoNode->attributes;
        foreach ( $attributes as $attribute )
        {
            $imageInformation[$attribute->name] = $attribute->value;
        }

        $children = $imageInfoNode->childNodes;
        foreach ( $children as $child )
        {
            if ( $child->nodeType != XML_ELEMENT_NODE )
            {
                continue;
            }

            $childName = false;
            if ( isset ( $child->localName ) )
            {
                $childName = $child->localName;
            }

            if ( $childName == 'array' )
            {
                $name = $child->getAttribute( 'name' );
                $items = $child->getElementsByTagName( 'item' );
                $array = array();
                foreach ( $items as $item )
                {
                    $itemValue = $item->textContent;
                    if (  $item->getAttribute( 'base64' ) == '1' )
                    {
                        $array[$item->getAttribute( 'key' )] = base64_decode( $itemValue );
                    }
                    else
                    {
                        $array[$item->getAttribute( 'key' )] = $itemValue;
                    }
                }
                ksort( $array );
                $imageInformation[$name] = $array;
            }
            else if ( $childName == 'serialized' )
            {
                $name = $child->getAttribute( 'name' );
                $data = $child->getAttribute( 'data' );
                $imageInformation[$name] = unserialize( $data );
            }
        }

        return $imageInformation;
    }

    /*!
     \static
     Normalized the image name \a $imageName by removing all characters that are not considered
     filename or URL friendly.
     The filename will also be in non-capital letters.
    */
    function normalizeImageName( $imageName )
    {
        // Initialize transformation system
        $trans = eZCharTransform::instance();

        $imageName = eZURLAliasML::convertToAlias( $imageName );
        return $imageName;
    }

    /*!
     Sets the uploaded HTTP file object to \a $httpFile.
     This object is used to store information about the image file until the content object attribute is to be stored.
     \sa httpFile
    */
    function setHTTPFile( $httpFile )
    {
        $this->ContentObjectAttributeData['DataTypeCustom']['http_file'] = $httpFile;
    }

    /*!
     \return the stored HTTP file object or \c false if no object is previously stored.
     \sa setHTTPFile
    */
    function httpFile( $release = false )
    {
        if ( isset( $this->ContentObjectAttributeData['DataTypeCustom']['http_file'] ) )
        {
            $httpFile = $this->ContentObjectAttributeData['DataTypeCustom']['http_file'];
            if ( $release )
            {
                unset( $this->ContentObjectAttributeData['DataTypeCustom']['http_file'] );
            }
            return $httpFile;
        }

        return false;
    }

    /*!
     Initializes the content object attribute \a $contentObjectAttribute with the uploaded HTTP file \a $httpFile.
     Optionally you may also specify the alternative text in the parameter \a $imageAltText.
    */
    function initializeFromHTTPFile( $httpFile, $imageAltText = false )
    {
        $this->increaseImageSerialNumber();

        $mimeData = eZMimeType::findByFileContents( $httpFile->attribute( 'filename' ) );
        if ( !$mimeData['is_valid'] )
        {
            $mimeData = eZMimeType::findByName( $httpFile->attribute( 'mime_type' ) );
            if ( !$mimeData['is_valid'] )
            {
                $mimeData = eZMimeType::findByURL( $httpFile->attribute( 'original_filename' ) );
            }
        }
        $attr = false;
        $this->removeAliases( $attr );
        $this->setOriginalAttributeDataValues( $this->ContentObjectAttributeData['id'],
                                               $this->ContentObjectAttributeData['version'],
                                               $this->ContentObjectAttributeData['language_code'] );
        $contentVersion = eZContentObjectVersion::fetchVersion( $this->ContentObjectAttributeData['version'],
                                                                $this->ContentObjectAttributeData['contentobject_id'] );
        $objectName = $this->imageName( $this->ContentObjectAttributeData, $contentVersion );
        $objectPathString = $this->imagePath( $this->ContentObjectAttributeData, $contentVersion, true );

        eZMimeType::changeBaseName( $mimeData, $objectName );
        eZMimeType::changeDirectoryPath( $mimeData, $objectPathString );

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
        if ( !file_exists( $filename ) )
        {
            eZDebug::writeError( "The image '$filename' does not exist, cannot initialize image attribute with it",
                                 'eZImageAliasHandler::initializeFromFile' );
            return false;
        }

        $this->increaseImageSerialNumber();

        if ( !$originalFilename )
            $originalFilename = basename( $filename );
        $mimeData = eZMimeType::findByFileContents( $filename );
        if ( !$mimeData['is_valid'] and
             $originalFilename != $filename )
        {
            $mimeData = eZMimeType::findByFileContents( $originalFilename );
        }

        $attr = false;
        $this->removeAliases( $attr );
        $this->setOriginalAttributeDataValues( $this->ContentObjectAttributeData['id'],
                                               $this->ContentObjectAttributeData['version'],
                                               $this->ContentObjectAttributeData['language_code'] );
        $contentVersion = eZContentObjectVersion::fetchVersion( $this->ContentObjectAttributeData['version'],
                                                                $this->ContentObjectAttributeData['contentobject_id'] );
        $objectName = $this->imageName( $this->ContentObjectAttributeData, $contentVersion );
        $objectPathString = $this->imagePath( $this->ContentObjectAttributeData, $contentVersion, true );

        eZMimeType::changeBaseName( $mimeData, $objectName );
        eZMimeType::changeDirectoryPath( $mimeData, $objectPathString );
        if ( !file_exists( $mimeData['dirpath'] ) )
        {
            eZDir::mkdir( $mimeData['dirpath'], false, true );
        }

        eZFileHandler::copy( $filename, $mimeData['url'] );

        return $this->initialize( $mimeData, $originalFilename, $imageAltText );
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
        require_once( 'kernel/common/image.php' );
        $imageManager = imageInit();

        $this->setOriginalAttributeDataValues( $this->ContentObjectAttributeData['id'],
                                               $this->ContentObjectAttributeData['version'],
                                               $this->ContentObjectAttributeData['language_code'] );

        $aliasList = array( 'original' => $mimeData );
        $aliasList['original']['alternative_text'] = $imageAltText;
        $aliasList['original']['original_filename'] = $originalFilename;

        $fileHandler = eZClusterFileHandler::instance();
        $filePath = $mimeData['url'];
        $fileHandler->fileStore( $filePath, 'image', false, $mimeData['name'] );

        if ( $imageManager->createImageAlias( 'original', $aliasList, array( 'basename' => $mimeData['basename'] ) ) )
        {
            $mimeData = $aliasList['original'];
            $mimeData['name'] = $mimeData['mime_type'];
            $aliasList['original']['original_filename'] = $originalFilename;
        }

        if ( $aliasList['original']['url'] )
        {
            $aliasFile = eZClusterFileHandler::instance( $aliasList['original']['url'] );
            if( $aliasFile->exists() )
                $aliasList['original']['filesize'] = $aliasFile->size();
        }

        // refetch the original image
        $fileHandler->fileFetch( $filePath );

        $imageManager->analyzeImage( $mimeData );

        $doc = new DOMDocument( '1.0', 'utf-8' );
        $imageNode = $doc->createElement( "ezimage" );
        $doc->appendChild( $imageNode );

        $width = false;
        $height = false;
        $info = getimagesize( $mimeData['url'] );
        if ( $info )
        {
            $width = $info[0];
            $height = $info[1];
        }

        $originalNode = $doc->createElement( "original" );
        $imageNode->appendChild( $originalNode );
        $attributeData = $this->originalAttributeData();
        $this->createOriginalAttributeXMLData( $originalNode, $attributeData );

        $imageNode->setAttribute( 'serial_number', $this->imageSerialNumber() );
        $imageNode->setAttribute( 'is_valid', true );
        $imageNode->setAttribute( 'filename', $mimeData['filename'] );
        $imageNode->setAttribute( 'suffix', $mimeData['suffix'] );
        $imageNode->setAttribute( 'basename', $mimeData['basename'] );
        $imageNode->setAttribute( 'dirpath', $mimeData['dirpath'] );
        $imageNode->setAttribute( 'url', $mimeData['url'] );
        $imageNode->setAttribute( 'original_filename', $originalFilename );
        $imageNode->setAttribute( 'mime_type', $mimeData['name'] );
        $imageNode->setAttribute( 'width', $width );
        $imageNode->setAttribute( 'height', $height );
        $imageNode->setAttribute( 'alternative_text', $imageAltText );
        $imageNode->setAttribute( 'alias_key', $imageManager->createImageAliasKey( $imageManager->alias( 'original' ) ) );
        $imageNode->setAttribute( 'timestamp', time() );

        $this->createImageInformationNode( $imageNode, $mimeData );

        $this->setDOMTree( $doc );

        $this->setAliasList( $aliasList );

        eZImageFile::appendFilepath( $this->ContentObjectAttributeData['id'], $mimeData['url'] );

        $fileHandler->fileDeleteLocal( $filePath );

        return true;
    }

    function createImageInformationNode( $imageNode, $mimeData )
    {
        $dom = $imageNode->ownerDocument;
        if ( isset( $mimeData['info'] ) and
             $mimeData['info'] )
        {
            $imageInfoNode = $dom->createElement( 'information' );
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
                        $toRemove = array();
                        foreach ( $infoItem as $key => $value )
                        {
                            if ( is_string( $value ) && !ctype_print( $value ) )
                            {
                                $toRemove[] = $key;
                            }
                        }

                        if ( count( $toRemove ) > 0 )
                        {
                            eZDebug::writeDebug( 'removing image information items containing non-printable characters: ' . implode( ', ', $toRemove ) );

                            foreach ( $toRemove as $remove )
                            {
                                unset( $infoItem[$remove] );
                            }
                        }

                        unset( $serializedNode );
                        $serializedNode = $dom->createElement( 'serialized' );
                        $serializedNode->setAttribute( 'name', $infoItemName );
                        $serializedNode->setAttribute( 'data', serialize( $infoItem ) );

                        $imageInfoNode->appendChild( $serializedNode );
                    }
                    else
                    {
                        unset( $arrayNode );
                        $arrayNode = $dom->createElement( 'array' );
                        $arrayNode->setAttribute( 'name', $infoItemName );

                        $imageInfoNode->appendChild( $arrayNode );
                        foreach ( $infoItem as $infoArrayKey => $infoArrayItem )
                        {
                            unset( $arrayItemNode );
                            $arrayItemNode = $dom->createElement( 'item' );
                            $arrayItemNode->appendChild( $dom->createTextNode( base64_encode( $infoArrayItem ) ) );
                            $arrayItemNode->setAttribute( 'key', $infoArrayKey );
                            $arrayItemNode->setAttribute( 'base64', 1 );

                            $arrayNode->appendChild( $arrayItemNode );
                        }
                    }
                }
                else
                {
                    $imageInfoNode->setAttribute( $infoItemName, $infoItem );
                }
            }
            $imageNode->appendChild( $imageInfoNode );
        }
    }

    /*!
     Adds all the new image alias structures in \a $imageAliasList to the content object attribute.
    */
    function addImageAliases( $imageAliasList )
    {
        $domTree = $this->domTree();
        foreach ( $imageAliasList as $imageAliasName => $imageAlias )
        {
            if ( $imageAlias['is_new'] )
            {
                $this->addImageAliasToXML( $domTree, $imageAlias );
                $this->setAliasAttribute( $imageAliasName, 'is_new', false );
            }
        }
        $attr = false;
        $this->storeDOMTree( $domTree, true, $attr );
    }

    /*!
     Adds the image alias structure \a $imageAlias to the content object attribute.
    */
    function addImageAlias( $imageAlias )
    {
        $domTree = $this->domTree();
        $this->addImageAliasToXML( $domTree, $imageAlias );
        $attr = false;
        $this->storeDOMTree( $domTree, true, $attr );

    }

    /*!
     Adds the image alias structure \a $imageAlias to the XML DOM document \a $domTree.
    */
    function addImageAliasToXML( $domTree, $imageAlias )
    {
        $imageVariationNodeArray = $domTree->getElementsByTagName( 'alias' );
        $imageNode = false;
        foreach ( $imageVariationNodeArray as $imageVariation )
        {
            $aliasEntryName = $imageVariation->getAttribute( 'name' );
            if ( $aliasEntryName == $imageAlias['name'] )
            {
                $imageNode = $imageVariation;
                break;
            }
        }
        if ( !$imageNode )
        {
            $rootNode = $domTree->documentElement;

            $imageNode = $domTree->createElement( "alias" );
            $rootNode->appendChild( $imageNode );
        }
        else
        {
            $imageNode->removeAttribute( 'name' );
            $imageNode->removeAttribute( 'filename' );
            $imageNode->removeAttribute( 'suffix' );
            $imageNode->removeAttribute( 'dirpath' );
            $imageNode->removeAttribute( 'url' );
            $imageNode->removeAttribute( 'mime_type' );
            $imageNode->removeAttribute( 'width' );
            $imageNode->removeAttribute( 'height' );
            $imageNode->removeAttribute( 'alias_key' );
            $imageNode->removeAttribute( 'timestamp' );
            $imageNode->removeAttribute( 'is_valid' );
        }
        $imageNode->setAttribute( 'name', $imageAlias['name'] );
        $imageNode->setAttribute( 'filename', $imageAlias['filename'] );
        $imageNode->setAttribute( 'suffix', $imageAlias['suffix'] );
        $imageNode->setAttribute( 'dirpath', $imageAlias['dirpath'] );
        $imageNode->setAttribute( 'url', $imageAlias['url'] );
        $imageNode->setAttribute( 'mime_type', $imageAlias['mime_type'] );
        $imageNode->setAttribute( 'width', $imageAlias['width'] );
        $imageNode->setAttribute( 'height', $imageAlias['height'] );
        $imageNode->setAttribute( 'alias_key', $imageAlias['alias_key'] );
        $imageNode->setAttribute( 'timestamp', $imageAlias['timestamp'] );
        $imageNode->setAttribute( 'is_valid', $imageAlias['is_valid'] );
    }

    /*!
     Sets the XML DOM document \a $domTree as the current DOM document.
    */
    function setDOMTree( $domTree )
    {
        $this->ContentObjectAttributeData['DataTypeCustom']['dom_tree'] = $domTree;
        $this->ContentObjectAttributeData['DataTypeCustom']['is_storage_required'] = true;
    }

    /*!
     Stores the XML DOM document \a $domTree to the content object attribute.
    */
    function storeDOMTree( $domTree, $storeAttribute, $contentObjectAttributeRef )
    {
        if ( !$domTree )
            return false;
        $this->ContentObjectAttributeData['DataTypeCustom']['dom_tree'] = $domTree;
        $this->ContentObjectAttributeData['DataTypeCustom']['is_storage_required'] = false;
        $xmlString = $domTree->saveXML();
        $this->ContentObjectAttributeData['data_text'] = $xmlString;

        if ( $storeAttribute )
        {
            if ( is_object( $contentObjectAttributeRef )  )
            {
                $contentObjectAttribute = $contentObjectAttributeRef;
            }
            else
            {
                $contentObjectAttribute = eZContentObjectAttribute::fetch( $this->ContentObjectAttributeData['id'],
                                                                           $this->ContentObjectAttributeData['version'] );
            }

            if ( is_object( $contentObjectAttribute )  )
            {
                $contentObjectAttribute->setAttribute( 'data_text', $xmlString );
                $contentObjectAttribute->storeData();
            }
            else
            {
                eZDebug::writeError( "Invalid objectAttribute: id = " . $this->ContentObjectAttributeData['id'] .
                                    " version = " . $this->ContentObjectAttributeData['version'] ,
                                    "eZImageAliasHandler::storeDOMTree" );
            }
        }

        return true;
    }

    /*!
     Stores the data in the image alias handler to the content object attribute.
     \sa isStorageRequired, setStorageRequired
    */
    function store( $contentObjectAttribute )
    {
        $domTree = $this->domTree();
        if ( $domTree )
        {
            if ( is_object( $contentObjectAttribute ) )
            {
                $this->storeDOMTree( $domTree, false, $contentObjectAttribute );

                $contentObjectAttribute->setAttribute( 'data_text', $this->ContentObjectAttributeData['data_text'] );
                $contentObjectAttribute->storeData();
            }
            else
            {
                $this->storeDOMTree( $domTree, true, $contentObjectAttribute );

            }
        }

        $this->setStorageRequired( false );
    }

    /*!
     \return \c true if the image alias handler is required to store it's contents.
     \sa setStorageRequired, store
    */
    function isStorageRequired()
    {
        return isset( $this->ContentObjectAttributeData['DataTypeCustom']['is_storage_required'] ) ?
            $this->ContentObjectAttributeData['DataTypeCustom']['is_storage_required'] :
            false;
    }

    /*!
     Sets whether storage of the image alias data is required or not.
     \sa isStorageRequired, store
    */
    function setStorageRequired( $require = true )
    {
        $this->ContentObjectAttributeData['DataTypeCustom']['is_storage_required'] = $require;
    }

    /*!
     \return An array structure with information on which attribute
             originally created the current data.

     This will only contain data if the attribute is a copy of
     another attribute, e.g in the case of a new version without an new image upload.
    */
    function originalAttributeData()
    {
        $contentObjectAttributeData = $this->ContentObjectAttributeData;
        if ( isset( $contentObjectAttributeData['DataTypeCustom']['original_data'] ) )
            return $contentObjectAttributeData['DataTypeCustom']['original_data'];

        $originalData = array( 'attribute_id' => $contentObjectAttributeData['id'],
                               'attribute_version' => $contentObjectAttributeData['version'],
                               'attribute_language' => $contentObjectAttributeData['language_code'] );
        $this->setOriginalAttributeData( $originalData );
        return $originalData;
    }

    /*!
     Sets the information on which attribute the data was fetched from.
     See eZImageAliasHandler::originalAttributeData() for more information.
    */
    function setOriginalAttributeData( $originalData )
    {
        $this->ContentObjectAttributeData['DataTypeCustom']['original_data'] = $originalData;

        $domTree = $this->domTree();
        $imageOriginalArray = $domTree->getElementsByTagName( "original" );
        if ( $imageOriginalArray->length > 0 )
        {
            $imageOriginalNode = $imageOriginalArray->item( 0 );
            $this->createOriginalAttributeXMLData( $imageOriginalNode, $originalData );
        }
    }

    /*!
     Sets the information on which attribute the data was fetched from.

     Fetches data from the contentobject attribute \a $contentObjectAttribute and
     sets it using setOriginalAttributeData().
    */
    function setOriginalAttributeDataFromAttribute( $contentObjectAttribute )
    {
        $originalImageHandler = $contentObjectAttribute->attribute( 'content' );
        $originalAttributeData = $originalImageHandler->originalAttributeData();
        $domTree = $originalImageHandler->domTree();
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

    /*!
     Sets the information on which attribute the data was fetched from.

     Fetches data from the parameters and sets it using setOriginalAttributeData().
    */
    function setOriginalAttributeDataValues( $attributeID, $attributeVersion, $attributeLanguage )
    {
        $this->setOriginalAttributeData( array( 'attribute_id' => $attributeID,
                                                'attribute_version' => $attributeVersion,
                                                'attribute_language' => $attributeLanguage ) );
    }

    /*!
     Set internal serial number

     \param val value
    */
    function setImageSerialNumber( $val )
    {
        $this->ContentObjectAttributeData['DataTypeCustom']['serial_number'] = $val;
    }

    /*!
     \private
     \return The internal serial number.

     It will check if a serial number exists and return that, if not a new one will be created and returned.
    */
    private function imageSerialNumberRaw()
    {
        return isset( $this->ContentObjectAttributeData['DataTypeCustom']['serial_number'] ) ?
            $this->ContentObjectAttributeData['DataTypeCustom']['serial_number'] :
            0;
    }

    /*!
     Creates default information.
    */
    function generateXMLData()
    {
        $doc = new DOMDocument( '1.0', 'utf-8' );
        $imageNode = $doc->createElement( "ezimage" );
        $doc->appendChild( $imageNode );

        $fileName = false;

        require_once( 'kernel/common/image.php' );
        $imageManager = imageInit();

        $mimeData = eZMimeType::findByFileContents( $fileName );
        $imageManager->analyzeImage( $mimeData );

        $imageNode->setAttribute( 'serial_number', false );
        $imageNode->setAttribute( 'is_valid', false );
        $imageNode->setAttribute( 'filename', $fileName );
        $imageNode->setAttribute( 'suffix', false );
        $imageNode->setAttribute( 'basename', false );
        $imageNode->setAttribute( 'dirpath', false );
        $imageNode->setAttribute( 'url', false );
        $imageNode->setAttribute( 'original_filename', false );
        $imageNode->setAttribute( 'mime_type', false );
        $imageNode->setAttribute( 'width', false );
        $imageNode->setAttribute( 'height', false );
        $imageNode->setAttribute( 'alternative_text', false );
        $imageNode->setAttribute( 'alias_key', $imageManager->createImageAliasKey( $imageManager->alias( 'original' ) ) );
        $imageNode->setAttribute( 'timestamp', time() );

        $this->createImageInformationNode( $imageNode, $mimeData );

        $this->storeDOMTree( $doc, true, false );
    }

    /// \privatesection
    /// Contains a some eZContentObjectAttribute's attributes.
    public $ContentObjectAttributeData;
    /// Deprecated. Contains a reference to the object attribute
    public $ContentObjectAttribute;

}
?>
