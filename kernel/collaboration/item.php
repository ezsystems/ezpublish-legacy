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

/*! \file view.php
*/

$Module =& $Params['Module'];
$ViewMode = $Params['ViewMode'];
$ItemID = $Params['ItemID'];

$Offset = $Params['Offset'];
if ( !is_numeric( $Offset ) )
    $Offset = 0;

include_once( 'kernel/classes/ezcollaborationitem.php' );

$collabItem =& eZCollaborationItem::fetch( $ItemID );
if ( $collabItem === null )
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );

$collabHandler =& $collabItem->handler();
$collabItem->handleView( $ViewMode );
$template = $collabHandler->template( $ViewMode );
$collabTitle = $collabItem->title();

include_once( 'kernel/classes/ezcollaborationitemhandler.php' );

$viewParameters = array( 'offset' => $Offset );

include_once( 'kernel/common/template.php' );
$tpl =& templateInit();

$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'collab_item', $collabItem );

$Result = array();
$Result['content'] = $tpl->fetch( $template );

$collabHandler->readItem( $collabItem );

$Result['path'] = array( array( 'url' => 'collaboration/view/summary',
                                'text' => ezi18n( 'kernel/collaboration', 'Collaboration' ) ),
                         array( 'url' => false,
                                'text' => $collabTitle ) );

?>
