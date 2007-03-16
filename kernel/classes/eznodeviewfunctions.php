<?php
//
// Definition of eZNodeviewfunctions class
//
// Created on: <20-Apr-2004 11:57:36 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
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
  \class eZNodeviewfunctions eznodeviewfunctions.php
  \brief The class eZNodeviewfunctions does

*/

define( 'eZNodeViewFunctions_FileGenerateTimeout', 3 );

class eZNodeviewfunctions
{
    function &generateNodeView( &$tpl, &$node, &$object, $languageCode, $viewMode, $offset,
                                $cacheDir, $cachePath, $viewCacheEnabled,
                                $viewParameters = array( 'offset' => 0, 'year' => false, 'month' => false, 'day' => false ),
                                $collectionAttributes = false, $validation = false )
    {
        include_once( 'kernel/classes/ezsection.php' );
        eZSection::setGlobalID( $object->attribute( 'section_id' ) );

        $section = eZSection::fetch( $object->attribute( 'section_id' ) );
        if ( $section )
            $navigationPartIdentifier = $section->attribute( 'navigation_part_identifier' );
        else
            $navigationPartIdentifier = null;

        $keyArray = array( array( 'object', $object->attribute( 'id' ) ),
                           array( 'node', $node->attribute( 'node_id' ) ),
                           array( 'parent_node', $node->attribute( 'parent_node_id' ) ),
                           array( 'class', $object->attribute( 'contentclass_id' ) ),
                           array( 'class_identifier', $node->attribute( 'class_identifier' ) ),
                           array( 'view_offset', $offset ),
                           array( 'viewmode', $viewMode ),
                           array( 'navigation_part_identifier', $navigationPartIdentifier ),
                           array( 'depth', $node->attribute( 'depth' ) ),
                           array( 'url_alias', $node->attribute( 'url_alias' ) ),
                           array( 'class_group', $object->attribute( 'match_ingroup_id_list' ) ) );

        $parentClassID = false;
        $parentClassIdentifier = false;
        $parentNode = $node->attribute( 'parent' );
        if ( is_object( $parentNode ) )
        {
            $parentObject = $parentNode->attribute( 'object' );
            if ( is_object( $parentObject ) )
            {
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

        $res =& eZTemplateDesignResource::instance();
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

        $parents =& $node->attribute( 'path' );

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
        $Result['content'] =& $tpl->fetch( 'design:node/view/' . $viewMode . '.tpl' );
        $Result['view_parameters'] =& $viewParameters;
        $Result['path'] =& $path;
        $Result['title_path'] =& $titlePath;
        $Result['section_id'] =& $object->attribute( 'section_id' );
        $Result['node_id'] =& $node->attribute( 'node_id' );
        $Result['navigation_part'] = $navigationPartIdentifier;

        $contentInfoArray = array();
        $contentInfoArray['object_id'] = $object->attribute( 'id' );
        $contentInfoArray['node_id'] = $node->attribute( 'node_id' );
        $contentInfoArray['parent_node_id'] =  $node->attribute( 'parent_node_id' );
        $contentInfoArray['class_id'] = $object->attribute( 'contentclass_id' );
        $contentInfoArray['class_identifier'] = $node->attribute( 'class_identifier' );
        $contentInfoArray['offset'] = $offset;
        $contentInfoArray['viewmode'] = $viewMode;
        $contentInfoArray['navigation_part_identifier'] = $navigationPartIdentifier;
        $contentInfoArray['node_depth'] = $node->attribute( 'depth' );
        $contentInfoArray['url_alias'] = $node->attribute( 'url_alias' );
        $contentInfoArray['persistent_variable'] = false;
        if ( $tpl->variable( 'persistent_variable' ) !== false )
        {
            $contentInfoArray['persistent_variable'] = $tpl->variable( 'persistent_variable' );
        }
        $contentInfoArray['class_group'] = $object->attribute( 'match_ingroup_id_list' );
        $contentInfoArray['parent_class_id'] = $parentClassID;
        $contentInfoArray['parent_class_identifier'] = $parentClassIdentifier;

        $Result['content_info'] = $contentInfoArray;

        // Store which templates were used to make this cache.
        $Result['template_list'] = $tpl->templateFetchList();

        // Check if time to live is set in template
        if ( $tpl->hasVariable( 'cache_ttl' ) )
        {
            $cacheTTL =& $tpl->variable( 'cache_ttl' );
        }

        if ( !isset( $cacheTTL ) )
        {
            $cacheTTL = -1;
        }

        // Check if cache time = 0 (disabled)
        if ( $cacheTTL == 0 )
        {
            $viewCacheEnabled = false;
        }

        $Result['cache_ttl'] = $cacheTTL;

        // Store view cache
        if ( $viewCacheEnabled )
        {
            $serializeString = serialize( $Result );

            if ( !file_exists( $cacheDir ) )
            {
                include_once( 'lib/ezfile/classes/ezdir.php' );
                $ini =& eZINI::instance();
                $perm = octdec( $ini->variable( 'FileSettings', 'StorageDirPermissions' ) );
                eZDir::mkdir( $cacheDir, $perm, true );
            }
            $oldumask = umask( 0 );
            $pathExisted = file_exists( $cachePath );
            $ini =& eZINI::instance();
            $perm = octdec( $ini->variable( 'FileSettings', 'StorageFilePermissions' ) );
            $fp = @fopen( $cachePath, "w" );
            if ( !$fp )
                eZDebug::writeError( "Could not open file '$cachePath' for writing, perhaps wrong permissions" );
            if ( $fp and
                 !$pathExisted )
                chmod( $cachePath, $perm );
            umask( $oldumask );

            if ( $fp )
            {
                fwrite( $fp, $serializeString );
                fclose( $fp );
            }

            // VS-DBFILE

            require_once( 'kernel/classes/ezclusterfilehandler.php' );
            $fileHandler = eZClusterFileHandler::instance();
            $fileHandler->fileStore( $cachePath, 'viewcache', true );
        }

        if ( $languageCode )
        {
            $node->setCurrentLanguage( $oldLanguageCode );
        }

        return $Result;
    }

    function generateViewCacheFile( $user, $nodeID, $offset, $layout, $language, $viewMode, $viewParameters = false, $cachedViewPreferences = false )
    {
        include_once( 'kernel/classes/ezuserdiscountrule.php' );
        include_once( 'kernel/classes/ezpreferences.php' );

        $limitedAssignmentValueList = $user->limitValueList();
        $roleList = $user->roleIDList();
        $discountList = eZUserDiscountRule::fetchIDListByUserID( $user->attribute( 'contentobject_id' ) );

        if ( !$language )
        {
            $language = false;
        }
        $currentSiteAccess = $GLOBALS['eZCurrentAccess']['name'];

        $cacheHashArray = array( $nodeID,
                                 $viewMode,
                                 $language,
                                 $offset,
                                 $layout,
                                 implode( '.', $roleList ),
                                 implode( '.', $limitedAssignmentValueList),
                                 implode( '.', $discountList ),
                                 eZSys::indexFile() );

        // Make the cache unique for every case of view parameters
        if ( $viewParameters )
        {
            $vpString = "";
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
            $siteIni =& eZINI::instance( );
            $depPreferences = $siteIni->variable( 'ContentSettings', 'CachedViewPreferences' );
        }
        else
        {
            $depPreferences = $cachedViewPreferences;
        }
        if ( isset ( $depPreferences[$viewMode] ) )
        {
            $depPreferences = explode( ';', $depPreferences[$viewMode] );
            $pString = "";
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

        $ini =& eZINI::instance();

        $cacheFile = $nodeID . '-' . md5( implode( '-', $cacheHashArray ) ) . '.cache';
        $extraPath = eZDir::filenamePath( $nodeID );
        $cacheDir = eZDir::path( array( eZSys::cacheDirectory(), $ini->variable( 'ContentSettings', 'CacheDir' ), $currentSiteAccess, $extraPath ) );
        $cachePath = eZDir::path( array( $cacheDir, $cacheFile ) );

        return array( 'cache_path' => $cachePath,
                      'cache_dir' => $cacheDir,
                      'cache_file' => $cacheFile );
    }

}

?>
