<?php
//
// Definition of eZTemplatedesignresource class
//
// Created on: <14-Sep-2002 15:37:17 amos>
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

/*! \file eztemplatedesignresource.php
*/

/*!
  \class eZTemplatedesignresource eztemplatedesignresource.php
  \brief Handles template file loading with override support

*/

//include_once( "lib/eztemplate/classes/eztemplatefileresource.php" );
//include_once( "lib/ezutils/classes/ezini.php" );

class eZTemplateDesignResource extends eZTemplateFileResource
{
    /*!
     Initializes with a default resource name "design".
    */
    function eZTemplateDesignResource( $name = "design", $onlyStandard = false )
    {
        $this->eZTemplateFileResource( $name, true );
        $this->Keys = array();
        $this->KeyStack = array();
        $this->OnlyStandard = $onlyStandard;
    }

    /*!
    */
    function templateNodeTransformation( $functionName, &$node,
                                         $tpl, &$resourceData, $parameters, $namespaceValue )
    {
        if ( $this->Name != 'design' and $this->Name != 'standard' )
            return false;

        $file = $resourceData['template-name'];
        $matchFileArray = $this->overrideArray( $this->OverrideSiteAccess );
        $matchList = array();
        foreach ( $matchFileArray as $matchFile )
        {
            if ( !isset( $matchFile['template'] ) )
                continue;
            if ( $matchFile['template'] == ('/' . $file) )
            {
                $matchList[] = $matchFile;
            }
        }

        $resourceName = $resourceData['resource'];
        $resourceNameText = eZPHPCreator::variableText( $resourceName );

        $designKeysName = 'dKeys';
        if ( $resourceName == 'standard' )
            $designKeysName = 'rKeys';

        $newNodes = array();
        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "if " . ( $resourceData['use-comments'] ? ( "/*TDR:" . __LINE__ . "*/" ) : "" ) . "( !isset( \$$designKeysName ) )\n" .
                                                               "{\n" .
                                                               "    \$resH = \$tpl->resourceHandler( $resourceNameText );\n" .
                                                               "    \$$designKeysName = \$resH->keys();" .
                                                               "\n" .
                                                               "}\n" );
        foreach ( $matchList as $match )
        {
            $basedir = $match['base_dir'];
            $template = $match['template'];
            $file = $basedir . $template;
            $spacing = 0;
            $addFileResource = true;
            if ( isset( $match['custom_match'] ) )
            {
                $spacing = 4;
                $customMatchList = $match['custom_match'];
                $matchCount = 0;
                foreach ( $customMatchList as $customMatch )
                {
                    $matchConditionCount = count( $customMatch['conditions'] );
                    $code = '';
                    if ( $matchCount > 0 )
                    {
                        $code = "else " . ( $resourceData['use-comments'] ? ( "/*TDR:" . __LINE__ . "*/" ) : "" ) . "";
                    }
                    if ( $matchConditionCount > 0 )
                    {
                        if ( $matchCount > 0 )
                            $code .= " ";
                        $code .= "if " . ( $resourceData['use-comments'] ? ( "/*TDR:" . __LINE__ . "*/" ) : "" ) . "( ";
                    }
                    $ifLength = strlen( $code );
                    $conditionCount = 0;
                    if ( is_array( $customMatch['conditions'] ) )
                    {
                        foreach ( $customMatch['conditions'] as $conditionName => $conditionValue )
                        {
                            if ( $conditionCount > 0 )
                                $code .= " and\n" . str_repeat( ' ', $ifLength );
                            $conditionNameText = eZPHPCreator::variableText( $conditionName, 0 );
                            $conditionValueText = eZPHPCreator::variableText( $conditionValue, 0 );

                            $code .= "isset( \$" . $designKeysName . "[$conditionNameText] ) and ";
                            if ( $conditionName == 'url_alias' )
                            {
                                $code .= "(strpos( \$" . $designKeysName . "[$conditionNameText], $conditionValueText ) === 0 )";
                            }
                            else
                            {
                                $code .= "( is_array( \$" . $designKeysName . "[$conditionNameText] ) ? " .
                                         "in_array( $conditionValueText, \$" . $designKeysName . "[$conditionNameText] ) : " .
                                         "\$" . $designKeysName . "[$conditionNameText] == $conditionValueText )";
                            }
                            ++$conditionCount;
                        }
                    }
                    if ( $matchConditionCount > 0 )
                    {
                        $code .= " )\n";
                    }
                    if ( $matchConditionCount > 0 or $matchCount > 0 )
                    {
                        $code .= "{";
                    }
                    $matchFile = $customMatch['match_file'];
                    $newNodes[] = eZTemplateNodeTool::createCodePieceNode( $code );
                    $newNodes[] = eZTemplateNodeTool::createResourceAcquisitionNode( '',
                                                                                     $matchFile, $matchFile,
                                                                                     eZTemplate::RESOURCE_FETCH, false,
                                                                                     $node[4], array( 'spacing' => $spacing ),
                                                                                     $namespaceValue );
                    if ( $matchConditionCount > 0 or $matchCount > 0 )
                    {
                        $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "}" );
                    }
                    ++$matchCount;
                    if ( $matchConditionCount == 0 )
                    {
                        $addFileResource = false;
                        break;
                    }
                }
                if ( $addFileResource )
                    $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "else" . ( $resourceData['use-comments'] ? ( "/*TDR:" . __LINE__ . "*/" ) : "" ) . "\n{" );
            }
            if ( $addFileResource )
            {
                $newNodes[] = eZTemplateNodeTool::createResourceAcquisitionNode( '',
                                                                                 $file, $file,
                                                                                 eZTemplate::RESOURCE_FETCH, false,
                                                                                 $node[4], array( 'spacing' => $spacing ),
                                                                                 $namespaceValue );
            }
            if ( isset( $match['custom_match'] ) and $addFileResource )
                $newNodes[] = eZTemplateNodeTool::createCodePieceNode( "}" );
        }

        return $newNodes;
    }

    /*!
     \static
     \return the sitedesign for the design type \a $type, currently \c standard and \c site is allowed.
             If no sitedesign is set it will fetch it from site.ini.
    */
    static function designSetting( $type = 'standard' )
    {
        if ( $type != 'standard' and
             $type != 'site' )
        {
            eZDebug::writeWarning( "Cannot retrieve designsetting for type '$type'", 'eZTemplateDesignResource::designSetting' );
            return null;
        }
        if ( $type == 'site' )
        {
            if ( !empty( $GLOBALS['eZSiteBasics']['site-design-override'] ) )
            {
                return $GLOBALS['eZSiteBasics']['site-design-override'];
            }
        }
        if ( isset( $GLOBALS['eZTemplateDesignSetting'][$type] ) )
        {
            return $GLOBALS['eZTemplateDesignSetting'][$type];
        }
        $ini = eZINI::instance();
        if ( $type == 'standard' )
        {
            $GLOBALS['eZTemplateDesignSetting'][$type] = $ini->variable( "DesignSettings", "StandardDesign" );
        }
        else if ( $type == 'site' )
        {
            $GLOBALS['eZTemplateDesignSetting'][$type] = $ini->variable( "DesignSettings", "SiteDesign" );
        }
        return $GLOBALS['eZTemplateDesignSetting'][$type];
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
        if ( !isset( $GLOBALS['eZTemplateDesignSetting'] ) )
        {
            $GLOBALS['eZTemplateDesignSetting'] = array();
        }
        $GLOBALS['eZTemplateDesignSetting'][$type] = $designSetting;
    }

    /*!
     \return the rules used for matching design elements. \a $element defines the element type.
    */
    static function fileMatchingRules( $element, $path, $onlyStandard = false )
    {
        eZDebug::accumulatorStart( 'matching_rules', 'override', 'Matching rules' );

        $standardBase = eZTemplateDesignResource::designSetting( 'standard' );
        if ( !$onlyStandard )
            $siteBase = eZTemplateDesignResource::designSetting( 'site' );

        $ini = eZINI::instance();
        $additionalSiteDesignList = $ini->variable( "DesignSettings", "AdditionalSiteDesignList" );

        //include_once( 'lib/ezutils/classes/ezextension.php' );
        $extensionDirectory = eZExtension::baseDirectory();

        $designINI = eZINI::instance( 'design.ini' );
        $extensions = $designINI->variable( 'ExtensionSettings', 'DesignExtensions' );

        $matches = array();

        $elementText = '';
        if ( $element !== false )
            $elementText = $element . '/';

        $designStartPath = eZTemplateDesignResource::designStartPath();

        $designList = $additionalSiteDesignList;
        array_unshift( $designList, $siteBase );
        $designList[] = $standardBase;

        if ( $onlyStandard )
            $designList = array( $standardBase );


        foreach ( $designList as $design )
        {
            foreach ( $extensions as $extension )
            {
                $matches[] = array( 'file' => "$extensionDirectory/$extension/$designStartPath/$design/override/$elementText$path",
                                    'type' => 'override' );
            }
            $matches[] = array( 'file' => "$designStartPath/$design/override/$elementText$path",
                                'type' => 'override' );
        }

        foreach ( $designList as $design )
        {
            foreach ( $extensions as $extension )
            {
                $matches[] = array( 'file' => "$extensionDirectory/$extension/$designStartPath/$design/$elementText$path",
                                    'type' => 'normal' );
            }
            $matches[] = array( 'file' => "$designStartPath/$design/$elementText$path",
                                'type' => 'normal' );
        }
        eZDebug::accumulatorStop( 'matching_rules' );
        return $matches;
    }

    /*!
     Loads the template file if it exists, also sets the modification timestamp.
     Returns true if the file exists.
    */
    function handleResource( $tpl, &$resourceData, $method, &$extraParameters )
    {
        $path = $resourceData['template-name'];

        $matchKeys = $this->Keys;
        if ( isset( $GLOBALS['eZDesignKeys'] ) )
        {
            $matchKeys = array_merge( $matchKeys, $GLOBALS['eZDesignKeys'] );
            unset( $GLOBALS['eZDesignKeys'] );
            $this->Keys = $matchKeys;
        }
        if ( is_array( $extraParameters ) and
             isset( $extraParameters['ezdesign:keys'] ) )
        {
            $this->mergeKeys( $matchKeys, $extraParameters['ezdesign:keys'] );
        }
        $this->KeyStack[] = $this->Keys;
        $this->Keys = $matchKeys;

        eZDebug::accumulatorStart( 'override_cache', 'override', 'Cache load' );

        $overrideCacheFile = $this->createOverrideCache();

        if ( $overrideCacheFile )
        {
            include_once( $overrideCacheFile );
            if( isset( $GLOBALS['eZOverrideTemplateCacheMap'][md5( '/' . $path )] ) )
            {
                $cacheMap = $GLOBALS['eZOverrideTemplateCacheMap'][md5( '/' . $path )];
                if ( !is_string( $cacheMap ) and trim( $cacheMap['code'] ) )
                {
                    eval( "\$matchFile = " . $cacheMap['code'] . ";" );
                }
                else
                {
                    $matchFile = $cacheMap;
                }
                $match['file'] = $matchFile;
            }
        }
        else
        {
            $template = "/" . $path;
            // TODO add correct memory cache
//            $matchFileArray = false;
            if ( empty( $GLOBALS['eZTemplateOverrideArray_' . $this->OverrideSiteAccess] ) )
            {
                $GLOBALS['eZTemplateOverrideArray_' . $this->OverrideSiteAccess] = $this->overrideArray( $this->OverrideSiteAccess );
            }
            $matchFileArray = $GLOBALS['eZTemplateOverrideArray_' . $this->OverrideSiteAccess];

            $matchFile = $matchFileArray[$template];

            if ( isset( $matchFile['custom_match'] ) )
            {
                $matchFound = false;
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
                            else if ( isset( $matchKeys[$conditionKey] ) and
                                      isset( $customMatch['conditions'][$conditionKey] ) )
                            {
                                if ( is_array( $matchKeys[$conditionKey] ) )
                                {
                                    if ( !in_array( $customMatch['conditions'][$conditionKey], $matchKeys[$conditionKey] ) )
                                    {
                                        $matchOverride = false;
                                    }
                                }
                                else if ( $matchKeys[$conditionKey] != $customMatch['conditions'][$conditionKey] )
                                {
                                    $matchOverride = false;
                                }
                            }
                            else
                            {
                                $matchOverride = false;
                            }
                        }
                        if ( $matchOverride == true )
                        {
                            $match['file'] = $customMatch['match_file'];
                            $matchFound = true;
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
                        $matchFound = true;
                    }
                }
                if ( !$matchFound )
                    $match['file'] = $matchFile['base_dir'] . $matchFile['template'];
            }
            else
            {
                $match['file'] = $matchFile['base_dir'] . $matchFile['template'];
            }
        }
        eZDebug::accumulatorStop( 'override_cache' );
        if ( !isset( $match ) or $match === null )
            return false;

        $file = $match["file"];

        $matchedKeys = array();
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
        $oldKeys = array_pop( $this->KeyStack );
        $this->Keys = $oldKeys;
        return $result;
    }

    /*!
     Generates the cache for the template override matching.
    */
    function createOverrideCache()
    {
        if ( isset( $GLOBALS['eZSiteBasics'] ) )
        {
            if ( isset( $GLOBALS['eZSiteBasics']['no-cache-adviced'] ) and
                 $GLOBALS['eZSiteBasics']['no-cache-adviced'] )
                return false;
        }
        global $eZTemplateOverrideCacheNoPermission;
        if ( $eZTemplateOverrideCacheNoPermission == "nocache" )
        {
            return false;
        }

        $ini = eZINI::instance( 'site.ini' );
        $useOverrideCache = true;
        if ( $ini->hasVariable( 'OverrideSettings', 'Cache' ) )
            $useOverrideCache = $ini->variable( 'OverrideSettings', 'Cache' ) == 'enabled';

        $standardBase = eZTemplateDesignResource::designSetting( 'standard' );
        if ( !$this->OnlyStandard )
            $siteBase = eZTemplateDesignResource::designSetting( 'site' );

        $overrideKeys = $this->overrideKeys();

        $overrideKey = md5( implode( ',', $overrideKeys ) . $siteBase . $standardBase );
        $cacheDir = eZSys::cacheDirectory();

        $overrideCacheFile = $cacheDir.'/override/override_'.$overrideKey.'.php';

        // Build matching cache only of it does not already exists,
        // or override file has been updated
        if ( !$useOverrideCache or
             !file_exists( $overrideCacheFile ) )
        {
            $matchFileArray = $this->overrideArray( $this->OverrideSiteAccess );

            // Generate PHP compiled cache file.
            //include_once( 'lib/ezutils/classes/ezphpcreator.php' );
            $phpCache = new eZPHPCreator( "$cacheDir/override", "override_$overrideKey.php" );

            $phpCode = "\$GLOBALS['eZOverrideTemplateCacheMap'] = array (\n";
            $numMatchFiles = count ( $matchFileArray );
            $countMatchFiles = 0;
//            $phpCode .= "switch ( \$matchFile )\n{\n    ";
            foreach ( array_keys( $matchFileArray ) as $matchKey )
            {
                $countMatchFiles++;
                $phpCode .= '\'' . md5( $matchKey ) . '\' => ';
                if ( isset( $matchFileArray[$matchKey]['custom_match'] ) )
                {
                    $baseDir = isset( $matchFileArray[$matchKey]['base_dir'] ) ? $matchFileArray[$matchKey]['base_dir'] : '';
                    $defaultMatchFile = $baseDir . $matchKey;
                    // Custom override matching
//                    $phpCode .= "    case  \"$matchKey\":\n    {\n";

                    $matchConditionArray = array();
                    foreach ( $matchFileArray[$matchKey]['custom_match'] as $customMatch )
                    {
                        $matchCondition = "";
                        $condCount = 0;
                        if ( is_array( $customMatch['conditions'] ) )
                        {
                            foreach ( array_keys( $customMatch['conditions'] ) as $conditionKey )
                            {
                                if ( $condCount > 0 )
                                    $matchCondition .= " and ";

                                // Have a special substring match for subtree matching

                                $matchCondition .= "( isset( \$matchKeys[\\'$conditionKey\\'] ) and ";
                                if ( $conditionKey == 'url_alias' )
                                {
                                    $matchCondition .=
                                        "( strpos( \$matchKeys[\\'url_alias\\'],  \\'" . $customMatch['conditions']['url_alias'] . "\\' ) === 0 ) )";
                                }
                                else
                                {
                                    $matchCondition .=
                                        "( is_array( \$matchKeys[\\'$conditionKey\\'] ) ? " .
                                        "in_array( \\'" . $customMatch['conditions'][$conditionKey] . "\\', \$matchKeys[\\'$conditionKey\\'] ) : " .
                                        "\$matchKeys[\\'$conditionKey\\'] == \\'" . $customMatch['conditions'][$conditionKey] . "\\') )";
                                }

                                $condCount++;
                            }
                        }

                        // Only create custom match if conditions are defined
                        if ( $matchCondition != "" )
                        {
//                            $phpCode .= "        if ( $matchCondition )\n        {\n";
//                            $phpCode .= "            return '" . $customMatch['match_file'] . "';\n        }\n";
                            if ( $condCount > 1 )
                                $matchConditionArray[] = array( 'condition' => '(' . $matchCondition . ')',
                                                                'matchFile' => $customMatch['match_file'] );
                            else
                                $matchConditionArray[] = array( 'condition' => $matchCondition,
                                                                'matchFile' => $customMatch['match_file'] );
                        }
                        else
                        {
                            // No override conditions defined. Override default match file
                            $defaultMatchFile = $customMatch['match_file'];
                        }
                    }

                    $phpCode .= "array ( 'eval' => 1, 'code' => ";

                    $phpCode .= "'";

                    foreach ( array_keys( $matchConditionArray ) as $key )
                    {
                        $phpCode .= '(' . $matchConditionArray[$key]['condition'] . ' ? ' . "\\'" .  $matchConditionArray[$key]['matchFile'] . "\\'" . ' : ';
                    }

                    $phpCode .= "\\'" . $defaultMatchFile . "\\'";

                    for ( $condCount = 0; $condCount < count( $matchConditionArray ); $condCount++)
                    {
                        $phpCode .= ')';
                    }

                    $phpCode .= "' )";
                }
                else
                {
                    $phpCode .= "'". $matchFileArray[$matchKey]['base_dir'] . $matchKey . "'";
                    // Plain matching without custom override
//                    $phpCode .= "case  \"$matchKey\":\n    {\n
//                           return '" .
//                         $matchFileArray[$matchKey]['base_dir'] . $matchKey . "';}\nbreak;\n";
                }

                if ( $countMatchFiles < $numMatchFiles )
                {
                    $phpCode .= ",\n";
                }
                else
                {
                    $phpCode .= ");\n";
                }
            }
//            $phpCode .= "default:\n {\n}break;\n}";

//            $phpCode .= "}\n";

//            $phpCode .= "function overrideFile( \$matchFile, \$matchKeys )\n{\n    ";
//            $phpCode .= '  eval( "\$return = " . $GLOBALS[\'eZOverrideTemplateCacheMap\'][$matchFile] . ";" );' . "\n";
//            $phpCode .= '  return $return;' . "\n}\n\n";

            $phpCache->addCodePiece( $phpCode );
            if ( $useOverrideCache and
                 $phpCache->store() )
            {

            }
            else
            {
                if ( $useOverrideCache )
                {
                    eZDebug::writeError( "Could not write template override cache file, check permissions in $cacheDir/override/.\nRunning eZ Publish without this cache will have a performance impact.", "eZTemplateDesignResource::createOverrideCache" );
                }
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
    function overrideKeys( $siteAccess = false )
    {
//        print( "<br>" . xdebug_call_function() . "<br>" );
        $keys = array();
        $designStartPath = eZTemplateDesignResource::designStartPath();
        $keys[] = $designStartPath;

        // fetch the override array from a specific siteacces
        if ( $siteAccess )
        {
            // Get the design resources
            $ini = eZINI::instance( 'site.ini', 'settings', null, null, true );
            $ini->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
            eZExtension::prependExtensionSiteAccesses( $siteAccess, $ini, false, 'siteaccess' );
            $ini->loadCache();

            $overrideINI = eZINI::instance( 'override.ini', 'settings', null, null, true );
            $overrideINI->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
            eZExtension::prependExtensionSiteAccesses( $siteAccess, $overrideINI, false, 'siteaccess', false );
            $overrideINI->loadCache();

            $standardBase = $ini->variable( "DesignSettings", "StandardDesign" );
            $keys[] = "siteaccess/$siteAccess";
            $keys[] = $standardBase;
            if ( !$this->OnlyStandard )
            {
                $siteBase = $ini->variable( "DesignSettings", "SiteDesign" );
                $keys[] = $siteBase;
            }
        }
        else
        {
            $ini = eZINI::instance();
            if ( $this->OverrideSiteAccess != false )
            {
                $overrideINI = eZINI::instance( 'override.ini', 'settings', null, null, true );
                eZExtension::prependExtensionSiteAccesses( $siteAccess, $overrideINI, false, 'siteaccess' );
                $overrideINI->prependOverrideDir( "siteaccess/$this->OverrideSiteAccess", false, 'siteaccess', false );
                $overrideINI->loadCache();
                $keys[] = "siteaccess/$this->OverrideSiteAccess";
            }
            else
            {
                $overrideINI = eZINI::instance( 'override.ini' );
                $currentAccess = $GLOBALS['eZCurrentAccess'];
                $siteAccess = $currentAccess['name'];
                $keys[] = "siteaccess/$siteAccess";
            }

            $standardBase = eZTemplateDesignResource::designSetting( 'standard' );
            $keys[] = $standardBase;
            $siteBase = eZTemplateDesignResource::designSetting( 'site' );
            if ( !$this->OnlyStandard )
                $keys[] = $siteBase;
        }


        $additionalSiteDesignList = $ini->variable( "DesignSettings", "AdditionalSiteDesignList" );
        $keys = array_merge( $keys, $additionalSiteDesignList );

        // Add extension paths
        //include_once( 'lib/ezutils/classes/ezextension.php' );
        $extensionDirectory = eZExtension::baseDirectory();

        $designINI = eZINI::instance( 'design.ini' );
        $extensions = $designINI->variable( 'ExtensionSettings', 'DesignExtensions' );

        return  array_merge( $keys, $extensions );
    }

    /*!
     \static
    */
    static function serializeOverrides( $siteAccess = false,
                                 $matchKeys = array() )
    {
    }

    /*!
     \static
     \return Gives all knows bases for avialable sitedesign folders.
    */
    static function allDesignBases()
    {
        $ini = eZINI::instance();

        //include_once( 'lib/ezutils/classes/ezextension.php' );
        $extensionDirectory = eZExtension::baseDirectory();
        $designINI = eZINI::instance( 'design.ini' );
        $extensions = $designINI->variable( 'ExtensionSettings', 'DesignExtensions' );

        $bases = array();

        $std_base = eZTemplateDesignResource::designSetting( 'standard' );
        $site_base = eZTemplateDesignResource::designSetting( 'site' );
        $SiteDesignList = $ini->variable( 'DesignSettings', 'AdditionalSiteDesignList' );
        array_unshift( $SiteDesignList, $site_base );
        $SiteDesignList[] = $std_base;
        $designStartPath = eZTemplateDesignResource::designStartPath();
        foreach ( $SiteDesignList as $design )
        {
            $bases[] = "$designStartPath/$design";
            foreach( $extensions as $extension )
            {
               $bases[] = "$extensionDirectory/$extension/$designStartPath/$design";
            }
        }
        return $bases;
    }

    /*!
     \static
     \return The start path of the design directory, by default it will return \c 'design'
             To change the directory use setDesignStartPath().
    */
    static function designStartPath()
    {
        $designStartPath = false;
        if ( isset( $GLOBALS['eZTemplateDesignResourceStartPath'] ) )
        {
            $designStartPath = $GLOBALS['eZTemplateDesignResourceStartPath'];
        }
        if ( !$designStartPath )
            $designStartPath = 'design';
        return $designStartPath;
    }

    /*!
     \static
     Changes the design start path which is used to find design files.
     \param $path Must be a string defining the path or \c false to use default start path.
     \sa designStartPath();
    */
    static function setDesignStartPath( $path )
    {
        $GLOBALS['eZTemplateDesignResourceStartPath'] = $path;
    }

    /*!
     \static
     \return an array of all the current templates and overrides for them.
             The current siteaccess is used if none is specified.
    */
    static function overrideArray( $siteAccess = false, $onlyStandard = null )
    {
        if ( $onlyStandard === null and
             isset( $this ) and
             strtolower( get_class( $this ) ) == 'eztemplatedesignresource' )
            $onlyStandard = $this->OnlyStandard;

        // fetch the override array from a specific siteacces
        if ( $siteAccess )
        {
            // Get the design resources
            $ini = eZINI::instance( 'site.ini', 'settings', null, null, true );
            $ini->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
            eZExtension::prependExtensionSiteAccesses( $siteAccess, $ini, false, 'siteaccess' );
            $ini->loadCache();

            $overrideINI = eZINI::instance( 'override.ini', 'settings', null, null, true );
            $overrideINI->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
            eZExtension::prependExtensionSiteAccesses( $siteAccess, $overrideINI, false, 'siteaccess', false );
            $overrideINI->loadCache();

            $standardBase = $ini->variable( "DesignSettings", "StandardDesign" );
            if ( !$onlyStandard )
                $siteBase = $ini->variable( "DesignSettings", "SiteDesign" );
        }
        else
        {
            $ini = eZINI::instance();
            $overrideINI = eZINI::instance( 'override.ini' );
            $standardBase = eZTemplateDesignResource::designSetting( 'standard' );
            $siteBase = eZTemplateDesignResource::designSetting( 'site' );
        }

        $designStartPath = eZTemplateDesignResource::designStartPath();

        $additionalSiteDesignList = $ini->variable( 'DesignSettings', 'AdditionalSiteDesignList' );
        // Reverse additionalSiteDesignList so that most prefered design is last
        $additionalSiteDesignList = array_reverse( $additionalSiteDesignList );

        // Generate match cache for all templates
        //include_once( 'lib/ezfile/classes/ezdir.php' );

        // Build arrays of available files, start with base design and end with most prefered design
        $matchFilesArray = array();

        // For each override dir overwrite current default file
        // TODO: fetch all resource repositories
        $resourceArray[] = "$designStartPath/$standardBase/templates";
        $resourceArray[] = "$designStartPath/$standardBase/override/templates";

        // Add the additional sitedesigns
        foreach ( $additionalSiteDesignList as $additionalSiteDesign )
        {
            $resourceArray[] = "$designStartPath/$additionalSiteDesign/override/templates";
            $resourceArray[] = "$designStartPath/$additionalSiteDesign/templates";
        }

        $resourceArray[] = "$designStartPath/$siteBase/override/templates";
        $resourceArray[] = "$designStartPath/$siteBase/templates";

        // Add extension paths
        //include_once( 'lib/ezutils/classes/ezextension.php' );
        $extensionDirectory = eZExtension::baseDirectory();

        $designINI = eZINI::instance( 'design.ini' );
        $extensions = $designINI->variable( 'ExtensionSettings', 'DesignExtensions' );

        foreach ( $extensions as $extension )
        {
            // Look for standard design in extension
            $resourceArray[] = "$extensionDirectory/$extension/$designStartPath/$standardBase/templates";
            $resourceArray[] = "$extensionDirectory/$extension/$designStartPath/$standardBase/override/templates";

            // Look for aditional sitedesigns in extension
            foreach ( $additionalSiteDesignList as $additionalSiteDesign )
            {
                $resourceArray[] = "$extensionDirectory/$extension/$designStartPath/$additionalSiteDesign/override/templates";
                $resourceArray[] = "$extensionDirectory/$extension/$designStartPath/$additionalSiteDesign/templates";
            }

            // Look for site base in extention
            $resourceArray[] = "$extensionDirectory/$extension/$designStartPath/$siteBase/override/templates";
            $resourceArray[] = "$extensionDirectory/$extension/$designStartPath/$siteBase/templates";
        }

        foreach ( $resourceArray as $resource )
        {
            $sourceFileArray = eZDir::recursiveFindRelative( $resource, "",  "tpl" );
            foreach ( array_keys( $sourceFileArray ) as $sourceKey )
            {
                $matchFileArray[$sourceFileArray[$sourceKey]]['base_dir'] = $resource;
                $matchFileArray[$sourceFileArray[$sourceKey]]['template'] = $sourceFileArray[$sourceKey];
            }
        }


        // Load complex/custom override templates
        $overrideSettingGroupArray = $overrideINI->groups();
        if ( isset( $GLOBALS['eZDesignOverrides'] ) )
        {
            $overrideSettingGroupArray = array_merge( $overrideSettingGroupArray, $GLOBALS['eZDesignOverrides'] );
        }

        foreach ( array_keys( $overrideSettingGroupArray ) as $overrideSettingKey )
        {
            $overrideName = $overrideSettingKey;
            $overrideSource = "/" . $overrideSettingGroupArray[$overrideSettingKey]['Source'];

            $overrideMatchConditionArray = isset( $overrideSettingGroupArray[$overrideSettingKey]['Match'] ) ?
                $overrideSettingGroupArray[$overrideSettingKey]['Match'] :
                null;
            $overrideMatchFile = $overrideSettingGroupArray[$overrideSettingKey]['MatchFile'];

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
            if ( ! $overrideMatchFilePath )
            {
                eZDebug::writeError( "Custom match file: path '$overrideMatchFile' not found in any resource. Check template settings in settings/override.ini",
                                     "eZTemplateDesignResource::overrideArray" );
                eZDebug::writeError( implode( ', ', $triedFiles ),
                                     "eZTemplateDesignResource::overrideArray, tried files" );
            }

        }

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
        if ( isset( $GLOBALS['eZDesignKeys'] ) )
        {
            return array_merge( $this->Keys, $GLOBALS['eZDesignKeys'] );
        }
        return $this->Keys;
    }

    /*!
     \static
    */
    static function addGlobalOverride( $name, $source, $match, $subdir, $matches )
    {
        if ( !isset( $GLOBALS['eZDesignOverrides'] ) )
            $GLOBALS['eZDesignOverrides'] = array();
        $GLOBALS['eZDesignOverrides'][$name] = array( 'Source' => $source,
                                                      'MatchFile' => $match,
                                                      'Subdir' => $subdir,
                                                      'Match' => $matches );
    }

    /*!
     \return the unique instance of the design resource.
    */
    static function instance()
    {
        if ( !isset( $GLOBALS['eZTemplateDesignResourceInstance'] ) )
        {
            $GLOBALS['eZTemplateDesignResourceInstance'] = new eZTemplateDesignResource();
        }
        return $GLOBALS['eZTemplateDesignResourceInstance'];
    }

    /*!
     \return the unique instance of the standard resource.
    */
    static function standardInstance()
    {
        if ( !isset( $GLOBALS['eZTemplateStandardResourceInstance'] ) )
        {
            $GLOBALS['eZTemplateStandardResourceInstance'] = new eZTemplateDesignResource( 'standard', true );
        }
        return $GLOBALS['eZTemplateStandardResourceInstance'];
    }

    /*!
     Sets the siteaccess which are to be used for loading the override settings.
    */
    function setOverrideAccess( $siteAccess )
    {
        $this->OverrideSiteAccess = $siteAccess;
    }

    public $Keys;
    public $OnlyStandard;
    public $OverrideSiteAccess = false;
}

?>
