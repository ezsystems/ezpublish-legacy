<?php
//
// Definition of eZURLWildcard class
//
// Created on: <08-Nov-2007 16:44:56 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

/*! \file
*/

/*!
  \class eZURLWildcard ezurlwildcard.php
  \brief Handles URL alias wildcards in eZ Publish

  \private
*/

class eZURLWildcard extends eZPersistentObject
{
    const REGEXP_ARRAY_CALLBACK = 'eZURLWilcardCachedReqexpArray';
    const CACHED_TRANSLATE = 'eZURLWilcardCachedTranslate';

    const WILDCARDS_PER_CACHE_FILE = 100;

    const TYPE_NONE = 0;
    const TYPE_FORWARD = 1;
    const TYPE_DIRECT = 2;

    const CACHE_SIGNATURE = 'urlalias-wildcard';

    /*!
     Initializes a new URL alias.
    */
    function eZURLWildcard( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        static $definition = array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "source_url" => array( 'name' => "SourceURL",
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ),
                                         "destination_url" => array( 'name' => "DestinationURL",
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ),
                                         "type" => array( 'name' => "Type",
                                                          'datatype' => 'integer',
                                                          'default' => '0',
                                                          'required' => true ) ),
                      "keys" => array( "id" ),
                      'function_attributes' => array(),
                      "increment_key" => "id",
                      "class_name" => "eZURLWildcard",
                      "name" => "ezurlwildcard" );
        return $definition;
    }

    /**
     * Converts the url wildcard object to an associative array with the attribute
     * names as array keys and the values as array values
     * @return array
     **/
    function asArray()
    {
        return array( 'id' => $this->attribute( 'id' ),
                      'source_url' => $this->attribute( 'source_url' ),
                      'destination_url' => $this->attribute( 'destination_url' ),
                      'type' => $this->attribute( 'type' ) );
    }

    /**
    * Stores the eZURLWildcard persistent object
    **/
    function store( $fieldFilters = null )
    {
        eZPersistentObject::store( $fieldFilters );
    }

    /**
     * Removes a wildcard from a source_url.
     * The URL should be provided without the /* prefix:
     * foobar will remove the wildcard with source_url = foobar/*
     * @param string $baseURL URL prefix matched against destination_url
     * @return void
     **/
    static function cleanup( $baseURL )
    {
        $db = eZDB::instance();
        $baseURLText = $db->escapeString( $baseURL . "/*" );
        $sql = "DELETE FROM ezurlwildcard
                WHERE source_url = '$baseURLText'";
        $db->query( $sql );
    }

    /**
    * Removes all the wildcards
    * @return void
    **/
    static function removeAll()
    {
        eZPersistentObject::removeObject( eZURLWildcard::definition() );
    }

    /**
     * Removes wildcards based on an ID list
     * @param array $idList array of numerical ID
     * @return void
     **/
    static function removeByIDs( $idList )
    {
        if ( !is_array( $idList ) )
            return;

        while ( count( $idList ) > 0 )
        {
            // remove by portion of 100 rows.
            $ids = array_splice( $idList, 0, 100 );

            $conditions = array( 'id' => array( $ids ) );

            eZPersistentObject::removeObject( eZURLWildcard::definition(),
                                              $conditions );
        }
    }

    /**
     * Fetch a wildcard by numerical ID
     * @param int $id
     * @param bool $asObject
     * @return eZURLWildcard null if no match was found
     **/
    static function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZURLWildcard::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
    }

    /**
     * Fetches a wildcard by source url
     * @param string $url Source URL
     * @param bool $asObject
     * @return eZURLWildcard Null if no match was found
     **/
    static function fetchBySourceURL( $url, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZURLWildcard::definition(),
                                                null,
                                                array( "source_url" => $url ),
                                                $asObject );
    }

    /**
     * Fetches the list of URL wildcards. By defaults, fetches all the wildcards
     * @param int $offset Offset to limit the list from
     * @param int $limit Limit to the number of fetched items
     * @param bool $asObject
     * @return array[eZURLWildcard]
     **/
    static function fetchList( $offset = false, $limit = false, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZURLWildcard::definition(),
                                                    null,
                                                    null,
                                                    null,
                                                    array( 'offset' => $offset, 'length' => $limit ),
                                                    $asObject );
    }

    /**
     * Returns the number of wildcards in the database without any filtering
     * @return int Number of wildcards in the database
     */
    static function fetchListCount()
    {
        $rows = eZPersistentObject::fetchObjectList( eZURLWildcard::definition(),
                                                     array(),
                                                     null,
                                                     false,
                                                     null,
                                                     false, false,
                                                     array( array( 'operation' => 'count( * )',
                                                                   'name' => 'count' ) ) );
        return $rows[0]['count'];
    }

    /*!
     \static
     \return array with information on the wildcard cache.

     The array containst the following keys
     - dir - The directory for the cache
     - file - The base filename for the caches
     - path - The entire path (including filename) for the cache
     - keys - Array with key values which is used to uniquely identify the cache
    */
    static function cacheInfo()
    {
        $cacheDir = eZSys::cacheDirectory();
        $ini = eZINI::instance();
        $keys = array( 'implementation' => $ini->variable( 'DatabaseSettings', 'DatabaseImplementation' ),
                       'server' => $ini->variable( 'DatabaseSettings', 'Server' ),
                       'database' => $ini->variable( 'DatabaseSettings', 'Database' ) );
        $wildcardKey = md5( implode( "\n", $keys ) );
        $wildcardCacheDir = "$cacheDir/wildcard";
        $wildcardCacheFile = "wildcard_$wildcardKey";
        $wildcardCachePath = "$wildcardCacheDir/$wildcardCacheFile";
        return array( 'dir' => $wildcardCacheDir,
                      'file' => $wildcardCacheFile,
                      'path' => $wildcardCachePath,
                      'keys' => $keys );
    }

    /**
     * Sets the various cache information to the parameters.
     * @private
     **/
    static function cacheInfoDirectories( &$wildcardCacheDir, &$wildcardCacheFile, &$wildcardCachePath, &$wildcardKeys )
    {
        $info = eZURLWildcard::cacheInfo();
        $wildcardCacheDir = $info['dir'];
        $wildcardCacheFile = $info['file'];
        $wildcardCachePath = $info['path'];
        $wildcardKeys = $info['keys'];
    }

    /**
     * Checks against a timestamp if the wildcard cache is expired
     *
     * @param int $timestamp The timestamp to check expiry against
     *
     * @return bool
     **/
    static function isCacheExpired( $timestamp )
    {
        $expired = false;

        $handler = eZExpiryHandler::instance();

        if ( $handler->hasTimestamp( eZURLWildcard::CACHE_SIGNATURE ) )
        {
            $expiryTime = $handler->timestamp( eZURLWildcard::CACHE_SIGNATURE );
            if ( $expiryTime >= $timestamp )
                $expired = true;
        }

        return $expired;
    }

    /**
     * Expires the wildcard cache. This causes the wildcard cache to be
     * regenerated on the next page load.
     * @return void
     **/
    static function expireCache()
    {
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( eZURLWildcard::CACHE_SIGNATURE, time() );
        $handler->store();
    }

    /**
     * Transforms the URI if there exists an alias for it.
     *
     * @param eZURI|string $uri
     * @return mixed The translated URI if the resource has moved, or true|false
     *               if translation was (un)successful
     **/
    static function translate( &$uri )
    {
        $result = false;

        // get uri string
        $uriString = ( $uri instanceof eZURI ) ? $uri->elements() : $uri;
        $uriString = eZURLAliasML::cleanURL( $uriString );

        eZDebugSetting::writeDebug( 'kernel-urltranslator', "input uriString: '$uriString'", 'eZURLWildcard::translate' );

        // setup helper callbacks
        $regexpArrayCallback = false;
        if ( !eZURLWildcard::setupMatchCallbacks( $regexpArrayCallback ) )
        {
            eZDebugSetting::writeDebug( 'kernel-urltranslator', "no match callbacks", 'eZURLWildcard::translate' );
            return false;
        }

        // fetch wildcards(actually regexps)
        $wildcards = $regexpArrayCallback();

        $ini = eZINI::instance();
        $iteration = $ini->variable( 'URLTranslator', 'MaximumWildcardIterations' );

        eZDebugSetting::writeDebug( 'kernel-urltranslator', "MaximumWildcardIterations: '$iteration'", 'eZURLWildcard::translate' );

        // translate
        $urlTranslated = false;
        while ( !$urlTranslated && $iteration >= 0 )
        {
            foreach ( $wildcards as $wildcardNum => $wildcard )
            {
                if ( preg_match( $wildcard, $uriString, $matches ) )
                {
                    eZDebugSetting::writeDebug( 'kernel-urltranslator', "matched with: '$wildcard'", 'eZURLWildcard::translate' );

                    // get new $uriString from wildcard
                    self::translateWithCache( $wildcardNum, $uriString, $wildcardInfo, $matches );

                    eZDebugSetting::writeDebug( 'kernel-urltranslator', "new uri string: '$uriString'", 'eZURLWildcard::translate' );

                    // optimization: don't try further translation if wildcard type is 'forward'
                    if ( $wildcardInfo['type'] == eZURLWildcard::TYPE_FORWARD )
                    {
                        $urlTranslated = true;
                        break;
                    }

                    // try to tranlsate
                    if ( $urlTranslated = eZURLAliasML::translate( $uriString ) )
                    {
                        // success
                        eZDebugSetting::writeDebug( 'kernel-urltranslator', "uri is translated to '$uriString' with result '$urlTranslated'", 'eZURLWildcard::translate' );
                        break;
                    }

                    eZDebugSetting::writeDebug( 'kernel-urltranslator', "uri is not translated, trying another wildcard", 'eZURLWildcard::translate' );

                    // translation failed. Try to match new $uriString with another wildcard.
                    --$iteration;
                    continue 2;
                }
            }

            // we here if non of the wildcards is matched
            break;
        }

        // check translation result
        // NOTE: 'eZURLAliasML::translate'(see above) can return 'true', 'false' or new url(in case of 'error/301').
        //       $urlTranslated can also be 'false' if no wildcard is matched.
        if ( $urlTranslated )
        {
            // check wildcard type and set appropriate $result and $uriString
            $wildcardType = $wildcardInfo['type'];

            eZDebugSetting::writeDebug( 'kernel-urltranslator', "wildcard type: $wildcardType", 'eZURLWildcard::translate' );

            switch ( $wildcardType )
            {
                case eZURLWildcard::TYPE_FORWARD:
                    {
                        // do redirect => set $result to untranslated uri
                        $result = $uriString;
                        $uriString = 'error/301';
                    }
                    break;

                default:
                    eZDebug::writeError( 'Invalid wildcard type.', 'eZURLWildcard::translate()' );
                    // no break, using 'EZ_URLALIAS_WILDCARD_TYPE_DIRECT' as fallback
                case eZURLWildcard::TYPE_DIRECT:
                    $result = $urlTranslated;
                    // $uriString already has correct value
                    break;
            }
        }
        else
        {
            // we are here if:
            // - input url is not matched with any wildcard;
            // - url is matched with wildcard and:
            //   - points to module
            //   - invalide url
            eZDebugSetting::writeDebug( 'kernel-urltranslator', "wildcard is not translated", 'eZURLWildcard::translate' );
            $result = false;
        }

        // set value back to $uri
        if ( $uri instanceof eZURI )
        {
            $uri->setURIString( $uriString, false );
        }
        else
        {
            $uri = $uriString;
        }

        eZDebugSetting::writeDebug( 'kernel-urltranslator', "finished with url '$uriString' and result '$result'", 'eZURLWildcard::translate' );

        return $result;
    }

    /**
     * Assign function names to input variables:
     * @param $regexpArrayCallback function to get an array of regexps
     * @return bool
     **/
    static private function setupMatchCallbacks( &$regexpArrayCallback )
    {
        if ( !eZURLWildcard::createCacheIfExpired() )
        {
            // mandatory cache is required in <= 3.9.x.
            // note: the appropriate $regexpArrayCallback can be implemented
            //       to support translation without cache.
            eZDebugSetting::writeDebug( 'kernel-urltranslator', "setting up callback for non cache mode", 'eZURLWildcard::setupMatchCallbacks' );
            return false;
        }
        else
        {
            eZDebugSetting::writeDebug( 'kernel-urltranslator', "setting up callback for cache mode", 'eZURLWildcard::setupMatchCallbacks' );

            eZURLWildcard::loadCacheIndex();

            if ( function_exists( eZURLWildcard::REGEXP_ARRAY_CALLBACK ) )
            {
                $regexpArrayCallback = eZURLWildcard::REGEXP_ARRAY_CALLBACK;
            }
            else
            {
                eZDebug::writeError( 'Broken wildcard cache index file.', 'eZURLWildcard::setupMatchCallbacks()' );
                return false;
            }
        }

        return true;
    }

    /**
     * Create the cache if expired
     * @return bool true
     **/
    static function createCacheIfExpired()
    {
        $cacheFile = eZURLWildcard::cacheIndexFile();

        $isExpired = true;
        if ( $cacheFile->exists() )
        {
            $timestamp = $cacheFile->mtime();
            $isExpired = eZURLWildcard::isCacheExpired( $timestamp );
        }

        if ( $isExpired )
        {
            eZURLWildcard::createCache();
        }

        return true;
    }

    /**
     * Create the wildcard cache
     *
     * The wildcard caches are splitted between several files:
     *   'wildcard_<md5>_index.php': contains regexps for wildcards
     *   'wildcard_<md5>_0.php',
     *   'wildcard_<md5>_1.php',
     *   ...
     *   'wildcard_<md5>_N.php': contains cached wildcards.
     * Each file has info about eZURLWildcard::WILDCARDS_PER_CACHE_FILE wildcards.
     * @return void
     **/
    static function createCache()
    {
        eZURLWildcard::cacheInfoDirectories( $wildcardCacheDir, $wildcardCacheFile, $wildcardCachePath, $wildcardKeys );
        if ( !file_exists( $wildcardCacheDir ) )
        {
            eZDir::mkdir( $wildcardCacheDir, false, true );
        }

        $phpCacheIndex = new eZPHPCreator( $wildcardCacheDir, $wildcardCacheFile . "_index.php", '', array( 'clustering' => 'wildcard-cache-index' ) );

        foreach ( $wildcardKeys as $wildcardKey => $wildcardKeyValue )
        {
            $phpCacheIndex->addComment( "$wildcardKey = $wildcardKeyValue" );
        }
        $phpCacheIndex->addSpace();

        $phpCodeIndex = "function " . eZURLWildcard::REGEXP_ARRAY_CALLBACK . "()\n{\n";

        $phpCodeIndex .= "    ";
        $phpCodeIndex .= "\$wildcards = array(\n";

        $limit = eZURLWildcard::WILDCARDS_PER_CACHE_FILE;
        $offset = 0;
        $cacheFilesCount = 0;
        $wildcardNum = 0;
        while( 1 )
        {
            $wildcards = eZURLWildcard::fetchList( $offset, $limit, false );
            if ( count( $wildcards ) === 0 )
            {
                break;
            }

            $phpCache = new eZPHPCreator( $wildcardCacheDir, $wildcardCacheFile . "_$cacheFilesCount.php", '', array( 'clustering' => 'wildcard-cache-' . $cacheFilesCount ) );

            foreach ( $wildcardKeys as $wildcardKey => $wildcardKeyValue )
            {
                $phpCache->addComment( "$wildcardKey = $wildcardKeyValue" );
            }
            $phpCache->addSpace();

            $phpCode = "function " . eZURLWildcard::CACHED_TRANSLATE . "( \$wildcardNum, &\$uri, &\$wildcardInfo, \$matches )\n{\n";

            $phpCode .= "    switch ( \$wildcardNum )\n";
            $phpCode .= "    {\n";

            foreach ( $wildcards as $wildcard )
            {
                $phpCodeIndex .= '        ';
                if ( $wildcardNum > 0 )
                {
                    $phpCodeIndex .= ", ";
                }
                $phpCodeIndex .= eZURLWildcard::matchRegexpCode( $wildcard ) . "\n";


                $phpCode .= "        case $wildcardNum:\n";
                $phpCode .= "            {\n";
                $phpCode .= eZURLWildcard::matchReplaceCode( $wildcard, '                ' );
                $phpCode .= "            } break;\n";

                ++$wildcardNum;
            }

            $phpCode .= "\n    };\n";
            $phpCode .= "}\n";

            $phpCache->addCodePiece( $phpCode );
            $phpCache->store( true );

            $offset += $limit;
            ++$cacheFilesCount;
        }

        $phpCodeIndex .= " );\n";
        $phpCodeIndex .= "    return \$wildcards;\n";
        $phpCodeIndex .= "}\n";

        $phpCacheIndex->addCodePiece( $phpCodeIndex );
        $phpCacheIndex->store( true );
    }

    /**
     * Creates a 'regexp' portion of php-code for cache-index.
     *
     * @param array $wildcard wildcard data with a source_url key
     *
     * @return string Regexp PHP code
     **/
    static private function matchRegexpCode( $wildcard )
    {
        $phpCode = "";

        $matchWilcard = $wildcard['source_url'];
        $matchWilcardList = explode( "*", $matchWilcard );
        $regexpList = array();
        foreach ( $matchWilcardList as $matchWilcardItem )
        {
            $regexpList[] = preg_quote( $matchWilcardItem, '#' );
        }
        $matchRegexp = implode( '(.*)', $regexpList );

        $phpCode = "\"#^$matchRegexp#\"";

        return $phpCode;
    }

    /**
     * Creates a 'replace' and wildcard-info portions of php-code for cache.
     *
     * @param array $wildcard Wildcard array with a destination_url key
     * @param string $indent
     *
     * @return string match/replace PHP Code
     **/
    static private function matchReplaceCode( $wildcard, $indent = '' )
    {
        $phpCode = "";

        $replaceWildcard = $wildcard['destination_url'];
        $replaceWildcardList = preg_split( "#{([0-9]+)}#", $replaceWildcard, false, PREG_SPLIT_DELIM_CAPTURE );
        $regexpList = array();
        $counter = 0;
        $replaceCode = "\$uri = ";
        foreach ( $replaceWildcardList as $replaceWildcardItem )
        {
            if ( $counter > 0 )
                $replaceCode .= " . ";
            if ( ( $counter % 2 ) == 0 )
            {
                if ( $replaceWildcardItem == "" )
                {
                    $replaceCode .= "\"\"";
                }
                else
                {
                    $replaceCode .= "\"$replaceWildcardItem\"";
                }
            }
            else
            {
                $replaceCode .= "\$matches[$replaceWildcardItem]";
            }
            ++$counter;
        }
        $replaceRegexp = implode( '', $regexpList );

        $phpCode .= $indent . "$replaceCode;\n";

        $wildcardCode = $indent . "\$wildcardInfo = array( ";

        $counter = 0;
        foreach ( $wildcard as $key => $value )
        {
            if ( $counter == 0 )
            {
                $wildcardCode .= "'$key' => '$value'";
            }
            else
            {
                $wildcardCode .= ",\n" . $indent . "                       '$key' => '$value'";
            }

            ++$counter;
        }

        $wildcardCode .= " );\n";

        $phpCode .= $wildcardCode;

        return $phpCode;
    }

    /**
     * Returns the cluster handler for the cache index file
     *
     * @return eZClusterFileHandlerInterface The cluster handler instance for the index file
     **/
    static function cacheIndexFile()
    {
        $info = eZURLWildcard::cacheInfo();

        $cacheFile = eZClusterFileHandler::instance( $info['path'] . "_index.php" );

        return $cacheFile;
    }

    /**
     * Loads the cache-index in memory
     * @return bool true if successful, false otherwise
     */
    static function loadCacheIndex()
    {
        $info = eZURLWildcard::cacheInfo();

        $cacheFile = eZClusterFileHandler::instance( $info['path'] . "_index.php" );

        if ( !$cacheFile->exists() )
            return false;

        $fetchedFilePath = $cacheFile->fetchUnique();
        include_once( $fetchedFilePath );
        $cacheFile->fileDeleteLocal( $fetchedFilePath );

        return true;
    }

    /**
     * The callback loads appropriate cache file for wildcard \a $wildcardNum,
     * extracts wildcard info and 'replace' url from cache.
     *
     * The wildcard number(not a wildcard id) is used here.
     * Reason: to find correct cache-file.
     * If it's needed to fetch wildcard from db, use eZURLWildcard::fetchList
     * with offset = $wildcardNum and $limit = 1.
     *
     * @param int $wildcardNum
     * @param eZURI|string $uri
     * @param mixed $wildcardInfo
     * @param mixed $matches
     *
     * @return bool
     **/
    static function translateWithCache( $wildcardNum, &$uri, &$wildcardInfo, $matches )
    {
        eZDebugSetting::writeDebug( 'kernel-urltranslator', "eZURLWildcardTranslateWithCache:: wildcardNum = $wildcardNum, uri = $uri", __METHOD__ );

        $cacheFileNum = (int) ( $wildcardNum / eZURLWildcard::WILDCARDS_PER_CACHE_FILE );

        eZDebugSetting::writeDebug( 'kernel-urltranslator', "eZURLWildcardTranslateWithCache:: cacheFileNum = $cacheFileNum", __METHOD__ );

        $info = eZURLWildcard::cacheInfo();
        $cacheFileName = $info['path'] . "_$cacheFileNum" . ".php";

        $cacheFile = eZClusterFileHandler::instance( $cacheFileName );

        if ( !$cacheFile->exists() )
        {
            eZDebugSetting::writeDebug( 'kernel-urltranslator', "eZURLWildcardTranslateWithCache:: no cache file '$cacheFileName'", __METHOD__ );
            return false;
        }

        $fetchedFilePath = $cacheFile->fetchUnique();
        include_once( $fetchedFilePath );
        $cacheFile->fileDeleteLocal( $fetchedFilePath );

        if ( !function_exists( eZURLWildcard::CACHED_TRANSLATE ) )
        {
            eZDebugSetting::writeDebug( 'kernel-urltranslator', "eZURLWildcardTranslateWithCache:: no function in cache file", __METHOD__ );
            return false;
        }

        $function = eZURLWildcard::CACHED_TRANSLATE;
        $wildcards = $function( $wildcardNum, $uri, $wildcardInfo, $matches );

        eZDebugSetting::writeDebug( 'kernel-urltranslator', "eZURLWildcardTranslateWithCache:: found wildcard: " . var_export( $wildcardInfo, true ), __METHOD__ );

        return true;
    }
}

?>