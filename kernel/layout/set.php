<?php
//
// Created on: <09-Oct-2002 15:33:01 amos>
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
include_once( "lib/ezutils/classes/ezhttptool.php" );

$LayoutStyle = $Params['LayoutStyle'];
$Module =& $Params['Module'];

$Result = array();
$Result['content'] = '';
$Result['rerun_uri'] = '/' . implode( '/', array_splice( $Params['Parameters'], 1 ) );

$layoutINI =& eZINI::instance( 'layout.ini' );
if ( $layoutINI->hasGroup( $LayoutStyle ) )
{
    if ( $layoutINI->hasVariable( $LayoutStyle, 'PageLayout' ) )
        $Result['pagelayout'] = $layoutINI->variable( $LayoutStyle, 'PageLayout' );

    include_once( 'kernel/common/eztemplatedesignresource.php' );
    $res =& eZTemplateDesignResource::instance();
    $res->setKeys( array( array( 'layout', $LayoutStyle ) ) );

    if ( $layoutINI->hasVariable( $LayoutStyle, 'UseAccessPass' ) && $layoutINI->variable( $LayoutStyle, 'UseAccessPass' ) == 'false' )
    {
    }
    else
    {
        include_once( 'lib/ezutils/classes/ezsys.php' );
        eZSys::addAccessPath( array( 'layout', 'set', $LayoutStyle ) );
    }



    $useFullUrl = false;
    $http =& eZHTTPTool::instance();
    $http->UseFullUrl = false;
    if ( $layoutINI->hasVariable( $LayoutStyle, 'PageLayout' ) )
    {
        if ( $layoutINI->variable( $LayoutStyle, 'UseFullUrl' ) == 'true' )
        {
            $http->UseFullUrl = true;
        }
    }

    $Module->setExitStatus( EZ_MODULE_STATUS_RERUN );
}
else
{
    eZDebug::writeError( 'No such layout style: ' . $LayoutStyle, 'layout/set' );
}

?>
