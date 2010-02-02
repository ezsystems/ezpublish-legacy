<?php
//
// Definition of eZImageManager class
//
// Created on: <01-Mar-2002 14:23:49 amos>
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
        $this->ImageFilters = array_unique( $this->ImageFilters, SORT_STRING );
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

        $converterList = $ini->variable( 'ImageConverterSettings', 'ImageConverters' );
        foreach ( $converterList as $converterName )
        {
            if ( $ini->hasGroup( $converterName ) )
            {
                if ( $ini->hasVariable( $converterName, 'Handler' ) )
                {
                    $factoryName = $ini->variable( $converterName, 'Handler' );
                    $factory = $this->factoryFor( $factoryName, $iniFile, $converterName );
                    if ( $factory )
                    {
                        $convertHandler = $factory->produceFromINI( $converterName, $iniFile );
                        $this->appendImageHandler( $convertHandler );
                    }
                }
                else
                {
                    eZDebug::writeWarning( "INI group $converterName does not have a Handler setting, cannot instantiate handler without it", __METHOD__ );
                }
            }
            else
            {
                eZDebug::writeWarning( "No INI group $converterName for Image Handler $converterName, cannot instantiate", __METHOD__ );
            }
        }
    }

    /*!
     Finds the image handler factory with the name \a $factoryName and returns it.
     \param $iniFile The INI file to read from or if \c false use 'image.ini'
    */
    function factoryFor( $factoryName, $iniFile = false, $converterName = false )
    {
        if ( !$iniFile )
            $iniFile = 'image.ini';
        if ( isset( $this->Factories[$factoryName] ) )
        {
            return $this->Factories[$factoryName];
        }
        else
        {
            $optionArray = array( 'iniFile'       => $iniFile,
                                  'iniSection'    => $converterName,
                                  'iniVariable'   => 'Handler' );

            $options = new ezpExtensionOptions( $optionArray );

            $factory = eZExtension::getHandlerClass( $options );

            return $this->Factories[$factoryName] = $factory;
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

    /**
     * Creates the image alias $aliasName if it's not already part of the
     * existing aliases
     *
     * @param string $aliasName Name of the alias to create
     * @param array $existingAliasList
     *        Reference to the current alias list. The created alias will be
     *        added to the list.
     * @param array $parameters
     *        Optional array that can be used to specify the image's basename
     * @return bool true if the alias was created, false if it wasn't
     **/
    function createImageAlias( $aliasName, &$existingAliasList, $parameters = array() )
    {
        $fname = "createImageAlias( $aliasName )";

        // check for $aliasName validity
        $aliasList = $this->aliasList();
        if ( !isset( $aliasList[$aliasName] ) )
        {
            eZDebug::writeWarning( "Alias name $aliasName does not exist, cannot create it" );
            return false;
        }

        // check if the reference alias is defined, and if no, use original as ref
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

        // generate the reference alias if it hasn't been generated yet
        $hasReference = false;
        if ( array_key_exists( $referenceAlias, $existingAliasList ) )
        {
            $fileHandler = eZClusterFileHandler::instance();
            if ( $fileHandler->fileExists( $existingAliasList[$referenceAlias]['url'] ) )
            {
                $hasReference = true;
            }
            else
            {
                eZDebug::writeError( "The reference alias $referenceAlias file {$existingAliasList[$referenceAlias]['url']} does not exist",
                                     'eZImageManager::createImageAlias' );
            }
        }
        if ( !$hasReference )
        {
            if ( $referenceAlias == 'original' )
            {
                eZDebug::writeError( "Original alias does not exist, cannot create other aliases without it" );
                return false;
            }
            if ( !$this->createImageAlias( $referenceAlias, $existingAliasList, $parameters ) )
            {
                eZDebug::writeError( "Failed creating the referenced alias $referenceAlias, cannot create alias $aliasName",
                                     'eZImageManager::createImageAlias' );
                return false;
            }
        }

        // from now on, our reference image (either reference or original)
        // exists
        $aliasInfo = $existingAliasList[$referenceAlias];
        $aliasFilePath = $aliasInfo['url'];
        $aliasKey = $currentAliasInfo['alias_key'];

        $sourceMimeData = eZMimeType::findByFileContents( $aliasFilePath );

        /**
         * at first, destinationMimeData (mimedata for the alias we're
         * generating) is the same as sourceMimeData. It will evolve as
         * alias generation goes on
         **/
        $destinationMimeData = $sourceMimeData;
        if ( isset( $parameters['basename'] ) )
        {
            $sourceMimeData['basename'] = $parameters['basename'];
            eZMimeType::changeBasename( $destinationMimeData, $parameters['basename'] );
        }

        /**
         * Concurrency protection
         * startCacheGeneration will return true if the file is not
         * already being generated by another process. If it is, it will
         * return the maximum time before the generating process enters
         * generation timeout
         **/
        while ( true )
        {
            $convertHandler = eZClusterFileHandler::instance( $sourceMimeData['url'] );
            $startGeneration = $convertHandler->startCacheGeneration();
            if ( $startGeneration === true )
            {
                $destinationMimeData['is_valid'] = false;
                if ( $this->convert( $sourceMimeData, $destinationMimeData, $aliasName, $parameters ) )
                {
                    /**
                     * At this point, we consider that the image exists and destinationMimeData
                     * has been filled with the proper information
                     *
                     * If we were locked during alias generation, we need to recreate
                     * this structure so that the image can actually be used, but ONLY
                     * if it was the same alias... sounds like a HUGE mess.
                     *
                     * Can we reload the alias list somehow ?
                     **/
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
                    $currentAliasData['full_path'] = $currentAliasData['url'];

                    if ( function_exists( 'getimagesize' ) )
                    {
                        /**
                         * we may want to fetch a unique name here, since we won't use
                         * the data for anything else
                         **/
                        $fileHandler = eZClusterFileHandler::instance( $destinationMimeData['url'] );
                        if ( $tempPath = $fileHandler->fetchUnique() )
                        {
                            $info = getimagesize( $tempPath );
                            if ( $info )
                            {
                                list( $currentAliasData['width'], $currentAliasData['height'] ) = $info;
                            }
                            else
                            {
                                eZDebug::writeError("The size of the generated image {$destinationMimeData['url']} could not be read by getimagesize()", 'eZImageManager::createImageAlias' );
                            }
                            $fileHandler->fileDeleteLocal( $tempPath );
                        }
                        else
                        {
                            eZDebug::writeError( "The destination image {$destinationMimeData['url']} does not exist, cannot figure out image size",
                                'eZImageManager::createImageAlias' );
                        }
                    }
                    else
                    {
                        eZDebug::writeError( "Unknown function 'getimagesize', cannot get image size", 'eZImageManager::createImageAlias' );
                    }
                    $existingAliasList[$aliasName] = $currentAliasData;

                    $convertHandler->endCacheGeneration( false );

                    return true;
                }
                // conversion failed, we abort generation
                else
                {
                    $sourceFile = $sourceMimeData['url'];
                    $destinationDir = $destinationMimeData['dirpath'];
                    eZDebug::writeError( "Failed converting $sourceFile to alias '$aliasName' in directory '$destinationDir'",
                                         'eZImageManager::createImageAlias' );
                    $convertHandler->abortCacheGeneration();
                    return false;
                }
            }
            // we were not granted file generation (someone else is doing it)
            // we wait for max. $remainingGenerationTime and check if the
            // file has been generated in between
            // Actually, we have no clue if the generated file was the one we were
            // looking for, and it doesn't seem possible to RELOAD the alias list.
            // We don't even know what attribute we're using... CRAP
            else
            {
                eZDebug::writeDebug( "An alias is already being generated for this image, let's wait", __METHOD__ );
                while ( true )
                {
                    $startGeneration = $convertHandler->startCacheGeneration();
                    // generation lock granted: we can start again by breaking to
                    // the beggining of the while loop
                    if ( $startGeneration === true )
                    {
                        eZDebug::writeDebug( "Got granted generation permission, restarting !", __METHOD__ );
                        $convertHandler->abortCacheGeneration();
                        continue 2;
                    }
                    else
                    {
                        sleep( 1 );
                    }
                }
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

    /**
     * Converts the source image $sourceMimeData into the destination image
     * $destinationMimeData.
     *
     * @param mixed $sourceMimeData Source image, either a mimedata array or the
     *        source image path
     * @param mixed $destinationMimeData
     *        Either a mimedata array or the target image path
     * @param mixed $aliasName
     *        Target alias (small, medium, large...)
     * @param array $parameters
     *        Optional parameters. Known ones so far: (basename)
     * @return bool
     **/
    function convert( $sourceMimeData, &$destinationMimeData, $aliasName = false, $parameters = array() )
    {
        // if the local file doesn't exist, we need to fetch it locally
        if ( !file_exists( $sourceMimeData['url'] ) )
        {
            $sourceFileHandler = eZClusterFileHandler::instance( $sourceMimeData['url'] );
            $sourceFileHandler->fetch();
        }

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
                    if ( isset( $sourceFileHandler ) )
                        $sourceFileHandler->deleteLocal();
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

                    $handlerFilters = array();
                    $leftoverFilters = array();
                    foreach ( $filters as $filter )
                    {
                        if ( $handler->isFilterSupported( $filter ) )
                            $handlerFilters[] = $filter;
                        else
                            $leftoverFilters[] = $filter;
                    }

                    $outputMimeData = $handler->outputMIMEType( $this, $currentMimeData, $destinationMimeData, $this->SupportedFormats, $aliasName );
                    if ( $outputMimeData['name'] == $destinationMimeData['name'] and count( $handlerFilters ) > 0 )
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
                    if ( isset( $sourceFile ) )
                        $sourceFile->deleteLocal();
                    return false;
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
                    eZDir::mkdir( $nextMimeData['dirpath'], false, true );
                if ( $currentMimeData['name'] == $nextMimeData['name'] and
                     count( $handlerFilters ) == 0 )
                {
                    if ( $currentMimeData['url'] != $nextMimeData['url'] )
                    {
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

        if ( $aliasName && $aliasName != 'original' )
        {
            if ( $result )
            {
                $destinationFilePath = $destinationMimeData['url'];
                $fileHandler = eZClusterFileHandler::instance();
                $fileHandler->fileStore( $destinationFilePath, 'image', true, $destinationMimeData['name'] );
            }

            if ( isset( $sourceFileHandler ) )
                $sourceFileHandler->deleteLocal();
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
     * @return array
     */
    function imageAliasInfo( $mimeData, $aliasName, $isAliasNew = false )
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

    /**
     * Returns a shared instance of the eZImageManager class.
     *
     * @return eZImageManager
     */
    static function instance()
    {
        if ( !isset( $GLOBALS["eZImageManager"] ) )
        {
            $GLOBALS["eZImageManager"] = new eZImageManager();
        }
        return $GLOBALS["eZImageManager"];
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
}
?>