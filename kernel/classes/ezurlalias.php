<?php
//
// Definition of eZURLAlias class
//
// Created on: <01-Aug-2003 16:44:56 bf>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezurlalias.php
*/

/*!
  \class eZURLAlias ezurlalias.php
  \brief Handles URL aliases in eZ publish

  URL aliases are different names for existing URLs in eZ publish.
  Using URL aliases allows for having better looking urls on the webpage
  as well as having fixed URLs pointing to various locations.

  This class handles storing, fetching, moving and subtree updates on eZ publish URL aliases.

  Creating a new alias is done by using the create() function and passing the correct parameters.
  Fetching an url can either be done with it's ID using fetch() or by it's URL string by using fetchBySourceURL.
  To fetch the original URL you must use the translate() function.
*/

include_once( "kernel/classes/ezpersistentobject.php" );

define( 'EZURLALIAS_CACHE_FUNCTION', 'eZURLAliasWilcardTranslate' );

define( 'EZ_URLALIAS_WILDCARD_TYPE_NONE', 0 );
define( 'EZ_URLALIAS_WILDCARD_TYPE_FORWARD', 1 );
define( 'EZ_URLALIAS_WILDCARD_TYPE_DIRECT', 2 );

class eZURLAlias extends eZPersistentObject
{
    /*!
     Initializes a new URL alias.
    */
    function eZURLAlias( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \reimp
    */
    function &definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "source_url" => array( 'name' => "SourceURL",
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ),
                                         "source_md5" => array( 'name' => "SourceMD5",
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ),
                                         "destination_url" => array( 'name' => "DestinationURL",
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ),
                                         "is_internal" => array( 'name' => "IsInternal",
                                                                 'datatype' => 'integer',
                                                                 'default' => '0',
                                                                 'required' => true ),
                                         "is_wildcard" => array( 'name' => "IsWildcard",
                                                                 'datatype' => 'integer',
                                                                 'default' => '0',
                                                                 'required' => true ),
                                         "forward_to_id" => array( 'name' => "ForwardToID",
                                                                   'datatype' => 'integer',
                                                                   'default' => '0',
                                                                   'required' => true ) ),
                      "keys" => array( "id" ),
                      'function_attributes' => array( 'forward_url' => 'forwardURL' ),
                      "increment_key" => "id",
                      "class_name" => "eZURLAlias",
                      "name" => "ezurlalias" );
    }

    /*!
     \return the url alias object as an associative array with all the attribute values.
    */
    function asArray()
    {
        return array( 'id' => $this->attribute( 'id' ),
                      'source_url' => $this->attribute( 'source_url' ),
                      'source_md5' => $this->attribute( 'source_md5' ),
                      'destination_url' => $this->attribute( 'destination_url' ),
                      'is_internal' => $this->attribute( 'is_internal' ),
                      'is_wildcard' => $this->attribute( 'is_wildcard' ),
                      'forward_to_id' => $this->attribute( 'forward_to_id' ) );
    }

    /*!
     \reimp
    */
    function &attribute( $attributeName )
    {
        if ( $attributeName == "forward_url" )
            return $this->forwardURL();
        else
            return eZPersistentObject::attribute( $attributeName );
    }

    /*!
     \return the URL alias object this URL alias points to or \c null if no such URL exists.
    */
    function &forwardURL()
    {
        $url = null;
        if ( $this->attribute( 'forward_to_id' ) != 0 )
        {
            $url =& eZURLAlias::fetch( $this->attribute( 'forward_to_id' ) );
        }
        return $url;
    }

    /*!
     \static
     Creates a new URL alias with the new URL \a $sourceURL and the original URL \a $destinationURL
     \param $isInternal decides if the url is internal or not (user created).
     \return the URL alias object
    */
    function &create( $sourceURL, $destinationURL, $isInternal = true, $forwardToID = false, $isWildcard = EZ_URLALIAS_WILDCARD_TYPE_NONE )
    {
        if ( !$forwardToID )
            $forwardToID = 0;
        $sourceURL = eZURLAlias::cleanURL( $sourceURL );
        $destinationURL = eZURLAlias::cleanURL( $destinationURL );
        $row = array( 'source_url' => $sourceURL,
                      'destination_url' => $destinationURL,
                      'is_internal' => $isInternal,
                      'is_wildcard' => $isWildcard,
                      'forward_to_id' => $forwardToID );
        $alias = new eZURLAlias( $row );
        return $alias;
    }

    /*!
     Creates a new URL alias which will forward the to this URL alias, the url which will
     be forwarded is \a $forwardURL. The forwarding URL is usually an old url you
     want to work with your new and renamed url.
     The difference between a forwarding and translation is that forwarding will the browser
     and crawlers that the url is no longer in use and give the new one.
     \return the URL alias object
    */
    function &createForForwarding( $forwardURL )
    {
        $alias =& eZURLAlias::create( $forwardURL, $this->attribute( 'destination_url' ),
                                      $this->attribute( 'is_internal' ), $this->attribute( 'id' ) );
        return $alias;
    }

    /*!
     Generates the md5 for the alias and stores the values.
    */
    function store()
    {
        $this->SourceURL = eZURLAlias::cleanURL( $this->SourceURL );
        $this->DestinationURL = eZURLAlias::cleanURL( $this->DestinationURL );
        $this->SourceMD5 = md5( $this->SourceURL );
        eZPersistentObject::store();
    }

    /*!
     Removes this url alias as well as all other aliases that relate to it,
     for instance forwarding aliases.
     \note If you want to remove just this alias you must use remove()
    */
    function cleanup()
    {
        $id = $this->attribute( 'id' );
        $db =& eZDB::instance();
        $sql = "DELETE FROM ezurlalias
 WHERE
     forward_to_id = '" . $db->escapeString( $id ) . "'";
        $db->query( $sql );
        $this->remove();
    }

    /*!
     \static
     Makes sure all aliases which are children of the alias \a $oldPathString is updated
     to have the correct \a $newPathString.
    */
    function updateChildAliases( $newPathString, $oldPathString )
    {
        $oldPathStringLength = strlen( $oldPathString );
        $db =& eZDB::instance();
        $newPathStringText = $db->escapeString( $newPathString );
        $oldPathStringText = $db->escapeString( $oldPathString );
        $subStringQueryPart = $db->subString( 'source_url', $oldPathStringLength + 1 );
        $newPathStringQueryPart = $db->concatString( array( "'$newPathStringText'", $subStringQueryPart ) );
        $sql = "UPDATE ezurlalias
SET
    source_url = $newPathStringQueryPart
WHERE
    is_wildcard = 0 AND
    source_url LIKE '$oldPathStringText/%'";

        $db->query( $sql );

        $subStringQueryPart = $db->subString( 'source_url', $oldPathStringLength + 1 );
        $newPathStringQueryPart = $db->concatString( array( "'$newPathStringText'", $subStringQueryPart ) );
        $destSubStringQueryPart = $db->subString( 'destination_url', $oldPathStringLength + 1 );
        $newDestPathStringQueryPart = $db->concatString( array( "'$newPathStringText'", $destSubStringQueryPart ) );
        $sql = "UPDATE ezurlalias
SET
    source_url = $newPathStringQueryPart, destination_url = $newDestPathStringQueryPart
WHERE
    is_wildcard != 0 AND
    source_url LIKE '$oldPathStringText/%/*' AND
    destination_url LIKE '$oldPathStringText/%/{1}'";

        $db->query( $sql );

        $md5QueryPart = $db->md5( 'source_url' );
        $sql = "UPDATE ezurlalias
SET
    source_md5 = $md5QueryPart
WHERE
    source_url like '$newPathStringText%'";

        $db->query( $sql );
    }

    /*!
     Removes all wildcards that matches the base URL \a $baseURL.
    */
    function cleanupWildcards( $baseURL )
    {
        $db =& eZDB::instance();
        $baseURLText = $db->escapeString( $baseURL . "/*" );
        $sql = "DELETE FROM ezurlalias
WHERE
     source_url = '$baseURLText' AND
     is_wildcard IN ( " . EZ_URLALIAS_WILDCARD_TYPE_FORWARD . ", " . EZ_URLALIAS_WILDCARD_TYPE_DIRECT . ")";
        $db->query( $sql );
    }

    /*!
     Removes forwarding urls where source_url match \a $oldURL.
    */
    function cleanupForwardingURLs( $oldURL )
    {
        $db =& eZDB::instance();
        $oldURLText = $db->escapeString( $oldURL );
        $sql = "DELETE FROM ezurlalias
WHERE
     source_url = '$oldURLText' AND
     forward_to_id != 0";
        $db->query( $sql );
    }

    /*!
     Updates all forwards urls that originally points to \a $oldForwardID
     to point to correct url \a $newForardID.
    */
    function updateForwardID( $newForwardID, $oldForwardID )
    {
        $db =& eZDB::instance();
        $oldForwardIDText = $db->escapeString( $oldForwardID );
        $newForwardIDText = $db->escapeString( $newForwardID );
        $sql = "UPDATE ezurlalias
SET
    forward_to_id = '$newForwardIDText'
WHERE
    forward_to_id = '$oldForwardIDText'";

        $db->query( $sql );
    }

    /*!
     \static
      Fetches the URL alias by ID.
    */
    function &fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZURLAlias::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
    }

    /*!
     \static
      Fetches the URL alias by source URL \a $url.
      \param $isInternal boolean which controls whether internal or external urls are fetched.
      \param $noForwardID boolean which controls whether to only fetch urls without forward id
                          or if forward id it should be ignored.
      \return the URL alias object or \c null
    */
    function &fetchBySourceURL( $url, $isInternal = true, $asObject = true, $noForwardID = true )
    {
        $url = eZURLAlias::cleanURL( $url );
        $conditions = array( "source_url" => $url,
                             'is_wildcard' => 0,
                             'is_internal' => $isInternal );
        if ( $noForwardID )
            $conditions['forward_to_id'] = 0;
        return eZPersistentObject::fetchObject( eZURLAlias::definition(),
                                                null,
                                                $conditions,
                                                $asObject );
    }

    /*!
     \static
      Fetches the URL alias by destination URL \a $url.
      \param $isInternal boolean which controls whether internal or external urls are fetched.
      \return the URL alias object or \c null
    */
    function &fetchByDestinationURL( $url, $isInternal = true, $asObject = true )
    {
        $url = eZURLAlias::cleanURL( $url );
        return eZPersistentObject::fetchObject( eZURLAlias::definition(),
                                                null,
                                                array( "destination_url" => $url,
                                                       'forward_to_id' => 0,
                                                       'is_wildcard' => 0,
                                                       'is_internal' => $isInternal ),
                                                $asObject );
    }

    /*!
     \static
      Fetches non-internal URL alias by offset and limit
    */
    function &fetchByOffset( $offset, $limit, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZURLAlias::definition(),
                                                    null,
                                                    array( "is_internal" => 0 ),
                                                    null,
                                                    array( 'offset' => $offset, 'length' => $limit ),
                                                    $asObject );
    }

    /*!
     \static
      Fetches all wildcards from DB.
    */
    function &fetchWildcards( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZURLAlias::definition(),
                                                    null,
                                                    array( "is_wildcard" => array( array( EZ_URLALIAS_WILDCARD_TYPE_FORWARD, EZ_URLALIAS_WILDCARD_TYPE_DIRECT ) ) ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    /*!
     \return array with information on the wildcard cache.

     The array containst the following keys
     - dir - The directory for the cache
     - file - The filename for the cache
     - path - The entire path (including filename) for the cache
     - keys - Array with key values which is used to uniquely identify the cache
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
        $wildcardCacheFile = "wildcard_$wildcardKey.php";
        $wildcardCachePath = "$wildcardCacheDir/$wildcardCacheFile";
        return array( 'dir' => $wildcardCacheDir,
                      'file' => $wildcardCacheFile,
                      'path' => $wildcardCachePath,
                      'keys' => $keys );
    }

    /*!
     Sets the various cache information to the parameters.
     \sa cacheInfo
    */
    function cacheInfoDirectories( &$wildcardCacheDir, &$wildcardCacheFile, &$wildcardCachePath, &$wildcardKeys )
    {
        $info =& eZURLAlias::cacheInfo();
        $wildcardCacheDir = $info['dir'];
        $wildcardCacheFile = $info['file'];
        $wildcardCachePath = $info['path'];
        $wildcardKeys = $info['keys'];
    }

    /*!
     Goes trough all wildcards in the database and creates the wildcard match cache.
     \sa cacheInfo
    */
    function createWildcardMatches()
    {
        eZURLAlias::cacheInfoDirectories( $wildcardCacheDir, $wildcardCacheFile, $wildcardCachePath, $wildcardKeys );
        if ( !file_exists( $wildcardCacheDir ) )
        {
            eZDir::mkdir( $wildcardCacheDir, eZDir::directoryPermission(), true );
        }

        include_once( 'lib/ezutils/classes/ezphpcreator.php' );
        $phpCache = new eZPHPCreator( $wildcardCacheDir, $wildcardCacheFile );

        foreach ( $wildcardKeys as $wildcardKey => $wildcardKeyValue )
        {
            $phpCache->addComment( "$wildcardKey = $wildcardKeyValue" );
        }
        $phpCache->addSpace();

        $phpCode = "function " . EZURLALIAS_CACHE_FUNCTION . "( &\$uri, &\$urlAlias )\n{\n";

        $wildcards =& eZURLAlias::fetchWildcards();
        $counter = 0;
        foreach ( $wildcards as $wildcard )
        {
            $matchWilcard = $wildcard->attribute( 'source_url' );
            $matchWilcardList = explode( "*", $matchWilcard );
            $matchWildcardCount = count( $matchWilcardList ) - 1;
            $regexpList = array();
            foreach ( $matchWilcardList as $matchWilcardItem )
            {
                $regexpList[] = preg_quote( $matchWilcardItem, '#' );
            }
            $matchRegexp = implode( '(.*)', $regexpList );

            $replaceWildcard = $wildcard->attribute( 'destination_url' );
            $replaceWildcardList = preg_split( "#{([0-9]+)}#", $replaceWildcard, false, PREG_SPLIT_DELIM_CAPTURE );
            $regexpList = array();
            $replaceCounter = 0;
            $replaceCode = "\$uri = ";
            foreach ( $replaceWildcardList as $replaceWildcardItem )
            {
                if ( $replaceCounter > 0 )
                    $replaceCode .= " . ";
                if ( ( $replaceCounter % 2 ) == 0 )
                {
                    $replaceWildcardItemText = $phpCache->variableText( $replaceWildcardItem, 0 );
                    $replaceCode .= "$replaceWildcardItemText";
                }
                else
                {
                    $replaceCode .= "\$matches[$replaceWildcardItem]";
                }
                ++$replaceCounter;
            }
            $replaceRegexp = implode( '', $regexpList );

            $wildcardArray = $wildcard->asArray();

            $phpCode .= "    ";
            if ( $counter > 0 )
                $phpCode .= "else ";
            $phpCode .= "if ( preg_match( \"#^$matchRegexp#\", \$uri, \$matches ) )\n    {\n";
            $phpCode .= "        $replaceCode;\n";
            $phpCode .= "        \$urlAlias = " . $phpCache->variableText( $wildcardArray, 8 + 12, 0, false ) . ";\n";
            $phpCode .= "        return true;\n";
            $phpCode .= "    }\n";

            ++$counter;
        }
        $phpCode .= "    return false;\n";

        $phpCode .= "}\n";

        $phpCache->addCodePiece( $phpCode );
        $phpCache->store();
    }

    /*!
     \return true if the wildcard cache is expired.
    */
    function &isWildcardExpired( $timestamp )
    {
        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler =& eZExpiryHandler::instance();
        if ( !$handler->hasTimestamp( 'urlalias-wildcard' ) )
            return false;
        $expiryTime = $handler->timestamp( 'urlalias-wildcard' );
        if ( $expiryTime > $timestamp )
            return true;
        return false;
    }

    /*!
     Expires the wildcard cache. This causes the wildcard cache to be
     regenerated on the next page load.
    */
    function expireWildcards()
    {
        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler =& eZExpiryHandler::instance();
        $handler->setTimestamp( 'urlalias-wildcard', mktime() );
        $handler->store();
    }

    /*!
     \static
     Transforms the URI if there exists an alias for it.
     \return \c true is if successful, \c false otherwise
     \return The eZURLAlias object of the new url is returned if the translation was found, but the resource has moved.
    */
    function &translateByWildcard( &$uri, $reverse = false )
    {
        if ( get_class( $uri ) == "ezuri" )
        {
            $uriString = $uri->elements();
        }
        else
        {
            $uriString = $uri;
        }
        $uriString = eZURLAlias::cleanURL( $uriString );

        $info =& eZURLAlias::cacheInfo();
        $hasCache = false;
        $isExpired = true;
        $return = false;
        if ( file_exists( $info['path'] ) )
        {
            $timestamp = filemtime( $info['path'] );
            $isExpired = eZURLAlias::isWildcardExpired( $timestamp );
            $hasCache = true;
        }
        if ( $isExpired )
        {
            eZURLAlias::createWildcardMatches();
            $hasCache = true;
        }
        if ( $hasCache )
        {
            include_once( $info['path'] );
            $hasCache = false;
            if ( function_exists( EZURLALIAS_CACHE_FUNCTION ) )
            {
                $hasCache = true;
                $function = EZURLALIAS_CACHE_FUNCTION;
                $hasTranslated = false;
                $url = false;
                $ini =& eZINI::instance();
                $maxIterationCount = $ini->variable( 'URLTranslator', 'MaximumWildcardIterations' );
                $iteration = 0;
                while ( $function( $uriString, $urlAlias ) )
                {
                    $hasTranslated = true;
                    $url =& eZURLAlias::fetchBySourceURL( $uriString, true, true, false );
                    if ( $url )
                        break;
                    ++$iteration;
                    if ( $iteration >= $maxIterationCount )
                        break;
                }
                if ( $hasTranslated )
                {
                    if ( $urlAlias['is_wildcard'] == EZ_URLALIAS_WILDCARD_TYPE_FORWARD )
                    {
                        if ( !$url )
                            $url =& eZURLAlias::fetchBySourceURL( $uriString, true, true, false );
                        if ( $url and $url->attribute( 'forward_to_id' ) != 0 )
                        {
                            $return =& eZURLAlias::fetch( $url->attribute( 'forward_to_id' ) );
                            $uriString = 'error/301';
                        }
                        else if ( $url )
                        {
                            $return =& $url;
                            $uriString = 'error/301';
                        }
                    }
                    else if ( $urlAlias['is_wildcard'] == EZ_URLALIAS_WILDCARD_TYPE_DIRECT )
                    {
                        $return = true;
                    }
                }
            }
        }
        if ( !$hasCache )
            return false;

        if ( get_class( $uri ) == "ezuri" )
        {
            $uri->setURIString( $uriString );
        }
        else
        {
            $uri = $uriString;
        }
        return $return;
    }

    /*!
     \static
      Counts the non-internal URL alias
    */
    function &totalCount( )
    {
        $db =& eZDB::instance();
        $query = "SELECT count(id) AS count
 FROM ezurlalias
 WHERE is_internal = 0";
        $res = $db->arrayQuery( $query );
        return $res[0]['count'];
    }

    /*!
     \static
     Converts the path \a $urlElement into a new alias url which only conists of characters
     in the range a-z, numbers and _.
     All other characters are converted to _.
     \return the converted element

     \example
     'My car' => 'my_car'
     'What is this?' => 'what_is_this'
     'This & that' => 'this_that'
     'myfile.tpl' => 'myfile_tpl',
     'רזו' => '_1'
     \endexample
    */
    function convertToAlias( $urlElement, $defaultValue = false )
    {
        $urlElement = strtolower( $urlElement );
        $urlElement = preg_replace( array( "#[^a-z0-9_ ]#",
                                           "/ /",
                                           "/__+/",
                                           "/^_|_$/" ),
                                    array( " ",
                                           "_",
                                           "_",
                                           "" ),
                                      $urlElement );
        if ( strlen( $urlElement ) == 0 )
        {
            if ( $defaultValue === false )
                $urlElement = '_1';
            else
            {
                $urlElement = $defaultValue;
                $urlElement = strtolower( $urlElement );
                $urlElement = preg_replace( array( "#[^a-z0-9_ ]#",
                                                   "/ /",
                                                   "/__+/",
                                                   "/^_|_$/" ),
                                            array( "",
                                                   "_",
                                                   "_",
                                                   "" ),
                                            $urlElement );
            }
        }
        return $urlElement;
    }

    /*!
     \static
     Converts the path \a $pathURL into a new alias path with limited characters.
     For more information on the conversion see convertToAlias().
     \note each element in the path (separated by / (slash) ) is converted separately.
     \return the converted path
    */
    function convertPathToAlias( $pathURL )
    {
        $result = array();

        $elements = explode( '/', $pathURL );

        foreach ( $elements as $element )
        {
            $element = eZURLAlias::convertToAlias( $element );
            $result[] = $element;
        }

        return implode( '/', $result );
    }

    /*!
     \static
     Transforms the URI if there exists an alias for it.
     \return \c true is if successful, \c false otherwise
     \return The eZURLAlias object of the new url is returned if the translation was found, but the resource has moved.
    */
    function translate( &$uri, $reverse = false )
    {
        if ( get_class( $uri ) == "ezuri" )
        {
            $uriString = $uri->elements();
        }
        else
        {
            $uriString = $uri;
        }
        $uriString = eZURLAlias::cleanURL( $uriString );

        $ini =& eZIni::instance();
        if ( $ini->hasVariable( 'SiteAccessSettings', 'PathPrefix' ) &&
             $ini->variable( 'SiteAccessSettings', 'PathPrefix' ) )
        {
            $prefix = $ini->variable( 'SiteAccessSettings', 'PathPrefix' );
            // Only prepend the path prefix if it's not already in the url.
            if ( !preg_match( "#^$prefix(/.*){0,1}$#", $uriString )  )
            {
                $internalURIString = eZUrlAlias::cleanURL( $ini->variable( 'SiteAccessSettings', 'PathPrefix' ) ) . '/' . $uriString;
            }
            else
            {
                $internalURIString = $uriString;
            }
        }
        else
            $internalURIString = $uriString;

        $db =& eZDB::instance();
        if ( $reverse )
        {
            $query = "SELECT source_url as destination_url, forward_to_id
FROM ezurlalias
WHERE destination_url = '" . $db->escapeString( $internalURIString ) . "' AND
      forward_to_id = 0 AND
      is_wildcard = 0
ORDER BY forward_to_id ASC";
        }
        else
        {
            $query = "SELECT destination_url, forward_to_id
FROM ezurlalias
WHERE source_md5 = '" . md5( $internalURIString ) . "' AND
      is_wildcard = 0
ORDER BY forward_to_id ASC";
        }

        $return = false;
        $urlAliasArray = $db->arrayQuery( $query, array( 'limit' => 1 ) );
        if ( count( $urlAliasArray ) > 0 )
        {
            $uriString = $urlAliasArray[0]['destination_url'];
            if ( $uriString == '' )
                $uriString = '/';

            if ( $urlAliasArray[0]['forward_to_id'] == -1 )
            {
                $uriString = 'error/301';

                $return = $urlAliasArray[0]['destination_url'];
            }
            else if ( $urlAliasArray[0]['forward_to_id'] != 0 )
            {
                $uriString = 'error/301';

                $return = eZURLAlias::fetch( $urlAliasArray[0]['forward_to_id'] );
            }
            else
            {
                $return = true;
            }
        }

        if ( get_class( $uri ) == "ezuri" )
        {
            $uri->setURIString( $uriString );
        }
        else
        {
            $uri = $uriString;
        }
        return $return;
    }

    /*!
     \static
     Makes sure the URL \a $url does not contain leading and trailing slashes (/).
     \return the clean URL
    */
    function cleanURL( $url )
    {
        return trim( $url, '/ ' );
    }
}

?>
