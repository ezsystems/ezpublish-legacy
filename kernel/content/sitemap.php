<?php
//
// Created on: <17-Apr-2002 09:28:12 bf>
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

include_once( 'kernel/classes/ezcontentobject.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'kernel/classes/ezcontentclass.php' );

include_once( 'kernel/common/template.php' );
$http =& eZHTTPTool::instance();

$Module =& $Params['Module'];

if ( $http->hasPostVariable( 'RemoveButton' )  )
{
    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $deleteIDArray =& $http->postVariable( 'DeleteIDArray' );
        foreach ( $deleteIDArray as $deleteID )
        {
            $contentObject = eZContentObject::fetch( $deleteID );
            if ( $contentObject->attribute( 'can_remove' ) )
            {
                $contentObject->remove();
            }
        }
        unset( $contentObject );
    }
}

if ( $http->hasPostVariable( 'NewButton' )  )
{
    if ( $http->hasPostVariable( 'ClassID' ) )
    {
        $parentNode = eZContentObjectTreeNode::fetch( 1 );
        $parentContentObject = $parentNode->attribute( 'contentobject' );
        if ( $parentContentObject->checkAccess( 'create', $http->postVariable( 'ClassID' ) ) == '1' )
        {
            $contentObject =& eZContentObject::createNew( $http->postVariable( 'ClassID' ) );
            $Module->redirectTo( $Module->functionURI( 'edit' ) . '/' . $contentObject->attribute( 'id' ) . '/' . $contentObject->attribute( 'current_version' ) );
            return;
        }else
        {
            $Module->redirectTo( '/error/403' );
            return;
        }
    }
}
$Module->setTitle( 'Sitemap' );

$TopObjectID = $Params['TopObjectID'];
$Offset = $Params['Offset'];



$tpl =& templateInit();

//$classes =& eZContentClass::fetchList( $version = 0, $as_object = true, $user_id = false,
//            array("name"=>"name"), $fields = null );
$parentNode = eZContentObjectTreeNode::fetch( 1 );
$parentContentObject = $parentNode->attribute( 'contentobject' );
$classes = $parentContentObject->attribute( 'can_create_class_list' );
eZDebug::writeNotice(  $parentContentObject, 'returned classes' );


//$tree =& eZContentObject::fetchTree( $TopObjectID );

//$tree = & eZContentObjectTreeNode::fetchListWithObjects( $TopObjectID, true, false, $Offset, 25 );

$mainNode = &eZContentObjectTreeNode::fetch( $TopObjectID );
if ( !get_class( $mainNode ) == 'ezcontentobjecttreenode' )
{
    $Module->redirectTo( '/error/404/' );
    return;
}
unset( $contentObject );

$contentObject = $mainNode->attribute( 'contentobject' );


if ( ! $contentObject->attribute( 'can_read' ) )
{
        $Module->redirectTo( '/error/403' );
        return;
}

$limitationList = array();
if ( array_key_exists( 'Limitation', $Params ) )
{
    $Limitation =& $Params['Limitation'];
    foreach ( $Limitation as $policy )
    {
        $limitationList[] = $policy->attribute( 'limitations' );
    }
}

$subTree =& $mainNode->subTree( array( 'Offset' => $Offset,
                                       'Limit' => 25,
                                       'Limitation' => &$limitationList
                                       ) );

$treeCount = $mainNode->subTreeCount( array('Limitation' => &$limitationList ) );

if( $Offset < 1 )
{
    $tree = array_merge( array( $mainNode ) , $subTree );
}else
{
    $tree = $subTree;
}

$res =& eZTemplateDesignResource::instance();
$res->setKeys( array( array( 'topobject', $TopObjectID ) ) ); // Object ID
//                       array( 'class', $class->attribute( 'id' ) ), // Class ID
//                       array( 'section', 0 ) ) ); // Section ID, 0 so far

$tpl->setVariable( 'top_object_id', $TopObjectID );

$tpl->setVariable( 'tree', $tree );
$tpl->setVariable( 'tree_count', $treeCount );

$tpl->setVariable( 'classes', $classes );

$tpl->setVariable( 'module', $Module );

$tpl->setVariable( 'nodeID', $TopObjectID );

$tpl->setVariable( 'previous', $Offset - 25 );
$tpl->setVariable( 'next', $Offset + 25 );

$Result =& $tpl->fetch( 'design:content/sitemap.tpl' );

?>
