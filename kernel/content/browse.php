<?php
//
// Created on: <18-Jul-2002 10:55:01 bf>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

include_once( "kernel/classes/ezcontentobject.php" );
include_once( "kernel/classes/ezcontentobjecttreenode.php" );

include_once( "lib/ezutils/classes/ezhttptool.php" );

include_once( "kernel/common/template.php" );

$tpl =& templateInit();
$http =& eZHTTPTool::instance();

$NodeID = $Params["NodeID"];
$ObjectID = $Params["ObjectID"];
$EditVersion = $Params["EditVersion"];

if ( array_key_exists( 'Limitation', $Params ) )
{
    $Limitation =& $Params['Limitation'];
    foreach ( $Limitation as $policy )
    {
        $limitationList[] = $policy->attribute( 'limitations' );
    }
}


$returnURL = $http->sessionVariable( "BrowseFromPage" );
$browseActionName = $http->sessionVariable( "BrowseActionName" );
$customActionButton = $http->sessionVariable( "CustomActionButton" );
$returnType = $http->sessionVariable( "BrowseReturnType" );

$node =& eZContentObjectTreeNode::fetch( $NodeID );

$contentObject =& $node->attribute( 'object' );

if ( ! $contentObject->attribute( 'can_read' ) )
{
        $Module->redirectTo( '/error/403' );
        return;
}

$objectArray =& $node->subTree( array( "Depth" => 1,
                                       "Offset" => $Offset,
                                       "Limit" => 30,
                                       'Limitation' => $limitationList
                                       ) );
$parents =& $node->attribute( 'path' );

$tpl->setVariable( "main_node", $node );

$tpl->setVariable( "return_url", $returnURL );
$tpl->setVariable( "browse_action_name", $browseActionName );
$tpl->setVariable( "custom_action_button", $customActionButton );

$tpl->setVariable( "return_type", $returnType );

$tpl->setVariable( "node_id", $NodeID );
$tpl->setVariable( "object_id", $ObjectID );
$tpl->setVariable( "edit_version", $EditVersion );

$tpl->setVariable( "object_array", $objectArray );
$tpl->setVariable( "parents", $parents );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:content/browse.tpl" );

?>
