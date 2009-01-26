<?php
//
// Definition of eZTemplatedesignresource class
//
// Created on: <14-Sep-2002 15:37:17 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file
*/

/*!
  \class eZTemplatedesignresource eztemplatedesignresource.php
  \brief Handles template file loading with override support

*/

class eZTemplateDesignResource extends eZTemplateFileResource
{
    /*!
     Initializes with a default resource name "design".
    */
    function eZTemplateDesignResource( $name = "design" )
    {
        $this->eZTemplateFileResource( $name, true );
        $this->Keys = array();
        $this->KeyStack = array();
    }

    function templateNodeTransformation( $functionName, &$node,
                                         $tpl, &$resourceData, $parameters, $namespaceValue )
    {
        if ( $this->Name != 'design' and $this->Name != 'standard' )
            return false;

        $file = $resourceData['template-name'];
        $matchFileArray = eZTemplateDesignResource::overrideArray( $this->OverrideSiteAccess );
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
     \static

    */
    static function fileMatch( $bases, $element, $path, &$triedFiles )
    {
        foreach ( $bases as $base )
        {
            $resource = $element != '' ? "$base/$element" : $base;
            $possibleMatchFile = $resource . '/' . $path;

            $triedFiles[] = $possibleMatchFile;

            if ( file_exists( $possibleMatchFile ) )
            {
                return array( 'resource' => $resource,
                              'path' => $possibleMatchFile );
            }
        }

        return false;
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

        if( !isset( $GLOBALS['eZOverrideTemplateCacheMap'] ) )
        {
            $overrideCacheFile = $this->createOverrideCache();

            if ( $overrideCacheFile )
            {
                include_once( $overrideCacheFile );
            }
        }

        if ( isset( $GLOBALS['eZOverrideTemplateCacheMap'] ) )
        {
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
                $GLOBALS['eZTemplateOverrideArray_' . $this->OverrideSiteAccess] = eZTemplateDesignResource::overrideArray( $this->OverrideSiteAccess );
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
            $matchFileArray = eZTemplateDesignResource::overrideArray( $this->OverrideSiteAccess );

            // Generate PHP compiled cache file.
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
            $siteBase = $ini->variable( "DesignSettings", "SiteDesign" );
            $keys[] = $siteBase;
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
            $keys[] = $siteBase;
        }


        $additionalSiteDesignList = $ini->variable( "DesignSettings", "AdditionalSiteDesignList" );
        $keys = array_merge( $keys, $additionalSiteDesignList );

        // Add extension paths
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
     \return An array containing the names of the design extensions that are
             currently active
    */
    static function designExtensions()
    {
        $designINI = eZINI::instance( 'design.ini' );
        $extensions = $designINI->variable( 'ExtensionSettings', 'DesignExtensions' );
        return array_reverse( $extensions );
    }

    /*!
     \static
     \return Gives all knows bases for avialable sitedesign folders.
    */
    static function allDesignBases( $siteAccess = false )
    {
        if ( $siteAccess )
        {
            if ( isset( $GLOBALS['eZTemplateDesignResourceSiteAccessBases'] ) )
            {
                if ( isset( $GLOBALS['eZTemplateDesignResourceSiteAccessBases'][$siteAccess] ) )
                {
                    return $GLOBALS['eZTemplateDesignResourceSiteAccessBases'][$siteAccess];
                }
            }
            else
            {
                $GLOBALS['eZTemplateDesignResourceSiteAccessBases'] = array();
            }

            $ini = eZINI::instance( 'site.ini', 'settings', null, null, true );
            $ini->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
            eZExtension::prependExtensionSiteAccesses( $siteAccess, $ini, false, 'siteaccess' );
            $ini->loadCache();

            $standardDesign = $ini->variable( "DesignSettings", "StandardDesign" );
            $siteDesign = $ini->variable( "DesignSettings", "SiteDesign" );
        }
        else
        {
            if ( isset( $GLOBALS['eZTemplateDesignResourceBases'] ) )
            {
                return $GLOBALS['eZTemplateDesignResourceBases'];
            }

            $ini = eZINI::instance();
            $standardDesign = eZTemplateDesignResource::designSetting( 'standard' );
            $siteDesign = eZTemplateDesignResource::designSetting( 'site' );
        }

        $siteDesignList = $ini->variable( 'DesignSettings', 'AdditionalSiteDesignList' );

        array_unshift( $siteDesignList, $siteDesign );
        $siteDesignList[] = $standardDesign;

        $bases = array();
        $extensionDirectory = eZExtension::baseDirectory();
        $designStartPath = eZTemplateDesignResource::designStartPath();
        $extensions = eZTemplateDesignResource::designExtensions();

        foreach ( $siteDesignList as $design )
        {
            foreach ( $extensions as $extension )
            {
                $path = "$extensionDirectory/$extension/$designStartPath/$design";
                if ( file_exists( $path ) )
                {
                    $bases[] = $path;
                }
            }

            $path = "$designStartPath/$design";
            if ( file_exists( $path ) )
            {
                $bases[] = $path;
            }
        }

        if ( $siteAccess )
        {
            $GLOBALS['eZTemplateDesignResourceSiteAccessBases'][$siteAccess] = $bases;
        }
        else
        {
            $GLOBALS['eZTemplateDesignResourceBases'] = $bases;
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
    static function overrideArray( $siteAccess = false )
    {
        $bases = eZTemplateDesignResource::allDesignBases( $siteAccess );

        // fetch the override array from a specific siteacces
        if ( $siteAccess )
        {
            $overrideINI = eZINI::instance( 'override.ini', 'settings', null, null, true );
            $overrideINI->prependOverrideDir( "siteaccess/$siteAccess", false, 'siteaccess' );
            eZExtension::prependExtensionSiteAccesses( $siteAccess, $overrideINI, false, 'siteaccess', false );
            $overrideINI->loadCache();
        }
        else
        {
            $overrideINI = eZINI::instance( 'override.ini' );
        }

        $designStartPath = eZTemplateDesignResource::designStartPath();

        // Generate match cache for all templates

        // Build arrays of available files, start with standard design and end with most prefered design
        $matchFilesArray = array();

        $reverseBases = array_reverse( $bases );

        foreach ( $reverseBases as $base )
        {
            $templateResource = $base . '/templates';
            $sourceFileArray = eZDir::recursiveFindRelative( $templateResource, "",  "tpl" );
            foreach ( $sourceFileArray as $source )
            {
                $matchFileArray[$source]['base_dir'] = $templateResource;
                $matchFileArray[$source]['template'] = $source;
            }
        }

        // Load override templates
        $overrideSettingGroups = $overrideINI->groups();
        if ( isset( $GLOBALS['eZDesignOverrides'] ) )
        {
            $overrideSettingGroups = array_merge( $overrideSettingGroups, $GLOBALS['eZDesignOverrides'] );
        }

        foreach ( $overrideSettingGroups as $overrideName => $overrideSetting )
        {
            $overrideSource = "/" . $overrideSetting['Source'];
            $overrideMatchFile = $overrideSetting['MatchFile'];

            // Find the matching file in the available resources
            $triedFiles = array();
            $fileInfo = eZTemplateDesignResource::fileMatch( $bases, 'override/templates', $overrideMatchFile, $triedFiles );

            $resourceInUse = is_array( $fileInfo ) ? $fileInfo['resource'] : false;
            $overrideMatchFilePath = is_array( $fileInfo ) ? $fileInfo['path'] : false;

            // if the override template is not found
            // then we probably shouldn't use it
            // there should be added a check around the following code
            // if ( $overrideMatchFilePath )
            // {
            $customMatchArray = array();
            $customMatchArray['conditions'] = isset( $overrideSetting['Match'] ) ? $overrideSetting['Match'] : null;
            $customMatchArray['match_file'] = $overrideMatchFilePath;
            $customMatchArray['override_name'] = $overrideName;

            $matchFileArray[$overrideSource]['custom_match'][] = $customMatchArray;
            // }

            // if overriding a non-existing template
            // then we use the override template as main template
            // this code should probably be removed
            // because we should not allow an override if the main template is missing
            if ( $resourceInUse && !isset( $matchFileArray[$overrideSource]['base_dir'] ) )
            {
                $matchFileArray[$overrideSource]['base_dir'] = $resourceInUse;
                $matchFileArray[$overrideSource]['template'] = $overrideSource;
            }

            if ( ! $overrideMatchFilePath )
            {
                eZDebug::writeError( "Custom match file: path '$overrideMatchFile' not found in any resource. Check the template settings in settings/override.ini",
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
     Sets the siteaccess which are to be used for loading the override settings.
    */
    function setOverrideAccess( $siteAccess )
    {
        $this->OverrideSiteAccess = $siteAccess;
    }

    public $Keys;
    public $OverrideSiteAccess = false;
}

?>
