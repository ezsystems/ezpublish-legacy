<?php
//
// Created on: <24-Jan-2003 17:35:58 amos>
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

$Module =& $Params['Module'];

$http =& eZHTTPTool::instance();
eZDebug::writeDebug( $http->attribute( 'post' ) );

if ( $Module->isCurrentAction( 'Custom' ) )
{
    $typeIdentifier = $Module->actionParameter( 'TypeIdentifer' );
    $itemID = $Module->actionParameter( 'ItemID' );
    include_once( 'kernel/classes/ezcollaborationitem.php' );
    include_once( 'kernel/classes/ezcollaborationitemhandler.php' );
    $collaborationItem =& eZCollaborationItem::fetch( $itemID );
    $handler =& eZCollaborationItemHandler::instantiate( $typeIdentifier );
    return $handler->handleCustomAction( $Module, $collaborationItem );
}

$Result = array();
$Result['content'] = false;
$Result['path'] = array( array( 'url' => false,
                                ezi18n( 'kernel/collaboration', 'Collaboration custom action' ) ) );

?>
