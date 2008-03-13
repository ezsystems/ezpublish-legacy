<?php
//
// Definition of eZImageManager class
//
// Created on: <01-Mar-2002 14:23:49 amos>
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
$img = eZImageManager::instance();
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

//include_once( 'lib/ezutils/classes/ezini.php' );

class eZImageManager
{
    /*!
     Initializes the manager by registering a application/octet-stream mimetype
     which is applied for all unknown files.
    */
    function eZImageManager()
    {
        $this->SupportedFormats = array();
        $this->SupportedMIMEMap = array();
        $this->ImageHandlers = array();
        $this->AliasList = array();
        $this->Factories = array();
        $this->ImageFilters = array();
        $this->MIMETypeSettings = array();
        $this->MIMETypeSettingsMap = array();
        $this->QualityValues = array();
        $this->QualityValueMap = array();

        $ini = eZINI::instance( 'image.ini' );
        $this->TemporaryImageDirPath = eZSys::cacheDirectory() . '/' . $ini->variable( 'FileSettings', 'TemporaryDir' );
        $this->lockTimeout = $ini->hasVariable( 'ImageConverterSettings', 'LockTimeout' ) ? $ini->variable( 'ImageConverterSettings', 'LockTimeout' ) : 60;
    }

    /*!
     Sets which MIME-Types are allowed to use for destination format, this is an array of MIME-Type names.
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
     Sets which MIME-Types are allowed to use for destination format, this is an array of MIME-Type names.
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

    /*!
     Appends the image handler \a $handler to the list of known handlers in the image system.
     Onces it is added the supported image filters for that handler is extracted.

     \note If the handler is not available (isAvailable()) it will not be added.
    */
    function appendImageHandler( $handler )
    {
        if ( !$handler )
            return false;
        if ( !$handler->isAvailable() )
            return false;
        $this->ImageHandlers[] = $handler;
        $this->ImageFilters = array_merge( $this->ImageFilters, $handler->supportedImageFilters() );
        $this->ImageFilters = array_unique( $this->ImageFilters );
        return true;
    }

    /*!
     \return \c true if the filtername \a $filtername is supported by any of the image handlers.
    */
    function isFilterSupported( $filterName )
    {
        return in_array( $filterName, $this->ImageFilters );
    }

    /*!
     Returns a list of defined image aliases in the image system.
     Each entry in the list is an associative array with the following keys:
     - name - The name of the alias
     - reference - The name of the alias it refers to or \c false if no reference
     - mime_type - Controls which MIME-Type the alias will be in, or \c false if not defined.
     - filters - An array with filters which applies to this alias
     - alias_key - The CRC key for this alias, it is created from the current values of the alias
                   and will change each time the alias values changes
    */
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

    /*!
     \return \c true if the image alias \a $aliasName exists.
    */
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

    /*!
     Appends the image alias \a $alias to the list of defined aliases.
    */
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

        //include_once( 'lib/ezutils/classes/ezsys.php' );
        $key = eZSys::ezcrc32( implode( "\n", $keyData ) );

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
            if ( $isValid )
            {
                $aliasTimestamp = $alias['timestamp'];
                $isValid = $this->isImageTimestampValid( $aliasTimestamp );
            }
            return $isValid;
        }
        return false;
    }

    /*!
     \return \c true if the timestamp \a $timestamp is newer than the image alias expiry timestamp.
     The image alias expiry timestamp will be set whenever the image aliases must be recreated.

     \note Normally the expiry timestamp is not set.
    */
    function isImageTimestampValid( $timestamp )
    {
        eZExpiryHandler::registerShutdownFunction();
        $expiryHandler = eZExpiryHandler::instance();
        if ( $expiryHandler->hasTimestamp( 'image-manager-alias' ) )
        {
            $aliasTimestamp = $expiryHandler->timestamp( 'image-manager-alias' );
            return ( $timestamp > $aliasTimestamp );
        }
        return true;
    }

    /*!
     Reads all image aliases from the INI file 'image.ini'
     and appends them to the image system.
     \param $iniFile The INI file to read from or if \c false use 'image.ini'
    */
    function readImageAliasesFromINI( $iniFile = false )
    {
        if ( !$iniFile )
            $iniFile = 'image.ini';
        $ini = eZINI::instance( $iniFile );
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
        $aliasName = 'original';
        if ( !in_array( $aliasName, $aliasNames ) )
        {
            //include_once( 'lib/ezutils/classes/ezini.php' );
            $ini = eZINI::instance( 'image.ini' );
            if ( $ini->hasGroup( $aliasName ) )
            {
                $alias = $this->createAliasFromINI( $aliasName );
                if ( $alias )
                {
                    $alias['reference'] = false;
                    $this->appendImageAlias( $alias );
                }
                else
                {
                    eZDebug::writeWarning( "Failed reading Image Alias $aliasName from $iniFile",
                                           'eZImageManager::readImageAliasFromINI' );
                }
            }
        }
    }

    /*!
     Reads all supported image formats from the INI file 'image.ini'
     and appends them to the image system.
     \param $iniFile The INI file to read from or if \c false use 'image.ini'
    */
    function readSupportedFormatsFromINI( $iniFile = false )
    {
        if ( !$iniFile )
            $iniFile = 'image.ini';
        $ini = eZINI::instance( $iniFile );
        if ( !$ini )
            return false;
        $allowedOutputFormats = $ini->variable( 'OutputSettings', 'AllowedOutputFormat' );
        foreach ( $allowedOutputFormats as $allowedOutputFormat )
        {
            $this->appendSupportedFormat( $allowedOutputFormat );
        }
    }

    /*!
     \return \c true if the MIME-Type defined in \a $mimeData exists in the image system.
    */
    function hasMIMETypeSetting( $mimeData )
    {
        return isset( $this->MIMETypeSettingsMap[$mimeData['name']] );
    }

    /*!
     \return The setting for the MIME-Type defined in \a $mimeData.
    */
    function mimeTypeSetting( $mimeData )
    {
        if ( !isset( $this->MIMETypeSettingsMap[$mimeData['name']] ) )
            return false;
        $list = $this->MIMETypeSettingsMap[$mimeData['name']];
        foreach ( $list as $item )
        {
            if ( is_array( $item['match'] ) )
            {
                if ( array_key_exists( 'info', $mimeData ) && is_array( $mimeData['info'] ) )
                {
                    $isMatch = true;
                    $info = $mimeData['info'];
                    foreach ( $item['match'] as $matchKey => $matchValue )
                    {
                        if ( !isset( $info[$matchKey] ) or
                             $info[$matchKey] != $matchValue )
                        {
                            $isMatch = false;
                            break;
                        }
                    }
                    if ( $isMatch )
                        return $item;
                }
            }
            else
                return $item;
        }
        return false;
    }

    /*!
     Calls eZImageHandler::wildcardToRegexp() to generate
     a regular expression out of the wilcard and return it.
    */
    static function wildcardToRegexp( $wildcard, $separatorCharacter = false )
    {
        return eZImageHandler::wildcardToRegexp( $wildcard, $separatorCharacter );
    }

    /*!
     \return The override MIME-Type for the MIME structure \a $mimeData or \c false if no override.
    */
    function mimeTypeOverride( $mimeData )
    {
        if ( $this->hasMIMETypeSetting( $mimeData ) )
        {
            $settings = $this->mimeTypeSetting( $mimeData );
            if ( $settings )
            {
                return $settings['override_mime_type'];
            }
        }
        return false;
    }

    /*!
     \return An array with extra filters for the MIME-Type defined in \a $mimeData
             or \c false if no filters.
    */
    function mimeTypeFilters( $mimeData )
    {
        if ( $this->hasMIMETypeSetting( $mimeData ) )
        {
            $settings = $this->mimeTypeSetting( $mimeData );
            if ( $settings )
            {
                return $settings['extra_filters'];
            }
        }
        return false;
    }

    /*!
     \return \c true if the filtername \a $filtername is allowed to be used on the type defined in \a $mimeData.
    */
    function isFilterAllowed( $filterName, $mimeData )
    {
        if ( $this->hasMIMETypeSetting( $mimeData ) )
        {
            $settings = $this->mimeTypeSetting( $mimeData );
            if ( $settings )
            {
                if ( is_array( $settings['disallowed_filters'] ) )
                {
                    foreach ( $settings['disallowed_filters'] as $filter )
                    {
                        $regexp = eZImageManager::wildcardToRegexp( $filter );
                        if ( preg_match( '#' . $regexp . '#', $filterName ) )
                        {
                            return false;
                        }
                    }
                }
                if ( is_array( $settings['allowed_filters'] ) )
                {
                    foreach ( $settings['allowed_filters'] as $filter )
                    {
                        $regexp = eZImageManager::wildcardToRegexp( $filter );
                        if ( preg_match( '#' . $regexp . '#', $filterName ) )
                        {
                            return true;
                        }
                    }
                    return false;
                }
                return true;
            }
        }
        return true;
    }

    /*!
     Binds the quality value \a $qualityValue to the MIME-Type \a $mimeType.
    */
    function appendQualityValue( $mimeType, $qualityValue )
    {
        $element = array( 'name' => $mimeType,
                          'value' => $qualityValue );
        $this->QualityValues[] = $element;
        $this->QualityValueMap[$mimeType] = $element;
    }

    /*!
     \return the quality value for MIME-Type \a $mimeType or \c false if none exists.
    */
    function qualityValue( $mimeType )
    {
        if ( isset( $this->QualityValueMap[$mimeType] ) )
            return $this->QualityValueMap[$mimeType]['value'];
        return false;
    }

    /*!
     Appends the MIME-Type setting \a $settings to the image system.
    */
    function appendMIMETypeSetting( $settings )
    {
        $this->MIMETypeSettings[] = $settings;
        if ( !isset( $this->MIMETypeSettingsMap[$settings['mime_type']] ) )
            $this->MIMETypeSettingsMap[$settings['mime_type']] = array();
        $this->MIMETypeSettingsMap[$settings['mime_type']][] = $settings;
    }

    /*!
     Reads all MIME-Type settings from the INI file 'image.ini'
     and appends them to the image system.
     \param $iniFile The INI file to read from or if \c false use 'image.ini'
    */
    function readMIMETypeSettingsFromINI( $iniFile = false )
    {
        if ( !$iniFile )
            $iniFile = 'image.ini';
        $ini = eZINI::instance( $iniFile );
        if ( !$ini )
            return false;
        $overrideList = $ini->variable( 'MIMETypeSettings', 'OverrideList' );
        foreach ( $overrideList as $mimeType )
        {
            $settings = eZImageManager::readMIMETypeSettingFromINI( $mimeType );
            if ( $settings )
                $this->appendMIMETypeSetting( $settings );
        }
    }

    /*!
     Reads MIME-Type quality settings and appends them.
    */
    function readMIMETypeQualitySettingFromINI( $iniFile = false )
    {
        if ( !$iniFile )
            $iniFile = 'image.ini';
        $ini = eZINI::instance( $iniFile );
        if ( !$ini )
            return false;
        if ( !$ini->hasVariable( 'MIMETypeSettings', 'Quality' ) )
            return false;
        $values = $ini->variable( 'MIMETypeSettings', 'Quality' );
        foreach ( $values as $value )
        {
            $elements = explode( ';', $value );
            $mimeType = $elements[0];
            $qualityValue = $elements[1];
            $this->appendQualityValue( $mimeType, $qualityValue );
        }
    }

    /*!
     Reads in global conversion rules from INI file.
    */
    function readConversionRuleSettingsFromINI( $iniFile = false )
    {
        if ( !$iniFile )
            $iniFile = 'image.ini';
        $ini = eZINI::instance( $iniFile );
        if ( !$ini )
            return false;
        if ( $ini->hasVariable( 'MIMETypeSettings', 'ConversionRules' ) )
        {
            $conversionRules = array();
            $rules = $ini->variable( 'MIMETypeSettings', 'ConversionRules' );
            foreach ( $rules as $ruleString )
            {
                $ruleItems = explode( ';', $ruleString );
                if ( count( $ruleItems ) >= 2 )
                {
                    $conversionRule = array( 'from' => $ruleItems[0],
                                             'to' => $ruleItems[1] );
                    $this->appendConversionRule( $conversionRule );
                }
            }
        }
    }

    /*!
     Will read in all required INI settings.
    */
    function readINISettings()
    {
        $this->readImageHandlersFromINI();
        $this->readSupportedFormatsFromINI();
        $this->readImageAliasesFromINI();
        $this->readMIMETypeSettingsFromINI();
        $this->readMIMETypeQualitySettingFromINI();
        $this->readConversionRuleSettingsFromINI();
    }

    /*!
     Appends a new global conversion rule.
    */
    function appendConversionRule( $conversionRule )
    {
        $this->ConversionRules[] = $conversionRule;
    }

    /*!
     \return The global conversion rules.
    */
    function conversionRules()
    {
        return $this->ConversionRules;
    }

    /*!
     Reads a single MIME-Type setting from the INI file 'image.ini'
     and appends them to the image system.
     \param $mimeGroup Which INI group to read settings from.
     \param $iniFile The INI file to read from or if \c false use 'image.ini'
     \return The settings that were read.
    */
    function readMIMETypeSettingFromINI( $mimeGroup, $iniFile = false )
    {
        if ( !$iniFile )
            $iniFile = 'image.ini';
        $ini = eZINI::instance( $iniFile );
        if ( !$ini )
            return false;
        if ( !$ini->hasGroup( $mimeGroup ) )
            return false;
        if ( !$ini->hasVariable( $mimeGroup, 'MIMEType' ) )
            return false;
        $settings = array( 'name' => $mimeGroup,
                           'match' => false,
                           'mime_type' => false,
                           'override_mime_type' => false,
                           'allowed_filters' => false,
                           'disallowed_filters' => false,
                           'extra_filters' => false );
        $settings['mime_type'] = $ini->variable( $mimeGroup, 'MIMEType' );
        $ini->assign( $mimeGroup, 'Match', $settings['match'] );
        $ini->assign( $mimeGroup, 'OverrideMIMEType', $settings['override_mime_type'] );
        $ini->assign( $mimeGroup, 'AllowedFilters', $settings['allowed_filters'] );
        $ini->assign( $mimeGroup, 'DisallowedFilters', $settings['disallowed_filters'] );
        if ( $ini->hasVariable( $mimeGroup, 'ExtraFilters' ) )
        {
            $filters = array();
            $filterRawList = $ini->variable( $mimeGroup, 'ExtraFilters' );
            foreach ( $filterRawList as $filterRawItem )
            {
                $filters[] = $this->createFilterDataFromINI( $filterRawItem );
            }
            if ( count( $filters ) > 0 )
                $settings['extra_filters'] = $filters;
        }
        return $settings;
    }

    /*!
     Reads all settings for image handlers from the INI file 'image.ini'
     and appends them to the image system.
     \param $iniFile The INI file to read from or if \c false use 'image.ini'
    */
    function readImageHandlersFromINI( $iniFile = false )
    {
        if ( !$iniFile )
            $iniFile = 'image.ini';
        $ini = eZINI::instance( $iniFile );
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
                    $factory = $this->factoryFor( $factoryName, $iniFile );
                    if ( $factory )
                    {
                        $convertHandler = $factory->produceFromINI( $handlerName, $iniFile );
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

    /*!
     Finds the image handler factory with the name \a $factoryName and returns it.
     \param $iniFile The INI file to read from or if \c false use 'image.ini'
    */
    function factoryFor( $factoryName, $iniFile = false )
    {
        if ( !$iniFile )
            $iniFile = 'image.ini';
        if ( isset( $this->Factories[$factoryName] ) )
        {
            return $this->Factories[$factoryName];
        }
        else
        {
            //include_once( 'lib/ezutils/classes/ezextension.php' );
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
                include_once( $result['found-file-dir'] . '/' . $className . '.php' );
                if ( class_exists( $className ) )
                {
                    return $this->Factories[$factoryName] = new $className();
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
        //include_once( 'lib/ezutils/classes/ezini.php' );
        $ini = eZINI::instance( 'image.ini' );
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
        if ( $ini->hasVariable( $iniGroup, 'MIMEType' ) )
            $alias['mime_type'] = $ini->variable( $iniGroup, 'MIMEType' );
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
        //eZDebug::writeDebug( $existingAliasList, 'eZImageManager::createImageAlias existing alias list' );
        $aliasList = $this->aliasList();
        if ( !isset( $aliasList[$aliasName] ) )
        {
            eZDebug::writeWarning( "Alias name $aliasName does not exist, cannot create it" );
            return false;
        }
        $currentAliasInfo = $aliasList[$aliasName];
        $referenceAlias = $currentAliasInfo['reference'];
        if ( $referenceAlias and !$this->hasAlias( $referenceAlias ) )
        {
            eZDebug::writeError( "The referenced alias '$referenceAlias' for image alias '$aliasName' does not exist, cannot use it for reference.\n" .
                                 "Will use 'original' alias instead.",
                                 'eZImageManager::createImageAlias' );
            $referenceAlias = false;
        }
        if ( !$referenceAlias )
            $referenceAlias = 'original';
        $hasReference = false;
        //eZDebug::writeDebug( 'alias ' . $referenceAlias, 'eZImageManager::createImageAlias' );
        if ( array_key_exists( $referenceAlias, $existingAliasList ) )
        {
            // VS-DBFILE

            require_once( 'kernel/classes/ezclusterfilehandler.php' );
            $fileHandler = eZClusterFileHandler::instance();
            if ( $fileHandler->fileExists( $existingAliasList[$referenceAlias]['url'] ) )
            {
                $hasReference = true;
            }
            else
            {
                eZDebug::writeDebug( 'cluster file handler could not find ' . $existingAliasList[$referenceAlias]['url'], 'eZImageManager::createImageAlias' );
                //$backtrace = debug_backtrace();
                /*var_dump( count( $backtrace ) );
                var_dump( $backtrace[3] );*/
                eZDebug::writeError( "The reference alias $referenceAlias file " . $existingAliasList[$referenceAlias]['url'] . " does not exist",
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
            $aliasFilePath = $aliasInfo['url'];
            $aliasKey = $currentAliasInfo['alias_key'];

            // VS-DBFILE

            $aliasFile = eZClusterFileHandler::instance( $aliasFilePath );

            if ( $aliasFile->exists() )
            {
                $aliasFile->fetch();
                //include_once( 'lib/ezutils/classes/ezmimetype.php' );
                $sourceMimeData = eZMimeType::findByFileContents( $aliasFilePath );
                $destinationMimeData = $sourceMimeData;
                if ( isset( $parameters['basename'] ) )
                {
                    $sourceMimeData['basename'] = $parameters['basename'];
                    eZMimeType::changeBasename( $destinationMimeData, $parameters['basename'] );
                }

                if ( !$this->_exclusiveLock( $aliasFilePath, $aliasName ) )
                {
                    return false;
                }

                $wantImage = $this->imageAliasInfo( $sourceMimeData, $aliasName );
                $wantImagePath = $wantImage['url'];

                $fileHandler = eZClusterFileHandler::instance( $wantImagePath );
                $fileHandler->loadMetaData( true );

                if ( $fileHandler->exists() and $this->isImageTimestampValid( $fileHandler->mtime() ) )
                {
                    $destinationMimeData = $wantImage;
                    $wasLocked = true;
                }
                else
                {
                    $destinationMimeData['is_valid'] = false;
                    if ( !$this->convert( $sourceMimeData, $destinationMimeData, $aliasName, $parameters ) )
                    {
                        $sourceFile = $sourceMimeData['url'];
                        $destinationDir = $destinationMimeData['dirpath'];
                        eZDebug::writeError( "Failed converting $sourceFile to alias '$aliasName' in directory '$destinationDir'",
                                             'eZImageManager::createImageAlias' );
                        // VS-DBFILE
                        $aliasFile->deleteLocal();
                        return false;
                    }
                }

                $currentAliasData = array( 'url' => $destinationMimeData['url'],
                                           'dirpath' => $destinationMimeData['dirpath'],
                                           'filename' => $destinationMimeData['filename'],
                                           'suffix' => $destinationMimeData['suffix'],
                                           'basename' => $destinationMimeData['basename'],
                                           'alternative_text' => $aliasInfo['alternative_text'],
                                           'name' => $aliasName,
                                           'sub_type' => false,
                                           'timestamp' => time(),
                                           'alias_key' => $aliasKey,
                                           'mime_type' => $destinationMimeData['name'],
                                           'override_mime_type' => false,
                                           'info' => false,
                                           'width' => false,
                                           'height' => false,
                                           'is_valid' => true,
                                           'is_new' => true );
                if ( isset( $destinationMimeData['override_mime_type'] ) )
                    $currentAliasData['override_mime_type'] = $destinationMimeData['override_mime_type'];
                if ( isset( $destinationMimeData['info'] ) )
                    $currentAliasData['info'] = $destinationMimeData['info'];
                $currentAliasData['full_path'] =& $currentAliasData['url'];
                if ( function_exists( 'getimagesize' ) )
                {
                    // VS-DBFILE

                    $fileHandler = eZClusterFileHandler::instance();
                    $fileHandler->fileFetch( $destinationMimeData['url'] );

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
                        else
                        {
                            eZDebug::writeError("The size of the generated image " . $destinationMimeData['url'] . " could not be read by getimagesize()", 'eZImageManager::createImageAlias' );
                        }

                        // VS-DBFILE

                        // The file is not written to the database if it was already written due to a lock situation
                        if ( !isset( $wasLocked ) )
                        {
                            $fileHandler = eZClusterFileHandler::instance( $aliasFilePath );
                            $fileHandler->fileStore( $destinationMimeData['url'], 'image', true, $destinationMimeData['name'] );
                        }
                    }
                    else
                    {
                        eZDebug::writeError( "The destination image " . $destinationMimeData['url'] . " does not exist, cannot figure out image size", 'eZImageManager::createImageAlias' );
                    }
                }
                else
                    eZDebug::writeError( "Unknown function 'getimagesize' cannot get image size", 'eZImageManager::createImageAlias' );
                $existingAliasList[$aliasName] = $currentAliasData;
                // VS-DBFILE
                $aliasFile->deleteLocal();

                $this->_freeExclusiveLock( $aliasFilePath, $aliasName );

                return true;
            }
        }
        return false;
    }

    /*!
     \static
     Analyzes the image in the MIME structure \a $mimeData and fills in extra information if found.
     \return \c true if the image was succesfully analyzed, \c false otherwise.
     \note It will return \c true if there is no analyzer for the image type.
    */
    static function analyzeImage( &$mimeData, $parameters = array() )
    {
        $file = $mimeData['url'];

        if ( !file_exists( $file ) )
            return false;
        $analyzer = eZImageAnalyzer::createForMIME( $mimeData );
        $status = true;
        if ( is_object( $analyzer ) )
        {
            $imageInformation = $analyzer->process( $mimeData, $parameters );
            if ( $imageInformation )
            {
                $mimeData['info'] = $imageInformation;
            }
            else
                $status = false;
        }
        return $status;
    }

    /*!
     Converts the source image \a $sourceMimeData into the destination image \a $destinationMimeData.
     The source image can be supplied with the full path to the image instead of the MIME structure.
     The destination image can be supplied with full path or dirpath to the destination image instead of the MIME structure.
     \param $aliasName determines the Image Alias to use for conversion, this usually adds some filters to the conversion.
    */
    function convert( $sourceMimeData, &$destinationMimeData, $aliasName = false, $parameters = array() )
    {
        // VS-DBFILE

        require_once( 'kernel/classes/ezclusterfilehandler.php' );
        $sourceFile = eZClusterFileHandler::instance( $sourceMimeData['url'] );
        $sourceFile->fetch();

        //include_once( 'lib/ezutils/classes/ezmimetype.php' );
        if ( is_string( $sourceMimeData ) )
            $sourceMimeData = eZMimeType::findByFileContents( $sourceMimeData );

        $this->analyzeImage( $sourceMimeData );
        $currentMimeData = $sourceMimeData;
        $handlers = $this->ImageHandlers;
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
        $mimeTypeOverride = $this->mimeTypeOverride( $sourceMimeData );
        if ( $mimeTypeOverride )
            $alias['override_mime_type'] = $mimeTypeOverride;

        if ( isset( $parameters['filters'] ) )
        {
            $filters = array_merge( $filters, $parameters['filters'] );
        }

        $wantedFilters = $filters;
        $mimeTypeFilters = $this->mimeTypeFilters( $sourceMimeData );
        if ( is_array( $mimeTypeFilters ) )
            $wantedFilters = array_merge( $wantedFilters, $mimeTypeFilters );
        $filters = array();
        foreach ( array_keys( $wantedFilters ) as $wantedFilterKey )
        {
            $wantedFilter = $wantedFilters[$wantedFilterKey];
            if ( !$this->isFilterSupported( $wantedFilter['name'] ) )
            {
                eZDebug::writeWarning( "The filter '" . $wantedFilter['name'] . "' is not supported by any of the image handlers, will ignore this filter",
                                       'eZImageManager::convert' );
                continue;
            }
            $filters[] = $wantedFilter;
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
                foreach ( $handlers as $handler )
                {
                    $gotMimeData = true;
                    while( $gotMimeData )
                    {
                        $gotMimeData = false;
                        $outputMimeData = $handler->outputMIMEType( $this, $sourceMimeData, false, $this->SupportedFormats, $aliasName );
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
                {
                    // VS-DBFILE
                    $sourceFile->deleteLocal();
                    return false;
                }
            }
        }

        $wantedFilters = $filters;
        $filters = array();
        foreach ( array_keys( $wantedFilters ) as $wantedFilterKey )
        {
            $wantedFilter = $wantedFilters[$wantedFilterKey];
            if ( !$this->isFilterAllowed( $wantedFilter['name'], $destinationMimeData ) )
            {
                continue;
            }
            $filters[] = $wantedFilter;
        }
        $result = true;
        $tempFiles = array();
        if ( $currentMimeData['name'] != $destinationMimeData['name'] or
             count( $filters ) > 0 )
        {
            while ( $currentMimeData['name'] != $destinationMimeData['name'] or
                    count( $filters ) > 0 )
            {
                $nextMimeData = false;
                $nextHandler = false;
                foreach ( $handlers as $handler )
                {
                    if ( !$handler )
                        continue;
                    $outputMimeData = $handler->outputMIMEType( $this, $currentMimeData, $destinationMimeData, $this->SupportedFormats, $aliasName );
                    if ( $outputMimeData['name'] == $destinationMimeData['name'] )
                    {
                        $nextMimeData = $outputMimeData;
                        $nextHandler = $handler;
                        break;
                    }
                    if ( $outputMimeData and
                         !$nextMimeData )
                    {
                        $nextMimeData = $outputMimeData;
                        $nextHandler = $handler;
                    }
                }
                if ( !$nextMimeData )
                {
                    eZDebug::writeError( "None of the handlers can convert MIME-Type " . $currentMimeData['name'],
                                         'eZImageManager::convert' );
                    // VS-DBFILE
                    $sourceFile->deleteLocal();
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
                        //include_once( 'lib/ezfile/classes/ezfilehandler.php' );
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
                    if ( $nextHandler->convert( $this, $currentMimeData, $nextMimeData, $handlerFilters ) )
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
            }
        }
        else
        {
            $useCopy = false;
            if ( $aliasName and
                 $aliasName != 'original' )
            {
                $destinationMimeData['filename'] = $destinationMimeData['basename'] . '_' . $aliasName . '.' . $destinationMimeData['suffix'];
                if ( $destinationMimeData['dirpath'] )
                    $destinationMimeData['url'] = $destinationMimeData['dirpath'] . '/' . $destinationMimeData['filename'];
                else
                    $destinationMimeData['url'] = $destinationMimeData['filename'];
            }
            if ( $sourceMimeData['url'] != $destinationMimeData['url'] )
            {
                //include_once( 'lib/ezfile/classes/ezfilehandler.php' );
                if ( $useCopy )
                {
                    eZFileHandler::copy( $sourceMimeData['url'], $destinationMimeData['url'] );
                }
                else
                {
                    eZFileHandler::linkCopy( $sourceMimeData['url'], $destinationMimeData['url'], false );
                }
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

        // VS-DBFILE

        if ( $aliasName && $aliasName != 'original' )
        {
            if ( $result )
            {
                $destinationFilePath = $destinationMimeData['url'];
                require_once( 'kernel/classes/ezclusterfilehandler.php' );
                $fileHandler = eZClusterFileHandler::instance();
                $fileHandler->fileStore( $destinationFilePath, 'image', true, $destinationMimeData['name'] );
            }

            $sourceFile->deleteLocal();
        }

        return $result;
    }

    /**
     * Image information for $aliasName. This is the information which normally
     * would be provided during generation of aliasName. This so that requests
     * not holding the lock will provide meaningful information.
     *
     * @param mixed $mimeData 
     * @param string $aliasName 
     * @return mixed
     */
    function imageAliasInfo( $mimeData, $aliasName )
    {
        if ( is_string( $mimeData ) )
            $mimeData = eZMimeType::findByFileContents( $mimeData );

        $this->analyzeImage( $mimeData );
        if ( $aliasName )
        {
            $aliasList = $this->aliasList();
            if ( isset( $aliasList[$aliasName] ) )
            {
                $alias = $aliasList[$aliasName];
                if ( $alias['mime_type'] )
                {
                    eZMimeType::changeMIMEType( $mimeData, $alias['mime_type'] );
                }
            }
        }
        if ( $aliasName != 'original' )
        {
            $mimeData['filename'] = $mimeData['basename'] . '_' . $aliasName . '.' . $mimeData['suffix'];
            $mimeData['url'] = $mimeData['dirpath'] . '/' . $mimeData['filename'];
        }
        // eZDebug::writeDebug( $mimeData, 'return from eZImageManager::imageAliasInfo' );
        return $mimeData;
    }

    /*!
     \return the path for temporary images.
     \note The default value uses the temporary directory setting from site.ini.
    */
    function temporaryImageDirPath()
    {
        return $this->TemporaryImageDirPath;
    }

    /*!
     Returns the only instance of the image manager.
    */
    static function instance()
    {
        if ( !isset( $GLOBALS["eZImageManager"] ) )
        {
            $GLOBALS["eZImageManager"] = new eZImageManager();
        }
        return $GLOBALS["eZImageManager"];
    }

    /*!
     Acquires an exclusive lock for the currently generated alias
    */
    private function _exclusiveLock( $fileName, $aliasName )
    {
        // mutex w/ exclusive lock: convert(targetFileName)
        $mutex = $this->_mutex( "{$fileName}-{$aliasName}" );
        while( true )
        {
            $timestamp  = $mutex->lockTS(); // Note: This does not lock, only checks what the timestamp is.
            if ( $timestamp === false )
            {
                if ( !$mutex->lock() )
                {
                    eZDebug::writeWarning( "Failed to acquire lock for file $fileName/$aliasName" );
                    return false;
                }
                $mutex->setMeta( 'pid', getmypid() );
                return true;
            }
            if ( $timestamp >= time() - $this->lockTimeout )
            {
                sleep( 1 ); // Sleep 1 second
                continue;
            }

            $oldPid = $mutex->meta( 'pid' );
            if ( is_numeric( $oldPid ) &&
                 $oldPid != 0 &&
                 function_exists( 'posix_kill' ) )
            {
                posix_kill( $oldPid, 9 );
            }
            if ( !$mutex->steal() )
            {
                eZDebug::writeWarning( "Failed to steal lock for file $fileName/$aliasName from PID $oldPid" );
                return false;
            }
            $mutex->setMeta( 'pid', getmypid() );
            return true;
        } // while
    }

    /*!
     Frees the current exclusive lock in use.

     \param $fname Name of the calling code (usually function name).
     */
    private function _freeExclusiveLock( $fileName, $aliasName )
    {
        $this->_mutex( "{$fileName}-{$aliasName}" )->unlock();
    }

    /*!
     Returns the mutex object for the current file.
     */
    private function _mutex( $fname = false )
    {
        if ( $this->Mutex !== null )
        {
            return $this->Mutex;
        }
        $this->Mutex = new eZMutex( $fname );
        return $this->Mutex;
    }

    /// \privatesection
    public $ImageHandlers;
    public $OutputMIME;
    public $OutputMIMEMap;
    public $Rules;
    public $DefaultRule;
    public $RuleMap;
    public $MIMETypes;
    public $Types = array();

    /**
     * Holds the mutex for image alias file.
     *
     * @var eZMutex
     */
    private $Mutex;
    
    /**
     * The time spent waiting before an existing eZMutex lock is cancelled and reused.
     * Default value is 60 seconds, which is set in constructor.
     *
     * @var int
     */
    private $lockTimeout;
    
}

?>
