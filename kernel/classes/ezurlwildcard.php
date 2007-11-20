<?php
//
// Definition of eZURLWildcard class
//
// Created on: <08-Nov-2007 16:44:56 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file ezurlwildcard.php
*/

/*!
  \class eZURLWildcard ezurlwildcard.php
  \brief Handles URL alias wildcards in eZ Publish

  \private
*/

//include_once( "kernel/classes/ezpersistentobject.php" );
//include_once( "kernel/classes/ezurlaliasml.php" );

class eZURLWildcard extends eZPersistentObject
{
    const EZURLWILDCARD_REGEXP_ARRAY_CALLBACK = 'eZURLWilcardCachedReqexpArray';
    const EZURLWILDCARD_TRANSLATE_CALLBACK = 'eZURLWildcardTranslateWithCache';
    const EZURLWILDCARD_CACHED_TRANSLATE = 'eZURLWilcardCachedTranslate';

    const EZURLWILDCARD_WILDCARDS_PER_CACHE_FILE = 100;

    const EZ_URLWILDCARD_TYPE_NONE = 0;
    const EZ_URLWILDCARD_TYPE_FORWARD = 1;
    const EZ_URLWILDCARD_TYPE_DIRECT = 2;

    const EZ_URLWILDCARD_CACHE_SIGNATURE = 'urlalias-wildcard';

    /*!
     Initializes a new URL alias.
    */
    function eZURLWildcard( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \reimp
    */
    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
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
    }

    /*!
     \return the url alias object as an associative array with all the attribute values.
    */
    function asArray()
    {
        return array( 'id' => $this->attribute( 'id' ),
                      'source_url' => $this->attribute( 'source_url' ),
                      'destination_url' => $this->attribute( 'destination_url' ),
                      'type' => $this->attribute( 'type' ) );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function store( $fieldFilters = null )
    {
        eZPersistentObject::store( $fieldFilters );
    }

    /*!
     \static
      Removes all wildcards that matches the base URL \a $baseURL.
    */
    static function cleanup( $baseURL )
    {
        $db = eZDB::instance();
        $baseURLText = $db->escapeString( $baseURL . "/*" );
        $sql = "DELETE FROM ezurlwildcard
                WHERE source_url = '$baseURLText'";
        $db->query( $sql );
    }

    /*!
     \static
      Removes all wildcards.
     */
    static function removeAll()
    {
        eZPersistentObject::removeObject( eZURLWildcard::definition() );
    }

    /*!
     \static
      Removes wildcards by IDs specified in \a $idList
     */
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

    /*!
     \static
      Fetches the URL wildcard by ID.
    */
    static function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZURLWildcard::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
    }

    /*!
     \static
      Fetches wildcard by source url \a $url
     */
    static function fetchBySourceURL( $url, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZURLWildcard::definition(),
                                                null,
                                                array( "source_url" => $url ),
                                                $asObject );
    }

    /*!
     \static
      Fetches URL wildcards by offset \a $offset and limit \a $limit.
      By default fetches all wildcards.
    */
    static function fetchList( $offset = false, $limit = false, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZURLWildcard::definition(),
                                                    null,
                                                    null,
                                                    null,
                                                    array( 'offset' => $offset, 'length' => $limit ),
                                                    $asObject );
    }

    /*!
     \static
     \return number of wildcards in db
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
     \deprecated This class has been deprecated and disabled in eZ Publish 3.10, please use eZURLAliasML for future alias handling.
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

    /*!
     \static
     Sets the various cache information to the parameters.
     \sa cacheInfo
    */
    static function cacheInfoDirectories( &$wildcardCacheDir, &$wildcardCacheFile, &$wildcardCachePath, &$wildcardKeys )
    {
        $info = eZURLWildcard::cacheInfo();
        $wildcardCacheDir = $info['dir'];
        $wildcardCacheFile = $info['file'];
        $wildcardCachePath = $info['path'];
        $wildcardKeys = $info['keys'];
    }

    /*!
     \static
     \return true if the wildcard cache is expired.
    */
    static function isCacheExpired( $timestamp )
    {
        $expired = false;

        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler = eZExpiryHandler::instance();

        if ( $handler->hasTimestamp( eZURLWildcard::EZ_URLWILDCARD_CACHE_SIGNATURE ) )
        {
            $expiryTime = $handler->timestamp( eZURLWildcard::EZ_URLWILDCARD_CACHE_SIGNATURE );
            if ( $expiryTime >= $timestamp )
                $expired = true;
        }

        return $expired;
    }

    /*!
     \static
     Expires the wildcard cache. This causes the wildcard cache to be
     regenerated on the next page load.
    */
    static function expireCache()
    {
        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler = eZExpiryHandler::instance();
        $handler->setTimestamp( eZURLWildcard::EZ_URLWILDCARD_CACHE_SIGNATURE, time() );
        $handler->store();
    }

    /*!
     \static
     Transforms the URI if there exists an alias for it.
     \return \c true is if successful, \c false otherwise
     \return The string with new url is returned if the translation was found, but the resource has moved.
    */
    static function translate( &$uri )
    {
        $result = false;

        // get uri string
        $uriString = ( $uri instanceof eZURI ) ? $uri->elements() : $uri;
        $uriString = eZURLAliasML::cleanURL( $uriString );

        eZDebugSetting::writeDebug( 'kernel-urltranslator', "input uriString: '$uriString'", 'eZURLWildcard::translate' );

        // setup helper callbacks
        $regexpArrayCallback = false;
        $translateCallback = false;
        if ( !eZURLWildcard::setupMatchCallbacks( $regexpArrayCallback, $translateCallback ) )
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
                    $translateCallback( $wildcardNum, $uriString, $wildcardInfo, $matches );

                    eZDebugSetting::writeDebug( 'kernel-urltranslator', "new uri string: '$uriString'", 'eZURLWildcard::translate' );

                    // optimization: don't try further translation if wildcard type is 'forward'
                    if ( $wildcardInfo['type'] == eZURLWildcard::EZ_URLWILDCARD_TYPE_FORWARD )
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
        // NOTE: 'eZURLAliasML::translate' can return 'true', 'false' or new url(in case of 'error/301').
        if ( $urlTranslated === false )
        {
            eZDebugSetting::writeDebug( 'kernel-urltranslator', "no wildcard is matched input url", 'eZURLWildcard::translate' );
            return false;
        }
        else
        {
            // check wildcard type and set appropriate $result and $uriString
            $wildcardType = $wildcardInfo['type'];

            eZDebugSetting::writeDebug( 'kernel-urltranslator', "wildcard type: $wildcardType", 'eZURLWildcard::translate' );

            switch ( $wildcardType )
            {
                case eZURLWildcard::EZ_URLWILDCARD_TYPE_FORWARD:
                    {
                        // do redirect => set $result to untranslated uri
                        $result = $uriString;
                        $uriString = 'error/301';
                    }
                    break;

                default:
                    eZDebug::writeError( 'Invalid wildcard type.', 'eZURLWildcard::translate()' );
                    // no break, using 'EZ_URLALIAS_WILDCARD_TYPE_DIRECT' as fallback
                case eZURLWildcard::EZ_URLWILDCARD_TYPE_DIRECT:
                    $result = $urlTranslated;
                    // $uriString already has correct value
                    break;
            }
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

    /*!
     \static
     \private
      Assign function names to input variables:
        $regexpArrayCallback  - function to get an array of regexps;
        $translateCallback - function to get data for urlalias;
     */
    static private function setupMatchCallbacks( &$regexpArrayCallback, &$translateCallback )
    {
        if ( !eZURLWildcard::createCacheIfExpired() )
        {
            // mandatory cache is required in <= 3.9.x.
            // note: the appropriate $regexpArrayCallback and $translateCallback can be implemented
            //       to support translation without cache.
            eZDebugSetting::writeDebug( 'kernel-urltranslator', "setting up callback for non cache mode", 'eZURLWildcard::setupMatchCallbacks' );
            return false;
        }
        else
        {
            eZDebugSetting::writeDebug( 'kernel-urltranslator', "setting up callback for cache mode", 'eZURLWildcard::setupMatchCallbacks' );

            eZURLWildcard::loadCacheIndex();

            if ( function_exists( eZURLWildcard::EZURLWILDCARD_REGEXP_ARRAY_CALLBACK ) )
            {
                $regexpArrayCallback = eZURLWildcard::EZURLWILDCARD_REGEXP_ARRAY_CALLBACK;
                $translateCallback = eZURLWildcard::EZURLWILDCARD_TRANSLATE_CALLBACK;
            }
            else
            {
                eZDebug::writeError( 'Broken wildcard cache index file.', 'eZURLWildcard::setupMatchCallbacks()' );
                return false;
            }
        }

        return true;
    }

    /*!
     \static
    */
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

    /*!
     \static
     The wildcard caches are splitted between several files:
        'wildcard_<md5>_index.php' - contains regexps for wildcards,
        'wildcard_<md5>_0.php',
        'wildcard_<md5>_1.php',
        ...
        'wildcard_<md5>_N.php'     - contains cached wildcards.
                                     Each file has info about eZURLWildcard::EZURLWILDCARD_WILDCARDS_PER_CACHE_FILE wildcards.
     */
    static function createCache()
    {
        eZURLWildcard::cacheInfoDirectories( $wildcardCacheDir, $wildcardCacheFile, $wildcardCachePath, $wildcardKeys );
        if ( !file_exists( $wildcardCacheDir ) )
        {
            eZDir::mkdir( $wildcardCacheDir, eZDir::directoryPermission(), true );
        }

        // VS-DBFILE

        include_once( 'lib/ezutils/classes/ezphpcreator.php' );
        $phpCacheIndex = new eZPHPCreator( $wildcardCacheDir, $wildcardCacheFile . "_index.php", '', array( 'clustering' => 'wirldcard-cache-index' ) );

        foreach ( $wildcardKeys as $wildcardKey => $wildcardKeyValue )
        {
            $phpCacheIndex->addComment( "$wildcardKey = $wildcardKeyValue" );
        }
        $phpCacheIndex->addSpace();

        $phpCodeIndex = "function " . eZURLWildcard::EZURLWILDCARD_REGEXP_ARRAY_CALLBACK . "()\n{\n";

        $phpCodeIndex .= "    ";
        $phpCodeIndex .= "\$wildcards = array(\n";

        $limit = eZURLWildcard::EZURLWILDCARD_WILDCARDS_PER_CACHE_FILE;
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

            // VS-DBFILE
            $phpCache = new eZPHPCreator( $wildcardCacheDir, $wildcardCacheFile . "_$cacheFilesCount.php", '', array( 'clustering' => 'wirldcard-cache-' . $cacheFilesCount ) );

            foreach ( $wildcardKeys as $wildcardKey => $wildcardKeyValue )
            {
                $phpCache->addComment( "$wildcardKey = $wildcardKeyValue" );
            }
            $phpCache->addSpace();

            $phpCode = "function " . eZURLWildcard::EZURLWILDCARD_CACHED_TRANSLATE . "( \$wildcardNum, &\$uri, &\$wildcardInfo, \$matches )\n{\n";

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

    /*!
     \static
     \private
     Creates a 'regexp' portion of php-code for cache-index.
     */
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

    /*!
     \static
     \private
     Creates a 'replace' and wildcard-info portions of php-code for cache.
     */
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

    /*!
     \static
     \return instance of 'eZClusterFileHandler' for cache-index file
    */
    static function cacheIndexFile()
    {
        $info = eZURLWildcard::cacheInfo();

        // VS-DBFILE

        require_once( 'kernel/classes/ezclusterfilehandler.php' );
        $cacheFile = eZClusterFileHandler::instance( $info['path'] . "_index.php" );

        return $cacheFile;
    }

    /*!
     \static
     Loads cache-index.
     */
    static function loadCacheIndex()
    {
        $info = eZURLWildcard::cacheInfo();

        // VS-DBFILE

        require_once( 'kernel/classes/ezclusterfilehandler.php' );
        $cacheFile = eZClusterFileHandler::instance( $info['path'] . "_index.php" );

        if ( !$cacheFile->exists() )
            return false;

        // VS-DBFILE

        $fetchedFilePath = $cacheFile->fetchUnique();
        include_once( $fetchedFilePath );
        $cacheFile->fileDeleteLocal( $fetchedFilePath );

        return true;
    }
}

/*!
 \global
 The callback loads appropriate cache file for wildcard \a $wildcardNum,
 extracts wildcard info and 'replace' url from cache.
 \note The wildcard number(not a wildcard id) is used here. Reason: to find correct cache-file.
       If it's needed to fetch wildcard from db, use eZURLWildcard::fetchList with offset = $wildcardNum and
       $limit = 1.
 */
function eZURLWildcardTranslateWithCache( $wildcardNum, &$uri, &$wildcardInfo, $matches )
{
    eZDebugSetting::writeDebug( 'kernel-urltranslator', "eZURLWildcardTranslateWithCache:: wildcardNum = $wildcardNum, uri = $uri", 'eZURLWildcardTranslateWithCache' );

    $cacheFileNum = (int) ( $wildcardNum / eZURLWildcard::EZURLWILDCARD_WILDCARDS_PER_CACHE_FILE );

    eZDebugSetting::writeDebug( 'kernel-urltranslator', "eZURLWildcardTranslateWithCache:: cacheFileNum = $cacheFileNum", 'eZURLWildcardTranslateWithCache' );

    $info = eZURLWildcard::cacheInfo();
    $cacheFileName = $info['path'] . "_$cacheFileNum" . ".php";

    // VS-DBFILE
    require_once( 'kernel/classes/ezclusterfilehandler.php' );
    $cacheFile = eZClusterFileHandler::instance( $cacheFileName );

    if ( !$cacheFile->exists() )
    {
        eZDebugSetting::writeDebug( 'kernel-urltranslator', "eZURLWildcardTranslateWithCache:: no cache file '$cacheFileName'", 'eZURLWildcardTranslateWithCache' );
        return false;
    }

    // VS-DBFILE

    $fetchedFilePath = $cacheFile->fetchUnique();
    include_once( $fetchedFilePath );
    $cacheFile->fileDeleteLocal( $fetchedFilePath );

    if ( !function_exists( eZURLWildcard::EZURLWILDCARD_CACHED_TRANSLATE ) )
    {
        eZDebugSetting::writeDebug( 'kernel-urltranslator', "eZURLWildcardTranslateWithCache:: no function in cache file", 'eZURLWildcardTranslateWithCache' );
        return false;
    }

    $function = eZURLWildcard::EZURLWILDCARD_CACHED_TRANSLATE;
    $wildcards = $function( $wildcardNum, $uri, $wildcardInfo, $matches );

    eZDebugSetting::writeDebug( 'kernel-urltranslator', "eZURLWildcardTranslateWithCache:: found wildcard: " . var_export( $wildcardInfo, true ), 'eZURLWildcardTranslateWithCache' );

    return true;
}

?>
