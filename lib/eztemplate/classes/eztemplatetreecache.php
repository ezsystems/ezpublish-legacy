<?php
//
// Definition of eZTemplateTreeCache class
//
// Created on: <28-Nov-2002 07:44:29 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

/*! \file eztemplatetreecache.php
*/

/*!
  \class eZTemplateTreeCache eztemplatetreecache.php
  \brief Cache handling for template tree nodes.

*/

include_once( 'lib/ezutils/classes/ezdebug.php' );

define( 'EZ_TEMPLATE_TREE_CACHE_CODE_DATE', 1044440833 );

class eZTemplateTreeCache
{
    /*!
     \static
     \return the cache table which has cache keys and cache data.
    */
    function &cacheTable()
    {
        $templateCache =& $GLOBALS['eZTemplateTreeCacheTable'];
        if ( !is_array( $templateCache ) )
            $templateCache = array();
        return $templateCache;
    }

    /*!
     \private
     \static
     \return a new key from \a $key which has some additional info.
    */
    function internalKey( $key )
    {
        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance();
        $debug = $ini->variable( 'TemplateSettings', 'Debug' ) == 'enabled';
        if ( $debug )
            $key = $key . '-debug';
        else
            $key = $key . '-clean';
        return $key;
    }

    /*!
     \static
     \return the cache node tree which is stored with the cache key \a $key.
             Returns \c null if no cache data was found.
    */
    function cachedTree( $key, $uri, $res, $templatePath, &$extraParameters )
    {
        $templateCache =& eZTemplateTreeCache::cacheTable();
        $key = eZTemplateTreeCache::internalKey( $key );
        $root = null;
        if ( isset( $templateCache[$key] ) )
        {
            $root =& $templateCache[$key]['root'];
            eZDebugSetting::writeDebug( 'eztemplate-tree-cache', "Cache hit for uri '$uri' with key '$key'", 'eZTemplateTreeCache::cachedTree' );
        }
        else
            eZDebugSetting::writeDebug( 'eztemplate-tree-cache', "Cache miss for uri '$uri' with key '$key'", 'eZTemplateTreeCache::cachedTree' );

        return $root;
    }

    /*!
     Sets the template tree node \a $root to be cached with the cache key $root.
     \note Trying to overwrite and existing cache key will give a warning and fail.
    */
    function setCachedTree( $originalKey, $uri, $res, $templatePath, &$extraParameters, &$root )
    {
        if ( $root === null )
            return;
        $templateCache =& eZTemplateTreeCache::cacheTable();
        $key = eZTemplateTreeCache::internalKey( $originalKey );
        if ( isset( $templateCache[$key] ) )
        {
            eZDebug::writeWarning( "Template cache for key '$key', created from uri '$uri', already exists", 'eZTemplateTreeCache::setCachedTree' );
        }
        else
        {
            $templateCache[$key] = array();
        }
        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance();
        $debug = $ini->variable( 'TemplateSettings', 'Debug' ) == 'enabled';
        $templateCache[$key]['root'] =& $root;
        $templateCache[$key]['info'] = array( 'original_key' => $originalKey,
                                              'key' => $key,
                                              'uri' => $uri,
                                              'debug' => $debug,
                                              'resource' => $res,
                                              'template_path' => $templatePath,
                                              'resource_parameters' => $extraParameters );
    }

    /*!
     \static
     \return true if template tree node caching is enabled.
     \note To change this setting edit settings/site.ini and locate the group TemplateSettings and the entry NodeTreeCaching.
    */
    function isCacheEnabled()
    {
        if ( isset( $GLOBALS['eZSiteBasics'] ) )
        {
            $siteBasics = $GLOBALS['eZSiteBasics'];
            if ( isset( $siteBasics['no-cache-adviced'] ) and
                 $siteBasics['no-cache-adviced'] )
            {
                return false;
            }
        }

        include_once( 'lib/ezutils/classes/ezini.php' );
        $ini =& eZINI::instance();
        $cacheEnabled = $ini->variable( 'TemplateSettings', 'NodeTreeCaching' ) == 'enabled';
        return $cacheEnabled;
    }

    /*!
     \static
     \return the cache directory for tree node cache files.
    */
    function cacheDirectory()
    {
        $cacheDirectory =& $GLOBALS['eZTemplateTreeCacheDirectory'];
        if ( !isset( $cacheDirectory ) )
        {
            include_once( 'lib/ezfile/classes/ezdir.php' );
            include_once( 'lib/ezutils/classes/ezsys.php' );
            $cacheDirectory = eZDir::path( array( eZSys::cacheDirectory(), 'template/tree' ) );
        }
        return $cacheDirectory;
    }

    /*!
     Creates the name for the tree cache file and returns it.
     The name conists of the md5 of the key and charset with the original filename appended.
    */
    function treeCacheFilename( $key, $templateFilepath )
    {
        $internalCharset = eZTextCodec::internalCharset();
        $extraName = '';
        if ( preg_match( "#^.+/(.*)\.tpl$#", $templateFilepath, $matches ) )
            $extraName = '-' . $matches[1];
        else if ( preg_match( "#^(.*)\.tpl$#", $templateFilepath, $matches ) )
            $extraName = '-' . $matches[1];
        $cacheFileKey = "$key-$internalCharset";
        $cacheFileName = md5( $cacheFileKey ) . $extraName . '.php';
        return $cacheFileName;
    }

    /*!
     \static
     \return true if the cache with the key \a $key can be restored.
             A cache file is found restorable when it exists and has a timestamp
             higher or equal to \a $timestamp.
    */
    function canRestoreCache( $key, $timestamp, $templateFilepath )
    {
        if ( !eZTemplateTreeCache::isCacheEnabled() )
            return false;

        $templateCache =& eZTemplateTreeCache::cacheTable();
        $key = eZTemplateTreeCache::internalKey( $key );
        if ( isset( $templateCache[$key] ) )
        {
            return false;
        }
        $cacheFileName = eZTemplateTreeCache::treeCacheFilename( $key, $templateFilepath );

        include_once( 'lib/ezutils/classes/ezphpcreator.php' );

        $php = new eZPHPCreator( eZTemplateTreeCache::cacheDirectory(), $cacheFileName );
        return $php->canRestore( $timestamp );
    }

    /*!
     \static
     Loads the cache with the key \a $key from a file and sets the result in the cache table.
     \return true if the cache was successfully restored.
    */
    function restoreCache( $key, $templateFilepath )
    {
        if ( !eZTemplateTreeCache::isCacheEnabled() )
            return false;

        $templateCache =& eZTemplateTreeCache::cacheTable();
        $key = eZTemplateTreeCache::internalKey( $key );
        if ( isset( $templateCache[$key] ) )
        {
            eZDebug::writeWarning( "Template cache for key '$key' already exist, cannot restore cache", 'eZTemplateTreeCache::restoreCache' );
            return false;
        }
        $cacheFileName = eZTemplateTreeCache::treeCacheFilename( $key, $templateFilepath );

        include_once( 'lib/ezutils/classes/ezphpcreator.php' );

        $php = new eZPHPCreator( eZTemplateTreeCache::cacheDirectory(), $cacheFileName );
        $variables = $php->restore( array( 'info' => 'TemplateInfo',
                                           'root' => 'TemplateRoot',
                                           'cache-date' => 'eZTemplateTreeCacheCodeDate' ) );
        if ( $variables['cache-date'] != EZ_TEMPLATE_TREE_CACHE_CODE_DATE )
            return false;
        $cache =& $templateCache[$key];
        $cache['root'] =& $variables['root'];
        $cache['info'] =& $variables['info'];
        return true;
    }

    /*!
     \static
     Stores the data of the cache with the key \a $key to a file.
     \return false if the cache does not exist.
    */
    function storeCache( $key, $templateFilepath )
    {
        if ( !eZTemplateTreeCache::isCacheEnabled() )
            return false;
        $templateCache =& eZTemplateTreeCache::cacheTable();
        $key = eZTemplateTreeCache::internalKey( $key );
        if ( !isset( $templateCache[$key] ) )
        {
            eZDebug::writeWarning( "Template cache for key '$key' does not exist, cannot store cache", 'eZTemplateTreeCache::storeCache' );
            return;
        }
        $cacheFileName = eZTemplateTreeCache::treeCacheFilename( $key, $templateFilepath );

        $cache =& $templateCache[$key];

        include_once( 'lib/ezutils/classes/ezphpcreator.php' );

        $php = new eZPHPCreator( eZTemplateTreeCache::cacheDirectory(), $cacheFileName );
        $php->addVariable( 'eZTemplateTreeCacheCodeDate', EZ_TEMPLATE_TREE_CACHE_CODE_DATE );
        $php->addSpace();
        $php->addVariable( 'TemplateInfo', $cache['info'] );
        $php->addSpace();
        $php->addVariable( 'TemplateRoot', $cache['root'] );
        $php->store();
    }
}

?>
