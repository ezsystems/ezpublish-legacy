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

$tpl =& templateInit();

$ViewMode = $Params['ViewMode'];
$NodeID = $Params['NodeID'];
$Module =& $Params['Module'];
$LanguageCode = $Params['LanguageCode'];
$Offset = $Params['Offset'];

$limitationList = array();

if ( array_key_exists( 'Limitation', $Params ) )
{
    $Limitation =& $Params['Limitation'];
    foreach ( $Limitation as $policy )
    {
        $limitationList[] = $policy->attribute( 'limitations' );
    }
}
//eZDebug::writeNotice( $limitationList, "Limitation" );

$node =& eZContentObjectTreeNode::fetch( $NodeID );
if ( $node === null )
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

$contentObject = $node->attribute( 'contentobject' );
if ( $contentObject === null )
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );

if ( ! $contentObject->attribute( 'can_read' ) )
{
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
}


$object = $node->attribute( 'contentobject' );

if ( $LanguageCode != "" )
{
    $object->setCurrentLanguage( $LanguageCode );
}


if ( $contentObject->attribute( 'section_id' ) == 4 )
{
    eZDebug::writeWarning( "Object child limit is hard coded to 1", "view.php" );
    $Limit = 1;
}
else
{
    $Limit = 15;
}

$children =& $node->subTree( array( 'Depth' => 1,
                                    'Offset' => $Offset,
                                    'Limit' => $Limit,
                                    'Limitation' => $limitationList
                                    ) );

$childrenCount = $node->subTreeCount( array( 'Depth' => 1,
                                             'Limitation' => $limitationList
                                             ) );

$relatedObjectArray =& $object->relatedContentObjectArray( $object->attribute( 'current_version' ) );

$classID = $object->attribute( "contentclass_id" );

$parents =& $node->attribute( 'path' );
$classes =& $object->attribute( 'can_create_class_list' );

$Module->setTitle( "View " . $object->attribute( "class_name" ) . " - " . $object->attribute( "name" ) );

$res =& eZTemplateDesignResource::instance();
$res->setKeys( array( array( "object", $object->attribute( "id" ) ), // Object ID
                      array( "class", $object->attribute( "contentclass_id" ) ), // Class I
                      array( "section", $contentObject->attribute( 'section_id' ) ) // Section ID
                      ) );

$tpl->setVariable( "nodeID", $NodeID );
$tpl->setVariable( "previous", $Offset - $Limit );
$tpl->setVariable( "next", $Offset + $Limit );
$tpl->setVariable( "parents", $parents );
$tpl->setVariable( "object", $object );
$tpl->setVariable( "children", $children );
$tpl->setVariable( "children_count", $childrenCount );

$tpl->setVariable( "related_contentobject_array", $relatedObjectArray );


$tpl->setVariable( "module", $Module );
$tpl->setVariable( 'classes', $classes );

$Result =& $tpl->fetch( "design:content/view/$ViewMode.tpl" );

?>
