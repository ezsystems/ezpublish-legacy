<?php
//
// Definition of eZSubtreeCache class
//
// Created on: <21-Mar-2005 16:53:41 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
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

/*! \file ezsubtreecache.php
*/

/*!
  \class eZSubtreeCache ezsubtreecache.php
  \brief The class eZSubtreeCache does

*/

include_once( 'lib/eztemplate/classes/eztemplatecachefunction.php' );

class eZSubtreeCache
{
    /*!
     Constructor
    */
    function eZSubtreeCache()
    {
    }

    /*!
     \static
     Removes caches which were created using 'cache-block' operator with 'subtree_expiry' parameter.
     \a $nodeList is an array of node's ids. It is used to determine caches to remove.
     if $nodeList is not an array or if $nodeList is empty all 'subtree_expiry' caches will be removed.
    */
    function cleanupByNodeIDs( &$nodeIDList )
    {
        if ( !is_array( $nodeIDList ) || count( $nodeIDList ) === 0 )
        {
            eZSubtreeCache::cleanupAll();
        }
        else
        {
            include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
            $nodeList = eZContentObjectTreeNode::fetch( $nodeIDList );
            if ( $nodeList )
            {
                if ( !is_array( $nodeList ) )
                    $nodeList = array( $nodeList );

                eZSubtreeCache::cleanup( $nodeList );
            }
        }
    }

    /*!
     \static
     Clears template block caches with 'subtree_ezpiry' parameter for nodes in the $nodeList.
     Note: if 'DelayedCacheBlockCleanup' setting is enabled then expiried caches will be renamed only
     (removing from disk should be made, for example, by cronjob).
    */
    function cleanup( &$nodeList )
    {
        if ( !is_array( $nodeList ) )
            return;

        $cacheDir = eZTemplateCacheFunction::templateBlockCacheDir();

        $keys = array_keys( $nodeList );
        foreach ( $keys as $key )
        {
            $node =& $nodeList[$key];
            $pathString = $node->attribute( 'path_string' );
            $pathString = trim( $pathString, '/' );
            $nodeListID = explode( '/', $pathString );

            foreach( $nodeListID as $nodeID )
            {
                $cachePath = $cacheDir . eZTemplateCacheFunction::subtreeCacheSubDirForNode( $nodeID );
                eZSubtreeCache::cleanupCacheDir( $cachePath );
            }
        }
    }

    /*!
     \static
     Removes all caches which were created using 'cache-block' operator with 'subtree_expiry' parameter.
    */
    function cleanupAll()
    {
        $subtreeCacheDir = eZTemplateCacheFunction::templateBlockCacheDir() . eZTemplateCacheFunction::subtreeCacheBaseSubDir();
        eZSubtreeCache::cleanupCacheDir( $subtreeCacheDir );
    }

    /*!
     \static
     If DelayedCacheBlockCleanup is enables just renames $cachDir, otherwise removes $cacheDir from disk.
    */
    function cleanupCacheDir( $cacheDir )
    {
        if ( file_exists( $cacheDir ) )
        {
            include_once( 'lib/ezutils/classes/ezini.php' );
            $ini =& eZINI::instance();
            if ( $ini->variable( 'TemplateSettings', 'DelayedCacheBlockCleanup' ) === 'enabled' )
            {
                // VS-DBFILE : FIXME: this will not work if clustering enabled
                eZSubtreeCache::renameDir( $cacheDir );
            }
            else
                eZSubtreeCache::removeExpiryCacheFromDisk( $cacheDir );
        }
    }

    /*!
     \static
     $dir is a path to the cache directory which should be renamed.
     $dir is relative to the root directiry of 'subtree' cache.
    */
    function renameDir( $dir )
    {
        // just rename. Actual removing will be performed by cronjob.

        if ( $dir )
        {
            // VS-DBFILE : FIXME: this will not work if clustering enabled

            include_once( 'lib/ezfile/classes/ezfile.php' );
            $expiryCacheDir = eZTemplateCacheFunction::expiryTemplateBlockCacheDir();

            $uniqid = md5( uniqid( 'ezpsubtreecache'. getmypid(), true ) );
            $expiryCacheDir .= '/' . $uniqid[0] . '/' . $uniqid[1] . '/' . $uniqid[2] . '/' . $uniqid;

            if ( !file_exists( $expiryCacheDir ) )
            {
                $ini =& eZINI::instance();
                $perm = octdec( $ini->variable( 'FileSettings', 'StorageDirPermissions' ) );
                eZDir::mkdir( $expiryCacheDir, $perm, true );
            }
            eZFile::rename( $dir, $expiryCacheDir );
        }
        else
        {
            eZDebug::writeWarning( "$dir should be a directory. Template-block caches for 'subtree_expiry' are not removed.", "eZSubtreeCache::renameDir" );
        }
    }

    /*!
     \static
    */
    function removeAllExpiryCacheFromDisk()
    {
        $expiryCachePath = eZTemplateCacheFunction::expiryTemplateBlockCacheDir();
        eZSubtreeCache::removeExpiryCacheFromDisk( $expiryCachePath );
    }

    /*!
     \static
     $expiryCachePath is a path to directory with cache that should be removed
    */
    function removeExpiryCacheFromDisk( $expiryCachePath )
    {
        require_once( 'kernel/classes/ezclusterfilehandler.php' );
        $fileHandler = eZClusterFileHandler::instance();
        $fileHandler->fileDelete( $expiryCachePath );
    }
}

?>
