<?php
//
// Created on: <24-Apr-2002 11:18:59 bf>
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
include_once( "kernel/classes/ezcontentclass.php" );
include_once( "kernel/classes/ezcontentobjecttreenode.php" );

include_once( "lib/ezutils/classes/ezhttptool.php" );

include_once( "kernel/common/template.php" );

$http =& eZHTTPTool::instance();

$tpl =& templateInit();

$ViewMode = $Params['ViewMode'];
$NodeID = $Params['NodeID'];
$Module =& $Params['Module'];
$LanguageCode = $Params['LanguageCode'];
$Offset = $Params['Offset'];
if ( !$Offset )
    $Offset = 0;

$limitationList = array();

if ( array_key_exists( 'Limitation', $Params ) )
{
    $Limitation =& $Params['Limitation'];
    foreach ( $Limitation as $policy )
    {
        $limitationList[] =& $policy->attribute( 'limitations' );
    }
}

$node =& eZContentObjectTreeNode::fetch( $NodeID );
if ( $node === null )
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

$object = $node->attribute( 'object' );

if ( $object === null )
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

if ( !$object->attribute( 'can_read' ) )
{
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
}

if ( $LanguageCode != "" )
{
    $object->setCurrentLanguage( $LanguageCode );
}

$viewParameters = array( 'offset' => $Offset );

$res =& eZTemplateDesignResource::instance();
$res->setKeys( array( array( "object", $object->attribute( "id" ) ), // Object ID
                      array( "class", $object->attribute( "contentclass_id" ) ), // Class ID
                      array( "section", $object->attribute( 'section_id' ) ), // Section ID
                      array( "node", $node->attribute( 'node_id' ) ), // Node ID
                      array( 'viewmode', $ViewMode ),
                      ) );

$tpl->setVariable( "node", $node );
$tpl->setVariable( 'view_parameters', $viewParameters );

// create path
$parents =& $node->attribute( 'path' );

$path = array();
foreach ( $parents as $parent )
{
    $path[] = array( "text" => $parent->attribute( "name" ),
                    "url" => "/content/view/full/" . $parent->attribute( "node_id" )
                    );
}
$path[] = array( "text" => $object->attribute( "name" ),
                 "url" => false );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:content/view/$ViewMode.tpl" );
$Result['view_parameters'] =& $viewParameters;
$Result['path'] =& $path;
?>
