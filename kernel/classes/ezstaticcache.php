<?php
//
// Definition of eZStaticClass class
//
// Created on: <12-Jan-2005 10:29:21 dr>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( 'lib/ezutils/classes/ezini.php' );

class eZStaticCache
{
    function eZStaticCache()
    {
        $ini =& eZINI::instance( 'staticcache.ini');
        $this->HostName = $ini->variable( 'CacheSettings', 'HostName' );
        $this->StaticStorageDir = $ini->variable( 'CacheSettings', 'StaticStorageDir' );
        $this->MaxCacheDepth = $ini->variable( 'CacheSettings', 'MaxCacheDepth' );
        $this->CachedURLArray = $ini->variable( 'CacheSettings', 'CachedURLArray' );
        $this->CachedSiteAccesses = $ini->variable( 'CacheSettings', 'CachedSiteAccesses' );
        $this->AlwaysUpdate = $ini->variable( 'CacheSettings', 'AlwaysUpdateArray' );
    }

    function generateAlwaysUpdatedCache()
    {
        $hostname = $this->HostName;
        $staticStorageDir = $this->StaticStorageDir;

        foreach ( $this->AlwaysUpdate as $uri )
        {
            $this->storeCache( $uri, $hostname, $staticStorageDir, array(), false );
        }
    }

    function cacheURL( $url, $nodeID = false, $skipExisting = false )
    {
        // Set default hostname
        $hostname = $this->HostName;
        $staticStorageDir = $this->StaticStorageDir;

        // Check if URL should be cached
        if ( substr_count( $url, "/") >= $this->MaxCacheDepth )
            return false;

        $doCacheURL = false;
        foreach ( $this->CachedURLArray as $cacheURL )
        {
            if ( $url == $cacheURL )
            {
                $doCacheURL = true;
            }
            else if ( strpos( $cacheURL, '*') !== false )
            {
                if ( strpos( $url, str_replace( '*', '', $cacheURL ) ) === 0 )
                {
                    $doCacheURL = true;
                }
            }
        }

        if ( $doCacheURL == false )
        {
            return false;
        }

        $this->storeCache( $url, $hostname, $staticStorageDir, $nodeID ? array( "/content/view/full/$nodeID" ) : array(), $skipExisting );

        return true;
    }

    function storeCache( $url, $hostname, $staticStorageDir, $alternativeStaticLocations = array(), $skipUnlink = false )
    {
        if ( is_array( $this->CachedSiteAccesses ) and count ( $this->CachedSiteAccesses ) )
        {
            $dirs = array();
            foreach ( $this->CachedSiteAccesses as $dir )
            {
                $dirs[] = '/' . $dir ;
            }
        }
        else
        {
            $dirs = array ('');
        }

        foreach ( $dirs as $dir )
        {
            $cacheFiles = array();
            if ( !is_dir( $dir ) )
         	{
                eZDir::mkdir( $dir, 0777, true );
            }

            $cacheFiles[] = $this->buildCacheFilename( $staticStorageDir, $dir . $url );
            foreach ( $alternativeStaticLocations as $location )
            {
                $cacheFiles[] = $this->buildCacheFilename( $staticStorageDir, $dir . $location );
            }
            /* Get rid of cache files */
            if ( !$skipUnlink )
            {
                foreach ( $cacheFiles as $file )
                {
                    if ( file_exists ( $file ) )
                    {
                        unlink( $file );
                    }
                }
            }

            /* Generate new content */
            $fileName = "http://$hostname$dir$url";
            $content = @file_get_contents( $fileName );

            /* Store new content */
            if ( $content !== false )
            {
                foreach ( $cacheFiles as $file )
                {
                    if ( !file_exists( $file ) )
                    {
                        $this->storeCachedFile( $file, $content );
                    }
                }
            }
        }
    }

    function buildCacheFilename( $staticStorageDir, $url )
    {
        $file = "{$staticStorageDir}{$url}/index.html";
        $file = preg_replace( '#//+#', '/', $file );
        return $file;
    }

    function storeCachedFile( $file, $content )
    {
        $dir = dirname( $file );
        if ( !is_dir( $dir ) )
        {
            eZDir::mkdir( $dir, 0777, true );
        }

        $oldumask = umask( 0 );

        $tmpFileName = $file . '.' . md5( $file. uniqid( "ezp". getmypid(), true ) );

        /* Remove files, this might be necessary for Windows */
        @unlink( $tmpFileName );
        @unlink( $file);

        /* Write the new cache file with the data attached */
        $fp = fopen( $tmpFileName, 'w' );
        if ( $fp )
        {
            fwrite( $fp, $content . '<!-- Generated: '. date( 'Y-m-d H:i:s' ). ' -->' );
            fclose( $fp );
            rename( $tmpFileName, $file );
        }

        umask( $oldumask );
    }

    function removeURL( $url )
    {
        if ( $url == "/" )
            $dir = $this->StaticStorageDir . $url;
        else
            $dir = $this->StaticStorageDir . $url . "/";

        @unlink( $dir . "/index.html" );
        @rmdir( $dir );
    }

    function &cachedURLArray()
    {
        return $this->CachedURLArray;
    }

    function generateCache( $force = false, $quiet = false )
    {
        $staticURLArray = $this->cachedURLArray();
        $db =& eZDB::instance();
        foreach ( $staticURLArray as $url )
        {
            if ( strpos( $url, '*') === false )
            {
                if ( !$quiet )
                    print( "caching: $url " );
                $this->cacheURL( $url, false, !$force );
                if ( !$quiet )
                    print( "done\n" );
            }
            else
            {
                if ( !$quiet )
                    print( "wildcard cache: $url\n" );
                $queryURL = ltrim( str_replace( '*', '%', $url ), '/' );

                $aliasArray = $db->arrayQuery( "SELECT source_url, destination_url FROM ezurlalias WHERE source_url LIKE '$queryURL' AND source_url NOT LIKE '%*' ORDER BY source_url" );
                foreach ( $aliasArray as $urlAlias )
                {
                    $url = "/" . $urlAlias['source_url'];
                    preg_match( '/([0-9]+)$/', $urlAlias['destination_url'], $matches );
                    $id = $matches[1];
                    if ( $this->cacheURL( $url, (int) $id, !$force ) )
                    {
                        if ( !$quiet )
                            print( "  cache $url\n" );
                    }
                }

                if ( !$quiet )
                    print( "done\n" );
            }
        }
    }

    var $HostName;
    var $StaticStorage;
    var $MaxCacheDepth;

    var $CachedURLArray;
}

?>
