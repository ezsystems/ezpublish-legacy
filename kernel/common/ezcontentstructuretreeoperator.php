<?php
//
// Definition of eZContentStructureTreeOperator class
//
// Created on: <14-Jul-2004 14:18:58 dl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file ezcontentstructuretreeoperator.php
*/

/*!
  \class eZContentStructureTreeOperator ezcontentstructuretreeoperator.php
  \brief
*/

require_once( 'kernel/classes/ezcontentlanguage.php' );
//include_once( 'kernel/classes/ezcontentclassnamelist.php' );

class eZContentStructureTreeOperator
{
    function eZContentStructureTreeOperator( $name = 'content_structure_tree' )
    {
        $this->Operators = array( $name );
    }

    /*!
     Returns the operators in this class.
    */
    function operatorList()
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
                      'sort_by' => array( 'type' => 'array',
                                          'required' => false,
                                          'default' => 'false' ),
                      'fetch_hidden' => array( 'type' => 'bool',
                                               'required' => false,
                                               'default' => 'false' ),
                      'unfold_node_id' => array( 'type' => 'int',
                                               'required' => false,
                                               'default' => 0 ) );
    }

    /*!
     \reimp
    */
    function modify( $tpl, $operatorName, $operatorParameters, $rootNamespace, $currentNamespace, &$operatorValue, $namedParameters )
    {
        $sortArray = false;
        $fetchHidden = false;
        if ( $namedParameters[ 'sort_by' ] != 'false' )
        {
            if( is_array($namedParameters[ 'sort_by' ]))
            {
                $sortArray = array();
                foreach( $namedParameters[ 'sort_by' ] as $parameter )
                {
                    $sortingMethod = explode("/", $parameter );
                    $sortingMethod[1] = ($sortingMethod[1] == 'ascending') ? '1' : '0';
                    $sortArray[] = $sortingMethod;
                }
            }
            else
            {
                $sortingMethod = explode("/", $namedParameters[ 'sort_by' ]);
                $sortingMethod[1] = ($sortingMethod[1] == 'ascending') ? '1' : '0';
                $sortArray = array();
                $sortArray[] = $sortingMethod;
            }
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
                                                                               $fetchHidden,
                                                                               $namedParameters['unfold_node_id'] );
    }

    /*!
        \static
        Returns one-level children of node \a $nodeID if \a $countChildren = false,
        othewise returns count of one-level children of node \a nodeID.
    */
    function subTree( $params, $nodeID, $countChildren = false )
    {
        $nodeListArray = array();

        // sorting params
        $sortingInfo = eZContentObjectTreeNode::createSortingSQLStrings( $params['SortBy'] );

        // node params
        $notEqParentString = '';
        $pathStringCond    = '';
        eZContentObjectTreeNode::createPathConditionAndNotEqParentSQLStrings( $pathStringCond, $notEqParentString, $nodeID, 1, false );

        // class filter
        $classCondition = eZContentObjectTreeNode::createClassFilteringSQLString( $params['ClassFilterType'], $params['ClassFilterArray'] );
        if ( $classCondition === false )
        {
            return $nodeListArray;
        }

        // permissions
        $limitationParams = false;
        $limitationList = eZContentObjectTreeNode::getLimitationList( $limitationParams );

        if ( $limitationList === false )
        {
            return $nodeListArray;
        }

        $permissionChecking = eZContentObjectTreeNode::createPermissionCheckingSQL( $limitationList );

        // version
        $useVersionName = true;
        $versionNameTables = eZContentObjectTreeNode::createVersionNameTablesSQLString( $useVersionName );
        $versionNameTargets = eZContentObjectTreeNode::createVersionNameTargetsSQLString( $useVersionName );
        $versionNameJoins = eZContentObjectTreeNode::createVersionNameJoinsSQLString( $useVersionName );

        // invisible nodes.
        $showInvisibleNodesCond = eZContentObjectTreeNode::createShowInvisibleSQLString( false, $params['FetchHidden'] );

        $query = '';
        if ( $countChildren )
        {
            $query = "SELECT count(*) as count
                          FROM
                               ezcontentobject_tree,
                               ezcontentobject,ezcontentclass
                               $versionNameTables
                               $permissionChecking[from]
                          WHERE $pathStringCond
                                $classCondition
                                ezcontentclass.version=0 AND
                                $notEqParentString
                                ezcontentobject_tree.contentobject_id = ezcontentobject.id  AND
                                ezcontentclass.id = ezcontentobject.contentclass_id
                                $versionNameJoins
                                $permissionChecking[where] ";
        }
        else
        {
            $query = "SELECT ezcontentobject.*,
                             ezcontentobject_tree.*,
                             ezcontentclass.serialized_name_list as class_serialized_name_list,
                             ezcontentclass.identifier as class_identifier,
                             ezcontentclass.is_container as is_container
                             $versionNameTargets
                      FROM
                             ezcontentobject_tree,
                             ezcontentobject,ezcontentclass
                             $versionNameTables
                             $sortingInfo[attributeFromSQL]
                             $permissionChecking[from]
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
                             $permissionChecking[where]
                      ORDER BY $sortingInfo[sortingFields]";

        }

        $db = eZDB::instance();
        $nodeListArray = $db->arrayQuery( $query );

        // cleanup temp tables
        $db->dropTempTableList( $permissionChecking['temp_tables'] );

        if ( $countChildren )
        {
            return $nodeListArray[0]['count'];
        }
        else
        {
            foreach ( $nodeListArray as $key => $row )
            {
                $nodeListArray[$key]['path_identification_string'] = eZContentObjectTreeNode::fetch( $row['node_id'] )->pathWithNames();
            }
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
    function contentStructureTree( $rootNodeID, $classFilter, $maxDepth, $maxNodes, $sortArray, $fetchHidden, $unfoldNodeID )
    {
        $contentTree = eZContentStructureTreeOperator::initContentStructureTree( $rootNodeID, $fetchHidden, $classFilter );

        // if root node is invisible then no point to fetch children
        //if ( count( $contentTree ) == 0 )
        //    return $contentTree;

        if ( $contentTree === false)
            return $contentTree;

        $nodesLeft = $maxNodes - 1;
        $depthLeft = $maxDepth - 1;

        eZContentStructureTreeOperator::children( $contentTree, $classFilter, $depthLeft, $nodesLeft, $sortArray, $fetchHidden, $unfoldNodeID );

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
        $parentNode = $contentTree['parent_node'];

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

            $children = eZContentStructureTreeOperator::subTree( array(  'SortBy' => $sortArray,
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

                    $childNode = eZContentStructureTreeOperator::createContentStructureNode( $child, $childrenCount );

                    $childrenNodes[] = array( 'parent_node' => $childNode,
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
    function children( &$contentTree, &$classFilter, &$depthLeft, &$nodesLeft, &$sortBy, $fetchHidden, $unfoldNodeID )
    {
        if ( $depthLeft == 0 )
            return false;

        if ( eZContentStructureTreeOperator::oneLevelChildren( $contentTree, $classFilter, $sortBy, $nodesLeft, $fetchHidden ) )
        {
            --$depthLeft;
            if ( $depthLeft != 0 )
            {
                $children =& $contentTree['children'];

                foreach( $children as &$child )
                {
                    $currentDepth = $depthLeft;

                    if ( $unfoldNodeID != 0 and $unfoldNodeID != $child['parent_node']['node']['node_id'] )
                        continue;

                    if ( !eZContentStructureTreeOperator::children( $child, $classFilter, $currentDepth, $nodesLeft, $sortBy, $fetchHidden, 0 ) )
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
    function createContentStructureNode( &$treeNode, $childrenCount )
    {
        $node = array( 'node' => array( 'node_id' => $treeNode['node_id'],
                                        'path_identification_string' => $treeNode['path_identification_string'],
                                        'children_count' => $childrenCount,
                                        'sort_array' => eZContentObjectTreeNode::sortArrayBySortFieldAndSortOrder( $treeNode['sort_field'], $treeNode['sort_order'] ),
                                        'path_string' => $treeNode['path_string'],
                                        'depth' => $treeNode['depth'],
                                        'is_hidden' => $treeNode['is_hidden'],
                                        'is_invisible' => $treeNode['is_invisible'] ),
                                        'classes_js_array' => eZContentObjectTreeNode::availableClassListJsArray( array( 'path_string' => $treeNode['path_string'], 'is_container' => $treeNode['is_container'], 'node_id' => $treeNode['node_id'] ) ),
                       'object' => array( 'id' => $treeNode['id'],
                                          'name' => $treeNode['name'],
                                          'class_identifier' => $treeNode['class_identifier'],
                                          'class_name' => eZContentClass::nameFromSerializedString( $treeNode['class_serialized_name_list'] ),
                                          'published' => $treeNode['published'],
                                          'is_container' => ( $treeNode['is_container'] == '1' ),
                                          'language_js_array' => eZContentLanguage::jsArrayByMask( $treeNode['language_mask'] ) ) );
        return $node;
    }

    /*!
     \static
     \private
        Initializes a tree: creates root node.
     \return a tree with one node and empty children subtree.
    */
    function initContentStructureTree( $rootNodeID, $fetchHidden, $classFilter = false )
    {
        // create initial subtree with root node and empty children.
        $nodes = false;
        $rootTreeNode = eZContentObjectTreeNode::fetch( $rootNodeID );
        if ( $rootTreeNode && $rootTreeNode->canRead() )
        {
            if ( !$fetchHidden && ( $rootTreeNode->attribute( 'is_hidden' ) || $rootTreeNode->attribute( 'is_invisible' ) ) )
            {
                return false;
            }
            else
            {
                $contentObject = $rootTreeNode->attribute( 'object' );

                $viewNodeAllowed = true;
                if ( is_array( $classFilter ) && count( $classFilter ) > 0 )
                {
                    $contentClassIdentifier = $contentObject->attribute( 'class_identifier' );

                    if ( !in_array( $contentClassIdentifier, $classFilter ) )
                        $viewNodeAllowed = false;
                }

                if ( $viewNodeAllowed )
                {
                    $rootNode = array( 'node' => array( 'node_id' => $rootTreeNode->attribute( 'node_id' ),
                                                        'path_identification_string' => $rootTreeNode->pathWithNames(),
                                                        'children_count' => $rootTreeNode->attribute( 'children_count' ),
                                                        'sort_array' => $rootTreeNode->attribute( 'sort_array' ),
                                                        'path_string' => $rootTreeNode->attribute( 'path_string' ),
                                                        'depth' => $rootTreeNode->attribute( 'depth' ),
                                                        'is_hidden' => $rootTreeNode->attribute( 'is_hidden' ),
                                                        'is_invisible' => $rootTreeNode->attribute( 'is_invisible' ) ),
                                                        'classes_js_array' => eZContentObjectTreeNode::availableClassListJsArray( array( 'node' => &$rootTreeNode ) ),
                                       'object' => array( 'id' => $contentObject->attribute( 'id' ),
                                                          'name' => $contentObject->attribute( 'name' ),
                                                          'class_identifier' => $contentObject->attribute( 'class_identifier' ),
                                                          'class_name' => $contentObject->attribute('class_name'),
                                                          'published' => $contentObject->attribute( 'published' ),
                                                          'is_container' => true,
                                                          'language_js_array' => eZContentLanguage::jsArrayByMask( $contentObject->attribute( 'language_mask' ) ) ) );

                    $nodes = array( 'parent_node' => &$rootNode,
                                    'children' => array() );
                }
            }
        }

        return $nodes;
    }

    /// \privatesection
    public $Operators;
}
?>
