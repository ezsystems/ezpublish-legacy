<?php
//
// Definition of eZContentObjectTreeNode class
//
// Created on: <10-Jul-2002 19:28:22 sp>
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

/*! \file ezcontentobjecttreenode.php
*/

/*!
  \class eZContentObjectTreeNode ezcontentobjecttreenode.php
  \brief The class eZContentObjectTreeNode does

\verbatim

Some algorithms
----------
1. Adding new Node
Enter  1 - parent_node
       2 - contentobject_id,  ( that is like a node value )

(a) - get path_string, depth for parent node to built path_string  and to count depth for new one
(c) - calculating attributes for new node and inserting it
Returns node_id for added node


2. Deleting node ( or subtree )
Enter - node_id

3. Move subtree in tree
Enter node_id,new_parent_id


4. fetching subtree

\endverbatim

*/

include_once( "lib/ezutils/classes/ezini.php" );
include_once( "lib/ezutils/classes/ezhttptool.php" );
include_once( "lib/ezutils/classes/ezdebugsetting.php" );
include_once( "kernel/classes/ezcontentobject.php" );

class eZContentObjectTreeNode extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZContentObjectTreeNode( $row = array() )
    {
        $this->HasContentObject = false;
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( "node_id" => "NodeID",
                                         "parent_node_id" => "ParentNodeID",
                                         "main_node_id" => "MainNodeID",
                                         "contentobject_id" => "ContentObjectID",
                                         'contentobject_version' => 'ContentObjectVersion',
                                         'contentobject_is_published' => 'ContentObjectIsPublished',
                                         "depth" => "Depth",
                                         'sort_field' => 'SortField',
                                         'sort_order' => 'SortOrder',
                                         'priority' => 'Priority',
                                         "path_string" => "PathString",
                                         "crc32_path" => "CRC32Path",
                                         "path_identification_string" => "PathIdentificationString",
                                         "md5_path" => 'MD5Path'
                                         ),
                      "keys" => array( "node_id" ),
                      "function_attributes" => array( "name" => "getName",
                                                      'data_map' => 'dataMap',
                                                      "object" => "object",
                                                      "subtree" => "subTree",
                                                      "children" => "children",
                                                      "children_count" => "childrenCount",
                                                      'contentobject_version_object' => 'contentObjectVersionObject',
                                                      'sort_array' => 'sortArray',
                                                      'creator' => 'creator',
                                                      "path" => "fetchPath",
                                                      "parent" => "fetchParent",
                                                      'url_alias' => 'urlAlias'
                                                      ),
                      "increment_key" => "node_id",
                      "class_name" => "eZContentObjectTreeNode",
                      "name" => "ezcontentobject_tree" );
    }

    function create( $parentNodeID = null, $contentObjectID = null, $contentObjectVersion = 0,
                     $sortField = 0, $sortOrder = true )
    {
        $row = array( 'node_id' => null,
                      'main_node_id' => null,
                      'parent_node_id' => $parentNodeID,
                      'contentobject_id' => $contentObjectID,
                      'contentobject_version' => $contentObjectVersion,
                      'contentobject_is_published' => false,
                      'depth' => 1,
                      'sort_field' => $sortField,
                      'sort_order' => $sortOrder,
                      'priority' => 0 );
        $node =& new eZContentObjectTreeNode( $row );
        return $node;
    }

    function attributes()
    {
        return eZPersistentObject::attributes();
    }

    function &attribute( $attr )
    {
        if ( $attr == 'name')
        {
            return $this->getName();
        }
        else if ( $attr == 'data_map')
        {
            return $this->dataMap();
        }
        elseif ( $attr == 'object' )
        {
            $obj = $this->object();
            return $obj;
        }
        elseif ( $attr == 'subtree' )
        {
            return $this->subTree();
        }
        else if ( $attr == 'contentobject_version_object' )
        {
            return $this->contentObjectVersionObject();
        }
        elseif ( $attr == 'children' )
        {
            return $this->children();
        }
        elseif ( $attr == 'children_count' )
        {
            return $this->childrenCount();
        }
        elseif ( $attr == 'sort_array' )
        {
            return $this->sortArray();
        }
        elseif ( $attr == 'path' )
        {
            return $this->fetchPath();
        }
        elseif ( $attr == 'parent' )
        {
            return $this->fetchParent();
        }
        elseif ( $attr == 'creator' )
        {
            return $this->creator();
        }
        elseif ( $attr == 'url_alias' )
        {
            return $this->urlAlias();
        }else
            return eZPersistentObject::attribute( $attr );
    }

    /*!
	 \return a map with all the content object attributes where the keys are the
             attribute identifiers.
     \sa eZContentObject::fetchDataMap
    */
    function &dataMap()
    {
        $obj =& $this->object();
        return $obj->fetchDataMap( $this->attribute( 'contentobject_version' ) );
    }

    function &subTree( $params = false ,$nodeID = 0 )
    {
        if ( $params === false )
        {
            $params = array( 'Depth' => false,
                             'Offset' => false,
                             'Limit' => false,
                             'SortBy' => false,
                             'ClassFilterType' => false,
                             'ClassFilterArray' => false );
        }
        $depth = false;
        $offset = false;
        $limit = false;
        $limitationList = array();
        if ( isset( $params['Depth'] ) && is_numeric( $params['Depth'] ) )
        {
            $depth = $params['Depth'];

        }
        if ( isset( $params['Offset'] ) && is_numeric( $params['Offset'] ) )
        {
            $offset = $params['Offset'];
        }
        if ( isset( $params['Limit'] ) && is_numeric( $params['Limit'] ) )
        {
            $limit = $params['Limit'];
        }
        if ( isset( $params['Limitation'] ) )
        {
            $limitationList =& $params['Limitation'];
        }
        else if ( isset( $GLOBALS['ezpolicylimitation_list'] ) )
        {

            $policyList =& $GLOBALS['ezpolicylimitation_list'];
            $limitationList = array();
            foreach ( array_keys( $policyList ) as $key )
            {
                $policy =& $policyList[$key];
                $limitationList[] =& $policy->attribute( 'limitations' );

            }
            eZDebugSetting::writeDebug( 'kernel-content-treenode', $limitationList, "limitation list"  );
        }
        $sortCount = 0;
        $sortList = false;
        if ( isset( $params['SortBy'] ) and
             is_array( $params['SortBy'] ) and
             count( $params['SortBy'] ) > 0 )
        {
            $sortList = $params['SortBy'];
            if ( count( $sortList ) > 1 and
                 !is_array( $sortList[0] ) )
            {
                $sortList = array( $sortList );
            }
        }
        if ( $sortList !== false )
        {
            $sortingFields = '';
            foreach ( $sortList as $sortBy )
            {
                if ( is_array( $sortBy ) and count( $sortBy ) > 0 )
                {
                    if ( $sortCount > 0 )
                        $sortingFields .= ', ';
                    $sortField = $sortBy[0];
                    switch ( $sortField )
                    {
                        case 'path':
                        {
                            $sortingFields .= 'path_string';
                        } break;
                        case 'published':
                        {
                            $sortingFields .= 'ezcontentobject.published';
                        } break;
                        case 'modified':
                        {
                            $sortingFields .= 'ezcontentobject.modified';
                        } break;
                        case 'section':
                        {
                            $sortingFields .= 'ezcontentobject.section';
                        } break;
                        case 'depth':
                        {
                            $sortingFields .= 'depth';
                        } break;
                        case 'class_identifier':
                        {
                            $sortingFields .= 'ezcontententclass.identifier';
                        } break;
                        case 'class_name':
                        {
                            $sortingFields .= 'ezcontentclass.name';
                        } break;
                        case 'priority':
                        {
                            $sortingFields .= 'ezcontentobject_tree.priority';
                        } break;
                        case 'name':
                        {
                            $sortingFields .= 'ezcontentobject_name.name';
                        } break;
                        default:
                        {
                            eZDebug::writeWarning( 'Unknown sort field: ' . $sortField, 'eZContentObjectTreeNode::subTree' );
                            continue;
                        };
                    }
                    $sortOrder = true; // true is ascending
                    if ( isset( $sortBy[1] ) )
                        $sortOrder = $sortBy[1];
                    $sortingFields .= $sortOrder ? " ASC" : " DESC";
                    ++$sortCount;
                }
            }
        }
        if ( $sortCount == 0 )
        {
            $sortingFields = " path_string ASC";
        }

        $classCondition = '';
        if ( ( $params['ClassFilterType'] == 'include' or $params['ClassFilterType'] == 'exclude' )
             and count( $params['ClassFilterArray'] ) > 0 )
        {
            $classCondition = ' ( ';
            $i = 0;
            $classCount = count( $params['ClassFilterArray'] );
            foreach ( $params['ClassFilterArray'] as $classID )
            {
                if ( $params['ClassFilterType'] == 'include' )
                    $classCondition .= " ezcontentobject.contentclass_id = '$classID' ";
                else
                    $classCondition .= " ezcontentobject.contentclass_id <> '$classID' ";

                $i++;
                if ( $i < $classCount )
                {
                    if ( $params['ClassFilterType'] == 'include' )
                        $classCondition .= " OR ";
                    else
                        $classCondition .= " AND ";
                }
            }
            $classCondition .= ' ) AND ';
        }


        if ( $nodeID == 0 )
        {
            $nodeID = $this->attribute( 'node_id' );
            $node = $this;
        }
        else
        {
            $node = eZContentObjectTreeNode::fetch( $nodeID );
        }

        $fromNode = $nodeID ;

        if ( count( $node ) != 0 )
        {
            $nodePath =  $node->attribute( 'path_string' );
            $nodeDepth = $node->attribute( 'depth' );
        }

        $childrensPath = $nodePath ;
        $pathLength = strlen( $childrensPath );

        $db =& eZDB::instance();
        $subStringString = $db->subString( 'path_string', 1, $pathLength );
          $pathString = " path_string like '$childrensPath%' and ";
        $depthCond = '';
        if ( $depth )
        {

            $nodeDepth += $params[ 'Depth' ];
            $depthCond = ' depth <= ' . $nodeDepth . ' and ';
        }

        $ini =& eZINI::instance();
        $db =& eZDB::instance();

        $useVersionName = true;
        if ( $useVersionName )
        {
            $versionNameTables = ', ezcontentobject_name ';
            $versionNameTargets = ', ezcontentobject_name.name as name,  ezcontentobject_name.real_translation ';

            $ini =& eZINI::instance();
            $lang = $ini->variable( 'RegionalSettings', 'ContentObjectLocale' );

            $versionNameJoins = " and  ezcontentobject_tree.contentobject_id = ezcontentobject_name.contentobject_id and
                                  ezcontentobject_tree.contentobject_version = ezcontentobject_name.content_version and
                                  ezcontentobject_name.content_translation = '$lang' ";
        }
        if( count( $limitationList ) > 0 )
        {
            $sqlParts = array();
            foreach( $limitationList as $limitationArray )
            {
                $sqlPartPart = array();
                foreach ( $limitationArray as $limitation )
                {
                    if ( $limitation->attribute( 'identifier' ) == 'Class' )
                    {
                        $sqlPartPart[] = 'ezcontentobject.contentclass_id in (' . $limitation->attribute( 'values_as_string' ) . ')';
                    }
                    elseif ( $limitation->attribute( 'identifier' ) == 'Section' )
                    {
                        $sqlPartPart[] = 'ezcontentobject.section_id in (' . $limitation->attribute( 'values_as_string' ) . ')';
                    }
                    elseif( $limitation->attribute( 'name' ) == 'Owner' )
                    {
                        eZDebug::writeWarning( $limitation, 'System is not configured to check Assigned in  objects' );
                    }
                }
                $sqlParts[] = implode( ' AND ', $sqlPartPart );
            }
            $sqlPermissionCheckingString = ' AND ((' . implode( ') or (', $sqlParts ) . ')) ';

            $query = "SELECT ezcontentobject.*,
                           ezcontentobject_tree.*,
                           ezcontentclass.name as class_name
                           $versionNameTargets
                    FROM
                          ezcontentobject_tree,
                          ezcontentobject,ezcontentclass
                           $versionNameTables
                    WHERE $pathString
                          $depthCond
                          ezcontentclass.version=0 AND
                          node_id != $fromNode AND
                          ezcontentobject_tree.contentobject_id = ezcontentobject.id  AND
                          ezcontentclass.id = ezcontentobject.contentclass_id AND
                          $classCondition
                          ezcontentobject_tree.contentobject_is_published = 1
                          $versionNameJoins
                          $sqlPermissionCheckingString
                    ORDER BY $sortingFields";

        }
        else
        {
            $query = "SELECT ezcontentobject.*,
                             ezcontentobject_tree.*,
                             ezcontentclass.name as class_name
                            $versionNameTargets
                      FROM
                            ezcontentobject_tree,
                            ezcontentobject,ezcontentclass
                            $versionNameTables
                      WHERE $pathString
                            $depthCond
                            ezcontentclass.version=0 AND
                            node_id != $fromNode AND
                            ezcontentobject_tree.contentobject_id = ezcontentobject.id  AND
                            ezcontentclass.id = ezcontentobject.contentclass_id AND
                            $classCondition
                            ezcontentobject_tree.contentobject_is_published = 1
                            $versionNameJoins
                      ORDER BY $sortingFields";
        }
        if ( !$offset && !$limit )
        {
            $nodeListArray =& $db->arrayQuery( $query );
        }
        else
        {
            $nodeListArray =& $db->arrayQuery( $query, array( "offset" => $offset,
                                                             "limit" => $limit ) );
        }
        $retNodeList =& eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );

        return $retNodeList;
    }

    function subTreeCount( $params = array() )
    {
        $nodePath = $this->attribute( 'path_string' );
        $fromNode = $this->attribute( 'node_id');
        $childrensPath = $nodePath ;
        $pathLength = strlen( $childrensPath );
        $db =& eZDB::instance();

        $subStringString = $db->subString( 'path_string', 1, $pathLength );
        //       $pathString = " $subStringString = '$childrensPath' AND ";
        $pathString = " path_string like '$childrensPath%' AND ";

        $nodeDepth = $this->attribute( 'depth' );
        $depthCond = '';

        $limitationList = array();
        if ( isset( $params['Limitation'] ) )
        {
            $limitationList =& $params['Limitation'];
        }
        else if ( isset( $GLOBALS['ezpolicylimitation_list'] ) )
        {
            $policyList =& $GLOBALS['ezpolicylimitation_list'];
            $limitationList = array();
            foreach ( array_keys( $policyList ) as $key )
            {
                $policy =& $policyList[$key];
                $limitationList[] =& $policy->attribute( 'limitations' );

            }
            eZDebugSetting::writeDebug( 'kernel-content-treenode', $limitationList, "limitation list"  );

        }


        if ( isset( $params[ 'Depth' ] ) && $params[ 'Depth' ] > 0 )
        {

            $nodeDepth += $params[ 'Depth' ];
            $depthCond = ' depth <= ' . $nodeDepth . ' and ';
        }

        $ini =& eZINI::instance();
        $classCondition = "";
        if ( isset( $params['ClassFilterType'] ) and isset( $params['ClassFilterArray'] ) and
             ( $params['ClassFilterType'] == 'include' or $params['ClassFilterType'] == 'exclude' )
             and count( $params['ClassFilterArray'] ) > 0 )
        {
            $classCondition = ' ( ';
            $i = 0;
            $classCount = count( $params['ClassFilterArray'] );
            foreach ( $params['ClassFilterArray'] as $classID )
            {
                if ( $params['ClassFilterType'] == 'include' )
                    $classCondition .= " ezcontentobject.contentclass_id = '$classID' ";
                else
                    $classCondition .= " ezcontentobject.contentclass_id <> '$classID' ";

                $i++;
                if ( $i < $classCount )
                {
                    if ( $params['ClassFilterType'] == 'include' )
                        $classCondition .= " OR ";
                    else
                        $classCondition .= " AND ";
                }
            }
            $classCondition .= ' ) AND ';
        }

        if ( count( $limitationList ) > 0 )
        {
            $sqlParts = array();
            foreach( $limitationList as $limitationArray )
            {
                $sqlPartPart = array();
                foreach ( $limitationArray as $limitation )
                {
                    if ( $limitation->attribute( 'identifier' ) == 'Class' )
                    {
                        $sqlPartPart[] = 'ezcontentobject.contentclass_id in (' . $limitation->attribute( 'values_as_string' ) . ')';
                    }
                    elseif ( $limitation->attribute( 'identifier' ) == 'Section' )
                    {
                        $sqlPartPart[] = 'ezcontentobject.section_id in (' . $limitation->attribute( 'values_as_string' ) . ')';
                    }
                    elseif( $limitation->attribute( 'name' ) == 'Owner' )
                    {
                        eZDebug::writeWarning( $limitation, 'System is not configured to check Assigned in  objects' );
                    }
                }
                $sqlParts[] = implode( ' AND ', $sqlPartPart );
            }
            $sqlPermissionCheckingString = ' AND ((' . implode( ') or (', $sqlParts ) . ')) ';

            $query = "SELECT count(*) as count
                      FROM
                           ezcontentobject_tree,
                           ezcontentobject,ezcontentclass
                      WHERE $pathString
                            $depthCond
                            $classCondition
                            ezcontentclass.version=0 AND
                            node_id != $fromNode AND
                            ezcontentobject_tree.contentobject_id = ezcontentobject.id  AND
                            ezcontentclass.id = ezcontentobject.contentclass_id
                            $sqlPermissionCheckingString ";

        }
        else
        {
            $query="SELECT
                           count(*) AS count
                    FROM
                          ezcontentobject_tree,
                          ezcontentobject,
                          ezcontentclass
                    WHERE
                           $pathString
                           $depthCond
                           $classCondition
                           ezcontentclass.version=0 AND
                           node_id != '$fromNode' AND
                           ezcontentobject_tree.contentobject_id = ezcontentobject.id AND
                           ezcontentclass.id = ezcontentobject.contentclass_id ";
        }

        $nodeListArray = $db->arrayQuery( $query );
        return $nodeListArray[0]['count'];
    }

    /*!
     \return the children(s) of the current node as an array of eZContentObjectTreeNode objects
    */
    function &childrenByName( $name )
    {
        $nodeID = $this->attribute( 'node_id' );

        $fromNode = $nodeID ;

        $nodePath = $this->attribute( 'path_string' );
        $nodeDepth = $this->attribute( 'depth' );

        $childrensPath = $nodePath ;
        $pathLength = strlen( $childrensPath );

        $db =& eZDB::instance();
        $subStringString = $db->subString( 'path_string', 1, $pathLength );
        $pathString = " path_string like '$childrensPath%' and ";
        $depthCond = '';
        $nodeDepth = $this->Depth + 1;
        $depthCond = ' depth <= ' . $nodeDepth . ' and ';

        $ini =& eZINI::instance();
        $db =& eZDB::instance();

        $query = "SELECT ezcontentobject.*,
                             ezcontentobject_tree.*,
                             ezcontentclass.name as class_name
                      FROM
                            ezcontentobject_tree,
                            ezcontentobject,ezcontentclass
                      WHERE $pathString
                            $depthCond
                            ezcontentobject.name = '$name' AND
                            ezcontentclass.version=0 AND
                            node_id != $fromNode AND
                            ezcontentobject_tree.contentobject_id = ezcontentobject.id  AND
                            ezcontentclass.id = ezcontentobject.contentclass_id AND
                            $classCondition
                            ezcontentobject_tree.contentobject_is_published = 1";

        $nodeListArray =& $db->arrayQuery( $query );

        $retNodeList =& eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );

        return $retNodeList;
    }

    /*!
     Returns the first level children in sorted order.
    */
    function &children( )
    {
        return $this->subTree( array( 'Depth' => 1,
                                      'Limitation' => $limitationList
                                      ) );
    }

    /*!
     Returns the number of children for the current node.
    */
    function &childrenCount( )
    {
        return $this->subTreeCount( array( 'Depth' => 1,
                                           'Limitation' => $limitationList
                                           ) );
    }

    /*!
     \return the field name for the sort order number \a $sortOrder.
             Gives a warning if the number is unknown and return \c 'path'.
    */
    function sortFieldName( $sortOrder )
    {
        switch ( $sortOrder )
        {
            default:
                eZDebug::writeWarning( 'Unknown sort order: ' . $sortOrder, 'eZContentObjectTreeNode::sortFieldName' );
            case 1:
                return 'path';
            case 2:
                return 'published';
            case 3:
                return 'modified';
            case 4:
                return 'section';
            case 5:
                return 'depth';
            case 6:
                return 'class_identifier';
            case 7:
                return 'class_name';
            case 8:
                return 'priority';
            case 8:
                return 'name';
        }
    }

    /*!
     \return an array which defines the sorting method for this node.
     The array will contain one element which is an array with sort field
     and sort order.
    */
    function sortArray()
    {
        $sort = array( eZContentObjectTreeNode::sortFieldName( $this->attribute( 'sort_field' ) ),
                       $this->attribute( 'sort_order' ) );
        return array( $sort );
    }

    /*!
     Will assign a section to the current node and all child objects.
     Only main node assignments will be updated.
    */
    function assignSectionToSubTree( $nodeID, $sectionID )
    {
        $db =& eZDB::instance();

        $node = eZContentObjectTreeNode::fetch( $nodeID );
        $nodePath =  $node->attribute( 'path_string' );

//        $subStringString = $db->subString( 'path_string', 1, strlen( $nodePath ) );

        $pathString = " path_string like '$nodePath%' AND ";

        // fetch the object id's which needs to be updated
        $objectIDArray =& $db->arrayQuery( "SELECT
                                                   ezcontentobject.id
                                            FROM
                                                   ezcontentobject_tree, ezcontentobject
                                            WHERE
                                                  $pathString
                                                  ezcontentobject_tree.contentobject_id=ezcontentobject.id AND
                                                  ezcontentobject_tree.main_node_id=ezcontentobject_tree.node_id" );
        $inSQL = "";
        $i = 0;
        foreach ( $objectIDArray as $objectID )
        {
            if ( $i > 0 )
                $inSQL .= ",";
            $inSQL .= " " . $objectID['id'];
            $i++;
        }
        $db->query( "UPDATE ezcontentobject SET section_id='$sectionID' WHERE id IN ( $inSQL )" );
        $db->query( "UPDATE ezsearch_object_word_link SET section_id='$sectionID' WHERE contentobject_id IN ( $inSQL )" );
    }
/*
    function &fetchByCRC( $pathStr )
    {
        $crcSum = crc32( $pathStr );
        $db =& eZDB::instance();

        $useVersionName = true;
        if ( $useVersionName )
        {
            $versionNameTables = ', ezcontentobject_name ';
            $versionNameTargets = ', ezcontentobject_name.name as name,  ezcontentobject_name.real_translation ';

            $ini =& eZINI::instance();
            $lang = $ini->variable( 'RegionalSettings', 'ContentObjectLocale' );

            $versionNameJoins = " and  ezcontentobject_tree.contentobject_id = ezcontentobject_name.contentobject_id and
                                  ezcontentobject_tree.contentobject_version = ezcontentobject_name.content_version and
                                  ezcontentobject_name.content_translation = '$lang' ";
        }

        $query="SELECT ezcontentobject.*,
                           ezcontentobject_tree.*,
                           ezcontentclass.name as class_name
                           $versionNameTargets
                    FROM ezcontentobject_tree,
                         ezcontentobject,
                         ezcontentclass
                         $versionNameTables
                    WHERE crc32_path = $crcSum AND
                          ezcontentobject_tree.contentobject_id=ezcontentobject.id AND
                          ezcontentclass.version=0  AND
                          ezcontentclass.id = ezcontentobject.contentclass_id
                         $versionNameJoins";

        $nodeListArray = $db->arrayQuery( $query );
        $retNodeArray =& eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );
        if ( count( $retNodeArray ) > 1 )
        {
            reset( $retNodeArray );
            while ( ( $key = key( $retNodeArray )) !== null )
            {
                $node =& $retNodeArray[ $key ];
                if ( $node->attribute( 'path_identification_string' ) == $pathStr )
                {
                    return $node;
                }
                next( $retNodeArray );
            }
        }
        return $retNodeArray[0];
    }
*/
    function &fetchByCRC( $pathStr )
    {
        $md5hash = md5( $pathStr );
        $db =& eZDB::instance();

        $useVersionName = true;
        if ( $useVersionName )
        {
            $versionNameTables = ', ezcontentobject_name ';
            $versionNameTargets = ', ezcontentobject_name.name as name,  ezcontentobject_name.real_translation ';

            $ini =& eZINI::instance();
            $lang = $ini->variable( 'RegionalSettings', 'ContentObjectLocale' );

            $versionNameJoins = " and  ezcontentobject_tree.contentobject_id = ezcontentobject_name.contentobject_id and
                                  ezcontentobject_tree.contentobject_version = ezcontentobject_name.content_version and
                                  ezcontentobject_name.content_translation = '$lang' ";
        }

        $query="SELECT ezcontentobject.*,
                           ezcontentobject_tree.*,
                           ezcontentclass.name as class_name
                           $versionNameTargets
                    FROM ezcontentobject_tree,
                         ezcontentobject,
                         ezcontentclass
                         $versionNameTables
                    WHERE md5_path = '$md5hash' AND
                          ezcontentobject_tree.contentobject_id=ezcontentobject.id AND
                          ezcontentclass.version=0  AND
                          ezcontentclass.id = ezcontentobject.contentclass_id
                         $versionNameJoins";

        $nodeListArray = $db->arrayQuery( $query );
        $retNodeArray =& eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );
        if ( count( $retNodeArray ) > 1 )
        {
            reset( $retNodeArray );
            while ( ( $key = key( $retNodeArray )) !== null )
            {
                $node =& $retNodeArray[ $key ];
                if ( $node->attribute( 'path_identification_string' ) == $pathStr )
                {
                    return $node;
                }
                next( $retNodeArray );
            }
        }
        return $retNodeArray[0];
    }

    function &fetchByContentObjectID( $contentObjectID, $asObject = true )
    {
         return eZPersistentObject::fetchObjectList( eZContentObjectTreeNode::definition(),
                                                     null,
                                                     array( "contentobject_id" => $contentObjectID ),
                                                     null,
                                                     null,
                                                     $asObject );
    }

    function &findMainNode( $objectID, $asObject = false )
    {
        $query="SELECT ezcontentobject.*,
                           ezcontentobject_tree.*,
                           ezcontentclass.name as class_name
                    FROM ezcontentobject_tree,
                         ezcontentobject,
                         ezcontentclass
                    WHERE ezcontentobject_tree.contentobject_id=$objectID AND
                          ezcontentobject_tree.main_node_id = ezcontentobject_tree.node_id AND
                          ezcontentobject_tree.contentobject_id=ezcontentobject.id AND
                          ezcontentclass.version=0  AND
                          ezcontentclass.id = ezcontentobject.contentclass_id  ";

        $db =& eZDB::instance();
        $nodeListArray =& $db->arrayQuery( $query );
        if ( count( $nodeListArray ) > 1 )
        {
            eZDebug::writeError( $nodeListArray , "There are more then one main_node for objectID: $objectID" );
        }
        else
        {
            if ( $asObject )
            {
                $retNodeArray =& eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );
                $returnValue =& $retNodeArray[0];
                return $returnValue;
            }else
            {
                $retNodeArray =& $nodeListArray;
                return $retNodeArray[0]['node_id'];
            }

        }
        return null;
    }

    function &fetch( $nodeID )
    {
        $returnValue = null;
        $ini =& eZINI::instance();
        $db =& eZDB::instance();

        $useVersionName = true;
        if ( $useVersionName )
        {
            $versionNameTables = ', ezcontentobject_name ';
            $versionNameTargets = ', ezcontentobject_name.name as name,  ezcontentobject_name.real_translation ';

            $ini =& eZINI::instance();
            $lang = $ini->variable( 'RegionalSettings', 'ContentObjectLocale' );

            $versionNameJoins = " and  ezcontentobject_tree.contentobject_id = ezcontentobject_name.contentobject_id and
                                  ezcontentobject_tree.contentobject_version = ezcontentobject_name.content_version and
                                  ezcontentobject_name.content_translation = '$lang' ";
        }

        if ( $nodeID != '' )
        {
            if ( $nodeID != 1 )
            {
                $query="SELECT ezcontentobject.*,
                           ezcontentobject_tree.*,
                           ezcontentclass.name as class_name
                           $versionNameTargets
                    FROM ezcontentobject_tree,
                         ezcontentobject,
                         ezcontentclass
                         $versionNameTables
                    WHERE node_id = $nodeID AND
                          ezcontentobject_tree.contentobject_id=ezcontentobject.id AND
                          ezcontentclass.version=0  AND
                          ezcontentclass.id = ezcontentobject.contentclass_id
                          $versionNameJoins";
            }
            else
            {
                $query="SELECT *
                    FROM ezcontentobject_tree
                    WHERE node_id = $nodeID ";
            }

            $nodeListArray =& $db->arrayQuery( $query );
            if ( count( $nodeListArray ) == 1 )
            {
                $retNodeArray =& eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );
                $returnValue =& $retNodeArray[0];
            }
        }
        else
            eZDebug::writeWarning( 'Cannot fetch node from empty node ID', 'eZContentObjectTreeNode::fetch' );
        return $returnValue;
    }

    function &fetchNode( $contentObjectID, $partentNodeID )
    {
        $returnValue = null;
        $ini =& eZINI::instance();
        $db =& eZDB::instance();
        $query="SELECT *
                FROM ezcontentobject_tree
                WHERE contentobject_id = $contentObjectID AND
                      parent_node_id = $partentNodeID";
        $nodeListArray =& $db->arrayQuery( $query );
        if ( count( $nodeListArray ) == 1 )
        {
            $retNodeArray =& eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );
            $returnValue = $retNodeArray[0];
        }
        return $returnValue;
    }

    function fetchParent()
    {
        return $this->fetch( $this->attribute( 'parent_node_id' ) );
    }

    function fetchPath()
    {
        $nodeID = $this->attribute( 'node_id' );
        $nodePath = $this->attribute( 'path_string' );

        $pathArray = explode( '/', trim($nodePath,'/') );
        $pathArray = array_slice( $pathArray, 0, count($pathArray)-1 );

        $pathString = '';
        foreach ( $pathArray as $node )
        {
            $pathString .= 'or node_id = ' . $node . ' ';

        }
        if ( strlen( $pathString) > 0 )
        {
            $pathString = '('. substr( $pathString, 2 ) . ') and ';
        }
        $useVersionName = true;
        if ( $useVersionName )
        {
            $versionNameTables = ', ezcontentobject_name ';
            $versionNameTargets = ', ezcontentobject_name.name as name,  ezcontentobject_name.real_translation ';

            $ini =& eZINI::instance();
            $lang = $ini->variable( 'RegionalSettings', 'ContentObjectLocale' );

            $versionNameJoins = " and  ezcontentobject_tree.contentobject_id = ezcontentobject_name.contentobject_id and
                                  ezcontentobject_tree.contentobject_version = ezcontentobject_name.content_version and
                                  ezcontentobject_name.content_translation = '$lang' ";
        }

        $query="SELECT ezcontentobject.*,
                       ezcontentobject_tree.*,
                       ezcontentclass.name as class_name
                       $versionNameTargets
                FROM ezcontentobject_tree,
                     ezcontentobject,
                     ezcontentclass
                     $versionNameTables
                WHERE $pathString
                      ezcontentobject_tree.contentobject_id=ezcontentobject.id  AND
                      ezcontentclass.version=0 AND
                      ezcontentclass.id = ezcontentobject.contentclass_id
                      $versionNameJoins
                ORDER BY path_string";

        $db =& eZDB::instance();
        $nodesListArray = $db->arrayQuery( $query );
        $retNodes = array();
        $retNodes =& eZContentObjectTreeNode::makeObjectsArray( $nodesListArray );
        return $retNodes;


    }

    function createObject( $contentClassID, $parentNodeID = 2 )
    {
        $user =& eZUser::currentUser();
        $userID =& $user->attribute( 'contentobject_id' );

        $class =& eZContentClass::fetch( $contentClassID );
        $parentNode =& eZContentObjectTreeNode::fetch( $parentNodeID );
        $parentContentObject =& $parentNode->attribute( 'contentobject' );
        $sectionID = $parentContentObject->attribute( 'section_is' );
        $object =& $class->instantiate( $userID, $sectionID );

//        $parentContentObject = $parentNode->attribute( 'contentobject' );

        $node =& eZContentObjectTreeNode::addChild( $object->attribute( "id" ), $parentNodeID, true );
//        $object->setAttribute( "main_node_id", $node->attribute( 'node_id' ) );
        $node->setAttribute( 'main_node_id', $node->attribute( 'node_id' ) );
        $object->store();
        $node->store();

        return $object;
    }

    function &addChild( $contentobjectID, $nodeID = 0, $asObject = false )
    {

        if ( $nodeID == 0 )
        {
            $node = $this;
        }else
        {
            $node =& eZContentObjectTreeNode::fetch( $nodeID );
        }

        $db =& eZDB::instance();
        $parentMainNodeID = $node->attribute( 'node_id' ); //$parent->attribute( 'main_node_id' );
        $parentPath = $node->attribute( 'path_string' );
        $parentDepth = $node->attribute( 'depth' );

        $nodeDepth = $parentDepth + 1 ;


        $insertedNode =& new eZContentObjectTreeNode();
        $insertedNode->setAttribute( 'parent_node_id', $parentMainNodeID );
        $insertedNode->setAttribute( 'contentobject_id', $contentobjectID );
        $insertedNode->setAttribute( 'depth', $nodeDepth );
        $insertedNode->setAttribute( 'path_string', '/TEMPPATH' );
        $insertedNode->store();
        $insertedID = $insertedNode->attribute( 'node_id' );
        $newNodePath = $parentPath . $insertedID . '/';
        $insertedNode->setAttribute( 'path_string', $newNodePath );
//        $insertedNode = eZContentObjectTreeNode::fetch( $insertedID );

        $insertedNode->store();
        if ( $asObject )
        {
            return $insertedNode;
        }else
        {
            return $insertedID;
        }
    }

    function pathWithNames( $nodeID = 0 )
    {
        if ( $nodeID == 0 )
        {
            $node =& $this;
        }else
        {
            $node =& eZContentObjectTreeNode::fetch( $nodeID );
        }
//        eZDebugSetting::writeDebug( 'kernel-content-treenode', $this, 'node3' );

//        $nodeList = $node->attribute( 'path' );
//        array_shift( $nodeList );
        $nodeList =& $node->attribute( 'path' );
        if ( $node->attribute( 'depth' ) > 1 )
        {
            $parentNodeID = $node->attribute( 'parent_node_id' );
            $parentNode = eZContentObjectTreeNode::fetch( $parentNodeID );
            if( ! is_null( $parentNode ) )
            {
            $parentNodePathString = $parentNode->attribute( 'path_identification_string' );
            }
            else
            {
                eZDebug::printReport();
                die();
            }
        }
        else
        {
            $parentNodePathString = '';
        }



        if ( count( $nodeList ) > 0 )
        {
            $topLevelNode = $nodeList[0];
            $topLevelName = $topLevelNode->getName();
            $topLevelName = strtolower( $topLevelName );
            $topLevelName = preg_replace( array( "/[^a-z0-9_ ]/" ,
                                                 "/ /",
                                                 "/__+/" ),
                                          array( "",
                                                 "_",
                                                 "_" ),
                                          $topLevelName );
            $pathElementArray = explode( '/', $parentNodePathString );
            if( count( $pathElementArray ) > 0 )
            {
                $parentNodePathString = implode( '/', $pathElementArray );
            }else
            {
                $parentNodePathString = '';
            }
        }
        else
        {
            $parentNodePathString = '';
        }
//            eZDebugSetting::writeDebug( 'kernel-content-treenode', $pathElementArray, "pathElementArray" );
//            eZDebugSetting::writeDebug( 'kernel-content-treenode', $nodeList, "nodeList" );



        if ( count( $nodeList ) > 0 )
        {
            $nodeName = $node->attribute( 'name' );
            $nodeName = strtolower( $nodeName );
            $nodeName = preg_replace( array( "/[^a-z0-9_ ]/" ,
                                             "/ /",
                                             "/__+/" ),
                                      array( "",
                                             "_",
                                             "_" ),
                                      $nodeName );
            if ( $parentNodePathString != '' )
            {
                $nodePath = $parentNodePathString . '/' . $nodeName ;
            }
            else
            {
                $nodePath = $nodeName ;
            }
        }
        else
        {
            $nodePath = '';
        }
        eZDebugSetting::writeDebug( 'nice-urls', $nodePath, "path for node before checking");
        $nodePath = $node->checkPath( $nodePath );
        eZDebugSetting::writeDebug( 'nice-urls', $nodePath, "path for node after checking");
        return $nodePath;
/*
        $nodeList = array_merge( $nodeList, array( &$node ) );
        $nodePathElementList = array();
        foreach ( $nodeList as $nodeInPath )
        {
            $nodeName = $nodeInPath->attribute( 'name' );
//            $identifier = $attribute->attribute( "name" );
            $nodeName = strtolower( $nodeName );
            $nodeName = preg_replace( array( "/[^a-z0-9_ ]/" ,
                                             "/ /",
                                             "/__+/" ),
                                      array( "",
                                             "_",
                                             "_" ),
                                      $nodeName );
            $nodePathElementList[]=$nodeName;
        }
        $nodePath = implode( '/', $nodePathElementList );
//        eZDebugSetting::writeDebug( 'kernel-content-treenode', $nodePath, 'path1' );
        $nodePath = $node->checkPath( $nodePath );
        return $nodePath;
*/
    }

    function checkPath( $path )
    {
//        eZDebugSetting::writeDebug( 'kernel-content-treenode', $path, 'path2' );
        $depth = $this->attribute( 'depth' );
        $parentNodeID = $this->attribute( 'parent_node_id' );
        $nodeID = $this->attribute( 'node_id' );

        $sqlToCheckCurrentName = 'select path_identification_string
                                  from ezcontentobject_tree
                                  where ( path_identification_string = \'' . $path . '\' or
                                          path_identification_string like \'' . $path . '\\\_\\\_%\' )
                                          and node_id = ' . $nodeID ;
        $db =& eZDb::instance();
        $retNode = $db->arrayQuery( $sqlToCheckCurrentName );
        if ( count( $retNode ) > 0 )
        {
            return $retNode[0]['path_identification_string'];
        }
        $sql = 'select path_identification_string
                from ezcontentobject_tree
                where parent_node_id = ' . $parentNodeID . ' and
                      depth = ' . $depth . ' and
                      ( path_identification_string = \'' . $path . '\' or path_identification_string like \'' . $path . '\\\_\\\_%\' ) and
                      node_id != ' . $nodeID ;

        $retNodes = $db->arrayQuery( $sql );
        if( count( $retNodes ) > 0 )
        {
            $nodeNum = 0;
            $matchedArray = array();
            foreach ( $retNodes as $node )
            {
                if ( preg_match( '/__(\d+)$/', $node['path_identification_string'], $matchedArray ) )
                {
                    $nodeNumTemp = $matchedArray[1];
                    if ( $nodeNumTemp > $nodeNum )
                    {
                        $nodeNum = $nodeNumTemp;
                    }
                }
            }
            $path = $path . '__' . ++$nodeNum;
        }
        return $path;
    }

/*    function updatePathWithNames()
    {
        $this->setAttribute( 'path_identification_string', $this->pathWithNames() );
        $this->setAttribute( 'crc32_path', crc32 ( $this->attribute( 'path_identification_string' ) ) );
        $this->store();
    }
*/
    function updateSubTreePath()
    {
        $oldPathString = $this->attribute( 'path_identification_string' );
        $oldPathStringLength = strlen( $oldPathString );

//        eZDebugSetting::writeDebug( 'kernel-content-treenode', $this, 'node2' );
        $newPathString = $this->pathWithNames();
        eZDebugSetting::writeDebug( 'kernel-content-treenode', $oldPathString .'  ' . $oldPathStringLength . '  ' . $newPathString );
        $this->setAttribute( 'path_identification_string', $newPathString );

        $this->setAttribute( 'crc32_path', crc32 ( $newPathString ) );
        $this->setAttribute( 'md5_path', md5 ( $newPathString ) );
        $this->store();
        $childrensPath = $this->attribute( 'path_string' );
        $db =& eZDb::instance();
        $subStringQueryPart = $db->subString( 'path_identification_string', $oldPathStringLength + 1 );
        $newPathStringQueryPart = $db->concatString( array( "'$newPathString'", $subStringQueryPart ) );
        $md5QueryPart = $db->md5( $newPathStringQueryPart );
        $query = "UPDATE
                         ezcontentobject_tree
                  SET
                         path_identification_string = $newPathStringQueryPart,
                         md5_path = $md5QueryPart
                  WHERE
                         path_string like '$childrensPath%'";
//        eZDebugSetting::writeDebug( 'kernel-content-treenode', $query, "nice_urls" );
        $db->query( $query );
/*
             $subTree = & $this->subTree();
         reset( $subTree );
         while( ( $key = key( $subTree ) ) !== null )
         {
               $node =& $subTree[$key];
               $nodeOldPathString = $node->attribute( 'path_identification_string' );
               eZDebugSetting::writeDebug( 'kernel-content-treenode', $nodeOldPathString , 'nodeOldPathString' );

               eZDebug::accumulatorStart( 'new_path_string', 'nice_urls_total', 'new_path_string' );
               $node->setAttribute( 'path_identification_string', $newPathString . substr( $nodeOldPathString, $oldPathStringLength ) );
               eZDebug::accumulatorStop( 'new_path_string' );
               eZDebug::accumulatorStart( 'crc32', 'nice_urls_total', 'crc32' );
               $node->setAttribute( 'crc32_path', crc32 ( $node->attribute( 'path_identification_string' ) ) );
               eZDebug::accumulatorStop( 'crc32' );
               eZDebug::accumulatorStart( 'node_store', 'nice_urls_total', 'node_store' );
               $node->store();
               eZDebug::accumulatorStop( 'node_store' );
               next( $subTree );
         }
*/
    }
    function remove( $nodeID = 0 )
    {
        if ( $nodeID == 0 )
        {
            $node =& $this;
            $nodeID = $node->attribute( 'node_id' );
        }
        else
        {
            $node =& eZContentObjectTreeNode::fetch( $nodeID );
        }

        $nodePath = $node->attribute( 'path_string' );
        $childrensPath = $nodePath ; //. $nodeID . '/';
        $pathLength = strlen( $childrensPath ); //+ 1;

/*        $query = "delete from ezcontentobject_tree
                  where substring( path_string from 1 for $pathLength ) = '$childrensPath' or
                        path_string = '$nodePath' ";
*/
        $db =& eZDB::instance();

        $subStringString = $db->subString( 'path_string', 1, $pathLength );

        $query = "delete from ezcontentobject_tree
                  where $subStringString = '$childrensPath' or
                        path_string = '$nodePath' ";
        $db->query( $query );

    }

    function move( $newParentNodeID, $nodeID = 0 )
    {
        if ( $nodeID == 0 )
        {
            $node = $this;
            $nodeID = $node->attribute( 'node_id' );
        }
        else
        {
            $node =& eZContentObjectTreeNode::fetch( $nodeID );
        }

        $oldPath = $node->attribute( 'path_string' ); //$marginsArray[0][2];
        $oldParentNodeID = $node->attribute( 'parent_node_id' ); //$marginsArray[0][3];

        if ( $oldParentNodeID != $newParentNodeID )
        {
            $newParentNode =& eZContentObjectTreeNode::fetch( $newParentNodeID );
            $newParentPath = $newParentNode->attribute( 'path_string' );
            $newParentDepth = $newParentNode->attribute( 'depth' );
            $newPath =  $newParentPath;// . $newParentNodeID . '/' ;
            $oldDepth = $node->attribute( 'depth' );
            $childrensPath = $oldPath;// . $nodeID . '/';

            $oldPathLength = strlen( $oldPath );// + 1;
            $moveQuery = "UPDATE
                                 ezcontentobject_tree
                          SET
                                 parent_node_id = $newParentNodeID
                          WHERE
                                 node_id = $nodeID";
            $db =& eZDB::instance();
            $subStringString = $db->subString( 'path_string', 1, $oldPathLength );
            $subStringString2 =  $db->subString( 'path_string', $oldPathLength );
            $moveQuery1 = "UPDATE
                                 ezcontentobject_tree
                           SET
                                 path_string = " . $db->concatString( array( "'$newPath'" , "'$nodeID'",$subStringString2 ) ) . " ,
                                 depth = depth + $newParentDepth - $oldDepth + 1
                           WHERE
                                 $subStringString = '$childrensPath' OR
                                 path_string = '$oldPath' ";
            $db->query( $moveQuery );
            $db->query( $moveQuery1 );
        }
    }

    function &makeObjectsArray( $array , $with_contentobject = true )
    {
        $retNodes = array();
        if ( !is_array( $array ) )
            return $retNodes;
        $ini =& eZINI::instance();

        foreach ( $array as $node )
        {
            unset( $object );

            $object =& new eZContentObjectTreeNode( $node );
            $object->setName($node['name']);
            if ( $with_contentobject )
            {
                if ( array_key_exists( 'class_name', $node ) )
                {
                    $contentObject =& new eZContentObject( $node );

                    $permissions = array();
                    $contentObject->setPermissions( $permissions );
                    $contentObject->setClassName( $node['class_name'] );
                }
                else
                {
                    $contentObject =& new eZContentObject( array());
                }

                $object->setContentObject( $contentObject );
            }
            $retNodes[] =& $object;
        }
        return $retNodes;
    }

    function getParentNodeId( $nodeID )
    {
        $db =& eZDB::instance();
        $parentArr = $db->arrayQuery( "SELECT
                                              parent_node_id
                                       FROM
                                              ezcontentobject_tree
                                       WHERE
                                              node_id = $nodeID");
        return $parentArr[0]['parent_node_id'];
    }


    function deleteNodeWhereParent( $node, $id )
    {
        eZContentObjectTreeNode::remove( eZContentObjectTreeNode::findNode( $node, $id ) );

    }

    function findNode( $parentNode, $id, $asObject = false )
    {
        if ( !isset( $parentNode) || $parentNode == NULL  )
        {
            $parentNode = 2;
        }
//        var_dump( $parentNode );

        $db =& eZDB::instance();
        if( $asObject )
        {
            $query="SELECT ezcontentobject.*,
                           ezcontentobject_tree.*,
                           ezcontentclass.name as class_name
                    FROM ezcontentobject_tree,
                         ezcontentobject,
                         ezcontentclass
                    WHERE parent_node_id = $parentNode AND
                          contentobject_id = $id AND
                          ezcontentobject_tree.contentobject_id=ezcontentobject.id AND
                          ezcontentclass.version=0  AND
                          ezcontentclass.id = ezcontentobject.contentclass_id ";


            $nodeListArray = $db->arrayQuery( $query );
            $retNodeArray =& eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );

            if ( count( $retNodeArray ) > 0 )
            {
                return $retNodeArray[0];
            }
            else
            {
                return null;
            }
        }else{
            $getNodeQuery = "SELECT node_id
                           FROM ezcontentobject_tree
                           WHERE
                                parent_node_id=$parentNode AND
                                contentobject_id = $id ";
            $nodeArr = $db->arrayQuery( $getNodeQuery );
            return $nodeArr[0]['node_id'];
        }
    }

    function &getName()
    {
        return  $this->Name;
    }

    function setName( $name )
    {
        $this->Name = $name;
    }

    function store()
    {
        /*      $newPathString = $this->pathWithNames();

        if ( $newPathString != $this->attribute ( 'path_identification_string' ) )
        {

            $this->setAttribute( 'path_identification_string', $newPathString );
            $this->setAttribute( 'crc32_path', crc32 ( $this->attribute( 'path_identification_string' ) ) );
            $this->updateSubTreePath();
        }
        */
        eZPersistentObject::storeObject( $this );
    }
    function &object()
    {
        if ( $this->hasContentObject() )
        {
            return $this->ContentObject;
        }
        $contentobject_id = $this->attribute( 'contentobject_id' );
        $obj =& eZContentObject::fetch( $contentobject_id );
        $this->ContentObject =& $obj;
        $this->HasContentObject = true;
        return $obj;
    }

    function hasContentObject()
    {
        return $this->HasContentObject;

    }

    function setContentObject( $obj )
    {
        $this->ContentObject =& $obj;
        $this->HasContentObject = true;
    }

    /*!
    \todo optimize
    \return the creator of the version published in the node.
    */
    function creator()
    {
        $db =& eZDB::instance();
         $query = "SELECT creator_id
                           FROM ezcontentobject_version
                           WHERE
                                contentobject_id = '$this->ContentObjectID' AND
                                version = '$this->ContentObjectVersion' ";

        $creatorArray = $db->arrayQuery( $query );
        return eZContentObject::fetch( $creatorArray[0]['creator_id'] );
    }

    function &contentObjectVersionObject( $asObject = true )
    {
        return eZContentObjectVersion::fetchVersion( $this->ContentObjectVersion, $this->ContentObjectID, $asObject );
    }

    function &urlAlias()
    {
        return $this->PathIdentificationString;
    }
}

?>
