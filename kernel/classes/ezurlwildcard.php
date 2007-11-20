<?php
//
// Definition of eZURLWildcard class
//
// Created on: <08-Nov-2007 16:44:56 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ systems AS
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

include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezurlaliasml.php" );

define( 'EZURLWILDCARD_REGEXP_ARRAY_CALLBACK', 'eZURLWilcardCachedReqexpArray' );
define( 'EZURLWILDCARD_TRANSLATE_CALLBACK', 'eZURLWildcardTranslateWithCache' );
define( 'EZURLWILDCARD_CACHED_TRANSLATE', 'eZURLWilcardCachedTranslate' );
define( 'EZURLWILDCARD_WILDCARDS_PER_CACHE_FILE', 100 );

define( 'EZ_URLWILDCARD_TYPE_NONE', 0 );
define( 'EZ_URLWILDCARD_TYPE_FORWARD', 1 );
define( 'EZ_URLWILDCARD_TYPE_DIRECT', 2 );

define( 'EZ_URLWILDCARD_CACHE_SIGNATURE', 'urlalias-wildcard' );

class eZURLWildcard extends eZPersistentObject
{
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
    function definition()
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
    function store()
    {
        eZPersistentObject::store();
    }

    /*!
     \static
      Removes all wildcards that matches the base URL \a $baseURL.
    */
    function cleanup( $baseURL )
    {
        $db =& eZDB::instance();
        $baseURLText = $db->escapeString( $baseURL . "/*" );
        $sql = "DELETE FROM ezurlwildcard
                WHERE source_url = '$baseURLText'";
        $db->query( $sql );
    }

    /*!
     \static
      Removes all wildcards.
     */
    function removeAll()
    {
        eZPersistentObject::removeObject( eZURLWildcard::definition() );
    }

    /*!
     \static
      Removes wildcards by IDs specified in \a $idList
     */
    function removeByIDs( $idList )
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
    function fetch( $id, $asObject = true )
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
    function fetchBySourceURL( $url, $asObject = true )
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
    function fetchList( $offset = false, $limit = false, $asObject = true )
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
    function fetchListCount()
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
    function cacheInfo()
    {
        $cacheDir = eZSys::cacheDirectory();
        $ini =& eZINI::instance();
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
    function cacheInfoDirectories( &$wildcardCacheDir, &$wildcardCacheFile, &$wildcardCachePath, &$wildcardKeys )
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
    function isCacheExpired( $timestamp )
    {
        $expired = false;

        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler =& eZExpiryHandler::instance();

        if ( $handler->hasTimestamp( EZ_URLWILDCARD_CACHE_SIGNATURE ) )
        {
            $expiryTime = $handler->timestamp( EZ_URLWILDCARD_CACHE_SIGNATURE );
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
    function expireCache()
    {
        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler =& eZExpiryHandler::instance();
        $handler->setTimestamp( EZ_URLWILDCARD_CACHE_SIGNATURE, mktime() );
        $handler->store();
    }

    /*!
     \static
     Transforms the URI if there exists an alias for it.
     \return \c true is if successful, \c false otherwise
     \return The string with new url is returned if the translation was found, but the resource has moved.
    */
    function translate( &$uri )
    {
        $result = false;

        // get uri string
        $uriString = ( get_class( $uri ) == "ezuri" ) ? $uri->elements() : $uri;
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

        $ini =& eZINI::instance();
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
                    if ( $wildcardInfo['type'] == EZ_URLWILDCARD_TYPE_FORWARD )
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
                case EZ_URLWILDCARD_TYPE_FORWARD:
                    {
                        // do redirect => set $result to untranslated uri
                        $result = $uriString;
                        $uriString = 'error/301';
                    }
                    break;

                default:
                    eZDebug::writeError( 'Invalid wildcard type.', 'eZURLWildcard::translate()' );
                    // no break, using 'EZ_URLALIAS_WILDCARD_TYPE_DIRECT' as fallback
                case EZ_URLWILDCARD_TYPE_DIRECT:
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
        if ( get_class( $uri ) == "ezuri" )
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
    function setupMatchCallbacks( &$regexpArrayCallback, &$translateCallback )
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

            if ( function_exists( EZURLWILDCARD_REGEXP_ARRAY_CALLBACK ) )
            {
                $regexpArrayCallback = EZURLWILDCARD_REGEXP_ARRAY_CALLBACK;
                $translateCallback = EZURLWILDCARD_TRANSLATE_CALLBACK;
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
    function createCacheIfExpired()
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
                                     Each file has info about EZURLWILDCARD_WILDCARDS_PER_CACHE_FILE wildcards.
     */
    function createCache()
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

        $phpCodeIndex = "function " . EZURLWILDCARD_REGEXP_ARRAY_CALLBACK . "()\n{\n";

        $phpCodeIndex .= "    ";
        $phpCodeIndex .= "\$wildcards = array(\n";

        $limit = EZURLWILDCARD_WILDCARDS_PER_CACHE_FILE;
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

            $phpCode = "function " . EZURLWILDCARD_CACHED_TRANSLATE . "( \$wildcardNum, &\$uri, &\$wildcardInfo, \$matches )\n{\n";

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
     Creates a 'regexp' portion of php-code for cache-index.
     */
    function matchRegexpCode( $wildcard )
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
     Creates a 'replace' and wildcard-info portions of php-code for cache.
     */
    function matchReplaceCode( $wildcard, $indent = '' )
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
    function cacheIndexFile()
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
    function loadCacheIndex()
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

    $cacheFileNum = (int) ( $wildcardNum / EZURLWILDCARD_WILDCARDS_PER_CACHE_FILE );

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

    if ( !function_exists( EZURLWILDCARD_CACHED_TRANSLATE ) )
    {
        eZDebugSetting::writeDebug( 'kernel-urltranslator', "eZURLWildcardTranslateWithCache:: no function in cache file", 'eZURLWildcardTranslateWithCache' );
        return false;
    }

    $function = EZURLWILDCARD_CACHED_TRANSLATE;
    $wildcards = $function( $wildcardNum, $uri, $wildcardInfo, $matches );

    eZDebugSetting::writeDebug( 'kernel-urltranslator', "eZURLWildcardTranslateWithCache:: found wildcard: " . var_export( $wildcardInfo, true ), 'eZURLWildcardTranslateWithCache' );

    return true;
}

?>
