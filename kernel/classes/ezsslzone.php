<?php
//
// Definition of eZSSLZone class
//
// Created on: <12-Jul-2005 13:01:07 vs>
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


/*!
 \class eZSSLZone ezsslzone.php
 \brief SSL zones handling functionality.

 Using functionality of this class you can mark certain parts of you site
 as "SSL zones". After that users will be able to access those parts only over SSL.
 When entering an SSL zone, user will be automatically switched to SSL.
 When leaving an SSL zone, user will be automatically switched to plain HTTP.
 Such a switch is called "access mode change" in the comments below.

 SSL zones may be defined on either module/view basis, or on subtree basis.

 For more details pleaase see doc/feautures/3.8/ssl_zones.txt
*/

class eZSSLZone
{
    const DEFAULT_SSL_PORT = 443;

    /*! \privatesection */

    /**
     * \static
     * Returns true if the SSL zones functionality is enabled, false otherwise.
     * The result is cached in memory to save time on multiple invocations.
     */
    static function enabled()
    {
        if ( isset( $GLOBALS['eZSSLZoneEnabled'] ) )
            return $GLOBALS['eZSSLZoneEnabled'];

        $enabled = false;
        $ini = eZINI::instance();
        if ( $ini->hasVariable( 'SSLZoneSettings', 'SSLZones' ) )
            $enabled = ( $ini->variable( 'SSLZoneSettings', 'SSLZones' ) == 'enabled' );

        return $GLOBALS['eZSSLZoneEnabled'] = $enabled;
    }

    /**
     * \static
     */
    static function cacheFileName()
    {
        return eZDir::path( array( eZSys::cacheDirectory(), 'ssl_zones_cache.php' ) );
    }

    /**
     * \static
     */
    static function clearCacheIfNeeded()
    {
        if ( eZSSLZone::enabled() )
            eZSSLZone::clearCache();
    }

    /**
     * \static
     */
    static function clearCache()
    {
        eZDebugSetting::writeDebug( 'kernel-ssl-zone', 'Clearing caches.' );

        // clear in-memory cache
        unset( $GLOBALS['eZSSLZonesCachedPathStrings'] );

        // and remove cache file
        $cacheFileName = eZSSLZone::cacheFileName();
        if ( file_exists( $cacheFileName ) )
            unlink( $cacheFileName );
    }

    /**
     * \static
     * Load content SSL zones definitions.
     * Substitute URIs with corresponding path strings
     * (e.g. "/news" would be subsituted with "/1/2/50").
     * The result is cached in memory to save time on multiple invocations.
     * It is also saved in a cache file that is usually updated by eZContentCacheManager along with content cache.
     */
    static function getSSLZones()
    {
        if ( !isset( $GLOBALS['eZSSLZonesCachedPathStrings'] ) ) // if in-memory cache does not exist
        {
            $cacheFileName = eZSSLZone::cacheFileName();
            $cacheDirName = eZSys::cacheDirectory();

            // if file cache does not exist then create it
            if ( !is_readable( $cacheFileName ) )
            {
                $ini = eZINI::instance();
                $sslSubtrees = $ini->variable( 'SSLZoneSettings', 'SSLSubtrees' );

                if ( !isset( $sslSubtrees ) || !$sslSubtrees )
                    return array();

                // if there are some content SSL zones defined in the ini settings
                // then let's calculate path strings for them
                $pathStringsArray = array();
                foreach ( $sslSubtrees as $uri )
                {
                    $elements = eZURLAliasML::fetchByPath( $uri );
                    if ( count( $elements ) == 0 )
                    {
                        eZDebug::writeError( "Cannot fetch URI '$uri'", 'eZSSLZone::getSSLZones' );
                        continue;
                    }
                    $action = $elements[0]->attribute( 'action' );
                    if ( !preg_match( "#^eznode:(.+)#", $action, $matches ) )
                    {
                        eZDebug::writeError( "Cannot decode action '$action' for URI '$uri'", 'eZSSLZone::getSSLZones' );
                        continue;
                    }
                    $nodeID = (int)$matches[1];
                    $node =& eZContentObjectTreeNode::fetch( $nodeID );
                    if ( !is_object( $node ) )
                    {
                        eZDebug::writeError( "cannot fetch node by URI '$uri'", 'eZSSLZone::getSSLZones' );
                        continue;
                    }
                    $pathStringsArray[$uri] = $node->attribute( 'path_string' );
                    unset( $node );
                }

                // write calculated path strings to the file
                if ( !file_exists( $cacheDirName ) )
                {
                    eZDir::mkdir( $cacheDirName, false, true );
                }

                $fh = fopen( $cacheFileName, 'w' );
                if ( $fh )
                {
                    fwrite( $fh, "<?php\n\$pathStringsArray = " . var_export( $pathStringsArray, true ) . ";\n?>" );
                    fclose( $fh );

                    $perm = eZINI::instance()->variable( 'FileSettings', 'StorageFilePermissions' );
                    chmod( $cacheFileName, octdec( $perm ) );
                }

                return $GLOBALS['eZSSLZonesCachedPathStrings'] = $pathStringsArray;
            }
            else // if the cache file exists
            {
                // let's read its contents and return them
                include_once( $cacheFileName ); // stores array to $pathStringsArray
                return $GLOBALS['eZSSLZonesCachedPathStrings'] = $pathStringsArray;
            }
        }

        // else if in-memory cache already exists then return its contents
        $pathStringsArray = $GLOBALS['eZSSLZonesCachedPathStrings'];

        return $pathStringsArray;
    }

    /**
     * \static
     * Checks if a given module/view pair is in the given list of views.
     * Wildcard matching on view name is done.
     *
     * \return 2        if wildcard match occurs on the given view
     *         1        if exact match occurs on the given view
     *         0        if the view is not found in the list
     */
    static function viewIsInArray( $module, $view, $moduleViews )
    {
        if ( in_array( "$module/$view", $moduleViews ) )
            return 2;
        if ( in_array( "$module/*", $moduleViews ) )
            return 1;
        return 0;
    }

    /**
     * \static
     * \return true if the view is defined as 'keep'
     */
    static function isKeepModeView( $module, $view )
    {
        $ini = eZINI::instance();
        $viewsModes  = $ini->variable( 'SSLZoneSettings', 'ModuleViewAccessMode' );
        $sslViews      = array_keys( $viewsModes, 'ssl' );
        $keepModeViews = array_keys( $viewsModes, 'keep' );

        if ( eZSSLZone::viewIsInArray( $module, $view, $keepModeViews ) <
             eZSSLZone::viewIsInArray( $module, $view, $sslViews ) )
        {
            eZDebugSetting::writeDebug( 'kernel-ssl-zone', 'The view cannot choose access mode itself.' );
            return false;
        }

        return true;
    }

    /**
     * \static
     * \param  $inSSL  The desired access mode.
     *
     * Change access mode (HTTP/HTTPS):
     * - If previous mode was HHTP but $inSSL is true, we switch to SSL.
     * - If previous mode was SSL  but $inSSL is false, we switch to HTTP.
     * - Otherwise no mode change is occured.
     *
     * Mode change is done by redirect to the same URL, but with changed
     * protocol (http/https) and TCP port.
     *
     * In case of mode change this method does not return (exit() is called).
     */
    static function switchIfNeeded( $inSSL )
    {
        // if it's undefined whether we should redirect  we do nothing
        if ( !isset( $inSSL ) )
            return;

        // $nowSSl is true if current access mode is HTTPS.
        $nowSSL = eZSys::isSSLNow();

        $requestURI = eZSys::requestURI();
        $indexDir = eZSys::indexDir( false );

        $sslZoneRedirectionURL = false;
        if ( $nowSSL && !$inSSL )
        {
            // switch to plain HTTP
            $ini = eZINI::instance();
            $host = $ini->variable( 'SiteSettings', 'SiteURL' );
            $sslZoneRedirectionURL = "http://" . $host . $indexDir . $requestURI;
        }
        elseif ( !$nowSSL && $inSSL )
        {
            // switch to HTTPS
            $host = eZSys::serverVariable( 'HTTP_HOST' );
            $host = preg_replace( '/:\d+$/', '', $host );

            $ini = eZINI::instance();
            $sslPort = $ini->variable( 'SiteSettings', 'SSLPort' );
            $sslPortString = ( $sslPort == eZSSLZone::DEFAULT_SSL_PORT ) ? '' : ":$sslPort";
            $sslZoneRedirectionURL = "https://" . $host  . $sslPortString . $indexDir . $requestURI;
        }

        if ( $sslZoneRedirectionURL ) // if a redirection URL is found
        {
            eZDebugSetting::writeDebug( 'kernel-ssl-zone', "redirecting to [$sslZoneRedirectionURL]" );
            eZHTTPTool::redirect( $sslZoneRedirectionURL );
            eZExecution::cleanExit();
        }
    }

    /*! \publicsection */

    /**
     * \static
     * Check whether the given node should cause access mode change.
     * It it should, this method does not return.
     *
     * \see checkNode()
     */
    static function checkNodeID( $module, $view, $nodeID )
    {
        if ( !eZSSLZone::enabled() )
            return;

        /* If the given module/view is not in the list of 'keep mode' views,
         * i.e. it cannot choose access mode itself,
         * then do nothing.
         */
        if ( !eZSSLZone::isKeepModeView( $module, $view ) )
            return;

        // Fetch path string for the given node.
        $pathStrings = eZPersistentObject::fetchObjectList(
            eZContentObjectTreeNode::definition(), // def
            array( 'path_string' ),                // field_filters
            array( 'node_id' => $nodeID ),         // conds
            null,                                  // sorts
            null,                                  // limit
            false                                  // asObject
        );

        if ( !$pathStrings )
        {
            eZDebug::writeError( "Node #$nodeID not found", "eZSSLZone::checkNodeID" );
            return;
        }

        eZSSLZone::checkNodePath( $module, $view, $pathStrings[0]['path_string'] );
    }

    /**
     * \static
     * Check whether the given node should cause access mode change.
     * It it should, this method does not return.
     */
    static function checkNode( $module, $view, &$node, $redirect = true )
    {
        if ( !eZSSLZone::enabled() )
            return;

        /* If the given module/view is not in the list of 'keep mode' views,
         * i.e. it cannot choose access mode itself,
         * then do nothing.
         */
        if ( !$redirect && !eZSSLZone::isKeepModeView( $module, $view ) )
            return;

        $pathString = $node->attribute( 'path_string' );

        return eZSSLZone::checkNodePath( $module, $view, $pathString, $redirect );
    }

    /**
     * \static
     * Check whether the given node should cause access mode change.
     * It it should, this method does not return.
     */
    static function checkNodePath( $module, $view, $pathString, $redirect = true )
    {
        if ( !eZSSLZone::enabled() )
            return;

        /* If the given module/view is not in the list of 'keep mode' views,
         * i.e. it cannot choose access mode itself,
         * then do nothing.
         */
        if ( !$redirect && !eZSSLZone::isKeepModeView( $module, $view ) )
            return;

        // Decide whether the node belongs to an SSL zone or not.
        $sslZones  = eZSSLZone::getSSLZones();

        $inSSLZone = false;
        foreach ( $sslZones as $sslZonePathString )
        {
            if ( strpos( $pathString, $sslZonePathString ) === 0 )
            {
                $inSSLZone = true;
                break;
            }
        }

        eZDebugSetting::writeDebug( 'kernel-ssl-zone',
                                    ( $inSSLZone ? 'yes' : 'no' ),
                                    "Does the node having path $pathString belong to an SSL zone?" );

        if ( $redirect )
            eZSSLZone::switchIfNeeded( $inSSLZone );

        return $inSSLZone;
    }

    /**
     * \static
     * Check whether the given object should cause access mode change.
     * It it should, this method does not return.
     */
    static function checkObject( $module, $view, $object )
    {
        if ( !eZSSLZone::enabled() )
            return;

        /* If the given module/view is not in the list of 'keep mode' views,
         * i.e. it cannot choose access mode itself,
         * then do nothing.
         */
        if ( !eZSSLZone::isKeepModeView( $module, $view ) )
            return;

        $pathStringList = eZPersistentObject::fetchObjectList(
            eZContentObjectTreeNode::definition(),                     // def
            array( 'path_string' ),                                    // field_filters
            array( 'contentobject_id' => $object->attribute( 'id' ) ), // conds
            null,                                                      // sorts
            null,                                                      // limit
            false                                                      // asObject
        );

        if ( is_array( $pathStringList ) && count( $pathStringList ) )
        {
            /* The object has some assigned nodes.
             * If at least one of those nodes belongs to an SSL zone,
             * we switch to SSL.
             */

            // "flatten" the array
            array_walk( $pathStringList, create_function( '&$a', '$a = $a[\'path_string\'];' ) );
        }
        else
        {
            /* The object has no assigned nodes.
             * Let's work with its parent nodes.
             * If at least one of the parent nodes belongs to an SSL zone,
             * we switch to SSL.
             */
            $pathStringList = array();
            $nodes = $object->parentNodes( $object->attribute( 'current' ) );
            if ( !is_array( $nodes ) )
            {
                eZDebug::writeError( 'Object ' . $object->attribute( 'is' ) .
                                     'does not have neither assigned nor parent nodes.' );
            }
            else
            {
                foreach( $nodes as $node )
                {
                    $pathStringList[] = $node->attribute( 'path_string' );
                }
            }
        }

        $inSSL = false; // does the object belong to an SSL zone?
        foreach ( $pathStringList as $pathString )
        {
            if ( eZSSLZone::checkNodePath( $module, $view, $pathString, false ) )
            {
                $inSSL = true;
                break;
            }
        }

        eZSSLZone::switchIfNeeded( $inSSL );
    }

    /**
     * \static
     * Decide whether we should change access mode for this module view or not.
     * Called from index.php.
     */
    static function checkModuleView( $module, $view )
    {
        if ( !eZSSLZone::enabled() )
            return;

        $ini = eZINI::instance();
        $viewsModes  = $ini->variable( 'SSLZoneSettings', 'ModuleViewAccessMode' );

        $sslViews      = array_keys( $viewsModes, 'ssl' );
        $keepModeViews = array_keys( $viewsModes, 'keep' );

        $sslPriority      = eZSSLZone::viewIsInArray( $module, $view, $sslViews      );
        $keepModePriority = eZSSLZone::viewIsInArray( $module, $view, $keepModeViews );

        if ( $sslPriority && $keepModePriority && $sslPriority == $keepModePriority )
        {
            eZDebug::writeError( "Configuration error: view $module/$view is defined both as 'ssl' and 'keep'",
                                 'eZSSLZone' );
            return;
        }

        /* If the view belongs to the list of views we should not change access mode for,
         * then do nothing.
         * (however, the view may do access mode switch itself later)
         */
        if ( $keepModePriority > $sslPriority )
        {
            eZDebugSetting::writeDebug( 'kernel-ssl-zone', 'Keeping current access mode...' );
            return;
        }

        /* Otherwise we look if the view is in the list of SSL views,
         * and if it is, we switch to SSL. Else, if it's not, we switch to plain HTTP.
         */
        $inSSL = ( $sslPriority > 0 );

        eZDebugSetting::writeDebug( 'kernel-ssl-zone',
                                    ( isset( $inSSL ) ? ( $inSSL?'yes':'no') : 'dunno' ),
                                    'Should we use SSL for this view?' );

        // Change access mode if we need to.
        eZSSLZone::switchIfNeeded( $inSSL );
    }
}
?>
