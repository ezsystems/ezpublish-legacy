<?php
//
// Definition of Updateniceurls class
//
// Created on: <03-ñÎ×-2003 16:05:43 sp>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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

/*! \file updateniceurls.php
*/
set_time_limit ( 0 );
chdir ( '../' );

include_once( 'lib/ezdb/classes/ezdb.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
$db =& eZDb::instance();
$topLevelNodesArray =$db->arrayQuery( 'select node_id from ezcontentobject_tree where depth = 1 order by node_id' );
eZDebug::writeDebug( $topLevelNodesArray  );
var_dump( $topLevelNodesArray );
print( "<br>/" );
foreach ( array_keys( $topLevelNodesArray ) as $key )
{
    $topLevelNodeID = $topLevelNodesArray[$key]['node_id'];
    eZDebug::writeDebug( $topLevelNodeID, "toplevelnode" );
    $rootNode =& eZContentObjectTreeNode::fetch( $topLevelNodeID );
    $rootNode->setAttribute( 'path_identification_string', $rootNode->pathWithNames() );
    eZDebug::writeDebug( $rootNode->pathWithNames() );
    $rootNode->setAttribute( 'crc32_path', crc32 ( $rootNode->attribute( 'path_identification_string' ) ) );
    $rootNode->store();
    $nodes =& $rootNode->subTree();
    foreach( array_keys( $nodes ) as $key )
    {
        $node =& $nodes[ $key ];
        $node->setAttribute( 'path_identification_string', $node->pathWithNames() );
        $node->setAttribute( 'crc32_path', crc32 ( $node->attribute( 'path_identification_string' ) ) );
        $node->setAttribute( 'md5_path', md5 ( $node->attribute( 'path_identification_string' ) ) );
        $node->store();
    }

}
eZDebug::printReport();
?>
