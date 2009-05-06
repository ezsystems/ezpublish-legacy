<?php
//
// Created on: <21-Jan-05 16:00:52 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

/*! \file
*/

$ini = eZINI::instance( 'content.ini' );
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
        $nodeArray = $rootNode->subTree( array( 'ClassFilterType' => 'include',
                                                'ClassFilterArray' => $hideClasses,
                                                'Offset' => $offset,
                                                'Limit' => $limit ) );
        if ( !$nodeArray ||
             count( $nodeArray ) == 0 )
        {
            break;
        }

        $offset += $limit;

        foreach ( $nodeArray as $node )
        {
            $dataMap = $node->attribute( 'data_map' );

            $dateAttributeName = $hideAttributeArray[$node->attribute( 'class_identifier' )];

            if ( !$dateAttributeName )
            {
                continue;
            }

            $dateAttribute = $dataMap[$dateAttributeName];

            if ( $dateAttribute === null || !$dateAttribute->hasContent() )
            {
                continue;
            }

            $date = $dateAttribute->content();
            $retractDate = $date->attribute( 'timestamp' );
            if ( $retractDate > 0 && $retractDate < $currrentDate && !$node->attribute( 'is_hidden' ) )
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
