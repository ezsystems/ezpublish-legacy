<?php
//
// Definition of eZContentStructureTreeOperator class
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

/*! \file ezcontentstructuretreeoperator.php
*/

/*!
  \class eZContentStructureTreeOperator ezcontentstructuretreeoperator.php
  \brief
*/

class eZContentStructureTreeOperator
{
    function eZContentStructureTreeOperator( $name = 'content_structure_tree' )
    {
        $this->Operators = array( $name );
    }

    /*!
     Returns the operators in this class.
    */
    function &operatorList()
    {
        return $this->Operators;
    }

    /*!
     See eZTemplateOperator::namedParameterList()
    */
    function namedParameterList()
    {
        return array( 'root_node_id' => array( 'type' => 'int',
                                               'required' => true,
                                               'default' => 0 ),
                      'class_filter' => array( 'type' => 'array',
                                               'required' => false,
                                               'default' => false ),
                      'max_depth' => array( 'type' => 'int',
                                            'required' => false,
                                            'default' => 0 ),
                      'max_nodes' => array( 'type' => 'int',
                                            'required' => false,
                                            'default' => 0 ),
                      'sort_by' => array( 'type' => 'string',
                                          'required' => false,
                                          'default' => 'false' ),
                      'fetch_hidden' => array( 'type' => 'bool',
                                               'required' => false,
                                               'default' => 'false' ) );
    }

    /*!
     \reimp
    */
    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        $sortArray = false;
        $fetchHidden = false;

        if ( $namedParameters[ 'sort_by' ] != 'false' )
        {
            $sortingMethod = explode("/", $namedParameters[ 'sort_by' ]);
            $sortingMethod[1] = ($sortingMethod[1] == 'ascending') ? '1' : '0';

            $sortArray = array();
            $sortArray[] =& $sortingMethod;
        }

        if ( $namedParameters[ 'fetch_hidden' ] != 'false' )
        {
            $fetchHidden = true;
        }

        $operatorValue = eZContentStructureTreeOperator::contentStructureTree( $namedParameters['root_node_id'],
                                                                               $namedParameters['class_filter'],
                                                                               $namedParameters['max_depth'],
                                                                               $namedParameters['max_nodes'],
                                                                               $sortArray,
                                                                               $fetchHidden );
    }

    /*!
     \static
     \return  Return an tree of content structure:
            tree = array( node_desc, children ), where
                'node_desc' is array( 'node'             => eZContentObjectTreeNode,
                                      'object'           => eZContentObject );

                'children' is array( node_desc, children );
    */
    function &contentStructureTree( $rootNodeID, &$classFilter, $maxDepth, $maxNodes, &$sortArray, $fetchHidden )
    {
        $contentTree =& eZContentStructureTreeOperator::initContentStructureTree( $rootNodeID );

        $nodesLeft = $maxNodes - 1;
        $depthLeft = $maxDepth - 1;

        eZContentStructureTreeOperator::children( $contentTree, $classFilter, $depthLeft, $nodesLeft, $sortArray, $fetchHidden );

        return $contentTree;
    }

    /*!
     \static
     \private
        Parameters the same as in \a children.
     \return Returns one-level children.
    */
    function oneLevelChildren( &$contentTree, &$classFilter, &$sortBy, &$nodesLeft, $fetchHidden )
    {
        $parentNode =& $contentTree['parent_node'];
        $parentNodeID = $parentNode['node']->attribute( 'node_id' );

        $parentTreeNode =& eZContentObjectTreeNode::fetch( $parentNodeID );
        if ( !is_null( $parentTreeNode ) )
        {
            // fill parent node attributes
            if ( $nodesLeft != 0)
            {
                // get children
                $sortArray = ( $sortBy == false ) ? $parentTreeNode->sortArray() : $sortBy;
                $children =& eZContentObjectTreeNode::subTree( array( 'Depth' => 1,
                                                                       'Offset' => 0,
                                                                       'SortBy' => $sortArray,
                                                                       'ClassFilterType' => 'include',
                                                                       'ClassFilterArray' => $classFilter ),
                                                                $parentNodeID );
                if ( $children && count( $children ) > 0 )
                {
                    $childrenNodes =& $contentTree['children'];
                    // fill children attributes
                    foreach ( $children as $child )
                    {
                        $childNode =& eZContentStructureTreeOperator::createContentStructureNode( $child );

                        $childrenNodes[] = array( 'parent_node' => &$childNode,
                                                  'children' => array() );
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
        Creates a tree of content by recursive calls to \a children and \a oneLevelChildren.
        \a $contentTree is a tree at previous call to \a children.
        \a $classFilter is an array of class ids. Only nodes of these classes will be fetched from db.
        \a $depthLeft determines a depth of recursion.
        \a $nodesLeft determines a number of nodes which are left to fetch.
        \a $sortBy is a method of sorting one-level children.
        \a $fetchHidden - should or not fetch unpublished/hidden nodes(NOT IMPLEMENTED)
    */
    function children( &$contentTree, &$classFilter, &$depthLeft, &$nodesLeft, &$sortBy, $fetchHidden )
    {
        if ( $depthLeft == 0 )
            return false;

        if ( eZContentStructureTreeOperator::oneLevelChildren( $contentTree, $classFilter, $sortBy, $nodesLeft, $fetchHidden ) )
        {
            --$depthLeft;
            if ( $depthLeft != 0 )
            {
                $children =& $contentTree['children'];

                $childrenCount = count( $children );
                $i = 0;

                while ( $i < $childrenCount )
                {
                    $child =& $children[$i];
                    $currentDepth = $depthLeft;
                    if ( !eZContentStructureTreeOperator::children( $child, $classFilter, $currentDepth, $nodesLeft, $sortBy, $fetchHidden ) )
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
    function &createContentStructureNode( &$contentObjectTreeNode )
    {
        $contentObject =& eZContentObject::fetch( $contentObjectTreeNode->attribute( 'contentobject_id' ) );

        $node = array( 'node' => $contentObjectTreeNode,
                       'object' => &$contentObject );
        return $node;
    }

    /*!
     \static
     \private
        Initializes a tree: creates root node with \a $rootNodeID.
     \return a tree with one node and empty children subtree.
    */
    function &initContentStructureTree( $rootNodeID )
    {
        // create initional subtree with root node and empty children.
        $rootTreeNode =& eZContentObjectTreeNode::fetch( $rootNodeID );

        $rootNode =& eZContentStructureTreeOperator::createContentStructureNode( $rootTreeNode );

        $nodes = array( 'parent_node' => &$rootNode,
                        'children' => array() );
        return $nodes;
    }

    /// \privatesection
    var $Operators;
}
?>
