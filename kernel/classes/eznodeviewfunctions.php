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

/*!
  \class eZNodeviewfunctions eznodeviewfunctions.php
  \brief The class eZNodeviewfunctions does

*/

class eZNodeviewfunctions
{
    function &generateNodeView( $tpl, $node, $object, $languageCode, $viewMode, $offset,
                                $cacheDir, $cachePath, $viewCacheEnabled,
                                $viewParameters = array( 'offset' => 0, 'year' => false, 'month' => false, 'day' => false ),
                                $collectionAttributes = false, $validation = false )
    {
        include_once( 'kernel/classes/ezsection.php' );
        eZSection::setGlobalID( $object->attribute( 'section_id' ) );

        $section =& eZSection::fetch( $object->attribute( 'section_id' ) );
        if ( $section )
            $navigationPartIdentifier = $section->attribute( 'navigation_part_identifier' );

        $class =& $object->attribute( 'content_class' );

        $res =& eZTemplateDesignResource::instance();
        $res->setKeys( array( array( 'object', $object->attribute( 'id' ) ),
                              array( 'node', $node->attribute( 'node_id' ) ),
                              array( 'parent_node', $node->attribute( 'parent_node_id' ) ),
                              array( 'class', $object->attribute( 'contentclass_id' ) ),
                              array( 'class_identifier', $class->attribute( 'identifier' ) ),
                              array( 'view_offset', $offset ),
                              array( 'viewmode', $viewMode ),
                              array( 'navigation_part_identifier', $navigationPartIdentifier ),
                              array( 'depth', $node->attribute( 'depth' ) ),
                              array( 'url_alias', $node->attribute( 'url_alias' ) )
                              ) );

        $tpl->setVariable( 'node', $node );
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
        $Result['content_info'] = array( 'object_id' => $object->attribute( 'id' ),
                                         'node_id' => $node->attribute( 'node_id' ),
                                         'parent_node_id' => $node->attribute( 'parent_node_id' ),
                                         'class_id' => $object->attribute( 'contentclass_id' ),
                                         'class_identifier' => $class->attribute( 'identifier' ),
                                         'offset' => $offset,
                                         'viewmode' => $viewMode,
                                         'navigation_part_identifier' => $navigationPartIdentifier,
                                         'node_depth' => $node->attribute( 'depth' ),
                                         'url_alias' => $node->attribute( 'url_alias' ) );


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

    function generateViewCacheFile( $user, $nodeID, $offset, $layout, $language, $viewMode )
    {
        include_once( 'kernel/classes/ezuserdiscountrule.php' );

        $roleList = $user->roleIDList();
        $discountList =& eZUserDiscountRule::fetchIDListByUserID( $user->attribute( 'contentobject_id' ) );

        if ( $language == '' )
            $language = eZContentObject::defaultLanguage();

        $designSetting = eZTemplateDesignResource::designSetting( 'site' );
        $cacheHashArray = array( $nodeID, $offset, $layout, implode( '.', $roleList ), implode( '.', $discountList ) );

        // Make the cache unique for every case of view parameters
        if ( isset( $viewParameters ) )
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
