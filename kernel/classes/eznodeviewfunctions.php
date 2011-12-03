<?php
/**
 * File containing the eZNodeviewfunctions class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZNodeviewfunctions eznodeviewfunctions.php
  \brief The class eZNodeviewfunctions does

*/

class eZNodeviewfunctions
{
    // Deprecated function for generating the view cache
    static function generateNodeView( $tpl, $node, $object, $languageCode, $viewMode, $offset,
                                      $cacheDir, $cachePath, $viewCacheEnabled,
                                      $viewParameters = array( 'offset' => 0, 'year' => false, 'month' => false, 'day' => false ),
                                      $collectionAttributes = false, $validation = false )
    {
        $cacheFile = eZClusterFileHandler::instance( $cachePath );
        $args = compact( "tpl", "node", "object", "languageCode", "viewMode", "offset",
                         "viewCacheEnabled",
                         "viewParameters",
                         "collectionAttributes", "validation" );
        $Result = $cacheFile->processCache( null, // no retrieve, only generate is called
                                            array( 'eZNodeviewfunctions', 'generateCallback' ),
                                            null,
                                            null,
                                            $args );
        return $Result;
    }

    // Note: This callback is needed to generate the array which is returned
    //       back to eZClusterFileHandler for processing.
    static function generateCallback( $file, $args )
    {
        extract( $args );

        $res = eZNodeViewFunctions::generateNodeViewData( $tpl, $node, $object, $languageCode, $viewMode, $offset,
                                                          $viewParameters, $collectionAttributes, $validation );


        // Check if cache time = 0 (viewcache disabled)
        $store = $res['cache_ttl'] != 0;
        // or if explicitly turned off
        if ( !$viewCacheEnabled )
            $store = false;
        $retval = array( 'content' => $res,
                         'scope'   => 'viewcache',
                         'store'   => $store );
        if ( $store )
            $retval['binarydata'] = serialize( $res );

        return $retval;
    }

    /**
     * Generate result data for a node view
     *
     * @param eZTemplate $tpl
     * @param eZContentObjectTreeNode $node
     * @param eZContentObject $object
     * @param false|string $languageCode
     * @param string $viewMode
     * @param int $offset
     * @param array $viewParameters
     * @param false|array $collectionAttributes
     * @param bool $validation
     * @return array Result array for view
     */
    static function generateNodeViewData( eZTemplate $tpl, eZContentObjectTreeNode $node, eZContentObject $object, $languageCode, $viewMode, $offset,
                                          array $viewParameters = array( 'offset' => 0, 'year' => false, 'month' => false, 'day' => false ),
                                          $collectionAttributes = false, $validation = false )
    {
        $section = eZSection::fetch( $object->attribute( 'section_id' ) );
        if ( $section )
        {
            $navigationPartIdentifier = $section->attribute( 'navigation_part_identifier' );
            $sectionIdentifier = $section->attribute( 'identifier' );
        }
        else
        {
            $navigationPartIdentifier = null;
            $sectionIdentifier = null;
        }

        $keyArray = array( array( 'object', $object->attribute( 'id' ) ),
                           array( 'node', $node->attribute( 'node_id' ) ),
                           array( 'parent_node', $node->attribute( 'parent_node_id' ) ),
                           array( 'class', $object->attribute( 'contentclass_id' ) ),
                           array( 'class_identifier', $node->attribute( 'class_identifier' ) ),
                           array( 'view_offset', $offset ),
                           array( 'viewmode', $viewMode ),
                           array( 'remote_id', $object->attribute( 'remote_id' ) ),
                           array( 'node_remote_id', $node->attribute( 'remote_id' ) ),
                           array( 'navigation_part_identifier', $navigationPartIdentifier ),
                           array( 'depth', $node->attribute( 'depth' ) ),
                           array( 'url_alias', $node->attribute( 'url_alias' ) ),
                           array( 'class_group', $object->attribute( 'match_ingroup_id_list' ) ),
                           array( 'state', $object->attribute( 'state_id_array' ) ),
                           array( 'state_identifier', $object->attribute( 'state_identifier_array' ) ),
                           array( 'section', $object->attribute( 'section_id' ) ),
                           array( 'section_identifier', $sectionIdentifier ) );

        $parentClassID = false;
        $parentClassIdentifier = false;
        $parentNodeRemoteID = false;
        $parentObjectRemoteID = false;
        $parentNode = $node->attribute( 'parent' );
        if ( is_object( $parentNode ) )
        {
            $parentNodeRemoteID = $parentNode->attribute( 'remote_id' );
            $keyArray[] = array( 'parent_node_remote_id', $parentNodeRemoteID );

            $parentObject = $parentNode->attribute( 'object' );
            if ( is_object( $parentObject ) )
            {
                $parentObjectRemoteID = $parentObject->attribute( 'remote_id' );
                $keyArray[] = array( 'parent_object_remote_id', $parentObjectRemoteID );

                $parentClass = $parentObject->contentClass();
                if ( is_object( $parentClass ) )
                {
                    $parentClassID = $parentClass->attribute( 'id' );
                    $parentClassIdentifier = $parentClass->attribute( 'identifier' );

                    $keyArray[] = array( 'parent_class', $parentClassID );
                    $keyArray[] = array( 'parent_class_identifier', $parentClassIdentifier );
                }
            }
        }

        $res = eZTemplateDesignResource::instance();
        $res->setKeys( $keyArray );

        if ( $languageCode )
        {
            $oldLanguageCode = $node->currentLanguage();
            $node->setCurrentLanguage( $languageCode );
        }

        $tpl->setVariable( 'node', $node );
        $tpl->setVariable( 'viewmode', $viewMode );
        $tpl->setVariable( 'language_code', $languageCode );
        $tpl->setVariable( 'view_parameters', $viewParameters );
        $tpl->setVariable( 'collection_attributes', $collectionAttributes );
        $tpl->setVariable( 'validation', $validation );
        $tpl->setVariable( 'persistent_variable', false );

        $parents = $node->attribute( 'path' );

        $path = array();
        $titlePath = array();
        foreach ( $parents as $parent )
        {
            $path[] = array( 'text' => $parent->attribute( 'name' ),
                             'url' => '/content/view/full/' . $parent->attribute( 'node_id' ),
                             'url_alias' => $parent->attribute( 'url_alias' ),
                             'node_id' => $parent->attribute( 'node_id' ) );
        }

        $titlePath = $path;
        $path[] = array( 'text' => $object->attribute( 'name' ),
                         'url' => false,
                         'url_alias' => false,
                         'node_id' => $node->attribute( 'node_id' ) );

        $titlePath[] = array( 'text' => $object->attribute( 'name' ),
                              'url' => false,
                              'url_alias' => false );

        $tpl->setVariable( 'node_path', $path );

        $Result = array();
        $Result['content']         = $tpl->fetch( 'design:node/view/' . $viewMode . '.tpl' );
        $Result['view_parameters'] = $viewParameters;
        $Result['path']            = $path;
        $Result['title_path']      = $titlePath;
        $Result['section_id']      = $object->attribute( 'section_id' );
        $Result['node_id']         = $node->attribute( 'node_id' );
        $Result['navigation_part'] = $navigationPartIdentifier;

        $contentInfoArray = array();
        $contentInfoArray['object_id']        = $object->attribute( 'id' );
        $contentInfoArray['node_id']          = $node->attribute( 'node_id' );
        $contentInfoArray['parent_node_id']   = $node->attribute( 'parent_node_id' );
        $contentInfoArray['class_id']         = $object->attribute( 'contentclass_id' );
        $contentInfoArray['class_identifier'] = $node->attribute( 'class_identifier' );
        $contentInfoArray['remote_id']        = $object->attribute( 'remote_id' );
        $contentInfoArray['node_remote_id']   = $node->attribute( 'remote_id' );
        $contentInfoArray['offset']           = $offset;
        $contentInfoArray['viewmode']         = $viewMode;
        $contentInfoArray['navigation_part_identifier'] = $navigationPartIdentifier;
        $contentInfoArray['node_depth']       = $node->attribute( 'depth' );
        $contentInfoArray['url_alias']        = $node->attribute( 'url_alias' );
        $contentInfoArray['current_language'] = $object->attribute( 'current_language' );
        $contentInfoArray['language_mask']    = $object->attribute( 'language_mask' );

        $contentInfoArray['main_node_id']   = $node->attribute( 'main_node_id' );
        $contentInfoArray['main_node_url_alias'] = false;
        // Add url alias for main node if it is not current node and user has access to it
        if ( !$node->isMain() )
        {
            $mainNode = $object->mainNode();
            if ( $mainNode->canRead() )
            {
                $contentInfoArray['main_node_url_alias'] = $mainNode->attribute( 'url_alias' );
            }
        }

        $contentInfoArray['persistent_variable'] = false;
        if ( $tpl->variable( 'persistent_variable' ) !== false )
        {
            $contentInfoArray['persistent_variable'] = $tpl->variable( 'persistent_variable' );
            $keyArray[] = array( 'persistent_variable', $contentInfoArray['persistent_variable'] );
            $res->setKeys( $keyArray );
        }
        $contentInfoArray['class_group']             = $object->attribute( 'match_ingroup_id_list' );
        $contentInfoArray['state']                   = $object->attribute( 'state_id_array' );
        $contentInfoArray['state_identifier']        = $object->attribute( 'state_identifier_array' );
        $contentInfoArray['parent_class_id']         = $parentClassID;
        $contentInfoArray['parent_class_identifier'] = $parentClassIdentifier;
        $contentInfoArray['parent_node_remote_id']   = $parentNodeRemoteID;
        $contentInfoArray['parent_object_remote_id'] = $parentObjectRemoteID;

        $Result['content_info'] = $contentInfoArray;

        // Store which templates were used to make this cache.
        $Result['template_list'] = $tpl->templateFetchList();

        // Check if time to live is set in template
        if ( $tpl->hasVariable( 'cache_ttl' ) )
        {
            $cacheTTL = $tpl->variable( 'cache_ttl' );
        }

        if ( !isset( $cacheTTL ) )
        {
            $cacheTTL = -1;
        }

        $Result['cache_ttl'] = $cacheTTL;

        // if cache_ttl is set to 0 from the template, we need to add a no-cache advice
        // to the node's data. That way, the retrieve callback on the next calls
        // will be able to determine earlier that no cache generation should be started
        // for this node
        if ( $cacheTTL == 0 )
        {
            $Result['no_cache'] = true;
        }

        if ( $languageCode )
        {
            $node->setCurrentLanguage( $oldLanguageCode );
        }

        return $Result;
    }

    static function generateViewCacheFile( $user, $nodeID, $offset, $layout, $language, $viewMode, $viewParameters = false, $cachedViewPreferences = false, $viewCacheTweak = '' )
    {
        $cacheNameExtra = '';
        $ini = eZINI::instance();

        if ( !$language )
        {
            $language = false;
        }

        if ( !$viewCacheTweak && $ini->hasVariable( 'ContentSettings', 'ViewCacheTweaks' ) )
        {
            $viewCacheTweaks = $ini->variable( 'ContentSettings', 'ViewCacheTweaks' );
            if ( isset( $viewCacheTweaks[$nodeID] ) )
            {
                $viewCacheTweak = $viewCacheTweaks[$nodeID];
            }
            else if ( isset( $viewCacheTweaks['global'] ) )
            {
                $viewCacheTweak = $viewCacheTweaks['global'];
            }
        }

        // should we use current siteaccess or let several siteaccesse share cache?
        if ( strpos( $viewCacheTweak, 'ignore_siteaccess_name' ) === false )
        {
            $currentSiteAccess = $GLOBALS['eZCurrentAccess']['name'];
        }
        else
        {
            $currentSiteAccess = $ini->variable( 'SiteSettings', 'DefaultAccess' );
        }

        $cacheHashArray = array( $nodeID,
                                 $viewMode,
                                 $language,
                                 $offset,
                                 $layout );

        // several user related cache tweaks
        if ( strpos( $viewCacheTweak, 'ignore_userroles' ) === false )
        {
            $cacheHashArray[] = implode( '.', $user->roleIDList() );
        }

        if ( strpos( $viewCacheTweak, 'ignore_userlimitedlist' ) === false )
        {
            $cacheHashArray[] = implode( '.', $user->limitValueList() );
        }

        if ( strpos( $viewCacheTweak, 'ignore_discountlist' ) === false )
        {
            $cacheHashArray[] = implode( '.', eZUserDiscountRule::fetchIDListByUserID( $user->attribute( 'contentobject_id' ) ) );
        }

        $cacheHashArray[] = eZSys::indexFile();

        // Add access type to cache hash if current access is uri type (so uri and host doesn't share cache)
        if ( strpos( $viewCacheTweak, 'ignore_siteaccess_type' ) === false && $GLOBALS['eZCurrentAccess']['type'] === eZSiteAccess::TYPE_URI )
        {
            $cacheHashArray[] = eZSiteAccess::TYPE_URI;
        }

        // Make the cache unique for every logged in user
        if ( strpos( $viewCacheTweak, 'pr_user' ) !== false and !$user->isAnonymous() )
        {
            $cacheNameExtra = $user->attribute( 'contentobject_id' ) . '-';
        }

        // Make the cache unique for every case of view parameters
        if ( strpos( $viewCacheTweak, 'ignore_viewparameters' ) === false && $viewParameters )
        {
            $vpString = '';
            ksort( $viewParameters );
            foreach ( $viewParameters as $key => $value )
            {
                if ( !$key )
                    continue;
                $vpString .= 'vp:' . $key . '=' . $value;
            }
            $cacheHashArray[] = $vpString;
        }

        // Make the cache unique for every case of the preferences
        if ( $cachedViewPreferences === false )
        {
            $depPreferences = $ini->variable( 'ContentSettings', 'CachedViewPreferences' );
        }
        else
        {
            $depPreferences = $cachedViewPreferences;
        }

        if ( strpos( $viewCacheTweak, 'ignore_userpreferences' ) === false && isset ( $depPreferences[$viewMode] ) )
        {
            $depPreferences = explode( ';', $depPreferences[$viewMode] );
            $pString = '';
            // Fetch preferences for the specified user
            $preferences = eZPreferences::values( $user );
            foreach( $depPreferences as $pref )
            {
                $pref = explode( '=', $pref );
                if ( isset( $pref[0] ) )
                {
                    if ( isset( $preferences[$pref[0]] ) )
                        $pString .= 'p:' . $pref[0] . '='. $preferences[$pref[0]]. ';';
                    else if ( isset( $pref[1] ) )
                        $pString .= 'p:' . $pref[0] . '='. $pref[1]. ';';
                }
            }
            $cacheHashArray[] = $pString;
        }

        $cacheFile = $nodeID . '-' . $cacheNameExtra . md5( implode( '-', $cacheHashArray ) ) . '.cache';
        $extraPath = eZDir::filenamePath( $nodeID );
        $cacheDir = eZDir::path( array( eZSys::cacheDirectory(), $ini->variable( 'ContentSettings', 'CacheDir' ), $currentSiteAccess, $extraPath ) );
        $cachePath = eZDir::path( array( $cacheDir, $cacheFile ) );

        return array( 'cache_path' => $cachePath,
                      'cache_dir' => $cacheDir,
                      'cache_file' => $cacheFile );
    }


    static function contentViewRetrieve( $file, $mtime, $args )
    {
        extract( $args );

        $cacheExpired = false;

        // Read Cache file
        if ( !eZContentObject::isCacheExpired( $mtime ) )
        {
//        $contents = $cacheFile->fetchContents();
            $contents = file_get_contents( $file );
            $Result = unserialize( $contents );

            // Check if a no_cache key has been set in the viewcache, and
            // return an eZClusterFileFailure if it has
            if ( isset( $Result['no_cache'] ) )
            {
                return new eZClusterFileFailure( 3, "Cache has been disabled for this node" );
            }

            // Check if cache has expired when cache_ttl is set
            $cacheTTL = isset( $Result['cache_ttl'] ) ? $Result['cache_ttl'] : -1;
            if ( $cacheTTL > 0 )
            {
                $expiryTime = $mtime + $cacheTTL;
                if ( time() > $expiryTime )
                {
                    $cacheExpired = true;
                    $expiryReason = 'Content cache is expired by cache_ttl=' . $cacheTTL;
                }
            }

            // Check if template source files are newer, but only if the cache is not expired
            if ( !$cacheExpired )
            {
                $developmentModeEnabled = $ini->variable( 'TemplateSettings', 'DevelopmentMode' ) == 'enabled';
                // Only do filemtime checking when development mode is enabled.
                if ( $developmentModeEnabled &&
                     isset( $Result['template_list'] ) ) // And only if there is a list stored in the cache
                {
                    foreach ( $Result['template_list'] as $templateFile )
                    {
                        if ( !file_exists( $templateFile ) )
                        {
                            $cacheExpired = true;
                            $expiryReason = "Content cache is expired by template file '" . $templateFile . "', it does not exist anymore";
                            break;
                        }
                        else if ( filemtime( $templateFile ) > $mtime )
                        {
                            $cacheExpired = true;
                            $expiryReason = "Content cache is expired by template file '" . $templateFile . "'";
                            break;
                        }
                    }
                }
            }

            if ( !$cacheExpired )
            {
                $keyArray = array( array( 'object', $Result['content_info']['object_id'] ),
                                   array( 'node', $Result['content_info']['node_id'] ),
                                   array( 'parent_node', $Result['content_info']['parent_node_id'] ),
                                   array( 'parent_node_remote_id', $Result['content_info']['parent_node_remote_id'] ),
                                   array( 'parent_object_remote_id', $Result['content_info']['parent_object_remote_id'] ),
                                   array( 'class', $Result['content_info']['class_id'] ),
                                   array( 'view_offset', $Result['content_info']['offset'] ),
                                   array( 'navigation_part_identifier', $Result['content_info']['navigation_part_identifier'] ),
                                   array( 'viewmode', $Result['content_info']['viewmode'] ),
                                   array( 'depth', $Result['content_info']['node_depth'] ),
                                   array( 'remote_id', $Result['content_info']['remote_id'] ),
                                   array( 'node_remote_id', $Result['content_info']['node_remote_id'] ),
                                   array( 'url_alias', $Result['content_info']['url_alias'] ),
                                   array( 'persistent_variable', $Result['content_info']['persistent_variable'] ),
                                   array( 'class_group', $Result['content_info']['class_group'] ),
                                   array( 'parent_class_id', $Result['content_info']['parent_class_id'] ),
                                   array( 'parent_class_identifier', $Result['content_info']['parent_class_identifier'] ),
                                   array( 'state', $Result['content_info']['state'] ),
                                   array( 'state_identifier', $Result['content_info']['state_identifier'] ),
                                   array( 'section', $Result['section_id'] ) );

                if ( isset( $Result['content_info']['class_identifier'] ) )
                    $keyArray[] = array( 'class_identifier', $Result['content_info']['class_identifier'] );

                $res = eZTemplateDesignResource::instance();
                $res->setKeys( $keyArray );

                return $Result;
            }
        }
        else
        {
            $expiryReason = 'Content cache is expired by eZContentObject::isCacheExpired(' . $mtime . ")";
        }

        // Cache is expired so return specialized cluster object
        if ( !isset( $expiryReason ) )
            $expiryReason = 'Content cache is expired';
        return new eZClusterFileFailure( 1, $expiryReason );
    }

    static function contentViewGenerate( $file, $args )
    {
        extract( $args );
        $node = eZContentObjectTreeNode::fetch( $NodeID );

        if ( !is_object( $node ) )
        {
            if ( !eZDB::instance()->isConnected())
            {
                return  array( 'content' => $Module->handleError( eZError::KERNEL_NO_DB_CONNECTION, 'kernel' ),
                               'store'   => false );

            }
            else
            {
                return  array( 'content' => $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' ),
                               'store'   => false );
            }
        }

        $object = $node->attribute( 'object' );

        if ( !is_object( $object ) )
        {
            return  array( 'content' => $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' ),
                           'store'   => false );
        }

        if ( !$object instanceof eZContentObject )
        {
            return  array( 'content' => $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' ),
                           'store'   => false );
        }
        if ( $node === null )
        {
            return  array( 'content' => $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' ),
                           'store'   => false );
        }

        if ( $object === null )
        {
            return  array( 'content' => $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' ),
                           'store'   => false );
        }

        if ( $node->attribute( 'is_invisible' ) && !eZContentObjectTreeNode::showInvisibleNodes() )
        {
            return array( 'content' => $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' ),
                          'store'   => false );
        }

        if ( !$node->canRead() )
        {
            return array( 'content' => $Module->handleError( eZError::KERNEL_ACCESS_DENIED,
                                                             'kernel',
                                                             array( 'AccessList' => $node->checkAccess( 'read', false, false, true ) ) ),
                          'store'   => false );
        }

        $Result = eZNodeviewfunctions::generateNodeViewData( $tpl, $node, $object,
                                                              $LanguageCode, $ViewMode, $Offset,
                                                              $viewParameters, $collectionAttributes,
                                                              $validation );

        // 'store' depends on noCache: if $noCache is set, this means that retrieve
        // returned it, and the noCache fake cache file is already stored
        // and should not be stored again
        $retval = array( 'content' => $Result,
                         'scope'   => 'viewcache',
                         'store'   => !( isset( $noCache ) and $noCache ) );
        if ( $file !== false && $retval['store'] )
            $retval['binarydata'] = serialize( $Result );
        return $retval;
    }
}

?>
