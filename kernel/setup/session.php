<?php
//
// Created on: <15-Apr-2004 11:25:31 bh>
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



include_once( 'kernel/common/template.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'lib/ezutils/classes/ezsession.php' );

$tpl =& templateInit();
$sessionsRemoved = false;
$http =& eZHTTPTool::instance();

$module =& $Params["Module"];
$param['limit'] = 50;

if ( $module->isCurrentAction( 'RemoveAllSessions' ) )
{
    eZSessionEmpty();
    $sessionsRemoved = true;
}
elseif ( $module->isCurrentAction( 'RemoveTimedOutSessions' ) )
{
    eZSessionGarbageCollector();
    $sessionsRemoved = true;
}
elseif ( $module->isCurrentAction( 'RemoveSelectedSessions' ) )
{
    if ( eZHTTPTool::hasPostVariable( 'SessionKeyArray' ) )
    {
        $sessionKeyArray = eZHTTPTool::postVariable( 'SessionKeyArray' );
        foreach( $sessionKeyArray as $sessionKeyItem )
        {
            eZSessionDestroy( $sessionKeyItem );
        }
    }
}

$view_parameters = $Params['UserParameters'];
if ( is_Numeric( $view_parameters['offset'] ) )
{
    $param['offset'] = $view_parameters['offset'];
}
else
{
    $param['offset'] = 0;
    $view_parameters['offset'] = 0;
}

$param['sortby'] = $view_parameters['sortby'];
$sessionsActive = eZSessionCountActive();
$sessionsList =& eZSessionGetActive( $param );

if ( $param['offset'] >= $sessionsActive && $sessionsActive != 0 )
{
    $module->redirectTo( '/setup/session' );
}


$tpl->setVariable( "sessions_removed", $sessionsRemoved );
$tpl->setVariable( "sessions_active",  $sessionsActive  );
$tpl->setVariable( "sessions_list",  $sessionsList  );
$tpl->setVariable( "page_limit",  $param['limit']  );
$tpl->setVariable( "view_parameters",  $view_parameters  );
$tpl->setVariable( "form_parameter_string",  $view_parameters  );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:setup/session.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/setup', 'Session admin' ) ) );

?>
