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
        Returns one-level children of node \a $nodeID if \a $countChildren = false,
        othewise returns count of one-level children of node \a nodeID.
    */
    function &subTree( $params, $nodeID, $countChildren = false )
    {
        $nodeListArray = array();

        // sorting params
        $sortingInfo =& eZContentObjectTreeNode::createSortingSQLStrings( $params['SortBy'] );

        // node params
        $notEqParentString =& eZContentObjectTreeNode::createNotEqParentSQLString( $nodeID, 1, false );
        $pathStringCond    =& eZContentObjectTreeNode::createPathConditionSQLString( $params['NodePath'], $params['NodeDepth'], 1, false );

        // class filter
        $classCondition =& eZContentObjectTreeNode::createClassFilteringSQLString( $params['ClassFilterType'], $params['ClassFilterArray'] );

        // permissions
        $limitationParams = false;
        $limitationList =& eZContentObjectTreeNode::getLimitationList( $limitationParams );

        if ( $limitationList === false )
        {
            return $nodeListArray;
        }

        $permissionChecking =& eZContentObjectTreeNode::createPermissionCheckingSQLString( $limitationList );

        // version
        $useVersionName = true;
        $versionNameTables =& eZContentObjectTreeNode::createVersionNameTablesSQLString( $useVersionName );
        $versionNameTargets =& eZContentObjectTreeNode::createVersionNameTargetsSQLString( $useVersionName );
        $versionNameJoins =& eZContentObjectTreeNode::createVersionNameJoinsSQLString( $useVersionName );

        // invisible nodes.
        $showInvisibleNodesCond =& eZContentObjectTreeNode::createShowInvisibleSQLString( false, $params['FetchHidden'] );

        $query = '';
        if ( $countChildren )
        {
            $query = "SELECT count(*) as count
                          FROM
                               ezcontentobject_tree,
                               ezcontentobject,ezcontentclass
                               $versionNameTables
                          WHERE $pathStringCond
                                $classCondition
                                ezcontentclass.version=0 AND
                                $notEqParentString
                                ezcontentobject_tree.contentobject_id = ezcontentobject.id  AND
                                ezcontentclass.id = ezcontentobject.contentclass_id
                                $versionNameJoins
                                $permissionChecking ";
        }
        else
        {
            $query = "SELECT ezcontentobject.*,
                             ezcontentobject_tree.*,
                             ezcontentclass.name as class_name,
                             ezcontentclass.identifier as class_identifier,
                             ezcontentclass.is_container as is_container
                             $versionNameTargets
                      FROM
                             ezcontentobject_tree,
                             ezcontentobject,ezcontentclass
                             $versionNameTables
                             $sortingInfo[attributeFromSQL]
                      WHERE
                             $pathStringCond
                             $sortingInfo[attributeWhereSQL]
                             ezcontentclass.version=0 AND
                             $notEqParentString
                             ezcontentobject_tree.contentobject_id = ezcontentobject.id  AND
                             ezcontentclass.id = ezcontentobject.contentclass_id AND
                             $classCondition
                             ezcontentobject_tree.contentobject_is_published = 1
                             $versionNameJoins
                             $showInvisibleNodesCond
                             $permissionChecking
                      ORDER BY $sortingInfo[sortingFields]";

        }

        $db =& eZDB::instance();
        $nodeListArray =& $db->arrayQuery( $query );

        if ( $countChildren )
        {
            return $nodeListArray[0]['count'];
        }
        else
        {
            return $nodeListArray;
        }
    }

    /*!
     \static
     \Returns a tree of content structure:
            tree = array( tree_node, children ), where
                'tree_node' is array( 'node'             => info about node,
                                      'object'           => info about object );

                'children' is array( tree_node, children );
    */
    function &contentStructureTree( $rootNodeID, &$classFilter, $maxDepth, $maxNodes, &$sortArray, $fetchHidden )
    {
        $contentTree =& eZContentStructureTreeOperator::initContentStructureTree( $rootNodeID, $fetchHidden );

        // if root node is invisible then no point to fetch children
        //if ( count( $contentTree ) == 0 )
        //    return $contentTree;

        if ( $contentTree === false)
            return $contentTree;

        $nodesLeft = $maxNodes - 1;
        $depthLeft = $maxDepth - 1;

        eZContentStructureTreeOperator::children( $contentTree, $classFilter, $depthLeft, $nodesLeft, $sortArray, $fetchHidden );

        return $contentTree;
    }

    /*!
     \static
     \private
        Parameters the same as in \a children.
     \Returns one-level children.
    */
    function oneLevelChildren( &$contentTree, &$classFilter, &$sortBy, &$nodesLeft, $fetchHidden )
    {
        $parentNode =& $contentTree['parent_node'];

        if ( !is_array( $parentNode ) || count( $parentNode['node'] ) == 0 )
        {
            return false;
        }

        if ( !$parentNode['object']['is_container'] || $parentNode['node']['children_count'] == 0 )
        {
            return true;
        }

        // fill parent node attributes
        if ( $nodesLeft != 0)
        {
            // get children
            $sortArray = ( $sortBy == false ) ? $parentNode['node']['sort_array'] : $sortBy;

            $children =& eZContentStructureTreeOperator::subTree( array(  'SortBy' => $sortArray,
                                                                          'ClassFilterType' => 'include',
                                                                          'ClassFilterArray'=> $classFilter,
                                                                          'NodePath' => $parentNode['node']['path_string'],
                                                                          'NodeDepth' => $parentNode['node']['depth'],
                                                                          'FetchHidden' => $fetchHidden ),
                                                                  $parentNode['node']['node_id'] );
            if ( $children && count( $children ) > 0 )
            {
                $childrenNodes =& $contentTree['children'];
                // fill children attributes
                foreach ( $children as $child )
                {
                    $childrenCount = 0;

                    if ( $child['is_container'] == '1' )
                    {
                        $childrenCount = eZContentStructureTreeOperator::subTree( array(  'SortBy' => false,
                                                                              'ClassFilterType' => 'include',
                                                                              'ClassFilterArray'=> $classFilter,
                                                                              'NodePath' => $child['path_string'],
                                                                              'NodeDepth' => $child['depth'],
                                                                              'FetchHidden' => $fetchHidden ),
                                                                      $child['node_id'],
                                                                      true );
                    }

                    $childNode =& eZContentStructureTreeOperator::createContentStructureNode( $child, $childrenCount );

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
        \a $fetchHidden - should or not fetch unpublished/hidden nodes
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

                $children_keys = array_keys( $children );

                foreach( $children_keys as $key )
                {
                    $child =& $children[$key];
                    $currentDepth = $depthLeft;
                    if ( !eZContentStructureTreeOperator::children( $child, $classFilter, $currentDepth, $nodesLeft, $sortBy, $fetchHidden ) )
                    {
                        return false;
                    }
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
        using \a $treeNode to retrieve necessary data.
     \return new content structure tree node.
    */
    function &createContentStructureNode( &$treeNode, $childrenCount )
    {
        $node = array( 'node' => array( 'node_id' => $treeNode['node_id'],
                                        'path_identification_string' => $treeNode['path_identification_string'],
                                        'children_count' => $childrenCount,
                                        'sort_array' => eZContentObjectTreeNode::sortArrayBySortFieldAndSortOrder( $treeNode['sort_field'], $treeNode['sort_order'] ),
                                        'path_string' => $treeNode['path_string'],
                                        'depth' => $treeNode['depth'],
                                        'is_hidden' => $treeNode['is_hidden'],
                                        'is_invisible' => $treeNode['is_invisible'] ),
                       'object' => array( 'id' => $treeNode['id'],
                                          'name' => $treeNode['name'],
                                          'class_identifier' => $treeNode['class_identifier'],
                                          'class_name' => $treeNode['class_name'],
                                          'published' => $treeNode['published'],
                                          'is_container' => ( $treeNode['is_container'] == '1' ) ) );
        return $node;
    }

    /*!
     \static
     \private
        Initializes a tree: creates root node.
     \return a tree with one node and empty children subtree.
    */
    function &initContentStructureTree( $rootNodeID, $fetchHidden )
    {
        // create initial subtree with root node and empty children.
        $rootTreeNode =& eZContentObjectTreeNode::fetch( $rootNodeID );
        if( $rootTreeNode && $rootTreeNode->canRead() )
        {
            $contentObject =& $rootTreeNode->attribute( 'object' );

            if ( !$fetchHidden && ( $rootTreeNode->attribute( 'is_hidden' ) || $rootTreeNode->attribute( 'is_invisible' ) ) )
            {
                $nodes = false;
            }
            else
            {
                $rootNode = array( 'node' => array( 'node_id' => $rootTreeNode->attribute( 'node_id' ),
                                                    'path_identification_string' => $rootTreeNode->attribute( 'path_identification_string' ),
                                                    'children_count' => $rootTreeNode->attribute( 'children_count' ),
                                                    'sort_array' => $rootTreeNode->attribute( 'sort_array' ),
                                                    'path_string' => $rootTreeNode->attribute( 'path_string' ),
                                                    'depth' => $rootTreeNode->attribute( 'depth' ),
                                                    'is_hidden' => $rootTreeNode->attribute( 'is_hidden' ),
                                                    'is_invisible' => $rootTreeNode->attribute( 'is_invisible' ) ),
                                   'object' => array( 'id' => $contentObject->attribute( 'id' ),
                                                      'name' => $contentObject->attribute( 'name' ),
                                                      'class_identifier' => $contentObject->attribute( 'class_identifier' ),
                                                      'class_name' => $contentObject->attribute('class_name'),
                                                      'published' => $contentObject->attribute( 'published' ),
                                                      'is_container' => true ) );

                $nodes = array( 'parent_node' => &$rootNode,
                                'children' => array() );
            }
        }
        else
        {
            $nodes = false;
        }

        return $nodes;
    }

    /// \privatesection
    var $Operators;
}
?>
