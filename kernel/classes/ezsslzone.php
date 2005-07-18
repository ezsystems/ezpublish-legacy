<?php
//
// Definition of eZSSLZone class
//
// Created on: <12-Jul-2005 13:01:07 vs>
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

define( 'EZSSLZONE_DEFAULT_SSL_PORT', 443 );

include_once( 'lib/ezutils/classes/ezini.php' );

class eZSSLZone
{
    /*! \privatesection */

    /**
     * Returns true if the SSL zones functionality is enabled, false otherwise.
     * The result is cached in memory to save time on multiple invocations.
     */
    function enabled()
    {
        if ( isset( $GLOBALS['eZSSLZoneEnabled'] ) )
            return $GLOBALS['eZSSLZoneEnabled'];

        $ini =& eZINI::instance();
        if ( !$ini->hasVariable( 'SSLZoneSettings', 'SSLZones' ) )
            return false;
        return $GLOBALS['eZSSLZoneEnabled'] = ( $ini->variable( 'SSLZoneSettings', 'SSLZones' ) == 'enabled' );
    }

    /**
     * Load content SSL zones definitions.
     * Substitute URIs with corresponding path strings
     * (e.g. "/news" would be subsituted with "/1/2/50").
     * The result is cached in memory to save time on multiple invocations.
     */
    function getSSLZones()
    {
        // If SSL zones path strings are already cached
        if ( isset( $GLOBALS['eZSSLZonesCachedPathStrings'] ) )
        {
            // then load them
            $pathStringsArray = $GLOBALS['eZSSLZonesCachedPathStrings'];
        }
        else
        {
            // else generate the cache.

            $ini =& eZINI::instance();
            $sslSubtrees = $ini->variable( 'SSLZoneSettings', 'SSLSubtrees' );
            if ( !isset( $sslSubtrees ) || !$sslSubtrees )
                return array();

            $pathStringsArray = array();
            foreach ( $sslSubtrees as $uri )
            {
                $node = eZContentObjectTreeNode::fetchByURLPath( preg_replace( '/^\//', '', $uri ) );
                if ( !is_object( $node ) )
                {
                    eZDebug::writeError( "cannot fetch node by URI '$uri'", 'eZSSLZone::getSSLZones' );
                    continue;
                }
                $pathStringsArray[$uri] = $node->attribute( 'path_string' );
                unset( $node );
            }
            $GLOBALS['eZSSLZonesCachedPathStrings'] = $pathStringsArray;
        }

        return $pathStringsArray;
    }

    /**
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
    function switchIfNeeded( $inSSL )
    {
        // if it's undefined whether we should redirect  we do nothing
        if ( !isset( $inSSL ) )
            return;

        $ini =& eZINI::instance();
        $sslPort = $ini->variable( 'SiteSettings', 'SSLPort' );
        if ( !isset( $sslPort ) )
            $sslPort = EZSSLZONE_DEFAULT_SSL_PORT;
        // $nowSSl is true if current access mode is HTTPS.
        $nowSSL = ( eZSys::serverPort() == $sslPort );

        $requestURI = eZSys::requestURI();

        $sslZoneRedirectionURL = false;
        if ( $nowSSL && !$inSSL )
        {
            // switch to plain HTTP
            $host = $ini->variable( 'SiteSettings', 'SiteURL' );
            $sslZoneRedirectionURL = "http://" . $host  . $requestURI;
        }
        elseif ( !$nowSSL && $inSSL )
        {
            // switch to HTTPS
            $host = preg_replace( '/:\d+$/', '', $host = eZSys::serverVariable( 'HTTP_HOST' ) );
            $sslPortString = ( $sslPort == EZSSLZONE_DEFAULT_SSL_PORT ) ? '' : ":$sslPort";
            $sslZoneRedirectionURL = "https://" . $host  . $sslPortString . $requestURI;
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
     * Check whether the given node should cause access mode change.
     * It it should, this method does not return.
     *
     * \see checkNode()
     */
    function checkNodeID( $module, $view,  $nodeID )
    {
        if ( !eZSSLZone::enabled() )
            return;

        $node = eZContentObjectTreeNode::fetch( $nodeID );
        if ( !is_object( $node ) )
        {
            eZDebug::writeError( "Node #$nodeID not found", "eZSSLZone::checkNodeID" );
            return;
        }

        eZSSLZone::checkNode( $module, $view, $node );
    }

    /**
     * Check whether the given node should cause access mode change.
     * It it should, this method does not return.
     */
    function checkNode( $module, $view, &$node, $redirect = true )
    {
        if ( !eZSSLZone::enabled() )
            return;

        include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
        $pathString = $node->attribute( 'path_string' );

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
                                    'Does node ' . $node->attribute( 'node_id' ) . ' belong to an SSL zone?' );

        if ( $redirect )
            eZSSLZone::switchIfNeeded( $inSSLZone );

        return $inSSLZone;
    }


    /**
     * Check whether the given object should cause access mode change.
     * It it should, this method does not return.
     */
    function checkObject( $module, $view, &$object )
    {
        if ( !eZSSLZone::enabled() )
            return;

        $nodes = $object->attribute( 'assigned_nodes' );

        if ( is_array( $nodes ) && count( $nodes ) )
        {
            /* The object has some assigned nodes.
             * If at least one of those nodes belongs to an SSL zone,
             * we switch to SSL.
             */
        }
        else
        {
            /* The object has no assigned nodes.
             * Let's work with its parent nodes.
             * If at least one of the parent nodes belongs to an SSL zone,
             * we switch to SSL.
             */
            $nodes = $object->parentNodes( $object->attribute( 'current' ) );
            if ( !is_array( $nodes ) )
            {
                eZDebug::writeError( 'Object ' . $object->attribute( 'is' ) .
                                     'does not have neither assigned nor parent nodes.' );
            }
        }

        $inSSL = false; // does the object belong to an SSL zone?
        foreach ( $nodes as $node )
        {
            if ( eZSSLZone::checkNode( $module, $view, $node, false ) )
            {
                $inSSL = true;
                break;
            }
        }

        eZSSLZone::switchIfNeeded( $inSSL );
    }

    /**
     * Decide whether we should change access mode for this module view or not.
     * Called from index.php.
     */
    function checkModuleView( $module, $view )
    {
        if ( !eZSSLZone::enabled() )
            return;


        $ini =& eZINI::instance();
        $smartViews  = $ini->variable( 'SSLZoneSettings', 'SmartViews' );
        $viewsModes  = $ini->variable( 'SSLZoneSettings', 'ModuleViewAccessMode' );

        $sslViews      = array_keys( $viewsModes, 'ssl' );
        $keepModeViews = array_keys( $viewsModes, 'keep' );
        $currentView   = "$module/$view";

        /* If the view belongs to the list of views we should not change access mode for,
         * then do nothing
         */
        if ( in_array( $currentView, $keepModeViews ) )
        {
            eZDebugSetting::writeDebug( 'kernel-ssl-zone', 'Keeping current access mode...' );
            return;
        }

        /* If there are some SSL zones defined for the module view,
         * it's up to the view to decide whether to change access mode.
         *
         * Otherwise we look if the view is in the list of SSL views,
         * and if it is, we switch to SSL. Else, if it's not, we switch to plain HTTP.
         */
        $isSSLView   = ( in_array( $currentView, $sslViews ) !== false );
        $isSmartView = in_array( $currentView, $smartViews );

        if ( $isSmartView )
            $inSSL = null;
        else
            $inSSL = $isSSLView;

        eZDebugSetting::writeDebug( 'kernel-ssl-zone',
                                    ( isset( $inSSL ) ? ( $inSSL?'yes':'no') : 'dunno' ),
                                    'Should we use SSL for this view?' );

        // Change access mode if we need to.
        eZSSLZone::switchIfNeeded( $inSSL );
    }
}
?>
