<?php
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
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
    $access = array();
    $type =& $access['type'];
    $type = EZ_ACCESS_TYPE_DEFAULT;

    $order = $ini->variable( 'SiteAccessSettings', 'MatchOrder' );
    if ( $order == 'none' )
        return $access;
    $order = $ini->variableArray( 'SiteAccessSettings', 'MatchOrder' );
    foreach( $order as $match )
    {
        $tmp_type = null;
        $match_type = false;
        switch( $match )
        {
            case 'port':
            {
                if ( $ini->hasVariable( 'PortAccessSettings', $port ) )
                {
                    $match_type = 'port';
                    $access['name'] = $ini->variable( 'PortAccessSettings', $port );
                    $type = EZ_ACCESS_TYPE_PORT;
                }
                else
                    continue;
            } break;
            case 'uri':
            {
                $match_type = $ini->variable( 'SiteAccessSettings', 'URIMatchType' );
                if ( $match_type != 'element' and
                     $match_type != 'regexp' )
                    continue;
                $tmp_type = EZ_ACCESS_TYPE_URI;
                if ( $match_type == 'element' )
                {
                    $matcher = $ini->variable( 'SiteAccessSettings', 'URIMatchElement' );
                    $type = $tmp_type;
                    $match = '';
                    $elements = $uri->elements( false );
                    $elements = array_slice( $elements, 0, $matcher );
                    $uri->increase( $matcher );
                    $match = implode( '_', $elements );
                    $access['name'] = $match;
                }
                else if ( $match_type == 'regexp' )
                {
                    $matcher = $ini->variable( 'SiteAccessSettings', 'URIMatchRegexp' );
                    $match_num = $ini->variable( 'SiteAccessSettings', 'URIMatchRegexpItem' );
                }
                else
                    $match_item = $uri->elements();
            } break;
            case 'host':
            {
                $match_type = $ini->variable( 'SiteAccessSettings', 'HostMatchType' );
                if ( $match_type != 'text' and
                     $match_type != 'regexp' )
                    continue;
                $match_item = $host;
                $tmp_type = EZ_ACCESS_TYPE_HTTP_HOST;
                if ( $match_type == 'element' )
                {
                    $match_index = $ini->variable( 'SiteAccessSettings', 'HostMatchElement' );
                    $type = $tmp_type;
                    $match_arr = explode( '.', $match_item );
                    $access['name'] = $match_arr[$match_index];
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
            } break;
            case 'index':
            {
                $match_type = $ini->variable( 'SiteAccessSettings', 'IndexMatchType' );
                if ( $match_type != 'text' and
                     $match_type != 'regexp' and
                     $match_type != 'element' )
                    continue;
                $tmp_type = EZ_ACCESS_TYPE_INDEX_FILE;
                $match_item = $file;
                if ( $match_type == 'element' )
                {
                    $match_index = $ini->variable( 'SiteAccessSettings', 'IndexMatchElement' );
                    $type = $tmp_type;
                    $match_pos = strpos( $match_item, '.php' );
                    if ( $match_pos !== false )
                    {
                        $match_item = substr( $match_item, 0, $match_pos );
                        $match_arr = explode( '_', $match_item );
                        $access['name'] = $match_arr[$match_index];
                    }
                }
                else if ( $match_type == 'text' )
                {
                    $matcher_pre = $ini->variable( 'SiteAccessSettings', 'IndexMatchSubtextPre' );
                    $matcher_post = $ini->variable( 'SiteAccessSettings', 'IndexMatchSubtextPost' );
                }
                else
                {
                    $matcher = $ini->variable( 'SiteAccessSettings', 'IndexMatchRegexp' );
                    $match_num = $ini->variable( 'SiteAccessSettings', 'IndexMatchRegexpItem' );
                }
            } break;
            default:
            {
                eZDebug::writeError( "Unknown access match: $match", "access" );
            } break;
        }
        if ( $match_type == 'element' )
        {
            break;
        }
        else if ( $match_type == 'regexp' )
        {
            $match = accessMatchRegexp( $match_item, $matcher, $match_num );
            if ( $match !== null )
            {
                $type = $tmp_type;
                $access['name'] = $match;
                break;
            }
        }
        else if ( $match_type == 'text' )
        {
            $match = accessMatchText( $match_item, $matcher_pre, $matcher_post );
            if ( $match !== null )
            {
                $type = $tmp_type;
                $access['name'] = $match;
                break;
            }
        }
        else if ( $match_type == 'port' )
        {
            break;
        }
    }
    if ( isset( $access['name'] ) )
    {
        $access['name'] = preg_replace( array( '/[^a-z0-9]+/',
                                               '/_+/',
                                               '/^_/',
                                               '/_$/' ),
                                        array( '_',
                                               '_',
                                               '',
                                               '' ),
                                        strtolower( $access['name'] ) );
        return $access;
    }
    return null;
}

function accessAllowed( $uri )
{
    $module_name = $uri->element();

    $ini =& eZINI::instance();

    $allow = true;
    $tmp_allow = true;
    if ( !$ini->hasGroup( 'SiteAccessRules' ) )
        return true;
    $items =& $ini->group( 'SiteAccessRules', true );
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
            case 'moduleall':
            {
                if ( $value == 'true' )
                    $allow = $tmp_allow;
            } break;
            case 'module':
            {
                if ( $value == $module_name )
                    $allow = $tmp_allow;
            } break;
            default:
            {
                eZDebug::writeError( "Unknown access rule: $name=$value", 'Access' );
            } break;
        }
    }

    return $allow;
}

function precheckAllowed( &$prechecks )
{
    $ini =& eZINI::instance();

    $tmp_allow = true;
    if ( !$ini->hasGroup( 'SitePrecheckRules' ) )
        return true;
    $items =& $ini->group( 'SitePrecheckRules', true );
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

function changeAccess( $access )
{
    $ini =& eZINI::instance();
    $name = $access['name'];
    if ( $access['type'] == EZ_ACCESS_TYPE_URI )
    {
        include_once( 'lib/ezutils/classes/ezsys.php' );
        eZSys::setAccessPath( $name );
    }
    if ( file_exists( "settings/siteaccess/$name" ) )
    {
        $ini->setOverrideDir( "siteaccess/$name" );
        $ini->loadCache();
//         $ini->parse( 'site.ini' );
    }
}


?>
