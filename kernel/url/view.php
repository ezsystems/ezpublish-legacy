<?php
//
// Created on: <23-Jan-2003 11:37:30 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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

/*! \file
*/

$Module = $Params['Module'];
$urlID = $Params['ID'];

if( eZPreferences::value( 'admin_url_view_limit' ) )
{
    switch( eZPreferences::value( 'admin_url_view_limit' ) )
    {
        case '2': { $limit = 25; } break;
        case '3': { $limit = 50; } break;
        default:  { $limit = 10; } break;
    }
}
else
{
    $limit = 10;
}

$offset = $Params['Offset'];
if ( !is_numeric( $offset ) )
{
    $offset = 0;
}

$url = eZURL::fetch( $urlID );
if ( !$url )
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );

$link = $url->attribute( 'url' );
if ( preg_match("/^(http:)/i", $link ) or
     preg_match("/^(ftp:)/i", $link ) or
     preg_match("/^(https:)/i", $link ) or
     preg_match("/^(file:)/i", $link ) or
     preg_match("/^(mailto:)/i", $link ) )
{
    // No changes
}
else
{
    $domain = getenv( 'HTTP_HOST' );
    $protocol = 'http';

    // Check if SSL port is defined in site.ini
    $ini = eZINI::instance();
    $sslPort = 443;
    if ( $ini->hasVariable( 'SiteSettings', 'SSLPort' ) )
    {
        $sslPort = $ini->variable( 'SiteSettings', 'SSLPort' );
    }

    if ( eZSys::serverPort() == $sslPort )
    {
        $protocol = 'https';
    }

    $preFix = $protocol . "://" . $domain;
    $preFix .= eZSys::wwwDir();

    $link = preg_replace("/^\//e", "", $link );
    $link = $preFix . "/" . $link;
}

$viewParameters = array( 'offset' => $offset, 'limit'  => $limit );
$http = eZHTTPTool::instance();
$objectList = eZURLObjectLink::fetchObjectVersionList( $urlID, $viewParameters );
$urlViewCount= eZURLObjectLink::fetchObjectVersionCount( $urlID );

if ( $Module->isCurrentAction( 'EditObject' ) )
{
    if ( $http->hasPostVariable( 'ObjectList' ) )
    {
        $versionID = $http->postVariable( 'ObjectList' );
        $version = eZContentObjectVersion::fetch( $versionID );
        $contentObjectID = $version->attribute( 'contentobject_id' );
        $versionNr = $version->attribute( 'version' );
        $Module->redirect( 'content', 'edit', array( $contentObjectID, $versionNr ) );
    }
}

require_once( 'kernel/common/template.php' );
$tpl = templateInit();

$tpl->setVariable( 'Module', $Module );
$tpl->setVariable( 'url_object', $url );
$tpl->setVariable( 'full_url', $link );
$tpl->setVariable( 'object_list', $objectList );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'url_view_count', $urlViewCount );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:url/view.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/url', 'URL' ) ),
                         array( 'url' => false,
                                'text' => ezi18n( 'kernel/url', 'View' ) ) );

?>
