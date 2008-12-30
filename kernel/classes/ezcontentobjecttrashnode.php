<?php
//
// Definition of eZContentObjectTrashNode class
//
// Created on: <20-Sep-2006 00:00:00 rl>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
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

/*! \file
*/

/*!
  \class eZContentObjectTrashNode ezcontentobjecttrashnode.php
  \brief The class eZContentObjectTrashNode
*/

//class eZContentObjectTrashNode extends eZPersistentObject
class eZContentObjectTrashNode extends eZContentObjectTreeNode
{
    /*!
     Constructor
    */
    function eZContentObjectTrashNode( $row = array() )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( 'fields' => array( 'node_id' => array( 'name' => 'NodeID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'parent_node_id' => array( 'name' => 'ParentNodeID',
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true ),
                                         'main_node_id' => array( 'name' => 'MainNodeID',
                                                                  'datatype' => 'integer',
                                                                  'default' => 0,
                                                                  'required' => true ),
                                         'contentobject_id' => array( 'name' => 'ContentObjectID',
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true,
                                                                      'foreign_class' => 'eZContentObject',
                                                                      'foreign_attribute' => 'id',
                                                                      'multiplicity' => '1..*' ),
                                         'contentobject_version' => array( 'name' => 'ContentObjectVersion',
                                                                           'datatype' => 'integer',
                                                                           'default' => 0,
                                                                           'required' => true ),
                                         'depth' => array( 'name' => 'Depth',
                                                           'datatype' => 'integer',
                                                           'default' => 0,
                                                           'required' => true ),
                                         'sort_field' => array( 'name' => 'SortField',
                                                                'datatype' => 'integer',
                                                                'default' => 1,
                                                                'required' => true ),
                                         'sort_order' => array( 'name' => 'SortOrder',
                                                                'datatype' => 'integer',
                                                                'default' => 1,
                                                                'required' => true ),
                                         'priority' => array( 'name' => 'Priority',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         'modified_subnode' => array( 'name' => 'ModifiedSubNode',
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         'path_string' => array( 'name' => 'PathString',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         'path_identification_string' => array( 'name' => 'PathIdentificationString',
                                                                                'datatype' => 'text',
                                                                                'default' => '',
                                                                                'required' => true ),
                                         'remote_id' => array( 'name' => 'RemoteID',
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => true ),
                                         'is_hidden' => array( 'name' => 'IsHidden',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         'is_invisible' => array( 'name' => 'IsInvisible',
                                                                  'datatype' => 'integer',
                                                                  'default' => 0,
                                                                  'required' => true )
                                          ),

                      'keys' => array( 'node_id' ),
                      'function_attributes' => array( // functional attributes derived from ezcontentobjecttreenode
                                                      'name' => 'getName',
                                                      'data_map' => 'dataMap',
                                                      'object' => 'object',
                                                      'contentobject_version_object' => 'contentObjectVersionObject',
                                                      'sort_array' => 'sortArray',
                                                      'can_read' => 'canRead',
                                                      'can_create' => 'canCreate',
                                                      'can_edit' => 'canEdit',
                                                      'can_remove' => 'canRemove',
                                                      'creator' => 'creator',
                                                      'path_array' => 'pathArray',
                                                      'parent' => 'fetchParent',
                                                      'class_identifier' => 'classIdentifier',
                                                      'class_name' => 'className',
                                                      // new functional attributes
                                                      'original_parent' => 'originalParent',
                                                      'original_parent_path_id_string' => 'originalParentPathIdentificationString'
                                                      ),
                      'class_name' => 'eZContentObjectTrashNode',
                      'name' => 'ezcontentobject_trash' );
    }


    static function createFromNode( $node )
    {
        $row = array( 'node_id' => $node->attribute( 'node_id' ),
                      'parent_node_id' => $node->attribute( 'parent_node_id' ),
                      'main_node_id' => $node->attribute( 'main_node_id' ),
                      'contentobject_id' => $node->attribute( 'contentobject_id' ),
                      'contentobject_version' => $node->attribute( 'contentobject_version' ),
                      'contentobject_is_published' => $node->attribute( 'contentobject_is_published' ),
                      'depth' => $node->attribute( 'depth' ),
                      'sort_field' => $node->attribute( 'sort_field' ),
                      'sort_order' => $node->attribute( 'sort_order' ),
                      'priority' => $node->attribute( 'priority' ),
                      'modified_subnode' => $node->attribute( 'modified_subnode' ),
                      'path_string' => $node->attribute( 'path_string' ),
                      'path_identification_string' => $node->attribute( 'path_identification_string' ),
                      'remote_id' => $node->attribute( 'remote_id' ),
                      'is_hidden' => $node->attribute( 'is_hidden' ),
                      'is_invisible' => $node->attribute( 'is_invisible' ) );

        $trashNode = new eZContentObjectTrashNode( $row );
        return $trashNode;
    }

    function storeToTrash()
    {
        $this->store();
    }

    static function purgeForObject( $contentObjectID )
    {
        if ( !is_numeric( $contentObjectID ) )
            return false;
        $db = eZDB::instance();
        $db->begin();
        $db->query( "DELETE FROM ezcontentobject_trash WHERE contentobject_id='$contentObjectID'" );
        $db->commit();
    }

    static function fetchListForObject( $objectID, $asObject = true, $offset = false, $limit = false )
    {
        return false;
    }

    /*
      Analog of eZContentObjectTreeNode::subTreeByNodeID(Count)() method, see it for extending this method
    */
    static function trashList( $params = false, $asCount = false )
    {
        if ( $params === false )
        {
            $params = array( 'Offset'                   => false,
                             'Limit'                    => false,
                             'SortBy'                   => false,
                             'AttributeFilter'          => false,
                             );
        }

        $offset           = ( isset( $params['Offset'] ) && is_numeric( $params['Offset'] ) ) ? $params['Offset']             : false;
        $limit            = ( isset( $params['Limit']  ) && is_numeric( $params['Limit']  ) ) ? $params['Limit']              : false;
        $asObject         = ( isset( $params['AsObject']          ) )                         ? $params['AsObject']           : true;
        $objectNameFilter = ( isset( $params['ObjectNameFilter']  ) )                         ? $params['ObjectNameFilter']   : false;
        $sortBy           = ( isset( $params['SortBy']  ) && is_array( $params['SortBy']  ) ) ? $params['SortBy']              : array( array( 'name' ) );

        if ( $asCount )
        {
            $sortingInfo = eZContentObjectTreeNode::createSortingSQLStrings( false );
        }
        else
        {
            $sortingInfo = eZContentObjectTreeNode::createSortingSQLStrings( $sortBy, 'ezcot' );
        }

        $attributeFilter         = eZContentObjectTreeNode::createAttributeFilterSQLStrings( $params['AttributeFilter'], $sortingInfo );
        if ( $attributeFilter === false )
        {
            return null;
        }

        $useVersionName     = true;
        $versionNameTables  = eZContentObjectTreeNode::createVersionNameTablesSQLString ( $useVersionName );
        $versionNameTargets = eZContentObjectTreeNode::createVersionNameTargetsSQLString( $useVersionName );
        $versionNameJoins   = eZContentObjectTreeNode::createVersionNameJoinsSQLString  ( $useVersionName, false, false, false, 'ezcot' );

        $languageFilter = ' AND ' . eZContentLanguage::languagesSQLFilter( 'ezcontentobject' );

        $objectNameFilterSQL = eZContentObjectTreeNode::createObjectNameFilterConditionSQLString( $objectNameFilter );

        $limitation = ( isset( $params['Limitation']  ) && is_array( $params['Limitation']  ) ) ? $params['Limitation']: false;
        $limitationList = eZContentObjectTreeNode::getLimitationList( $limitation );
        $sqlPermissionChecking = eZContentObjectTreeNode::createPermissionCheckingSQL( $limitationList, 'ezcontentobject_trash', 'ezcot' );

        if ( $asCount )
        {
            $query = "SELECT count(*) as count \n";
        }
        else
        {
            $query = "SELECT
                        ezcontentobject.*,
                        ezcot.*,
                        ezcontentclass.serialized_name_list as class_serialized_name_list,
                        ezcontentclass.identifier as class_identifier
                        $versionNameTargets
                        $sortingInfo[attributeTargetSQL] \n";
        }
        $query .= "FROM
                        ezcontentobject_trash ezcot,
                        ezcontentobject,
                        ezcontentclass
                        $versionNameTables
                        $sortingInfo[attributeFromSQL]
                        $attributeFilter[from]
                        $sqlPermissionChecking[from]
                   WHERE
                        ezcontentclass.version=0 AND
                        ezcot.contentobject_id = ezcontentobject.id  AND
                        ezcontentclass.id = ezcontentobject.contentclass_id AND
                        $sortingInfo[attributeWhereSQL]
                        $attributeFilter[where]
                        $versionNameJoins
                        $sqlPermissionChecking[where]
                        $objectNameFilterSQL
                        $languageFilter
                        ";
        if ( $sortingInfo['sortingFields'] && strlen( $sortingInfo['sortingFields'] ) > 5  )
            $query .= " ORDER BY $sortingInfo[sortingFields]";

        $db = eZDB::instance();
        if ( !$offset && !$limit )
            $trashRowsArray = $db->arrayQuery( $query );
        else
            $trashRowsArray = $db->arrayQuery( $query, array( 'offset' => $offset,
                                                              'limit'  => $limit ) );

        // cleanup temp tables
        $db->dropTempTableList( $sqlPermissionChecking['temp_tables'] );

        if ( $asCount )
        {
            return $trashRowsArray[0]['count'];
        }
        else if ( $asObject )
        {
            $retTrashNodes = array();
            foreach ( array_keys( $trashRowsArray ) as $key )
            {
                $trashRow =& $trashRowsArray[ $key ];
                $retTrashNodes[] = new eZContentObjectTrashNode( $trashRow );
            }
            return $retTrashNodes;
        }
        else
        {
            return $trashRowsArray;
        }
    }

    static function trashListCount( $params = false )
    {
        return eZContentObjectTrashNode::trashList( $params, true );
    }

    function originalParent()
    {
        $parent = eZContentObjectTreeNode::fetch( $this->attribute( 'parent_node_id' ) );
        $thisPathArray = $this->attribute( 'path_array' );

        if ( is_object( $parent ) and count( $thisPathArray ) > 0 )
        {
            $realParentPathArray = $parent->attribute( 'path_array' );
            $realParentPath = implode( '/', $realParentPathArray );

            array_pop( $thisPathArray );
            $thisParentPath = implode( '/', $thisPathArray );

            if ( $thisParentPath == $realParentPath )
            {
                // original parent exists at the same placement
                return $parent;
            }
        }
        // original parent was moved or deleted
        $ret = null;
        return $ret;
    }

    function originalParentPathIdentificationString()
    {
        $originalParent = $this->originalParent();
        if ( $originalParent )
        {
            return $originalParent->attribute( 'path_identification_string' );
        }
        // original parent was moved or does not exist, return original parent path
        $path = $this->attribute( 'path_identification_string' );
        $path = substr( $path, 0, strrpos( $path, '/') );
        return $path;
    }
}

?>
