<?php
//
// Created on: <04-Jul-2003 10:30:48 wy>
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

/*! \file view.php
*/
include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );

$Module =& $Params["Module"];
$urlID = null;
if ( isset( $Params["ID"] ) )
    $urlID = $Params["ID"];

if ( is_numeric( $urlID ) )
{
    $url =& eZURL::fetch( $urlID );
    if ( !$url )
    {
        return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
    }
}
else
{
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
}

$http =& eZHttpTool::instance();
if ( $Module->isCurrentAction( 'Cancel' ) )
{
    $Module->redirectToView( 'list' );
}

if ( $Module->isCurrentAction( 'Store' ) )
{
    if ( $http->hasPostVariable( 'link' ) )
    {
        $link = $http->postVariable( 'link' );
        $url->setAttribute( 'url', $link );
        $url->store();
    }
    $Module->redirectToView( 'list' );
}

$Module->setTitle( "Edit link " . $url->attribute( "id" ) );

// Template handling
include_once( "kernel/common/template.php" );
$tpl =& templateInit();

$tpl->setVariable( "Module", $Module );
$tpl->setVariable( "url", $url );

$Result = array();
$Result['content'] = $tpl->fetch( "design:url/edit.tpl" );
$Result['path'] = array( array( 'url' => '/url/edit/',
                                'text' => ezi18n( 'kernel/url', 'URL edit' ) ) );
?>
