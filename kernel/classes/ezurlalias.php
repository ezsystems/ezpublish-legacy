<?php
//
// Definition of eZURLAlias class
//
// Created on: <01-Aug-2003 16:44:56 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

/*! \file ezurlalias.php
*/

/*!
  \class eZURLAlias ezurlalias.php
  \brief Handles URL aliases in eZ publish

  \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.

  \private
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
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function eZURLAlias( $row )
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     \reimp
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function definition()
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
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
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function asArray()
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     \return the URL alias object this URL alias points to or \c null if no such URL exists.
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function &forwardURL()
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     \static
     Creates a new URL alias with the new URL \a $sourceURL and the original URL \a $destinationURL
     \param $isInternal decides if the url is internal or not (user created).
     \return the URL alias object
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function create( $sourceURL, $destinationURL, $isInternal = true, $forwardToID = false, $isWildcard = EZ_URLALIAS_WILDCARD_TYPE_NONE )
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     Creates a new URL alias which will forward the to this URL alias, the url which will
     be forwarded is \a $forwardURL. The forwarding URL is usually an old url you
     want to work with your new and renamed url.
     The difference between a forwarding and translation is that forwarding will the browser
     and crawlers that the url is no longer in use and give the new one.
     \return the URL alias object
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function &createForForwarding( $forwardURL )
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     Generates the md5 for the alias and stores the values.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function store()
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     Removes this url alias as well as all other aliases that relate to it,
     for instance forwarding aliases.
     \note If you want to remove just this alias you must use remove()
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function cleanup()
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     \static
     Makes sure all aliases which are children of the alias \a $oldPathString is updated
     to have the correct \a $newPathString.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function updateChildAliases( $newPathString, $oldPathString )
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     Removes all wildcards that matches the base URL \a $baseURL.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function cleanupWildcards( $baseURL )
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     Removes forwarding urls where source_url match \a $oldURL.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function cleanupForwardingURLs( $oldURL )
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     Updates all forwards urls that originally points to \a $oldForwardID
     to point to correct url \a $newForardID.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function updateForwardID( $newForwardID, $oldForwardID )
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     \static
      Fetches the URL alias by ID.
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function fetch( $id, $asObject = true )
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     \static
      Fetches the URL alias by source URL \a $url.
      \param $isInternal boolean which controls whether internal or external urls are fetched.
      \param $noForwardID boolean which controls whether to only fetch urls without forward id
                          or if forward id it should be ignored.
      \return the URL alias object or \c null
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function fetchBySourceURL( $url, $isInternal = true, $asObject = true, $noForwardID = true )
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     \static
      Fetches the URL alias by destination URL \a $url.
      \param $isInternal boolean which controls whether internal or external urls are fetched.
      \return the URL alias object or \c null
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function fetchByDestinationURL( $url, $isInternal = true, $asObject = true )
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     \static
      Fetches non-internal URL alias by offset and limit
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function fetchByOffset( $offset, $limit, $asObject = true )
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     \static
      Fetches all wildcards from DB.
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function fetchWildcards( $asObject = true )
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     \return array with information on the wildcard cache.

     The array containst the following keys
     - dir - The directory for the cache
     - file - The filename for the cache
     - path - The entire path (including filename) for the cache
     - keys - Array with key values which is used to uniquely identify the cache
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function cacheInfo()
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     Sets the various cache information to the parameters.
     \sa cacheInfo
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function cacheInfoDirectories( &$wildcardCacheDir, &$wildcardCacheFile, &$wildcardCachePath, &$wildcardKeys )
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     Goes trough all wildcards in the database and creates the wildcard match cache.
     \sa cacheInfo
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function createWildcardMatches()
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     \return true if the wildcard cache is expired.
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function &isWildcardExpired( $timestamp )
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     Expires the wildcard cache. This causes the wildcard cache to be
     regenerated on the next page load.
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function expireWildcards()
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     \static
     Transforms the URI if there exists an alias for it.
     \return \c true is if successful, \c false otherwise
     \return The eZURLAlias object of the new url is returned if the translation was found, but the resource has moved.
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function &translateByWildcard( &$uri, $reverse = false )
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     \static
      Counts the non-internal URL alias
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function &totalCount( )
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     \static
     Converts the path \a $urlElement into a new alias url which only conists of characters
     in the range a-z, numbers and _.
     All other characters are converted to _.
     \return the converted element

     \example
     'My car' => 'My-car'
     'What is this?' => 'What-is-this'
     'This & that' => 'This-that'
     'myfile.tpl' => 'Myfile-tpl',
     'רזו' => 'oeaeaa'
     \endexample
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function convertToAlias( $urlElement, $defaultValue = false )
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
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
     'רזו' => 'oeaeaa'
     \endexample

     \note Provided for creating url alias as they were before 3.10. Also used to make path_identification_string.
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function convertToAliasCompat( $urlElement, $defaultValue = false )
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     \static
     Converts the path \a $pathURL into a new alias path with limited characters.
     For more information on the conversion see convertToAlias().
     \note each element in the path (separated by / (slash) ) is converted separately.
     \return the converted path
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function convertPathToAlias( $pathURL )
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     \static
     Transforms the URI if there exists an alias for it.
     \return \c true is if successful, \c false otherwise
     \return The eZURLAlias object of the new url is returned if the translation was found, but the resource has moved.
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function translate( &$uri, $reverse = false )
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }

    /*!
     \static
     Makes sure the URL \a $url does not contain leading and trailing slashes (/).
     \return the clean URL
     \deprecated This class has been deprecated and disabled in eZ publish 3.10, please use eZURLAliasML for future alias handling.
    */
    function cleanURL( $url )
    {
        die( __CLASS__ . "::" . __FUNCTION__ ." is deprecated, use the class eZURLAliasML instead" );
    }
}

?>
