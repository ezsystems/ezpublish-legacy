<?php
//
// Created on: <20-Oct-2005 23:15:12 ymc-dabe>
//
// Copyright (C) 2005 Young Media Concepts GmbH. All rights reserved.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// If you wish to use this extension under conditions others than the
// GPL you need to contact Young MediaConcepts firtst (licence@ymc.ch).
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ymc.ch if any conditions of this licencing isn't clear to
// you.
//


include_once( 'kernel/error/errors.php' );

class eZOdcsmFunctionCollection
{
    /*!
     Constructor
    */
    function eZOdcsmFunctionCollection()
    {
    }


    /*!
     Returns an array with current open nodeIDs
    */
    function &dynamicContentStrukturMenuOpenNodes( $nodeID = 0 )
    {
        $ezcst_unfolded_node_array = array();

        if ( isset($_COOKIE['ezcst_unfolded_node_list']) )
        {
            $ezcst_unfolded_node_list = $_COOKIE['ezcst_unfolded_node_list'];
            $ezcst_unfolded_node_array = split(',n', substr( $ezcst_unfolded_node_list, 1 ));

            if ( $ezcst_unfolded_node_array[0] == "" or $ezcst_unfolded_node_array[0] == null )
            {
                $ezcst_unfolded_node_array = array_slice($ezcst_unfolded_node_array, 1);
            }
        }

        if ( $nodeID > 0 )
        {
            $node = eZContentObjectTreeNode::fetch( $nodeID );
            if ( is_object($node) )
            {
                $parentNodeIDs = $node->attribute('path_array');
                foreach ( $parentNodeIDs as $parentNodeID )
                {
                    $ezcst_unfolded_node_array[] = $parentNodeID;
                }
                $ezcst_unfolded_node_array = array_unique($ezcst_unfolded_node_array);
            }
        }

        $retValue = array( 'result' => array_unique($ezcst_unfolded_node_array) );
        return $retValue;
   }
}

?>
