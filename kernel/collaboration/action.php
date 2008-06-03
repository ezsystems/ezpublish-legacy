<?php
//
// Created on: <24-Jan-2003 17:35:58 amos>
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

$Module = $Params['Module'];

$http = eZHTTPTool::instance();

if ( $Module->isCurrentAction( 'Custom' ) )
{
    $typeIdentifier = $Module->actionParameter( 'TypeIdentifer' );
    $itemID = $Module->actionParameter( 'ItemID' );
    //include_once( 'kernel/classes/ezcollaborationitem.php' );
    //include_once( 'kernel/classes/ezcollaborationitemhandler.php' );
    $collaborationItem = eZCollaborationItem::fetch( $itemID );
    $handler = eZCollaborationItemHandler::instantiate( $typeIdentifier );
    return $handler->handleCustomAction( $Module, $collaborationItem );
}

$Result = array();
$Result['content'] = false;
$Result['path'] = array( array( 'url' => false,
                                ezi18n( 'kernel/collaboration', 'Collaboration custom action' ) ) );

?>
