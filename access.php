<?php
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

define( 'EZ_ACCESS_TYPE_DEFAULT', 1 );
define( 'EZ_ACCESS_TYPE_URI', 2 );
define( 'EZ_ACCESS_TYPE_PORT', 3 );
define( 'EZ_ACCESS_TYPE_HTTP_HOST', 4 );
define( 'EZ_ACCESS_TYPE_INDEX_FILE', 5 );
define( 'EZ_ACCESS_TYPE_STATIC', 6 );

define( 'EZ_ACCESS_SUBTYPE_PRE', 1 );
define( 'EZ_ACCESS_SUBTYPE_POST', 2 );

/*!
 Goes trough the access matching rules and returns the access match.
 The returned match is an associative array with \a name and \c type.
*/
function accessType( &$uri, $host, $port, $file )
{
    $ini =& eZINI::instance();
    if ( $ini->hasVariable( 'SiteAccessSettings', 'StaticMatch' ) )
    {
        $match = $ini->variable( 'SiteAccessSettings', 'StaticMatch' );
        if ( $match != '' )
        {
            $access = array( 'name' => $match,
                             'type' => EZ_ACCESS_TYPE_STATIC );
            return $access;
        }
    }

    $siteAccessList = $ini->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );

    $access = array( 'name' => $ini->variable( 'SiteSettings', 'DefaultAccess' ),
                     'type' => EZ_ACCESS_TYPE_DEFAULT );

    $order = $ini->variable( 'SiteAccessSettings', 'MatchOrder' );

    if ( $order == 'none' )
        return $access;

    $order = $ini->variableArray( 'SiteAccessSettings', 'MatchOrder' );

    foreach ( $order as $matchprobe )
    {
        $name = '';
        $type = '';
        $match_type = '';

        switch( $matchprobe )
        {
            case 'port':
            {
                if ( $ini->hasVariable( 'PortAccessSettings', $port ) )
                {
                    $access['name'] = $ini->variable( 'PortAccessSettings', $port );
                    $access['type'] = EZ_ACCESS_TYPE_PORT;
                    return $access;
                }
                else
                    continue;
            } break;
            case 'uri':
            {
                $type = EZ_ACCESS_TYPE_URI;
                $match_type = $ini->variable( 'SiteAccessSettings', 'URIMatchType' );
                if ( $match_type == 'element' )
                {
                    $matcher = $ini->variable( 'SiteAccessSettings', 'URIMatchElement' );
                    $elements = $uri->elements( false );
                    $elements = array_slice( $elements, 0, $matcher );
                    $name = implode( '_', $elements );
                }
                else if ( $match_type == 'regexp' )
                {
                    $match_item = $uri->elements();
                    $matcher = $ini->variable( 'SiteAccessSettings', 'URIMatchRegexp' );
                    $match_num = $ini->variable( 'SiteAccessSettings', 'URIMatchRegexpItem' );
                }
                else
                    continue;
            } break;
            case 'host':
            {
                $type = EZ_ACCESS_TYPE_HTTP_HOST;
                $match_type = $ini->variable( 'SiteAccessSettings', 'HostMatchType' );
                $match_item = $host;
                if ( $match_type == 'map' )
                {
                    if ( $ini->hasVariable( 'SiteAccessSettings', 'HostMatchMapItems' ) )
                    {
                        $matchMapItems = $ini->variableArray( 'SiteAccessSettings', 'HostMatchMapItems' );
                        foreach ( $matchMapItems as $matchMapItem )
                        {
                            $matchMapHost = $matchMapItem[0];
                            $matchMapAccess = $matchMapItem[1];
                            if ( $matchMapHost == $host )
                            {
                                $access['name'] = $matchMapAccess;
                                $access['type'] = $type;
                                return $access;
                            }
                        }
                    }
                }
                else if ( $match_type == 'element' )
                {
                    $match_index = $ini->variable( 'SiteAccessSettings', 'HostMatchElement' );
                    $match_arr = explode( '.', $match_item );
                    $name = $match_arr[$match_index];
                }
                else if ( $match_type == 'text' )
                {
                    $matcher_pre = $ini->variable( 'SiteAccessSettings', 'HostMatchSubtextPre' );
                    $matcher_post = $ini->variable( 'SiteAccessSettings', 'HostMatchSubtextPost' );
                }
                else if ( $match_type == 'regexp' )
                {
                    $matcher = $ini->variable( 'SiteAccessSettings', 'HostMatchRegexp' );
                    $match_num = $ini->variable( 'SiteAccessSettings', 'HostMatchRegexpItem' );
                }
                else
                    continue;
            } break;
            case 'index':
            {
                $type = EZ_ACCESS_TYPE_INDEX_FILE;
                $match_type = $ini->variable( 'SiteAccessSettings', 'IndexMatchType' );
                $match_item = $file;
                if ( $match_type == 'element' )
                {
                    $match_index = $ini->variable( 'SiteAccessSettings', 'IndexMatchElement' );
                    $match_pos = strpos( $match_item, '.php' );
                    if ( $match_pos !== false )
                    {
                        $match_item = substr( $match_item, 0, $match_pos );
                        $match_arr = explode( '_', $match_item );
                        $name = $match_arr[$match_index];
                    }
                }
                else if ( $match_type == 'text' )
                {
                    $matcher_pre = $ini->variable( 'SiteAccessSettings', 'IndexMatchSubtextPre' );
                    $matcher_post = $ini->variable( 'SiteAccessSettings', 'IndexMatchSubtextPost' );
                }
                else if ( $match_type == 'regexp' )
                {
                    $matcher = $ini->variable( 'SiteAccessSettings', 'IndexMatchRegexp' );
                    $match_num = $ini->variable( 'SiteAccessSettings', 'IndexMatchRegexpItem' );
                }
                else
                    continue;
            } break;
            default:
            {
                eZDebug::writeError( "Unknown access match: $match", "access" );
            } break;
        }

        if ( $match_type == 'regexp' )
            $name = accessMatchRegexp( $match_item, $matcher, $match_num );
        else if ( $match_type == 'text' )
            $name = accessMatchText( $match_item, $matcher_pre, $matcher_post );

        if ( ( isset( $name ) && $name != '' ) || $type == EZ_ACCESS_TYPE_URI )
        {
            $name = preg_replace( array( '/[^a-z0-9]+/',
                                         '/_+/',
                                         '/^_/',
                                         '/_$/' ),
                                  array( '_',
                                         '_',
                                         '',
                                         '' ),
                                  strtolower( $name ) );

            if ( in_array( $name, $siteAccessList ) )
            {
                if ( $type == EZ_ACCESS_TYPE_URI && $match_type == 'element' )
                {
                    $uri->increase( $matcher );
                    $uri->dropBase();
                }

                $access['type'] = $type;
                $access['name'] = $name;
                return $access;
            }
            else if ( $type == EZ_ACCESS_TYPE_URI )
            {
                $access['type'] = $type;
                return $access;
            }
        }
    }
    return $access;
}

/*!
 Changes the site access to what's defined in \a $access. It will change the
 access path in eZSys and prepend an override dir to eZINI
 \param $access An associative array consisting of \c name and \c type.
 \return the \a $access parameter.
*/

function changeAccess( $access )
{
    $ini =& eZINI::instance();

    $name = $access['name'];
    if ( $access['type'] == EZ_ACCESS_TYPE_URI )
    {
        include_once( 'lib/ezutils/classes/ezsys.php' );
        eZSys::addAccessPath( $name );
    }

    if ( file_exists( "settings/siteaccess/$name" ) )
    {
        $ini->prependOverrideDir( "siteaccess/$name", false, 'siteaccess' );
        $ini->loadCache();
        eZUpdateDebugSettings();
        if ( accessDebugEnabled() )
            eZDebug::writeDebug( "Updated settings to use siteaccess '$name'", 'access.php' );
    }
    return $access;
}

function accessMatchRegexp( $text, $reg, $num )
{
    $reg = preg_replace( "#/#", "\\/", $reg );
    if ( preg_match( "/$reg/", $text, $regs ) )
    {
        return $regs[$num];
    }
    return null;
}

function accessMatchText( $text, $match_pre, $match_post )
{
    $ret = null;
    $pos = strpos( $text, $match_pre );
    if ( $pos == false )
        return null;
    $text = substr( $text, $pos + strlen( $match_pre ) );
    $pos = strpos( $text, $match_post );
    if ( $pos == false )
        return null;
    $text = substr( $text, 0, $pos );
    return $text;
}

function accessAllowed( $uri )
{
    $moduleName = $uri->element();
    $viewName = $uri->element( 1 );
    $check = array( 'result' => true,
                    'module' => $moduleName,
                    'view' => $viewName,
                    'view-checked' => false );

    $ini =& eZINI::instance();

    $access = true;
    $currentAccess = true;
    if ( !$ini->hasGroup( 'SiteAccessRules' ) )
        return $check;
    $items =& $ini->variableArray( 'SiteAccessRules', 'Rules' );
    foreach( $items as $item )
    {
        $name = strtolower( $item[0] );
        $value = $item[1];
        switch( $name )
        {
            case 'access':
            {
                $currentAccess = ( $value == 'enable' );
            } break;
            case 'moduleall':
            {
                $access = $currentAccess;
            } break;
            case 'module':
            {
                if ( preg_match( "#([a-zA-Z0-9_]+)/([a-zA-Z0-9_]+)#", $value, $matches ) )
                {
                    if ( $matches[1] == $moduleName and
                         $matches[2] == $viewName )
                    {
                        $check['view_checked'] = true;
                        $access = $currentAccess;
                    }
                }
                else
                {
                    if ( $value == $moduleName )
                    {
                        $access = $currentAccess;
                        $check['view_checked'] = false;
                    }
                }
            } break;
            default:
            {
                eZDebug::writeError( "Unknown access rule: $name=$value", 'Access' );
            } break;
        }
    }

    $check['result'] = $access;
    return $check;
}

function precheckAllowed( &$prechecks )
{
    $ini =& eZINI::instance();

    $tmp_allow = true;
    if ( !$ini->hasGroup( 'SitePrecheckRules' ) )
        return true;
    $items =& $ini->variableArray( 'SitePrecheckRules', 'Rules' );
    foreach( $items as $item )
    {
        $name = strtolower( $item[0] );
        $value = $item[1];
        switch( $name )
        {
            case 'access':
            {
                $tmp_allow = ($value == 'enable');
            } break;
            case 'precheckall':
            {
                if ( $value == 'true' )
                {
                    reset( $prechecks );
                    while( ($key = key( $prechecks ) ) !== null )
                    {
                        $prechecks[$key]['allow'] = $tmp_allow;
                        next( $prechecks );
                    }
                }
            } break;
            case 'precheck':
            {
                if ( isset( $prechecks[$value] ) )
                    $prechecks[$value]['allow'] = $tmp_allow;
            } break;
            default:
            {
                eZDebug::writeError( "Unknown precheck rule: $name=$value", 'Access' );
            } break;
        }
    }
}

function accessDebugEnabled()
{
    $ini =& eZINI::instance();
    return $ini->variable( 'SiteAccessSettings', 'DebugAccess' ) == 'enabled';
}

function accessExtraDebugEnabled()
{
    $ini =& eZINI::instance();
    return $ini->variable( 'SiteAccessSettings', 'DebugExtraAccess' ) == 'enabled';
}

?>
