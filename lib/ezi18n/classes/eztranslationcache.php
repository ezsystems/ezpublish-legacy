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

define( 'EZ_TRANSLATION_CACHE_CODE_DATE', 1041857934 );

class eZTranslationCache
{
    /*!
     \static
     \return the cache table which has cache keys and cache data.
    */
    function &cacheTable( $root )
    {
        $translationCache =& $GLOBALS['eZTranslationCacheTable'][$root];
        if ( !is_array( $translationCache ) )
            $translationCache = array();
        return $translationCache;
    }

    /*!
     \static
     \return the cache translation context which is stored with the cache key \a $context_name.
             Returns \c null if no cache data was found.
    */
    function contextCache( $root, $context_name )
    {
        $translationCache =& eZTranslationCache::cacheTable( $root );
        $context = null;
        if ( isset( $translationCache[$context_name] ) )
        {
            $context =& $translationCache[$context_name]['root'];
//             eZDebug::writeDebug( "Cache hit for context '$context_name'",
//                                  'eZTranslationCache::contextCache' );
        }
//         else
//             eZDebug::writeDebug( "Cache miss for context '$context_name'",
//                                  'eZTranslationCache::contextCache' );
        return $context;
    }

    /*!
     \static
     Sets the translation context \a $context to be cached with the cache key $context_name.
     \note Trying to overwrite and existing cache key will give a warning and fail.
    */
    function setContextCache( $root, $context_name, $context )
    {
        if ( $context === null )
            return;
        $translationCache =& eZTranslationCache::cacheTable( $root );
        if ( isset( $translationCache[$context_name] ) )
        {
            eZDebug::writeWarning( "Translation cache for context '$context_name' already exists",
                                   'eZTranslationCache::setContextCache' );
        }
        else
        {
            $translationCache[$context_name] = array();
        }
        $translationCache[$context_name]['root'] =& $context;
        $translationCache[$context_name]['info'] = array( 'context' => $context_name );
    }

    /*!
     \static
     \return the cache directory for translation cache files.
    */
    function cacheDirectory( $root )
    {
        $cacheDirectory =& $GLOBALS['eZTranslationCacheDirectory'][$root];
        if ( !isset( $cacheDirectory ) )
        {
            include_once( 'lib/ezutils/classes/ezini.php' );
            include_once( 'lib/ezutils/classes/ezdir.php' );
            include_once( 'lib/ezutils/classes/ezsys.php' );

            $ini =& eZINI::instance();
            $locale = $ini->variable( 'RegionalSettings', 'Locale' );
            $rootName = 'root-' . md5( $root );
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
    function canRestoreCache( $root, $key, $timestamp )
    {
        $translationCache =& eZTranslationCache::cacheTable( $root );
        if ( isset( $translationCache[$key] ) )
        {
            return false;
        }
        $internalCharset = eZTextCodec::internalCharset();
        $cacheFileKey = "$key-$internalCharset";
        $cacheFileName = md5( $cacheFileKey ) . '.php';

        include_once( 'lib/ezutils/classes/ezphpcreator.php' );

        $php = new eZPHPCreator( eZTranslationCache::cacheDirectory( $root ), $cacheFileName );
        return $php->canRestore( $timestamp );
    }

    /*!
     \static
     Loads the cache with the key \a $key from a file and sets the result in the cache table.
     \return true if the cache was successfully restored.
    */
    function restoreCache( $root, $key )
    {
        $translationCache =& eZTranslationCache::cacheTable( $root );
        if ( isset( $translationCache[$key] ) )
        {
            eZDebug::writeWarning( "Translation cache for key '$key' already exist, cannot restore cache", 'eZTranslationCache::restoreCache' );
            return false;
        }
        $internalCharset = eZTextCodec::internalCharset();
        $cacheFileKey = "$key-$internalCharset";
        $cacheFileName = md5( $cacheFileKey ) . '.php';

        include_once( 'lib/ezutils/classes/ezphpcreator.php' );

        $php = new eZPHPCreator( eZTranslationCache::cacheDirectory( $root ), $cacheFileName );
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
    function storeCache( $root, $key )
    {
        $translationCache =& eZTranslationCache::cacheTable( $root );
        if ( !isset( $translationCache[$key] ) )
        {
            eZDebug::writeWarning( "Translation cache for key '$key' does not exist, cannot store cache", 'eZTranslationCache::storeCache' );
            return;
        }
        $internalCharset = eZTextCodec::internalCharset();
        $cacheFileKey = "$key-$internalCharset";
        $cacheFileName = md5( $cacheFileKey ) . '.php';

        $cache =& $translationCache[$key];

        include_once( 'lib/ezutils/classes/ezphpcreator.php' );

        $php = new eZPHPCreator( eZTranslationCache::cacheDirectory( $root ), $cacheFileName );
        $php->addVariable( 'eZTranslationCacheCodeDate', EZ_TRANSLATION_CACHE_CODE_DATE );
        $php->addSpace();
        $php->addVariable( 'TranslationInfo', $cache['info'] );
        $php->addSpace();
        $php->addVariable( 'TranslationRoot', $cache['root'] );
        $php->store();
    }
}

?>
