<?php
//
// Definition of eZContentObjectTreeNode class
//
// Created on: <10-Jul-2002 19:28:22 sp>
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

/*! \file ezcontentobjecttreenode.php
*/

/*!
  \class eZContentObjectTreeNode ezcontentobjecttreenode.php
  \brief The class eZContentObjectTreeNode does

\verbatim
  ___________0__________
 |  _____1______        |
 | |  _2_  _3_  | _4_   |
 | | |   ||   | ||   |  |
 |1|2|3 4||5 6|7||8 9|10|

--------------------------------------------------------------------------------------------
| node_id | parent_node_id | content_object_id | depth | path_string | left_margin | right_margin |
--------------------------------------------------------------------------------------------
|0        |0               |0                  | 0     |'/'   | 1           | 10           |
|1        |0               |1                  | 1     |'/0'  | 2           | 7            |
|2        |1               |2                  | 0     |'/0/1'| 3           | 4            |
|3        |1               |1                  | 0     |'/0/1'| 5           | 6            |
|4        |0               |4                  | 0     |'/0'  | 8           | 9            |
--------------------------------------------------------------------------------------------

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
                                         "md5_path" => "Md5Path",
                                         "left_margin" => "LeftMargin",
                                         "right_margin" => "RightMargin"
                                         ),
                      "keys" => array( "node_id" ),
                      "function_attributes" => array( "name" => "getName",
                                                      "object" => "object",
                                                      "subtree" => "subTree",
                                                      "children" => "children",
                                                      "children_count" => "childrenCount",
                                                      'contentobject_version_object' => 'contentObjectVersionObject',
                                                      'sort_array' => 'sortArray',
                                                      "path" => "fetchPath",
                                                      "parent" => "fetchParent"
                                                      ),
                      "increment_key" => "node_id",

                      "sort" => array( "left_margin" => "asc" ),
                      "class_name" => "eZContentObjectTreeNode",
                      "name" => "ezcontentobject_tree" );
    }

    function create( $parentNodeID = null, $contentObjectID = null, $contentObjectVersion = 0,
                     $sortField = 0, $sortOrder = true )
    {
        $row = array( 'node_id' => null,
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
        }else
            return eZPersistentObject::attribute( $attr );
    }

    function makePermissionTable( $db )
    {
        $user =& eZUser::currentUser();
        $groups =& $user->groups( false );
        $groupString = "";
        $groupString = implode( ',', $groups );
        if ( $db->databaseName() == 'oracle' )
        {
            $db->query( 'delete from permission' );
        }
        else
        {
            $db->query( 'drop table permission' );

            $createTempTableQuery="CREATE TEMPORARY TABLE permission(
                                       permission_id int primary key,
                                       can_read int,
                                       can_create int,
                                       can_edit int,
                                       can_remove int )";
            $db->query( $createTempTableQuery );
        }

        $fillPermissionsQuery = "INSERT INTO permission
                                 SELECT permission_id,
                                        max( ezcontentobject_permission.read_permission ) as can_read,
                                        max( ezcontentobject_permission.create_permission ) as can_create,
                                        max( ezcontentobject_permission.edit_permission ) as can_edit,
                                        max( ezcontentobject_permission.remove_permission ) as can_remove
                                 FROM ezcontentobject_permission
                                 WHERE user_group_id in ( $groupString )
                                 GROUP BY permission_id";
        $db->query( $fillPermissionsQuery );
    }

    function &subTree( $params = array( 'Depth' => false,
                                        'Offset' => false,
                                        'Limit' => false,
                                        'SortBy' => false,
                                        'ClassFilterType' => false,
                                        'ClassFilterArray' => false ) ,$nodeID = 0 )
    {
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

//        $childrensPath = $nodePath . $fromNode . '/';
        $childrensPath = $nodePath ;
        $pathLength = strlen( $childrensPath );
//        $pathString = " substring( path_string from 1 for $pathLength ) = '$childrensPath' and ";

        $db =& eZDB::instance();
        $subStringString = $db->subString( 'path_string', 1, $pathLength );
//        $pathString = " $subStringString = '$childrensPath' and ";
          $pathString = " path_string like '$childrensPath%' and ";
        $depthCond = '';
        if ( $depth )
        {

            $nodeDepth += $params[ 'Depth' ];
            $depthCond = ' depth <= ' . $nodeDepth . ' and ';
        }

        $ini =& eZINI::instance();
        $db =& eZDB::instance();

//        eZDebug::writeWarning( $limitationList, 'limitationList' );
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
                        eZDebug::writeWarning( $limitation, 'Sistem is not configured to check Assigned in  objects' );
                    }
                }
                $sqlParts[] = implode( ' AND ', $sqlPartPart );
            }
            $sqlPermissionCheckingString = ' AND ((' . implode( ') or (', $sqlParts ) . ')) ';

            $query = "SELECT ezcontentobject.*,
                           ezcontentobject_tree.*,
                           ezcontentclass.name as class_name
                    FROM
                          ezcontentobject_tree,
                          ezcontentobject,ezcontentclass
                    WHERE $pathString
                          $depthCond
                          ezcontentclass.version=0 AND
                          node_id != $fromNode AND
                          ezcontentobject_tree.contentobject_id = ezcontentobject.id  AND
                          ezcontentclass.id = ezcontentobject.contentclass_id AND
                          $classCondition
                          ezcontentobject_tree.contentobject_is_published = 1
                          $sqlPermissionCheckingString
                    ORDER BY $sortingFields";

        }
        else
        {
            $query = "SELECT ezcontentobject.*,
                             ezcontentobject_tree.*,
                             ezcontentclass.name as class_name
                      FROM
                            ezcontentobject_tree,
                            ezcontentobject,ezcontentclass
                      WHERE $pathString
                            $depthCond
                            ezcontentclass.version=0 AND
                            node_id != $fromNode AND
                            ezcontentobject_tree.contentobject_id = ezcontentobject.id  AND
                            ezcontentclass.id = ezcontentobject.contentclass_id AND
                            $classCondition
                            ezcontentobject_tree.contentobject_is_published = 1
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

//         $limitationList = array();
        if ( isset( $params['Limitation'] ) )
        {
            $limitationList =& $params['Limitation'];
        }

        if ( isset( $params[ 'Depth' ] ) && $params[ 'Depth' ] > 0 )
        {

            $nodeDepth += $params[ 'Depth' ];
            $depthCond = ' depth <= ' . $nodeDepth . ' and ';
        }

        $ini =& eZINI::instance();
        $classCondition = "";
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
                        eZDebug::writeWarning( $limitation, 'Sistem is not configured to check Assigned in  objects' );
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
//         eZDebug::writeDebug( $nodeListArray[0]['count'], 'childrenCount' );
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

        $subStringString = $db->subString( 'path_string', 1, strlen( $nodePath ) );

        // fetch the object id's which needs to be updated
        $objectIDArray =& $db->arrayQuery( "SELECT
                                                   ezcontentobject.id
                                            FROM
                                                   ezcontentobject_tree, ezcontentobject
                                            WHERE
                                                  $subStringString = '$nodePath' AND
                                                  ezcontentobject_tree.contentobject_id=ezcontentobject.id AND
                                                  ezcontentobject.main_node_id=ezcontentobject_tree.node_id" );
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

    function &fetchByCRC( $pathStr )
    {
        $crcSum = crc32( $pathStr );
        $db =& eZDB::instance();

        $query="SELECT ezcontentobject.*,
                           ezcontentobject_tree.*,
                           ezcontentclass.name as class_name
                    FROM ezcontentobject_tree,
                         ezcontentobject,
                         ezcontentclass
                    WHERE crc32_path = $crcSum AND
                          ezcontentobject_tree.contentobject_id=ezcontentobject.id AND
                          ezcontentclass.version=0  AND
                          ezcontentclass.id = ezcontentobject.contentclass_id  ";

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

    function &fetch( $nodeID )
    {
        $returnValue = null;
        $ini =& eZINI::instance();
        $db =& eZDB::instance();

        if ( $nodeID != 1 )
        {
            $query="SELECT ezcontentobject.*,
                           ezcontentobject_tree.*,
                           ezcontentclass.name as class_name
                    FROM ezcontentobject_tree,
                         ezcontentobject,
                         ezcontentclass
                    WHERE node_id = $nodeID AND
                          ezcontentobject_tree.contentobject_id=ezcontentobject.id AND
                          ezcontentclass.version=0  AND
                          ezcontentclass.id = ezcontentobject.contentclass_id  ";
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
            $returnValue = $retNodeArray[0];
        }
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
        $query="SELECT ezcontentobject.*,
                       ezcontentobject_tree.*,
                       ezcontentclass.name as class_name
                FROM ezcontentobject_tree,
                     ezcontentobject,
                     ezcontentclass
                WHERE $pathString
                      ezcontentobject_tree.contentobject_id=ezcontentobject.id  AND
                      ezcontentclass.version=0 AND
                      ezcontentclass.id = ezcontentobject.contentclass_id
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

        $nodeID = eZContentObjectTreeNode::addChild( $object->attribute( "id" ), $parentNodeID );
        $object->setAttribute( "main_node_id", $nodeID );
        $object->store();

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
        $parentRightMargin = $node->attribute( 'right_margin' );

        $nodeDepth = $parentDepth + 1 ;
        $rightMargin = $parentRightMargin + 1;
        $leftMargin =  $parentRightMargin;


        $insertedNode =& new eZContentObjectTreeNode();
        $insertedNode->setAttribute( 'parent_node_id', $parentMainNodeID );
        $insertedNode->setAttribute( 'contentobject_id', $contentobjectID );
        $insertedNode->setAttribute( 'depth', $nodeDepth );
        $insertedNode->setAttribute( 'path_string', '/TEMPPATH' );
        $insertedNode->store();
/*        $values = $parentMainNodeID  . "," .  $contentobjectID . "," . $nodeDepth . ",
                  '" .  "/TEMPPATH' ," . $leftMargin . "," . $rightMargin ;

        $newNodeQuery = "INSERT INTO ezcontentobject_tree(
                                                          parent_node_id,
                                                          contentobject_id,
                                                          depth,path_string,
                                                          left_margin,
                                                          right_margin )
                                     VALUES (" . $values . ")";

        $db->query( $newNodeQuery );
        $insertedID = $db->lastSerialID( 'ezcontentobject_tree', 'node_id' );
*/
        $insertedID = $insertedNode->attribute( 'node_id' );
        $newNodePath = $parentPath . $insertedID . '/';
        $insertedNode->setAttribute( 'path_string', $newNodePath );
/*        $updatePathQuery= "UPDATE ezcontentobject_tree
                           SET
                               path_string= '$newNodePath'
                           WHERE
                               node_id=$insertedID";
      $db->query( $updatePathQuery );
*/
//        $insertedNode = eZContentObjectTreeNode::fetch( $insertedID );

        $insertedNode->setAttribute( 'path_identification_string', $insertedNode->pathWithNames() );
        $insertedNode->setAttribute( 'crc32_path', crc32 ( $insertedNode->attribute( 'path_identification_string' ) ) );
        eZDebug::writeDebug($insertedNode->pathWithNames(), 'pathWithNames' );
        eZDebug::writeDebug( crc32 ( $insertedNode->pathWithNames() ), "CRC32" );
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
            $node = $this;
        }else
        {
            $node =& eZContentObjectTreeNode::fetch( $nodeID );
        }
        $nodeList = $node->attribute( 'path' );
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
        return implode( '/', $nodePathElementList );

    }

    function updatePathWithNames()
    {
        $this->setAttribute( 'path_identification_string', $this->pathWithNames() );
        $this->setAttribute( 'crc32_path', crc32 ( $this->attribute( 'path_identification_string' ) ) );
        $this->store();
    }
    function updateSubTreePath()
    {
        $subTree = & $this->subTree();
         reset( $subTree );
         while( ( $key = key( $subTree ) ) !== null )
         {
               $node =& $subTree[$key];
               $node->setAttribute( 'path_identification_string', $node->pathWithNames() );
               $node->setAttribute( 'crc32_path', crc32 ( $node->attribute( 'path_identification_string' ) ) );
               $node->store();
               next( $subTree );
         }
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
                                 path_string = " . $db->cancatString( array( "'$newPath'" , "'$nodeID'",$subStringString2 ) ) . " ,
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
        $ini =& eZINI::instance();

        foreach ( $array as $node )
        {
            unset( $object );

            $object =& new eZContentObjectTreeNode( $node );
//            eZDebug::writeDebug( $node, 'node' );
            $object->setName($node['name']);
            if ( $with_contentobject )
            {
                if ( array_key_exists( 'class_name', $node ) )
                {
                    $contentObject =& new eZContentObject( $node );

/*                    if ( $ini->variable( "AccessSetings", "Access" ) == 'GroupBased' )
                    {
                        $permissions['can_read'] = $node['can_read'];
                        $permissions['can_create'] = $node['can_create'];
                        $permissions['can_edit'] = $node['can_edit'];
                        $permissions['can_remove'] = $node['can_remove'];
                    }
                    else
                    {
                        $permissions['can_read'] = 1;
                        $permissions['can_create'] = 1;
                        $permissions['can_edit'] = 1;
                        $permissions['can_remove'] = 1;
                    }
*/                  $permissions = array();
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

    function &object()
    {
        if ( $this->hasContentObject() )
        {
            return $this->ContentObject;
        }
        $contentobject_id = $this->attribute( 'contentobject_id' );
        $obj =& eZContentObject::fetch( $contentobject_id );
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

    function &contentObjectVersionObject( $asObject = true )
    {
        return eZContentObjectVersion::fetchVersion( $this->ContentObjectVersion, $this->ContentObjectID, $asObject );
    }
}

?>
