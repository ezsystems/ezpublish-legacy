<?php
//
// Definition of eZImageManager class
//
// Created on: <01-Mar-2002 14:23:49 amos>
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

/*! \defgroup eZImage Image conversion and scaling */

/*!
  \class eZImageManager ezimagemanager.php
  \ingroup eZImage
  \brief Manages image operations using delegates to do the work

  The manager allows for transparent conversion of one image format
  to another. The conversion may be done in one step if the required
  conversion type is available or it may build a tree of conversion
  rules which is needed to reach the desired end format.

  It's also possible to run operations on images. It's up to each conversion
  rule to report whether or not the operation is supported, the manager will
  then distribute the operations on the available rules which can handle them.
  Examples of operations are scaling and grayscale.

  The scale operation is special and is known to the manager directly while
  the other operations must be recognized by the converter.

  In determing what image rules to be used the manager must first know
  which output types are allowed, this is set with setOutputTypes().
  It takes an array of mimetypes which are allowed.

  The manager must then be fed conversion rules, these tell which conversion
  type is used for converting from the source mimetype to the destination
  mimetype. The rules are set with setRules() which accepts an array of
  rules and a default rule as paremeter. The default rule is used when no
  other mimetype match is found.
  To create a rule you should use the createRule() function, it takes the source
  and destination mimetype as well as the conversion type name. Optionally
  it can specified whether the rule can scale or run operations.

  The last thing that needs to be done is to specify the mimetypes. The manager
  uses mimetypes internally to know what type of image it's working on.
  To go from a filename to a mimetype a set of matches must be setup. The matches
  are created with createMIMEType() which takes the mimetype, regex filename match
  and suffix as parameter. The mimetypes are then registered with setMIMETypes().

  See <a href="http://www.iana.org/">www.iana.org</a> for information on MIME types.

  Now the manager is ready and you can convert images with convert().

  Example:
\code
$img =& eZImageManager::instance();
$img->registerType( "convert", new eZImageShell( '', "convert", array(), array(),
                                                 array( eZImageShell::createRule( "-geometry %wx%h>", // Scale rule
                                                                                  "modify/scale" ),
                                                        eZImageShell::createRule( "-colorspace GRAY", // Grayscale rule
                                                                                  "colorspace/gray" ) ) ) ); // Register shell program convert
$img->registerType( "gd", new eZImageGD() ); // Register PHP converter GD

$img->setOutputTypes( array( "image/jpeg",
                             "image/png" ) ); // We only want jpeg and png, gif is not recommended due to licencing issues.
$rules = array( $img->createRule( "image/jpeg", "image/jpeg", "GD", true, false ), // Required for scaling jpeg images
                $img->createRule( "image/gif", "image/png", "convert", true, false ) ); // Convert GIF to png
$img->setRules( $rules, $img->createRule( "*", "image/png", "convert", true, false ) ); // Convert all other images to PNG with convert

$mime_rules = array( $img->createMIMEType( "image/jpeg", "\.jpe?g$", "jpg" ),
                     $img->createMIMEType( "image/png", "\.png$", "png" ),
                     $img->createMIMEType( "image/gif", "\.gif$", "gif" ) );
$img->setMIMETypes( $mime_rules ); // Register mimetypes

$img1 = $img->convert( "image1.gif", "cache/" ); // Convert GIF and places it in cache dir
$img1 = $img->convert( "image1.png", "cache/", // Scale PNG image and place in cache dir
                       array( "width" => 200, "height" => 200 ), // Scale parameter
                       array( array( "rule-type" => "colorspace/gray" ) ) ); // Gray scale conversion
 \endcode


*/

include_once( 'lib/ezutils/classes/ezini.php' );

class eZImageManager
{
    /*!
     Initializes the manager by registering a application/octet-stream mimetype
     which is applied for all unknown files.
    */
    function eZImageManager()
    {
//         $this->MIMEOctet =& $this->createMIMEType( "application/octet-stream", "^.+$", "" );
        $this->SupportedFormats = array();
        $this->SupportedMIMEMap = array();
        $this->ImageHandlers = array();
        $this->AliasList = array();
        $this->Factories[] = array();

        $ini =& eZINI::instance( 'image.ini' );
        $this->TemporaryImageDirPath = eZSys::cacheDirectory() . '/' . $ini->variable( 'FileSettings', 'TemporaryDir' );
    }

    /*!
     Sets which MIME types are allowed to use for destination format, this is an array of MIME type names.
     e.g.
     \code
     $manager->setOutputTypes( array( 'image/jpeg', 'image/gif' ) );
     \endcode
    */
    function setSupportedFormats( $mimeList )
    {
        $this->SupportedFormats = $mimeList;
        $this->SupportedMIMEMap = array();
        foreach ( $mimeList as $mimeName )
        {
            $this->SupportedMIMEMap[$mimeName] = true;
        }
    }

    /*!
     Sets which MIME types are allowed to use for destination format, this is an array of MIME type names.
     e.g.
     \code
     $manager->setOutputTypes( array( 'image/jpeg', 'image/gif' ) );
     \endcode
    */
    function appendSupportedFormat( $mimeName )
    {
        $this->SupportedFormats[] = $mimeName;
        $this->SupportedMIMEMap[$mimeName] = true;
    }

    function appendImageHandler( &$handler )
    {
        if ( !$handler )
            return false;
        if ( !$handler->isAvailable() )
            return false;
        $this->ImageHandlers[] =& $handler;
        return true;
    }

    function aliasList()
    {
        $aliasList = $this->AliasList;
        if ( !isset( $aliasList['original'] ) )
        {
            $alias = array( 'name' => 'original',
                            'reference' => false,
                            'mime_type' => false,
                            'filters' => array() );
            $alias['alias_key'] = $this->createImageAliasKey( $alias );
            $aliasList['original'] = $alias;
        }
        return $aliasList;
    }

    function hasAlias( $aliasName )
    {
        $aliasList = $this->aliasList();
        return array_key_exists( $aliasName, $aliasList );
    }

    /*!
     \return the definition for the Image Alias named \a $aliasName.
    */
    function alias( $aliasName )
    {
        $aliasList = $this->aliasList();
        if ( !array_key_exists( $aliasName, $aliasList ) )
            return false;
        return $aliasList[$aliasName];
    }

    function appendImageAlias( $alias )
    {
        $key = $this->createImageAliasKey( $alias );
        $alias['alias_key'] = $key;
        $this->AliasList[$alias['name']] = $alias;
        return $key;
    }

    /*!
     Creates a unique key for the image alias and returns it.
     \note The key is an MD5 of the alias settings and is used to determine if alias settings has changed.
    */
    function createImageAliasKey( $alias )
    {
        $keyData = array( $alias['name'],
                          $alias['reference'],
                          $alias['mime_type'] );
        if ( $alias['reference'] )
        {
            $referenceAlias = $this->alias( $alias['reference'] );
            if ( $referenceAlias )
                $keyData[] = $referenceAlias['alias_key'];
        }
        foreach ( $alias['filters'] as $filter )
        {
            $filterData = $filter['name'];
            if ( is_array( $filter['data'] ) )
                $filterData .= '=' . implode( ',', $filter['data'] );
            $keyData[] = $filterData;
        }
        $key = crc32( implode( "\n", $keyData ) );
        return $key;
    }

    /*!
     \return \c true if the Image Alias \a $alias is valid for use.
             This is tested by checking the key against the key for current Image Alias settings.
    */
    function isImageAliasValid( $alias )
    {
        $aliasName = $alias['name'];
        if ( isset( $this->AliasList[$aliasName] ) )
        {
            $aliasKey = $alias['alias_key'];
            $aliasInfo = $this->AliasList[$aliasName];
            $checkKey = $aliasInfo['alias_key'];
            $isValid = ( $aliasKey == $checkKey );
            return $isValid;
        }
        return false;
    }

    function readImageAliasesFromINI( $iniFile = false )
    {
        if ( !$iniFile )
            $iniFile = 'image.ini';
        $ini =& eZINI::instance( $iniFile );
        if ( !$ini )
            return false;
        $aliasNames = $ini->variable( 'AliasSettings', 'AliasList' );
        foreach ( $aliasNames as $aliasName )
        {
            $alias = $this->createAliasFromINI( $aliasName );
            if ( $alias )
            {
                $this->appendImageAlias( $alias );
            }
            else
                eZDebug::writeWarning( "Failed reading Image Alias $aliasName from $iniFile",
                                       'eZImageManager::readImageAliasFromINI' );
        }
    }

    function readSupportedFormatsFromINI( $iniFile = false )
    {
        if ( !$iniFile )
            $iniFile = 'image.ini';
        $ini =& eZINI::instance( $iniFile );
        if ( !$ini )
            return false;
        $allowedOutputFormats = $ini->variable( 'OutputSettings', 'AllowedOutputFormat' );
        foreach ( $allowedOutputFormats as $allowedOutputFormat )
        {
            $this->appendSupportedFormat( $allowedOutputFormat );
        }
    }

    function readImageHandlersFromINI( $iniFile = false )
    {
        if ( !$iniFile )
            $iniFile = 'image.ini';
        $ini =& eZINI::instance( $iniFile );
        if ( !$ini )
            return false;
        $handlerList = $ini->variable( 'ImageConverterSettings', 'ImageConverters' );
        foreach ( $handlerList as $handlerName )
        {
            if ( $ini->hasGroup( $handlerName ) )
            {
                if ( $ini->hasVariable( $handlerName, 'Handler' ) )
                {
                    $factoryName = $ini->variable( $handlerName, 'Handler' );
                    $factory =& $this->factoryFor( $factoryName, $iniFile );
                    if ( $factory )
                    {
                        $convertHandler =& $factory->produceFromINI( $handlerName, $iniFile );
                        $this->appendImageHandler( $convertHandler );
                    }
                }
                else
                {
                    eZDebug::writeWarning( "INI group $handlerName does not have a Handler setting, cannot instantiate handler without it",
                                           'eZImageManager::readImageHandlersFromINI' );
                }
            }
            else
            {
                eZDebug::writeWarning( "No INI group $handlerName for Image Handler $handlerName, cannot instantiate",
                                       'eZImageManager::readImageHandlersFromINI' );
            }
        }
    }

    function &factoryFor( $factoryName, $iniFile = false )
    {
        if ( !$iniFile )
            $iniFile = 'image.ini';
        if ( isset( $this->Factories[$factoryName] ) )
        {
            return $this->Factories[$factoryName];
        }
        else
        {
            include_once( 'lib/ezutils/classes/ezextension.php' );
            if ( eZExtension::findExtensionType( array( 'ini-name' => $iniFile,
                                                        'repository-group' => 'ImageConverterSettings',
                                                        'repository-variable' => 'RepositoryList',
                                                        'extension-group' => 'ImageConverterSettings',
                                                        'extension-variable' => 'ExtensionList',
                                                        'extension-subdir' => 'imagehandler',
                                                        'alias-group' => 'ImageConverterSettings',
                                                        'alias-variable' => 'ImageHandlerAlias',
                                                        'suffix-name' => 'handler.php',
                                                        'type-directory' => false,
                                                        'type' => $factoryName ),
                                                 $result ) )
            {
                $filepath = $result['found-file-path'];
                include_once( $filepath );
                $className = $result['type'] . 'factory';
                if ( class_exists( $className ) )
                {
                    $factory = new $className();
                    $this->Factories[$factoryName] =& $factory;
                    return $factory;
                }
                else
                {
                    eZDebug::writeWarning( "The Image Factory class $className was not found, cannot create factory",
                                           'eZImageManager::factoryFor' );
                }
            }
            else
            {
                eZDebug::writeWarning( "Could not locate Image Factory for $factoryName",
                                       'eZImageManager::factoryFor' );
            }
        }
        return false;
    }

    /*!
     Parses the filter text \a $filterText which is taken from an INI file
     and returns a filter data structure for it.
    */
    function createFilterDataFromINI( $filterText )
    {
        $equalPosition = strpos( $filterText, '=' );
        $filterData = false;
        if ( $equalPosition !== false )
        {
            $filterName = substr( $filterText, 0, $equalPosition );
            $filterDataText = substr( $filterText, $equalPosition + 1 );
            $filterData = explode( ';', $filterDataText );
        }
        else
            $filterName = $filterText;
        return array( 'name' => $filterName,
                      'data' => $filterData );
    }

    /*!
     Parses the INI group \a $iniGroup and creates an Image Alias from it.
     \return the Image Alias structure.
    */
    function createAliasFromINI( $iniGroup )
    {
        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance( 'image.ini' );
        if ( !$ini->hasGroup( $iniGroup ) )
        {
            eZDebug::writeError( "No such group $iniGroup in ini file image.ini",
                                 'eZImageManager::createAliasFromINI' );
            return false;
        }
        $alias = array( 'name' => $iniGroup,
                        'reference' => false,
                        'mime_type' => false,
                        'filters' => array() );
        if ( $ini->hasVariable( $iniGroup, 'Name' ) )
            $alias['name'] = $ini->variable( $iniGroup, 'Name' );
        if ( $ini->hasVariable( $iniGroup, 'MimeType' ) )
            $alias['mime_type'] = $ini->variable( $iniGroup, 'MimeType' );
        if ( $ini->hasVariable( $iniGroup, 'Filters' ) )
        {
            $filters = array();
            $filterRawList = $ini->variable( $iniGroup, 'Filters' );
            foreach ( $filterRawList as $filterRawItem )
            {
                $filters[] = $this->createFilterDataFromINI( $filterRawItem );
            }
            $alias['filters'] = $filters;
        }
        if ( $ini->hasVariable( $iniGroup, 'Reference' ) )
            $alias['reference'] = $ini->variable( $iniGroup, 'Reference' );
        return $alias;
    }

    /*!
     Makes sure the Image Alias \a $aliasName is created. It will check if referenced
     image aliases exists and if not create those also.
     \return \c true if successful
    */
    function createImageAlias( $aliasName, &$existingAliasList, $parameters = array() )
    {
        $aliasList = $this->aliasList();
        if ( !isset( $aliasList[$aliasName] ) )
        {
            eZDebug::writeWarning( "Alias name $aliasName does not exist, cannot create it" );
            return false;
        }
        $currentAliasInfo = $aliasList[$aliasName];
        $referenceAlias = $currentAliasInfo['reference'];
        if ( !$referenceAlias )
            $referenceAlias = 'original';
        $hasReference = false;
        if ( array_key_exists( $referenceAlias, $existingAliasList ) )
        {
            if ( file_exists( $existingAliasList[$referenceAlias]['full_path'] ) )
            {
                $hasReference = true;
            }
            else
            {
                eZDebug::writeError( "The reference alias $referenceAlias file " . $existingAliasList[$referenceAlias]['full_path'] . " does not exist",
                                     'eZImageManager::createImageAlias' );
            }
        }
        if ( !$hasReference )
        {
            if ( $referenceAlias == 'original' )
            {
                eZDebug::writeError( "Original alias does not exists, cannot create other aliases without it" );
                return false;
            }
            if ( !$this->createImageAlias( $referenceAlias, $existingAliasList, $parameters ) )
            {
                eZDebug::writeError( "Failed creating the referenced alias $referenceAlias, cannot create alias $aliasName",
                                     'eZImageManager::createImageAlias' );
                return false;
            }
        }
        if ( array_key_exists( $referenceAlias, $existingAliasList ) )
        {
            $aliasInfo = $existingAliasList[$referenceAlias];
            $aliasFile = $aliasInfo['full_path'];
            $aliasKey = $currentAliasInfo['alias_key'];
//             if ( $aliasFile )
//                 $aliasFile .= '/' . $aliasInfo['basename'];
            if ( file_exists( $aliasFile ) )
            {
                $sourceMimeData = eZMimeType::findByFileContents( $aliasFile );
                $destinationMimeData = $sourceMimeData;
                if ( isset( $parameters['basename'] ) )
                {
                    $sourceMimeData['basename'] = $parameters['basename'];
//                     $destinationMimeData['basename'] = $parameters['basename'];
                    eZMimeType::changeBasename( $destinationMimeData, $parameters['basename'] );
                }
//                 $destinationMimeData['filename'] = $destinationMimeData['basename'] . '.' . $destinationMimeData['suffix'];
//                 if ( $destinationMimeData['dirpath'] )
//                     $destinationMimeData['url'] = $destinationMimeData['dirpath'] . '/' . $destinationMimeData['filename'];
//                 else
//                     $destinationMimeData['url'] = $destinationMimeData['filename'];
                $destinationMimeData['is_valid'] = false;
                if ( !$this->convert( $sourceMimeData, $destinationMimeData, $aliasName, $parameters ) )
                {
                    $sourceFile = $sourceMimeData['url'];
                    $destinationDir = $destinationMimeData['dirpath'];
                    eZDebug::writeError( "Failed converting $sourceFile to alias $referenceAlias in directory $destinationDir",
                                         'eZImageManager::createImageAlias' );
                    return false;
                }
//                 $destinationMimeData = $destinationDir;
                $currentAliasData = array( 'url' => $destinationMimeData['url'],
                                           'dirpath' => $destinationMimeData['dirpath'],
                                           'filename' => $destinationMimeData['filename'],
                                           'suffix' => $destinationMimeData['suffix'],
//                                            'fileextra' => $destinationMimeData['fileextra'],
                                           'basename' => $destinationMimeData['basename'],
                                           'alternative_text' => $aliasInfo['alternative_text'],
                                           'name' => $aliasName,
                                           'alias_key' => $aliasKey,
                                           'mime_type' => $destinationMimeData['name'],
                                           'width' => false,
                                           'height' => false,
                                           'is_valid' => true,
                                           'is_new' => true );
                $currentAliasData['full_path'] =& $currentAliasData['url'];
                if ( function_exists( 'getimagesize' ) )
                {
                    if ( file_exists( $destinationMimeData['url'] ) )
                    {
                        $info = getimagesize( $destinationMimeData['url'] );
                        if ( $info )
                        {
                            $width = $info[0];
                            $height = $info[1];
                            $currentAliasData['width'] = $width;
                            $currentAliasData['height'] = $height;
                        }
                    }
                    else
                        eZDebug::writeError( "The destination image " . $destinationMimeData['url'] . " does not exist, cannot figure out image size", 'eZImageManager::createImageAlias' );
                }
                else
                    eZDebug::writeError( "Unknown function 'getimagesize' cannot get image size", 'eZImageManager::createImageAlias' );
                $existingAliasList[$aliasName] = $currentAliasData;
                return true;
            }
        }
        return false;
    }

    /*!
     Converts the source image \a $sourceMimeData into the destination image \a $destinationMimeData.
     The source image can be supplied with the full path to the image instead of the MIME structure.
     The destination image can be supplied with full path or dirpath to the destination image instead of the MIME structure.
     \param $aliasName determines the Image Alias to use for conversion, this usually adds some filters to the conversion.
    */
    function convert( $sourceMimeData, &$destinationMimeData, $aliasName = false, $parameters = array() )
    {
        if ( is_string( $sourceMimeData ) )
            $sourceMimeData = eZMimeType::findByFileContents( $sourceMimeData );
        $currentMimeData = $sourceMimeData;
        $handlers =& $this->ImageHandlers;
        $supportedMIMEMap = $this->SupportedMIMEMap;
        if ( is_string( $destinationMimeData ) )
        {
            $destinationPath = $destinationMimeData;
            $destinationMimeData = eZMimeType::findByFileContents( $destinationPath );
        }
        $filters = array();
        $alias = false;
        if ( $aliasName )
        {
            $aliasList = $this->aliasList();
            if ( isset( $aliasList[$aliasName] ) )
            {
                $alias = $aliasList[$aliasName];
                $filters = $alias['filters'];
                if ( $alias['mime_type'] )
                {
                    eZMimeType::changeMIMEType( $destinationMimeData, $alias['mime_type'] );
                }
            }
        }
        if ( !$destinationMimeData['is_valid'] )
        {
            $destinationDirPath = $destinationMimeData['dirpath'];
            $destinationBasename = $destinationMimeData['basename'];
            if ( isset( $supportedMIMEMap[$sourceMimeData['name']] ) )
            {
                $destinationMimeData = $sourceMimeData;
                if ( $alias['mime_type'] )
                {
                    eZMimeType::changeMIMEType( $destinationMimeData, $alias['mime_type'] );
                }
                eZMimeType::changeFileData( $destinationMimeData, $destinationDirPath, $destinationBasename );
            }
            else
            {
                $hasDestination = false;
                foreach ( array_keys( $handlers ) as $handlerKey )
                {
                    $handler =& $handlers[$handlerKey];
                    $gotMimeData = true;
                    while( $gotMimeData )
                    {
                        $gotMimeData = false;
                        $outputMimeData = $handler->outputMIMEType( $sourceMimeData, false, $this->SupportedFormats, $aliasName );
                        if ( $outputMimeData and
                             isset( $supportedMIMEMap[$outputMimeData['name']] ) )
                        {
                            $destinationMimeData = $outputMimeData;
                            eZMimeType::changeFileData( $destinationMimeData, $destinationDirPath, $destinationBasename );
                            $hasDestination = true;
                            $gotMimeData = true;
                            break;
                        }
                    }
                }
                if ( !$hasDestination )
                    return false;
            }
        }

        $result = true;
        if ( $currentMimeData['name'] != $destinationMimeData['name'] or
             count( $filters ) > 0 )
        {
            while ( $currentMimeData['name'] != $destinationMimeData['name'] or
                    count( $filters ) > 0 )
            {
                $nextMimeData = false;
                $nextHandler = false;
                foreach ( array_keys( $handlers ) as $handlerKey )
                {
                    $handler =& $handlers[$handlerKey];
                    if ( !$handler )
                        continue;
                    $outputMimeData = $handler->outputMIMEType( $currentMimeData, $destinationMimeData, $this->SupportedFormats, $aliasName );
                    if ( $outputMimeData['name'] == $destinationMimeData['name'] )
                    {
                        $nextMimeData = $outputMimeData;
                        $nextHandler =& $handler;
                        break;
                    }
                    if ( $outputMimeData and
                         !$nextMimeData )
                    {
                        $nextMimeData = $outputMimeData;
                        $nextHandler =& $handler;
                    }
                }
                if ( !$nextMimeData )
                {
                    eZDebug::writeError( "None of the handlers can convert MIME type " . $currentMimeData['name'],
                                         'eZImageManager::convert' );
                    return false;
                }
                $handlerFilters = array();
                $leftoverFilters = array();
                foreach ( $filters as $filter )
                {
                    if ( $nextHandler->isFilterSupported( $filter ) )
                        $handlerFilters[] = $filter;
                    else
                        $leftoverFilters[] = $filter;
                }
                $useTempImage = false;
                if ( $nextMimeData['name'] == $destinationMimeData['name'] and
                     count( $leftoverFilters ) == 0 )
                {
                    $nextMimeData['dirpath'] = $destinationMimeData['dirpath'];
                }
                else
                {
                    $useTempImage = true;
                    $nextMimeData['dirpath'] = $this->temporaryImageDirPath();
                }
                eZMimeType::changeDirectoryPath( $nextMimeData, $nextMimeData['dirpath'] );

                if ( $nextMimeData['dirpath'] and
                     !file_exists( $nextMimeData['dirpath'] ) )
                    eZDir::mkdir( $nextMimeData['dirpath'], eZDir::directoryPermission(), true );
                if ( $currentMimeData['name'] == $nextMimeData['name'] and
                     count( $handlerFilters ) == 0 )
                {
                    if ( $currentMimeData['url'] != $nextMimeData['url'] )
                    {
                        include_once( 'lib/ezfile/classes/ezfilehandler.php' );
                        if ( eZFileHandler::copy( $currentMimeData['url'], $nextMimeData['url'] ) )
                        {
                            if ( $useTempImage )
                                $tempFiles[] = $nextMimeData['url'];
                        }
                        else
                        {
                            $result = false;
                            break;
                        }
                    }
                    $currentMimeData = $nextMimeData;
                }
                else
                {
                    if ( $nextHandler->convert( $currentMimeData, $nextMimeData, $handlerFilters ) )
                    {
                        if ( $useTempImage )
                            $tempFiles[] = $nextMimeData['url'];
                    }
                    else
                    {
                        $result = false;
                        break;
                    }
                    $currentMimeData = $nextMimeData;
                }
                $filters = $leftoverFilters;
//             !isset( $supportedMIMEMap[$currentMimeData['name']]
            }
        }
        else
        {
            if ( $sourceMimeData['url'] != $destinationMimeData['url'] )
            {
                include_once( 'lib/ezfile/classes/ezfilehandler.php' );
                eZFileHandler::copy( $sourceMimeData['url'], $destinationMimeData['url'] );
                $currentMimeData = $destinationMimeData;
            }
        }
        foreach ( $tempFiles as $tempFile )
        {
            if ( !@unlink( $tempFile ) )
            {
                eZDebug::writeError( "Failed to unlink temporary image file $tempFile",
                                     'eZImageManager::convert' );
            }
        }
        $destinationMimeData = $currentMimeData;
        return $result;
    }

    function temporaryImageDirPath()
    {
        return $this->TemporaryImageDirPath;
    }


//     /*!
//      Registers a new conversion type which can handle conversion and image operations.
//      The name $name represents the conversion name which is used in the conversion rules.
//      $type is the conversion object.
//     */
//     function registerType( $name, &$type )
//     {
//         $this->Types[$name] =& $type;
//     }

//     /*!
//      Not used for now, may be deleted?
//     */
//     function ruleFor( $from )
//     {
//         if ( isset( $this->RuleMap[$from] ) )
//             return $this->RuleMap[$from];
//         else
//             return $this->DefaultRule;
//     }

//     /*!
//      Returns true if the mimetype $type is accepted as an output type.
//     */
//     function isDisplayType( $type )
//     {
//         return isset( $this->OutputMIMEMap[$type] );
//     }

//     /*!
//      Creates a MIME type structure.
//      $mime is the mimetype name,
//      $match is the regex file match,
//      $suffix is the filename suffix which is append to the converted filename.
//     */
//     function &createMIMEType( $mime, $match, $suffix )
//     {
//         $type = array();
//         $type["mime-type"] =& $mime;
//         $type["match"] =& $match;
//         $type["suffix"] =& $suffix;
//         return $type;
//     }

//     /*!
//      Register the mimetypes $types.
//     */
//     function setMIMETypes( $types )
//     {
//         $this->MIMETypes = $types;
//         $this->MIMEMap = array();
//         reset( $this->MIMETypes );
//         while ( ( $key = key( $this->MIMETypes ) ) !== null )
//         {
//             $type =& $this->MIMETypes[$key];
//             $this->MIMEMap[$type["mime-type"]] =& $type;
//             next( $this->MIMETypes );
//         }
//     }

//     /*!
//      Returns the mimetype for the file $file.
//      If $as_obj is true the whole mimetype structure is returned.
//     */
//     function mimeTypeFor( $file, $as_obj = false )
//     {
//         foreach ( $this->MIMETypes as $mime )
//         {
//             $reg = "/" . $mime["match"] . "/i";
//             if ( preg_match( $reg, $file ) )
//             {
//                 if ( $as_obj )
//                     return $mime;
//                 else
//                     return $mime["mime-type"];
//             }
//         }
//         if ( $as_obj )
//             return $this->MIMEOctet;
//         else
//             return $this->MIMEOctet["mime-type"];
//     }

//     /*!
//      Returns the conversion rules which is required for transforming the
//      mimetype $from to an output format. If scale is supplied the scaling
//      is taking into account when compressing rules.
//     */
//     function &convertRules( $from, $scale = false )
//     {
//         $rule = null;
//         $rules = array();
//         $cur = $from;
//         $i = 0;
//         $used_types = array();
//         if ( $this->isDisplayType( $cur ) and $scale !== false )
//         {
//             if ( isset( $this->RuleMap[$cur] ) )
//                 $rule = $this->RuleMap[$cur];
//             else
//             {
//                 $rule = $this->DefaultRule;
//                 $rule["from"] = $cur;
//             }
//             $rules[] = $rule;
//             $used_types[$cur] = true;
//             $cur = $rule["to"];
//         }
//         else
//         {
//             while ( !$this->isDisplayType( $cur ) )
//             {
//                 if ( isset( $used_types[$cur] ) )
//                 {
//                     return false;
//                 }
//                 if ( isset( $this->RuleMap[$cur] ) )
//                     $rule = $this->RuleMap[$cur];
//                 else
//                 {
//                     $rule = $this->DefaultRule;
//                     $rule["from"] = $cur;
//                 }
//                 $rules[] = $rule;
//                 $used_types[$cur] = true;
//                 $cur = $rule["to"];
//             }
//         }
//         $this->compressRules( $rules, $scale );
//         return $rules;
//     }

//     /*!
//      Adds the scale rule $scale to the first rule which can perform scaling.
//      $rules is usually the rules returned from convertRules().
//     */
//     function addScaleRule( &$rules, $scale )
//     {
//         for ( $i = 0; $i < count( $rules ); ++$i )
//         {
//             $rule =& $rules[$i];
//             if ( $rule["canscale"] )
//             {
//                 $rule["scale"] =& $scale;
//                 break;
//             }
//         }
//     }

//     /*!
//      Adds the filter rules $filter to the first rule which can perform filtering.
//      $rules is usually the rules returned from convertRules().
//     */
//     function addFilterRule( &$rules, $filter )
//     {
//         for ( $i = 0; $i < count( $rules ); ++$i )
//         {
//             $rule =& $rules[$i];
//             if ( $rule["canfilter"] )
//             {
//                 $rule["filter"] =& $filter;
//                 break;
//             }
//         }
//     }

//     /*!
//      Converst the image file $file to an output format and places it in
//      $dest. Scaling is handled with $scale and filters with $filters.
//      If $mime is false then the mimetype i fetched from the $file.
//     */
//     function convert( $file, $dest, $scale = false, $filters = false,
//                       $mime = false )
//     {
//         if ( $mime === false )
//             $mime = $this->mimeTypeFor( $file, true );
//         $rules =& $this->convertRules( $mime["mime-type"], $scale );
//         if ( $scale !== false )
//             $this->addScaleRule( $rules, $scale );
//         if ( is_array( $filters ) )
//             $this->addFilterRule( $rules, true );
//         $suffix = $mime["suffix"];
//         $dirs = "";
//         if ( preg_match( "#(.+/)?(.+)$#", $file, $matches ) )
//         {
//             $dirs = $matches[1];
//             $file = $matches[2];
//         }
//         $base = $file;
//         if ( preg_match( "/(.+)" . $mime["match"] . "/i", $file, $matches ) )
//         {
//             $base = $matches[1];
//         }
//         if ( is_dir( $dest ) )
//         {
//             $dest_dirs = $dest;
//             $dest_file = $file;
//             $dest_base = $base;
//         }
//         else
//         {
//             $dest_dirs = "";
//             $dest_file = $dest;
//             if ( preg_match( "#(.+/)?(.+)$#", $dest, $matches ) )
//             {
//                 $dest_dirs = $matches[1];
//                 $dest_file = $matches[2];
//             }
//             $dest_base = $dest_file;
//             if ( preg_match( "/(.+)" . $mime["match"] . "/i", $dest_file, $matches ) )
//             {
//                 $dest_base = $matches[1];
//             }
//         }
//         $from_array = array();
//         $to_array = array();
//         $from_array["original-filename"] = $file;
//         $from_array["dir"] = $dirs;
//         $from_array["basename"] = $base;
//         $from_array["suffix"] = $suffix;
//         $to_array["original-filename"] = $dest_file;
//         $to_array["dir"] = $dest_dirs;
//         $to_array["basename"] = $dest_base;
//         $to_array["suffix"] = $suffix;
//         $out_file = $file;
//         for ( $i = 0; $i < count( $rules ); ++$i )
//         {
//             $rule =& $rules[$i];
//             $type =& $rule["type"];
//             $mime_type =& $this->MIMEMap[$rule["to"]];
//             $to_array["suffix"] = $mime_type["suffix"];
//             $from_array["mime-type"] = $rule["from"];
//             $to_array["mime-type"] = $rule["to"];
//             if ( isset( $this->Types[$type] ) )
//             {
//                 $type_obj =& $this->Types[$type];
// //                 $str = $type_obj->conversionString( $from_array, $to_array, $to_file );
//                 $scale_rule = $rule["scale"];
//                 $filter_rule = $rule["filter"];
//                 unset( $filter_array );
//                 $filter_array = false;
//                 if ( $filter_rule )
//                     $filter_array =& $filters;
//                 if ( $scale_rule !== false )
//                     $str = $type_obj->scale( $from_array, $to_array, $to_dir, $to_file,
//                                              $filter_array, $scale_rule );
//                 else
//                     $str = $type_obj->convert( $from_array, $to_array, $to_dir, $to_file,
//                                                $filter_array );
//                 $to_array["dir"] = $to_dir;
//                 $to_array["original-filename"] = $to_file;
//                 $out_file = $to_file;
//                 $rule["params"] = $str;
//             }
//             $from_array["suffix"] = $to_array["suffix"];
//             $from_array["basename"] = $to_array["basename"];
//             $from_array["original-filename"] = $to_array["original-filename"];
//             $from_array["dir"] = $to_array["dir"];
//         }

//         // Write log message to storage.log
//         include_once( 'lib/ezutils/classes/ezlog.php' );
//         list( $mimeType, $storedFileName ) = split(":", $to_file );
//         eZLog::writeStorageLog( $storedFileName, $dest_dirs );

//         return $from_array["dir"] . "/" . $out_file;
//     }

//     /*!
//      \private
//      Compresses the rules $rules so that a minimum number of conversion is required.
//      The scale $rule is taken into account.
//     */
//     function compressRules( &$rules, $scale )
//     {
//         $new_rules = array();
//         $last_type = "";
//         $last_rule = null;
//         for ( $i = 0; $i < count( $rules ); ++$i )
//         {
//             $rule =& $rules[$i];
//             $use_rule = true;
//             if ( $last_type == $rule["type"] )
//             {
//                 if ( $scale and isset( $last_rule ) )
//                 {
//                     if ( !$last_rule["canscale"] or !$rule["canscale"] )
//                         $use_rule = true;
//                     else
//                         $use_rule = false;
//                 }
//                 else
//                     $use_rule = false;
//             }
//             else
//             {
//                 $use_rule = true;
//             }
//             if ( $use_rule )
//             {
//                 $new_rules[] =& $rule;
//                 unset( $last_rule );
//                 $last_rule =& $rule;
//                 $last_type = $rule["type"];
//             }
//             else if ( isset( $last_rule ) )
//                 $last_rule["to"] = $rule["to"];
//         }
//         $rules = $new_rules;
//     }

//     /*!
//      Creates a new conversion rule and returns it.
//      $from the source mimetype,
//      $to the destination mimetype,
//      $type the image conversion type must be registered,
//      $scale true if the rule can scale the image,
//      $filter true if the rule can perform filters.
//     */
//     function &createRule( $from, $to, $type, $scale, $filter )
//     {
//         $rule = array();
//         $rule["from"] =& $from;
//         $rule["to"] =& $to;
//         $rule["type"] =& $type;
//         $rule["canscale"] =& $scale;
//         $rule["canfilter"] =& $filter;
//         $rule["scale"] = false;
//         $rule["filter"] = false;
//         $rule["params"] = "";
//         return $rule;
//     }

    /*!
     Returns the only instance of the image manager.
    */
    function &instance()
    {
        $instance =& $GLOBALS["eZImageManager"];
        if ( get_class( $instance ) != "ezimagemanager" )
        {
            $instance = new eZImageManager();
        }
        return $instance;
    }

    /// \privatesection
    var $ImageHandlers;
    var $OutputMIME;
    var $OutputMIMEMap;
    var $Rules;
    var $DefaultRule;
    var $RuleMap;
    var $MIMETypes;
    var $Types = array();
}

?>
