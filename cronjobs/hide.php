<?php
//
// Created on: <21-Jan-05 16:00:52 kk>
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

/*! \file hide.php
*/

include_once( "kernel/classes/ezcontentobjecttreenode.php" );
include_once( "lib/ezutils/classes/ezini.php" );

$ini =& eZINI::instance( 'content.ini' );
$rootNodeIDList = $ini->variable( 'HideSettings','RootNodeList' );
$hideAttributeArray = $ini->variable( 'HideSettings', 'HideDateAttributeList' );
$hideClasses = array_keys( $hideAttributeArray );

$currrentDate = time();

$offset = 0;
$limit = 20;

foreach( $rootNodeIDList as $nodeID )
{
    $rootNode = eZContentObjectTreeNode::fetch( $nodeID );

    while( true )
    {
        $nodeArray =& $rootNode->subTree( array( 'ClassFilterType' => 'include',
                                                 'ClassFilterArray' => $hideClasses,
                                                 'Offset' => $offset,
                                                 'Limit' => $limit ) );
        if ( !$nodeArray ||
             count( $nodeArray ) == 0 )
        {
            break;
        }

        $offset += $limit;

        foreach ( array_keys( $nodeArray ) as $key )
        {
            $node =& $nodeArray[$key];
            $dataMap =& $node->attribute( 'data_map' );

            $dateAttributeName = $hideAttributeArray[$node->attribute( 'class_identifier' )];

            if ( !$dateAttributeName )
            {
                continue;
            }

            $dateAttribute =& $dataMap[$dateAttributeName];

            if ( is_null( $dateAttribute ) || !$dateAttribute->hasContent() )
            {
                continue;
            }

            $date = $dateAttribute->content();
            $retractDate = $date->attribute( 'timestamp' );
            if ( $retractDate > 0 && $retractDate < $currrentDate )
            {
                eZContentObjectTreeNode::hideSubTree( $node );
                if ( !$isQuiet )
                {
                    $cli->output( 'Hiding node : ' . $node->attribute( 'node_id' ) );
                }
            }
        }
    }
}


?>
