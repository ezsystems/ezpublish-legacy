<?php
//
// $Id$
//
// Definition of eZTranslationCache class
//
// Gunnstein Lye <gl@ez.no>
// Created on: <23-Jan-2003 10:19:26 gl>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

/*! \file eztranslationcache.php
*/

/*!
  \class eZTranslationCache eztranslationcache.php
  \brief Cache handling for translations.

*/

include_once( 'lib/ezutils/classes/ezdebug.php' );

define( 'EZ_TRANSLATION_CACHE_CODE_DATE', 1058863428 );

class eZTranslationCache
{
    /*!
     \static
     \return the cache table which has cache keys and cache data.
    */
    function &cacheTable()
    {
        $translationCache =& $GLOBALS['eZTranslationCacheTable'];
        if ( !is_array( $translationCache ) )
            $translationCache = array();
        return $translationCache;
    }

    /*!
     \static
     \return the cache translation context which is stored with the cache key \a $contextName.
             Returns \c null if no cache data was found.
    */
    function contextCache( $contextName )
    {
        $translationCache =& eZTranslationCache::cacheTable();
        $context = null;
        if ( isset( $translationCache[$contextName] ) )
        {
            $context =& $translationCache[$contextName]['root'];
//             eZDebug::writeDebug( "Cache hit for context '$contextName'",
//                                  'eZTranslationCache::contextCache' );
        }
//         else
//             eZDebug::writeDebug( "Cache miss for context '$contextName'",
//                                  'eZTranslationCache::contextCache' );
        return $context;
    }

    /*!
     \static
     Sets the translation context \a $context to be cached with the cache key $contextName.
     \note Trying to overwrite and existing cache key will give a warning and fail.
    */
    function setContextCache( $contextName, $context )
    {
        if ( $context === null )
            return;
        $translationCache =& eZTranslationCache::cacheTable();
        if ( isset( $translationCache[$contextName] ) )
        {
            eZDebug::writeWarning( "Translation cache for context '$contextName' already exists",
                                   'eZTranslationCache::setContextCache' );
        }
        else
        {
            $translationCache[$contextName] = array();
        }
        $translationCache[$contextName]['root'] =& $context;
        $translationCache[$contextName]['info'] = array( 'context' => $contextName );
    }

    /*!
     \static
     \return the cache directory for translation cache files.
    */
    function cacheDirectory()
    {
        $cacheDirectory =& $GLOBALS['eZTranslationCacheDirectory'];
        if ( !isset( $cacheDirectory ) )
        {
            include_once( 'lib/ezutils/classes/ezini.php' );
            include_once( 'lib/ezutils/classes/ezdir.php' );
            include_once( 'lib/ezutils/classes/ezsys.php' );

            $ini =& eZINI::instance();
            $locale = $ini->variable( 'RegionalSettings', 'Locale' );
            $internalCharset = eZTextCodec::internalCharset();
            $rootName = 'root-' . md5( $internalCharset );
            $cacheDirectory = eZDir::path( array( eZSys::cacheDirectory(), 'translation', $rootName, $locale ) );
        }
        return $cacheDirectory;
    }

    /*!
     \static
     \return true if the cache with the key \a $key can be restored.
             A cache file is found restorable when it exists and has a timestamp
             higher or equal to \a $timestamp.
    */
    function canRestoreCache( $key, $timestamp )
    {
        $translationCache =& eZTranslationCache::cacheTable();
        if ( isset( $translationCache[$key] ) )
        {
            return false;
        }
//         $internalCharset = eZTextCodec::internalCharset();
//         $cacheFileKey = "$key-$internalCharset";
        $cacheFileKey = $key;
        $cacheFileName = md5( $cacheFileKey ) . '.php';

        include_once( 'lib/ezutils/classes/ezphpcreator.php' );

        $php = new eZPHPCreator( eZTranslationCache::cacheDirectory(), $cacheFileName );
        return $php->canRestore( $timestamp );
    }

    /*!
     \static
     Loads the cache with the key \a $key from a file and sets the result in the cache table.
     \return true if the cache was successfully restored.
    */
    function restoreCache( $key )
    {
        $translationCache =& eZTranslationCache::cacheTable();
        if ( isset( $translationCache[$key] ) )
        {
            eZDebug::writeWarning( "Translation cache for key '$key' already exist, cannot restore cache", 'eZTranslationCache::restoreCache' );
            return false;
        }
//         $internalCharset = eZTextCodec::internalCharset();
//         $cacheFileKey = "$key-$internalCharset";
        $cacheFileKey = $key;
        $cacheFileName = md5( $cacheFileKey ) . '.php';

        include_once( 'lib/ezutils/classes/ezphpcreator.php' );

        $php = new eZPHPCreator( eZTranslationCache::cacheDirectory(), $cacheFileName );
        $variables =& $php->restore( array( 'info' => 'TranslationInfo',
                                            'root' => 'TranslationRoot',
                                            'cache-date' => 'eZTranslationCacheCodeDate' ) );
        if ( $variables['cache-date'] != EZ_TRANSLATION_CACHE_CODE_DATE )
            return false;
        $cache =& $translationCache[$key];
        $cache['root'] =& $variables['root'];
        $cache['info'] =& $variables['info'];
        return true;
    }

    /*!
     \static
     Stores the data of the cache with the key \a $key to a file.
     \return false if the cache does not exist.
    */
    function storeCache( $key )
    {
        $translationCache =& eZTranslationCache::cacheTable();
        if ( !isset( $translationCache[$key] ) )
        {
            eZDebug::writeWarning( "Translation cache for key '$key' does not exist, cannot store cache", 'eZTranslationCache::storeCache' );
            return;
        }
        $internalCharset = eZTextCodec::internalCharset();
//         $cacheFileKey = "$key-$internalCharset";
        $cacheFileKey = $key;
        $cacheFileName = md5( $cacheFileKey ) . '.php';

        $cache =& $translationCache[$key];

        include_once( 'lib/ezutils/classes/ezphpcreator.php' );

        if ( file_exists( eZTranslationCache::cacheDirectory() ) )
        {
            eZDir::mkdir( eZTranslationCache::cacheDirectory(), eZDir::directoryPermission(), true );
        }
        $php = new eZPHPCreator( eZTranslationCache::cacheDirectory(), $cacheFileName );
        $php->addVariable( 'eZTranslationCacheCodeDate', EZ_TRANSLATION_CACHE_CODE_DATE );
        $php->addSpace();
        $php->addVariable( 'CacheInfo', array( 'charset' => $internalCharset ) );
        $php->addVariable( 'TranslationInfo', $cache['info'] );
        $php->addSpace();
        $php->addVariable( 'TranslationRoot', $cache['root'] );
        $php->store();
    }
}

?>
