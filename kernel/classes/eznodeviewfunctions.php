<?php
//
// Definition of eZNodeviewfunctions class
//
// Created on: <20-Apr-2004 11:57:36 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZNodeviewfunctions eznodeviewfunctions.php
  \brief The class eZNodeviewfunctions does

*/

class eZNodeviewfunctions
{
    function &generateNodeView( &$tpl, &$node, &$object, $languageCode, $viewMode, $offset,
                                $cacheDir, $cachePath, $viewCacheEnabled,
                                $viewParameters = array( 'offset' => 0, 'year' => false, 'month' => false, 'day' => false ),
                                $collectionAttributes = false, $validation = false )
    {
        include_once( 'kernel/classes/ezsection.php' );
        eZSection::setGlobalID( $object->attribute( 'section_id' ) );

        $section =& eZSection::fetch( $object->attribute( 'section_id' ) );
        if ( $section )
            $navigationPartIdentifier = $section->attribute( 'navigation_part_identifier' );
        else
            $navigationPartIdentifier = null;


        $contentINI =& eZINI::instance( 'content.ini' );
        $classGroupOverrideEnabled = ( $contentINI->variable( 'ContentOverrideSettings', 'EnableClassGroupOverride' ) == 'true' );

        $keyArray = array();
        $keyArray[] = array( 'object', $object->attribute( 'id' ) );
        $keyArray[] = array( 'node', $node->attribute( 'node_id' ) );
        $keyArray[] = array( 'parent_node', $node->attribute( 'parent_node_id' ) );
        $keyArray[] = array( 'class', $object->attribute( 'contentclass_id' ) );
        $keyArray[] = array( 'class_identifier', $node->attribute( 'class_identifier' ) );
        $keyArray[] = array( 'view_offset', $offset );
        $keyArray[] = array( 'viewmode', $viewMode );
        $keyArray[] = array( 'navigation_part_identifier', $navigationPartIdentifier );
        $keyArray[] = array( 'depth', $node->attribute( 'depth' ) );
        $keyArray[] = array( 'url_alias', $node->attribute( 'url_alias' ) );

        if ( $classGroupOverrideEnabled )
        {
            $class =& $object->attribute( 'content_class' );

            $keyArray[] = array( 'class_group', $class->attribute( 'ingroup_id_list' ) );
        }

        $res =& eZTemplateDesignResource::instance();
        $res->setKeys( $keyArray );

        $tpl->setVariable( 'node', $node );
        $tpl->setVariable( 'viewmode', $viewMode );
        $tpl->setVariable( 'language_code', $languageCode );
        $tpl->setVariable( 'view_parameters', $viewParameters );
        $tpl->setVariable( 'collection_attributes', $collectionAttributes );
        $tpl->setVariable( 'validation', $validation );

        $parents =& $node->attribute( 'path' );

        $path = array();
        $titlePath = array();
        foreach ( $parents as $parent )
        {
            $path[] = array( 'text' => $parent->attribute( 'name' ),
                             'url' => '/content/view/full/' . $parent->attribute( 'node_id' ),
                             'url_alias' => $parent->attribute( 'url_alias' ),
                             'node_id' => $parent->attribute( 'node_id' )
                             );
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

        if ( $classGroupOverrideEnabled )
        {
            $contentInfoArray['class_group'] = $class->attribute( 'ingroup_id_list' );
        }

        $Result['content_info'] = $contentInfoArray;

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
        }

        return $Result;
    }

    function generateViewCacheFile( $user, $nodeID, $offset, $layout, $language, $viewMode, $viewParameters = false )
    {
        include_once( 'kernel/classes/ezuserdiscountrule.php' );
        include_once( 'kernel/classes/ezpreferences.php' );

        $roleList = $user->roleIDList();
        $discountList =& eZUserDiscountRule::fetchIDListByUserID( $user->attribute( 'contentobject_id' ) );

        if ( $language == '' )
            $language = eZContentObject::defaultLanguage();

        $designSetting = eZTemplateDesignResource::designSetting( 'site' );
        $cacheHashArray = array( $nodeID, $offset, $layout, implode( '.', $roleList ), implode( '.', $discountList ) );

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
        $siteIni =& eZINI::instance( );
        $depPreferences = $siteIni->variable( 'ContentSettings', 'CachedViewPreferences' );
        if ( $depPreferences[$viewMode] )
        {
            $depPreferences = explode( ';', $depPreferences[$viewMode] );
            $pString = "";
            $preferences =& eZPreferences::values();
            if ( $preferences )
            {
                foreach( $depPreferences as $pref )
                {
                    $pref = explode( '=', $pref );
                    if ( $pref[0] )
                    {
                        if ( isset( $preferences[$pref[0]] ) )
                            $pString .= 'p:' . $pref[0] . '='. $preferences[$pref[0]]. ';';
                        else if ( $pref[1] )
                            $pString .= 'p:' . $pref[0] . '='. $pref[1]. ';';
                    }
                }
            }
            $cacheHashArray[] = $pString;
        }

        $ini =& eZINI::instance();

        $cacheFile = $nodeID . '-' . md5( implode( '-', $cacheHashArray ) ) . '.cache';
        $extraPath = eZDir::filenamePath( $nodeID );
        $cacheDir = eZDir::path( array( eZSys::cacheDirectory(), $ini->variable( 'ContentSettings', 'CacheDir' ), $designSetting, $viewMode, $language, $extraPath ) );
        $cachePath = eZDir::path( array( $cacheDir, $cacheFile ) );

        return array( 'cache_path' => $cachePath,
                      'cache_dir' => $cacheDir,
                      'cache_file' => $cacheFile );
    }

}

?>
