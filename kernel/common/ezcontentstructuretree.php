<?php
//
// Definition of eZContentStructureTree class
//      
// Created on: <14-Jul-2004 14:18:58 dl>
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
            
/*! \file ezcontentstructuretree.php 
*/

/*! 
  \class eZContentStructureTree ezcontentstructuretree.php 
  \brief Creates content structre tree. 
*/

include_once( 'kernel/classes/ezcontentobjecttreenode.php' );

class eZContentStructureTree
{
    /*!
        Constructor
    */    
    function eZContentStructureTree()
    {
    }

    /*!
     \static
     \return  Return an tree of content structure:
            tree = array( node_desc, children ), where
                'node_desc' is array( 'node_id'             => int,
                                      'name'                => string,
                                      'path_id_string'      => string,
                                      'content_class_id'    => string,
                                      'tool_tip'            => array( 'node_id'        => int,
                                                                      'published'      => int
                                                                      'children_count' => int ) );
                                      
                'children' is array( node_desc, children );
    */
    function& getContentStructureTree( $rootNodeID, &$classFilter, $maxDepth, $maxNodes, &$sortArray, $fetchHidden )
    {
        $contentTree =& eZContentStructureTree::initContentStructureTree( $rootNodeID );

        $nodesLeft   = $maxNodes - 1;
        $depthLeft   = $maxDepth - 1;

        eZContentStructureTree::getChildren( $contentTree, $classFilter, $depthLeft, $nodesLeft, $sortArray, $fetchHidden );
                                
        return $contentTree;
    }

    /*!
     \static
     \private
        Parameters the same as in \a getChildren.
     \return Returns one-level children.
    */
    function getOneLevelChildren( &$contentTree, &$classFilter, &$sortBy, &$nodesLeft, $fetchHidden )
    {
        $parentNode     =& $contentTree['parent_node'];
        $parentNodeID   =  $parentNode['node_id'];
    
        $parentTreeNode =& eZContentObjectTreeNode::fetch( $parentNodeID );
        if ( !is_null( $parentTreeNode ) )
        {
            // fill parent node attributes
            if ( $nodesLeft != 0)
            {
                // get children
                $sortArray      = ( $sortBy == false ) ? $parentTreeNode->sortArray() : $sortBy;
                $children       =& eZContentObjectTreeNode::subTree ( array( 'Depth'           => 1,
                                                                             'Offset'          => 0,
                                                                             'SortBy'          => $sortArray,
                                                                             'ClassFilterType' => 'include',
                                                                             'ClassFilterArray'=> $classFilter ),
                                                                      $parentNodeID );
                
                if ( $children && count( $children ) > 0 )
                {
                    $childrenNodes =& $contentTree[ 'children' ];
                    // fill children attributes
                    foreach ( $children as $child )
                    {
                        $childNode  =& eZContentStructureTree::createContentStructureNode( $child );
    
                        $childrenNodes[]            = array( 'parent_node'  => $childNode,
                                                             'children'     => array() );
    
                        --$nodesLeft;
                        if ( $nodesLeft == 0 )
                        {
                            return false;
                        }
                    }
                }

                return true;
            }
        }

        return false;
    }
    
    /*!
     \static
     \private
        Creates a tree of content by recursive calls to \a getChildren and \a getOneLevelChildren.
        \a $contentTree is a tree at previous call to \a getChildren.
        \a $classFilter is an array of class ids. Only nodes of these classes will be fetched from db.
        \a $depthLeft determines a depth of recursion.
        \a $nodesLeft determines a number of nodes which are left to fetch.
        \a $sortBy is a method of sorting one-level children.
        \a $fetchHidden - should or not fetch unpublished/hidden nodes(NOT IMPLEMENTED)
    */
    function getChildren( &$contentTree, &$classFilter, &$depthLeft, &$nodesLeft, &$sortBy, $fetchHidden )
    {
        if ( $depthLeft == 0 )
            return false;

        if ( eZContentStructureTree::getOneLevelChildren( $contentTree, $classFilter, $sortBy, $nodesLeft, $fetchHidden ) )
        {
            --$depthLeft;
            if ( $depthLeft != 0 )
            {
                $children =& $contentTree['children'];

                $childrenCount  = count( $children );
                $i              = 0;
            
                while ( $i < $childrenCount )
                {
                    $child =& $children[$i];
                    $currentDepth = $depthLeft;
                    if ( !eZContentStructureTree::getChildren( $child, $classFilter, $currentDepth, $nodesLeft, $sortBy, $fetchHidden ) )
                    {
                        return false;
                    }
                    ++$i;
                }
            }
            return true;
        }
        return false;
    }
    
    /*!
     \static
     \private
        Creates a node of content structure tree and sets up attributes of node
        using \a $contentObjectTreeNode to retrieve necessary data.
     \return new content structure tree node.   
    */
    function& createContentStructureNode( &$contentObjectTreeNode )
    {
        $contentObject  =& eZContentObject::fetch( $contentObjectTreeNode->attribute( 'contentobject_id' ) );
        
        $toolTip        = array( 'node_id'          => $contentObjectTreeNode->attribute( 'node_id' ),
                                 'published'        => $contentObject->attribute( 'published' ),
                                 'children_count'   => $contentObjectTreeNode->attribute( 'children_count' ) );
        
        $node           = array( 'node_id'          => $contentObjectTreeNode->attribute( 'node_id' ),
                                 'name'             => $contentObject->attribute( 'name' ),
                                 'path_id_string'   => $contentObjectTreeNode->attribute( 'path_identification_string' ),
                                 'content_class_id' => $contentObject->attribute( 'class_identifier' ),
                                 'tool_tip'         => $toolTip );
        
        return $node;
    }

    /*!
     \static
     \private
        Initializes a tree: creates root node with \a $rootNodeID.
     \return a tree with one node and empty children subtree.
    */
    function& initContentStructureTree( $rootNodeID )
    {
        // create initional subtree with root node and empty children.
        $rootTreeNode   =& eZContentObjectTreeNode::fetch( $rootNodeID );
        
        $rootNode       =& eZContentStructureTree::createContentStructureNode( $rootTreeNode );
                       
        $nodes                  = array();
        $nodes[ 'parent_node' ] = $rootNode;
        $nodes[ 'children'    ] = array();

        return $nodes;
    }
}

?>
