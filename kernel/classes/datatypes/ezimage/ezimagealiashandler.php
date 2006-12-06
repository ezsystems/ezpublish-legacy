<?php
//
// Definition of eZImageAliasHandler class
//
// Created on: <16-Oct-2003 09:34:25 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
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
  \class eZImageAliasHandler ezimagealiashandler.php
  \ingroup eZDatatype
  \brief Internal manager for the eZImage datatype

  Takes care of image conversion and serialization from and to
  the internal XML format.

  \note This handler was introduced in eZ publish 3.3 and will detect older
        eZImage structures and convert them on the fly.

  \note The XML storage was improved in 3.8, from then it always stores the
        attribute ID, version and language in the <original> tag.
        This was required to get the new multi-language features to work.
*/

include_once( 'lib/ezdb/classes/ezdb.php' );
include_once( 'lib/ezfile/classes/ezfilehandler.php' );
include_once( "lib/ezxml/classes/ezxml.php" );
include_once( "kernel/classes/datatypes/ezimage/ezimagefile.php" );

class eZImageAliasHandler
{
    /*!
     Creates the handler and creates a reference to the contentobject attribute that created it.
    */
    function eZImageAliasHandler( &$contentObjectAttribute )
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
        include_once( 'kernel/common/image.php' );
        $imageManager =& imageInit();
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
        include_once( 'kernel/common/image.php' );
        $imageManager =& imageInit();
        if ( $imageManager->hasAlias( $attributeName ) )
            return true;
        return false;
    }

    /*!
     \return the value of the attribute named \a $attributeName.
     See eZImageAliasHandler::attributes() for which attributes are available.
    */
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
        $imageAlias =& $this->imageAlias( $aliasName );
        return $imageAlias;
    }

    /*!
     \return The value of the attribute named \a $attributeName from the 'original' image alias.

     This is a quick way for extracting information from the 'original' image alias.
    */
    function &attributeFromOriginal( $attributeName )
    {
        $originalAlias =& $this->attribute( 'original' );
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
            $serialNumber = 1;
        return $serialNumber;
    }

    /*!
     Increases the serial by one.
    */
    function increaseImageSerialNumber()
    {
        $serialNumber =& $this->imageSerialNumberRaw();
        ++$serialNumber;
    }

    /*!
     Resets the serial number to zero.
    */
    function resetImageSerialNumber()
    {
        $serialNumber =& $this->imageSerialNumberRaw();
        $serialNumber = 0;
    }

    /*!
     Sets the serial number to \a $number.
    */
    function setImageSerialNumber( $number )
    {
        $serialNumber =& $this->imageSerialNumberRaw();
        $serialNumber = $number;
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
        $aliasList =& $this->aliasList();
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
    function imageName( &$contentObjectAttribute, &$contentVersion, $language = false )
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
    function imageNameByNode( &$contentObjectAttribute, &$mainNode, $language = false )
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
    function imagePathByNode( &$contentObjectAttribute, &$mainNode )
    {
        $pathString = $mainNode->attribute( 'path_identification_string' );
        $ini =& eZINI::instance( 'image.ini' );
        $contentImageSubtree = $ini->variable( 'FileSettings', 'PublishedImages' );
        $attributeData = $this->originalAttributeData();
        $attributeID = $attributeData['attribute_id'];
        $attributeVersion = $attributeData['attribute_version'];
        $attributeLanguage = $attributeData['attribute_language'];
        $imagePath = eZSys::storageDirectory() . '/' . $contentImageSubtree . '/' . $pathString . '/' . $attributeID . '-' . $attributeVersion . '-' . $attributeLanguage;
        return $imagePath;
    }

    /*!
     \return The image alias structure for the alias named \a $aliasName.

     This will create the image alias if it does not exist yet, this can involve
     running image operations to for instance scale the image.
    */
    function &imageAlias( $aliasName )
    {
        include_once( 'kernel/common/image.php' );
        $imageManager =& imageInit();
        if ( !$imageManager->hasAlias( $aliasName ) )
        {
            $retValue = null;
            return $retValue;
        }

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
                    // VS-DBFILE : TODO checked..

                    $text = $this->displayText( $original['alternative_text'] );
                    $originalFilename = $original['original_filename'];
                    foreach ( array_keys( $aliasList ) as $aliasName )
                    {
                        $alias =& $aliasList[$aliasName];
                        $alias['original_filename'] = $originalFilename;
                        $alias['text'] = $text;
                        if ( $alias['url'] )
                        {
                            require_once( 'kernel/classes/ezclusterfilehandler.php' );
                            $aliasFile = eZClusterFileHandler::instance( $alias['url'] );
                            if( $aliasFile->exists() )
                                $alias['filesize'] = $aliasFile->size();
                        }
                        if ( $alias['is_new'] )
                        {
                            // VS-DBFILE : TODO checked
                            eZImageFile::appendFilepath( $this->ContentObjectAttributeData['id'], $alias['url'] );
                        }
                    }
                    $this->addImageAliases( $aliasList );
                    $aliasList3 =& $this->aliasList();
                    return $aliasList[$aliasName];
                }
            }
        }

        $imageAlias = null;
        return $imageAlias;
    }

    /*!
     \private
     \return A list of aliases structures for the current attribute.ezxml

     The first this is called the XML data will be parsed into the internal
     structures. Subsequent calls will simply return the internal structure.
    */
    function &aliasList()
    {
        $contentObjectAttributeData =& $this->ContentObjectAttributeData;
        if ( isset( $contentObjectAttributeData['DataTypeCustom']['alias_list'] ) )
        {
            $aliasList =& $contentObjectAttributeData['DataTypeCustom']['alias_list'];
            return $aliasList;
        }

        eZDebug::AccumulatorStart('imageparse', 'XML', 'Image XML parsing' );
        $xml = new eZXML();
        $xmlString =& $contentObjectAttributeData['data_text'];
        $domTree =& $xml->domTree( $xmlString, array(), true );

        if ( $domTree == false )
        {
            $this->generateXMLData();
            $domTree =& $xml->domTree( $xmlString, array(), false );
        }

        $contentObjectAttributeData['DataTypeCustom']['dom_tree'] =& $domTree;
        $imageNodeArray = $domTree->get_elements_by_tagname( "ezimage" );
        $imageInfoNodeArray = $domTree->get_elements_by_tagname( "information" );
        $imageVariationNodeArray = $domTree->get_elements_by_tagname( "alias" );
        $imageOriginalArray = $domTree->get_elements_by_tagname( "original" );

        $aliasList = array();

        $aliasEntry = array();
        $alternativeText = $imageNodeArray[0]->get_attribute( 'alternative_text' );
        $originalFilename = $imageNodeArray[0]->get_attribute( 'original_filename' );
        $basename = $imageNodeArray[0]->get_attribute( 'basename' );
        $displayText = $this->displayText( $alternativeText );

        $originalData = array( 'attribute_id' => '',
                               'attribute_version' => '',
                               'attribute_language' => '' );
        if ( isset( $imageOriginalArray[0] ) )
        {
            $originalData = array( 'attribute_id' => $imageOriginalArray[0]->get_attribute( 'attribute_id' ),
                                   'attribute_version' => $imageOriginalArray[0]->get_attribute( 'attribute_version' ),
                                   'attribute_language' => $imageOriginalArray[0]->get_attribute( 'attribute_language' ) );
//                                    'has_file_copy' => $imageOriginalArray[0]->get_attribute( 'has_file_copy' ) );
        }
        if ( strlen( $originalData['attribute_id'] ) == 0 ||
             strlen( $originalData['attribute_version'] ) == 0 ||
             strlen( $originalData['attribute_language'] ) == 0 )
        {
            $originalData = array( 'attribute_id' => $contentObjectAttributeData['id'],
                                   'attribute_version' => $contentObjectAttributeData['version'],
                                   'attribute_language' => $contentObjectAttributeData['language_code'] );
        }
        $this->setOriginalAttributeData( $originalData );

        $aliasEntry['name'] = 'original';
        $aliasEntry['width'] = $imageNodeArray[0]->get_attribute( 'width' );
        $aliasEntry['height'] = $imageNodeArray[0]->get_attribute( 'height' );
        $aliasEntry['mime_type'] = $imageNodeArray[0]->get_attribute( 'mime_type' );
        $aliasEntry['filename'] = $imageNodeArray[0]->get_attribute( 'filename' );
        $aliasEntry['suffix'] = $imageNodeArray[0]->get_attribute( 'suffix' );
        $aliasEntry['dirpath'] = $imageNodeArray[0]->get_attribute( 'dirpath' );
        $aliasEntry['basename'] = $basename;
        $aliasEntry['alternative_text'] = $alternativeText;
        $aliasEntry['text'] = $displayText;
        $aliasEntry['original_filename'] = $originalFilename;
        $aliasEntry['url'] = $imageNodeArray[0]->get_attribute( 'url' );
        $aliasEntry['alias_key'] = $imageNodeArray[0]->get_attribute( 'alias_key' );
        $aliasEntry['timestamp'] = $imageNodeArray[0]->get_attribute( 'timestamp' );
        $aliasEntry['full_path'] =& $aliasEntry['url'];
        $aliasEntry['is_valid'] = $imageNodeArray[0]->get_attribute( 'is_valid' );
        $aliasEntry['is_new'] = false;
        $aliasEntry['filesize'] = false;

        // VS-DBFILE
        if ( $aliasEntry['url'] )
        {
            require_once( 'kernel/classes/ezclusterfilehandler.php' );
            $aliasFile = eZClusterFileHandler::instance( $aliasEntry['url'] );

            if ( $aliasFile->exists() )
                $aliasEntry['filesize'] = $aliasFile->size();
        }

        $imageInformation = false;
        if ( count( $imageInfoNodeArray ) > 0 )
        {
            $imageInfoNode =& $imageInfoNodeArray[0];
            $this->parseInformationNode( $imageInfoNode, $imageInformation );
        }
        $aliasEntry['info'] =& $imageInformation;

        $serialNumber = $imageNodeArray[0]->get_attribute( 'serial_number' );
        if ( $serialNumber )
            $this->setImageSerialNumber( $serialNumber );

        $aliasList['original'] = $aliasEntry;

        if ( is_array( $imageVariationNodeArray ) )
        {
            foreach ( $imageVariationNodeArray as $imageVariation )
            {
                $aliasEntry = array();
                $aliasEntry['name'] = $imageVariation->get_attribute( 'name' );
                $aliasEntry['width'] = $imageVariation->get_attribute( 'width' );
                $aliasEntry['height'] = $imageVariation->get_attribute( 'height' );
                $aliasEntry['mime_type'] = $imageVariation->get_attribute( 'mime_type' );
                $aliasEntry['filename'] = $imageVariation->get_attribute( 'filename' );
                $aliasEntry['suffix'] = $imageVariation->get_attribute( 'suffix' );
                $aliasEntry['dirpath'] = $imageVariation->get_attribute( 'dirpath' );
                $aliasEntry['alias_key'] = $imageVariation->get_attribute( 'alias_key' );
                $aliasEntry['timestamp'] = $imageVariation->get_attribute( 'timestamp' );
                $aliasEntry['is_valid'] = $imageVariation->get_attribute( 'is_valid' );
                $aliasEntry['url'] = $imageVariation->get_attribute( 'url' );
                $aliasEntry['basename'] = $basename;
                $aliasEntry['alternative_text'] = $alternativeText;
                $aliasEntry['text'] = $displayText;
                $aliasEntry['original_filename'] = $originalFilename;
                $aliasEntry['full_path'] =& $aliasEntry['url'];
                $aliasEntry['is_new'] = false;
                $aliasEntry['info'] =& $imageInformation;

                if ( $aliasEntry['url'] )
                {
                    // VS-DBFILE

                    require_once( 'kernel/classes/ezclusterfilehandler.php' );
                    $aliasFile = eZClusterFileHandler::instance( $aliasEntry['url'] );

                    if ( $aliasFile->exists() )
                        $aliasEntry['filesize'] = $aliasFile->size();
                }

                include_once( 'kernel/common/image.php' );
                $imageManager =& imageInit();
                if ( $imageManager->isImageAliasValid( $aliasEntry ) )
                {
                    $aliasList[$aliasEntry['name']] = $aliasEntry;
                }
            }
        }
        $contentObjectAttributeData['DataTypeCustom']['alias_list'] =& $aliasList;
        eZDebug::AccumulatorStop( 'imageparse' );
        return $aliasList;
    }

   /*!
     \static
     Removes all image alias files which the attribute refers to.

     If you want to remove the alias information use removeAliases().
    */
    function removeAllAliases( &$contentObjectAttribute )
    {
        // VS-DBFILE

        $handler =& $contentObjectAttribute->attribute( 'content' );
        if ( !$handler->isImageOwner() )
        {
            return;
        }
        $attributeData = $handler->originalAttributeData();
        $files = eZImageFile::fetchForContentObjectAttribute( $attributeData['attribute_id'], false );
        $dirs = array();

        require_once( 'kernel/classes/ezclusterfilehandler.php' );
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
    function removeAliases( &$contentObjectAttribute )
    {
        $aliasList =& $this->aliasList();
        $alternativeText = false;

        $contentObjectAttributeData =& $this->ContentObjectAttributeData;
        $contentObjectAttributeVersion = $contentObjectAttributeData['version'];
        $contentObjectAttributeID = $contentObjectAttributeData['id'];

        $isImageOwner = $this->isImageOwner();
        foreach ( array_keys( $aliasList ) as $aliasName )
        {
            $alias =& $aliasList[$aliasName];
            $dirpath = $alias['dirpath'];
            $doNotDelete = false; // Do not delete files from storage

            if ( $aliasName == 'original' )
                $alternativeText = $alias['alternative_text'];
            if ( $alias['is_valid'] )
            {
                // VS-DBFILE

                $filepath = $alias['url'];

                // Fetch ezimage attributes that have $filepath.
                // Always returns current attribute (array of $contentObjectAttributeID and $contentObjectAttributeVersion)
                $dbResult = eZImageFile::fetchImageAttributesByFilepath( $filepath );
                // Check if there are the attributes.
                if ( count( $dbResult ) > 1 )
                {
                    $doNotDelete = true;
                    foreach ( $dbResult as $res )
                    {
                        $canAppend = true;
                        // If attr is current
                        if ( $res['id'] == $contentObjectAttributeID and
                             $res['version'] == $contentObjectAttributeVersion )
                            $canAppend = false;

                        // If the attr is not current we should append $filepath to ezimagefile for $res['id']
                        if ( $canAppend )
                        {
                            eZImageFile::appendFilepath( $res['id'], $filepath, true );
                            // If $filepath for $contentObjectAttributeID was appended
                            // we should not delete it from ezimagefile table.
                            if ( $res['id'] != $contentObjectAttributeID )
                                eZImageFile::removeFilepath( $contentObjectAttributeID, $filepath );
                        }
                    }
                }

                if ( !$doNotDelete )
                {
                    require_once( 'kernel/classes/ezclusterfilehandler.php' );
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
        unset( $aliasList );

        $doc = new eZDOMDocument();
        $imageNode = $doc->createElementNode( "ezimage" );
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

        $contentObjectAttributeData['DataTypeCustom']['dom_tree'] =& $doc;
        unset( $contentObjectAttributeData['DataTypeCustom']['alias_list'] );

        $this->storeDOMTree( $doc, true, $contentObjectAttribute );
    }

    /*!
     Will update the path for images to point to the new path \a $dirpath and filename \a $name.

     This is usually called when the object contain the image attribute is moved in the tree.
    */
    function updateAliasPath( $dirpath, $name )
    {
        $contentObjectAttributeData =& $this->ContentObjectAttributeData;

        if ( !file_exists( $dirpath ) )
        {
            eZDir::mkdir( $dirpath, eZDir::directoryPermission(), true );
        }
        include_once( 'lib/ezutils/classes/ezmimetype.php' );
        $aliasList =& $this->aliasList();
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

                    // VS-DBFILE

                    require_once( 'kernel/classes/ezclusterfilehandler.php' );
                    $fileHandler = eZClusterFileHandler::instance();
                    $fileHandler->fileMove( $oldURL, $alias['url'] );

                    eZDir::cleanupEmptyDirectories( $oldDirpath );
                    eZImageFile::moveFilepath( $contentObjectAttributeData['id'], $oldURL, $alias['url'] );
                }
                else
                {
                    // VS-DBFILE

                    require_once( 'kernel/classes/ezclusterfilehandler.php' );
                    $fileHandler = eZClusterFileHandler::instance();
                    $fileHandler->fileLinkCopy( $oldURL, $alias['url'], false );
                    eZImageFile::appendFilepath( $contentObjectAttributeData['id'], $alias['url'] );
                }
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
    function createOriginalAttributeXMLData( &$originalNode, $originalData )
    {
        $originalNode->set_attribute( 'attribute_id', $originalData['attribute_id'] );
        $originalNode->set_attribute( 'attribute_version', $originalData['attribute_version'] );
        $originalNode->set_attribute( 'attribute_language', $originalData['attribute_language'] );
//         $originalNode->set_attribute( 'has_file_copy', $originalData['has_file_copy'] );
    }

    /*!
     \private
     Recreates the DOM tree from the internal array structures and stores the DOM tree
     in the 'data_text' field of the attribute.
    */
    function recreateDOMTree()
    {
        $aliasList =& $this->aliasList();

        $doc = new eZDOMDocument();
        $imageNode = $doc->createElementNode( "ezimage" );
        $doc->setRoot( $imageNode );

        $originalNode = $doc->createElementNode( "original" );
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

        $filename = $aliasList[$aliasName]['url'];
        if ( $filename )
        {
            // VS-DBFILE

            require_once( 'kernel/classes/ezclusterfilehandler.php' );
            $imageFile = eZClusterFileHandler::instance( $filename );
            $imageFile->fetch();

            include_once( 'lib/ezutils/classes/ezmimetype.php' );
            $mimeData = eZMimeType::findByFileContents( $filename );
            $imageManager->analyzeImage( $mimeData );
            $this->createImageInformationNode( $imageNode, $mimeData );

            $imageFile->deleteLocal();
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
     \return the DOM tree for the current content object attribute.
     \note It will cache the result in the DataTypeCustom member variable of the
           content object attribute in the 'dom_tree' key.
    */
    function &domTree()
    {
        $contentObjectAttributeData =& $this->ContentObjectAttributeData;
        if ( isset( $contentObjectAttributeData['DataTypeCustom']['dom_tree'] ) )
            return $contentObjectAttributeData['DataTypeCustom']['dom_tree'];

        $xml = new eZXML();
        $xmlString =& $contentObjectAttributeData['data_text'];
        $domTree =& $xml->domTree( $xmlString );
        if ( $domTree == false )
        {
            $this->generateXMLData();
            $domTree =& $xml->domTree( $xmlString );
        }

        $contentObjectAttributeData['DataTypeCustom']['dom_tree'] =& $domTree;

        return $domTree;
    }

    /*!
     \private
     Parses the information node and generates the internal information structures.

     The information node contains information from the image itself, for instance
     EXIF data from a JPEG or TIFF image.
    */
    function parseInformationNode( &$imageInfoNode, &$imageInformation )
    {
        $imageInformation = array();

        $attributes = $imageInfoNode->attributes();
        foreach ( $attributes as $attribute )
        {
            $imageInformation[$attribute->name()] = $attribute->value;
        }

        $children = $imageInfoNode->children();
        foreach ( $children as $child )
        {
            $childName = false;
            if ( isset ( $child->tagname ) )
            {
                $childName = $child->tagname;
            }

            if ( $childName == 'array' )
            {
                $name = $child->get_attribute( 'name' );
                $items = $child->get_elements_by_tagname( 'item' );
                $array = array();
                foreach ( $items as $item )
                {
                    $itemValueNode = $item->first_child();
                    if ( !is_object ( $itemValueNode ) )
                        continue;
                    $itemValue = $itemValueNode->node_value();
                    if (  $item->get_attribute( 'base64' ) == '1' )
                    {
                        $array[$item->get_attribute( 'key' )] = base64_decode( $itemValue );
                    }
                    else
                    {
                        $array[$item->get_attribute( 'key' )] = $itemValue;
                    }
                }
                ksort( $array );
                $imageInformation[$name] = $array;
            }
            else if ( $childName == 'serialized' )
            {
                $name = $child->get_attribute( 'name' );
                $data = $child->get_attribute( 'data' );
                $imageInformation[$name] = unserialize( $data );
            }
        }
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
        include_once( 'lib/ezi18n/classes/ezchartransform.php' );
        $trans =& eZCharTransform::instance();

        $imageName = $trans->transformByGroup( $imageName, 'identifier' );
        return $imageName;
    }

    /*!
     Sets the uploaded HTTP file object to \a $httpFile.
     This object is used to store information about the image file until the content object attribute is to be stored.
     \sa httpFile
    */
    function setHTTPFile( &$httpFile )
    {
        $contentObjectAttributeData =& $this->ContentObjectAttributeData;
        $contentObjectAttributeData['DataTypeCustom']['http_file'] =& $httpFile;
    }

    /*!
     \return the stored HTTP file object or \c false if no object is previously stored.
     \sa setHTTPFile
    */
    function &httpFile( $release = false )
    {
        $contentObjectAttributeData =& $this->ContentObjectAttributeData;
        if ( isset( $contentObjectAttributeData['DataTypeCustom']['http_file'] ) )
        {
            $httpFile =& $contentObjectAttributeData['DataTypeCustom']['http_file'];
            if ( $release )
                unset( $contentObjectAttributeData['DataTypeCustom']['http_file'] );
            return $httpFile;
        }

        $httpFile = false;
        return $httpFile;
    }

    /*!
     Initializes the content object attribute \a $contentObjectAttribute with the uploaded HTTP file \a $httpFile.
     Optionally you may also specify the alternative text in the parameter \a $imageAltText.
    */
    function initializeFromHTTPFile( &$httpFile, $imageAltText = false )
    {
        $contentObjectAttributeData =& $this->ContentObjectAttributeData;
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
        $attr = false;
        $this->removeAliases( $attr );
        $this->setOriginalAttributeDataValues( $contentObjectAttributeData['id'],
                                               $contentObjectAttributeData['version'],
                                               $contentObjectAttributeData['language_code'] );
        $contentVersion = eZContentObjectVersion::fetchVersion( $contentObjectAttributeData['version'],
                                                                $contentObjectAttributeData['contentobject_id'] );
        $objectName = $this->imageName( $contentObjectAttributeData, $contentVersion );
        $objectPathString = $this->imagePath( $contentObjectAttributeData, $contentVersion, true );

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
        $contentObjectAttributeData =& $this->ContentObjectAttributeData;
        if ( !file_exists( $filename ) )
        {
            eZDebug::writeError( "The image '$filename' does not exist, cannot initialize image attribute with it",
                                 'eZImageAliasHandler::initializeFromFile' );
            return false;
        }

        $this->increaseImageSerialNumber();

        if ( !$originalFilename )
            $originalFilename = basename( $filename );
        include_once( 'lib/ezutils/classes/ezmimetype.php' );
        $mimeData = eZMimeType::findByFileContents( $filename );
        if ( !$mimeData['is_valid'] and
             $originalFilename != $filename )
        {
            $mimeData = eZMimeType::findByFileContents( $originalFilename );
        }

        $attr = false;
        $this->removeAliases( $attr );
        $this->setOriginalAttributeDataValues( $contentObjectAttributeData['id'],
                                               $contentObjectAttributeData['version'],
                                               $contentObjectAttributeData['language_code'] );
        $contentVersion = eZContentObjectVersion::fetchVersion( $contentObjectAttributeData['version'],
                                                                $contentObjectAttributeData['contentobject_id'] );
        $objectName = $this->imageName( $contentObjectAttributeData, $contentVersion );
        $objectPathString = $this->imagePath( $contentObjectAttributeData, $contentVersion, true );

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
        include_once( 'kernel/common/image.php' );
        $imageManager =& imageInit();

        $this->setOriginalAttributeDataValues( $this->ContentObjectAttributeData['id'],
                                               $this->ContentObjectAttributeData['version'],
                                               $this->ContentObjectAttributeData['language_code'] );

        $aliasList = array( 'original' => $mimeData );
        $aliasList['original']['alternative_text'] = $imageAltText;
        $aliasList['original']['original_filename'] = $originalFilename;

        // VS-DBFILE

        require_once( 'kernel/classes/ezclusterfilehandler.php' );
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
            require_once( 'kernel/classes/ezclusterfilehandler.php' );
            $aliasFile = eZClusterFileHandler::instance( $aliasList['original']['url'] );
            if( $aliasFile->exists() )
                $aliasList['original']['filesize'] = $aliasFile->size();
        }

        // VS-DBFILE

        // refetch the original image
        $fileHandler->fileFetch( $filePath );

        $imageManager->analyzeImage( $mimeData );

        $doc = new eZDOMDocument();
        $imageNode = $doc->createElementNode( "ezimage" );
        $doc->setRoot( $imageNode );

        $width = false;
        $height = false;
        $info = getimagesize( $mimeData['url'] );
        if ( $info )
        {
            $width = $info[0];
            $height = $info[1];
        }

        $originalNode = $doc->createElementNode( "original" );
        $imageNode->appendChild( $originalNode );
        $attributeData = $this->originalAttributeData();
        $this->createOriginalAttributeXMLData( $originalNode, $attributeData );

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

        $contentObjectAttributeData =& $this->ContentObjectAttributeData;
        $contentObjectAttributeData['DataTypeCustom']['alias_list'] =& $aliasList;

        eZImageFile::appendFilepath( $contentObjectAttributeData['id'], $mimeData['url'] );

        // VS-DBFILE
        $fileHandler->fileDeleteLocal( $filePath );

        return true;
    }

    function createImageInformationNode( &$imageNode, &$mimeData )
    {
        if ( isset( $mimeData['info'] ) and
             $mimeData['info'] )
        {
            $imageInfoNode = eZDOMDocument::createElementNode( 'information' );
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
                        unset( $serializedNode );
                        $serializedNode = eZDOMDocument::createElementNode( 'serialized',
                                                                             array( 'name' => $infoItemName,
                                                                                    'data' => serialize( $infoItem ) ) );
                        $imageInfoNode->appendChild( $serializedNode );
                    }
                    else
                    {
                        unset( $arrayNode );
                        $arrayNode = eZDOMDocument::createElementNode( 'array',
                                                                        array( 'name' => $infoItemName ) );
                        $imageInfoNode->appendChild( $arrayNode );
                        foreach ( $infoItem as $infoArrayKey => $infoArrayItem )
                        {
                            unset( $arrayItemNode );
                            $arrayItemNode = eZDOMDocument::createElementNode( 'item',
                                                                                array( 'key' => $infoArrayKey,
                                                                                       'base64' => 1 ) );
                            $arrayItemNode->appendChild( eZDOMDocument::createTextNode( base64_encode( $infoArrayItem ) ) );
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
        $attr = false;
        $this->storeDOMTree( $domTree, true, $attr );
    }

    /*!
     Adds the image alias structure \a $imageAlias to the content object attribute.
    */
    function addImageAlias( $imageAlias )
    {
        $domTree =& $this->domTree();
        $this->addImageAliasToXML( $domTree, $imageAlias );
        $attr = false;
        $this->storeDOMTree( $domTree, true, $attr );

    }

    /*!
     Adds the image alias structure \a $imageAlias to the XML DOM document \a $domTree.
    */
    function addImageAliasToXML( &$domTree, $imageAlias )
    {
        $imageVariationNodeArray = $domTree->get_elements_by_tagname( 'alias' );
        $imageNode = false;
        if ( is_array( $imageVariationNodeArray ) )
        {
            foreach ( array_keys( $imageVariationNodeArray ) as $imageVariationKey )
            {
                $imageVariation =& $imageVariationNodeArray[$imageVariationKey];
                $aliasEntryName = $imageVariation->get_attribute( 'name' );
                if ( $aliasEntryName == $imageAlias['name'] )
                {
                    $imageNode =& $imageVariation;
                    break;
                }
            }
        }
        if ( !$imageNode )
        {
            if ( is_a( $domTree, 'domdocument' ) )
            {
                $rootNode = $domTree->root();
            }
            else
            {
                $rootNode =& $domTree->root();
            }

            $imageNode = $domTree->create_element( "alias" );
            $rootNode->append_child( $imageNode );
        }
        else
        {
            $imageNode->remove_attribute( 'name' );
            $imageNode->remove_attribute( 'filename' );
            $imageNode->remove_attribute( 'suffix' );
            $imageNode->remove_attribute( 'dirpath' );
            $imageNode->remove_attribute( 'url' );
            $imageNode->remove_attribute( 'mime_type' );
            $imageNode->remove_attribute( 'width' );
            $imageNode->remove_attribute( 'height' );
            $imageNode->remove_attribute( 'alias_key' );
            $imageNode->remove_attribute( 'timestamp' );
            $imageNode->remove_attribute( 'is_valid' );
        }
        $imageNode->set_attribute( 'name', $imageAlias['name'] );
        $imageNode->set_attribute( 'filename', $imageAlias['filename'] );
        $imageNode->set_attribute( 'suffix', $imageAlias['suffix'] );
        $imageNode->set_attribute( 'dirpath', $imageAlias['dirpath'] );
        $imageNode->set_attribute( 'url', $imageAlias['url'] );
        $imageNode->set_attribute( 'mime_type', $imageAlias['mime_type'] );
        $imageNode->set_attribute( 'width', $imageAlias['width'] );
        $imageNode->set_attribute( 'height', $imageAlias['height'] );
        $imageNode->set_attribute( 'alias_key', $imageAlias['alias_key'] );
        $imageNode->set_attribute( 'timestamp', $imageAlias['timestamp'] );
        $imageNode->set_attribute( 'is_valid', $imageAlias['is_valid'] );

//        var_dump($imageNode);
    }

    /*!
     Sets the XML DOM document \a $domTree as the current DOM document.
    */
    function setDOMTree( &$domTree )
    {
        $contentObjectAttributeData =& $this->ContentObjectAttributeData;
        $contentObjectAttributeData['DataTypeCustom']['dom_tree'] =& $domTree;
        $contentObjectAttributeData['DataTypeCustom']['is_storage_required'] = true;
    }

    /*!
     Stores the XML DOM document \a $domTree to the content object attribute.
    */
    function storeDOMTree( &$domTree, $storeAttribute, &$contentObjectAttributeRef )
    {
        if ( !$domTree )
            return false;
        $contentObjectAttributeData =& $this->ContentObjectAttributeData;
        $contentObjectAttributeData['DataTypeCustom']['dom_tree'] =& $domTree;
        $contentObjectAttributeData['DataTypeCustom']['is_storage_required'] = false;
        $xmlString = $domTree->dump_mem();
        $contentObjectAttributeData['data_text'] = $xmlString;

        if ( $storeAttribute )
        {
            if ( is_object( $contentObjectAttributeRef )  )
                $contentObjectAttribute =& $contentObjectAttributeRef;
            else
                $contentObjectAttribute = eZContentObjectAttribute::fetch( $contentObjectAttributeData['id'], $contentObjectAttributeData['version'] );

            if ( is_object( $contentObjectAttribute )  )
            {
                $contentObjectAttribute->setAttribute( 'data_text', $xmlString );
                $contentObjectAttribute->storeData();
            }
            else
            {
                eZDebug::writeError( "Invalid objectAttribute: id = " . $contentObjectAttributeData['id'] . " version = " . $contentObjectAttributeData['version'] , "eZImageAliasHandler::storeDOMTree" );
            }
        }

        return true;
    }

    /*!
     Stores the data in the image alias handler to the content object attribute.
     \sa isStorageRequired, setStorageRequired
    */
    function store( &$contentObjectAttribute )
    {
        $contentObjectAttributeData =& $this->ContentObjectAttributeData;

        $domTree =& $this->domTree();
        if ( $domTree )
        {
            if ( is_object( $contentObjectAttribute ) )
            {
                $this->storeDOMTree( $domTree, false, $contentObjectAttribute );

                $contentObjectAttribute->setAttribute( 'data_text', $contentObjectAttributeData['data_text'] );
                $contentObjectAttribute->storeData();
            }
            else
            {
                $this->storeDOMTree( $domTree, true, $contentObjectAttribute );

            }
        }

        $contentObjectAttributeData['DataTypeCustom']['is_storage_required'] = false;
    }

    /*!
     \return \c true if the image alias handler is required to store it's contents.
     \sa setStorageRequired, store
    */
    function isStorageRequired()
    {
        $contentObjectAttributeData =& $this->ContentObjectAttributeData;
        if ( isset( $contentObjectAttributeData['DataTypeCustom']['is_storage_required'] ) )
            return $contentObjectAttributeData['DataTypeCustom']['is_storage_required'];
        return false;
    }

    /*!
     Sets whether storage of the image alias data is required or not.
     \sa isStorageRequired, store
    */
    function setStorageRequired( $require = true )
    {
        $contentObjectAttributeData =& $this->ContentObjectAttributeData;
        $contentObjectAttributeData['DataTypeCustom']['is_storage_required'] = $require;
    }

    /*!
     \return An array structure with information on which attribute
             originally created the current data.

     This will only contain data if the attribute is a copy of
     another attribute, e.g in the case of a new version without an new image upload.
    */
    function &originalAttributeData()
    {
        $contentObjectAttributeData =& $this->ContentObjectAttributeData;
        if ( isset( $contentObjectAttributeData['DataTypeCustom']['original_data'] ) )
            return $contentObjectAttributeData['DataTypeCustom']['original_data'];

        $originalData = array( 'attribute_id' => $contentObjectAttributeData['id'],
                               'attribute_version' => $contentObjectAttributeData['version'],
                               'attribute_language' => $contentObjectAttributeData['language_code'] );
        $contentObjectAttributeData['DataTypeCustom']['original_data'] =& $originalData;
        return $originalData;
    }

    /*!
     Sets the information on which attribute the data was fetched from.
     See eZImageAliasHandler::originalAttributeData() for more information.
    */
    function setOriginalAttributeData( $originalData )
    {
        $contentObjectAttributeData =& $this->ContentObjectAttributeData;
        $contentObjectAttributeData['DataTypeCustom']['original_data'] =& $originalData;

        $domTree =& $this->domTree();
        $imageOriginalArray = $domTree->get_elements_by_tagname( "original" );
        if ( isset( $imageOriginalArray[0] ) )
            $this->createOriginalAttributeXMLData( $imageOriginalArray[0], $originalData );
    }

    /*!
     Sets the information on which attribute the data was fetched from.

     Fetches data from the contentobject attribute \a $contentObjectAttribute and
     sets it using setOriginalAttributeData().
    */
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

    /*!
     Sets the information on which attribute the data was fetched from.

     Fetches data from the parameters and sets it using setOriginalAttributeData().
    */
    function setOriginalAttributeDataValues( $attributeID, $attributeVersion, $attributeLanguage )
    {
        $contentObjectAttributeData =& $this->ContentObjectAttributeData;
        $originalData = array( 'attribute_id' => $attributeID,
                               'attribute_version' => $attributeVersion,
                               'attribute_language' => $attributeLanguage );
        $this->setOriginalAttributeData( $originalData );
    }

    /*!
     \private
     \return The internal serial number.

     It will check if a serial number exists and return that, if not a new one will be created and returned.
    */
    function &imageSerialNumberRaw()
    {
        $contentObjectAttributeData =& $this->ContentObjectAttributeData;

        if ( isset( $contentObjectAttributeData['DataTypeCustom']['serial_number'] ) and $contentObjectAttributeData['DataTypeCustom']['serial_number'] >= 0 )
            return $contentObjectAttributeData['DataTypeCustom']['serial_number'];

        $contentObjectAttributeData['DataTypeCustom']['serial_number'] = 0;
        return $contentObjectAttributeData['DataTypeCustom']['serial_number'];
    }

    /*!
     Fetches image information from the old 3.2 image system and creates new information.
    */
    function generateXMLData()
    {
        // VS-DBFILE
        // VS: I feel we don't need clustering support for the old image system. 

        include_once( "lib/ezdb/classes/ezdb.php" );

        $db =& eZDB::instance();

        $contentObjectAttributeData =& $this->ContentObjectAttributeData;
        $attributeID = $contentObjectAttributeData['id'];
        $attributeVersion = $contentObjectAttributeData['version'];

        if ( is_numeric( $attributeID ) )
        {
            $imageRow = $db->arrayQuery( "SELECT * FROM ezimage
                                           WHERE contentobject_attribute_id=$attributeID AND
                                                 version=$attributeVersion" );
        }

        $doc = new eZDOMDocument();
        $imageNode = $doc->createElementNode( "ezimage" );
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
            require_once( 'kernel/classes/ezclusterfilehandler.php' );
            $fileHandler = eZClusterFileHandler::instance();

            $fileName = $imageRow[0]['filename'];
            $originalFileName = $imageRow[0]['original_filename'];
            $mimeType = $imageRow[0]['mime_type'];
            $altText = $imageRow[0]['alternative_text'];

            $dirPath = eZSys::storageDirectory() . '/original/image';
            $filePath = $dirPath . '/' . $fileName;

            // VS-DBFILE : TODO checked

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
                    // VS-DBFILE : TODO checked

                    $referenceFilePath = $referenceDirPath . '/' . $baseName . '.' . $suffix;
                    if ( file_exists( $referenceFilePath ) )
                    {
                        $filePath = $referenceFilePath;
                        $dirPath = $referenceDirPath;
                        break;
                    }
                }
            }

            // VS-DBFILE : TODO checked

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
                $contentVersion = eZContentObjectVersion::fetchVersion( $contentObjectAttributeData['version'],
                                                                        $contentObjectAttributeData['contentobject_id'] );
                if ( $contentVersion )
                {
                    $objectName = $this->imageName( $contentObjectAttributeData, $contentVersion );
                    $objectPathString = $this->imagePath( $contentObjectAttributeData, $contentVersion );

                    $newDirPath =  $objectPathString;
                    $newFileName = $objectName . '.' . $mimeInfo['suffix'];
                    $newSuffix = $mimeInfo['suffix'];
                    $newFilePath = $newDirPath . '/' . $newFileName;
                    $newBaseName = $objectName;
                }

                // VS-DBFILE : TODO checked

                if ( $newFilePath != $filePath )
                {
                    if ( !file_exists( $newDirPath ) )
                    {
                        include_once( 'lib/ezfile/classes/ezdir.php' );
                        eZDir::mkdir( $newDirPath, eZDir::directoryPermission(), true );
                    }
                    eZFileHandler::copy( $filePath, $newFilePath );

                    // VS-DBFILE : TODO checked

                    //require_once( 'kernel/classes/ezclusterfilehandler.php' );
                    //$fileHandler = eZClusterFileHandler::instance();
                    //$fileHandler->fileCopy( $filePath, $newFilePath );

                    $filePath = $newFilePath;
                    $fileName = $newFileName;
                    $suffix = $newSuffix;
                    $dirPath = $newDirPath;
                    $baseName = $newBaseName;
                }
            }

        /*
        // Fetch variations
        $imageVariationRowArray = $db->arrayQuery( "SELECT * FROM ezimagevariation
                                           WHERE contentobject_attribute_id=$attributeID AND
                                                 version=$attributeVersion" );

        foreach ( $imageVariationRowArray as $variationRow )
        {
            unset( $imageVariationNode );
            $imageVariationNode = $doc->createElementNode( "variation" );
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

        $attr = false;
        $this->storeDOMTree( $doc, true, $attr );


        eZImageFile::appendFilepath( $contentObjectAttributeData['id'], $filePath );
    }

    /// \privatesection
    /// Contains a some eZContentObjectAttribute's attributes.
    var $ContentObjectAttributeData;
    /// Deprecated. Contains a reference to the object attribute
    var $ContentObjectAttribute;

}
?>
