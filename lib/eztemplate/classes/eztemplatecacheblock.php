<?php
//
// Definition of eZTemplateCacheBlock class
//
// Created on: <27-Mar-2007 11:20:14 amos>
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

/*!
  \class eZTemplateCacheBlock eztemplatecacheblock.php
  \brief Cache block

*/

class eZTemplateCacheBlock
{
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
     if ( get_class( $data ) != 'ezclusterfilefailure' )
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
    function retrieve( $keys, $subtreeExpiry, $ttl, $useGlobalExpiry = true )
    {
        $nodeID = $subtreeExpiry ? eZTemplateCacheBlock::decodeNodeID( $subtreeExpiry ) : false;
        $cachePath = eZTemplateCacheBlock::cachePath( eZTemplateCacheBlock::keyString( $keys ), $nodeID );
        return eZTemplateCacheBlock::handle( $cachePath, $nodeID, $ttl, $useGlobalExpiry );
    }

    /*!
     \static
     Helper function for the compiled code, similar to retrieve() but requires the $cachePath to be calculated up front.
     */
    function handle( $cachePath, $nodeID, $ttl, $useGlobalExpiry = true )
    {
        $globalExpiryTime = -1;
        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        if ( $useGlobalExpiry )
        {
            $globalExpiryTime = eZExpiryHandler::getTimestamp( 'template-block-cache', -1 );
        }

        require_once( 'kernel/classes/ezclusterfilehandler.php' );
        $cacheHandler = eZClusterFileHandler::instance( $cachePath );

        $subtreeExpiry = -1;
        // Perform an extra check if the DB handler is in use,
        // get the modified_subnode value from the specified node ($nodeID)
        // and use it as an extra expiry value.
        if ( get_class( $cacheHandler ) == 'ezdbfilehandler' )
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
    function getSubtreeModification( $nodeID )
    {
        if ( $nodeID === false )
            return -1;
        $nodeID = (int)$nodeID;
        $sql = "SELECT modified_subnode FROM ezcontentobject_tree WHERE node_id=$nodeID";
        include_once( 'lib/ezdb/classes/ezdb.php' );
        $db =& eZDB::instance();
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
    function placementString( $functionPlacement )
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
    function keyString( $keys )
    {
        return serialize( $keys );
    }

    /*!
     \static
     Calculates the cache path based on the key string $keyString and $nodeID.

     See subtreeCacheSubDir() for more details on the $nodeID parameter.
     */
    function cachePath( $keyString, $nodeID = false )
    {
        include_once( 'lib/ezutils/classes/ezsys.php' );
        $filename = eZSys::ezcrc32( $keyString ) . ".cache";

        $phpDir = eZTemplateCacheBlock::templateBlockCacheDir();
        if ( is_numeric( $nodeID ) )
        {
            $phpDir .= eZTemplateCacheBlock::calculateSubtreeCacheDir( $nodeID, $filename );
        }
        else
        {
            $phpDir .= $filename[0] . '/' . $filename[1] . '/' . $filename[2];
        }

        $phpPath = $phpDir . '/' . $filename;
        return $phpPath;
    }

    /*!
     \static
     Returns base directory where template block caches are stored.
    */
    function templateBlockCacheDir()
    {
        $cacheDir = eZSys::cacheDirectory() . '/template-block/' ;
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
    function decodeNodeID( $subtreeExpiryParameter )
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
                    include_once( 'lib/ezdb/classes/ezdb.php' );
                    $db =& eZDB::instance();
                    // 'subtree_expiry' is url_alias
                    $nodePathStringSQL = "SELECT node_id FROM ezcontentobject_tree WHERE path_identification_string='" . $db->escapeString( $subtree ) . "'";
                    $nodes = $db->arrayQuery( $nodePathStringSQL );
                    if ( count( $nodes ) != 1 )
                    {
                        eZDebug::writeError( "Could not find path_string '$subtree' for 'subtree_expiry' node.", 'eZTemplateCacheBlock::subtreeExpiryCacheDir()' );
                    }
                    else
                    {
                        $nodeID = (int)$nodes[0]['node_id'];
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
    function calculateSubtreeCacheDir( $nodeID, $cacheFilename )
    {
        $cacheDir = eZTemplateCacheBlock::subtreeCacheSubDirForNode( $nodeID );
        $cacheDir .= '/' . $cacheFilename[0] . '/' . $cacheFilename[1] . '/' . $cacheFilename[2];

        return $cacheDir;
    }

    /*!
     \static
     Returns path of the directory where 'subtree_expiry' caches are stored.

     See decodeNodeID() for details on the $subtreeExpiryParameter parameter.

     \note If you know the node ID you can use calculateSubtreeCacheDir() instead.
    */
    function subtreeCacheSubDir( $subtreeExpiryParameter, $cacheFilename )
    {
        $nodeID = eZTemplateCacheBlock::decodeNodeID( $subtreeExpiryParameter );
        return eZTemplateCacheBlock::calculateSubtreeCacheDir( $nodeID, $cacheFilename );
    }

    /*!
     \static
     Builds and returns path from $nodeID, e.g. if $nodeID = 23 then path = subtree/2/3
    */
    function subtreeCacheSubDirForNode( $nodeID )
    {
        $cacheDir = eZTemplateCacheBlock::subtreeCacheBaseSubDir();

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
            eZDebug::writeWarning( "Unable to determine cacheDir for nodeID = $nodeID", 'eZtemplateCacheFunction::subtreeCacheSubDirForNode' );
        }

        $cacheDir .= '/cache';
        return $cacheDir;
    }

    /*!
     \static
     Returns base directory where 'subtree_expiry' caches are stored.
    */
    function subtreeCacheBaseSubDir()
    {
        return 'subtree';
    }

    /*!
     \static
     Callback function to get the contents of the specified filename.

     \param $fname Name of file
     \param $mtime Modified time of file.
     */
    function retrieveContent( $fname, $mtime )
    {
        return file_get_contents( $fname );
    }
}

?>
