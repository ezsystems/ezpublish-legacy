<?php
/**
 * File containing the eZTemplateCacheBlock class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZTemplateCacheBlock eztemplatecacheblock.php
  \brief Cache block

*/

class eZTemplateCacheBlock
{
    /*!
     Helper function for setting the expired flag to 1 to all cache block files related to the given cache block name and (optional) node id. 

     \param $name Name of the cache block which should be expired.
     \param $nodeID Optional the node id of which the named cache block should be expired.


     Example of usage:
     \code
        eZTemplateCacheBlock::expireCacheByName( $name, $nodeID);
     \endcode

     Note: If only the $name is given all files of the named cache block will be expired. 
           If a $nodeID is given only the file related to name and node id will be expired.

     */
    static function expireCacheByName( $name, $nodeID = false )
    {
        if ( $name == '' )
        {
            return;
        }

        $cachePath = eZTemplateCacheBlock::cachePath( eZTemplateCacheBlock::keyString( $keys ), $nodeID, $name );

        $phpPath = eZTemplateCacheBlock::templateBlockCacheDir() . '/';

        if ( is_numeric( $nodeID ) )
        {
            $phpPath .= eZTemplateCacheBlock::subtreeCacheSubDirForNode( $nodeID, $name ) . '/';
        }
        else
        {
            $phpPath .= $name . '/';
        }

        $phpPath .= $filename;

        $fileHandler = eZClusterFileHandler::instance($phpPath);

        $fileHandler->fileDelete( $phpPath, '' );
    }


    /*!
     Helper function for retrieving a cache-block entry which can be used by any custom code.

     \param $keys Array or string which is used for key. To ensure uniqueness prefix or add an entry which is unique to your code.
     \param $subtreeExpiry The subtree expiry value, use null to disable or a string. See subtreeCacheSubDir for more details.
     \param $ttl Amount of seconds the cache should live, use null, 0 or -1 to disable TTL.
     \param $useGlobalExpiry Boolean which controls if the global content expiry value should be used or not.

     Returns an array with the file handler objects as the first entry and the content data as the second.
     If the content could not be retrieved the content contains the object eZClusterFileFailure.


     Example of usage:
     \code
     list($handler, $data) = eZTemplateCacheBlock::retrieve( array( 'my_cool_key', $id, ), $nodeID, 60 ); // lives 60 seconds
     if ( !$data instanceof eZClusterFileFailure )
     {
         echo $data;
     }
     else
     {
         // ... generate the data
         $data = '...';
         $handler->storeCache( array( 'scope'      => 'template-block',
                                      'binarydata' => $data ) );
     }
     \endcode

     Note: Because of the cluster code the storeCache() call must occur to ensure stability.

     */
    static function retrieve( $keys, $subtreeExpiry, $ttl, $useGlobalExpiry = true, $name = '' )
    {
        $keys = (array)$keys;
        self::filterKeys( $keys );
        $nodeID = $subtreeExpiry ? eZTemplateCacheBlock::decodeNodeID( $subtreeExpiry ) : false;
        $cachePath = eZTemplateCacheBlock::cachePath( eZTemplateCacheBlock::keyString( $keys ), $nodeID, $name );
        return eZTemplateCacheBlock::handle( $cachePath, $nodeID, $ttl, $useGlobalExpiry );
    }

    /**
     * Filters cache keys when needed.
     * Useful to avoid having current URI as a cache key if an error has occurred and has been caught by error module.
     *
     * @param array $keys
     */
    static private function filterKeys( array &$keys )
    {
        if ( isset( $GLOBALS['eZRequestError'] ) && $GLOBALS['eZRequestError'] === true )
        {
            $requestUri = eZSys::requestURI();
            foreach ( $keys as $i => &$key )
            {
                if ( is_array( $key ) )
                {
                    self::filterKeys( $key );
                }
                else if ( $key === $requestUri )
                {
                    unset( $keys[$i] );
                }
            }
        }
    }

    /*!
     \static
     Helper function for the compiled code, similar to retrieve() but requires the $cachePath to be calculated up front.
     */
    static function handle( $cachePath, $nodeID, $ttl, $useGlobalExpiry = true )
    {
        $globalExpiryTime = -1;
        if ( $useGlobalExpiry )
        {
            $globalExpiryTime = eZExpiryHandler::getTimestamp( 'template-block-cache', -1 );
        }

        $cacheHandler = eZClusterFileHandler::instance( $cachePath );

        $subtreeExpiry = -1;
        // Perform an extra check if the DB handler is in use,
        // get the modified_subnode value from the specified node ($nodeID)
        // and use it as an extra expiry value.
        if ( $cacheHandler instanceof eZDFSFileHandler )
        {
            $subtreeExpiry = eZTemplateCacheBlock::getSubtreeModification( $nodeID );
        }
        $globalExpiryTime = max( eZExpiryHandler::getTimestamp( 'global-template-block-cache', -1 ), // This expiry value is the true global expiry for cache-blocks
                                 $globalExpiryTime,
                                 $subtreeExpiry );

        if ( $ttl == 0 )
            $ttl = -1;
        return array( &$cacheHandler,
                      $cacheHandler->processCache( array( 'eZTemplateCacheBlock', 'retrieveContent' ), null,
                                                   $ttl, $globalExpiryTime ) );
    }

    /*!
     Figures out the modification time for the subtree by looking up the database using $nodeID.
     If $nodeID is set to false no lookup is done and it will return -1.
     */
    static function getSubtreeModification( $nodeID )
    {
        if ( $nodeID === false )
            return -1;
        $nodeID = (int)$nodeID;
        $sql = "SELECT modified_subnode FROM ezcontentobject_tree WHERE node_id=$nodeID";
        $db = eZDB::instance();
        $rows = $db->arrayQuery( $sql );
        if ( count( $rows ) > 0 )
            return $rows[0]['modified_subnode'];
        return -1;
    }

    /*!
     \static
     Calculates the key entry for the function placement array $functionPlacement and returns it.

     \note This function is placed in this class to reduce the need to load the class eZTemplateCacheFunction
           when the templates are compiled. This reduces memory usage.
     */
    static function placementString( $functionPlacement )
    {
        $placementString =  $functionPlacement[0][0] . "_";
        $placementString .= $functionPlacement[0][1] . "_";
        $placementString .= $functionPlacement[1][0] . "_";
        $placementString .= $functionPlacement[1][1] . "_";
        $placementString .= $functionPlacement[2];
        return $placementString;
    }

    /*!
     \static
     Calculates the key string from the key values $keys.

     Note: Arrays are traversed recursively.
     */
    static function keyString( $keys )
    {
        return serialize( $keys );
    }

    /*!
     \static
     Calculates the cache path based on the key string $keyString and $nodeID.

     See subtreeCacheSubDir() for more details on the $nodeID parameter.
     */
    static function cachePath( $keyString, $nodeID = false, $name = '' )
    {
        $filename = md5( $keyString ) . ".cache";

        $phpDir = eZTemplateCacheBlock::templateBlockCacheDir();
        if ( is_numeric( $nodeID ) )
        {
             $phpDir .=  eZTemplateCacheBlock::calculateSubtreeCacheDir( $nodeID, $filename, $name );
        }
        else
        {
            if ( $name )
            {
                $phpDir .= $name . '/' . $filename[0] . '/' . $filename[1] . '/' . $filename[2];
            }
            else
            {
                $phpDir .= $filename[0] . '/' . $filename[1] . '/' . $filename[2];
            }
        }

        $phpPath = $phpDir . '/' . $filename;
        return $phpPath;
    }

    /*!
     \static
     Returns base directory where template block caches are stored.
    */
    static function templateBlockCacheDir()
    {
        $cacheDir = eZSys::cacheDirectory() . '/template-block/';
        return $cacheDir;
    }

    /*!
     \static
     Figures out the node ID for the $subtreeExpiryParameter.

     The parameter $subtreeExpiryParameter is expiry value is usually taken from the template operator and can be one of:
     - A numerical value which represents the node ID (the fastest approach)
     - A string containing 'content/view/full/xxx' where xx is the node ID number, the number will be extracted.
     - A string containing a nice url which will be decoded into a node ID using the database (slowest approach).
    */
    static function decodeNodeID( $subtreeExpiryParameter )
    {
        $nodeID = false;
        if ( !is_numeric( $subtreeExpiryParameter ) )
        {
            $nodePathString = '';

            // clean up $subtreeExpiryParameter
            $subtreeExpiryParameter = trim( $subtreeExpiryParameter, '/' );

            $nodeID = false;
            $subtree = $subtreeExpiryParameter;

            if ( $subtree == '' )
            {
                // 'subtree_expiry' is empty => use root node.
                $nodeID = 2;
            }
            else
            {
                $nonAliasPath = 'content/view/full/';

                if ( strpos( $subtree, $nonAliasPath ) === 0 )
                {
                    // 'subtree_expiry' is like 'content/view/full/2'
                    $nodeID = (int)substr( $subtree, strlen( $nonAliasPath ) );
                }
                else
                {
                    // 'subtree_expiry' is url_alias
                    $nodeID = eZURLAliasML::fetchNodeIDByPath( $subtree );
                    if ( !$nodeID )
                    {
                        eZDebug::writeError( "Could not find path_string '$subtree' for 'subtree_expiry' node.", __METHOD__ );
                    }
                    else
                    {
                        $nodeID = (int)$nodeID;
                    }
                }
            }
        }
        else
        {
            $nodeID = (int)$subtreeExpiryParameter;
        }
        return $nodeID;
    }

    /*!
     \static
     Returns path of the directory where 'subtree_expiry' caches are stored.

     See decodeNodeID() for details on the $subtreeExpiryParameter parameter.
    */
    static function calculateSubtreeCacheDir( $nodeID, $cacheFilename, $name = '' )
    {
        $cacheDir = eZTemplateCacheBlock::subtreeCacheSubDirForNode( $nodeID, $name );
        $cacheDir .= '/' . $cacheFilename[0] . '/' . $cacheFilename[1] . '/' . $cacheFilename[2];

        return $cacheDir;
    }

    /*!
     \static
     Returns path of the directory where 'subtree_expiry' caches are stored.

     See decodeNodeID() for details on the $subtreeExpiryParameter parameter.

     \note If you know the node ID you can use calculateSubtreeCacheDir() instead.
    */
    static function subtreeCacheSubDir( $subtreeExpiryParameter, $cacheFilename )
    {
        $nodeID = eZTemplateCacheBlock::decodeNodeID( $subtreeExpiryParameter );
        return eZTemplateCacheBlock::calculateSubtreeCacheDir( $nodeID, $cacheFilename );
    }

    /*!
     \static
     Builds and returns path from $nodeID, e.g. if $nodeID = 23 then path = subtree/2/3
    */
    static function subtreeCacheSubDirForNode( $nodeID, $name = '' )
    {
        $cacheDir = eZTemplateCacheBlock::subtreeCacheBaseSubDir();

        if ( $name != '' )
        {
            $cacheDir =  $name . '/' . $cacheDir;
        }

        if ( is_numeric( $nodeID ) )
        {
            $nodeID = (string)$nodeID;
            $length = strlen( $nodeID );
            $pos = 0;
            while ( $pos < $length )
            {
                $cacheDir .= '/' . $nodeID[$pos];
                ++$pos;
            }
        }
        else
        {
            eZDebug::writeWarning( "Unable to determine cacheDir for nodeID = $nodeID", __METHOD__ );
        }

        $cacheDir .= '/cache';
        return $cacheDir;
    }

    /*!
     \static
     Returns base directory where 'subtree_expiry' caches are stored.
    */
    static function subtreeCacheBaseSubDir()
    {
        return 'subtree';
    }

    /*!
     \static
     Callback function to get the contents of the specified filename.

     \param $fname Name of file
     \param $mtime Modified time of file.
     */
    static function retrieveContent( $fname, $mtime )
    {
        return file_get_contents( $fname );
    }
}

?>
