<?php
//
// $Id$
//
// Definition of eZTranslationCache class
//
// Gunnstein Lye <gl@ez.no>
// Created on: <23-Jan-2003 10:19:26 gl>
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

/*! \file eztranslationcache.php
*/

/*!
  \class eZTranslationCache eztranslationcache.php
  \brief Cache handling for translations.

*/

require_once( 'lib/ezutils/classes/ezdebug.php' );

class eZTranslationCache
{
    const CODE_DATE = 1058863428;

    /*!
     \static
     \return the cache table which has cache keys and cache data.
    */
    static function cacheTable()
    {
        if ( !isset( $GLOBALS['eZTranslationCacheTable'] ) )
        {
            $GLOBALS['eZTranslationCacheTable'] = array();
        }
        return $GLOBALS['eZTranslationCacheTable'];
    }

    /*!
     \static
     \return the cache translation context which is stored with the cache key \a $contextName.
             Returns \c null if no cache data was found.
    */
    static function contextCache( $contextName )
    {
        $translationCache = eZTranslationCache::cacheTable();
        if ( isset( $translationCache[$contextName] ) )
        {
            return $translationCache[$contextName]['root'];
        }
        return null;
    }

    /*!
     \static
     Sets the translation context \a $context to be cached with the cache key $contextName.
     \note Trying to overwrite and existing cache key will give a warning and fail.
    */
    static function setContextCache( $contextName, $context )
    {
        if ( $context === null )
        {
            return;
        }
        if ( isset( $GLOBALS['eZTranslationCacheTable'][$contextName] ) )
        {
            eZDebug::writeWarning( "Translation cache for context '$contextName' already exists",
                                   'eZTranslationCache::setContextCache' );
        }
        else
        {
            $GLOBALS['eZTranslationCacheTable'][$contextName] = array();
        }
        $GLOBALS['eZTranslationCacheTable'][$contextName]['root'] = $context;
        $GLOBALS['eZTranslationCacheTable'][$contextName]['info'] = array( 'context' => $contextName );
    }

    /*!
     \static
     \return the cache directory for translation cache files.
    */
    static function cacheDirectory()
    {
        $cacheDirectory =& $GLOBALS['eZTranslationCacheDirectory'];
        if ( !isset( $cacheDirectory ) )
        {
            //include_once( 'lib/ezutils/classes/ezini.php' );
            $ini = eZINI::instance();
            $locale = $ini->variable( 'RegionalSettings', 'Locale' );

            $rootCacheDirectory = eZTranslationCache::rootCacheDirectory();
            $cacheDirectory = eZDir::path( array( $rootCacheDirectory, $locale ) );

        }
        return $cacheDirectory;
    }

    /*!
     \static
    */
    static function rootCacheDirectory()
    {
        //include_once( 'lib/ezfile/classes/ezdir.php' );
        //include_once( 'lib/ezutils/classes/ezsys.php' );

        $internalCharset = eZTextCodec::internalCharset();
        $rootName = 'root-' . md5( $internalCharset );
        $rootCacheDirectory = eZDir::path( array( eZSys::cacheDirectory(), 'translation', $rootName ) );

        return $rootCacheDirectory;
    }

    /*!
     \static
     \return true if the cache with the key \a $key can be restored.
             A cache file is found restorable when it exists and has a timestamp
             higher or equal to \a $timestamp.
    */
    static function canRestoreCache( $key, $timestamp )
    {
        $translationCache = eZTranslationCache::cacheTable();
        if ( isset( $translationCache[$key] ) )
        {
            return false;
        }
//         $internalCharset = eZTextCodec::internalCharset();
//         $cacheFileKey = "$key-$internalCharset";
        $cacheFileKey = $key;
        $cacheFileName = md5( $cacheFileKey ) . '.php';

        //include_once( 'lib/ezutils/classes/ezphpcreator.php' );

        $php = new eZPHPCreator( eZTranslationCache::cacheDirectory(), $cacheFileName );
        return $php->canRestore( $timestamp );
    }

    /*!
     \static
     Loads the cache with the key \a $key from a file and sets the result in the cache table.
     \return true if the cache was successfully restored.
    */
    static function restoreCache( $key )
    {
        $translationCache = eZTranslationCache::cacheTable();
        if ( isset( $translationCache[$key] ) )
        {
            eZDebug::writeWarning( "Translation cache for key '$key' already exist, cannot restore cache", 'eZTranslationCache::restoreCache' );
            return false;
        }
//         $internalCharset = eZTextCodec::internalCharset();
//         $cacheFileKey = "$key-$internalCharset";
        $cacheFileKey = $key;
        $cacheFileName = md5( $cacheFileKey ) . '.php';

        //include_once( 'lib/ezutils/classes/ezphpcreator.php' );

        $php = new eZPHPCreator( eZTranslationCache::cacheDirectory(), $cacheFileName );
        $variables = $php->restore( array( 'info' => 'TranslationInfo',
                                           'root' => 'TranslationRoot',
                                           'cache-date' => 'eZTranslationCacheCodeDate' ) );
        if ( $variables['cache-date'] != self::CODE_DATE )
            return false;
        eZTranslationCache::setContextCache( $key, $variables['root'] );
        return true;
    }

    /*!
     \static
     Stores the data of the cache with the key \a $key to a file.
     \return false if the cache does not exist.
    */
    static function storeCache( $key )
    {
        $translationCache = eZTranslationCache::cacheTable();
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

        //include_once( 'lib/ezutils/classes/ezphpcreator.php' );

        if ( file_exists( eZTranslationCache::cacheDirectory() ) )
        {
            eZDir::mkdir( eZTranslationCache::cacheDirectory(), eZDir::directoryPermission(), true );
        }
        $php = new eZPHPCreator( eZTranslationCache::cacheDirectory(), $cacheFileName );
        $php->addRawVariable( 'eZTranslationCacheCodeDate', self::CODE_DATE );
        $php->addSpace();
        $php->addRawVariable( 'CacheInfo', array( 'charset' => $internalCharset ) );
        $php->addRawVariable( 'TranslationInfo', $cache['info'] );
        $php->addSpace();
        $php->addRawVariable( 'TranslationRoot', $cache['root'] );
        $php->store();
    }

    /*!
     \static
     Reset values strored in $GLOABLS variable
    */
    static function resetGlobals()
    {
        unset( $GLOBALS['eZTranslationCacheDirectory'] );
        unset( $GLOBALS['eZTranslationCacheTable'] );
    }
}

?>
