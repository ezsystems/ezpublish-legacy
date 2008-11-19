<?php
//
// Definition of eZTSTranslator class
//
// Created on: <07-Jun-2002 12:40:42 amos>
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

/*! \file eztstranslator.php
*/

/*!
  \class eZTSTranslator eztstranslator.php
  \ingroup eZTranslation
  \brief This provides internationalization using XML (.ts) files

*/

class eZTSTranslator extends eZTranslatorHandler
{
    /*!
     Construct the translator and loads the translation file $file if it is set and exists.
    */
    function eZTSTranslator( $locale, $filename = null, $useCache = true )
    {
        $this->UseCache = $useCache;
        if ( isset( $GLOBALS['eZSiteBasics'] ) )
        {
            $siteBasics = $GLOBALS['eZSiteBasics'];
            if ( isset( $siteBasics['no-cache-adviced'] ) && $siteBasics['no-cache-adviced'] )
                $this->UseCache = false;
        }
        $this->BuildCache = false;
        $this->eZTranslatorHandler( true );

        $this->Locale = $locale;
        $this->File = $filename;
        $this->Messages = array();
        $this->CachedMessages = array();
        $this->HasRestoredCache = false;
        $this->RootCache = false;
    }

    /*!
     \static
     Initialize the ts translator and context if this is not already done.
    */
    static function initialize( $context, $locale, $filename, $useCache = true )
    {
        $instance = false;
        $file = $locale . '/' . $filename;
        if ( !empty( $GLOBALS['eZTSTranslationTables'][$file] ) )
        {
            $instance = $GLOBALS['eZTSTranslationTables'][$file];
            if ( $instance->hasInitializedContext( $context ) )
            {
                return $instance;
            }
        }

        eZDebug::createAccumulatorGroup( 'tstranslator', 'TS translator' );
        eZDebug::accumulatorStart( 'tstranslator_init', 'tstranslator', 'TS init' );
        if ( !$instance )
        {
            $instance = new eZTSTranslator( $locale, $filename, $useCache );
            $GLOBALS['eZTSTranslationTables'][$file] = $instance;
            $manager = eZTranslatorManager::instance();
            $manager->registerHandler( $instance );
        }
        $instance->load( $context );
        eZDebug::accumulatorStop( 'tstranslator_init' );
        return $instance;
    }

    /*!
     \return true if the context \a $context is already initialized.
    */
    function hasInitializedContext( $context )
    {
        return isset( $this->CachedMessages[$context] );
    }

    /*!
     Tries to load the context \a $requestedContext for the translation and returns true if was successful.
    */
    function load( $requestedContext )
    {
        return $this->loadTranslationFile( $this->Locale, $this->File, $requestedContext );
    }

    /*!
     \private
    */
    function loadTranslationFile( $locale, $filename, $requestedContext )
    {
        // First try for current charset
        $charset = eZTextCodec::internalCharset();
        $tsTimeStamp = false;
        $ini = eZINI::instance();
        $checkMTime = $ini->variable( 'RegionalSettings', 'TranslationCheckMTime' ) === 'enabled';

        if ( !$this->RootCache )
        {
            $roots = array( $ini->variable( 'RegionalSettings', 'TranslationRepository' ) );
            $extensionBase = eZExtension::baseDirectory();
            $translationExtensions = $ini->variable( 'RegionalSettings', 'TranslationExtensions' );
            foreach ( $translationExtensions as $translationExtension )
            {
                $extensionPath = $extensionBase . '/' . $translationExtension . '/translations';
                if ( !$checkMTime || file_exists( $extensionPath ) )
                {
                    $roots[] = $extensionPath;
                }
            }
            $this->RootCache = array( 'roots' => $roots );
        }
        else
        {
            $roots = $this->RootCache['roots'];
            if ( isset( $this->RootCache['timestamp'] ) )
                $tsTimeStamp = $this->RootCache['timestamp'];
        }


        // Load cached translations if possible
        if ( $this->UseCache == true )
        {
            if ( !$tsTimeStamp && $checkMTime )
            {
                foreach ( $roots as $root )
                {
                    $path = eZDir::path( array( $root, $locale, $charset, $filename ) );
                    if ( file_exists( $path ) )
                    {
                        $timestamp = filemtime( $path );
                        if ( $timestamp > $tsTimeStamp )
                            $tsTimeStamp = $timestamp;
                    }
                    else
                    {
                        $path = eZDir::path( array( $root, $locale, $filename ) );
                        if ( file_exists( $path ) )
                        {
                            $timestamp = filemtime( $path );
                            if ( $timestamp > $tsTimeStamp )
                                $tsTimeStamp = $timestamp;
                        }
                    }
                }
                $this->RootCache['timestamp'] = $tsTimeStamp;
            }
            $key = 'cachecontexts';
            if ( $this->HasRestoredCache or
                 eZTranslationCache::canRestoreCache( $key, $tsTimeStamp ) )
            {
                eZDebug::accumulatorStart( 'tstranslator_cache_load', 'tstranslator', 'TS cache load' );
                if ( !$this->HasRestoredCache )
                {
                    if ( !eZTranslationCache::restoreCache( $key ) )
                    {
                        $this->BuildCache = true;
                    }
                    $contexts = eZTranslationCache::contextCache( $key );
                    if ( !is_array( $contexts ) )
                        $contexts = array();
                    $this->HasRestoredCache = $contexts;
                }
                else
                    $contexts = $this->HasRestoredCache;
                if ( !$this->BuildCache )
                {
                    $contextName = $requestedContext;
                    if ( !isset( $this->CachedMessages[$contextName] ) )
                    {
                        eZDebug::accumulatorStart( 'tstranslator_context_load', 'tstranslator', 'TS context load' );
                        if ( eZTranslationCache::canRestoreCache( $contextName, $tsTimeStamp ) )
                        {
                            if ( !eZTranslationCache::restoreCache( $contextName ) )
                            {
                                $this->BuildCache = true;
                            }
                            $this->CachedMessages[$contextName] =
                                 eZTranslationCache::contextCache( $contextName );

                            foreach ( $this->CachedMessages[$contextName] as $key => $msg )
                            {
                                $this->Messages[$key] = $msg;
                            }
                        }
                        eZDebug::accumulatorStop( 'tstranslator_context_load' );
                    }
                }
                eZDebugSetting::writeNotice( 'i18n-tstranslator', "Loading cached translation", "eZTSTranslator::loadTranslationFile" );
                eZDebug::accumulatorStop( 'tstranslator_cache_load' );
                if ( !$this->BuildCache )
                {
                    return true;
                }
            }
            eZDebugSetting::writeNotice( 'i18n-tstranslator',
                                         "Translation cache has expired. Will rebuild it from source.",
                                         "eZTSTranslator::loadTranslationFile" );
            $this->BuildCache = true;
        }

        $status = false;
        foreach ( $roots as $root )
        {
            if ( !file_exists( $root ) )
            {
                continue;
            }
            $path = eZDir::path( array( $root, $locale, $charset, $filename ) );
            if ( !file_exists( $path ) )
            {
                $path = eZDir::path( array( $root, $locale, $filename ) );

                $ini = eZINI::instance( "i18n.ini" );
                $fallbacks = $ini->variable( 'TranslationSettings', 'FallbackLanguages' );

                if ( array_key_exists( $locale,  $fallbacks ) and $fallbacks[$locale] )
                {
                    $fallbackpath = eZDir::path( array( $root, $fallbacks[$locale], $filename ) );
                    if ( !file_exists( $path ) and file_exists( $fallbackpath ) )
                        $path = $fallbackpath;
                }

                if ( !file_exists( $path ) )
                {
                    eZDebug::writeError( "Could not load translation file: $path", "eZTSTranslator::loadTranslationFile" );
                    continue;
                }
            }

            eZDebug::accumulatorStart( 'tstranslator_load', 'tstranslator', 'TS load' );

            $doc = new DOMDocument( '1.0', 'utf-8' );
            $success = $doc->load( $path );

            if ( !$success )
            {
                eZDebug::writeWarning( "Unable to load XML from file $path", 'eZTSTranslator::loadTranslationFile' );
                continue;
            }

            if ( !$this->validateDOMTree( $doc ) )
            {
                eZDebug::writeWarning( "XML text for file $path did not validate", 'eZTSTranslator::loadTranslationFile' );
                continue;
            }

            $status = true;

            $treeRoot = $doc->documentElement;
            $children = $treeRoot->childNodes;
            for ($i = 0; $i < $children->length; $i++ )
            {
                $child = $children->item( $i );

                if ( $child->nodeType == XML_ELEMENT_NODE )
                {
                    if ( $child->tagName == "context" )
                    {
                        $this->handleContextNode( $child );
                    }
                }
            }
            eZDebug::accumulatorStop( 'tstranslator_load' );
        }

        // Save translation cache
        if ( $this->UseCache == true && $this->BuildCache == true )
        {
            eZDebug::accumulatorStart( 'tstranslator_store_cache', 'tstranslator', 'TS store cache' );
            if ( eZTranslationCache::contextCache( 'cachecontexts' ) == null )
            {
                $contexts = array_keys( $this->CachedMessages );
                eZTranslationCache::setContextCache( 'cachecontexts',
                                                     $contexts );
                eZTranslationCache::storeCache( 'cachecontexts' );
                $this->HasRestoredCache = $contexts;
            }

            foreach ( $this->CachedMessages as $contextName => $context )
            {
                if ( eZTranslationCache::contextCache( $contextName ) == null )
                    eZTranslationCache::setContextCache( $contextName, $context );
                eZTranslationCache::storeCache( $contextName );
            }
            $this->BuildCache = false;
            eZDebug::accumulatorStop( 'tstranslator_store_cache' );
        }

        return $status;
    }

    /*!
     \static
     Validates the DOM tree \a $tree and returns true if it is correct.
    */
    static function validateDOMTree( $tree )
    {
        if ( !is_object( $tree ) )
            return false;

        $isValid = $tree->RelaxNGValidate( 'schemas/translation/ts.rng' );

        return $isValid;
    }

    function handleContextNode( $context )
    {
        $contextName = null;
        $messages = array();
        $context_children = $context->childNodes;

        for( $i = 0; $i < $context_children->length; $i++ )
        {
            $context_child = $context_children->item( $i );
            if ( $context_child->nodeType == XML_ELEMENT_NODE )
            {
                if ( $context_child->tagName == "name" )
                {
                    $name_el = $context_child->firstChild;
                    if ( $name_el )
                    {
                        $contextName = $name_el->nodeValue;
                    }
                }
                break;
            }
        }
        if ( !$contextName )
        {
            eZDebug::writeError( "No context name found, skipping context",
                                 "eZTSTranslator::handleContextNode" );
            return false;
        }
        foreach( $context_children as $context_child )
        {
            if ( $context_child->nodeType == XML_ELEMENT_NODE )
            {
                $childName = $context_child->tagName;
                if ( $childName == "message" )
                {
                    $this->handleMessageNode( $contextName, $context_child );
                }
                else if ( $childName == "name" )
                {
                    /* Skip name tags */
                }
                else
                {
                    eZDebug::writeError( "Unknown element name: $childName",
                                         "eZTSTranslator::handleContextNode" );
                }
            }
        }
        if ( $contextName === null )
        {
            eZDebug::writeError( "No context name found, skipping context",
                                 "eZTSTranslator::handleContextNode" );
            return false;
        }
        if ( !isset( $this->CachedMessages[$contextName] ) )
            $this->CachedMessages[$contextName] = array();

        return true;
    }

    function handleMessageNode( $contextName, $message )
    {
        $source = null;
        $translation = null;
        $comment = null;
        $message_children = $message->childNodes;
        for( $i = 0; $i < $message_children->length; $i++ )
        {
            $message_child = $message_children->item( $i );
            if ( $message_child->nodeType == XML_ELEMENT_NODE )
            {
                $childName = $message_child->tagName;
                if ( $childName  == "source" )
                {
                    $source_el = $message_child->firstChild;
                    $source = $source_el->nodeValue;
                }
                else if ( $childName == "translation" )
                {
                    $translation_el = $message_child->firstChild;
                    if ( $translation_el )
                    {
                        $translation = $translation_el->nodeValue;
                    }
                }
                else if ( $childName == "comment" )
                {
                    $comment_el = $message_child->firstChild;
                    $comment = $comment_el->nodeValue;
                }
                else if ( $childName == "location" )
                {
                    //Handle location element. No functionality yet.
                }
                else
                    eZDebug::writeError( "Unknown element name: " . $childName,
                                         "eZTSTranslator::handleMessageNode" );
            }
        }
        if ( $source === null )
        {
            eZDebug::writeError( "No source name found, skipping message",
                                 "eZTSTranslator::handleMessageNode" );
            return false;
        }
        if ( $translation === null )
        {
//             eZDebug::writeError( "No translation, skipping message", "eZTSTranslator::messageNode" );
            return false;
        }
        /* we need to convert ourselves if we're using libxml stuff here */
        if ( $message instanceof DOMElement )
        {
            $codec = eZTextCodec::instance( "utf8" );
            $source = $codec->convertString( $source );
            $translation = $codec->convertString( $translation );
            $comment = $codec->convertString( $comment );
        }

        $this->insert( $contextName, $source, $translation, $comment );
        return true;
    }

    /*!
     \reimp
    */
    function findKey( $key )
    {
        $msg = null;
        if ( isset( $this->Messages[$key] ) )
        {
            $msg = $this->Messages[$key];
        }
        return $msg;
    }

    /*!
     \reimp
    */
    function findMessage( $context, $source, $comment = null )
    {
        // First try with comment,
        $man = eZTranslatorManager::instance();
        $key = $man->createKey( $context, $source, $comment );

        if ( !isset( $this->Messages[$key] ) )
        {
            // then try without comment for general translation
            $key = $man->createKey( $context, $source );
        }

        return $this->findKey( $key );
    }

    /*!
     \reimp
    */
    function keyTranslate( $key )
    {
        $msg = $this->findKey( $key );
        if ( $msg !== null )
            return $msg["translation"];
        else
        {
            return null;
        }
    }

    /*!
     \reimp
    */
    function translate( $context, $source, $comment = null )
    {
        $msg = $this->findMessage( $context, $source, $comment );
        if ( $msg !== null )
        {
            return $msg["translation"];
        }

        return null;
    }

    /*!
     Inserts the \a $translation for the \a $context and \a $source as a translation message
     and returns the key for the message. If $comment is non-null it will be included in the message.

     If the translation message exists no new message is created and the existing key is returned.
    */
    function insert( $context, $source, $translation, $comment = null )
    {
//         eZDebug::writeDebug( "context=$context" );
        if ( $context == "" )
            $context = "default";
        $man = eZTranslatorManager::instance();
        $key = $man->createKey( $context, $source, $comment );
//         if ( isset( $this->Messages[$key] ) )
//             return $key;
        $msg = $man->createMessage( $context, $source, $comment, $translation );
        $msg["key"] = $key;
        $this->Messages[$key] = $msg;
        // Set array of messages to be cached
        if ( $this->UseCache == true && $this->BuildCache == true )
        {
            if ( !isset( $this->CachedMessages[$context] ) )
                $this->CachedMessages[$context] = array();
            $this->CachedMessages[$context][$key] = $msg;
        }
        return $key;
    }

    /*!
     Removes the translation message with \a $context and \a $source.
     Returns true if the message was removed, false otherwise.

     If you have the translation key use removeKey() instead.
    */
    function remove( $context, $source, $message = null )
    {
        if ( $context == "" )
            $context = "default";
        $man = eZTranslatorManager::instance();
        $key = $man->createKey( $context, $source, $message );
        if ( isset( $this->Messages[$key] ) )
            unset( $this->Messages[$key] );
    }

    /*!
     Removes the translation message with \a $key.
     Returns true if the message was removed, false otherwise.
    */
    function removeKey( $key )
    {
        if ( isset( $this->Messages[$key] ) )
            unset( $this->Messages[$key] );
    }

    /*!
     \static
     Fetche list of available translations, create eZTrnslator for each translations.
     \return list of eZTranslator objects representing available translations.
    */
    static function fetchList( $localeList = array() )
    {
        $ini = eZINI::instance();

        $dir = $ini->variable( 'RegionalSettings', 'TranslationRepository' );

        $fileInfoList = array();
        $translationList = array();
        $locale = '';

        if ( count( $localeList ) == 0 )
        {
            $localeList = eZDir::findSubdirs( $dir );
        }

        foreach( $localeList as $locale )
        {
            if ( $locale != 'untranslated' )
            {
                $translationFiles = eZDir::findSubitems( $dir . '/' . $locale, 'f' );

                foreach( $translationFiles as $translationFile )
                {
                    if ( eZFile::suffix( $translationFile ) == 'ts' )
                    {
                        $translationList[] = new eZTSTranslator( $locale,  $translationFile );
                    }
                }
            }
        }

        return $translationList;
    }

    /*!
     \static
    */
    static function resetGlobals()
    {
        unset( $GLOBALS["eZTSTranslationTables"] );
    }

    /// \privatesection
    /// Contains the hash table with message translations
    public $Messages;
    public $File;
    public $UseCache;
    public $BuildCache;
    public $CachedMessages;
}

?>
