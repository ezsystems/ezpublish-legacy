<?php
//
// Definition of Settings class
//
// Created on: <14-May-2003 16:30:26 sp>
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

/*! \file settings.php
*/

include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
include_once( 'kernel/common/template.php' );
include_once( "lib/ezutils/classes/ezhttptool.php" );

$http =& eZHTTPTool::instance();

$Module =& $Params['Module'];

$user =& eZUser::currentUser();

include_once( 'kernel/classes/notification/eznotificationeventfilter.php' );
$availableHandlers =& eZNotificationEventFilter::availableHandlers();


if ( $http->hasPostVariable( 'Store' ) )
{
    foreach ( array_keys( $availableHandlers ) as $key )
    {
        $handler =& $availableHandlers[$key];
        $handler->storeSettings( $http, $Module );
    }

}

foreach ( array_keys( $availableHandlers ) as $key )
{
    $handler =& $availableHandlers[$key];

    $handler->fetchHttpInput( $http, $Module );
}

$viewParameters = array( 'offset' => $Params['Offset'] );

$tpl =& templateInit();
$tpl->setVariable( 'user', $user );
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:notification/settings.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/notification', 'Notification settings' ) ) );


?>
