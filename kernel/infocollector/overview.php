<?php
//
// Created on: <13-Feb-2005 03:13:00 bh>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

include_once( 'kernel/common/template.php' );
include_once( 'kernel/classes/ezpreferences.php' );
include_once( 'kernel/classes/ezinformationcollection.php' );

$http =& eZHTTPTool::instance();
$module =& $Params['Module'];
$offset = $Params['Offset'];

if( !is_numeric( $offset ) )
{
    $offset = 0;
}


if( $module->isCurrentAction( 'RemoveObjectCollection' ) && $http->hasPostVariable( 'ObjectIDArray' ) )
{
    $objectIDArray =& $http->postVariable( 'ObjectIDArray' );
    $http->setSessionVariable( 'ObjectIDArray', $objectIDArray );

    $collections = 0;

    foreach( $objectIDArray as $objectID )
    {
        $collections += eZInformationCollection::fetchCollectionCountForObject( $objectID );
    }

    $tpl =& templateInit();
    $tpl->setVariable( 'module', $module );
    $tpl->setVariable( 'collections', $collections );
    $tpl->setVariable( 'remove_type', 'objects' );

    $Result = array();
    $Result['content'] =& $tpl->fetch( 'design:infocollector/confirmremoval.tpl' );
    $Result['path'] = array( array( 'url' => false,
                                    'text' => ezi18n( 'kernel/infocollector', 'Collected information' ) ) );
    return;
}


if( $module->isCurrentAction( 'ConfirmRemoval' ) )
{

    $objectIDArray =& $http->sessionVariable( 'ObjectIDArray' );

    if( is_array( $objectIDArray) )
    {
        foreach( $objectIDArray as $objectID )
        {
            eZInformationCollection::removeContentObject( $objectID );
        }
    }
}


if( eZPreferences::value( 'admin_infocollector_list_limit' ) )
{
    switch( eZPreferences::value( 'admin_infocollector_list_limit' ) )
    {
        case '2': { $limit = 25; } break;
        case '3': { $limit = 50; } break;
        default:  { $limit = 10; } break;
    }
}
else
{
    $limit = 10;
}


$db =& eZDB::instance();
$objects = $db->arrayQuery( 'SELECT DISTINCT ezinfocollection.contentobject_id, ezcontentobject.name, ezcontentobject_tree.main_node_id, ezcontentclass.name AS class_name, ezcontentclass.identifier AS class_identifier FROM ezinfocollection, ezcontentobject, ezcontentobject_tree, ezcontentclass WHERE ezinfocollection.contentobject_id=ezcontentobject.id AND ezcontentobject.contentclass_id=ezcontentclass.id AND ezinfocollection.contentobject_id=ezcontentobject_tree.contentobject_id LIMIT ' . $limit . ' OFFSET ' . $offset );


for( $i=0; $i<count( $objects ); $i++ )
{
    $collections = $db->arrayQuery( 'SELECT * FROM ezinfocollection WHERE contentobject_id=' . $objects[$i]['contentobject_id'] );

    $first = $collections[0]['created'];
    $last  = $first;

    for($j=0; $j<count( $collections ); $j++ )
    {
        $current = $collections[$j]['created'];

        if( $current < $first )
            $first = $current;

        if( $current > $last )
            $last = $current;
    }

    $objects[$i]['first_collection'] = $first;
    $objects[$i]['last_collection'] = $last;
    $objects[$i]['collections']= count( $collections );
}

$viewParameters = array( 'offset' => $offset );

$tpl =& templateInit();
$tpl->setVariable( 'module', $module );
$tpl->setVariable( 'limit', $limit );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'object_array', $objects );
$tpl->setVariable( 'object_count', count( $objects ) );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:infocollector/overview.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/infocollector', 'Collected information' ) ) );

?>
