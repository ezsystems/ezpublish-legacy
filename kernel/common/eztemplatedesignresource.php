<?php
//
// Definition of eZTemplatedesignresource class
//
// Created on: <14-Sep-2002 15:37:17 amos>
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

/*! \file eztemplatedesignresource.php
*/

/*!
  \class eZTemplatedesignresource eztemplatedesignresource.php
  \brief Handles template file loading with override support

*/

include_once( "lib/eztemplate/classes/eztemplatefileresource.php" );
include_once( "lib/ezutils/classes/ezini.php" );

class eZTemplateDesignResource extends eZTemplateFileResource
{
    /*!
     Initializes with a default resource name "design".
    */
    function eZTemplateDesignResource( $name = "design", $onlyStandard = false )
    {
        $this->eZTemplateFileResource( $name, true );
        $this->Keys = array();
        $this->OnlyStandard = $onlyStandard;
    }

    /*!
     \static
     \return the sitedesign for the design type \a $type, currently \c standard and \c site is allowed.
             If no sitedesign is set it will fetch it from site.ini.
    */
    function designSetting( $type = 'standard' )
    {
        if ( $type != 'standard' and
             $type != 'site' )
        {
            eZDebug::writeWarning( "Cannot retrieve designsetting for type '$type'", 'eZTemplateDesignResource::designSetting' );
            return null;
        }
        $designSettings =& $GLOBALS['eZTemplateDesignSetting'];
        if ( !isset( $designSettings ) )
            $designSettings = array();
        $designSetting =& $designSettings[$type];
        $siteBasics =& $GLOBALS['eZSiteBasics'];
        if ( $type == 'site' and
             is_string( $siteBasics['site-design-override'] ) )
            return $siteBasics['site-design-override'];

        if ( isset( $designSetting ) )
            return $designSetting;
        $ini =& eZINI::instance();
        if ( $type == 'standard' )
            $designSetting = $ini->variable( "DesignSettings", "StandardDesign" );
        else if ( $type == 'site' )
            $designSetting = $ini->variable( "DesignSettings", "SiteDesign" );
        return $designSetting;
    }

    /*!
     Sets the sitedesign for the design type \a $type, currently \c standard and \c site is allowed.
     The design is set to \a $designSetting.
    */
    function setDesignSetting( $designSetting, $type = 'standard' )
    {
        if ( $type != 'standard' and
             $type != 'site' )
        {
            eZDebug::writeWarning( "Cannot set designsetting '$designSetting' for type '$type'", 'eZTemplateDesignResource::setDesignSetting' );
            return;
        }
        $designSettings =& $GLOBALS['eZTemplateDesignSetting'];
        if ( !isset( $designSettings ) )
            $designSettings = array();
        $designSettings[$type] = $designSetting;
    }

    /*!
     \return the rules used for matching design elements. \a $element defines the element type.
    */
    function fileMatchingRules( $element, $path, $onlyStandard = false )
    {
        eZDebug::accumulatorStart( 'matching_rules', 'override', 'Matching rules' );

        $standardBase = eZTemplateDesignResource::designSetting( 'standard' );
        if ( !$onlyStandard )
            $siteBase = eZTemplateDesignResource::designSetting( 'site' );

        $ini =& eZINI::instance();
        $additionalSiteDesignList = $ini->variable( "DesignSettings", "AdditionalSiteDesignList" );

        include_once( 'lib/ezutils/classes/ezextension.php' );
        $extensionDirectory = eZExtension::baseDirectory();

        $designINI =& eZINI::instance( 'design.ini' );
        $extensions = $designINI->variable( 'ExtensionSettings', 'DesignExtensions' );

        $matches = array();

        $elementText = '';
        if ( $element !== false )
            $elementText = $element . '/';

        // Override
        foreach ( $extensions as $extension )
        {
            if ( !$onlyStandard )
                $matches[] = array( 'file' => "$extensionDirectory/$extension/design/$siteBase/override/$elementText$path",
                                    'type' => 'override' );
            $matches[] = array( 'file' => "$extensionDirectory/$extension/design/$standardBase/override/$elementText$path",
                                'type' => 'override' );
        }

        if ( !$onlyStandard )
        {
            $matches[] = array( 'file' => "design/$siteBase/override/$elementText$path",
                                'type' => 'override' );
            foreach ( $additionalSiteDesignList as $additionalSiteDesign )
            {
                $matches[] = array( 'file' => "design/$additionalSiteDesign/override/$elementText$path",
                                    'type' => 'override' );
            }
        }

        foreach ( $extensions as $extension )
        {
            if ( !$onlyStandard )
                $matches[] = array( 'file' => "$extensionDirectory/$extension/design/$siteBase/$elementText$path",
                                    'type' => 'normal' );
            $matches[] = array( 'file' => "$extensionDirectory/$extension/design/$standardBase/$elementText$path",
                                'type' => 'normal' );
        }

        // Normal
        if ( !$onlyStandard )
        {
            $matches[] = array( 'file' => "design/$siteBase/$elementText$path",
                                'type' => 'normal' );
            foreach ( $additionalSiteDesignList as $additionalSiteDesign )
            {
                $matches[] = array( "file" => "design/$additionalSiteDesign/$elementText$path",
                                    'type' => 'normal' );
            }
        }

        $matches[] = array( 'file' => "design/$standardBase/override/$elementText$path",
                            'type' => 'override' );

        $matches[] = array( 'file' => "design/$standardBase/$elementText$path",
                            'type' => 'normal' );

        eZDebug::accumulatorStop( 'matching_rules' );

        return $matches;
    }

    /*!
     Loads the template file if it exists, also sets the modification timestamp.
     Returns true if the file exists.
    */
//     function handleResource( &$tpl, &$templateRoot, &$text, &$tstamp, $uri, $resourceName, &$path, &$keyData, $method, &$extraParameters )
    function handleResource( &$tpl, &$resourceData, $method, &$extraParameters )
    {
        $templateRoot =& $resourceData['template-root'];
        $text =& $resourceData['text'];
        $tstamp =& $resourceData['time-stamp'];
        $uri =& $resourceData['uri'];
        $resourceName =& $resourceData['resource'];
        $path =& $resourceData['template-name'];
        $keyData =& $resourceData['key-data'];

        $matchKeys = $this->Keys;
        if ( is_array( $extraParameters ) and
             isset( $extraParameters['ezdesign:keys'] ) )
        {
            $this->mergeKeys( $matchKeys, $extraParameters['ezdesign:keys'] );
        }

        eZDebug::accumulatorStart( 'override_cache', 'override', 'Cache load' );

        $overrideCacheFile =& $this->createOverrideCache();

        if ( $overrideCacheFile )
        {
            include_once( $overrideCacheFile );
            $match['file'] = overrideFile( "/" . $path, $matchKeys );
        }
        else
        {
            $template = "/" . $path;
            $matchFileArray =& $GLOBALS['eZTemplateOverrideArray'];
            if ( !is_array( $matchFileArray ) )
            {
                $matchFileArray =& eZTemplateDesignResource::overrideArray();
            }

            $matchFile = $matchFileArray[$template];

            if ( isset( $matchFile['custom_match'] ) )
            {
                foreach ( $matchFile['custom_match'] as $customMatch )
                {
                    $matchOverride = true;
                    if ( count( $customMatch['conditions'] ) > 0 )
                    {
                        foreach ( array_keys( $customMatch['conditions'] ) as $conditionKey )
                        {
                            // Create special substring match for subtree override
                            if ( $conditionKey == 'url_alias' )
                            {
                                if ( strpos( $matchKeys['url_alias'], $customMatch['conditions'][$conditionKey] ) === 0 )
                                {
                                }
                                else
                                {
                                    $matchOverride = false;
                                }
                            }
                            else
                            if ( $matchKeys[$conditionKey] == $customMatch['conditions'][$conditionKey] )
                            {
                            }
                            else
                            {
                                $matchOverride = false;
                            }
                        }
                        if ( $matchOverride == true )
                        {
                            $match['file'] = $customMatch['match_file'];
                            break;
                        }
                        else
                        {
                        }
                    }
                    else
                    {
                        // Default match without conditions
                        $match['file'] = $customMatch['match_file'];
                    }
                }
            }
            else
            {
                $match['file'] = $matchFile['base_dir'] . $matchFile['template'];
            }
        }
        eZDebug::accumulatorStop( 'override_cache' );
        if ( $match === null )
            return false;

        $file = $match["file"];

        $matchedKeys = array();
        // TODO add used keys
        $usedKeys = array();
        foreach ( $matchKeys as $matchKeyName => $matchKeyValue )
        {
            $usedKeys[$matchKeyName] = $matchKeyValue;
        }
        $extraParameters['ezdesign:used_keys'] = $usedKeys;
        $extraParameters['ezdesign:matched_keys'] = $matchedKeys;
        $tpl->setVariable( 'used', $usedKeys, 'DesignKeys' );
        $tpl->setVariable( 'matched', $matchedKeys, 'DesignKeys' );
        $resourceData['template-filename'] = $file;
        $result = eZTemplateFileResource::handleResourceData( $tpl, $this, $resourceData, $method, $extraParameters );
        return $result;
    }

    /*!
     Generates the cache for the template override matching.
    */
    function &createOverrideCache()
    {
        if ( isset( $GLOBALS['eZSiteBasics'] ) )
        {
            $siteBasics = $GLOBALS['eZSiteBasics'];
            if ( $siteBasics['no-cache-adviced'] )
                return false;
        }
        global $eZTemplateOverrideCacheNoPermission;
        if ( $eZTemplateOverrideCacheNoPermission == "nocache" )
        {
            return false;
        }

        $onlyStandard = $this->OnlyStandard;

        $ini =& eZINI::instance( 'site.ini' );
        $useOverrideCache = true;
        if ( $ini->hasVariable( 'OverrideSettings', 'Cache' ) )
            $useOverrideCache = $ini->variable( 'OverrideSettings', 'Cache' ) == 'enabled';

        $standardBase = eZTemplateDesignResource::designSetting( 'standard' );
        if ( !$onlyStandard )
            $siteBase = eZTemplateDesignResource::designSetting( 'site' );

        $overrideKeys = eZTemplateDesignResource::overrideKeys();

        $overrideKey = md5( implode( ',', $overrideKeys ) . $siteBase . $standardBase ) ;
        $cacheDir = eZSys::cacheDirectory();

        $overrideCacheFile = "$cacheDir/override/override_$overrideKey.php";


        // Build matching cache only of it does not already exists,
        // or override file has been updated
        if ( !$useOverrideCache or
             !file_exists( $overrideCacheFile ) )
        {
            $matchFileArray =& eZTemplateDesignResource::overrideArray();

            // Generate PHP compiled cache file.
            include_once( 'lib/ezutils/classes/ezphpcreator.php' );
            $phpCache = new eZPHPCreator( "$cacheDir/override", "override_$overrideKey.php" );

            $phpCode = "function overrideFile( \$matchFile, \$matchKeys )\n{\n    ";
            $phpCode .= "switch ( \$matchFile )\n{\n    ";
            $i = 0;
            foreach ( array_keys( $matchFileArray ) as $matchKey )
            {
                if ( isset( $matchFileArray[$matchKey]['custom_match'] ) )
                {
                    $defaultMatchFile = $matchFileArray[$matchKey]['base_dir'] . $matchKey;
                    // Custom override matching
                    $phpCode .= "    case  \"$matchKey\":\n    {\n";

                    foreach ( $matchFileArray[$matchKey]['custom_match'] as $customMatch )
                    {
                        $matchCondition = "";
                        $condCount = 0;
                        foreach ( array_keys( $customMatch['conditions'] ) as $conditionKey )
                        {
                            if ( $condCount > 0 )
                                $matchCondition .= " and ";

                            // Have a special substring match for subtree matching
                            if ( $conditionKey == 'url_alias' )
                                $matchCondition .= "( strpos( \$matchKeys['url_alias'],  '" . $customMatch['conditions'][$conditionKey] . "' ) === 0 )";
                            else
                                $matchCondition .= "\$matchKeys['$conditionKey'] == '" . $customMatch['conditions'][$conditionKey] . "'";


                            $condCount++;
                        }

                        // Only create custom match if conditions are defined
                        if ( $matchCondition != "" )
                        {
                            $phpCode .= "        if ( $matchCondition )\n        {\n";
                            $phpCode .= "            return '" .
                                 $customMatch['match_file'] . "';\n        }\n";
                        }
                        else
                        {
                            // No override conditions defined. Override default match file
                            $defaultMatchFile = $customMatch['match_file'];
                        }
                    }

                    $phpCode .= "        return '" . $defaultMatchFile . "';\n    }break;\n";
                }
                else
                {
                    // Plain matching without custom override
                    $phpCode .= "case  \"$matchKey\":\n    {\n
                           return '" .
                         $matchFileArray[$matchKey]['base_dir'] . $matchKey . "';}\nbreak;\n";
                }

                $i++;
            }
            $phpCode .= "default:\n {\n}break;\n}";

            $phpCode .= "}\n";

            $phpCache->addCodePiece( $phpCode );
            if ( $useOverrideCache and
                 $phpCache->store() )
            {

            }
            else
            {
                if ( $useOverrideCache )
                    eZDebug::writeError( "Could not write template override cache file, check permissions in $cacheDir/override/.\nRunning eZ publish without this cache will have a performance impact.", "eZTemplateDesignResource::createOverrideCache" );
                $GLOBALS['eZTemplateOverrideArray'] =& $matchFileArray;
                $eZTemplateOverrideCacheNoPermission = 'nocache';
                $overrideCacheFile = false;

            }
        }

        return $overrideCacheFile;
    }

    /*!
     \static
     \return an array with keys that define the current override.
    */
    function &overrideKeys( $siteAccess = false )
    {
        $keys = array();
        $onlyStandard = $this->OnlyStandard;

        // fetch the override array from a specific siteacces
        if ( $siteAccess )
        {
            // Get the design resources
            $ini =& eZINI::instance( 'site.ini', 'settings', null, null, true );
            $ini->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
            $ini->loadCache();

            $overrideINI = eZINI::instance( 'override.ini', 'settings', null, null, true );
            $overrideINI->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
            $overrideINI->loadCache();
        }
        else
        {
            $ini =& eZINI::instance();
            $overrideINI =& eZINI::instance( 'override.ini' );
        }

        $standardBase = $ini->variable( "DesignSettings", "StandardDesign" );
        $keys[] = $standardBase;
        if ( !$onlyStandard )
        {
            $siteBase = $ini->variable( "DesignSettings", "SiteDesign" );
            $keys[] = $siteBase;
        }

        $additionalSiteDesignList = $ini->variable( "DesignSettings", "AdditionalSiteDesignList" );
        $keys = array_merge( $keys, $additionalSiteDesignList );

        // Add extension paths
        include_once( 'lib/ezutils/classes/ezextension.php' );
        $extensionDirectory = eZExtension::baseDirectory();

        $designINI =& eZINI::instance( 'design.ini' );
        $extensions = $designINI->variable( 'ExtensionSettings', 'DesignExtensions' );
        $keys = array_merge( $keys, $extensions );

        return $keys;
    }

    /*!
     \static
    */
    function serializeOverrides( $siteAccess = false,
                                 $matchKeys = array() )
    {
    }

    /*!
     \static
     \return an array of all the current templates and overrides for them.
             The current siteaccess is used if none is specified.
    */
    function &overrideArray( $siteAccess = false )
    {
        $onlyStandard = $this->OnlyStandard;

        // fetch the override array from a specific siteacces
        if ( $siteAccess )
        {
            // Get the design resources
            $ini =& eZINI::instance( 'site.ini', 'settings', null, null, true );
            $ini->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
            $ini->loadCache();

            $overrideINI = eZINI::instance( 'override.ini', 'settings', null, null, true );
            $overrideINI->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
            $overrideINI->loadCache();

            $standardBase = $ini->variable( "DesignSettings", "StandardDesign" );
            if ( !$onlyStandard )
                $siteBase = $ini->variable( "DesignSettings", "SiteDesign" );
        }
        else
        {
            $ini =& eZINI::instance();
            $overrideINI =& eZINI::instance( 'override.ini' );

            $standardBase = eZTemplateDesignResource::designSetting( 'standard' );
            if ( !$onlyStandard )
                $siteBase = eZTemplateDesignResource::designSetting( 'site' );
        }

        $additionalSiteDesignList = $ini->variable( "DesignSettings", "AdditionalSiteDesignList" );

        // Generate match cache for all templates
        include_once( "lib/ezutils/classes/ezdir.php" );

        // Build arrays of available files, start with base design and end with most prefered design
        $matchFilesArray = array();

        // For each override dir overwrite current default file
        // TODO: fetch all resource repositories

        // Generate match cache for all templates
        include_once( "lib/ezutils/classes/ezdir.php" );

        // Build arrays of available files, start with base design and end with most prefered design
        $matchFilesArray = array();

        // For each override dir overwrite current default file
        // TODO: fetch all resource repositories
        $resourceArray[] = "design/$standardBase/templates";

        // Add the additional sitedesigns
        foreach ( $additionalSiteDesignList as $additionalSiteDesign )
        {
            $resourceArray[] = "design/$additionalSiteDesign/override/templates";
            $resourceArray[] = "design/$additionalSiteDesign/templates";
        }

        $resourceArray[] = "design/$siteBase/override/templates";
        $resourceArray[] = "design/$siteBase/templates";

        // Add extension paths
        include_once( 'lib/ezutils/classes/ezextension.php' );
        $extensionDirectory = eZExtension::baseDirectory();

        $designINI =& eZINI::instance( 'design.ini' );
        $extensions = $designINI->variable( 'ExtensionSettings', 'DesignExtensions' );

        foreach ( $extensions as $extension )
        {
            // Look for standard design in extension
            $resourceArray[] = "$extensionDirectory/$extension/design/$standardBase/templates";

            // Look for aditional sitedesigns in extension
            foreach ( $additionalSiteDesignList as $additionalSiteDesign )
            {
                $resourceArray[] = "$extensionDirectory/$extension/design/$additionalSiteDesign/override/templates";
                $resourceArray[] = "$extensionDirectory/$extension/design/$additionalSiteDesign/templates";
            }

            // Look for site base in extention
            $resourceArray[] = "$extensionDirectory/$extension/design/$siteBase/override/templates";
            $resourceArray[] = "$extensionDirectory/$extension/design/$siteBase/templates";
        }

        foreach ( $resourceArray as $resource )
        {
            $sourceFileArray =& eZDir::recursiveFindRelative( $resource, "",  "tpl" );
            foreach ( array_keys( $sourceFileArray ) as $sourceKey )
            {
                $matchFileArray[$sourceFileArray[$sourceKey]]['base_dir'] = $resource;
                $matchFileArray[$sourceFileArray[$sourceKey]]['template'] = $sourceFileArray[$sourceKey];
            }
        }


        // Load complex/custom override templates
        $overrideSettingGroupArray =& $overrideINI->groups();

        foreach ( array_keys( $overrideSettingGroupArray ) as $overrideSettingKey )
        {
            $overrideName = $overrideSettingKey;
            $overrideSource = "/" . $overrideSettingGroupArray[$overrideSettingKey]['Source'];

            $overrideMatchConditionArray =& $overrideSettingGroupArray[$overrideSettingKey]['Match'];
            $overrideMatchFile =& $overrideSettingGroupArray[$overrideSettingKey]['MatchFile'];

            $overrideMatchFilePath = false;
            // Find the matching file in the available resources
            $triedFiles = array();
            $resourceInUse = false;
            foreach ( $resourceArray as $resource )
            {
                if ( file_exists( $resource . "/" . $overrideMatchFile ) )
                {
                    $overrideMatchFilePath = $resource . "/" . $overrideMatchFile;
                    $resourceInUse = $resource;
                }
                else
                    $triedFiles[] = $resource . '/' . $overrideMatchFile;
            }

            // Only create override if match file exists
            if ( $overrideMatchFilePath )
            {
                $customMatchArray = array();
                $customMatchArray['conditions'] = $overrideMatchConditionArray;
                $customMatchArray['match_file'] = $overrideMatchFilePath;
                $customMatchArray['override_name'] = $overrideName;
                $matchFileArray[$overrideSource]['custom_match'][] = $customMatchArray;
            if( $resourceInUse && !isset($matchFileArray[$overrideSource]['base_dir']))
            {
                $matchFileArray[$overrideSource]['base_dir'] = $resource;
                $matchFileArray[$overrideSource]['template'] = $overrideSource;
            }
            }
            else
            {
                eZDebug::writeError( "Custom match file: path '$overrideMatchFile' not found in any resource. Check template settings in settings/override.ini",
                                     "eZTemplateDesignResource::overrideArray" );
                eZDebug::writeError( $triedFiles,
                                     "eZTemplateDesignResource::overrideArray, tried files" );
            }

        }

/*            foreach ( array_keys( $matchFileArray ) as $matchKey )
            {
                print( "$matchKey  => " . $matchFileArray[$matchKey]['base_dir'] . "<br>" );
                if ( isset( $matchFileArray[$matchKey]['custom_match'] ) )
                {
                    foreach ( $matchFileArray[$matchKey]['custom_match'] as $customMatch )
                    {
                        print_r( $customMatch );
                    }
                }
            }
*/
        return $matchFileArray;
    }

    /*!
     Sets the override keys to \a $keys, if some of the keys already exists they are overriden
     by the new keys.
     \sa clearKeys
    */
    function setKeys( $keys )
    {
        $this->mergeKeys( $this->Keys, $keys );
    }

    /*!
     Removes the given key
    */
    function removeKey( $key )
    {
        if ( isset( $this->Keys[$key] ) )
        unset( $this->Keys[$key] );
    }

    /*!
     \private
     Merges keys set in \a $keys with the array in \a $originalKeys.
    */
    function mergeKeys( &$originalKeys, $keys )
    {
        foreach ( $keys as $key )
        {
            if ( count( $key ) >= 2 )
                $originalKeys[$key[0]] = $key[1];
        }
    }

    /*!
     Removes all override keys.
     \sa setKeys
    */
    function clearKeys()
    {
        $this->Keys = array();
    }

    /*!
     \return the match keys.
     \sa setKeys
    */
    function keys()
    {
        return $this->Keys;
    }

    /*!
     \return the unique instance of the design resource.
    */
    function &instance()
    {
        $instance =& $GLOBALS["eZTemplateDesignResourceInstance"];
        if ( get_class( $instance ) != "eztemplatedesignresource" )
        {
            $instance = new eZTemplateDesignResource();
        }
        return $instance;
    }

    /*!
     \return the unique instance of the standard resource.
    */
    function &standardInstance()
    {
        $instance =& $GLOBALS["eZTemplateStandardResourceInstance"];
        if ( get_class( $instance ) != "eztemplatedesignresource" )
        {
            $instance = new eZTemplateDesignResource( 'standard', true );
        }
        return $instance;
    }

    var $Keys;
    var $OnlyStandard;
}

?>
