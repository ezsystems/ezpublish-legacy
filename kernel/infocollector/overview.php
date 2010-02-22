<?php
//
// Created on: <13-Feb-2005 03:13:00 bh>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//


$http = eZHTTPTool::instance();
$module = $Params['Module'];
$offset = $Params['Offset'];

if( !is_numeric( $offset ) )
{
    $offset = 0;
}


if( $module->isCurrentAction( 'RemoveObjectCollection' ) && $http->hasPostVariable( 'ObjectIDArray' ) )
{
    $objectIDArray = $http->postVariable( 'ObjectIDArray' );
    $http->setSessionVariable( 'ObjectIDArray', $objectIDArray );

    $collections = 0;

    foreach( $objectIDArray as $objectID )
    {
        $collections += eZInformationCollection::fetchCollectionCountForObject( $objectID );
    }

    $tpl = eZTemplate::factory();
    $tpl->setVariable( 'module', $module );
    $tpl->setVariable( 'collections', $collections );
    $tpl->setVariable( 'remove_type', 'objects' );

    $Result = array();
    $Result['content'] = $tpl->fetch( 'design:infocollector/confirmremoval.tpl' );
    $Result['path'] = array( array( 'url' => false,
                                    'text' => ezpI18n::translate( 'kernel/infocollector', 'Collected information' ) ) );
    return;
}


if( $module->isCurrentAction( 'ConfirmRemoval' ) )
{

    $objectIDArray = $http->sessionVariable( 'ObjectIDArray' );

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


$db = eZDB::instance();
$objects = $db->arrayQuery( 'SELECT DISTINCT ezcontentobject.id AS contentobject_id,
                                             ezcontentobject.name,
                                             ezcontentobject_tree.main_node_id,
                                             ezcontentclass.serialized_name_list,
                                             ezcontentclass.identifier AS class_identifier
                             FROM ezcontentobject,
                                  ezcontentobject_tree,
                                  ezcontentclass
                             WHERE ezcontentobject_tree.contentobject_id = ezcontentobject.id
                                   AND ezcontentobject.contentclass_id = ezcontentclass.id
                                   AND ezcontentclass.version = ' . eZContentClass::VERSION_STATUS_DEFINED . '
                                   AND ezcontentobject.id IN
                                   ( SELECT DISTINCT ezinfocollection.contentobject_id FROM ezinfocollection )
                             ORDER BY ezcontentobject.name ASC',
                             array( 'limit'  => (int)$limit,
                                    'offset' => (int)$offset ) );

$infoCollectorObjectsQuery = $db->arrayQuery( 'SELECT COUNT( DISTINCT ezinfocollection.contentobject_id ) as count
                                               FROM ezinfocollection,
                                                    ezcontentobject,
                                                    ezcontentobject_tree
                                               WHERE
                                                    ezinfocollection.contentobject_id=ezcontentobject.id
                                                    AND ezinfocollection.contentobject_id=ezcontentobject_tree.contentobject_id' );
$numberOfInfoCollectorObjects = 0;

if ( $infoCollectorObjectsQuery )
{
    $numberOfInfoCollectorObjects = $infoCollectorObjectsQuery[0]['count'];
}

foreach ( array_keys( $objects ) as $i )
{
    $firstCollections = eZInformationCollection::fetchCollectionsList( (int)$objects[$i]['contentobject_id'], /* object id */
                                                                       false, /* creator id */
                                                                       false, /* user identifier */
                                                                       array( 'limit' => 1, 'offset' => 0 ), /* limitArray */
                                                                       array( 'created', true ), /* sortArray */
                                                                       false  /* asObject */
                                                                     );
    $objects[$i]['first_collection'] = $firstCollections[0]['created'];

    $lastCollections = eZInformationCollection::fetchCollectionsList( (int)$objects[$i]['contentobject_id'], /* object id */
                                                                      false, /* creator id */
                                                                      false, /* user identifier */
                                                                      array( 'limit' => 1, 'offset' => 0 ), /* limitArray */
                                                                      array( 'created', false ), /* sortArray */
                                                                      false  /* asObject */
                                                                    );
    $objects[$i]['last_collection'] = $lastCollections[0]['created'];

    $objects[$i]['class_name'] = eZContentClassNameList::nameFromSerializedString( $objects[$i]['serialized_name_list'] );
    $objects[$i]['collections']= eZInformationCollection::fetchCollectionCountForObject( $objects[$i]['contentobject_id'] );
}

$viewParameters = array( 'offset' => $offset );

$tpl = eZTemplate::factory();
$tpl->setVariable( 'module', $module );
$tpl->setVariable( 'limit', $limit );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'object_array', $objects );
$tpl->setVariable( 'object_count', $numberOfInfoCollectorObjects );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:infocollector/overview.tpl' );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::translate( 'kernel/infocollector', 'Collected information' ) ) );

?>
