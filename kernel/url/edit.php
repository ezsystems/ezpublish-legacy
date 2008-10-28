<?php
//
// Created on: <04-Jul-2003 10:30:48 wy>
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

/*! \file view.php
*/
$Module = $Params['Module'];
$urlID = null;
if ( isset( $Params["ID"] ) )
    $urlID = $Params["ID"];

if ( is_numeric( $urlID ) )
{
    $url = eZURL::fetch( $urlID );
    if ( !$url )
    {
        return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    }
}
else
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$http = eZHTTPTool::instance();
if ( $Module->isCurrentAction( 'Cancel' ) )
{
    $Module->redirectToView( 'list' );
    return;
}

if ( $Module->isCurrentAction( 'Store' ) )
{
    if ( $http->hasPostVariable( 'link' ) )
    {
        $link = $http->postVariable( 'link' );
        $url->setAttribute( 'url', $link );
        $url->store();
        eZURLObjectLink::clearCacheForObjectLink( $urlID );
    }
    $Module->redirectToView( 'list' );
    return;
}

$Module->setTitle( "Edit link " . $url->attribute( "id" ) );

// Template handling
require_once( "kernel/common/template.php" );
$tpl = templateInit();

$tpl->setVariable( "Module", $Module );
$tpl->setVariable( "url", $url );

$Result = array();
$Result['content'] = $tpl->fetch( "design:url/edit.tpl" );
$Result['path'] = array( array( 'url' => '/url/edit/',
                                'text' => ezi18n( 'kernel/url', 'URL edit' ) ) );
?>
