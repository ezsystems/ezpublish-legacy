<?php
//
// Created on: <28-Nov-2002 12:45:40 bf>
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

print( "Starting HiO cron\n" );
include_once( "lib/ezutils/classes/ezdebug.php" );

eZDebug::setHandleType( EZ_HANDLE_FROM_PHP );

include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

//
// Remove objects of annonse class which is more than x days
//
$NumDaysAnnonse = 40;

//
// Remove objects of hendelse class which is more than x days
//
$NumDaysHendelse = 3;

//
// Remove objects of hendelse class which is more than x days
//
$NumberOfArticles = 2;

// Get top node
$node =& eZContentObjectTreeNode::fetch( 2 );


print( "Archiving ads\n" );
// Find all annonser
$subTree =& $node->subTree( array ( 'ClassFilterType' => 'include',
                                   'ClassFilterArray' => array( 18 )
                                   ) );
// Check old ads
foreach ( $subTree as $node )
{
    $object =& $node->attribute( 'object' );
    $published = $object->attribute( 'published' );

    // Calculate how many days the ad have been published
    $diff = (int) ( ( mktime() - $published ) / ( 60 * 60 * 24 ) );
    if ( $diff >= $NumDaysAnnonse )
        $object->remove();
}

/*
// Get top node
$node =& eZContentObjectTreeNode::fetch( 2 );

// Find all hendelser
$subTree =& $node->subTree( array ( 'ClassFilterType' => 'include',
                                   'ClassFilterArray' => array( 9 )
                                   ) );
// Check old hendelser
foreach ( $subTree as $node )
{
    $object =& $node->attribute( 'object' );
    $attributeMap = $object->dataMap();
    $date = $attributeMap['dato']->content();

    $timeStamp = $date->timeStamp();

    // Calculate how many days the ad have been published
    $diff = (int) ( ( mktime() - $timeStamp ) / ( 60 * 60 * 24 ) );
    if ( $diff >= $NumDaysHendelse )
    {
        $object->remove();
    }
}
*/

include_once( "lib/ezutils/classes/ezmodule.php" );
eZModule::setGlobalPathList( array( "kernel" ) );

print( "Archiving news\n" );

// Get top node
$node =& eZContentObjectTreeNode::fetch( 27 );

// Find all annonser
$subTree =& $node->subTree( array ( 'ClassFilterType' => 'include',
                                    'ClassFilterArray' => array( 2 ),
                                    'SortBy' => array( array( 'priority' ) ),
                                    'Offset' => 2,
                                    'Limit' => 100,
                                    'Depth' => 1
                                   ) );

// Create new archive vevside
// check if archive folder exists
$childArray =& $node->childrenByName( 'Arkiv' );


function &createNameNode( $name, $parentID )
{
    // Create Arkiv node
    $class =& eZContentClass::fetch( 25 );
    // Create object by user 14 in section 1
    $contentObject =& $class->instantiate( 14, 1 );
    $nodeAssignment =& eZNodeAssignment::create( array(
                                                     'contentobject_id' => $contentObject->attribute( 'id' ),
                                                     'contentobject_version' => $contentObject->attribute( 'current_version' ),
                                                     'parent_node' => $parentID,
                                                     'main' => 1
                                                     )
                                                 );

    $version =& $contentObject->version( 1 );
    $contentObjectAttributes =& $version->contentObjectAttributes();

    $contentObjectAttributes[0]->setAttribute( 'data_text', $name );
    $contentObjectAttributes[0]->store();

    eZEnum::storeObjectEnumeration( $contentObjectAttributes[1]->attribute( 'id' ),
                                    1,
                                    $eID,
                                    'Meny',
                                    '1' );
    $contentObjectAttributes[1]->store();

    eZEnum::storeObjectEnumeration( $contentObjectAttributes[2]->attribute( 'id' ),
                                    1,
                                    $eID,
                                    'Sentrer',
                                    '1' );
    $contentObjectAttributes[2]->store();

    $version->setAttribute( 'modified', eZDateTime::currentTimeStamp() );
    $version->setAttribute( 'status', EZ_VERSION_STATUS_DRAFT );

    $version->store();

    $nodeAssignment->store();
    include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
    $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $contentObject->attribute( 'id' ),
                                                                                 'version' => 1 ) );
    $object = eZContentObject::fetch( $contentObject->attribute( 'id' ) );
    return $object->attribute( 'main_node_id' );
}

include_once( 'lib/ezlocale/classes/ezdate.php' );

$date = new eZDate();

if ( count( $childArray ) == 0 )
{
    $arkivNodeID = createNameNode( "Arkiv", 27 );
}
else
{
    $arkivNodeID = $childArray[0]->attribute( 'node_id' );
}

// check/create year
$arkivNode =& eZContentObjectTreeNode::fetch( $arkivNodeID );
$childArray =& $arkivNode->childrenByName( $date->year() );
if ( count( $childArray ) == 0 )
{
    $yearNodeID = createNameNode( $date->year(), $arkivNodeID );
}
else
{
    $yearNodeID = $childArray[0]->attribute( 'node_id' );
}

// check month
$yearNode =& eZContentObjectTreeNode::fetch( $yearNodeID );
$childArray =& $yearNode->childrenByName( $date->month() );
if ( count( $childArray ) == 0 )
{
    $monthNodeID = createNameNode( $date->month(), $yearNodeID );
}
else
{
    $monthNodeID = $childArray[0]->attribute( 'node_id' );
}

// Check old hendelser and move to archive vevside
foreach ( $subTree as $node )
{
    $object =& $node->attribute( 'object' );
    $published = $object->attribute( 'published' );

    // Archive
    {
        print( "Archive " . $object->attribute( 'name' ) . "\n" );
        $node->move( $monthNodeID );
//        print( "Moved Old " . $object->attribute( 'name' ) . "<br>" );
    }

}
eZDebug::printReport();

print( "done" );

?>
