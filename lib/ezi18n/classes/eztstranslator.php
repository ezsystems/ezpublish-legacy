<?php
/**
 * File containing the eZTSTranslator class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 * @subpackage i18n
 */
/**
 * Provides internationalization using XML (.ts) files
 * @package lib
 * @subpackage i18n
 */
class eZTSTranslator extends eZTranslatorHandler
{
    /**
     * Constructs the translator and loads the translation file $file if it is set and exists.
     * @param string $locale
     * @param string $filename
     * @param bool $useCache
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

    /**
     * Initialize the ts translator and context if this is not already done
     *
     * @param string $context
     * @param string $locale
     * @param string $filename
     * @param bool $useCache
     * @return eZTSTranslator
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

    /**
     * Checks if a context has been initialized (cached)
     *
     * @param string $context
     * @return bool True if the context was initialized before, false if it wasn't
     */
    function hasInitializedContext( $context )
    {
        return isset( $this->CachedMessages[$context] );
    }

    /**
     * Tries to load the context $requestedContext for the translation and returns true if was successful.
     *
     * @param string $requestedContext
     * @return bool True if load was successful, false otherwise
     */
    function load( $requestedContext )
    {
        return $this->loadTranslationFile( $this->Locale, $this->File, $requestedContext );
    }

    /**
     * Loads a translation file
     * Will load from cache if possible, or generate cache if needed
     *
     * Also checks for translation files expiry based on mtime if RegionalSettings.TranslationCheckMTime is enabled
     *
     * @access private
     * @param string $locale
     * @param string $filename
     * @param string $requestedContext
     *
     * @return bool The operation status, true or false
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
            if ( !$tsTimeStamp )
            {
                $expiry = eZExpiryHandler::instance();
                $globalTsTimeStamp = $expiry->getTimestamp( self::EXPIRY_KEY, 0 );
                $localeTsTimeStamp = $expiry->getTimestamp( self::EXPIRY_KEY . '-' . $locale, 0 );
                $tsTimeStamp = max( $globalTsTimeStamp, $localeTsTimeStamp );
                if ( $checkMTime && $tsTimeStamp < time() )// no need if ts == time()
                {
                    // iterate over each known TS file, and get the highest timestamp
                    // this value will be used to check for cache validity
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
                eZDebugSetting::writeNotice( 'i18n-tstranslator', "Loading cached translation", __METHOD__ );
                eZDebug::accumulatorStop( 'tstranslator_cache_load' );
                if ( !$this->BuildCache )
                {
                    return true;
                }
            }
            eZDebugSetting::writeNotice( 'i18n-tstranslator',
                                         "Translation cache has expired. Will rebuild it from source.",
                                         __METHOD__ );
            $this->BuildCache = true;
        }

        $status = false;

        // first process country translation files
        // then process country variation translation files
        $localeParts = explode( '@', $locale );

        $triedPaths = array();
        $loadedPaths = array();

        $ini = eZINI::instance( "i18n.ini" );
        $fallbacks = $ini->variable( 'TranslationSettings', 'FallbackLanguages' );

        foreach ( $localeParts as $localePart )
        {
            $localeCodeToProcess = isset( $localeCodeToProcess ) ? $localeCodeToProcess . '@' . $localePart: $localePart;

            // array with alternative subdirs to check
            $alternatives = array(
                array( $localeCodeToProcess, $charset, $filename ),
                array( $localeCodeToProcess, $filename ),
            );

            if ( isset( $fallbacks[$localeCodeToProcess] ) && $fallbacks[$localeCodeToProcess] )
            {
                if ( $fallbacks[$localeCodeToProcess] === 'eng-GB' ) // Consider eng-GB fallback as "untranslated" since eng-GB does not provide any ts file
                {
                    $fallbacks[$localeCodeToProcess] = 'untranslated';
                }
                $alternatives[] = array( $fallbacks[$localeCodeToProcess], $charset, $filename );
                $alternatives[] = array( $fallbacks[$localeCodeToProcess], $filename );
            }

            foreach ( $roots as $root )
            {
                if ( !file_exists( $root ) )
                {
                    continue;
                }

                unset( $path );

                foreach ( $alternatives as $alternative )
                {
                    $pathParts = $alternative;
                    array_unshift( $pathParts, $root );
                    $pathToTry = eZDir::path( $pathParts );
                    $triedPaths[] = $pathToTry;

                    if ( file_exists( $pathToTry ) )
                    {
                        $path = $pathToTry;
                        break;
                    }
                }

                if ( !isset( $path ) )
                {
                    continue;
                }

                eZDebug::accumulatorStart( 'tstranslator_load', 'tstranslator', 'TS load' );

                $doc = new DOMDocument( '1.0', 'utf-8' );
                $success = $doc->load( $path );

                if ( !$success )
                {
                    eZDebug::writeWarning( "Unable to load XML from file $path", __METHOD__ );
                    continue;
                }

                if ( !$this->validateDOMTree( $doc ) )
                {
                    eZDebug::writeWarning( "XML text for file $path did not validate", __METHOD__ );
                    continue;
                }

                $loadedPaths[] = $path;

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
        }

        eZDebugSetting::writeDebug( 'i18n-tstranslator', implode( PHP_EOL, $triedPaths ), __METHOD__ . ': tried paths' );
        eZDebugSetting::writeDebug( 'i18n-tstranslator', implode( PHP_EOL, $loadedPaths ), __METHOD__ . ': loaded paths' );

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

    /**
     * Validates the DOM tree $tree and returns true if it is correct
     * @param DOMDocument $tree
     * @return bool True if the DOMDocument is valid, false otherwise
     */
    static function validateDOMTree( $tree )
    {
        if ( !is_object( $tree ) )
            return false;

        $isValid = $tree->RelaxNGValidate( 'schemas/translation/ts.rng' );

        return $isValid;
    }

    /**
     * Handles a DOM Context node and the messages it contains
     * @param DOMNode $context
     * @return bool
     */
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
            eZDebug::writeError( "No context name found, skipping context", __METHOD__ );
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
                    eZDebug::writeError( "Unknown element name: $childName", __METHOD__ );
                }
            }
        }
        if ( $contextName === null )
        {
            eZDebug::writeError( "No context name found, skipping context", __METHOD__ );
            return false;
        }
        if ( !isset( $this->CachedMessages[$contextName] ) )
            $this->CachedMessages[$contextName] = array();

        return true;
    }

    /**
     * Handles a translation message DOM node
     * @param string $contextName
     * @param DOMNode $message
     */
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
                    if ( $message_child->childNodes->length > 0 )
                    {
                        $source = '';
                        foreach ( $message_child->childNodes as $textEl )
                        {
                            if ( $textEl instanceof DOMText )
                            {
                                $source .= $textEl->nodeValue;
                            }
                            else if ( $textEl instanceof DOMElement && $textEl->tagName == 'byte' )
                            {
                                $source .= chr( intval( '0' . $textEl->getAttribute( 'value' ) ) );
                            }
                        }
                    }
                }
                else if ( $childName == "translation" )
                {
                    if ( $message_child->childNodes->length > 0 )
                    {
                        $translation = '';
                        foreach ( $message_child->childNodes as $textEl )
                        {
                            if ( $textEl instanceof DOMText )
                            {
                                $translation .= $textEl->nodeValue;
                            }
                            else if ( $textEl instanceof DOMElement && $textEl->tagName == 'byte' )
                            {
                                $translation .= chr( intval( '0' . $textEl->getAttribute( 'value' ) ) );
                            }
                        }
                    }
                }
                else if ( $childName == "comment" )
                {
                    $comment_el = $message_child->firstChild;
                    $comment = $comment_el->nodeValue;
                }
                else if ( $childName == "translatorcomment" )
                {
                    //Ignore it.
                }
                else if ( $childName == "location" )
                {
                    //Handle location element. No functionality yet.
                }
                else
                    eZDebug::writeError( "Unknown element name: " . $childName, __METHOD__ );
            }
        }
        if ( $source === null )
        {
            eZDebug::writeError( "No source name found, skipping message in context '{$contextName}'", __METHOD__ );
            return false;
        }
        if ( $translation === null ) // No translation provided, then take the source as a reference
        {
//             eZDebug::writeError( "No translation, skipping message", __METHOD__ );
            $translation = $source;
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

    /**
     * Returns the message that matches a translation md5 key
     * @param string $key
     * @return array|false The message, as an array, or false if not found
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

    /**
     * Returns the message that matches a context / source / comment
     * @param string $context
     * @param string $source
     * @param string $comment
     * @return array|false The message, as an array, or false if not found
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

    /**
     * Returns the translation for a translation md5 key
     * @param string $key
     * @return string|false
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

    /**
     * Translates a context + source + comment
     * @param string $context
     * @param string $source
     * @param string $comment
     * @return string|false
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

    /**
     * Inserts the translation $translation for the context $context and source $source as a translation message.
     * Returns the key for the message. If $comment is non-null it will be included in the message.
     *
     * If the translation message exists no new message is created and the existing key is returned.
     *
     * @param string $context
     * @param string $source
     * @param string $translation
     * @param string $comment
     *
     * @return string The translation (md5) key
    */
    function insert( $context, $source, $translation, $comment = null )
    {
        if ( $context == "" )
            $context = "default";
        $man = eZTranslatorManager::instance();
        $key = $man->createKey( $context, $source, $comment );
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

    /**
     * Removes the translation message with context $context and source $source.
     *
     * If you have the translation key use removeKey() instead.
     *
     * @param string $context
     * @param string $source
     * @param string $message
     *
     * @return bool true if the message was removed, false otherwise
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

    /**
     * Removes the translation message with the key $key.
     *
     * @param string $key The translation md5 key
     *
     * @return bool true if the message was removed, false otherwise
     */
    function removeKey( $key )
    {
        if ( isset( $this->Messages[$key] ) )
            unset( $this->Messages[$key] );
    }

    /**
     * Fetches the list of available translations, as an eZTSTranslator for each translation.
     *
     * @param array $localList
     *
     * @return array( eZTSTranslator ) list of eZTranslator objects representing available translations
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

    /**
     * Resets the in-memory translations table
     * @return void
     */
    static function resetGlobals()
    {
        unset( $GLOBALS['eZTSTranslationTables'] );
        unset( $GLOBALS['eZTranslationCacheTable'] );
    }

    /**
     * Expires the translation cache
     *
     * @param int $timestamp An optional timestamp cache should be exired from. Current timestamp used by default
     * @param string $locale Optional translation's locale to expire specifically. Expires global ts cache by default.
     *
     * @return void
     */
    public static function expireCache( $timestamp = false, $locale = null )
    {
        eZExpiryHandler::registerShutdownFunction();

        if ( $timestamp === false )
            $timestamp = time();

        $handler = eZExpiryHandler::instance();
        if ( $locale )
            $handler->setTimestamp( self::EXPIRY_KEY . '-' . $locale, $timestamp );
        else
            $handler->setTimestamp( self::EXPIRY_KEY, $timestamp );
        $handler->store();
        self::resetGlobals();
    }

    /**
     * Contains the hash table with message translations
     * @var array
     */
    public $Messages;
    public $File;
    public $UseCache;
    public $BuildCache;
    public $CachedMessages;

    /**
     * Translation expiry key used by eZExpiryHandler to manage translation caches
     */
    const EXPIRY_KEY = 'ts-translation-cache';
}

?>
