<?php
//
// Created on: <23-Jan-2003 11:37:30 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );
include_once( 'kernel/classes/ezpreferences.php' );

$Module =& $Params['Module'];
$ViewMode = $Params['ViewMode'];

if( eZPreferences::value( 'admin_url_list_limit' ) )
{
    switch( eZPreferences::value( 'admin_url_list_limit' ) )
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

if( $ViewMode != 'all' && $ViewMode != 'invalid' && $ViewMode != 'valid')
{
    $ViewMode = 'all';
}

if ( $Module->isCurrentAction( 'SetValid' ) )
{
    $urlSelection = $Module->actionParameter( 'URLSelection' );
    eZURL::setIsValid( $urlSelection, true );
}
else if ( $Module->isCurrentAction( 'SetInvalid' ) )
{
    $urlSelection = $Module->actionParameter( 'URLSelection' );
    eZURL::setIsValid( $urlSelection, false );
}


if( $ViewMode == 'all' )
{
    $listParameters = array( 'is_valid'       => null,
                             'offset'         => $offset,
                             'limit'          => $limit,
                             'only_published' => true );

    $countParameters = array( 'only_published' => true );
}
elseif( $ViewMode == 'valid' )
{
    $listParameters = array( 'is_valid'       => true,
                             'offset'         => $offset,
                             'limit'          => $limit,
                             'only_published' => true );

    $countParameters = array( 'is_valid' => true,
                              'only_published' => true );
}
elseif( $ViewMode == 'invalid' )
{
    $listParameters = array( 'is_valid'       => false,
                             'offset'         => $offset,
                             'limit'          => $limit,
                             'only_published' => true );

    $countParameters = array( 'is_valid' => false,
                              'only_published' => true );
}

$list =& eZURL::fetchList( $listParameters );
$listCount =& eZURL::fetchListCount( $countParameters );

$viewParameters = array( 'offset' => $offset, 'limit'  => $limit );

include_once( 'kernel/common/template.php' );
$tpl =& templateInit();

$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'url_list', $list );
$tpl->setVariable( 'url_list_count', $listCount );
$tpl->setVariable( 'view_mode', $ViewMode );

$Result = array();
$Result['content'] = $tpl->fetch( "design:url/list.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/url', 'URL' ) ),
                         array( 'url' => false,
                                'text' => ezi18n( 'kernel/url', 'List' ) ) );
?>
