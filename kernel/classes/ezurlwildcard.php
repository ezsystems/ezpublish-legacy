<?php
//
// Definition of eZURLWildcard class
//
// Created on: <08-Nov-2007 16:44:56 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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
    /**
    * Max number of wildcard entries per cache file
    * @var int
    **/
    const WILDCARDS_PER_CACHE_FILE = 100;

    /**
    * Wildcards types
    * @var int
    **/
    const TYPE_NONE = 0;
    const TYPE_FORWARD = 1;
    const TYPE_DIRECT = 2;

    /**
     * ExpiryHandler key
     * @var string
     **/
    const CACHE_SIGNATURE = 'urlalias-wildcard';

    /**
    * Cluster file handler instances of cache files
    * @var array(eZClusterFileHandlerInterface)
    **/
    protected static $cacheFiles = array();

    /**
    * Wildcards index local cache
    * @var array
    **/
    protected static $wildcardsIndex = null;

    /**
     * Initializes a new URL alias persistent object
     * @param array $row
     **/
    public function eZURLWildcard( $row )
    {
        $this->eZPersistentObject( $row );
    }

    public static function definition()
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
    public function asArray()
    {
        return array( 'id' => $this->attribute( 'id' ),
                      'source_url' => $this->attribute( 'source_url' ),
                      'destination_url' => $this->attribute( 'destination_url' ),
                      'type' => $this->attribute( 'type' ) );
    }

    /**
    * Stores the eZURLWildcard persistent object
    **/
    public function store( $fieldFilters = null )
    {
        eZPersistentObject::store( $fieldFilters );
    }

    /**
     * Removes a wildcard based on a source_url.
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
        self::expireCache();
    }

    /**
    * Removes all the wildcards
    * @return void
    **/
    public static function removeAll()
    {
        eZPersistentObject::removeObject( self::definition() );
        self::expireCache();
    }

    /**
     * Removes wildcards based on an ID list
     * @param array $idList array of numerical ID
     * @return void
     **/
    public static function removeByIDs( $idList )
    {
        if ( !is_array( $idList ) )
            return;

        while ( count( $idList ) > 0 )
        {
            // remove by portion of 100 rows.
            $ids = array_splice( $idList, 0, 100 );

            $conditions = array( 'id' => array( $ids ) );

            eZPersistentObject::removeObject( self::definition(),
                                              $conditions );
        }
    }

    /**
     * Fetch a wildcard by numerical ID
     * @param int $id
     * @param bool $asObject
     * @return eZURLWildcard null if no match was found
     **/
    public static function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( self::definition(),
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
    public static function fetchBySourceURL( $url, $asObject = true )
    {
        return eZPersistentObject::fetchObject( self::definition(),
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
    public static function fetchList( $offset = false, $limit = false, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( self::definition(),
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
    public static function fetchListCount()
    {
        $rows = eZPersistentObject::fetchObjectList( self::definition(),
                                                     array(),
                                                     null,
                                                     false,
                                                     null,
                                                     false, false,
                                                     array( array( 'operation' => 'count( * )',
                                                                   'name' => 'count' ) ) );
        return $rows[0]['count'];
    }

    /**
     * Transforms the URI if there exists an alias for it.
     *
     * @param eZURI|string $uri
     * @return mixed The translated URI if the resource has moved, or true|false
     *               if translation was (un)successful
     **/
    public static function translate( &$uri )
    {
        $result = false;

        // get uri string
        $uriString = ( $uri instanceof eZURI ) ? $uri->elements() : $uri;
        $uriString = eZURLAliasML::cleanURL( $uriString );

        eZDebugSetting::writeDebug( 'kernel-urltranslator', "input uriString: '$uriString'", __METHOD__ );

        if ( !$wildcards = self::wildcardsIndex() )
        {
            eZDebugSetting::writeDebug( 'kernel-urltranslator', "no match callbacks", __METHOD__ );
            return false;
        }

        $ini = eZINI::instance();
        $iteration = $ini->variable( 'URLTranslator', 'MaximumWildcardIterations' );

        eZDebugSetting::writeDebug( 'kernel-urltranslator', "MaximumWildcardIterations: '$iteration'", __METHOD__ );

        // translate
        $urlTranslated = false;
        while ( !$urlTranslated && $iteration >= 0 )
        {
            foreach ( $wildcards as $wildcardNum => $wildcard )
            {
                if ( preg_match( $wildcard, $uriString ) )
                {
                    eZDebugSetting::writeDebug( 'kernel-urltranslator', "matched with: '$wildcard'", __METHOD__ );

                    // get new $uriString from wildcard
                    self::translateWithCache( $wildcardNum, $uriString, $wildcardInfo, $wildcard );

                    eZDebugSetting::writeDebug( 'kernel-urltranslator', "new uri string: '$uriString'", __METHOD__ );

                    // optimization: don't try further translation if wildcard type is 'forward'
                    if ( $wildcardInfo['type'] == self::TYPE_FORWARD )
                    {
                        $urlTranslated = true;
                        break;
                    }

                    // try to tranlsate
                    if ( $urlTranslated = eZURLAliasML::translate( $uriString ) )
                    {
                        // success
                        eZDebugSetting::writeDebug( 'kernel-urltranslator', "uri is translated to '$uriString' with result '$urlTranslated'", __METHOD__ );
                        break;
                    }

                    eZDebugSetting::writeDebug( 'kernel-urltranslator', "uri is not translated, trying another wildcard", __METHOD__ );

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

            eZDebugSetting::writeDebug( 'kernel-urltranslator', "wildcard type: $wildcardType", __METHOD__ );

            switch ( $wildcardType )
            {
                case self::TYPE_FORWARD:
                {
                    // do redirect:
                    //   => set $result to translated uri
                    //   => set uri string to a MOVED PERMANENTLY HTTP code
                    $result = $uriString;
                    $uriString = 'error/301';
                }
                break;

                default:
                {
                    eZDebug::writeError( 'Invalid wildcard type.', __METHOD__ );
                    // no break, using eZURLWildcard::TYPE_DIRECT as fallback
                }
                case self::TYPE_DIRECT:
                {
                    $result = $urlTranslated;
                    // $uriString already has correct value
                    break;
                }
            }
        }
        else
        {
            // we are here if:
            // - input url is not matched with any wildcard;
            // - url is matched with wildcard and:
            //   - points to module
            //   - invalide url
            eZDebugSetting::writeDebug( 'kernel-urltranslator', "wildcard is not translated", __METHOD__ );
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

        eZDebugSetting::writeDebug( 'kernel-urltranslator', "finished with url '$uriString' and result '$result'", __METHOD__ );

        return $result;
    }

    /**
     * Returns an array with information on the wildcard cache
     * The array containst the following keys
     * - dir - The directory for the cache
     * - file - The base filename for the caches
     * - path - The entire path (including filename) for the cache
     * - keys - Array with key values which is used to uniquely identify the cache
     * @return array
     **/
    protected static function cacheInfo()
    {
        static $cacheInfo = null;

        if ( $cacheInfo == null )
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
            $cacheInfo = array( 'dir' => $wildcardCacheDir,
                                'file' => $wildcardCacheFile,
                                'path' => $wildcardCachePath,
                                'keys' => $keys );
        }

        return $cacheInfo;
    }

    /**
     * Sets the various cache information to the parameters.
     * @private
     **/
    protected static function cacheInfoDirectories( &$wildcardCacheDir, &$wildcardCacheFile, &$wildcardCachePath, &$wildcardKeys )
    {
        $info = self::cacheInfo();
        $wildcardCacheDir = $info['dir'];
        $wildcardCacheFile = $info['file'];
        $wildcardCachePath = $info['path'];
        $wildcardKeys = $info['keys'];
    }

    /**
     * Expires the wildcard cache. This causes the wildcard cache to be
     * regenerated on the next page load.
     * @return void
     **/
    public static function expireCache()
    {
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( self::CACHE_SIGNATURE, time() );
        $handler->store();

        self::$wildcardsIndex = null;
    }

    /**
    * Returns the expiry timestamp for wildcard cache from eZExpiryHandler
    * @return int|bool the timestamp if set, false otherwise
    **/
    protected static function expiryTimestamp()
    {
        $handler = eZExpiryHandler::instance();
        if ( $handler->hasTimestamp( self::CACHE_SIGNATURE ) )
        {
            $ret = $handler->timestamp( self::CACHE_SIGNATURE );
        }
        else
        {
            $ret = false;
        }
        return $ret;
    }

    /**
     * Checks if the wildcard cache is expired
     *
     * @param int $timestamp Timestamp expiry should be checked against
     *
     * @return bool true if cache is expired
     * @deprecated since 4.2.0
     **/
    public static function isCacheExpired( $timestamp )
    {
        return ( self::expiryTimestamp() > $timestamp );
    }

    /**
     * Assign function names to input variables. Generates the wildcard cache if
     * expired.
     *
     * @param $regexpArrayCallback function to get an array of regexps
     *
     * @return array The wildcards index, as an array of regexps
     **/
    protected static function wildcardsIndex()
    {
        if ( self::$wildcardsIndex === null )
        {
            $cacheIndexFile = self::loadCacheFile();

            // if NULL is returned, the cache doesn't exist or isn't valid
            $wildcardsIndex = $cacheIndexFile->processFile( array( __CLASS__, 'fetchCacheFile' ), self::expiryTimestamp() );
            if ( $wildcardsIndex === null )
            {
                // This will generate and return the index, and store the cache
                // files for the different wildcards for later use
                $wildcardsIndex = self::createWildcardsIndex();
            }
        }

        return $wildcardsIndex;
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
    protected static function createWildcardsIndex()
    {
        self::cacheInfoDirectories( $wildcardCacheDir, $wildcardCacheFile, $wildcardCachePath, $wildcardKeys );
        if ( !file_exists( $wildcardCacheDir ) )
        {
            eZDir::mkdir( $wildcardCacheDir, false, true );
        }

        // Index file (wildcard_md5_index.php)
        $wildcardsIndex = array();

        $limit = self::WILDCARDS_PER_CACHE_FILE;
        $offset = 0;
        $cacheFilesCount = 0;
        $wildcardNum = 0;
        while( 1 )
        {
            $wildcards = self::fetchList( $offset, $limit, false );
            if ( count( $wildcards ) === 0 )
            {
                break;
            }

            // sub cache file (wildcard_md5_<i>.php)
            $wildcardDetails = array();
            $currentSubCacheFile = self::loadCacheFile( $cacheFilesCount );
            foreach ( $wildcards as $wildcard )
            {
                $wildcardsIndex[] = self::matchRegexpCode( $wildcard );
                $wildcardDetails[$wildcardNum] = self::matchReplaceCode( $wildcard );

                ++$wildcardNum;
            }
            $binaryData = "<" . "?php\nreturn ". var_export( $wildcardDetails, true ) . ";\n?" . ">\n";
            $currentSubCacheFile->storeContents( $binaryData, "wildcard-cache-$cacheFilesCount", 'php', true );

            $offset += $limit;
            ++$cacheFilesCount;
        }

        $indexCacheFile = self::loadCacheFile();
        $indexBinaryData = "<" . "?php\nreturn ". var_export( $wildcardsIndex, true ) . ";\n?" . ">\n";
        $indexCacheFile->storeContents( $indexBinaryData, "wildcard-cache-index", 'php', true );

        return $wildcardsIndex;
        // end index cache file
    }

    /**
     * Transforms the source-url of a wildcard to a preg_match compatible expression
     * Example: foo/* will be converted to #^foo/(.*)$#
     *
     * @param array $wildcard wildcard data with a source_url key
     *
     * @return string preg_match compatible string
     **/
    protected static function matchRegexpCode( $wildcard )
    {
        $matchWilcard = $wildcard['source_url'];
        $matchWilcardList = explode( "*", $matchWilcard );
        $regexpList = array();
        foreach ( $matchWilcardList as $matchWilcardItem )
        {
            $regexpList[] = preg_quote( $matchWilcardItem, '#' );
        }
        $matchRegexp = implode( '(.*)', $regexpList );

        $phpCode = "#^$matchRegexp#";

        return $phpCode;
    }

    /**
     * Converts the destination-url of a wildcard to a preg_replace compatible
     * expression.
     * Example: foobar/{1} will be converted to ...
     * @todo fix the example
     *
     * @param array $wildcard Wildcard array with a destination_url key
     *
     * @return string match/replace PHP Code
     *
     * @todo Try to replace the eval'd code with a preg_replace expression
     **/
    protected static function matchReplaceCode( $wildcard )
    {
        $return = array();

        $replaceWildcardList = preg_split( "#{([0-9]+)}#", $wildcard['destination_url'], false, PREG_SPLIT_DELIM_CAPTURE );

        $replaceArray = array();
        foreach ( $replaceWildcardList as $index => $replaceWildcardItem )
        {
            // even values are placeholders
            if ( ( $index % 2 ) == 0 )
            {
                $replaceArray[] = $replaceWildcardItem;
            }
            else
            {
                $replaceArray[] = "\${$replaceWildcardItem}";
            }
        }
        $replaceCode = implode( '', $replaceArray );

        $return['uri'] = $replaceCode;
        $return['info'] = $wildcard;

        return $return;
    }

    /**
     * The callback loads appropriate cache file for wildcard $wildcardNum,
     * extracts wildcard info and 'replace' url from cache.
     *
     * The wildcard number (not a wildcard id) is used here in order to load
     * the appropriate cache file.
     *
     * If it's needed to fetch wildcard from db, use eZURLWildcard::fetchList
     * with offset = $wildcardNum and $limit = 1.
     *
     * @param int $wildcardNum
     * @param eZURI|string $uri
     * @param mixed $wildcardInfo
     * @param mixed $matches
     *
     * @return bool
     *
     * @todo make private, this method isn't used anywhere else
     **/
    protected static function translateWithCache( $wildcardNum, &$uri, &$wildcardInfo, $matchRegexp )
    {
        eZDebugSetting::writeDebug( 'kernel-urltranslator', "wildcardNum = $wildcardNum, uri = $uri", __METHOD__ );

        $cacheFileNum = (int) ( $wildcardNum / self::WILDCARDS_PER_CACHE_FILE );

        eZDebugSetting::writeDebug( 'kernel-urltranslator', "cacheFileNum = $cacheFileNum", __METHOD__ );

        $cacheFile = self::loadCacheFile( $cacheFileNum );
        $wildcardsInfos = $cacheFile->processFile( array( __CLASS__, 'fetchCacheFile' ) );

        if ( !isset( $wildcardsInfos[$wildcardNum] ) )
        {
            eZDebug::writeError( "An error occured: the requested wildcard couldn't be found", __METHOD__ );
            return false;
        }

        // @todo Try to replace this with a preg_replace in order to get rid of eval()
        $replaceRegexp = $wildcardsInfos[$wildcardNum]['uri'];
        $uri = preg_replace( $matchRegexp, $replaceRegexp, $uri );
        $wildcardInfo = $wildcardsInfos[$wildcardNum]['info'];

        eZDebugSetting::writeDebug( 'kernel-urltranslator', "found wildcard: " . var_export( $wildcardInfo, true ), __METHOD__ );

        return true;
    }

    /**
    * Loads and returns the cluster handler instance for the requested cache file.
    * The instance will be returned even if the file doesn't exist
    *
    * @param $cacheID Cache file number. Will load the index if not provided.
    *
    * @return eZClusterFileHandlerInterface
    **/
    protected static function loadCacheFile( $cacheID = 'index' )
    {
        if ( isset( self::$cacheFiles[$cacheID] ) )
        {
            return self::$cacheFiles[$cacheID];
        }

        $info = self::cacheInfo();
        $cacheFileName = $info['path'] . '_' . $cacheID . '.php';

        self::$cacheFiles[$cacheID] = eZClusterFileHandler::instance( $cacheFileName );
        return self::$cacheFiles[$cacheID];
    }

    /**
     * Includes a wildcard cache file and returns its return value
     * This method is used as a callback by eZClusterFileHandler::processFile
     *
     * @param string $filepath
     *
     * @return array
     **/
    public static function fetchCacheFile( $filepath )
    {
        return include( $filepath );
    }
}

?>