<?php
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

define( 'EZ_ACCESS_TYPE_DEFAULT', 1 );
define( 'EZ_ACCESS_TYPE_URI', 2 );
define( 'EZ_ACCESS_TYPE_PORT', 3 );
define( 'EZ_ACCESS_TYPE_HTTP_HOST', 4 );
define( 'EZ_ACCESS_TYPE_INDEX_FILE', 5 );
define( 'EZ_ACCESS_TYPE_STATIC', 6 );
define( 'EZ_ACCESS_TYPE_SERVER_VAR', 7 );
define( 'EZ_ACCESS_TYPE_URL', 8 );

define( 'EZ_ACCESS_SUBTYPE_PRE', 1 );
define( 'EZ_ACCESS_SUBTYPE_POST', 2 );

/*!
 Goes trough the access matching rules and returns the access match.
 The returned match is an associative array with \a name and \c type.
*/
function accessType( $uri, $host, $port, $file )
{
    $ini = eZINI::instance();
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

    list( $siteAccessList, $order ) =
        $ini->variableMulti( 'SiteAccessSettings', array( 'AvailableSiteAccessList', 'MatchOrder' ) );
    $access = array( 'name' => $ini->variable( 'SiteSettings', 'DefaultAccess' ),
                     'type' => EZ_ACCESS_TYPE_DEFAULT );


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
            case 'servervar':
            {
                //include_once( 'lib/ezutils/classes/ezsys.php' );
                if ( $serversiteaccess = eZSys::serverVariable( $ini->variable( 'SiteAccessSettings', 'ServerVariableName' ), true ) )
                {
                    $access['name'] = $serversiteaccess;
                    $access['type'] = EZ_ACCESS_TYPE_SERVER_VAR;
                    return $access;
                }
                else
                    continue;
            } break;
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

                if ( $match_type == 'map' )
                {
                    if ( $ini->hasVariable( 'SiteAccessSettings', 'URIMatchMapItems' ) )
                    {
                        $match_item = $uri->element( 0 );
                        $matchMapItems = $ini->variableArray( 'SiteAccessSettings', 'URIMatchMapItems' );
                        foreach ( $matchMapItems as $matchMapItem )
                        {
                            $matchMapURI = $matchMapItem[0];
                            $matchMapAccess = $matchMapItem[1];
                            if ( $access['name']  == $matchMapAccess and in_array( $matchMapAccess, $siteAccessList ) )
                            {
                                $access['access_alias'] = $matchMapURI;
                            }
                            if ( $matchMapURI == $match_item and in_array( $matchMapAccess, $siteAccessList ) )
                            {
                                $uri->increase( 1 );
                                $uri->dropBase();
                                $access['name'] = $matchMapAccess;
                                $access['type'] = $type;
                                $access['access_alias'] = $matchMapURI;
                                return $access;
                            }
                        }
                    }
                }
                else if ( $match_type == 'element' )
                {
                    $match_index = $ini->variable( 'SiteAccessSettings', 'URIMatchElement' );
                    $elements = $uri->elements( false );
                    $elements = array_slice( $elements, 0, $match_index );
                    $name = implode( '_', $elements );
                }
                else if ( $match_type == 'text' )
                {
                    $match_item = $uri->elements();
                    $matcher_pre = $ini->variable( 'SiteAccessSettings', 'URIMatchSubtextPre' );
                    $matcher_post = $ini->variable( 'SiteAccessSettings', 'URIMatchSubtextPost' );
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
            $name = preg_replace( array( '/[^a-zA-Z0-9]+/',
                                         '/_+/',
                                         '/^_/',
                                         '/_$/' ),
                                  array( '_',
                                         '_',
                                         '',
                                         '' ),
                                  $name );

            if ( in_array( $name, $siteAccessList ) )
            {
                if ( $type == EZ_ACCESS_TYPE_URI )
                {
                    if ( $match_type == 'element' )
                    {
                        $uri->increase( $match_index );
                        $uri->dropBase();
                    }
                    else if ( $match_type == 'regexp' )
                    {
                        $uri->setURIString( $match_item );
                    }
                    else if ( $match_type == 'text' )
                    {
                        $uri->setURIString( $match_item );
                    }
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
    $ini = eZINI::instance();

    $GLOBALS['eZCurrentAccess'] =& $access;

    $name = $access['name'];
    if ( isset( $access['type'] ) &&
         $access['type'] == EZ_ACCESS_TYPE_URI )
    {
        //include_once( 'lib/ezutils/classes/ezsys.php' );
        eZSys::addAccessPath( $name );
    }

    if ( file_exists( "settings/siteaccess/$name" ) )
    {
        $ini->prependOverrideDir( "siteaccess/$name", false, 'siteaccess' );
    }

    /* Make sure extension siteaccesses are prepended */
    //include_once( 'lib/ezutils/classes/ezextension.php' );
    eZExtension::prependExtensionSiteAccesses( $name );

    $ini->loadCache();

    eZUpdateDebugSettings();
    if ( accessDebugEnabled() )
    {
        eZDebug::writeDebug( "Updated settings to use siteaccess '$name'", 'access.php' );
    }

    return $access;
}

function accessMatchRegexp( &$text, $reg, $num )
{
    $reg = preg_replace( "#/#", "\\/", $reg );
    if ( preg_match( "/$reg/", $text, $regs ) and $num < count( $regs ) )
    {
        $text = str_replace( $regs[$num], '', $text );
        return $regs[$num];
    }
    return null;
}

function accessMatchText( &$text, $match_pre, $match_post )
{
    $ret = null;
    if ( $match_pre != '' )
    {
        $pos = strpos( $text, $match_pre );
        if ( $pos === false )
            return null;

        $ret = substr( $text, $pos + strlen( $match_pre ) );
        $text = substr( $text, 0, $pos );
    }
    if ( $match_post != '' )
    {
        $pos = strpos( $ret, $match_post );
        if ( $pos === false )
            return null;

        $text .= substr( $ret, $pos + 1 );
        $ret = substr( $ret, 0, $pos );
    }
    return $ret;
}

function accessAllowed( $uri )
{
    $moduleName = $uri->element();
    $viewName = $uri->element( 1 );
    $check = array( 'result' => true,
                    'module' => $moduleName,
                    'view' => $viewName,
                    'view_checked' => false );

    $ini = eZINI::instance();

    $access = true;
    $currentAccess = true;
    if ( !$ini->hasGroup( 'SiteAccessRules' ) )
        return $check;
    $items = $ini->variableArray( 'SiteAccessRules', 'Rules' );
    foreach( $items as $item )
    {
        $name = strtolower( $item[0] );
        if ( isset ( $item[1] ) )
            $value = $item[1];
        else
            $value = null;
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

/*!
 */
function precheckAllowed( $prechecks )
{
    $ini = eZINI::instance();

    $tmp_allow = true;
    if ( !$ini->hasGroup( 'SitePrecheckRules' ) )
        return $prechecks;
    $items = $ini->variableArray( 'SitePrecheckRules', 'Rules' );
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
                    foreach( $prechecks as $key => $value )
                    {
                        $prechecks[$key]['allow'] = $tmp_allow;
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

    return $prechecks;
}

function accessDebugEnabled()
{
    $ini = eZINI::instance();
    return $ini->variable( 'SiteAccessSettings', 'DebugAccess' ) == 'enabled';
}

function accessExtraDebugEnabled()
{
    $ini = eZINI::instance();
    return $ini->variable( 'SiteAccessSettings', 'DebugExtraAccess' ) == 'enabled';
}

?>
