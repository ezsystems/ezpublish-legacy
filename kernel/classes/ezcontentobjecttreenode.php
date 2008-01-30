<?php
//
// Definition of eZContentObjectTreeNode class
//
// Created on: <10-Jul-2002 19:28:22 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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
include_once( "kernel/classes/ezurlalias.php" );

class eZContentObjectTreeNode extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZContentObjectTreeNode( $row = array() )
    {
        $this->eZPersistentObject( $row );
    }

    function definition()
    {
        return array( "fields" => array( "node_id" => array( 'name' => "NodeID",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "parent_node_id" => array( 'name' => "ParentNodeID",
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true,
                                                                    'foreign_class' => 'eZContentObjectTreeNode',
                                                                    'foreign_attribute' => 'node_id',
                                                                    'multiplicity' => '1..*' ),
                                         "main_node_id" => array( 'name' => "MainNodeID",
                                                                  'datatype' => 'integer',
                                                                  'default' => 0,
                                                                  'required' => true,
                                                                  'foreign_class' => 'eZContentObjectTreeNode',
                                                                  'foreign_attribute' => 'node_id',
                                                                  'multiplicity' => '1..*' ),
                                         "contentobject_id" => array( 'name' => "ContentObjectID",
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
                                         'contentobject_is_published' => array( 'name' => 'ContentObjectIsPublished',
                                                                                'datatype' => 'integer',
                                                                                'default' => 0,
                                                                                'required' => true ),
                                         "depth" => array( 'name' => "Depth",
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
                                         "path_string" => array( 'name' => "PathString",
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         "path_identification_string" => array( 'name' => "PathIdentificationString",
                                                                                'datatype' => 'text',
                                                                                'default' => '',
                                                                                'required' => true ),
                                         'remote_id' => array( 'name' => 'RemoteID',
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => true ),
                                         "is_hidden" => array( 'name' => "IsHidden",
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         "is_invisible" => array( 'name' => "IsInvisible",
                                                                  'datatype' => 'integer',
                                                                  'default' => 0,
                                                                  'required' => true ) ),
                      "keys" => array( "node_id" ),
                      "function_attributes" => array( "name" => "getName",
                                                      'data_map' => 'dataMap',
                                                      'remote_id' => 'remoteID', // Note: This overrides remote_id field
                                                      "object" => "object",
                                                      "subtree" => "subTree",
                                                      "children" => "children",
                                                      "children_count" => "childrenCount",
                                                      'view_count' => 'viewCount',
                                                      'contentobject_version_object' => 'contentObjectVersionObject',
                                                      'sort_array' => 'sortArray',
                                                      'can_read' => 'canRead',
                                                      'can_pdf' =>  'canPdf',
                                                      'can_create' => 'canCreate',
                                                      'can_edit' => 'canEdit',
                                                      'can_hide' => 'canHide',
                                                      'can_remove' => 'canRemove',
                                                      'can_move' => 'canMoveFrom',
                                                      'can_move_from' => 'canMoveFrom',
                                                      'can_add_location' => 'canAddLocation',
                                                      'can_remove_location' => 'canRemoveLocation',
                                                      'can_view_embed' => 'canViewEmbed',
                                                      'is_main' => 'isMain',
                                                      'creator' => 'creator',
                                                      "path" => "fetchPath",
                                                      'path_array' => 'pathArray',
                                                      "parent" => "fetchParent",
                                                      'url' => 'url',
                                                      'url_alias' => 'urlAlias',
                                                      'class_identifier' => 'classIdentifier',
                                                      'class_name' => 'className',
                                                      'hidden_invisible_string' => 'hiddenInvisibleString',
                                                      'hidden_status_string' => 'hiddenStatusString',
                                                      'classes_js_array' => 'availableClassesJsArray' ),
                      "increment_key" => "node_id",
                      "class_name" => "eZContentObjectTreeNode",
                      "name" => "ezcontentobject_tree" );
    }

    /*!
     Creates a new tree node and returns it.
     \param $parentNodeID The ID of the parent or \c null if the node is not known yet.
     \param $contentObjectID The ID of the object it points to or \c null if it is not known yet.
     \param $contentObjectVersion The version of the object or \c 0 if not known yet.
     \param $sortField Number describing the field to sort by, or \c 0 if not known yet.
     \param $sortOrder Which way to sort, \c true means ascending while \c false is descending.
     \note The attribute \c remote_id will get an automatic and unique value.
    */
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
                      'path_string' => null,
                      'path_identification_string' => null,
                      'is_hidden' => false,
                      'is_invisible' => false,
                      'sort_field' => $sortField,
                      'sort_order' => $sortOrder,
                      'modified_subnode' => 0,
                      'remote_id' => md5( (string)mt_rand() . (string)mktime() ),
                      'priority' => 0 );
        $node = new eZContentObjectTreeNode( $row );
        return $node;
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

    /*!
     Get remote id of content node, the remote ID is often used to synchronise imports and exports.
     If there is no remote ID a new unique one will be generated.
    */
    function &remoteID()
    {
        $remoteID = eZPersistentObject::attribute( 'remote_id', true );
        if ( !$remoteID )
        {
            $this->setAttribute( 'remote_id', md5( (string)mt_rand() . (string)mktime() ) );
            $this->sync( array( 'remote_id' ) );
            $remoteID = eZPersistentObject::attribute( 'remote_id', true );
        }

        return $remoteID;
    }

    /*!
     \return true if this node is the main node.
     */
    function &isMain()
    {
        $retVal = $this->NodeID == $this->MainNodeID;
        return $retVal;
    }

    /*!
     \return the ID of the class attribute with the given ID.
     False is returned if no class/attribute by that identifier is found.
     If multiple classes have the same identifier, the first found is returned.
    */
    function classAttributeIDByIdentifier( $identifier )
    {
        $db =& eZDB::instance();
        $dbName = $db->DB;

        include_once( 'lib/ezutils/classes/ezphpcreator.php' );
        $cacheDir = eZSys::cacheDirectory();
        $phpCache = new eZPHPCreator( "$cacheDir", "classattributeidentifiers_$dbName.php" );

        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler =& eZExpiryHandler::instance();
        $expiryTime = 0;
        if ( $handler->hasTimestamp( 'content-view-cache' ) )
        {
            $expiryTime = $handler->timestamp( 'content-view-cache' );
        }

        if ( $phpCache->canRestore( $expiryTime ) )
        {
            $var = $phpCache->restore( array( 'identifierHash' => 'identifier_hash' ) );
            $identifierHash =& $var['identifierHash'];
        }
        else
        {
            // Fetch identifier/id pair from db
            $query = "SELECT ezcontentclass_attribute.id as attribute_id, ezcontentclass_attribute.identifier as attribute_identifier, ezcontentclass.identifier as class_identifier
                      FROM ezcontentclass_attribute, ezcontentclass
                      WHERE ezcontentclass.id=ezcontentclass_attribute.contentclass_id";
            $identifierArray = $db->arrayQuery( $query );

            $identifierHash = array();
            foreach ( $identifierArray as $identifierRow )
            {
                $classIdentifier = $identifierRow['class_identifier'];
                $attributeIdentifier = $identifierRow['attribute_identifier'];
                $attributeID = $identifierRow['attribute_id'];
                $combinedIdentifier = $classIdentifier . '/' . $attributeIdentifier;
                $identifierHash[$combinedIdentifier] = (int)$attributeID;
            }

            // Store identifier list to cache file
            $phpCache->addVariable( 'identifier_hash', $identifierHash );
            $phpCache->store();
        }
        $return = false;
        if ( isset( $identifierHash[$identifier] ) )
            $return = $identifierHash[$identifier];

        return $return;
    }

    /*!
     \return the ID of the class with the given ID.
     False is returned if no class by that identifier is found.
     If multiple classes have the same identifier, the first found is returned.
    */
    function classIDByIdentifier( $identifier )
    {
        $db =& eZDB::instance();
        $dbName = $db->DB;

        // VS-DBFILE

        include_once( 'lib/ezutils/classes/ezphpcreator.php' );
        $cacheDir = eZSys::cacheDirectory();
        $phpCache = new eZPHPCreator( "$cacheDir", "classidentifiers_$dbName.php", '', array( 'clustering' => 'classidentifiers' ) );

        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler =& eZExpiryHandler::instance();
        $expiryTime = 0;
        if ( $handler->hasTimestamp( 'content-view-cache' ) )
        {
            $expiryTime = $handler->timestamp( 'content-view-cache' );
        }

        if ( $phpCache->canRestore( $expiryTime ) )
        {
            $var = $phpCache->restore( array( 'identifierHash' => 'identifier_hash' ) );
            $identifierHash =& $var['identifierHash'];
        }
        else
        {
            // Fetch identifier/id pair from db
            $query = "SELECT id, identifier FROM ezcontentclass where version=0";
            $identifierArray = $db->arrayQuery( $query );

            $identifierHash = array();
            foreach ( $identifierArray as $identifierRow )
            {
                $identifierHash[$identifierRow['identifier']] = $identifierRow['id'];
            }

            // Store identifier list to cache file
            $phpCache->addVariable( 'identifier_hash', $identifierHash );
            $phpCache->store();
        }
        $return = false;
        if ( isset( $identifierHash[$identifier] ) )
            $return = $identifierHash[$identifier];

        return $return;
    }

    /*!
     \return \c true if the node can be read by the current user.
     \sa checkAccess().
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &canRead( )
    {
        if ( !isset( $this->Permissions["can_read"] ) )
        {
            $this->Permissions["can_read"] = $this->checkAccess( 'read' );
        }
        $p = ( $this->Permissions["can_read"] == 1 );
        return $p;
    }

    /*!
     \return \c true if the current user can create a pdf of this content object.
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &canPdf( )
    {
        if ( !isset( $this->Permissions["can_pdf"] ) )
        {
            $this->Permissions["can_pdf"] = $this->checkAccess( 'pdf' );
        }
        $p = ( $this->Permissions["can_pdf"] == 1 );
        return $p;
    }


    /*!
     \return \c true if the node can be viewed as embeded object by the current user.
     \sa checkAccess().
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &canViewEmbed( )
    {
        if ( !isset( $this->Permissions["can_view_embed"] ) )
        {
            $this->Permissions["can_view_embed"] = $this->checkAccess( 'view_embed' );
        }
        $p = ( $this->Permissions["can_view_embed"] == 1 );
        return $p;
    }

    /*!
     \return \c true if the node can be edited by the current user.
     \sa checkAccess().
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &canEdit( )
    {
        if ( !isset( $this->Permissions["can_edit"] ) )
        {
            $this->Permissions["can_edit"] = $this->checkAccess( 'edit' );
            if ( $this->Permissions["can_edit"] != 1 )
            {
                 $user =& eZUser::currentUser();
                 if ( $user->id() == $this->ContentObject->attribute( 'id' ) )
                 {
                     $access = $user->hasAccessTo( 'user', 'selfedit' );
                     if ( $access['accessWord'] == 'yes' )
                     {
                         $this->Permissions["can_edit"] = 1;
                     }
                 }
            }
        }
        $p = ( $this->Permissions["can_edit"] == 1 );
        return $p;
    }

    /*!
     \return \c true if the node can be hidden by the current user.
     \sa checkAccess().
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &canHide( )
    {
        if ( !isset( $this->Permissions["can_hide"] ) )
        {
            $this->Permissions["can_hide"] = $this->checkAccess( 'hide' );
        }
        $p = ( $this->Permissions["can_hide"] == 1 );
        return $p;
    }

    /*!
     \return \c true if the current user can create a new node as child of this node.
     \sa checkAccess().
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &canCreate( )
    {
        if ( !isset( $this->Permissions["can_create"] ) )
        {
            $this->Permissions["can_create"] = $this->checkAccess( 'create' );
        }
        $p = ( $this->Permissions["can_create"] == 1 );
        return $p;
    }

    /*!
     \return \c true if the node can be removed by the current user.
     \sa checkAccess().
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &canRemove( )
    {
        if ( !isset( $this->Permissions["can_remove"] ) )
        {
            $this->Permissions["can_remove"] = $this->checkAccess( 'remove' );
        }
        $p = ( $this->Permissions["can_remove"] == 1 );
        return $p;
    }

    /*!
     Check if the node can be moved. (actually checks 'edit' and 'remove' permissions)
     \return \c true if the node can be moved by the current user.
     \sa checkAccess().
     \note The reference for the return value is required to workaround
           a bug with PHP references.
     \deprecated The function canMove() is preferred since its naming is clearer.
    */
    function &canMove()
    {
        return $this->canMoveFrom();
    }

    /*!
     Check if the node can be moved. (actually checks 'edit' and 'remove' permissions)
     \return \c true if the node can be moved by the current user.
     \sa checkAccess().
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &canMoveFrom( )
    {
        if ( !isset( $this->Permissions['can_move_from'] ) )
        {
            $this->Permissions['can_move_from'] = $this->checkAccess( 'edit' ) && $this->checkAccess( 'remove' );
        }
        $p = ( $this->Permissions['can_move_from'] == 1 );
        return $p;
    }

    /*!
     \return \c true if a node of class \a $classID can be moved to the current node by the current user.
     \sa checkAccess().
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &canMoveTo( $classID = false )
    {
        if ( !isset( $this->Permissions['can_move_to'] ) )
        {
            $this->Permissions['can_move_to'] = $this->checkAccess( 'create', $classID );
        }
        $p = ( $this->Permissions['can_move_to'] == 1 );
        return $p;
    }

    /*!
     \return \c true if a node can be swaped by the current user.
     \sa checkAccess().
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &canSwap()
    {
        if ( !isset( $this->Permissions['can_swap'] ) )
        {
            $this->Permissions['can_swap'] = $this->checkAccess( 'edit' );
        }
        $p = ( $this->Permissions['can_swap'] == 1 );
        return $p;
    }

    /*!
     \return \c true if current user can add object locations to current node.
     \sa checkAccess()
    */
    function &canAddLocation()
    {
        if ( !isset( $this->Permissions['can_add_location'] ) )
        {
            $this->Permissions['can_add_location'] = $this->checkAccess( 'can_add_location' );
        }
        $p = ( $this->Permissions['can_add_location'] == 1 );
        return $p;
    }

    /*!
     \return \c true if current user can add object locations to current node.
    */
    function &canRemoveLocation()
    {
        if ( !isset( $this->Permissions['can_remove_location'] ) )
        {
            $this->Permissions['can_remove_location'] = $this->checkAccess( 'can_remove_location' );
        }
        $p = ( $this->Permissions['can_remove_location'] == 1 );
        return $p;
    }

    /*!
     \static
     \returns the sort key for the given classAttributeID.
      int|string is returend. False is returned if unsuccessful.
    */
    function sortKeyByClassAttributeID( $classAttributeID )
    {
        include_once( 'kernel/classes/ezcontentclassattribute.php' );
        return eZContentClassAttribute::sortKeyTypeByID( $classAttributeID );
    }

    /*!
     \static
    */
    function dataTypeByClassAttributeID( $classAttributeID )
    {
        include_once( 'kernel/classes/ezcontentclassattribute.php' );
        return eZContentClassAttribute::dataTypeByID( $classAttributeID );
    }


    /*!
     Fetches the number of nodes which exists in the system.
    */
    function fetchListCount()
    {
        $sql = "SELECT count( node_id ) as count FROM ezcontentobject_tree";
        $db =& eZDB::instance();
        $rows = $db->arrayQuery( $sql );
        return $rows[0]['count'];
    }

    /*!
     Fetches a list of nodes and returns it. Offset and limitation can be set if needed.
    */
    function fetchList( $asObject = true, $offset = false, $limit = false )
    {
        $sql = "SELECT * FROM ezcontentobject_tree";
        $parameters = array();
        if ( $offset !== false )
            $parameters['offset'] = $offset;
        if ( $limit !== false )
            $parameters['limit'] = $limit;
        $db =& eZDB::instance();
        $rows = $db->arrayQuery( $sql, $parameters );
        $nodes = array();
        if ( $asObject )
        {
            foreach ( $rows as $row )
            {
                $nodes[] = new eZContentObjectTreeNode( $row );
            }
            return $nodes;
        }
        else
            return $rows;
    }

    /*!
        \a static
    */
    function createSortingSQLStrings( $sortList, $treeTableName = 'ezcontentobject_tree' )
    {
        $sortingInfo = array( 'sortCount'           => 0,
                              'sortingFields'       => " path_string ASC",
                              'attributeJoinCount'  => 0,
                              'attributeFromSQL'    => "",
                              'attributeTargetSQL'  => "",
                              'attributeWhereSQL'   => "" );

        if ( $sortList and is_array( $sortList ) and count( $sortList ) > 0 )
        {
            if ( count( $sortList ) > 1 and !is_array( $sortList[0] ) )
            {
                $sortList = array( $sortList );
            }

            $sortingFields      = '';
            $sortCount          = 0;
            $attributeJoinCount = 0;
            $attributeFromSQL   = "";
            $attributeWhereSQL  = "";
            $datatypeSortingTargetSQL = "";

            foreach ( $sortList as $sortBy )
            {
                if ( is_array( $sortBy ) and count( $sortBy ) > 0 )
                {
                    if ( $sortCount > 0 )
                    {
                        $sortingFields .= ', ';
                    }

                    $sortField = $sortBy[0];
                    switch ( $sortField )
                    {
                        case 'path':
                        {
                            $sortingFields .= 'path_string';
                        } break;
                        case 'path_string':
                        {
                            $sortingFields .= 'path_identification_string';
                        } break;
                        case 'published':
                        {
                            $sortingFields .= 'ezcontentobject.published';
                        } break;
                        case 'modified':
                        {
                            $sortingFields .= 'ezcontentobject.modified';
                        } break;
                        case 'modified_subnode':
                        {
                            $sortingFields .= 'modified_subnode';
                        } break;
                        case 'section':
                        {
                            $sortingFields .= 'ezcontentobject.section_id';
                        } break;
                        case 'depth':
                        {
                            $sortingFields .= 'depth';
                        } break;
                        case 'class_identifier':
                        {
                            $sortingFields .= 'ezcontentclass.identifier';
                        } break;
                        case 'class_name':
                        {
                            $classNameFilter = eZContentClassName::sqlFilter();
                            $sortingFields .= $classNameFilter['nameField'];
                            $attributeFromSQL .= ", $classNameFilter[from]";
                            $attributeWhereSQL .= "$classNameFilter[where] AND ";
                        } break;
                        case 'priority':
                        {
                            $sortingFields .= $treeTableName . '.priority';
                        } break;
                        case 'name':
                        {
                            $sortingFields .= 'ezcontentobject_name.name';
                        } break;
                        case 'attribute':
                        {
                            include_once( 'kernel/classes/ezdatatype.php' );

                            $classAttributeID = $sortBy[2];
                            if ( !is_numeric( $classAttributeID ) )
                                $classAttributeID = eZContentObjectTreeNode::classAttributeIDByIdentifier( $classAttributeID );


                            $contentAttributeTableAlias = "a$attributeJoinCount";
                            $datatypeFromSQL = "ezcontentobject_attribute $contentAttributeTableAlias";
                            $datatypeWhereSQL = "
                                   $contentAttributeTableAlias.contentobject_id = ezcontentobject.id AND
                                   $contentAttributeTableAlias.contentclassattribute_id = $classAttributeID AND
                                   $contentAttributeTableAlias.version = ezcontentobject_name.content_version AND";
                            $datatypeWhereSQL .= eZContentLanguage::sqlFilter( $contentAttributeTableAlias, 'ezcontentobject' );

                            $dataType = eZDataType::create( eZContentObjectTreeNode::dataTypeByClassAttributeID( $classAttributeID ) );
                            if( is_object( $dataType ) && $dataType->customSorting() )
                            {
                                $params = array();
                                $params['contentobject_attr_id'] = "$contentAttributeTableAlias.id";
                                $params['contentobject_attr_version'] = "$contentAttributeTableAlias.version";
                                $params['table_alias_suffix'] = "$attributeJoinCount";

                                $sql = $dataType->customSortingSQL( $params );

                                $datatypeFromSQL .= ", {$sql['from']}";
                                $datatypeWhereSQL .= " AND {$sql['where']}";
                                $datatypeSortingFieldSQL = $sql['sorting_field'];
                                $datatypeSortingTargetSQL .= ', ' . $sql['sorting_field'];
                            }
                            else
                            {
                                // Look up datatype for standard sorting
                                $sortKeyType = eZContentObjectTreeNode::sortKeyByClassAttributeID( $classAttributeID );
                                switch ( $sortKeyType )
                                {
                                    case 'string':
                                        {
                                            $sortKey = 'sort_key_string';
                                        } break;

                                    case 'int':
                                    default:
                                        {
                                            $sortKey = 'sort_key_int';
                                        } break;
                                }

                                $datatypeSortingFieldSQL = "a$attributeJoinCount.$sortKey";
                                $datatypeSortingTargetSQL .= ', ' . $datatypeSortingFieldSQL;
                            }

                            $sortingFields .= "$datatypeSortingFieldSQL";
                            $attributeFromSQL .= ", $datatypeFromSQL";
                            $attributeWhereSQL .= "$datatypeWhereSQL AND ";

                            $attributeJoinCount++;
                        }break;

                        default:
                        {
                            eZDebug::writeWarning( 'Unknown sort field: ' . $sortField, 'eZContentObjectTreeNode::getSortingInfo' );
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

            $sortingInfo['sortCount']           = $sortCount;
            $sortingInfo['sortingFields']       = $sortingFields;
            $sortingInfo['attributeTargetSQL'] = $datatypeSortingTargetSQL;
            $sortingInfo['attributeJoinCount']  = $attributeJoinCount;
            $sortingInfo['attributeFromSQL']    = $attributeFromSQL;
            $sortingInfo['attributeWhereSQL']   = $attributeWhereSQL;
        }

        return $sortingInfo;
    }

    /*!
        \a static
    */
    function createClassFilteringSQLString( $classFilterType, &$classFilterArray )
    {
        // Check for class filtering
        $classCondition = '';

        if ( isset( $classFilterType ) and
             ( $classFilterType == 'include' or $classFilterType == 'exclude' ) and
             count( $classFilterArray ) > 0 )
        {
            $classCondition = '  ';
            $i = 0;
            $classCount = count( $classFilterArray );
            $classIDArray = array();
            foreach ( $classFilterArray as $classID )
            {
                $originalClassID = $classID;
                // Check if classes are recerenced by identifier
                if ( is_string( $classID ) && !is_numeric( $classID ) )
                {
                    $classID = eZContentObjectTreeNode::classIDByIdentifier( $classID );
                }
                if ( is_numeric( $classID ) )
                {
                    $classIDArray[] = $classID;
                }
                else
                {
                    eZDebugSetting::writeWarning( 'kernel-content-class', "Invalid class identifier in subTree() classfilterarray, classID : " . $originalClassID );
                }
            }

            if ( count( $classIDArray ) > 0  )
            {
                $classCondition .= " ezcontentobject.contentclass_id ";
                if ( $classFilterType == 'include' )
                    $classCondition .= " IN ";
                else
                    $classCondition .= " NOT IN ";

                $classIDString =  implode( ', ', $classIDArray );
                $classCondition .= ' ( ' . $classIDString . ' ) AND';
            }
            else
            {
                if ( count( $classIDArray ) == 0 and count( $classFilterArray ) > 0 and $classFilterType == 'include' )
                {
                    $classCondition = false;
                }
            }
        }

        return $classCondition;
    }

    /*!
        \a static
    */
    function createExtendedAttributeFilterSQLStrings( &$extendedAttributeFilter )
    {
        $filter = array( 'tables'   => '',
                         'joins'    => '' );

        if ( $extendedAttributeFilter and count( $extendedAttributeFilter ) > 1 )
        {
            $extendedAttributeFilterID      = $extendedAttributeFilter['id'];
            $extendedAttributeFilterParams  = $extendedAttributeFilter['params'];
            $filterINI                      =& eZINI::instance( 'extendedattributefilter.ini' );

            $filterClassName    = $filterINI->variable( $extendedAttributeFilterID, 'ClassName' );
            $filterMethodName   = $filterINI->variable( $extendedAttributeFilterID, 'MethodName' );
            $filterFile         = $filterINI->variable( $extendedAttributeFilterID, 'FileName' );

            if ( $filterINI->hasVariable( $extendedAttributeFilterID, 'ExtensionName' ) )
            {
                include_once( 'lib/ezutils/classes/ezextension.php' );
                $extensionName = $filterINI->variable( $extendedAttributeFilterID, 'ExtensionName' );
                ext_activate( $extensionName, $filterFile );
            }
            else
            {
                include_once( $filterFile );
            }

            $classObject        = new $filterClassName();
            $parameterArray     = array( $extendedAttributeFilterParams );

            $sqlResult          = call_user_func_array( array( $classObject, $filterMethodName ), $parameterArray );

            $filter['tables']   = $sqlResult['tables'];
            $filter['joins']    = $sqlResult['joins'];

        }

        return $filter;
    }

    /*!
        \a static
    */
    function createMainNodeConditionSQLString( $mainNodeOnly )
    {
        // Main node check
        $mainNodeCondition = '';
        if ( isset( $mainNodeOnly ) && $mainNodeOnly === true )
        {
            $mainNodeCondition = 'ezcontentobject_tree.node_id = ezcontentobject_tree.main_node_id AND';
        }

        return $mainNodeCondition;
    }

    /*!
        \a static
    */
    function createObjectNameFilterConditionSQLString( $filter )
    {
        if ( !$filter )
            return '';

        $db =& eZDB::instance();
        if ( $filter == 'others' )
        {
            include_once('kernel/common/ezalphabetoperator.php');
            $alphabet = eZAlphabetOperator::fetchAlphabet();
            $sql = '';
            foreach ( $alphabet as $letter )
            {
                $sql .= " AND ezcontentobject.name NOT LIKE '". $db->escapeString( $letter ) . "%'\n";
            }
            return $sql;
        }
        $objectNameFilterSQL =  " AND ezcontentobject.name LIKE '" . $db->escapeString( $filter ) ."%'";
        return $objectNameFilterSQL;
    }


    /*!
        \a static
    */
    function createAttributeFilterSQLStrings( &$attributeFilter, &$sortingInfo )
    {
        // Check for attribute filtering

        $filterSQL = array( 'from'    => '',
                            'where'   => '' );

        $invalidFilterSQL = false;
        $totalAttributesFiltersCount = 0;
        $invalidAttributesFiltersCount = 0;

        if ( isset( $attributeFilter ) && $attributeFilter !== false )
        {
            $filterArray = $attributeFilter;

            // Check if first value of array is a string.
            // To check for and/or filtering
            $filterJoinType = 'AND';
            if ( is_string( $filterArray[0] ) )
            {
                if ( strtolower( $filterArray[0] ) == 'or' )
                {
                    $filterJoinType = 'OR';
                }
                else if ( strtolower( $filterArray[0] ) == 'and' )
                {
                    $filterJoinType = 'AND';
                }
                unset( $filterArray[0] );
            }

            $attibuteFilterJoinSQL = "";
            $filterCount = $sortingInfo['sortCount'];
            $justFilterCount = 0;

            $db =& eZDB::instance();
            if ( is_array( $filterArray ) )
            {
                // Handle attribute filters and generate SQL
                $totalAttributesFiltersCount = count( $filterArray );

                foreach ( $filterArray as $filter )
                {
                    $isFilterValid = true; // by default assumes that filter is valid

                    $filterAttributeID = $filter[0];
                    $filterType = $filter[1];
                    $filterValue = is_array( $filter[2] ) ? '' : $db->escapeString( $filter[2] );

                    $useAttributeFilter = false;
                    switch ( $filterAttributeID )
                    {
                        case 'path':
                        {
                            $filterField = 'path_string';
                        } break;
                        case 'published':
                        {
                            $filterField = 'ezcontentobject.published';
                        } break;
                        case 'modified':
                        {
                            $filterField = 'ezcontentobject.modified';
                        } break;
                        case 'modified_subnode':
                        {
                            $filterField = 'modified_subnode';
                        } break;
                        case 'section':
                        {
                            $filterField = 'ezcontentobject.section_id';
                        } break;
                        case 'depth':
                        {
                            $filterField = 'depth';
                        } break;
                        case 'class_identifier':
                        {
                            $filterField = 'ezcontentclass.identifier';
                        } break;
                        case 'class_name':
                        {
                            $classNameFilter = eZContentClassName::sqlFilter();
                            $filterField = $classNameFilter['nameField'];
                            $filterSQL['from'] .= ", $classNameFilter[from]";
                            $filterSQL['where'] .= "$classNameFilter[where] AND ";
                        } break;
                        case 'priority':
                        {
                            $filterField = 'ezcontentobject_tree.priority';
                        } break;
                        case 'name':
                        {
                            $filterField = 'ezcontentobject_name.name';
                        } break;
                        case 'owner':
                        {
                            $filterField = 'ezcontentobject.owner_id';
                        } break;
                        default:
                        {
                            $useAttributeFilter = true;
                        } break;
                    }

                    if ( $useAttributeFilter )
                    {
                        if ( !is_numeric( $filterAttributeID ) )
                            $filterAttributeID = eZContentObjectTreeNode::classAttributeIDByIdentifier( $filterAttributeID );

                        if ( $filterAttributeID === false )
                        {
                            $isFilterValid = false;
                            if( $filterJoinType === 'AND' )
                            {
                                // go out
                                $invalidAttributesFiltersCount = $totalAttributesFiltersCount;
                                break;
                            }

                            ++$invalidAttributesFiltersCount;
                        }
                        else
                        {
                            // Check datatype for filtering
                            $filterDataType = eZContentObjectTreeNode::sortKeyByClassAttributeID( $filterAttributeID );
                            if ( $filterDataType === false )
                            {
                                $isFilterValid = false;
                                if( $filterJoinType === 'AND' )
                                {
                                    // go out
                                    $invalidAttributesFiltersCount = $totalAttributesFiltersCount;
                                    break;
                                }

                                // check next filter
                                ++$invalidAttributesFiltersCount;
                            }
                            else
                            {
                                $sortKey = false;
                                if ( $filterDataType == 'string' )
                                {
                                    $sortKey = 'sort_key_string';
                                }
                                else
                                {
                                    $sortKey = 'sort_key_int';
                                }

                                $filterField = "a$filterCount.$sortKey";

                                // Use the same joins as we do when sorting,
                                // if more attributes are filtered by we will append them
                                if ( $filterCount >= $sortingInfo['attributeJoinCount'] )
                                {
                                    $filterSQL['from']  .= ", ezcontentobject_attribute a$filterCount ";
                                    $filterSQL['where'] .= "
                                       a$filterCount.contentobject_id = ezcontentobject.id AND
                                       a$filterCount.contentclassattribute_id = $filterAttributeID AND
                                       a$filterCount.version = ezcontentobject_name.content_version AND ";
                                    $filterSQL['where'] .= eZContentLanguage::sqlFilter( "a$filterCount", 'ezcontentobject' ).' AND ';
                                }
                                else
                                {
                                    $filterSQL['where'] .= "
                                      a$filterCount.contentobject_id = ezcontentobject.id AND
                                      a$filterCount.contentclassattribute_id = $filterAttributeID AND
                                      a$filterCount.version = ezcontentobject_name.content_version AND ";
                                    $filterSQL['where'] .= eZContentLanguage::sqlFilter( "a$filterCount", 'ezcontentobject' ). ' AND ';
                                }
                            }
                        }
                    }

                    if( $isFilterValid )
                    {
                        $hasFilterOperator = true;
                        // Controls quotes around filter value, some filters do this manually
                        $noQuotes = false;
                        // Controls if $filterValue or $folder[2] is used, $filterValue is already escaped
                        $unEscape = false;

                        switch ( $filterType )
                        {
                            case '=' :
                            {
                                $filterOperator = '=';
                            }break;

                            case '!=' :
                            {
                                $filterOperator = '<>';
                            }break;

                            case '>' :
                            {
                                $filterOperator = '>';
                            }break;

                            case '<' :
                            {
                                $filterOperator = '<';
                            }break;

                            case '<=' :
                            {
                                $filterOperator = '<=';
                            }break;

                            case '>=' :
                            {
                                $filterOperator = '>=';
                            }break;

                            case 'like':
                            case 'not_like':
                            {
                                $filterOperator = ( $filterType == 'like' ? 'LIKE' : 'NOT LIKE' );
                                // We escape the string ourselves, this MUST be done before wildcard replace
                                $filter[2] = $db->escapeString( $filter[2] );
                                $unEscape = true;
                                // Since * is used as wildcard we need to transform the string to
                                // use % as wildcard. The following rules apply:
                                // - % -> \%
                                // - * -> %
                                // - \* -> *
                                // - \\ -> \

                                $filter[2] = preg_replace( array( '#%#m',
                                                                  '#(?<!\\\\)\\*#m',
                                                                  '#(?<!\\\\)\\\\\\*#m',
                                                                  '#\\\\\\\\#m' ),
                                                           array( '\\%',
                                                                  '%',
                                                                  '*',
                                                                  '\\\\' ),
                                                           $filter[2] );
                            } break;

                            case 'in':
                            case 'not_in' :
                            {
                                $filterOperator = ( $filterType == 'in' ? 'IN' : 'NOT IN' );
                                // Turn off quotes for value, we do this ourselves
                                $noQuotes = true;
                                if ( is_array( $filter[2] ) )
                                {
                                    reset( $filter[2] );
                                    while ( list( $key, $value ) = each( $filter[2] ) )
                                    {
                                        // Non-numerics must be escaped to avoid SQL injection
                                        $filter[2][$key] = is_numeric( $value ) ? $value : "'" . $db->escapeString( $value ) . "'";
                                    }
                                    $filterValue = '(' .  implode( ",", $filter[2] ) . ')';
                                }
                                else
                                {
                                    $hasFilterOperator = false;
                                }
                            } break;

                            case 'between':
                            case 'not_between' :
                            {
                                $filterOperator = ( $filterType == 'between' ? 'BETWEEN' : 'NOT BETWEEN' );
                                // Turn off quotes for value, we do this ourselves
                                $noQuotes = true;
                                if ( is_array( $filter[2] ) )
                                {
                                    // Check for non-numerics to avoid SQL injection
                                    if ( !is_numeric( $filter[2][0] ) )
                                        $filter[2][0] = "'" . $db->escapeString( $filter[2][0] ) . "'";
                                    if ( !is_numeric( $filter[2][1] ) )
                                        $filter[2][1] = "'" . $db->escapeString( $filter[2][1] ) . "'";

                                    $filterValue = $filter[2][0] . ' AND ' . $filter[2][1];
                                }
                            } break;

                            default :
                            {
                                $hasFilterOperator = false;
                                eZDebug::writeError( "Unknown attribute filter type: $filterType", "eZContentObjectTreeNode::subTree()" );
                            }break;

                        }
                        if ( $hasFilterOperator )
                        {
                            if ( ( $filterCount - $sortingInfo['sortCount'] ) > 0 )
                                $attibuteFilterJoinSQL .= " $filterJoinType ";

                            // If $unEscape is true we get the filter value from the 2nd element instead
                            // which must have been escaped by filter type
                            $filterValue = $unEscape ? $filter[2] : $filterValue;

                            $attibuteFilterJoinSQL .= "$filterField $filterOperator ";
                            $attibuteFilterJoinSQL .= $noQuotes ? "$filterValue " : "'" . $filterValue . "' ";

                            $filterCount++;
                            $justFilterCount++;
                        }
                    }
                } // end of 'foreach ( $filterArray as $filter )'

                if( $totalAttributesFiltersCount == $invalidAttributesFiltersCount )
                {
                    eZDebug::writeNotice( "Attribute filter returned false" );
                    $filterSQL = $invalidFilterSQL;
                }
                else
                {
                    if ( $justFilterCount > 0 )
                        $filterSQL['where'] .= "\n                            ( " . $attibuteFilterJoinSQL . " ) AND ";
                }
            } // endif 'if ( is_array( $filterArray ) )'
        }

        return $filterSQL;
    }

    /*!
        \a static
    */
    function createNotEqParentSQLString( $nodeID, $depth, $depthOperator )
    {
        $notEqParentString  = '';
        if( !$depth || !$depthOperator || $depthOperator != 'eq' )
        {
            $notEqParentString  = "ezcontentobject_tree.node_id != $nodeID AND";
        }

        return $notEqParentString;
    }

    /*!
        \a static
    */
    function createPathConditionSQLString( $nodePath, $nodeDepth, $depth, $depthOperator )
    {
        $pathCondition  = '';
        $depthCondition = '';

        if ( $depth )
        {
            $sqlDepthOperator = '<=';
            if ( $depthOperator )
            {
                if ( $depthOperator == 'lt' )
                {
                    $sqlDepthOperator = '<';
                }
                else if ( $depthOperator == 'gt' )
                {
                    $sqlDepthOperator = '>';
                }
                else if ( $depthOperator == 'le' )
                {
                    $sqlDepthOperator = '<=';
                }
                else if ( $depthOperator == 'ge' )
                {
                    $sqlDepthOperator = '>=';
                }
                else if ( $depthOperator == 'eq' )
                {
                    $sqlDepthOperator = '=';
                }
            }

            $nodeDepth += $depth;
            $depthCondition = ' ezcontentobject_tree.depth '. $sqlDepthOperator . ' ' . $nodeDepth . '  and ';
        }

        $pathCondition = " ezcontentobject_tree.path_string like '$nodePath%' and $depthCondition ";
        return $pathCondition;
    }

    /*!
        \a static
    */
    function createPathConditionAndNotEqParentSQLStrings( &$outPathConditionStr, &$outNotEqParentStr, &$treeNode, $nodeID, $depth, $depthOperator )
    {
        if ( is_array( $nodeID ) )
        {
            $nodeIDList             = $nodeID;
            $sqlPartForOneNodeList  = array();

            foreach ( $nodeIDList as $nodeID )
            {
                $node           = eZContentObjectTreeNode::fetch( $nodeID, false, false );
                if ( !is_array( $node ) )
                    return false;

                $nodePath       = $node['path_string'];
                $nodeDepth      = $node['depth'];
                $depthCond      = '';
                if ( $depth )
                {
                    $sqlDepthOperator = '<=';
                    if ( $depthOperator )
                    {
                        if ( $depthOperator == 'lt' )
                        {
                            $sqlDepthOperator = '<';
                        }
                        else if ( $depthOperator == 'gt' )
                        {
                            $sqlDepthOperator = '>';
                        }
                        else if ( $depthOperator == 'le' )
                        {
                            $sqlDepthOperator = '<=';
                        }
                        else if ( $depthOperator == 'ge' )
                        {
                            $sqlDepthOperator = '>=';
                        }
                        else if ( $depthOperator == 'eq' )
                        {
                            $sqlDepthOperator = '=';
                        }
                    }
                    $nodeDepth += $depth;
                    $depthCond = ' and ezcontentobject_tree.depth '. $sqlDepthOperator . ' ' . $nodeDepth . ' ';
                }

                $outNotEqParentStr          = " and ezcontentobject_tree.node_id != $nodeID ";
                $sqlPartForOneNodeList[]    = " ( ezcontentobject_tree.path_string like '$nodePath%'   $depthCond $outNotEqParentStr ) ";
                $outNotEqParentStr          = '';
            }
            $outPathConditionStr = implode( ' or ', $sqlPartForOneNodeList );
            $outPathConditionStr = ' (' . $outPathConditionStr . ') and';
        }
        else
        {
            if ( $nodeID == 0 )
            {
                if ( !is_object( $treeNode ) or get_class( $treeNode ) != 'ezcontentobjecttreenode' )
                    return false;

                $node =& $treeNode;
                $nodeID = $node->attribute( 'node_id' );
                $nodePath = $node->attribute('path_string');
                $nodeDepth  = $node->attribute('depth');
            }
            else
            {
                $node = eZContentObjectTreeNode::fetch( $nodeID, false, false );
                if ( !is_array( $node ) )
                    return false;

                $nodePath   = $node['path_string'];
                $nodeDepth  = $node['depth'];
            }

            $outNotEqParentStr   = eZContentObjectTreeNode::createNotEqParentSQLString( $nodeID, $depth, $depthOperator );
            $outPathConditionStr = eZContentObjectTreeNode::createPathConditionSQLString( $nodePath, $nodeDepth, $depth, $depthOperator );
        }

        return true;
    }

    /*!
        \a static
    */
    function createGroupBySQLStrings( &$outGroupBySelectText, &$outGroupByText, $groupBy )
    {
        if ( $groupBy )
        {
            if ( isset( $groupBy['field'] ) and isset( $groupBy['type'] ) )
            {
                $groupByField       = $groupBy['field'];
                $groupByFieldType   = $groupBy['type'];

                switch ( $groupByField )
                {
                    case 'published':
                    {
                        $groupBySelect = eZContentObjectTreeNode::subTreeGroupByDateField( "ezcontentobject." . $groupByField, $groupByFieldType );
                        $groupBySelect['field'] = "ezcontentobject." . $groupByField;
                    } break;
                    case 'modified':
                    {
                        $groupBySelect = eZContentObjectTreeNode::subTreeGroupByDateField( "ezcontentobject." . $groupByField, $groupByFieldType );
                        $groupBySelect['field'] = "ezcontentobject." . $groupByField;
                    } break;
                }

                $outGroupBySelectText = ", " . $groupBySelect['select'];
                $outGroupByText = "GROUP BY " . $groupBySelect['group_field'];
            }
        }
    }

    /*!
        \a static
    */
    function createVersionNameTablesSQLString( $useVersionName )
    {
        $versionNameTables = '';

        if ( $useVersionName )
        {
            $versionNameTables = ', ezcontentobject_name ';
        }

        return $versionNameTables;
    }

    /*!
        \a static
    */
    function createVersionNameTargetsSQLString( $useVersionName )
    {
        $versionNameTargets = '';

        if ( $useVersionName )
        {
            $versionNameTargets = ', ezcontentobject_name.name as name,  ezcontentobject_name.real_translation ';
        }


        return $versionNameTargets;
    }

    /*!
        \a static
    */
    function createVersionNameJoinsSQLString( $useVersionName, $includeAnd = true, $onlyTranslated = false, $lang = false, $treeTableName = 'ezcontentobject_tree' )
    {
        $versionNameJoins = '';
        if ( $useVersionName )
        {
            if ( $includeAnd )
            {
                $versionNameJoins .= ' AND ';
            }
            $versionNameJoins .= " $treeTableName.contentobject_id = ezcontentobject_name.contentobject_id and
                                   $treeTableName.contentobject_version = ezcontentobject_name.content_version and ";
            $versionNameJoins .= eZContentLanguage::sqlFilter( 'ezcontentobject_name', 'ezcontentobject' );
        }
        return $versionNameJoins;
    }

    /*!
        \a static
        Deprecated. Use 'createPermissionCheckingSQL' instead.
    */
    function createPermissionCheckingSQLString( &$limitationList )
    {
        $sqlPermissionChecking = eZContentObjectTreeNode::createPermissionCheckingSQL( $limitationList );
        return $sqlPermissionChecking['where'];
    }

    /*!
        \a static
    */
    function createPermissionCheckingSQL( &$limitationList, $treeTableName = 'ezcontentobject_tree', $tableAliasName = 'ezcontentobject_tree' )
    {
        $db =& eZDB::instance();

        $sqlPermissionCheckingFrom = '';
        $sqlPermissionCheckingWhere = '';
        $sqlPermissionTempTables = array();
        $groupPermTempTable = false;

        if ( is_array( $limitationList ) && count( $limitationList ) > 0 )
        {
            $sqlParts = array();
            foreach( $limitationList as $limitationArray )
            {
                $sqlPartPart = array();
                $sqlPartPartPart = array();
                $sqlPlacementPart = array();

                $count = 1;
                foreach ( array_keys( $limitationArray ) as $ident )
                {
                    switch( $ident )
                    {
                        case 'Class':
                        {
                            $sqlPartPart[] = 'ezcontentobject.contentclass_id in (' . implode( ', ', $limitationArray[$ident] ) . ')';
                        } break;

                        case 'Section':
                        case 'User_Section':
                        {
                            $sqlPartPart[] = 'ezcontentobject.section_id in (' . implode( ', ', $limitationArray[$ident] ) . ')';
                        } break;

                        case 'Owner':
                        {
                            $user   =& eZUser::currentUser();
                            $userID = $user->attribute( 'contentobject_id' );
                            $sqlPartPart[] = "ezcontentobject.owner_id = '" . $db->escapeString( $userID ) . "'";
                        } break;

                        case 'Group':
                        {
                            if ( !$groupPermTempTable )
                            {
                                $user =& eZUser::currentUser();
                                $userContentObject =& $user->attribute( 'contentobject' );
                                $parentList =& $userContentObject->attribute( 'parent_nodes' );

                                $groupPermTempTable = $db->generateUniqueTempTableName( 'ezgroup_perm_tmp_%' );
                                $sqlPermissionTempTables[] = $groupPermTempTable;

                                $db->createTempTable( "CREATE TEMPORARY TABLE $groupPermTempTable ( user_id int )" );
                                $db->query( "INSERT INTO $groupPermTempTable
                                                    SELECT DISTINCT contentobject_id AS user_id
                                                    FROM     ezcontentobject_tree
                                                    WHERE    parent_node_id IN ("  . implode( ', ', $parentList ) . ')' );

                                $sqlPermissionCheckingFrom = ', ' . $groupPermTempTable;
                            }
                            $sqlPartPart[] = "ezcontentobject.owner_id = $groupPermTempTable.user_id";
                        } break;

                        case 'Node':
                        {
                            $sqlPlacementPart[] = $tableAliasName . '.node_id in (' . implode( ', ', $limitationArray[$ident] ) . ')';
                        } break;

                        case 'Subtree':
                        {
                            $pathArray =& $limitationArray[$ident];

                            $sqlSubtreePart = array();
                            foreach ( $pathArray as $limitationPathString )
                            {
                                $sqlSubtreePart[] = "$tableAliasName.path_string like '$limitationPathString%'";
                            }
                            $sqlPlacementPart[] = implode( ' OR ', $sqlSubtreePart );
                        } break;

                        case 'User_Subtree':
                        {
                            $pathArray =& $limitationArray[$ident];
                            $sqlPartUserSubtree = array();
                            foreach ( $pathArray as $limitationPathString )
                            {
                                $sqlPartUserSubtree[] = "$tableAliasName.path_string like '$limitationPathString%'";
                            }
                            $sqlPartPart[] = implode( ' OR ', $sqlPartUserSubtree );
                        }
                    }

                    $count++;
                }
                if ( $sqlPlacementPart )
                {
                    $sqlPartPart[] = '( ( ' . implode( ' ) OR ( ', $sqlPlacementPart ) . ' ) )';
                }
                if ( $sqlPartPartPart )
                {
                    $sqlPartPart[] = '( ' . implode( ' ) OR ( ', $sqlPartPartPart ) . ' )';
                }
                $sqlParts[] = implode( ' AND ', $sqlPartPart );
            }
            $sqlPermissionCheckingWhere = ' AND ((' . implode( ') OR (', $sqlParts ) . ')) ';
        }

        $sqlPermissionChecking = array( 'from' => $sqlPermissionCheckingFrom,
                                        'where' => $sqlPermissionCheckingWhere,
                                        'temp_tables' => $sqlPermissionTempTables );

        return $sqlPermissionChecking;
    }


    /*!
        \a static
    */
    function createNodesConditionSQLStringFromPath( $nodePath, $includingLastNodeInThePath )
    {
        $pathString = false;
        $pathArray  = explode( '/', trim($nodePath,'/') );

        if ( $includingLastNodeInThePath == false )
            $pathArray = array_slice( $pathArray, 0, count($pathArray)-1 );

        if ( count( $pathArray ) > 0 )
        {
            foreach ( $pathArray as $node )
            {
                $pathString .= 'or node_id = ' . $node . ' ';

            }
            if ( strlen( $pathString) > 0 )
            {
                $pathString = '('. substr( $pathString, 2 ) . ') and ';
            }
        }

        return $pathString;
    }

    /*!
        \a static
        If \a $useSettings is true \a $fetchHidden will be ignored.
        If \a $useSettings is false \a $fetchHidden will be used.
    */
    function createShowInvisibleSQLString( $useSettings, $fetchHidden = true )
    {
        $showInvisibleNodesCond = '';
        $showInvisible          = $fetchHidden;

        if ( $useSettings )
            $showInvisible = eZContentObjectTreeNode::showInvisibleNodes();

        if ( !$showInvisible )
            $showInvisibleNodesCond = 'AND ezcontentobject_tree.is_invisible = 0';

        return $showInvisibleNodesCond;
    }

    /*!
     \a static
     \returns true if we should show invisible nodes (determined by ini setting), false otherwise.
    */
    function showInvisibleNodes()
    {
        static $cachedResult;

        if ( !isset( $cachedResult ) )
        {
            $ini =& eZINI::instance( 'site.ini' );
            $cachedResult = $ini->hasVariable( 'SiteAccessSettings', 'ShowHiddenNodes' ) ?
                            $ini->variable( 'SiteAccessSettings', 'ShowHiddenNodes' ) == 'true' :
                            true;
        }

        return $cachedResult;
    }

    /*!
        \a static
    */
    function getLimitationList( &$limitation )
    {
        $limitationList = array();

        if ( $limitation !== false )
        {
            $limitationList = $limitation;
        }
        else if ( isset( $GLOBALS['ezpolicylimitation_list']['content']['read'] ) )
        {
            $limitationList =& $GLOBALS['ezpolicylimitation_list']['content']['read'];
            eZDebugSetting::writeDebug( 'kernel-content-treenode', $limitationList, "limitation list"  );
        }
        else
        {
            include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
            $currentUser =& eZUser::currentUser();
            $accessResult = $currentUser->hasAccessTo( 'content', 'read' );

            if ( $accessResult['accessWord'] == 'no' )
            {
                $limitationList = false;
                $GLOBALS['ezpolicylimitation_list']['content']['read'] = false;
            }
            else if ( $accessResult['accessWord'] == 'limited' )
            {
                $limitationList =& $accessResult['policies'];
                $GLOBALS['ezpolicylimitation_list']['content']['read'] =& $accessResult['policies'];
            }
        }

        return $limitationList;
    }

    /*!
    */
    function &subTree( $params = false ,$nodeID = 0 )
    {
        if ( !is_numeric( $nodeID ) and !is_array( $nodeID ) )
        {
            $retValue = null;
            return $retValue;
        }

        if ( $params === false )
        {
            $params = array( 'Depth'                    => false,
                             'Offset'                   => false,
                             //'OnlyTranslated'           => false,
                             'Language'                 => false,
                             'Limit'                    => false,
                             'SortBy'                   => false,
                             'AttributeFilter'          => false,
                             'ExtendedAttributeFilter'  => false,
                             'ClassFilterType'          => false,
                             'ClassFilterArray'         => false,
                             'GroupBy'                  => false );
        }

        $offset           = ( isset( $params['Offset'] ) && is_numeric( $params['Offset'] ) ) ? $params['Offset']             : false;
        //$onlyTranslated   = ( isset( $params['OnlyTranslated']      ) )                       ? $params['OnlyTranslated']     : false;
        $language         = ( isset( $params['Language']      ) )                             ? $params['Language']           : false;
        $limit            = ( isset( $params['Limit']  ) && is_numeric( $params['Limit']  ) ) ? $params['Limit']              : false;
        $depth            = ( isset( $params['Depth']  ) && is_numeric( $params['Depth']  ) ) ? $params['Depth']              : false;
        $depthOperator    = ( isset( $params['DepthOperator']     ) )                         ? $params['DepthOperator']      : false;
        $asObject         = ( isset( $params['AsObject']          ) )                         ? $params['AsObject']           : true;
        $loadDataMap      = ( isset( $params['LoadDataMap'] ) )                               ? $params['LoadDataMap']        : false;
        $groupBy          = ( isset( $params['GroupBy']           ) )                         ? $params['GroupBy']            : false;
        $mainNodeOnly     = ( isset( $params['MainNodeOnly']      ) )                         ? $params['MainNodeOnly']       : false;
        $ignoreVisibility = ( isset( $params['IgnoreVisibility']  ) )                         ? $params['IgnoreVisibility']   : false;
        $objectNameFilter = ( isset( $params['ObjectNameFilter']  ) )                         ? $params['ObjectNameFilter']   : false;

        if ( $offset < 0 )
        {
            $offset = ABS( $offset );
        }

        if ( !isset( $params['SortBy'] ) )
            $params['SortBy'] = false;
        if ( !isset( $params['ClassFilterType'] ) )
            $params['ClassFilterType'] = false;

        if ( $language )
        {
            if ( !is_array( $language ) )
            {
                $language = array( $language );
            }
            eZContentLanguage::setPrioritizedLanguages( $language );
        }

        $sortingInfo             = eZContentObjectTreeNode::createSortingSQLStrings( $params['SortBy'] );
        $classCondition          = eZContentObjectTreeNode::createClassFilteringSQLString( $params['ClassFilterType'], $params['ClassFilterArray'] );
        if ( $classCondition === false )
        {
            eZDebug::writeNotice( "Class filter returned false" );
            $retValue = null;
            return $retValue;
        }

        $attributeFilter         = eZContentObjectTreeNode::createAttributeFilterSQLStrings( $params['AttributeFilter'], $sortingInfo );
        if ( $attributeFilter === false )
        {
            $retValue = null;
            return $retValue;
        }
        $extendedAttributeFilter = eZContentObjectTreeNode::createExtendedAttributeFilterSQLStrings( $params['ExtendedAttributeFilter'] );
        $mainNodeOnlyCond        = eZContentObjectTreeNode::createMainNodeConditionSQLString( $mainNodeOnly );

        $pathStringCond     = '';
        $notEqParentString  = '';
        // If the node(s) doesn't exist we return null.
        if ( !eZContentObjectTreeNode::createPathConditionAndNotEqParentSQLStrings( $pathStringCond, $notEqParentString, $this, $nodeID, $depth, $depthOperator ) )
        {
            $retValue = null;
            return $retValue;
        }

        $groupBySelectText  = '';
        $groupByText        = '';
        eZContentObjectTreeNode::createGroupBySQLStrings( $groupBySelectText, $groupByText, $groupBy );

        $useVersionName     = true;

        $versionNameTables  = eZContentObjectTreeNode::createVersionNameTablesSQLString ( $useVersionName );
        $versionNameTargets = eZContentObjectTreeNode::createVersionNameTargetsSQLString( $useVersionName );
        $versionNameJoins   = eZContentObjectTreeNode::createVersionNameJoinsSQLString  ( $useVersionName, false );

        $languageFilter = ' AND ' . eZContentLanguage::languagesSQLFilter( 'ezcontentobject' );

        if ( $language )
        {
            eZContentLanguage::clearPrioritizedLanguages();
        }
        $objectNameFilterSQL = eZContentObjectTreeNode::createObjectNameFilterConditionSQLString( $objectNameFilter );

        $limitation = ( isset( $params['Limitation']  ) && is_array( $params['Limitation']  ) ) ? $params['Limitation']: false;
        $limitationList = eZContentObjectTreeNode::getLimitationList( $limitation );
        $sqlPermissionChecking = eZContentObjectTreeNode::createPermissionCheckingSQL( $limitationList );

        // Determine whether we should show invisible nodes.
        $showInvisibleNodesCond = eZContentObjectTreeNode::createShowInvisibleSQLString( !$ignoreVisibility );

        $query = "SELECT DISTINCT
                       ezcontentobject.*,
                       ezcontentobject_tree.*,
                       ezcontentclass.serialized_name_list as class_serialized_name_list,
                       ezcontentclass.identifier as class_identifier,
                       ezcontentclass.is_container as is_container
                       $groupBySelectText
                       $versionNameTargets
                       $sortingInfo[attributeTargetSQL]
                   FROM
                      ezcontentobject_tree,
                      ezcontentobject,ezcontentclass
                      $versionNameTables
                      $sortingInfo[attributeFromSQL]
                      $attributeFilter[from]
                      $extendedAttributeFilter[tables]
                      $sqlPermissionChecking[from]
                   WHERE
                      $pathStringCond
                      $extendedAttributeFilter[joins]
                      $sortingInfo[attributeWhereSQL]
                      $attributeFilter[where]
                      ezcontentclass.version=0 AND
                      $notEqParentString
                      ezcontentobject_tree.contentobject_id = ezcontentobject.id  AND
                      ezcontentclass.id = ezcontentobject.contentclass_id AND
                      $mainNodeOnlyCond
                      $classCondition
                      $versionNameJoins
                      $showInvisibleNodesCond
                      $sqlPermissionChecking[where]
                      $objectNameFilterSQL
                      $languageFilter
                $groupByText";

        if ( $sortingInfo['sortingFields'] )
            $query .= " ORDER BY $sortingInfo[sortingFields]";

        $db =& eZDB::instance();

        if ( !$offset && !$limit )
        {
            $nodeListArray = $db->arrayQuery( $query );
        }
        else
        {
            $nodeListArray = $db->arrayQuery( $query, array( 'offset' => $offset,
                                                             'limit'  => $limit ) );
        }

        if ( $asObject )
        {
            $retNodeList = eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );
            if ( $loadDataMap )
                eZContentObject::fillNodeListAttributes( $retNodeList );
        }
        else
        {
            $retNodeList =& $nodeListArray;
        }

        // cleanup temp tables
        $db->dropTempTableList( $sqlPermissionChecking['temp_tables'] );

        return $retNodeList;
    }

    /*!
    Retrieve subtrees from multiple paths.

    This method composes a list of objects retrieved from various node paths,
    sorted by criteria that are globally applied to the whole list.

    It is for example useful for an RSS feed that serves content from
    several node paths. The respective subtrees need to be amalgated and
    the resulting object listed sorted by publishing date to show the latest
    entries in chronological order.

    The first parameter is a multi-dimensional array containing the
    node IDs and filter criteria assigned to each of the nodes:

    array(
        [node_1] => array(
                        'ClassFilterType' =>    [filter_type],
                        'ClassFilterArray'  =>  [filter_array]
                        ),
         [node_2] => array(
                        'ClassFilterType' =>    [filter_type],
                        'ClassFilterArray'  =>  [filter_array]
                        )
         )

    The second parameter is a single-dimensional array with criteria
    applied to the list of objects retrieved from the various subtrees:

    array(
        'SortBy' => [sorting-criteria]
        )
    */
    function subTreeMultiPaths( $nodesParams, $listParams = NULL )
    {
        if( !is_array( $nodesParams ) || !count( $nodesParams ) )
        {
            eZDebug::writeWarning( __CLASS__.'::'.__FUNCTION__.': Nodes parameter must be an array with at least one key.' );
            $retValue = null;
            return $retValue;
        }

        if( is_null( $listParams ) )
        {
            $listParams = array(
                             'SortBy'                   => false,
                             'Offset'                   => false,
                             'Limit'                    => false,
                             'SortBy'                   => false,
                             'GroupBy'                  => false );
        }

        $offset           = ( isset( $listParams['Offset'] ) && is_numeric( $listParams['Offset'] ) ) ? $listParams['Offset']             : false;
        $limit            = ( isset( $listParams['Limit']  ) && is_numeric( $listParams['Limit']  ) ) ? $listParams['Limit']              : false;
        $groupBy          = ( isset( $listParams['GroupBy']                                       ) ) ? $listParams['GroupBy']            : false;
        if ( !isset( $listParams['SortBy'] ) )
        {
            $listParams['SortBy'] = false;
        }
        $sortBy = $listParams['SortBy'];

        $queryNodes = '';

        foreach( $nodesParams as $nodeParams )
        {
            $nodeID = $nodeParams['ParentNodeID'];

            if ( !is_numeric( $nodeID ) && !is_array( $nodeID ) )
            {
                eZDebug::writeWarning( __CLASS__.'::'.__FUNCTION__.': Nodes parameter must be numeric or an array with numeric values.' );
                $retValue = null;
                return $retValue;
            }

            if ( is_null( $nodeParams ) )
            {
                $nodeParams = array(
                                 'Depth'                    => false,
                                 //'OnlyTranslated'           => false,
                                 'Language'                 => false,
                                 'AttributeFilter'          => false,
                                 'ExtendedAttributeFilter'  => false,
                                 'ClassFilterType'          => false,
                                 'ClassFilterArray'         => false );
            }

            //$onlyTranslated   = ( isset( $nodeParams['OnlyTranslated']    ) )                       ? $nodeParams['OnlyTranslated']     : false;
            $language         = ( isset( $nodeParams['Language']          ) )                             ? $nodeParams['Language']           : false;
            $depth            = ( isset( $nodeParams['Depth']  ) && is_numeric( $nodeParams['Depth']  ) ) ? $nodeParams['Depth']              : false;
            $depthOperator    = ( isset( $nodeParams['DepthOperator']     ) )                         ? $nodeParams['DepthOperator']      : false;
            $asObject         = ( isset( $nodeParams['AsObject']          ) )                         ? $nodeParams['AsObject']           : true;
            $mainNodeOnly     = ( isset( $nodeParams['MainNodeOnly']      ) )                         ? $nodeParams['MainNodeOnly']       : false;
            $ignoreVisibility = ( isset( $nodeParams['IgnoreVisibility']  ) )                         ? $nodeParams['IgnoreVisibility']   : false;
            if ( !isset( $nodeParams['ClassFilterType'] ) )
            {
                $nodeParams['ClassFilterType'] = false;
            }

            if ( $language )
            {
                if ( !is_array( $language ) )
                {
                    $language = array( $language );
                }
                eZContentLanguage::setPrioritizedLanguages( $language );
            }

            $sortingInfo             = eZContentObjectTreeNode::createSortingSQLStrings( $sortBy );
            $classCondition          = eZContentObjectTreeNode::createClassFilteringSQLString( $nodeParams['ClassFilterType'], $nodeParams['ClassFilterArray'] );
            $attributeFilter         = eZContentObjectTreeNode::createAttributeFilterSQLStrings( $nodeParams['AttributeFilter'], $sortingInfo );
            $extendedAttributeFilter = eZContentObjectTreeNode::createExtendedAttributeFilterSQLStrings( $nodeParams['ExtendedAttributeFilter'] );
            $mainNodeOnlyCond        = eZContentObjectTreeNode::createMainNodeConditionSQLString( $mainNodeOnly );

            $pathStringCond     = '';
            $notEqParentString  = '';
            // If the node(s) doesn't exist we return null.
            $noNode = null;
            if ( !eZContentObjectTreeNode::createPathConditionAndNotEqParentSQLStrings( $pathStringCond, $notEqParentString, $noNode, $nodeID, $depth, $depthOperator ) )
            {
                $retValue = null;
                return $retValue;
            }

            $useVersionName     = true;
            $versionNameTables  = eZContentObjectTreeNode::createVersionNameTablesSQLString ( $useVersionName );
            $versionNameTargets = eZContentObjectTreeNode::createVersionNameTargetsSQLString( $useVersionName );
            $versionNameJoins   = eZContentObjectTreeNode::createVersionNameJoinsSQLString  ( $useVersionName, false );

            $languageFilter = ' AND ' . eZContentLanguage::languagesSQLFilter( 'ezcontentobject' );

            if ( $language )
            {
                eZContentLanguage::clearPrioritizedLanguages();
            }

            $limitation = ( isset( $nodeParams['Limitation']  ) && is_array( $nodeParams['Limitation']  ) ) ? $nodeParams['Limitation']: false;
            $limitationList = eZContentObjectTreeNode::getLimitationList( $limitation );
            $sqlPermissionChecking = eZContentObjectTreeNode::createPermissionCheckingSQL( $limitationList );

            // Determine whether we should show invisible nodes.
            $showInvisibleNodesCond = eZContentObjectTreeNode::createShowInvisibleSQLString( !$ignoreVisibility );

            $queryNodes .= " (
                          $pathStringCond
                          $extendedAttributeFilter[joins]
                          $sortingInfo[attributeWhereSQL]
                          $attributeFilter[where]
                          ezcontentclass.version=0 AND
                          $notEqParentString
                          ezcontentobject_tree.contentobject_id = ezcontentobject.id  AND
                          ezcontentclass.id = ezcontentobject.contentclass_id AND
                          $mainNodeOnlyCond
                          $classCondition
                          $versionNameJoins
                          $showInvisibleNodesCond
                          $sqlPermissionChecking[where]
                          $languageFilter
                      )
                      OR";
        }

        $groupBySelectText  = '';
        $groupByText        = '';
        eZContentObjectTreeNode::createGroupBySQLStrings( $groupBySelectText, $groupByText, $groupBy );

        $query = "SELECT DISTINCT
                       ezcontentobject.*,
                       ezcontentobject_tree.*,
                       ezcontentclass.serialized_name_list as class_serialized_name_list,
                       ezcontentclass.identifier as class_identifier
                       $groupBySelectText
                       $versionNameTargets
                       $sortingInfo[attributeTargetSQL]
                       , ".$nodeParams['ResultID']." AS resultid
                   FROM
                      ezcontentobject_tree,
                      ezcontentobject,ezcontentclass
                      $versionNameTables
                      $sortingInfo[attributeFromSQL]
                      $attributeFilter[from]
                      $extendedAttributeFilter[tables]
                      $sqlPermissionChecking[from]
                   WHERE
                      ".substr($queryNodes, 0, -2)."
                $groupByText";

        if ( $sortingInfo['sortingFields'] )
        {
            $query .= " ORDER BY $sortingInfo[sortingFields]";
        }

        $db =& eZDB::instance();

        if ( !$offset && !$limit )
        {
            $nodeListArray = $db->arrayQuery( $query );
        }
        else
        {
            $nodeListArray = $db->arrayQuery( $query, array( 'offset' => $offset,
                                                              'limit'  => $limit ) );
        }

        if ( $asObject )
        {
            $retNodeList = eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );
        }
        else
        {
            $retNodeList =& $nodeListArray;
        }

        // cleanup temp tables
        $db->dropTempTableList( $sqlPermissionChecking['temp_tables'] );

        return $retNodeList;
    }

    function subTreeGroupByDateField( $field, $type )
    {
        $divisor = 0;
        switch ( $type )
        {
            case 'year':
            {
                $divisor = 60*60*24*365;
            } break;
            case 'week':
            {
                $divisor = 60*60*24*7;
            } break;
            case 'day':
            {
                $divisor = 60*60*24;
            } break;
            case 'hour':
            {
                $divisor = 60*60;
            } break;
            case 'minute':
            {
                $divisor = 60;
            } break;
            case 'second':
            {
                $divisor = 0;
            } break;
            default:
            {
                eZDebug::writeError( "Unknown field type $type",
                                     'eZContentObjectTreeNode::subTreeGroupByDateField' );
            }
        }
        if ( $divisor > 0 )
            $text = "( $field / $divisor ) AS groupbyfield";
        else
            $text = "$field AS groupbyfield";
        return array( 'select' => $text,
                      'group_field' => "( $field / $divisor )" );
    }

    /*!
     Count number of subnodes

     \param params array
    */
    function &subTreeCount( $params = array(), $nodeID = 0 )
    {
        $language = ( isset( $params['Language'] ) ) ? $params['Language'] : false;

        if ( $language )
        {
            if ( !is_array( $language ) )
            {
                $language = array( $language );
            }
            eZContentLanguage::setPrioritizedLanguages( $language );
        }

        if ( !is_numeric( $nodeID ) and !is_array( $nodeID ) )
        {
            $retVal = 0;
            return $retVal;
        }

        $depth = false;
        if ( isset( $params['Depth'] ) && is_numeric( $params['Depth'] ) )
        {
            $depth = $params['Depth'];

        }

        //$nodePath = $node->attribute( 'path_string' );
        //$fromNode = $node->attribute( 'node_id');
        //$childrensPath = $nodePath ;
        $db =& eZDB::instance();

//        $pathString = " path_string like '$childrensPath%' AND ";

        $pathStringCond = '';
        if ( is_array( $nodeID ) )
        {
            $nodeIDList = $nodeID;
            $nodeList = array();
            $sqlPartForOneNodeList = array();
            foreach ( $nodeIDList as $nodeID )
            {
                $node = eZContentObjectTreeNode::fetch( $nodeID, false, false );
                // If the node doesn't exist we return null.
                if ( !is_array( $node ) )
                {
                    $retVal = null;
                    return $retVal;
                }

                $nodePath = $node['path_string'];
                $nodeDepth = $node['depth'];
                $childrensPath = $nodePath ;
                $pathString = " ezcontentobject_tree.path_string like '$childrensPath%' ";
                if ( isset( $params[ 'Depth' ] ) and $params[ 'Depth' ] > 0 )
                {
                    $nodeDepth += $params[ 'Depth' ];
                    $depthCond = ' and ezcontentobject_tree.depth = ' . $nodeDepth . ' ';
                }
                else
                {
                    $depthCond = '';
                }

                $notEqParentString = " and ezcontentobject_tree.node_id != $nodeID ";

                $sqlPartForOneNodeList[] = " ( ezcontentobject_tree.path_string like '$childrensPath%'   $depthCond $notEqParentString ) ";
                $notEqParentString = '';
            }
            $pathStringCond = implode( ' or ', $sqlPartForOneNodeList );
            $pathStringCond = ' (' . $pathStringCond . ') and';
        }
        else
        {
            $nodePath = null;
            $nodeDepth = 0;

            if ( $nodeID == 0 )
            {
                $nodeID = $this->attribute( 'node_id' );
                $node = $this;
                $nodePath = $node->attribute( 'path_string' );
                $nodeDepth = $node->attribute( 'depth' );
            }
            else if ( is_numeric( $nodeID ) )
            {
                $node = eZContentObjectTreeNode::fetch( $nodeID, false, false );
                // If the node doesn't exist we return null.
                if ( !is_array( $node ) )
                {
                    $retVal = 0;
                    return $retVal;
                }
                $nodePath = $node['path_string'];
                $nodeDepth = $node['depth'];
            }

            $fromNode = $nodeID;
            $childrensPath = $nodePath;
            $pathLength = strlen( $childrensPath );

            $db =& eZDB::instance();
            $subStringString = $db->subString( 'path_string', 1, $pathLength );
            $pathString = " ezcontentobject_tree.path_string like '$childrensPath%' and ";

            $notEqParentString = "ezcontentobject_tree.node_id != $fromNode AND";
            $depthCond = '';
            if ( $depth )
            {
                $nodeDepth += $params[ 'Depth' ];
                if ( isset( $params[ 'DepthOperator' ] ) && $params[ 'DepthOperator' ] == 'eq' )
                {
                    $depthCond = ' ezcontentobject_tree.depth = ' . $nodeDepth . ' and ';
                    $notEqParentString = '';
                }
                else
                    $depthCond = ' ezcontentobject_tree.depth <= ' . $nodeDepth . ' and ';
            }

            $pathStringCond = $pathString . $depthCond;
        }

        $pathString = $pathStringCond;

        //$nodeDepth = $node->attribute( 'depth' );
        $depthCond = '';

        // $notEqParentString = "node_id != $fromNode AND";

        $limitation = ( isset( $params['Limitation']  ) && is_array( $params['Limitation']  ) ) ? $params['Limitation']: false;
        $limitationList = eZContentObjectTreeNode::getLimitationList( $limitation );

        $ini =& eZINI::instance();

        // Check for class filtering
        $classCondition = '';

        if ( isset( $params['ClassFilterType'] ) and isset( $params['ClassFilterArray'] ) and
             ( $params['ClassFilterType'] == 'include' or $params['ClassFilterType'] == 'exclude' )
             and count( $params['ClassFilterArray'] ) > 0 )
        {
            $classCondition = '  ';
            $i = 0;
            $classCount = count( $params['ClassFilterArray'] );
            $classIDArray = array();
            foreach ( $params['ClassFilterArray'] as $classID )
            {
                $originalClassID = $classID;
                // Check if classes are recerenced by identifier
                if ( is_string( $classID ) && !is_numeric( $classID ) )
                {
                    $classID = eZContentObjectTreeNode::classIDByIdentifier( $classID );
                }
                if ( is_numeric( $classID ) )
                {
                    $classIDArray[] = $classID;
                }
                else
                {
                    eZDebugSetting::writeWarning( 'kernel-content-class', "Invalid class identifier in subTree() classfilterarray, classID : " . $originalClassID );
                }
            }
            if ( count( $classIDArray ) > 0  )
            {
                $classCondition .= " ezcontentobject.contentclass_id ";
                if ( $params['ClassFilterType'] == 'include' )
                    $classCondition .= " IN ";
                else
                    $classCondition .= " NOT IN ";

                $classIDString =  implode( ', ', $classIDArray );
                $classCondition .= ' ( ' . $classIDString . ' ) AND';
            }
        }


        // Main node check
        $mainNodeOnlyCond = '';
        if ( isset( $params['MainNodeOnly'] ) && $params['MainNodeOnly'] === true )
        {
            $mainNodeOnlyCond = 'ezcontentobject_tree.node_id = ezcontentobject_tree.main_node_id AND';
        }

        // Attribute filtering
        // Check for attribute filtering
        $attributeFilterFromSQL = "";
        $attributeFilterWhereSQL = "";

        $totalAttributesFiltersCount = 0;
        $invalidAttributesFiltersCount = 0;

        if ( isset( $params['AttributeFilter'] ) )
        {
            $filterArray = $params['AttributeFilter'];

            // Check if first value of array is a string.
            // To check for and/or filtering
            $filterJoinType = 'AND';
            if ( is_string( $filterArray[0] ) )
            {
                if ( strtolower( $filterArray[0] ) == 'or' )
                {
                    $filterJoinType = 'OR';
                }
                else if ( strtolower( $filterArray[0] ) == 'and' )
                {
                    $filterJoinType = 'AND';
                }
                unset( $filterArray[0] );
            }

            $attibuteFilterJoinSQL = "";
            $filterCount = 0;

            if ( is_array( $filterArray ) )
            {
                // Handle attribute filters and generate SQL
                $totalAttributesFiltersCount = count( $filterArray );

                foreach ( $filterArray as $filter )
                {
                    $isFilterValid = true; // by default assumes that filter is valid

                    $filterAttributeID = $filter[0];
                    $filterType = $filter[1];
                    $filterValue = is_array( $filter[2] ) ? '' : $db->escapeString( $filter[2] );

                    $useAttributeFilter = false;
                    switch ( $filterAttributeID )
                    {
                        case 'path':
                        {
                            $filterField = 'path_string';
                        } break;
                        case 'published':
                        {
                            $filterField = 'ezcontentobject.published';
                        } break;
                        case 'modified':
                        {
                            $filterField = 'ezcontentobject.modified';
                        } break;
                        case 'modified_subnode':
                        {
                            $filterField = 'modified_subnode';
                        } break;
                        case 'section':
                        {
                            $filterField = 'ezcontentobject.section_id';
                        } break;
                        case 'depth':
                        {
                            $filterField = 'depth';
                        } break;
                        case 'class_identifier':
                        {
                            $filterField = 'ezcontentclass.identifier';
                        } break;
                        case 'class_name':
                        {
                            $classNameFilter = eZContentClassName::sqlFilter();
                            $filterField .= $classNameFilter['nameField'];
                            $attributeFromSQL .= ", $classNameFilter[from]";
                            $attributeWhereSQL .= "$classNameFilter[where] AND ";
                        } break;
                        case 'priority':
                        {
                            $filterField = 'ezcontentobject_tree.priority';
                        } break;
                        case 'name':
                        {
                            $filterField = 'ezcontentobject_name.name';
                        } break;
                        case 'owner':
                        {
                            $filterField = 'ezcontentobject.owner_id';
                        } break;
                        default:
                        {
                            $useAttributeFilter = true;
                        } break;
                    }

                    if ( $useAttributeFilter )
                    {
                        if ( !is_numeric( $filterAttributeID ) )
                            $filterAttributeID = eZContentObjectTreeNode::classAttributeIDByIdentifier( $filterAttributeID );

                        if ( $filterAttributeID === false )
                        {
                            $isFilterValid = false;
                            if( $filterJoinType === 'AND' )
                            {
                                // go out
                                $invalidAttributesFiltersCount = $totalAttributesFiltersCount;
                                break;
                            }

                            // check next filter
                            ++$invalidAttributesFiltersCount;
                        }
                        else
                        {
                            // Check datatype for filtering
                            $filterDataType = eZContentObjectTreeNode::sortKeyByClassAttributeID( $filterAttributeID );
                            if ( $filterDataType === false )
                            {
                                $isFilterValid = false;
                                if( $filterJoinType === 'AND' )
                                {
                                    // go out
                                    $invalidAttributesFiltersCount = $totalAttributesFiltersCount;
                                    break;
                                }

                                // check next filter
                                ++$invalidAttributesFiltersCount;
                            }
                            else
                            {
                                $sortKey = false;
                                if ( $filterDataType == 'string' )
                                {
                                    $sortKey = 'sort_key_string';
                                }
                                else
                                {
                                    $sortKey = 'sort_key_int';
                                }

                                $filterField = "a$filterCount.$sortKey";

                                // Use the same joins as we do when sorting,
                                // if more attributes are filtered by we will append them
                                $attributeFilterFromSQL .= ", ezcontentobject_attribute a$filterCount ";
                                $attributeFilterWhereSQL .= "
                                    a$filterCount.contentobject_id = ezcontentobject.id AND
                                       a$filterCount.version = ezcontentobject.current_version AND
                                       a$filterCount.contentclassattribute_id = $filterAttributeID AND ";
                                $attributeFilterWhereSQL .= eZContentLanguage::sqlFilter( "a$filterCount", 'ezcontentobject' );
                                $attributeFilterWhereSQL .= ' AND ';
                            }

                        }
                    }

                    if ( $isFilterValid )
                    {
                        $hasFilterOperator = true;
                        // Controls quotes around filter value, some filters do this manually
                        $noQuotes = false;
                        // Controls if $filterValue or $folder[2] is used, $filterValue is already escaped
                        $unEscape = false;

                        switch ( $filterType )
                        {
                            case '=' :
                            {
                                $filterOperator = '=';
                            }break;

                            case '!=' :
                            {
                                $filterOperator = '<>';
                            }break;

                            case '>' :
                            {
                                $filterOperator = '>';
                            }break;

                            case '<' :
                            {
                                $filterOperator = '<';
                            }break;

                            case '<=' :
                            {
                                $filterOperator = '<=';
                            }break;

                            case '>=' :
                            {
                                $filterOperator = '>=';
                            }break;

                            case 'like':
                            case 'not_like':
                            {
                                $filterOperator = ( $filterType == 'like' ? 'LIKE' : 'NOT LIKE' );
                                // We escape the string ourselves, this MUST be done before wildcard replace
                                $filter[2] = $db->escapeString( $filter[2] );
                                $unEscape = true;
                                // Since * is used as wildcard we need to transform the string to
                                // use % as wildcard. The following rules apply:
                                // - % -> \%
                                // - * -> %
                                // - \* -> *
                                // - \\ -> \

                                $filter[2] = preg_replace( array( '#%#m',
                                                                  '#(?<!\\\\)\\*#m',
                                                                  '#(?<!\\\\)\\\\\\*#m',
                                                                  '#\\\\\\\\#m' ),
                                                           array( '\\%',
                                                                  '%',
                                                                  '*',
                                                                  '\\\\' ),
                                                           $filter[2] );
                            } break;

                            case 'in':
                            case 'not_in' :
                            {
                                $filterOperator = ( $filterType == 'in' ? 'IN' : 'NOT IN' );
                                // Turn off quotes for value, we do this ourselves
                                $noQuotes = true;
                                if ( is_array( $filter[2] ) )
                                {
                                    reset( $filter[2] );
                                    while ( list( $key, $value ) = each( $filter[2] ) )
                                    {
                                        // Non-numerics must be escaped to avoid SQL injection
                                        $filter[2][$key] = is_numeric( $value ) ? $value : "'" . $db->escapeString( $value ) . "'";
                                    }
                                    $filterValue = '(' .  implode( ",", $filter[2] ) . ')';
                                }
                                else
                                {
                                    $hasFilterOperator = false;
                                }
                            } break;

                            case 'between':
                            case 'not_between' :
                            {
                                $filterOperator = ( $filterType == 'between' ? 'BETWEEN' : 'NOT BETWEEN' );
                                // Turn off quotes for value, we do this ourselves
                                $noQuotes = true;
                                if ( is_array( $filter[2] ) )
                                {
                                    // Check for non-numerics to avoid SQL injection
                                    if ( !is_numeric( $filter[2][0] ) )
                                        $filter[2][0] = "'" . $db->escapeString( $filter[2][0] ) . "'";
                                    if ( !is_numeric( $filter[2][1] ) )
                                        $filter[2][1] = "'" . $db->escapeString( $filter[2][1] ) . "'";

                                    $filterValue = $filter[2][0] . ' AND ' . $filter[2][1];
                                }
                            } break;

                            default :
                            {
                                $hasFilterOperator = false;
                                eZDebug::writeError( "Unknown attribute filter type: $filterType", "eZContentObjectTreeNode::subTree()" );
                            }break;

                        }
                        if ( $hasFilterOperator )
                        {
                            if ( $filterCount > 0 )
                                $attibuteFilterJoinSQL .= " $filterJoinType ";

                            // If $unEscape is true we get the filter value from the 2nd element instead
                            // which must have been escaped by filter type
                            $filterValue = $unEscape ? $filter[2] : $filterValue;

                            $attibuteFilterJoinSQL .= "$filterField $filterOperator ";
                            $attibuteFilterJoinSQL .= $noQuotes ? "$filterValue " : "'$filterValue' ";

                            $filterCount++;
                        }
                    }
                } // end of 'foreach ( $filterArray as $filter )'

                if ( $totalAttributesFiltersCount == $invalidAttributesFiltersCount )
                {
                    $attributeFilterFromSQL = "";
                    $attributeFilterWhereSQL = "";

                    eZDebug::writeNotice( "Attribute filter returned false" );
                    $retVal = 0;
                    return $retVal;
                }
                else
                {
                    if ( $filterCount > 0 )
                        $attributeFilterWhereSQL .= "\n                            ( " . $attibuteFilterJoinSQL . " ) AND ";
                }
            } // end of 'if ( is_array( $filterArray ) )'
        }

        //$onlyTranslated   = ( isset( $params['OnlyTranslated'] ) ) ? $params['OnlyTranslated']     : false;

        $useVersionName     = true;

        $versionNameTables  = eZContentObjectTreeNode::createVersionNameTablesSQLString ( $useVersionName );
        $versionNameTargets = eZContentObjectTreeNode::createVersionNameTargetsSQLString( $useVersionName );
        $versionNameJoins   = eZContentObjectTreeNode::createVersionNameJoinsSQLString  ( $useVersionName, false );

        $languageFilter = ' AND '.eZContentLanguage::languagesSQLFilter( 'ezcontentobject' );

        if ( $language )
        {
            eZContentLanguage::clearPrioritizedLanguages();
        }
        $objectNameFilter = ( isset( $params['ObjectNameFilter']  ) ) ? $params['ObjectNameFilter']   : false;
        $objectNameFilterSQL = eZContentObjectTreeNode::createObjectNameFilterConditionSQLString( $objectNameFilter );

        $extendedAttributeFilter = eZContentObjectTreeNode::createExtendedAttributeFilterSQLStrings( $params['ExtendedAttributeFilter'] );

        // Determine whether we should show invisible nodes.
        $ignoreVisibility = isset( $params['IgnoreVisibility'] ) ? $params['IgnoreVisibility'] : false;
        $showInvisibleNodesCond = eZContentObjectTreeNode::createShowInvisibleSQLString( !$ignoreVisibility );

        $sqlPermissionCheckingFrom = '';
        $sqlPermissionCheckingWhere = '';
        $sqlPermissionTempTables = array();
        $groupPermTempTable = false;

        if ( $limitationList !== false && count( $limitationList ) > 0 )
        {
            $sqlParts = array();

            foreach( $limitationList as $limitationArray )
            {
                $sqlPartPart = array();
                $sqlPartPartPart = array();

                foreach ( array_keys( $limitationArray ) as $ident )
                {
                    switch( $ident )
                    {
                        case 'Class':
                        {
                            $sqlPartPart[] = 'ezcontentobject.contentclass_id in (' . implode( ', ', $limitationArray[$ident] ) . ')';
                        } break;

                        case 'Section':
                        case 'User_Section':
                        {
                            $sqlPartPart[] = 'ezcontentobject.section_id in (' . implode( ', ', $limitationArray[$ident] ) . ')';
                        } break;

                        case 'Owner':
                        {
                            $user =& eZUser::currentUser();
                            $userID = $user->attribute( 'contentobject_id' );
                            $sqlPartPart[] = "ezcontentobject.owner_id = '" . $db->escapeString( $userID ) . "'";
                        } break;

                        case 'Group':
                        {
                            if ( !$groupPermTempTable )
                            {
                                $user   =& eZUser::currentUser();
                                $userContentObject =& $user->attribute( 'contentobject' );
                                $parentList =& $userContentObject->attribute( 'parent_nodes' );

                                $groupPermTempTable = $db->generateUniqueTempTableName( 'ezgroup_perm_tmp_%' );
                                $sqlPermissionTempTables[] = $groupPermTempTable;

                                $db->createTempTable( "CREATE TEMPORARY TABLE $groupPermTempTable ( user_id int )" );
                                $db->query( "INSERT INTO $groupPermTempTable
                                                    SELECT DISTINCT contentobject_id AS user_id
                                                    FROM     ezcontentobject_tree
                                                    WHERE    parent_node_id IN ("  . implode( ', ', $parentList ) . ')' );

                                $sqlPermissionCheckingFrom = ', ' . $groupPermTempTable;
                            }
                            $sqlPartPart[] = "ezcontentobject.owner_id = $groupPermTempTable.user_id";
                        } break;

                        case 'Node':
                        {
                            $sqlPartPartPart[] = 'ezcontentobject_tree.node_id in (' . implode( ', ', $limitationArray[$ident] ) . ')';
                        } break;

                        case 'Subtree':
                        {
                            $pathArray =& $limitationArray[$ident];
                            foreach ( $pathArray as $limitationPathString )
                            {
                                $sqlPartPartPart[] = "ezcontentobject_tree.path_string like '$limitationPathString%'";
                            }
                        } break;

                        case 'User_Subtree':
                        {
                            $pathArray =& $limitationArray[$ident];
                            $sqlPartUserSubtree = array();
                            foreach ( $pathArray as $limitationPathString )
                            {
                                $sqlPartUserSubtree[] = "ezcontentobject_tree.path_string like '$limitationPathString%'";
                            }
                            $sqlPartPart[] = implode( ' OR ', $sqlPartUserSubtree );
                        }
                    }
                }
                if ( $sqlPartPartPart )
                {
                    $sqlPartPart[] = '( ' . implode( ' ) OR ( ', $sqlPartPartPart ). ' )';
                }
                $sqlParts[] = implode( ' AND ', $sqlPartPart );
            }

            $sqlPermissionCheckingWhere = ' AND ((' . implode( ') or (', $sqlParts ) . ')) ';
            $sqlPermissionChecking = array( 'from' => $sqlPermissionCheckingFrom,
                                            'where' => $sqlPermissionCheckingWhere );

            $query = "SELECT
                            count( DISTINCT ezcontentobject_tree.node_id ) as count
                      FROM
                           ezcontentobject_tree,
                           ezcontentobject,ezcontentclass
                           $versionNameTables
                           $attributeFilterFromSQL
                           $extendedAttributeFilter[tables]
                           $sqlPermissionChecking[from]
                      WHERE $pathString
                            $extendedAttributeFilter[joins]
                            $depthCond
                            $mainNodeOnlyCond
                            $classCondition
                            $attributeFilterWhereSQL
                            ezcontentclass.version=0 AND
                            $notEqParentString
                            ezcontentobject_tree.contentobject_id = ezcontentobject.id  AND
                            ezcontentclass.id = ezcontentobject.contentclass_id AND
                            $versionNameJoins
                            $showInvisibleNodesCond
                            $sqlPermissionChecking[where]
                            $objectNameFilterSQL
                            $languageFilter ";

        }
        else
        {
            $query="SELECT
                          count( DISTINCT ezcontentobject_tree.node_id ) as count
                    FROM
                          ezcontentobject_tree,
                          ezcontentobject,
                          ezcontentclass
                          $versionNameTables
                          $attributeFilterFromSQL
                          $extendedAttributeFilter[tables]
                    WHERE
                           $pathString
                           $extendedAttributeFilter[joins]
                           $depthCond
                           $mainNodeOnlyCond
                           $classCondition
                           $attributeFilterWhereSQL
                           ezcontentclass.version=0 AND
                           $notEqParentString
                           ezcontentobject_tree.contentobject_id = ezcontentobject.id AND
                           ezcontentclass.id = ezcontentobject.contentclass_id AND
                           $versionNameJoins
                           $showInvisibleNodesCond
                           $objectNameFilterSQL
                           $languageFilter ";
        }

        $nodeListArray = $db->arrayQuery( $query );

        // cleanup temp tables
        $db->dropTempTableList( $sqlPermissionTempTables );

        return $nodeListArray[0]['count'];
    }

    /*!
      \return The date/time list when object were published
    */
    function calendar( $params = false, $nodeID = 0 )
    {
        if ( !is_numeric( $nodeID ) and !is_array( $nodeID ) )
        {
            return array();
        }

        if ( $params === false )
        {
            $params = array( 'Depth'                    => false,
                             'Offset'                   => false,
                             'Limit'                    => false,
                             'AttributeFilter'          => false,
                             'ExtendedAttributeFilter'  => false,
                             'ClassFilterType'          => false,
                             'ClassFilterArray'         => false,
                             'GroupBy'                  => false );
        }

        $offset           = ( isset( $params['Offset'] ) && is_numeric( $params['Offset'] ) ) ? $params['Offset']             : false;
        $limit            = ( isset( $params['Limit']  ) && is_numeric( $params['Limit']  ) ) ? $params['Limit']              : false;
        $depth            = ( isset( $params['Depth']  ) && is_numeric( $params['Depth']  ) ) ? $params['Depth']              : false;
        $depthOperator    = ( isset( $params['DepthOperator']     ) )                         ? $params['DepthOperator']      : false;
        $groupBy          = ( isset( $params['GroupBy']           ) )                         ? $params['GroupBy']            : false;
        $mainNodeOnly     = ( isset( $params['MainNodeOnly']      ) )                         ? $params['MainNodeOnly']       : false;
        $ignoreVisibility = ( isset( $params['IgnoreVisibility']  ) )                         ? $params['IgnoreVisibility']   : false;
        if ( !isset( $params['ClassFilterType'] ) )
            $params['ClassFilterType'] = false;

        $classCondition          = eZContentObjectTreeNode::createClassFilteringSQLString( $params['ClassFilterType'], $params['ClassFilterArray'] );
        $attributeFilter         = eZContentObjectTreeNode::createAttributeFilterSQLStrings( $params['AttributeFilter'], $sortingInfo );
        $extendedAttributeFilter = eZContentObjectTreeNode::createExtendedAttributeFilterSQLStrings( $params['ExtendedAttributeFilter'] );
        $mainNodeOnlyCond        = eZContentObjectTreeNode::createMainNodeConditionSQLString( $mainNodeOnly );

        $pathStringCond     = '';
        $notEqParentString  = '';
        eZContentObjectTreeNode::createPathConditionAndNotEqParentSQLStrings( $pathStringCond, $notEqParentString, $this, $nodeID, $depth, $depthOperator );

        $groupBySelectText  = '';
        $groupByText        = '';
        eZContentObjectTreeNode::createGroupBySQLStrings( $groupBySelectText, $groupByText, $groupBy );

        $limitation = ( isset( $params['Limitation']  ) && is_array( $params['Limitation']  ) ) ? $params['Limitation']: false;
        $limitationList = eZContentObjectTreeNode::getLimitationList( $limitation );
        $sqlPermissionChecking = eZContentObjectTreeNode::createPermissionCheckingSQL( $limitationList );

        // Determine whether we should show invisible nodes.
        $showInvisibleNodesCond = eZContentObjectTreeNode::createShowInvisibleSQLString( !$ignoreVisibility );

        $query = "SELECT DISTINCT
                         ezcontentobject.published as published
                         $groupBySelectText
                   FROM
                      ezcontentobject_tree,
                      ezcontentobject,ezcontentclass
                      $attributeFilter[from]
                      $extendedAttributeFilter[tables]
                      $sqlPermissionChecking[from]
                   WHERE
                      $pathStringCond
                      $extendedAttributeFilter[joins]
                      $attributeFilter[where]
                      ezcontentclass.version=0
                      AND
                      $notEqParentString
                      $mainNodeOnlyCond
                      $classCondition
                      ezcontentobject_tree.contentobject_id = ezcontentobject.id  AND
                      ezcontentclass.id = ezcontentobject.contentclass_id
                      $showInvisibleNodesCond
                      $sqlPermissionChecking[where]
                $groupByText ";


        $db =& eZDB::instance();

        if ( !$offset && !$limit )
        {
            $nodeListArray = $db->arrayQuery( $query );
        }
        else
        {
            $nodeListArray = $db->arrayQuery( $query, array( 'offset' => $offset,
                                                              'limit'  => $limit ) );
        }

        $retNodeList =& $nodeListArray;

        // cleanup temp tables
        $db->dropTempTableList( $sqlPermissionChecking['temp_tables'] );

        return $retNodeList;

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

        $name = $db->escapeString( $name );
        $query = "SELECT ezcontentobject.*,
                             ezcontentobject_tree.*,
                             ezcontentclass.serialized_name_list as class_serialized_name_list,
                             ezcontentclass.identifier as class_identifier,
                             ezcontentclass.is_container as is_container
                      FROM
                            ezcontentobject_tree,
                            ezcontentobject,ezcontentclass
                      WHERE $pathString
                            $depthCond
                            ezcontentobject.name = '$name' AND
                            ezcontentclass.version=0 AND
                            node_id != $fromNode AND
                            ezcontentobject_tree.contentobject_id = ezcontentobject.id  AND
                            ezcontentclass.id = ezcontentobject.contentclass_id";

        $nodeListArray = $db->arrayQuery( $query );

        $retNodeList = eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );

        return $retNodeList;
    }

    /*!
     Returns the first level children in sorted order.
    */
    function &children()
    {
        $children =& $this->subTree( array( 'Depth' => 1,
                                            'DepthOperator' => 'eq',
                                            'SortBy' => $this->sortArray() ) );
        return $children;
    }

    /*!
     Returns the number of children for the current node.
     \params $checkPolicies If \c true it will only include nodes which can be read using the current policies,
                            if \c false all nodes are included in count.
    */
    function &childrenCount( $checkPolicies = true )
    {
        $params = array( 'Depth' => 1,
                         'DepthOperator' => 'eq' );
        if ( !$checkPolicies )
            $params['Limitation'] = array();
        $subTreeCount = $this->subTreeCount( $params );
        return $subTreeCount;
    }

    /*!
     Get amount views of content node.
    */
    function &viewCount()
    {
        include_once( 'kernel/classes/ezviewcounter.php' );
        $count = eZViewCounter::fetch( $this->attribute( 'node_id' ), false );
        $count = (int) $count['count'];
        return $count;
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
            case 9:
                return 'name';
            case 10:
                return 'modified_subnode';
        }
    }

    /*!
     \return the field name for the sort order number \a $sortOrder.
             Gives a warning if the number is unknown and return \c 'path'.
    */
    function sortFieldID( $sortFieldName )
    {
        switch ( $sortFieldName )
        {
            default:
                eZDebug::writeWarning( 'Unknown sort order: ' . $sortFieldName, 'eZContentObjectTreeNode::sortFieldID()' );
            case 'path':
                return 1;
            case 'published':
                return 2;
            case 'modified':
                return 3;
            case 'section':
                return 4;
            case 'depth':
                return 5;
            case 'class_identifier':
                return 6;
            case 'class_name':
                return 7;
            case 'priority':
                return 8;
            case 'name':
                return 9;
            case 'modified_subnode':
                return 10;
        }
    }

    /*!
     \return an array which defines the sorting method for this node.
     The array will contain one element which is an array with sort field
     and sort order.
    */
    function &sortArray()
    {
        $retVal = eZContentObjectTreeNode::sortArrayBySortFieldAndSortOrder( $this->attribute( 'sort_field' ),
                                                                             $this->attribute( 'sort_order' ) );
        return $retVal;
    }

    /*!
     \static
     \return an array which defines the sorting method for this node.
     The array will contain one element which is an array with sort field
     and sort order.
    */
    function sortArrayBySortFieldAndSortOrder( $sortField, $sortOrder )
    {
        return array( array( eZContentObjectTreeNode::sortFieldName( $sortField ),
                              $sortOrder ) );
    }

    /*!
     Will assign a section to the current node and all child objects.
     Only main node assignments will be updated.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function assignSectionToSubTree( $nodeID, $sectionID, $oldSectionID = false )
    {
        $db =& eZDB::instance();

        $node = eZContentObjectTreeNode::fetch( $nodeID );
        $nodePath =  $node->attribute( 'path_string' );

        $sectionID =(int) $sectionID;
//        $subStringString = $db->subString( 'path_string', 1, strlen( $nodePath ) );

        $pathString = " path_string like '$nodePath%' AND ";

        // fetch the object id's which needs to be updated
        $objectIDArray = $db->arrayQuery( "SELECT
                                                   ezcontentobject.id
                                            FROM
                                                   ezcontentobject_tree, ezcontentobject
                                            WHERE
                                                  $pathString
                                                  ezcontentobject_tree.contentobject_id=ezcontentobject.id AND
                                                  ezcontentobject_tree.main_node_id=ezcontentobject_tree.node_id" );
        if ( count( $objectIDArray ) == 0 )
            return;

        // Who assigns which section at which node should be logged.
        include_once( 'kernel/classes/ezsection.php' );
        $section = eZSection::fetch( $sectionID );
        $object = $node->object();
        include_once( "kernel/classes/ezaudit.php" );
        eZAudit::writeAudit( 'section-assign', array( 'Section ID' => $sectionID, 'Section name' => $section->attribute( 'name' ),
                                                      'Node ID' => $nodeID,
                                                      'Content object ID' => $object->attribute( 'id' ),
                                                      'Content object name' => $object->attribute( 'name' ),
                                                      'Comment' => 'Assigned a section to the current node and all child objects: eZContentObjectTreeNode::assignSectionToSubTree()' ) );

        $objectSimpleIDArray = array();
        $inSQL   = array();
        $inSQLs  = array();
        $counter = 0;
        foreach ( $objectIDArray as $objectID )
        {
            $objectSimpleIDArray[] = $objectID['id'];
            if ( $counter < 99 )
            {
                $inSQL[] = $objectID['id'];
                ++$counter;
            }
            else
            {
                $inSQL[]  = $objectID['id'];
                $counter  = 0;
                $inSQLs[] = $inSQL;
                $inSQL    = array();
            }
        }
        $inSQLs[] = $inSQL;

        $filterPart = '';
        if ( $oldSectionID !== false )
        {
            $oldSectionID =(int) $oldSectionID;
            $filterPart = " section_id = '$oldSectionID' and ";
        }

        $sets = count( $inSQLs );

        $db->begin();
        for ( $i = 0; $i < $sets; ++$i )
        {
            $inPart = implode( ',', $inSQLs[$i] );
            $db->query( "UPDATE ezcontentobject SET section_id='$sectionID' WHERE $filterPart id IN ( $inPart )" );
            $db->query( "UPDATE ezsearch_object_word_link SET section_id='$sectionID' WHERE $filterPart contentobject_id IN ( $inPart )" );
        }
        $db->commit();

        // clear caches for updated objects
        eZContentObject::clearCache( $objectSimpleIDArray );
    }

    /*!
     \static
     Updates the main node selection for the content object \a $objectID.

     \param $mainNodeID The ID of the node that should be that main node
     \param $objectID The ID of the object that all nodes belong to
     \param $version The version of the object to update node assignments, use \c false for currently published version.
     \param $parentMainNodeID The ID of the parent node of the new main placement

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function updateMainNodeID( $mainNodeID, $objectID, $version = false, $parentMainNodeID )
    {
        $mainNodeID = (int)$mainNodeID;
        $parentMainNodeID = (int)$parentMainNodeID;
        $objectID = (int)$objectID;
        $version = (int)$version;

        $db =& eZDB::instance();
        $db->begin();
        $db->query( "UPDATE ezcontentobject_tree SET main_node_id=$mainNodeID WHERE contentobject_id=$objectID" );
        if ( !$version )
        {
            $rows = $db->arrayQuery( "SELECT current_version FROM ezcontentobject WHERE id=$objectID" );
            $version = $rows[0]['current_version'];
        }
        $db->query( "UPDATE eznode_assignment SET is_main=1 WHERE contentobject_id=$objectID AND contentobject_version=$version AND parent_node=$parentMainNodeID" );
        $db->query( "UPDATE eznode_assignment SET is_main=0 WHERE contentobject_id=$objectID AND contentobject_version=$version AND parent_node!=$parentMainNodeID" );

        $contentObject = eZContentObject::fetch( $objectID );
        $parentContentObject = eZContentObject::fetchByNodeID( $parentMainNodeID );
        if ( $contentObject->attribute( 'section_id' ) != $parentContentObject->attribute( 'section_id' ) )
        {
            $newSectionID = $parentContentObject->attribute( 'section_id' );
            eZContentObjectTreeNode::assignSectionToSubTree( $mainNodeID, $newSectionID );
        }

        $db->commit();

    }

    function &fetchByCRC( $pathStr )
    {
        eZDebug::writeWarning( "Obsolete: use ezurlalias instead", 'eZContentObjectTreeNode::fetchByCRC' );
        $retValue = null;
        return $retValue;
    }

    function fetchByContentObjectID( $contentObjectID, $asObject = true, $contentObjectVersion = false )
    {
        $conds = array( 'contentobject_id' => $contentObjectID );
        if ( $contentObjectVersion !== false )
        {
            $conds['contentobject_version'] = $contentObjectVersion;
        }
        return eZPersistentObject::fetchObjectList( eZContentObjectTreeNode::definition(),
                                                    null,
                                                    $conds,
                                                    null,
                                                    null,
                                                    $asObject );
    }

    function fetchByRemoteID( $remoteID, $asObject = true )
    {
        return eZContentObjectTreeNode::fetch( false, false, $asObject, array( "remote_id" => $remoteID ) );
    }

    function fetchByPath( $pathString, $asObject = true )
    {
        return eZContentObjectTreeNode::fetch( false, false, $asObject, array( "path_string" => $pathString ) );
    }

    function fetchByURLPath( $pathString, $asObject = true )
    {
        if ( $pathString == "" )
        {
            eZDebug::writeWarning( 'Can not fetch, given URLPath is empty', 'eZContentObjectTreeNode::fetchByURLPath' );
            return null;
        }

        return eZContentObjectTreeNode::fetch( false, false, $asObject, array( "path_identification_string" => $pathString ) );
    }

    function fetchAliasesFromNodeList( $nodeList )
    {
        if ( !is_array( $nodeList ) || count( $nodeList ) < 1 )
            return array();

        $db =& eZDB::instance();
        $nodeIDs = $db->implodeWithTypeCast( ', ', $nodeList, 'int' );
        $query = "SELECT path_identification_string FROM ezcontentobject_tree WHERE node_id IN ( $nodeIDs )";
        $pathListArray = $db->arrayQuery( $query );
        return $pathListArray;
    }

    function &findMainNode( $objectID, $asObject = false )
    {
        $objectID=(int) $objectID;
        $query="SELECT ezcontentobject.*,
                           ezcontentobject_tree.*,
                           ezcontentclass.serialized_name_list as class_serialized_name_list,
                           ezcontentclass.identifier as class_identifier,
                           ezcontentclass.is_container as is_container
                    FROM ezcontentobject_tree,
                         ezcontentobject,
                         ezcontentclass
                    WHERE ezcontentobject_tree.contentobject_id=$objectID AND
                          ezcontentobject_tree.main_node_id = ezcontentobject_tree.node_id AND
                          ezcontentobject_tree.contentobject_id=ezcontentobject.id AND
                          ezcontentclass.version=0  AND
                          ezcontentclass.id = ezcontentobject.contentclass_id";
        $db =& eZDB::instance();
        $nodeListArray = $db->arrayQuery( $query );
        if ( count( $nodeListArray ) > 1 )
        {
            eZDebug::writeError( $nodeListArray , "There are more then one main_node for objectID: $objectID" );
        }
        else
        {
            if ( $asObject )
            {
                $retNodeArray = eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );
                $returnValue =& $retNodeArray[0];
                return $returnValue;
            }else
            {
                $retNodeArray =& $nodeListArray;
                return $retNodeArray[0]['node_id'];
            }

        }
        $retVal = null;
        return $retVal;
    }

    /*!
      Fetches the main nodes for an array of object id's
    */
    function &findMainNodeArray( $objectIDArray, $asObject = true )
    {
        if ( count( $objectIDArray ) )
        {
            $db =& eZDB::instance();
            $objectIDString = $db->implodeWithTypeCast( ',', $objectIDArray, 'int' );
            $query="SELECT ezcontentobject.*,
                           ezcontentobject_tree.*,
                           ezcontentclass.serialized_name_list as class_serialized_name_list,
                           ezcontentclass.identifier as class_identifier,
                           ezcontentclass.is_container as is_container
                    FROM ezcontentobject_tree,
                         ezcontentobject,
                         ezcontentclass
                    WHERE ezcontentobject_tree.contentobject_id in ( $objectIDString ) AND
                          ezcontentobject_tree.main_node_id = ezcontentobject_tree.node_id AND
                          ezcontentobject_tree.contentobject_id=ezcontentobject.id AND
                          ezcontentclass.version=0  AND
                          ezcontentclass.id = ezcontentobject.contentclass_id";

            $nodeListArray = $db->arrayQuery( $query );
            if ( $asObject )
            {
                $retNodeArray = eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );
                return $retNodeArray;
            }
            else
            {
                return $nodeListArray;
            }
        }
        $retValue = null;
        return $retValue;
    }


    /*!
     \static
     Fetch node by $nodeID. If $nodeID is an array of ids then list of nodes will be returned.
    */
    function fetch( $nodeID = false, $lang = false, $asObject = true, $conditions = false )
    {
        $returnValue = null;
        $db = eZDB::instance();
        if ( ( is_numeric( $nodeID ) && $nodeID == 1 ) ||
             ( is_array( $nodeID ) && count( $nodeID ) === 1 && $nodeID[0] == 1 ) )
        {
            $query = "SELECT *
                FROM ezcontentobject_tree
                WHERE node_id = 1";
        }
        else
        {
            $versionNameTables = ', ezcontentobject_name ';
            $versionNameTargets = ', ezcontentobject_name.name as name,  ezcontentobject_name.real_translation ';
            $versionNameJoins = " and  ezcontentobject_tree.contentobject_id = ezcontentobject_name.contentobject_id and
                                  ezcontentobject_tree.contentobject_version = ezcontentobject_name.content_version and ";
            if ( $lang )
            {
                $lang = $db->escapeString( $lang );
                $versionNameJoins .= " ezcontentobject_name.content_translation = '$lang' ";
            }
            else
            {
                $versionNameJoins .= eZContentLanguage::sqlFilter( 'ezcontentobject_name', 'ezcontentobject' );
            }

            $languageFilter = ' AND '.eZContentLanguage::languagesSQLFilter( 'ezcontentobject' );

            $sqlCondition = '';

            if ( $nodeID !== false )
            {
                if ( is_array( $nodeID ) )
                {
                    if( count( $nodeID ) === 1 )
                        $sqlCondition = 'node_id IN ( ' . (int) $nodeID[0] . ' ) AND';
                    else
                    {
                        // All elements from $nodeID should be casted to (int)
                        $impStr = $db->implodeWithTypeCast( ',', $nodeID, 'int' );
                        $sqlCondition = 'node_id IN ( ' . $impStr . ' ) AND';
                    }
                }
                else
                {
                    $sqlCondition = 'node_id IN ( ' . (int) $nodeID . ' ) AND';
                }
            }

            if ( is_array( $conditions ) )
            {
                foreach( $conditions as $key => $condition )
                {
                    if ( is_string( $condition ) )
                    {
                        $condition = $db->escapeString( $condition );
                        $condition = "'$condition'";
                    }

                    $sqlCondition .= "ezcontentobject_tree." . $db->escapeString( $key ) . "=$condition AND ";
                }
            }

            if ( $sqlCondition == '' )
            {
                eZDebug::writeWarning( 'Cannot fetch node, emtpy ID or no conditions given', 'eZContentObjectTreeNode::fetch' );
                return $returnValue;
            }

            $query="SELECT ezcontentobject.*,
                       ezcontentobject_tree.*,
                       ezcontentclass.serialized_name_list as class_serialized_name_list,
                       ezcontentclass.identifier as class_identifier,
                       ezcontentclass.is_container as is_container
                       $versionNameTargets
                FROM ezcontentobject_tree,
                     ezcontentobject,
                     ezcontentclass
                     $versionNameTables
                WHERE $sqlCondition
                      ezcontentobject_tree.contentobject_id=ezcontentobject.id AND
                      ezcontentclass.version=0  AND
                      ezcontentclass.id = ezcontentobject.contentclass_id
                      $languageFilter
                      $versionNameJoins";
        }

        $nodeListArray = $db->arrayQuery( $query );

        if ( count( $nodeListArray ) > 0 )
        {
            if ( $asObject )
            {
                $returnValue = eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );
                if ( count( $returnValue ) === 1 )
                    $returnValue = $returnValue[0];
            }
            else
            {
                if ( count( $nodeListArray ) === 1 )
                    $returnValue = $nodeListArray[0];
                else
                    $returnValue = $nodeListArray;
            }
        }

        return $returnValue;
    }

    /*!
     \static
     Finds the node for the object \a $contentObjectID which placed as child of node \a $parentNodeID.
     \return An eZContentObjectTreeNode object or \c null if no node was found.
    */
    function fetchNode( $contentObjectID, $parentNodeID )
    {
        $returnValue = null;
        $ini =& eZINI::instance();
        $db =& eZDB::instance();
        $contentObjectID =(int) $contentObjectID;
        $parentNodeID =(int) $parentNodeID;
        $query = "SELECT ezcontentobject_tree.*
                  FROM ezcontentobject_tree, ezcontentobject
                  WHERE ezcontentobject_tree.contentobject_id = '$contentObjectID' AND
                        ezcontentobject.id = '$contentObjectID' AND
                        ezcontentobject_tree.contentobject_is_published = 1 AND
                        ezcontentobject_tree.parent_node_id = '$parentNodeID' AND ".
                        eZContentLanguage::languagesSQLFilter( 'ezcontentobject' );

        $nodeListArray = $db->arrayQuery( $query );
        if ( count( $nodeListArray ) == 1 )
        {
            $retNodeArray = eZContentObjectTreeNode::makeObjectsArray( $nodeListArray, false );
            $returnValue = $retNodeArray[0];
        }
        return $returnValue;
    }

    /*!
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &fetchParent()
    {
        $parent = $this->fetch( $this->attribute( 'parent_node_id' ) );
        return $parent;
    }

    /*!
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &pathArray()
    {
        $pathString = $this->attribute( 'path_string' );
        $pathItems = explode( '/', $pathString );
        $pathArray = array();
        foreach ( $pathItems as $pathItem )
        {
            if ( $pathItem != '' )
                $pathArray[] = (int) $pathItem;
        }
        return $pathArray;
    }


    function &fetchPath()
    {
        $nodePath = $this->attribute( 'path_string' );

        $retNodes = eZContentObjectTreeNode::fetchNodesByPathString( $nodePath, false, true );

        return $retNodes;
    }

    /*!
     \static
     \return An array with content node objects that is present in the node path \a $nodePath.
     \param $withLastNode If \c true the last node in the path is included in the list.
                          The last node is the node which the path was fetched from.
     \param $asObjects If \c true then return PHP objects, if not return raw row data.
    */
    function fetchNodesByPathString( $nodePath, $withLastNode = false, $asObjects = true )
    {
        $nodesListArray = array();
        $pathString = eZContentObjectTreeNode::createNodesConditionSQLStringFromPath( $nodePath, $withLastNode );

        if ( $pathString  )
        {
            $useVersionName     = true;
            $versionNameTables  = eZContentObjectTreeNode::createVersionNameTablesSQLString ( $useVersionName );
            $versionNameTargets = eZContentObjectTreeNode::createVersionNameTargetsSQLString( $useVersionName );
            $versionNameJoins   = eZContentObjectTreeNode::createVersionNameJoinsSQLString  ( $useVersionName );

            $query = "SELECT ezcontentobject.*,
                             ezcontentobject_tree.*,
                             ezcontentclass.serialized_name_list as class_serialized_name_list,
                             ezcontentclass.identifier as class_identifier
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
        }

        if ( $asObjects )
            $retNodes = eZContentObjectTreeNode::makeObjectsArray( $nodesListArray );
        else
            $retNodes =& $nodesListArray;

        return $retNodes;
    }


    /*!
     \static
     Extracts each node that in the path from db and returns an array of class identifiers
     \param $nodePath A string containing the path of the node, it consists of
                      node IDs starting from the root and delimited by / (slash).
     \param $withLastNode If \c true the last node in the path is included in the list.
                          The last node is the node which the path was fetched from.
     \return An array with class identifier and node ID.

     Example
     \code
     $list = fetchClassIdentifierListByPathString( '/2/10/', false );
     \endcode
    */
    function &fetchClassIdentifierListByPathString( $nodePath, $withLastNode )
    {
        $itemList = array();
        $nodes = eZContentObjectTreeNode::fetchNodesByPathString( $nodePath, $withLastNode, false );

        foreach ( array_keys( $nodes ) as $nodeKey )
        {
            $node =& $nodes[$nodeKey];
            $itemList[]  = array( 'node_id'          => $node['node_id'],
                                  'class_identifier' => $node['class_identifier'] );
        }

        return $itemList;
    }

    /*!
     \deprecated This function should no longer be used, use the eZContentClass::instantiate and eZNodeAssignment::create instead.
    */
    function createObject( $contentClassID, $parentNodeID = 2 )
    {
        $user =& eZUser::currentUser();
        $userID =& $user->attribute( 'contentobject_id' );

        $class = eZContentClass::fetch( $contentClassID );
        $parentNode = eZContentObjectTreeNode::fetch( $parentNodeID );
        $parentContentObject =& $parentNode->attribute( 'object' );
        $sectionID = $parentContentObject->attribute( 'section_id' );
        $object = $class->instantiate( $userID, $sectionID );

//        $parentContentObject = $parentNode->attribute( 'contentobject' );

        $node =& eZContentObjectTreeNode::addChild( $object->attribute( "id" ), $parentNodeID, true );
//        $object->setAttribute( "main_node_id", $node->attribute( 'node_id' ) );
        $node->setAttribute( 'main_node_id', $node->attribute( 'node_id' ) );
        $object->store();
        $node->store();

        return $object;
    }

    /*!
     Add a child to the object tree.
     \param $contentobjectID      The ID of the contentobject the child-node should point to.
     \param $nodeID               The ID of the parent-node to add child-node to, if 0 it uses $this.
     \param $asObject             If true it will return the new child-node as an object, if not it returns the ID.
     \param $contentObjectVersion The version to use on the newly created child-node, if
                                  false it uses the current_version of the specified object.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function &addChild( $contentobjectID, $nodeID = 0, $asObject = false, $contentObjectVersion = false )
    {
        if ( $nodeID == 0 )
        {
            $node = $this;
        }
        else
        {
            $node = eZContentObjectTreeNode::fetch( $nodeID );
        }

        $contentObject =& eZContentObject::fetch( $contentobjectID );
        if ( !$contentObject )
        {
            return false;
        }

        if ( !$contentObjectVersion )
        {
            $contentObjectVersion = $contentObject->attribute( 'current_version' );
        }

        $db =& eZDB::instance();
        $parentMainNodeID = $node->attribute( 'node_id' ); //$parent->attribute( 'main_node_id' );
        $parentPath = $node->attribute( 'path_string' );
        $parentDepth = $node->attribute( 'depth' );
        $isInvinsible = $node->attribute( 'is_invisible' );

        $nodeDepth = $parentDepth + 1 ;

        $insertedNode = eZContentObjectTreeNode::create( $parentMainNodeID, $contentobjectID );

        // set default sorting from content class
        $contentClass = $contentObject->attribute( 'content_class' );
        $insertedNode->setAttribute( 'sort_field', $contentClass->attribute( 'sort_field' ) );
        $insertedNode->setAttribute( 'sort_order', $contentClass->attribute( 'sort_order' ) );

        $insertedNode->setAttribute( 'depth', $nodeDepth );
        $insertedNode->setAttribute( 'path_string', '/TEMPPATH' );

        $insertedNode->setAttribute( 'contentobject_version', $contentObjectVersion );

        // If the parent node is invisible, the new created node should be invisible as well.
        $insertedNode->setAttribute( 'is_invisible', $isInvinsible );

        $db->begin();
        $insertedNode->store();
        $insertedID = $insertedNode->attribute( 'node_id' );
        $newNodePath = $parentPath . $insertedID . '/';
        $insertedNode->setAttribute( 'path_string', $newNodePath );
        $insertedNode->store();
        $db->commit();

        if ( $asObject )
        {
            return $insertedNode;
        }
        else
        {
            return $insertedID;
        }
    }

    /*!
     \return an url alias for the current node. It will generate a unique alias.
    */
    function pathWithNames( $nodeID = 0 )
    {
        if ( $nodeID == 0 )
        {
            $node =& $this;
        }
        else
        {
            $node = eZContentObjectTreeNode::fetch( $nodeID );
        }

        $nodeList =& $node->attribute( 'path' );
        if ( $node->attribute( 'depth' ) > 1 )
        {
            $parentNodeID = $node->attribute( 'parent_node_id' );
            $parentNode = eZContentObjectTreeNode::fetch( $parentNodeID, false, false );
            if ( ! is_null( $parentNode ) )
            {
                $parentNodePathString = $parentNode['path_identification_string'];
            }
            else
            {
                eZDebug::writeError( 'Parent node was null.', 'eZContentObjectTreeNode::pathWithNames()' );
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
            $topLevelName = eZURLAlias::convertToAlias( $topLevelName, 'node_' . $topLevelNode->attribute( 'node_id' ) );

            $pathElementArray = explode( '/', $parentNodePathString );
            if ( count( $pathElementArray ) > 0 )
            {
                $parentNodePathString = implode( '/', $pathElementArray );
            }
            else
            {
                $parentNodePathString = '';
            }
        }
        else
        {
            $parentNodePathString = '';
        }

        // Only set name if current node is not the content root
        $ini =& eZINI::instance( 'content.ini' );
        $contentRootID = $ini->variable( 'NodeSettings', 'RootNode' );
        if ( $node->attribute( 'node_id' ) != $contentRootID )
        {
            $nodeName = $node->attribute( 'name' );
            $nodeName = eZURLAlias::convertToAlias( $nodeName, 'node_' . $node->attribute( 'node_id' ) );

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
        $nodePath = $node->checkPath( $nodePath );
        return $nodePath;
    }

    /*!
     Check if a node with the same name already exists. If so create a $name + __x value.
    */
    function checkPath( $path )
    {
        $moduleINI =& eZINI::instance( 'module.ini' );
        $reserved = $moduleINI->variable( 'ModuleSettings', 'ModuleList' );
        $reservedReg = implode( '|', $reserved );
        $uniqueNumber = 0;
        if ( preg_match( "#^($reservedReg)$#", $path ) )
        {
            ++$uniqueNumber;
        }

        $nodeID       =  $this->attribute( 'node_id' );
        $parentNodeID =  $this->attribute( 'parent_node_id' );
        $depth        =  $this->attribute( 'depth' );
        $db           =& eZDB::instance();

        // common part for two queries
        $pathLikeCheck = 'path_identification_string LIKE \'' . $path . '\\_\\_%\' ESCAPE \'' .  $db->escapeString( '\\' ) . '\'';

        /* If current node has path equal to $path or matching to pattern "<$path>__<number>"
         * then return its current path without changes.
         */
        $sql = 'SELECT path_identification_string
                FROM ezcontentobject_tree
                WHERE ( path_identification_string = \'' . $path . '\' OR ' . $pathLikeCheck . ' ) AND node_id = ' . $nodeID;
        $rows = $db->arrayQuery( $sql );
        foreach ( $rows as $idx => $row ) // exclude rows that do not match the pattern
        {
            if ( !preg_match( "#^$path(__\d+)?$#", $row['path_identification_string'] ) )
                 unset( $rows[$idx] );
        }
        if ( count( $rows ) > 0 )
            return $rows[0]['path_identification_string'];
        unset( $rows );

        // If the path matches a module ($uniqueNumber > 0 ) we should not check for existing path
        if ( $uniqueNumber == 0 )
        {
            /* Else if there are no other nodes having path equal to $path
             * then return $path.
             */
            $sql = 'SELECT COUNT(*) AS cnt
                    FROM ezcontentobject_tree
                    WHERE path_identification_string = \'' . $path . '\' AND node_id <> ' . $nodeID;
            $rows = $db->arrayQuery( $sql );
            if ( $rows[0]['cnt'] == 0 )
                return $path;
            unset( $rows );
        }

        /* Else if there are other nodes having path like "<$path>__<number>"
         * then return computed unique path.
         */
        if ( $depth == 2 ) // in this case we should take into account toplevels that may have path equal to one we're checking
            $depthCheck = ' depth IN ( 1, 2 )';
        else
            $depthCheck = 'depth = ' . $depth . ' AND parent_node_id = ' . $parentNodeID;
        $sql = 'SELECT path_identification_string
                FROM ezcontentobject_tree
                WHERE ' . $depthCheck . ' AND ' . $pathLikeCheck . ' AND node_id <> ' . $nodeID;
        $rows = $db->arrayQuery( $sql );
        foreach ( $rows as $row )
        {
            $pathString =& $row['path_identification_string'];
            if ( !preg_match( "#^${path}__(\d+)$#", $pathString, $matches ) )
                continue;
            if ( $matches[1] >= $uniqueNumber )
                $uniqueNumber = $matches[1] + 1;
        }
        // If we have not found a number yet we set it to 1
        if ( $uniqueNumber == 0 )
            $uniqueNumber = 1;
        return $path . '__' . $uniqueNumber;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function updateURLAlias()
    {
        $hasChanged = 0;
        include_once( 'kernel/classes/ezurlalias.php' );
        $newPathString = $this->pathWithNames();

        $existingUrlAlias = eZURLAlias::fetchBySourceURL( $newPathString );

        $db =& eZDB::instance();
        $db->begin();
        if ( get_class( $existingUrlAlias ) == 'ezurlalias' )
        {
            $alias =& $existingUrlAlias;
            if ( $alias->attribute( 'source_url' ) != $newPathString )
                $hasChanged++;
            $alias->setAttribute( 'source_url', $newPathString );
            $alias->setAttribute( 'destination_url', 'content/view/full/' . $this->NodeID );
            $alias->setAttribute( 'forward_to_id', 0 );
            $alias->store();
        }
        else
        {
            $alias = eZURLAlias::create( $newPathString, 'content/view/full/' . $this->NodeID );
            $alias->store();
            $hasChanged++;
        }

        eZURLAlias::cleanupForwardingURLs( $newPathString );
        eZURLAlias::cleanupWildcards( $newPathString );

        $oldPathString = $this->attribute( 'path_identification_string' );

        // Only update if the name has changed
        if ( strcmp( $oldPathString, $newPathString ) != 0 )
        {
            $oldUrlAlias = false;
            // Check if there exists an URL alias for this name already
            if ( $oldPathString != "" )
            {
                $oldUrlAlias = eZURLAlias::fetchBySourceURL( $oldPathString );
            }

            // Update old url alias and old forwarding urls
            if ( get_class( $oldUrlAlias ) == 'ezurlalias' )
            {
                $oldUrlAlias->setAttribute( 'forward_to_id', $alias->attribute( 'id' ) );
                $oldUrlAlias->setAttribute( 'destination_url', 'content/view/full/' . $this->NodeID );
                $oldUrlAlias->store();
                eZURLAlias::updateForwardID( $alias->attribute( 'id' ), $oldUrlAlias->attribute( 'id' ) );
            }
        }

        if ( $this->attribute( 'path_identification_string' ) != $newPathString )
            $hasChanged++;
        $this->setAttribute( 'path_identification_string', $newPathString );
        $this->store();
        $db->commit();

        return $hasChanged;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function updateSubTreePath()
    {
        include_once( 'kernel/classes/ezurlalias.php' );
        include_once( 'kernel/classes/datatypes/ezurl/ezurl.php' );
        $oldPathString = $this->attribute( 'path_identification_string' );

        $newPathString = $this->pathWithNames();

        // Only update if the name has changed
        if ( $oldPathString == $newPathString )
            return;

        $oldUrlAlias = false;
        // Check if there exists an URL alias for this name already
        if ( $oldPathString != "" )
        {
            $oldUrlAlias = eZURLAlias::fetchBySourceURL( $oldPathString );
        }

        // Remove existing aliases if they are forwarding aliases
        $existingUrlAlias = eZURLAlias::fetchBySourceURL( $newPathString );

        $db =& eZDB::instance();
        $db->begin();

        // Update children, part #1 (ezcontentobject_tree table)
        $oldPathStringLength = strlen( $oldPathString );
        $newPathStringText = $db->escapeString( $newPathString );
        $oldPathStringText = $db->escapeString( $oldPathString );
        $subStringQueryPart = $db->subString( 'path_identification_string', $oldPathStringLength + 1 );
        $newPathStringQueryPart = $db->concatString( array( "'$newPathStringText'", $subStringQueryPart ) );
        $sql = "UPDATE ezcontentobject_tree SET path_identification_string = $newPathStringQueryPart " .
               "WHERE  path_identification_string LIKE '$oldPathStringText/%'";
        $db->query( $sql );

        if ( get_class( $existingUrlAlias ) == 'ezurlalias' )
        {
            $alias =& $existingUrlAlias;
            $alias->setAttribute( 'source_url', $newPathString );
            $alias->setAttribute( 'destination_url', 'content/view/full/' . $this->NodeID );
            $alias->setAttribute( 'forward_to_id', 0 );
            $alias->store();
        }
        else
        {
            $alias = eZURLAlias::create( $newPathString, 'content/view/full/' . $this->NodeID );
            $alias->store();
        }

        eZURLAlias::cleanupForwardingURLs( $newPathString );
        eZURLAlias::cleanupWildcards( $newPathString );

        $subNodeCount = $this->subTreeCount( array( 'Limitation' => array() ) );
        if ( $subNodeCount > 0 )
        {
            $wildcardAlias = eZURLAlias::create( $oldPathString . '/*', $newPathString . '/{1}', true, false, EZ_URLALIAS_WILDCARD_TYPE_FORWARD );
            $wildcardAlias->store();
        }

        // Update old url alias and old forwarding urls
        if ( get_class( $oldUrlAlias ) == 'ezurlalias' )
        {
            $oldUrlAlias->setAttribute( 'forward_to_id', $alias->attribute( 'id' ) );
            $oldUrlAlias->setAttribute( 'destination_url', 'content/view/full/' . $this->NodeID );
            $oldUrlAlias->store();
            eZURLAlias::updateForwardID( $alias->attribute( 'id' ), $oldUrlAlias->attribute( 'id' ) );
        }

        // Check if any URL's is pointing to this node, if so update it
        if ( include_once( 'kernel/classes/datatypes/ezurl/ezurltype.php' ) )
            $url = eZURL::urlByURL( "/" . $oldPathString );

        if ( $url && $oldPathString != "") // oldPathString=="" when url refers to site root, don't need to update url.
        {
            $url->setAttribute( 'url', '/' . $newPathString );
            $url->store();
        }

        eZDebugSetting::writeDebug( 'kernel-content-treenode', $oldPathString .'  ' . strlen( $oldPathString ) . '  ' . $newPathString );
        $this->setAttribute( 'path_identification_string', $newPathString );
        $this->store();

        // Update children, part #2 (ezurlalias table)
        // This part is separated from the first one to avoid deadlocks in mysql.
        // Only update children if the node has had an URL alias before.
        // Which means that it has been published before
        if ( $oldPathString != "" )
            eZURLAlias::updateChildAliases( $newPathString, $oldPathString );

        eZURLAlias::expireWildcards();
        $db->commit();
    }

    /*!
      Removes the current node.

      \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function remove( $nodeID = 0 )
    {
        include_once( "kernel/classes/ezrole.php" );
        include_once( "kernel/classes/ezpolicy.php" );
        include_once( "kernel/classes/ezpolicylimitation.php" );

        $ini =& eZINI::instance();

        if ( $nodeID == 0 )
        {
            $node =& $this;
        }
        else
        {
            $node = eZContentObjectTreeNode::fetch( $nodeID );
        }

        if ( !is_object( $node ) )
        {
            return;
        }

        include_once( "kernel/classes/ezaudit.php" );
        if ( eZAudit::isAuditEnabled() )
        {
            // Set audit params.
            $nodeIDAudit = $node->attribute( 'node_id' );
            $object = $node->object();
            $objectID = $object->attribute( 'id' );
            $objectName = $object->attribute( 'name' );

            eZAudit::writeAudit( 'content-delete', array( 'Node ID' => $nodeIDAudit, 'Object ID' => $objectID, 'Content Name' => $objectName,
                                                          'Comment' => 'Removed the current node: eZContentObjectTreeNode::remove()' ) );
        }

        $db =& eZDB::instance();
        $db->begin();

        $nodePath = $node->attribute( 'path_string' );
        $childrensPath = $nodePath ;
        $pathLength = strlen( $childrensPath );

        $pathString = " path_string like '$childrensPath%' ";


        $subStringString = $db->subString( 'path_string', 1, $pathLength );

        $urlAlias = $node->attribute( 'url_alias' );

        // Remove static cache
        if ( $ini->variable( 'ContentSettings', 'StaticCache' ) == 'enabled' )
        {
            include_once( 'kernel/classes/ezstaticcache.php' );
            $staticCache = new eZStaticCache();
            $staticCache->removeURL( "/" . $urlAlias );
            $staticCache->generateAlwaysUpdatedCache();

            $parent = $node->fetchParent();
        }

        $db->query( "DELETE FROM ezcontentobject_tree
                            WHERE $pathString OR
                            path_string = '$nodePath'" );

        // Re-cache parent node
        if ( $ini->variable( 'ContentSettings', 'StaticCache' ) == 'enabled' )
        {
            if ( $parent )
            {
                $staticCache->cacheURL( "/" . $parent->urlAlias() );
            }
        }

        // Clean up URL alias
        $urlObject = eZURLAlias::fetchBySourceURL( $urlAlias );
        if ( $urlObject )
        {
            $urlObject->cleanup();
        }

        // Clean up content cache
        include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearContentCacheIfNeeded( $node->attribute( 'contentobject_id' ) );

        $parentNode =& $node->attribute( 'parent' );
        if ( is_object( $parentNode ) )
        {
            eZContentCacheManager::clearContentCacheIfNeeded( $parentNode->attribute( 'contentobject_id' ) );
            $parentNode->updateAndStoreModified();
        }

        // Clean up policies and limitations
        eZRole::cleanupByNode( $node );

        // Clean up recent items
        $nodeID = $node->attribute( 'node_id' );
        include_once( 'kernel/classes/ezcontentbrowserecent.php' );
        eZContentBrowseRecent::removeRecentByNodeID( $nodeID );

        // Clean up bookmarks
        include_once( 'kernel/classes/ezcontentbrowsebookmark.php' );
        eZContentBrowseBookmark::removeByNodeID( $nodeID );

        // Clean up tip-a-friend counter
        include_once( 'kernel/classes/eztipafriendcounter.php' );
        eZTipafriendCounter::remove( $nodeID );

        $db->commit();
    }

    /*!
     \static
     Returns information on what will happen if all subtrees in \a $deleteIDArray
     is removed. The returned structure is:
     - move_to_trash     - \c true if removed objects can be moved to trash,
                           some objects are not allowed to be in trash (e.g user).
     - total_child_count - The total number of children for all delete items
     - can_remove_all    - Will be set to \c true if all selected items can be removed, \c false otherwise
     - delete_list - A list of all subtrees that should be removed, structure:
     -- node               - The content node
     -- object             - The content object
     -- class              - The content class
     -- node_name          - The name of the node
     -- child_count        - Total number of child items below the node
     -- can_remove         - Boolean which tells if the user has permission to remove the node
     -- can_remove_subtree - Boolean which tells if the user has permission to remove items in the subtree
     -- new_main_node_id   - The new main node ID for the node if it needs to be moved, or \c false if not
     -- object_node_count  - The number of nodes the object has (before removal)
     -- sole_node_count    - The number of nodes in the subtree (excluding current) that does
                             not have multiple locations.

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function subtreeRemovalInformation( $deleteIDArray )
    {
        return eZContentObjectTreeNode::removeSubtrees( $deleteIDArray, true, true );
    }

    /*!
     \static
     Will remove the nodes in the subtrees defined in \a $deleteIDArray,
     it will only remove the nodes unless there are no more nodes for
     an object in which case the object is removed too.

     \param $moveToTrash If \c true it will move the object to trash, if \c false
                         the object will be purged from the system.
     \param $infoOnly If set to \c true then it will not remove the subtree
                      but instead return information on what will happen
                      if it is removed. See subtreeRemovalInformation() for the
                      returned structure.

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function removeSubtrees( $deleteIDArray, $moveToTrash = true, $infoOnly = false )
    {
        if ( !$infoOnly )
        {
            include_once( "kernel/classes/ezcontentcachemanager.php" );
        }

        $moveToTrashAllowed = true;
        $deleteResult = array();
        $totalChildCount = 0;
        $totalLoneNodeCount = 0;
        $canRemoveAll = true;

        $db =& eZDB::instance();
        $db->begin();
        foreach ( $deleteIDArray as $deleteID )
        {
            $node = eZContentObjectTreeNode::fetch( $deleteID );
            if ( $node === null )
                continue;

            $object = $node->attribute( 'object' );
            if ( $object === null )
                continue;

            $class = $object->attribute( 'content_class' );
            $canRemove = $object->attribute( 'can_remove' );
            $canRemoveSubtree = true;

            $nodeID = $node->attribute( 'node_id' );
            $nodeName = $object->attribute( 'name' );

            $childCount = 0;
            $newMainNodeID = false;
            $objectNodeCount = 0;
            $readableChildCount = 0;

            if ( $canRemove )
            {
                $isUserClass = false;

                $attributes = eZContentClass::fetchAttributes( $class->attribute( 'id' ) );
                foreach ( $attributes as $attribute )
                {
                    if ( $attribute->attribute( 'data_type_string' ) == 'ezuser' )
                    {
                        $isUserClass = true;
                        break;
                    }
                }

                if ( $moveToTrashAllowed and $isUserClass )
                {
                    $moveToTrashAllowed = false;
                }
                $readableChildCount = $node->subTreeCount( array( 'Limitation' => array() ) );
                $childCount = $node->subTreeCount();
                $totalChildCount += $childCount;

                $allAssignedNodes =& $object->attribute( 'assigned_nodes' );
                $objectNodeCount = count( $allAssignedNodes );
                // We need to find a new main node ID if we are trying
                // to remove the current main node.
                if ( $node->attribute( 'main_node_id' ) == $nodeID )
                {
                    if ( count( $allAssignedNodes ) > 1 )
                    {
                        foreach( $allAssignedNodes as $assignedNode )
                        {
                            $assignedNodeID = $assignedNode->attribute( 'node_id' );
                            if ( $assignedNodeID == $nodeID )
                                continue;
                            $newMainNodeID = $assignedNodeID;
                            break;
                        }
                    }
                }

                if ( $infoOnly )
                {
                    // Find the number of items in the subtree we are allowed to remove
                    // if this differs from the total count it means we have items we cannot remove
                    // We do this by fetching the limitation list for content/remove
                    // and passing it to the subtree count function.
                    include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
                    $currentUser =& eZUser::currentUser();
                    $accessResult = $currentUser->hasAccessTo( 'content', 'remove' );
                    if ( $accessResult['accessWord'] == 'limited' )
                    {
                        $limitationList =& $accessResult['policies'];
                        $removeableChildCount = $node->subTreeCount( array( 'Limitation' => $limitationList ) );
                        $canRemoveSubtree = ( $removeableChildCount == $childCount );
                        $canRemove = $canRemoveSubtree;
                    }
                }

                // We will only remove the subtree if are allowed
                // and are told to do so.
                if ( $canRemove and !$infoOnly )
                {
                    $moveToTrashTemp = $moveToTrash;
                    if ( !$moveToTrashAllowed )
                        $moveToTrashTemp = false;

                    eZContentCacheManager::clearContentCacheIfNeeded( $node->attribute( 'contentobject_id' ) );

                    // Remove children, fetching them by 100 to avoid memory overflow.
                    while ( 1 )
                    {
                        // We should remove the latest subitems first,
                        // so we should fetch subitems sorted by 'path_string' DESC
                        $children =& $node->subTree( array( 'Limitation' => array(),
                                                            'SortBy' => array( 'path' , false ),
                                                            'Limit' => 100 ) );
                        if ( !$children )
                            break;

                        foreach ( array_keys( $children ) as $childKey )
                        {
                            $child =& $children[$childKey];
                            $child->removeNodeFromTree( $moveToTrashTemp );
                            eZContentObject::clearCache();
                        }
                    }

                    $node->removeNodeFromTree( $moveToTrashTemp );
                }
            }
            if ( !$canRemove )
                $canRemoveAll = false;

            // Do not create info list if we are removing subtrees
            if ( !$infoOnly )
                continue;

            $soleNodeCount = $node->subtreeSoleNodeCount();
            $totalLoneNodeCount += $soleNodeCount;
            if ( $objectNodeCount <= 1 )
                ++$totalLoneNodeCount;

            $item = array( "nodeName" => $nodeName, // Backwards compatability
                           "childCount" => $childCount, // Backwards compatability
                           "additionalWarning" => '', // Backwards compatability, this will always be empty
                           'node' => $node,
                           'object' => $object,
                           'class' => $class,
                           'node_name' => $nodeName,
                           'child_count' => $childCount,
                           'object_node_count' => $objectNodeCount,
                           'sole_node_count' => $soleNodeCount,
                           'can_remove' => $canRemove,
                           'can_remove_subtree' => $canRemoveSubtree,
                           'real_child_count' => $readableChildCount,
                           'new_main_node_id' => $newMainNodeID );
            $deleteResult[] = $item;
        }
        $db->commit();


        if ( !$infoOnly )
            return true;

        if ( $moveToTrashAllowed and $totalLoneNodeCount == 0 )
            $moveToTrashAllowed = false;

        return array( 'move_to_trash' => $moveToTrashAllowed,
                      'total_child_count' => $totalChildCount,
                      'can_remove_all' => $canRemoveAll,
                      'delete_list' => $deleteResult,
                      'reverse_related_count' => eZContentObjectTreeNode::reverseRelatedCount( $deleteIDArray ) );
    }

    /*!
     \private
     \static
     Return reverse related count for specified node

     \param $nodeIDArray, array of node id's

     \return reverse related count.
    */
    function reverseRelatedCount( $nodeIDArray )
    {
        // Select count of all elements having reverse relations. And ignore those items that don't relate to objects other than being removed.
        if ( $nodeIDArray === array() )
        {
            return 0;
        }

        foreach( $nodeIDArray as $nodeID )
        {
            $contentObjectTreeNode = eZContentObjectTreeNode::fetch( $nodeID, false, false );
            $tempPathString = $contentObjectTreeNode['path_string'];

            // Create WHERE section
            $pathStringArray[] = "tree.path_string like '$tempPathString%'";
            $path2StringArray[] = "tree2.path_string like '$tempPathString%'";
        }
        $path_strings = '( ' . implode( ' OR ', $pathStringArray ) . ' ) ';
        $path_strings_where = '( ' . implode( ' OR ', $path2StringArray ) . ' ) ';

        // Total count of sub items
        $db = eZDB::instance();
        $countOfItems = $db->arrayQuery( "SELECT COUNT( DISTINCT( tree.node_id ) ) as count
                                                  FROM  ezcontentobject_tree tree,  ezcontentobject obj,
                                                        ezcontentobject_link link LEFT JOIN ezcontentobject_tree tree2
                                                        ON link.from_contentobject_id = tree2.contentobject_id
                                                  WHERE $path_strings
                                                        and link.to_contentobject_id = tree.contentobject_id
                                                        and obj.id = link.from_contentobject_id
                                                        and obj.current_version = link.from_contentobject_version
                                                        and not $path_strings_where" );

        if ( $countOfItems )
        {
            return $countOfItems[0]['count'];
        }
    }

    /*!
     Will check if you are  removing the main node in which case it relocates
     the main node before removing it. It will also remove the object if there
     no more node assignments for it.
     \param $moveToTrash If \c true it will move the object to trash, if \c false
                         the object will be purged from the system.

     \note This uses remove() to do the actual node removal but has some extra logic
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function removeNodeFromTree( $moveToTrash = true )
    {
        include_once( 'kernel/classes/ezcontentcachemanager.php' );
        $nodeID = $this->attribute( 'node_id' );
        if ( $nodeID == $this->attribute( 'main_node_id' ) )
        {
            $object = $this->object();
            $assignedNodes =& $object->attribute( 'assigned_nodes' );
            if ( count( $assignedNodes ) > 1 )
            {
                $newMainNode = false;
                foreach ( $assignedNodes as $assignedNode )
                {
                    $assignedNodeID = $assignedNode->attribute( 'node_id' );
                    if ( $assignedNodeID == $nodeID )
                        continue;
                    $newMainNode = $assignedNode;
                    break;
                }

                // We need to change the main node ID before we remove the current node
                $db =& eZDB::instance();
                $db->begin();
                eZContentObjectTreeNode::updateMainNodeID( $newMainNode->attribute( 'node_id' ),
                                                           $object->attribute( 'id' ),
                                                           $object->attribute( 'current_version' ),
                                                           $newMainNode->attribute( 'parent_node_id' ) );

                eZContentCacheManager::clearContentCacheIfNeeded( $this->attribute( 'contentobject_id' ) );
                $this->remove();
                $db->commit();
            }
            else
            {
                // This is the last assignment so we remove the object too
                eZContentCacheManager::clearContentCacheIfNeeded( $this->attribute( 'contentobject_id' ) );

                $db =& eZDB::instance();
                $db->begin();
                $this->remove();

                if ( $moveToTrash )
                {
                    // saving information about this node in ..trash_node table
                    include_once( 'kernel/classes/ezcontentobjecttrashnode.php' );
                    $trashNode = eZContentObjectTrashNode::createFromNode( $this );
                    $db =& eZDB::instance();
                    $db->begin();
                    $trashNode->storeToTrash();
                    $db->commit();
                    $object->remove();
                }
                else
                {
                    $object->purge();
                }
                $db->commit();
            }
        }
        else
        {
            eZContentCacheManager::clearContentCacheIfNeeded( $this->attribute( 'contentobject_id' ) );
            $this->remove();
        }
    }

    /*!
     \return The number of nodes in the current subtree that have no other placements.
    */
    function subtreeSoleNodeCount( $params = array() )
    {
        $nodeID = $this->attribute( 'node_id' );
        $node = $this;

        $depth = false;
        if ( isset( $params['Depth'] ) && is_numeric( $params['Depth'] ) )
        {
            $depth = $params['Depth'];

        }

        $fromNode = $nodeID;

        $nodePath = null;
        $nodeDepth = 0;
        if ( count( $node ) != 0 )
        {
            $nodePath = $node->attribute( 'path_string' );
            $nodeDepth = $node->attribute( 'depth' );
        }

        $childPath = $nodePath;
        $pathLength = strlen( $childPath );

        $db =& eZDB::instance();
        $subStringString = $db->subString( 'path_string', 1, $pathLength );
        $pathString = " ezcot.path_string like '$childPath%' and ";

        $notEqParentString = "ezcot.node_id != $fromNode";
        $depthCond = '';
        if ( $depth )
        {

            $nodeDepth += $depth;
            if ( isset( $params[ 'DepthOperator' ] ) && $params[ 'DepthOperator' ] == 'eq' )
            {
                $depthCond = ' ezcot.depth = ' . $nodeDepth . '';
                $notEqParentString = '';
            }
            else
                $depthCond = ' ezcot.depth <= ' . $nodeDepth . ' and ';
        }

        $tmpTableName = $db->generateUniqueTempTableName( 'eznode_count_%' );
        $db->createTempTable( "CREATE TEMPORARY TABLE $tmpTableName ( count int )" );
        $query = "INSERT INTO $tmpTableName
                  SELECT
                          count( ezcot.main_node_id ) AS count
                    FROM
                          ezcontentobject_tree ezcot,
                          ezcontentobject_tree ezcot_all
                    WHERE
                           $pathString
                           $depthCond
                           $notEqParentString
                           and ezcot.contentobject_id = ezcot_all.contentobject_id
                    GROUP BY ezcot_all.main_node_id
                    HAVING count( ezcot.main_node_id ) <= 1";

        $db->query( $query );
        $query = "SELECT count( * ) AS count
                  FROM $tmpTableName";

        $rows = $db->arrayQuery( $query );
        $db->dropTempTable( "DROP TABLE $tmpTableName" );
        return $rows[0]['count'];
    }

    /*!
      Moves the node to the given node.
      \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function move( $newParentNodeID, $nodeID = 0 )
    {
        include_once( "kernel/classes/ezpolicylimitation.php" );
        if ( $nodeID == 0 )
        {
            $node = $this;
            $nodeID = $node->attribute( 'node_id' );
        }
        else
        {
            $node = eZContentObjectTreeNode::fetch( $nodeID );
        }

        $oldPath = $node->attribute( 'path_string' ); //$marginsArray[0][2];
        $oldParentNodeID = $node->attribute( 'parent_node_id' ); //$marginsArray[0][3];
        $newParentNodeID =(int) $newParentNodeID;
        if ( $oldParentNodeID != $newParentNodeID )
        {
            $node->updateAndStoreModified();
            // Who moves which content should be logged.
            include_once( "kernel/classes/ezaudit.php" );
            $object = $node->object();
            eZAudit::writeAudit( 'content-move', array( 'Node ID' => $node->attribute( 'node_id' ),
                                                        'Old parent node ID' => $oldParentNodeID, 'New parent node ID' => $newParentNodeID,
                                                        'Object ID' => $object->attribute( 'id' ), 'Content Name' => $object->attribute( 'name' ),
                                                        'Comment' => 'Moved the node to the given node: eZContentObjectTreeNode::move()' ) );

            $newParentNode = eZContentObjectTreeNode::fetch( $newParentNodeID );
            $newParentPath = $newParentNode->attribute( 'path_string' );
            $newParentDepth = $newParentNode->attribute( 'depth' );
            $newPath = $newParentPath;// . $newParentNodeID . '/' ;
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
            $db->begin();
            $db->query( $moveQuery );
            $db->query( $moveQuery1 );

        /// role system clean up
        // Clean up policies and limitations

            $limitationsToFix = eZPolicyLimitation::findByType( 'SubTree', $node->attribute( 'path_string' ), false );
            if ( count( $limitationsToFix )  > 0 )
            {
                include_once( "kernel/classes/ezrole.php" );
                $limitationIDString = implode( ',', $limitationsToFix );
                $limitationIDString = " limitation_id in ( $limitationIDString ) ";
                $subStringString = $db->subString( 'value', 1, $oldPathLength );
                $subStringString2 =  $db->subString( 'value', $oldPathLength );

                $query = "UPDATE
                                 ezpolicy_limitation_value
                          SET
                                 value = " . $db->concatString( array( "'$newPath'" , "'$nodeID'",$subStringString2 ) ) . "
                          WHERE
                                ( $subStringString = '$childrensPath' OR
                                 value = '$oldPath' ) AND  $limitationIDString";

                $db->query( $query );

                eZRole::expireCache();
            }

            // Update "is_invisible" node attribute.
            $newNode = eZContentObjectTreeNode::fetch( $nodeID );
            eZContentObjectTreeNode::updateNodeVisibility( $newNode, $newParentNode );
            $db->commit();
        }
    }

    function checkAccess( $functionName, $originalClassID = false, $parentClassID = false, $returnAccessList = false, $language = false )
    {
        $classID = $originalClassID;
        $user =& eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );

        include_once( 'kernel/classes/ezcontentlanguage.php' );
        // Fetch the ID of the language if we get a string with a language code
        // e.g. 'eng-GB'
        $originalLanguage = $language;
        if ( is_string( $language ) && strlen( $language ) > 0 )
        {
            $language = eZContentLanguage::idByLocale( $language );
        }
        else
        {
            $language = false;
        }

        // This will be filled in with the available languages of the object
        // if a Language check is performed.
        $languageList = false;

        // This will be filled if parent object is needed.
        $parentObject = false;

        $origFunctionName = $functionName;
        // The 'move' function simply reuses 'edit' for generic access
        // but adds another top-level check below
        // The original function is still available in $origFunctionName
        if ( $functionName == 'move' )
            $functionName = 'edit';

        // Manage locations depends if it's removal or not.
        if ( $functionName == 'can_add_location' ||
             $functionName == 'can_remove_location' )
        {
            $functionName = 'manage_locations';
        }

        $accessResult = $user->hasAccessTo( 'content' , $functionName );
        $accessWord = $accessResult['accessWord'];
        if ( $origFunctionName == 'can_remove_location' )
        {
            if ( $this->ParentNodeID <= 1 )
            {
                return 0;
            }
            $currentNode = eZContentObjectTreeNode::fetch( $this->ParentNodeID );
            $contentObject = $currentNode->attribute( 'object' );
        }
        else
        {
            $currentNode = $this;
            $contentObject =& $this->attribute( 'object' );
        }

        /*
        // Uncomment this part if 'create' permissions should become implied 'edit'.
        // Merges in 'create' policies with 'edit'
        if ( $functionName == 'edit' &&
             !in_array( $accessWord, array( 'yes', 'no' ) ) )
        {
            // Add in create policies.
            $accessExtraResult = $user->hasAccessTo( 'content', 'create' );
            if ( $accessExtraResult['accessWord'] != 'no' )
            {
                $accessWord = $accessExtraResult['accessWord'];
                if ( isset( $accessExtraResult['policies'] ) )
                {
                    $accessResult['policies'] = array_merge( $accessResult['policies'],
                                                             $accessExtraResult['policies'] );
                }
                if ( isset( $accessExtraResult['accessList'] ) )
                {
                    $accessResult['accessList'] = array_merge( $accessResult['accessList'],
                                                               $accessExtraResult['accessList'] );
                }
            }
        }
        */

        if ( $origFunctionName == 'remove' or
             $origFunctionName == 'move' or
             $origFunctionName == 'can_remove_location' )
        {
            // We do not allow these actions on top-level nodes
            // - remove
            // - move
            if ( $this->ParentNodeID <= 1 )
            {
                return 0;
            }
        }

        if ( $classID === false )
        {
            $classID = $contentObject->attribute( 'contentclass_id' );
        }
        if ( $accessWord == 'yes' )
        {
            return 1;
        }
        else if ( $accessWord == 'no' )
        {
            if ( $functionName == 'edit' )
            {
                // Check if we have 'create' access under the main parent
                $object =& $currentNode->object();
                if ( $object && $object->attribute( 'current_version' ) == 1 && !$object->attribute( 'status' ) )
                {
                    $mainNode = eZNodeAssignment::fetchForObject( $object->attribute( 'id' ), $object->attribute( 'current_version' ) );
                    $parentObj = $mainNode[0]->attribute( 'parent_contentobject' );
                    $result = $parentObj->checkAccess( 'create', $object->attribute( 'contentclass_id' ),
                                                       $parentObj->attribute( 'contentclass_id' ), false, $originalLanguage );
                    return $result;
                }
                else
                {
                    return 0;
                }
            }

            return 0;
        }
        else
        {
            $policies =& $accessResult['policies'];
            $access = 'denied';

            foreach ( array_keys( $policies ) as $pkey  )
            {
                if ( $access == 'allowed' )
                {
                    break;
                }

                $limitationArray =& $policies[$pkey];
                $limitationList = array();
                if ( isset( $limitationArray['Subtree' ] ) )
                {
                    $checkedSubtree = false;
                }
                else
                {
                    $checkedSubtree = true;
                    $accessSubtree = false;
                }
                if ( isset( $limitationArray['Node'] ) )
                {
                    $checkedNode = false;
                }
                else
                {
                    $checkedNode = true;
                    $accessNode = false;
                }
                foreach ( array_keys( $limitationArray ) as $key  )
                {
                    $access = 'denied';
                    switch( $key )
                    {
                        case 'Class':
                        {
                            if ( $functionName == 'create' and
                                 !$originalClassID )
                            {
                                $access = 'allowed';
                            }
                            else if ( $functionName == 'create' and
                                      in_array( $classID, $limitationArray[$key] ) )
                            {
                                $access = 'allowed';
                            }
                            else if ( $functionName != 'create' and
                                      in_array( $contentObject->attribute( 'contentclass_id' ), $limitationArray[$key] ) )
                            {
                                $access = 'allowed';
                            }
                            else
                            {
                                $access = 'denied';
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $limitationArray[$key] );
                            }
                        } break;

                        case 'ParentClass':
                        {
                            if (  in_array( $contentObject->attribute( 'contentclass_id' ), $limitationArray[$key]  ) )
                            {
                                $access = 'allowed';
                            }
                            else
                            {
                                $access = 'denied';
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $limitationArray[$key] );
                            }
                        } break;

                        case 'Section':
                        case 'User_Section':
                        {
                            if ( in_array( $contentObject->attribute( 'section_id' ), $limitationArray[$key] ) )
                            {
                                $access = 'allowed';
                            }
                            else
                            {
                                $access = 'denied';
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $limitationArray[$key] );
                            }
                        } break;

                        case 'Language':
                        {
                            $languageMask = 0;
                            // If we don't have a language list yet we need to fetch it
                            // and optionally filter out based on $language.
                            if ( $functionName == 'create' )
                            {
                                // If the function is 'create' we do not use the language_mask for matching.
                                if ( $language !== false )
                                {
                                    $languageMask = $language;
                                }
                                else
                                {
                                    // If the create is used and no language specified then
                                    // we need to match against all possible languages (which
                                    // is all bits set, ie. -1).
                                    $languageMask = -1;
                                }
                            }
                            else
                            {
                                if ( $language !== false )
                                {
                                    if ( $languageList === false )
                                    {
                                        $languageMask = $contentObject->attribute( 'language_mask' );
                                        // We are restricting language check to just one language
                                        $languageMask &= $language;
                                        // If the resulting mask is 0 it means that the user is trying to
                                        // edit a language which does not exist, ie. translating.
                                        // The mask will then become the language trying to edit.
                                        if ( $languageMask == 0 )
                                        {
                                            $languageMask = $language;
                                        }
                                    }
                                }
                                else
                                {
                                    $languageMask = -1;
                                }
                            }
                            // Fetch limit mask for limitation list
                            $limitMask = eZContentLanguage::maskByLocale( $limitationArray[$key] );
                            if ( ( $languageMask & $limitMask ) != 0 )
                            {
                                $access = 'allowed';
                            }
                            else
                            {
                                $access = 'denied';
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $limitationArray[$key] );
                            }
                        } break;

                        case 'Owner':
                        {
                            // if limitation value == 2, anonymous limited to current session.
                            if ( in_array( 2, $limitationArray[$key] ) &&
                                 $user->isAnonymous() )
                            {
                                include_once( 'kernel/classes/ezpreferences.php' );
                                $createdObjectIDList = eZPreferences::value( 'ObjectCreationIDList' );
                                if ( $createdObjectIDList &&
                                     in_array( $contentObject->attribute( 'id' ), unserialize( $createdObjectIDList ) ) )
                                {
                                    $access = 'allowed';
                                }
                            }
                            else if ( $contentObject->attribute( 'owner_id' ) == $userID || $contentObject->attribute( 'id' ) == $userID )
                            {
                                $access = 'allowed';
                            }
                            if ( $access != 'allowed' )
                            {
                                $access = 'denied';
                                $limitationList = array ( 'Limitation' => $key );
                            }
                        } break;

                        case 'Group':
                        {
                            $access = $contentObject->checkGroupLimitationAccess( $limitationArray[$key], $userID );

                            if ( $access != 'allowed' )
                            {
                                $access = 'denied';
                                $limitationList = array ( 'Limitation' => $key,
                                                          'Required' => $limitationArray[$key] );
                            }
                        } break;

                        case 'ParentDepth':
                        {
                            if ( in_array( $currentNode->attribute( 'depth' ), $limitationArray[$key] ) )
                            {
                                $access = 'allowed';
                            }
                            else
                            {
                                $access = 'denied';
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $limitationArray[$key] );
                            }
                        } break;

                        case 'Node':
                        {
                            $accessNode = false;
                            $mainNodeID = $currentNode->attribute( 'main_node_id' );
                            foreach ( $limitationArray[$key] as $nodeID )
                            {
                                $node = eZContentObjectTreeNode::fetch( $nodeID, false, false );
                                $limitationNodeID = $node['main_node_id'];
                                if ( $mainNodeID == $limitationNodeID )
                                {
                                    $access = 'allowed';
                                    $accessNode = true;
                                    break;
                                }
                            }
                            if ( $access != 'allowed' && $checkedSubtree && !$accessSubtree )
                            {
                                $access = 'denied';
                                // ??? TODO: if there is a limitation on Subtree, return two limitations?
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $limitationArray[$key] );
                            }
                            else
                            {
                                $access = 'allowed';
                            }
                            $checkedNode = true;
                        } break;

                        case 'Subtree':
                        {
                            $accessSubtree = false;
                            $path = $currentNode->attribute( 'path_string' );
                            $subtreeArray = $limitationArray[$key];
                            foreach ( $subtreeArray as $subtreeString )
                            {
                                if ( strstr( $path, $subtreeString ) )
                                {
                                    $access = 'allowed';
                                    $accessSubtree = true;
                                    break;
                                }
                            }
                            if ( $access != 'allowed' && $checkedNode && !$accessNode )
                            {
                                $access = 'denied';
                                // ??? TODO: if there is a limitation on Node, return two limitations?
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $limitationArray[$key] );
                            }
                            else
                            {
                                $access = 'allowed';
                            }
                            $checkedSubtree = true;
                        } break;

                        case 'User_Subtree':
                        {
                            $path = $currentNode->attribute( 'path_string' );
                            $subtreeArray = $limitationArray[$key];
                            foreach ( $subtreeArray as $subtreeString )
                            {
                                if ( strstr( $path, $subtreeString ) )
                                {
                                    $access = 'allowed';
                                }
                            }
                            if ( $access != 'allowed' )
                            {
                                $access = 'denied';
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $limitationArray[$key] );
                            }
                        } break;
                    }

                    if ( $access == 'denied' )
                    {
                        break;
                    }
                }

                $policyList[] = array( 'PolicyID' => $pkey,
                                       'LimitationList' => $limitationList );
            }
            if ( $access == 'denied' )
            {
                $accessList = array( 'FunctionRequired' => array ( 'Module' => 'content',
                                                                   'Function' => $origFunctionName,
                                                                   'ClassID' => $classID,
                                                                   'MainNodeID' => $currentNode->attribute( 'main_node_id' ) ),
                                     'PolicyList' => $policyList );
                return 0;
            }
            else
            {
                return 1;
            }
        }
    }

    // code-template::create-block: class-list-from-policy, is-node
    // code-template::auto-generated:START class-list-from-policy
    // This code is automatically generated from templates/classlistfrompolicy.ctpl
    // DO NOT EDIT THIS CODE DIRECTLY, CHANGE THE TEMPLATE FILE INSTEAD

    function classListFromPolicy( $policy, $allowedLanguageCodes = false )
    {
        $canCreateClassIDListPart = array();
        $hasClassIDLimitation = false;
        $object = false;
        if ( isset( $policy['Class'] ) )
        {
            $canCreateClassIDListPart = $policy['Class'];
            $hasClassIDLimitation = true;
        }

        if ( isset( $policy['User_Section'] ) )
        {
            if ( $object === false )
                $object = $this->attribute( 'object' );
            if ( !in_array( $object->attribute( 'section_id' ), $policy['User_Section']  ) )
            {
                return array();
            }
        }

        if ( isset( $policy['User_Subtree'] ) )
        {
            $allowed = false;
            if ( $object === false )
                $object = $this->attribute( 'object' );
            $assignedNodes = $object->attribute( 'assigned_nodes' );
            foreach ( $assignedNodes as $assignedNode )
            {
                $path = $assignedNode->attribute( 'path_string' );
                foreach ( $policy['User_Subtree'] as $subtreeString )
                {
                    if ( strstr( $path, $subtreeString ) )
                    {
                        $allowed = true;
                        break;
                    }
                }
            }
            if( !$allowed )
            {
                return array();
            }
        }

        if ( isset( $policy['Section'] ) )
        {
            if ( $object === false )
                $object = $this->attribute( 'object' );
            if ( !in_array( $object->attribute( 'section_id' ), $policy['Section']  ) )
            {
                return array();
            }
        }

        if ( isset( $policy['ParentClass'] ) )
        {
            if ( $object === false )
                $object = $this->attribute( 'object' );
            if ( !in_array( $object->attribute( 'contentclass_id' ), $policy['ParentClass']  ) )
            {
                return array();
            }
        }

        if ( isset( $policy['ParentDepth'] ) && is_array( $policy['ParentDepth'] ) )
        {
            $NodeDepth = $this->attribute( 'depth' );
            if ( !in_array( '*', $policy['ParentDepth'] ) && !in_array( $NodeDepth, $policy['ParentDepth'] ) )
            {
                return array();
            }
        }

        if ( isset( $policy['Assigned'] ) )
        {
            if ( $object === false )
                $object = $this->attribute( 'object' );
            if ( $object->attribute( 'owner_id' ) != $user->attribute( 'contentobject_id' )  )
            {
                return array();
            }
        }

        $allowedNode = false;
        if ( isset( $policy['Node'] ) )
        {
            $allowed = false;
            foreach( $policy['Node'] as $nodeID )
            {
                $mainNodeID = $this->attribute( 'main_node_id' );
                $node = eZContentObjectTreeNode::fetch( $nodeID, false, false );
                if ( $mainNodeID == $node['main_node_id'] )
                {
                    $allowed = true;
                    $allowedNode = true;
                    break;
                }
            }
            if ( !$allowed && !isset( $policy['Subtree'] ) )
            {
                return array();
            }
        }

        if ( isset( $policy['Subtree'] ) )
        {
            $allowed = false;
            if ( $object === false )
                $object = $this->attribute( 'object' );
            $assignedNodes = $object->attribute( 'assigned_nodes' );
            foreach ( $assignedNodes as $assignedNode )
            {
                $path = $assignedNode->attribute( 'path_string' );
                foreach ( $policy['Subtree'] as $subtreeString )
                {
                    if ( strstr( $path, $subtreeString ) )
                    {
                        $allowed = true;
                        break;
                    }
                }
            }
            if ( !$allowed && !$allowedNode )
            {
                return array();
            }
        }

        if ( isset( $policy['Language'] ) )
        {
            if ( $allowedLanguageCodes )
            {
                $allowedLanguageCodes = array_intersect( $allowedLanguageCodes, $policy['Language'] );
            }
            else
            {
                $allowedLanguageCodes = $policy['Language'];
            }
        }

        if ( $hasClassIDLimitation )
        {
            return array( 'classes' => $canCreateClassIDListPart, 'language_codes' => $allowedLanguageCodes );
        }
        return array( 'classes' => '*', 'language_codes' => $allowedLanguageCodes );
    }

    // This code is automatically generated from templates/classlistfrompolicy.ctpl
    // code-template::auto-generated:END class-list-from-policy

    // code-template::create-block: can-instantiate-class-list, group-filter, object-policy-list, name-create, object-creation, object-sql-creation
    // code-template::auto-generated:START can-instantiate-class-list
    // This code is automatically generated from templates/classcreatelist.ctpl
    // DO NOT EDIT THIS CODE DIRECTLY, CHANGE THE TEMPLATE FILE INSTEAD

    /*!
     Finds all classes that the current user can create objects from and returns.
     It is also possible to filter the list event more with \a $includeFilter and \a $groupList.

     \param $asObject If \c true then it return eZContentClass objects, if not it will
                      be an associative array with \c name and \c id keys.
     \param $includeFilter If \c true then it will include only from class groups defined in
                           \a $groupList, if not it will exclude those groups.
     \param $groupList An array with class group IDs that should be used in filtering, use
                       \c false if you do not wish to filter at all.
     \param $id A unique name for the current fetch, this must be supplied when filtering is
                used if you want caching to work.
    */
    function &canCreateClassList( $asObject = false, $includeFilter = true, $groupList = false, $fetchID = false )
    {
        $ini =& eZINI::instance();
        $groupArray = array();
        $languageCodeList = eZContentLanguage::fetchLocaleList();
        $allowedLanguages = array( '*' => array() );

        $user =& eZUser::currentUser();
        $accessResult = $user->hasAccessTo( 'content' , 'create' );
        $accessWord = $accessResult['accessWord'];

        $classIDArray = array();
        $classList = array();
        $fetchAll = false;
        if ( $accessWord == 'yes' )
        {
            $fetchAll = true;
            $allowedLanguages['*'] = $languageCodeList;
        }
        else if ( $accessWord == 'no' )
        {
            // Cannnot create any objects, return empty list.
            return $classList;
        }
        else
        {
            $policies = $accessResult['policies'];
            foreach ( $policies as $policyKey => $policy )
            {
                $policyArray = $this->classListFromPolicy( $policy, $languageCodeList );
                if ( count( $policyArray ) == 0 )
                {
                    continue;
                }
                $classIDArrayPart = $policyArray['classes'];
                $languageCodeArrayPart = $policyArray['language_codes'];
                if ( $classIDArrayPart == '*' )
                {
                    $fetchAll = true;
                    $allowedLanguages['*'] = array_unique( array_merge( $allowedLanguages['*'], $languageCodeArrayPart ) );
                }
                else
                {
                    foreach( $classIDArrayPart as $class )
                    {
                        if ( isset( $allowedLanguages[$class] ) )
                        {
                            $allowedLanguages[$class] = array_unique( array_merge( $allowedLanguages[$class], $languageCodeArrayPart ) );
                        }
                        else
                        {
                            $allowedLanguages[$class] = $languageCodeArrayPart;
                        }
                    }
                    $classIDArray = array_merge( $classIDArray, array_diff( $classIDArrayPart, $classIDArray ) );
                }
            }
        }

        $filterTableSQL = '';
        $filterSQL = '';
        // Create extra SQL statements for the class group filters.
        if ( is_array( $groupList ) )
        {
            $filterTableSQL = ', ezcontentclass_classgroup ccg';
            $filterSQL = ( " AND\n" .
                           "      cc.id = ccg.contentclass_id AND\n" .
                           "      ccg.group_id " );
            $groupText = implode( ', ', $groupList );
            if ( $includeFilter )
                $filterSQL .= "IN ( $groupText )";
            else
                $filterSQL .= "NOT IN ( $groupText )";
        }

        $classNameFilter = eZContentClassName::sqlFilter( 'cc' );

        if ( $fetchAll )
        {
            $classList = array();
            $db =& eZDb::instance();
            // If $asObject is true we fetch all fields in class
            $fields = $asObject ? "cc.*, $classNameFilter[nameField]" : "cc.id, $classNameFilter[nameField]";
            $rows = $db->arrayQuery( "SELECT DISTINCT $fields\n" .
                                     "FROM ezcontentclass cc$filterTableSQL, $classNameFilter[from]\n" .
                                     "WHERE cc.version = " . EZ_CLASS_VERSION_STATUS_DEFINED . " $filterSQL AND $classNameFilter[where]\n" .
                                     "ORDER BY $classNameFilter[nameField] ASC" );
            $classList = eZPersistentObject::handleRows( $rows, 'ezcontentclass', $asObject );
        }
        else
        {
            // If the constrained class list is empty we are not allowed to create any class
            if ( count( $classIDArray ) == 0 )
            {
                $classList = array();
                return $classList;
            }

            $classList = array();
            $db =& eZDb::instance();
            $classString = implode( ',', $classIDArray );
            // If $asObject is true we fetch all fields in class
            $fields = $asObject ? "cc.*, $classNameFilter[nameField]" : "cc.id, $classNameFilter[nameField]";
            $rows = $db->arrayQuery( "SELECT DISTINCT $fields\n" .
                                     "FROM ezcontentclass cc$filterTableSQL, $classNameFilter[from]\n" .
                                     "WHERE cc.id IN ( $classString  ) AND\n" .
                                     "      cc.version = " . EZ_CLASS_VERSION_STATUS_DEFINED . " $filterSQL AND $classNameFilter[where]\n" .
                                     "ORDER BY $classNameFilter[nameField] ASC" );
            $classList = eZPersistentObject::handleRows( $rows, 'ezcontentclass', $asObject );
        }

        if ( $asObject )
        {
            foreach ( $classList as $key => $class )
            {
                $id = $class->attribute( 'id' );
                if ( isset( $allowedLanguages[$id] ) )
                {
                    $languageCodes = array_unique( array_merge( $allowedLanguages['*'], $allowedLanguages[$id] ) );
                }
                else
                {
                    $languageCodes = $allowedLanguages['*'];
                }
                $classList[$key]->setCanInstantiateLanguages( $languageCodes );
            }
        }

        eZDebugSetting::writeDebug( 'kernel-content-class', $classList, "class list fetched from db" );
        return $classList;
    }

    // This code is automatically generated from templates/classcreatelist.ctpl
    // code-template::auto-generated:END can-instantiate-class-list

    function makeObjectsArray( &$array , $with_contentobject = true )
    {
        $retNodes = array();
        if ( !is_array( $array ) )
            return $retNodes;

        $ini =& eZINI::instance();

        foreach ( $array as $node )
        {
            unset( $object );

            if( $node['node_id'] == 1 )
            {
                if( !array_key_exists( 'name', $node ) || !$node['name'] )
                    $node['name'] = ezi18n( 'kernel/content', 'Top Level Nodes' );
            }

            $object = new eZContentObjectTreeNode( $node );
            // If the name is not set it will be fetched later on when
            // getName()/attribute( 'name' ) is accessed.
            if ( isset( $node['name'] ) )
            {
                $object->setName( $node['name'] );
            }

            if ( isset( $node['class_serialized_name_list'] ) )
            {
                $node['class_name'] = eZContentClass::nameFromSerializedString( $node['class_serialized_name_list'] );
                $object->ClassName = $node['class_name'];
            }
            if ( isset( $node['class_identifier'] ) )
                $object->ClassIdentifier = $node['class_identifier'];
            if ( $with_contentobject )
            {
                if ( array_key_exists( 'class_name', $node ) )
                {
                    unset( $node['remote_id'] );
                    $contentObject = new eZContentObject( $node );

                    $permissions = array();
                    $contentObject->setPermissions( $permissions );
                    $contentObject->setClassName( $node['class_name'] );
                    if ( isset( $node['class_identifier'] ) )
                        $contentObject->ClassIdentifier = $node['class_identifier'];

                }
                else
                {
                    $contentObject = new eZContentObject( array());
                    if ( isset( $node['name'] ) )
                         $contentObject->setCachedName( $node['name'] );
                }
                if ( isset( $node['real_translation'] ) && $node['real_translation'] != '' )
                {
                    $object->CurrentLanguage = $node['real_translation'];
                    $contentObject->CurrentLanguage = $node['real_translation'];
                }
                if ( $node['node_id'] == 1 )
                {
                    $contentObject->ClassName = 'Folder';
                    $contentObject->ClassIdentifier = 'folder';
                    $contentObject->ClassID = 1;
                    $contentObject->SectionID = 1;
                }

                $object->setContentObject( $contentObject );
            }
            $retNodes[] =& $object;
        }
        return $retNodes;
    }

    function getParentNodeId( $nodeID )
    {
        if ( !isset( $nodeID ) )
            return null;
        $db =& eZDB::instance();
        $nodeID =(int) $nodeID;
        $parentArr = $db->arrayQuery( "SELECT
                                              parent_node_id
                                       FROM
                                              ezcontentobject_tree
                                       WHERE
                                              node_id = $nodeID");
        return $parentArr[0]['parent_node_id'];
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function deleteNodeWhereParent( $node, $id )
    {
        eZContentObjectTreeNode::remove( eZContentObjectTreeNode::findNode( $node, $id ) );

    }

    function findNode( $parentNode, $id, $asObject = false, $remoteID = false )
    {
        if ( !isset( $parentNode) || $parentNode == NULL  )
        {
            $parentNode = 2;
        }
        $parentNode =(int) $parentNode;
        $db =& eZDB::instance();
        if( $asObject )
        {
            if ( $remoteID )
            {
                $objectIDFilter = 'ezcontentobject.remote_id = ' . (string) $id;
            }
            else
            {
                $objectIDFilter = 'contentobject_id = ' . (int) $id;
            }

            $query="SELECT ezcontentobject.*,
                       ezcontentobject_tree.*,
                       ezcontentclass.serialized_name_list as class_serialized_name_list,
                       ezcontentclass.identifier as class_identifier,
                       ezcontentclass.is_container as is_container
                FROM ezcontentobject_tree,
                     ezcontentobject,
                     ezcontentclass
                WHERE parent_node_id = $parentNode AND
                      $objectIDFilter AND
                      ezcontentobject_tree.contentobject_id=ezcontentobject.id AND
                      ezcontentclass.version=0  AND
                      ezcontentclass.id = ezcontentobject.contentclass_id ";

            $nodeListArray = $db->arrayQuery( $query );
            $retNodeArray = eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );

            if ( count( $retNodeArray ) > 0 )
            {
                return $retNodeArray[0];
            }
            else
            {
                return null;
            }
        }
        else
        {
            $id = (int) $id;
            $getNodeQuery = "SELECT node_id
                           FROM ezcontentobject_tree
                           WHERE
                                parent_node_id=$parentNode AND
                                contentobject_id = $id ";
            $nodeArr = $db->arrayQuery( $getNodeQuery );
            if ( isset( $nodeArr[0] ) )
            {
                return $nodeArr[0]['node_id'];
            }
            else
            {
                return false;
            }
        }
    }

    function &getName( $language = false )
    {
        // If the name is not set yet we fetch it from the object table
        if ( $this->Name === null || $language !== false )
        {
            if ( $this->CurrentLanguage || $language !== false )
            {
                $sql = "SELECT name FROM ezcontentobject_name WHERE contentobject_id=" . (int) $this->ContentObjectID . " AND content_version=" . (int)$this->attribute( 'contentobject_version' ) . " AND real_translation='" . ( $language !== false ? $language : $this->CurrentLanguage ) . "'";
            }
            else
            {
                $sql = "SELECT name FROM ezcontentobject WHERE id=" . (int) $this->ContentObjectID;
            }
            $db =& eZDB::instance();
            $rows = $db->arrayQuery( $sql );
            if ( count( $rows ) > 0 )
            {
                if ( $language !== false )
                {
                    return $rows[0]['name'];
                }
                $this->Name = $rows[0]['name'];
            }
            else
            {
                if ( $language !== false )
                {
                    return false;
                }
                $this->Name = false;
            }
        }
        return $this->Name;
    }

    function setName( $name )
    {
        $this->Name = $name;
    }

    /*!
     \static
     Creates propper nodeassigment from contentNodeDOMNode specification

     \param contentobjecttreenode DOMNode
     \param contentobject.
     \param version
     \param isMain
     \param options

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function unserialize( $contentNodeDOMNode, $contentObject, $version, $isMain, &$nodeList, &$options, $handlerType = 'ezcontentobject' )
    {
        $parentNodeID = -1;

        $remoteID = $contentNodeDOMNode->attributeValue( 'remote-id' );
        $parentNodeRemoteID = $contentNodeDOMNode->attributeValue( 'parent-node-remote-id' );
        $node = eZContentObjectTreeNode::fetchByRemoteID( $remoteID );
        if ( is_object( $node ) )
        {
            $description = "Node with remote ID $remoteID already exists.";

            include_once( 'kernel/classes/ezpackagehandler.php' );
            $choosenAction = eZPackageHandler::errorChoosenAction( EZ_PACKAGE_CONTENTOBJECT_ERROR_EXISTS,
                                                                   $options, $description, $handlerType, false );

            switch( $choosenAction )
            {
                // In case user have choosen "Keep existing object and create new"
                case EZ_PACKAGE_CONTENTOBJECT_NEW:
                {
                    $newRemoteID = md5( (string)mt_rand() . (string)mktime() );
                    $node->setAttribute( 'remote_id', $newRemoteID );
                    $node->store();
                    $nodeInfo = array( 'contentobject_id' =>  $node->attribute( 'contentobject_id' ),
                                       'contentobject_version' => $node->attribute( 'contentobject_version' ),
                                       'parent_remote_id' => $remoteID );
                    $nodeAssignment = eZPersistentObject::fetchObject( eZNodeAssignment::definition(),
                                                                       null,
                                                                       $nodeInfo );
                    if ( is_object( $nodeAssignment ) )
                    {
                        $nodeAssignment->setAttribute( 'parent_remote_id', $newRemoteID );
                        $nodeAssignment->store();
                    }
                } break;

                // When running non-interactively with ezpm.php
                case EZ_PACKAGE_NON_INTERACTIVE:
                {
                    // Update existing node settigns.
                    if ( !$parentNodeRemoteID )
                    {
                        // when top node of subtree export, only update node sort field and sort order
                        $node->setAttribute( 'sort_field', eZContentObjectTreeNode::sortFieldID( $contentNodeDOMNode->getAttribute( 'sort-field' ) ) );
                        $node->setAttribute( 'sort_order', $contentNodeDOMNode->getAttribute( 'sort-order' ) );
                        $node->store();
                        return true;
                    }
                } break;

                default:
                {
                    // This error may occur only if data integrity is broken
                    $options['error'] = array( 'error_code' => EZ_PACKAGE_CONTENTOBJECT_ERROR_NODE_EXISTS,
                                               'element_id' => $remoteID,
                                               'description' => $description );
                    return false;
                } break;
            }
        }

        if ( $parentNodeRemoteID )
        {
            $parentNode = eZContentObjectTreeNode::fetchByRemoteID( $parentNodeRemoteID );
            if ( $parentNode !== null )
            {
                $parentNodeID = $parentNode->attribute( 'node_id' );
            }
        }
        else
        {
            if ( isset( $options['top_nodes_map'][$contentNodeDOMNode->attributeValue( 'node-id' )]['new_node_id'] ) )
            {
                $parentNodeID = $options['top_nodes_map'][$contentNodeDOMNode->attributeValue( 'node-id' )]['new_node_id'];
            }
            else if ( isset( $options['top_nodes_map']['*'] ) )
            {
                $parentNodeID = $options['top_nodes_map']['*'];
            }
            else
            {
                eZDebug::writeError( 'New parent node not set ' . $contentNodeDOMNode->attributeValue( 'name' ),
                                     'eZContentObjectTreeNode::unserialize()' );
            }
        }

        $isMain = ( $isMain && $contentNodeDOMNode->attributeValue( 'is-main-node' ) );

        $nodeInfo = array( 'contentobject_id' => $contentObject->attribute( 'id' ),
                           'contentobject_version' => $version,
                           'is_main' => $isMain,
                           'parent_node' => $parentNodeID,
                           'parent_remote_id' => $remoteID,     // meaning processed node remoteID (not parent)
                           'sort_field' => eZContentObjectTreeNode::sortFieldID( $contentNodeDOMNode->attributeValue( 'sort-field' ) ),
                           'sort_order' => $contentNodeDOMNode->attributeValue( 'sort-order' ) );

        if ( $parentNodeID == -1 && $parentNodeRemoteID )
        {
            if ( !isset( $options['suspended-nodes'] ) )
            {
                $options['suspended-nodes'] = array();
            }

            $options['suspended-nodes'][$parentNodeRemoteID] = array( 'nodeinfo' => $nodeInfo,
                                                                      'priority' => $contentNodeDOMNode->attributeValue( 'priority' ) );
            return true;
        }

        $existNodeAssignment = eZPersistentObject::fetchObject( eZNodeAssignment::definition(),
                                                   null,
                                                   $nodeInfo );
        $nodeInfo['priority'] = $contentNodeDOMNode->attributeValue( 'priority' );
        if( !is_object( $existNodeAssignment ) )
        {
            $nodeAssignment = eZNodeAssignment::create( $nodeInfo );
            $nodeList[] = $nodeInfo;
            $nodeAssignment->store();
        }

        return true;
    }

    /*!
     Serialize ContentObjectTreeNode

     \params $options
     \params contentNodeIDArray
     \params topNodeIDArray
    */
    function serialize( $options, $contentNodeIDArray, $topNodeIDArray )
    {
        if ( $options['node_assignment'] == 'main' &&
             $this->attribute( 'main_node_id' ) != $this->attribute( 'node_id' ) )
        {
            return false;
        }
        if ( ! in_array( $this->attribute( 'node_id' ), array_keys( $contentNodeIDArray ) ) )
        {
            return false;
        }

        $nodeAssignmentNode = new eZDOMNode();
        $nodeAssignmentNode->setName( 'node-assignment' );
        if ( $this->attribute( 'main_node_id' ) == $this->attribute( 'node_id' ) )
        {
            $nodeAssignmentNode->appendAttribute( eZDOMDocument::createAttributeNode( 'is-main-node', 1 ) );
        }
        if( !in_array( $this->attribute( 'node_id'), $topNodeIDArray ) )
        {
            $parentNode = $this->attribute( 'parent' );
            $nodeAssignmentNode->appendAttribute( eZDOMDocument::createAttributeNode( 'parent-node-remote-id', $parentNode->attribute( 'remote_id' ) ) );
        }
        $nodeAssignmentNode->appendAttribute( eZDOMDocument::createAttributeNode( 'name', $this->attribute( 'name' ) ) );
        $nodeAssignmentNode->appendAttribute( eZDOMDocument::createAttributeNode( 'node-id', $this->attribute( 'node_id' ) ) );
        $nodeAssignmentNode->appendAttribute( eZDOMDocument::createAttributeNode( 'remote-id', $this->attribute( 'remote_id' ) ) );
        $nodeAssignmentNode->appendAttribute( eZDOMDocument::createAttributeNode( 'sort-field', eZContentObjectTreeNode::sortFieldName( $this->attribute( 'sort_field' ) ) ) );
        $nodeAssignmentNode->appendAttribute( eZDOMDocument::createAttributeNode( 'sort-order', $this->attribute( 'sort_order' ) ) );
        $nodeAssignmentNode->appendAttribute( eZDOMDocument::createAttributeNode( 'priority', $this->attribute( 'priority' ) ) );
        return $nodeAssignmentNode;
    }

    /*!
     Update and store modified_subnode value for this node and all super nodes.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function updateAndStoreModified()
    {
        $pathArray = explode( '/', $this->attribute( 'path_string' ) );
        $sql = '';

        for( $pathCount = 1; $pathCount < count( $pathArray ) - 1; ++$pathCount )
        {
            $sql .= ( $pathCount != 1 ? 'OR ' : '' ) . 'node_id=\'' . $pathArray[$pathCount] . '\' ';
        }

        if ( $sql != '' )
        {
            $sql = 'UPDATE ezcontentobject_tree SET modified_subnode=' . time() .
                 ' WHERE ' . $sql;
            $db =& eZDB::instance();
            $db->query( $sql );
        }
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function store()
    {
        $db =& eZDB::instance();

        $db->begin();
        eZPersistentObject::storeObject( $this );
        $this->updateAndStoreModified();
        $db->commit();
    }

    function &object()
    {
        if ( $this->hasContentObject() )
        {
            return $this->ContentObject;
        }
        $contentobject_id = $this->attribute( 'contentobject_id' );
        $obj =& eZContentObject::fetch( $contentobject_id );
        $obj->setCurrentLanguage( $this->CurrentLanguage );
        $this->ContentObject =& $obj;
        return $obj;
    }

    function hasContentObject()
    {
        if ( isset( $this->ContentObject ) && get_class( $this->ContentObject ) == "ezcontentobject" )
            return true;
        else
            return false;
    }

    /*!
     Sets the current content object for this node.
    */
    function setContentObject( $object )
    {
        $this->ContentObject =& $object;
    }

    /*!
     \return the creator of the version published in the node.
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &creator()
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
        $version = eZContentObjectVersion::fetchVersion( $this->ContentObjectVersion, $this->ContentObjectID, $asObject );
        if ( $this->CurrentLanguage != false )
        {
            $version->CurrentLanguage = $this->CurrentLanguage;
        }
        return $version;
    }

    function &urlAlias()
    {
        $useURLAlias =& $GLOBALS['eZContentObjectTreeNodeUseURLAlias'];
        $ini =& eZINI::instance();
        $cleanURL = '';
        if ( !isset( $useURLAlias ) )
        {
            $useURLAlias = $ini->variable( 'URLTranslator', 'Translation' ) == 'enabled';
        }
        if ( $useURLAlias )
        {
            if ( $ini->hasVariable( 'SiteAccessSettings', 'PathPrefix' ) &&
                 $ini->variable( 'SiteAccessSettings', 'PathPrefix' ) != '' )
            {
                $prepend = $ini->variable( 'SiteAccessSettings', 'PathPrefix' );
                $pathIdenStr = substr( $prepend, strlen( $prepend ) -1 ) == '/'
                                ? $this->PathIdentificationString . '/'
                                : $this->PathIdentificationString;
                if ( strncmp( $pathIdenStr, $prepend, strlen( $prepend ) ) == 0 )
                    $cleanURL = eZUrlAlias::cleanURL( substr( $this->PathIdentificationString, strlen( $prepend ) ) );
                else
                    $cleanURL = eZUrlAlias::cleanURL( $this->PathIdentificationString );
            }
            else
            {
                $cleanURL = eZUrlAlias::cleanURL( $this->PathIdentificationString );
            }
        }
        else
        {
            $cleanURL = eZUrlAlias::cleanURL( 'content/view/full/' . $this->NodeID );
        }

        return $cleanURL;
    }

    function &url()
    {
        $ini =& eZINI::instance();
        if ( $ini->variable( 'URLTranslator', 'Translation' ) == 'enabled' )
        {
            $url =& $this->urlAlias();
        }
        else
        {
            $url = 'content/view/full/' . $this->NodeID;
        }

        return $url;
    }


    /*!
     \return the cached value of the class identifier if it exists, it not it's fetched dynamically
    */
    function &classIdentifier()
    {
        $identifier = '';
        if ( $this->ClassIdentifier !== null )
        {
            $identifier = $this->ClassIdentifier;
        }
        else
        {
            $object = $this->object();
            $class = $object->contentClass();
            $identifier = $class->attribute( 'identifier' );
        }

        return $identifier;
    }

    /*!
     \return the cached value of the class name if it exists, it not it's fetched dynamically
    */
    function &className()
    {
        $name = "";
        if ( $this->ClassName !== null )
        {
            $name = $this->ClassName;
        }
        else
        {
            $object = $this->object();
            $class = $object->contentClass();
            $name = $class->attribute( 'name' );
        }

        return $name;
    }

    /*!
    \return combined string representation of both "is_hidden" and "is_invisible" attributes
    Used in the node view templates.
    FIXME: this method probably should be removed in the future.
    */
    function &hiddenInvisibleString()
    {
        $retValue = ( $this->IsHidden ? 'H' : '-' ) . '/' . ( $this->IsInvisible ? 'X' : '-' );
        return $retValue;
    }

    /*!
    \return combined string representation of both "is_hidden" and "is_invisible" attributes
    Used in the limitation handling templates.
    */
    function &hiddenStatusString()
    {
        if( $this->IsHidden )
            $retVal = ezi18n( 'kernel/content', 'Hidden' );
        else if( $this->IsInvisible )
            $retVal = ezi18n( 'kernel/content', 'Hidden by superior' );
        else
            $retVal = ezi18n( 'kernel/content', 'Visible' );

        return $retVal;
    }

    /*!
     \a static

     \param $node            Root node of the subtree
     \param $modifyRootNode  Whether to modify the root node (true/false)

     Hide algorithm:
     if ( root node of the subtree is visible )
     {
        1) Mark root node as hidden and invisible
        2) Recursively mark child nodes as invisible except for ones which have been previously marked as invisible
     }
     else
     {
        Mark root node as hidden
     }

     In some cases we don't want to touch the root node when (un)hiding a subtree, for example
     after content/move or content/copy.
     That's why $modifyRootNode argument is used.

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function hideSubTree( &$node, $modifyRootNode = true )
    {
        $nodeID = $node->attribute( 'node_id' );
        $db     =& eZDB::instance();

        if ( !$node->attribute( 'is_invisible' ) ) // if root node is visible
        {
            $db->begin();

            // 1) Mark root node as hidden and invisible.
            if ( $modifyRootNode )
                $db->query( "UPDATE ezcontentobject_tree SET is_hidden=1, is_invisible=1 WHERE node_id=$nodeID" );

            // 2) Recursively mark child nodes as invisible, except for ones which have been previously marked as invisible.
            $nodePath = $node->attribute( 'path_string' );
            $db->query( "UPDATE ezcontentobject_tree SET is_invisible=1 WHERE is_invisible=0 AND path_string LIKE '$nodePath%'" );

            $db->commit();
        }
        else
        {
            // Mark root node as hidden
            if ( $modifyRootNode )
                $db->query( "UPDATE ezcontentobject_tree SET is_hidden=1 WHERE node_id=$nodeID" );
        }

        eZContentObjectTreeNode::clearViewCacheForSubtree( $node, $modifyRootNode );
    }

    /*!
     \a static

     \param $node            Root node of the subtree
     \param $modifyRootNode  Whether to modify the root node (true/false)

     Unhide algorithm:
     if ( parent node is visible )
     {
        1) Mark root node as not hidden and visible.
        2) Recursively mark child nodes as visible (except for nodes previosly marked as hidden, and all their children).
     }
     else
     {
        Mark root node as not hidden.
     }

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function unhideSubTree( &$node, $modifyRootNode = true )
    {
        $nodeID        = $node->attribute( 'node_id' );
        $nodePath      = $node->attribute( 'path_string' );
        $nodeInvisible = $node->attribute( 'is_invisible' );
        $parentNode    =& $node->attribute( 'parent' );
        $db            =& eZDB::instance();


        if ( ! $parentNode->attribute( 'is_invisible' ) ) // if parent node is visible
        {
            $db->begin();
            // 1) Mark root node as not hidden and visible.
            if ( $modifyRootNode )
                $db->query( "UPDATE ezcontentobject_tree SET is_invisible=0, is_hidden=0 WHERE node_id=$nodeID" );

            // 2) Recursively mark child nodes as visible (except for nodes previosly marked as hidden, and all their children).

            // 2.1) $hiddenChildren = Fetch all hidden children for the root node
            $hiddenChildren = $db->arrayQuery( "SELECT path_string FROM ezcontentobject_tree " .
                                                "WHERE node_id <> $nodeID AND is_hidden=1 AND path_string LIKE '$nodePath%'" );
            $skipSubtreesString = '';
            foreach ( $hiddenChildren as $i )
                $skipSubtreesString .= " AND path_string NOT LIKE '" . $i['path_string'] . "%'";

            // 2.2) Mark those children as visible which are not under nodes in $hiddenChildren
            $db->query( "UPDATE ezcontentobject_tree SET is_invisible=0 WHERE path_string LIKE '$nodePath%' $skipSubtreesString" );
            $db->commit();
        }
        else
        {
            // Mark root node as not hidden.
            if ( $modifyRootNode )
                $db->query( "UPDATE ezcontentobject_tree SET is_hidden=0 WHERE node_id=$nodeID" );
        }

        eZContentObjectTreeNode::clearViewCacheForSubtree( $node, $modifyRootNode );
    }

    /*!
     \a static
     Depending on the new parent node visibility, recompute "is_invisible" attribute for the given node and its children.
     (used after content/move or content/copy)
    */
    function updateNodeVisibility( &$node, &$parentNode, $recursive = true )
    {
        if ( !$node )
        {
            eZDebug::writeWarning( 'No such node to update visibility for.' );
            return;
        }

        if ( !$parentNode )
        {
            eZDebug::writeWarning( 'No parent node found when updating node visibility' );
            return;
        }

        if ( $node->attribute( 'is_hidden' ) == 0 &&
             $parentNode->attribute( 'is_invisible' ) != $node->attribute( 'is_invisible' ) )
        {
            $parentNodeIsVisible = $parentNode->attribute( 'is_invisible' );
            $nodeID                 = $node->attribute( 'node_id' );
            $db                     =& eZDB::instance();
            $db->begin();
            $db->query( "UPDATE ezcontentobject_tree SET is_invisible=$parentNodeIsVisible WHERE node_id=$nodeID" );

            if ( $recursive )
            {
                // update visibility for children of the node
                if( $parentNodeIsVisible )
                    eZContentObjectTreeNode::hideSubTree( $node, $modifyRootNode = false );
                else
                    eZContentObjectTreeNode::unhideSubTree( $node, $modifyRootNode = false );
            }
            $db->commit();
        }
    }

    /*!
     \a static
     \return true on success, false otherwise
    */
    function clearViewCacheForSubtree( &$node, $clearForRootNode = true )
    {
        include_once( 'kernel/classes/ezcontentcachemanager.php' );

        // Max nodes to fetch at a time
        static $limit = 50;

        if ( !$node )
        {
            eZDebug::writeWarning( "No such subtree to clear view cache for" );
            return false;
        }

        if ( $clearForRootNode )
        {
            $objectID = $node->attribute( 'contentobject_id' );
            eZContentCacheManager::clearContentCacheIfNeeded( $objectID );
        }

        $offset = 0;
        $params = array( 'AsObject' => false,
                         'Depth' => false,
                         'Limitation' => array() ); // Empty array means no permission checking
        $subtreeCount = $node->subTreeCount( $params );
        while ( $offset < $subtreeCount )
        {
            $params['Offset'] = $offset;
            $params['Limit'] = $limit;

            $subtreeChunk =& $node->subTree( $params );
            $nNodesInChunk = count( $subtreeChunk );
            $offset += $nNodesInChunk;
            if ( $nNodesInChunk == 0 )
                break;

            $objectIDList = array();
            foreach ( $subtreeChunk as $curNode )
                $objectIDList[] = $curNode['contentobject_id'];
            $objectIDList = array_unique( $objectIDList );
            unset( $subtreeChunk );

            foreach ( $objectIDList as $objectID )
                eZContentCacheManager::clearContentCacheIfNeeded( $objectID );
        }

        return true;
    }

    function setVersionByObjectID( $objectID, $newVersion )
    {
        $db =& eZDB::instance();
        $db->query( "UPDATE ezcontentobject_tree SET contentobject_version='$newVersion' WHERE contentobject_id='$objectID'" );
    }

    function currentLanguage()
    {
        return $this->CurrentLanguage;
    }

    function setCurrentLanguage( $languageCode )
    {
        $this->CurrentLanguage = $languageCode;
        if ( $this->hasContentObject() )
        {
            $this->ContentObject->setCurrentLanguage( $languageCode );
        }
        $this->Name = null;
    }

    /*!
     \static
    */
    function parentDepthLimitationList()
    {
        $maxLevel = 0;
        $ini =& eZINI::instance();
        if ( $ini->hasVariable( 'RoleSettings', 'MaxParentDepthLimitation' ) )
            $maxLevel = $ini->variable( 'RoleSettings', 'MaxParentDepthLimitation' );

        $depthArray = array();
        for ( $i = 1; $i <= $maxLevel; $i++ )
        {
            $depthArray[] = array( 'name' => $i, 'id' => $i );
        }

        return $depthArray;
    }

    /*
      Returns available classes as Js array.
      Checks if the node is container, if yes emptyStr will be returned.
    */
    function &availableClassesJsArray()
    {
        $classList = eZContentObjectTreeNode::availableClassListJsArray( array( 'node' => &$this ) );
        return $classList;
    }

    /*
      Returns available classes as Js array.
      Checks for ini settings.
    */
    function availableClassListJsArray( $parameters = false )
    {
        $iniMenu =& eZINI::instance( 'contentstructuremenu.ini' );
        $falseValue = "''";

        if ( $iniMenu->hasVariable( 'TreeMenu', 'CreateHereMenu' ) )
        {
            $createHereMenu = $iniMenu->variable( 'TreeMenu', 'CreateHereMenu' );
        }
        else
        {
            $createHereMenu = 'simplified';
        }
        if ( $createHereMenu != 'simplified' and $createHereMenu != 'full' )
            return $falseValue;

        $ini =& eZINI::instance( 'content.ini' );
        list( $usersClassGroupID, $setupClassGroupID ) = $ini->variableMulti( 'ClassGroupIDs', array( 'Users', 'Setup' ) );
        $userRootNode = $ini->variable( 'NodeSettings', 'UserRootNode' );
        $groupIDs = false;
        $filterType = 'include';

        if ( !is_array( $parameters ) )
            return $falseValue;

        $node = isset( $parameters['node'] ) ? $parameters['node'] : false;
        if ( is_object( $node ) )
        {
            if ( $createHereMenu == 'full' and !$node->canCreate() )
                return $falseValue;

            $obj =& $node->object();
            $contentClass =& $obj->attribute( 'content_class' );
            if ( !$contentClass->attribute( 'is_container' ) )
                return $falseValue;

            $pathArray = $node->pathArray();
        }
        else
        {
            // If current object is not container we should not return class list, should not display "create here" menu.
            if ( isset( $parameters['is_container'] ) and !$parameters['is_container'] )
                return $falseValue;

            // Check if current user can create under this node
            if ( $createHereMenu == 'full' and isset( $parameters['node_id'] ) )
            {
                $node =& eZContentObjectTreeNode::fetch( $parameters['node_id'] );
                if ( is_object( $node ) and !$node->canCreate() )
                    return $falseValue;
            }
            $pathString = isset( $parameters['path_string'] ) ? $parameters['path_string'] : false;
            if ( !$pathString )
                return $falseValue;

            $pathItems = explode( '/', $pathString );
            $pathArray = array();
            foreach ( $pathItems as $pathItem )
            {
                if ( $pathItem != '' )
                    $pathArray[] = (int) $pathItem;
            }
        }

        if ( in_array( $userRootNode, $pathArray ) )
        {
            $groupIDs = array( $usersClassGroupID );
        }
        else
        {
            $groupIDs = array( $usersClassGroupID, $setupClassGroupID );
            $filterType = 'exclude';
        }

        if ( $createHereMenu == 'simplified' )
        {
            include_once( "kernel/classes/ezcontentclass.php" );
            $classes =& eZContentClass::fetchAllClasses( false, $filterType == 'include', $groupIDs );
            $classList = eZContentObjectTreeNode::getClassesJsArray( false, $filterType == 'include', $groupIDs, false, $classes );
            return $classList;
        }

        $classList = eZContentObjectTreeNode::getClassesJsArray( $node, $filterType == 'include', $groupIDs );
        return $classList;
    }

    /*
      Returns available classes as Js array.
      \note building js array.
    */
    function getClassesJsArray( $node = false, $includeFilter = true, $groupList = false, $fetchID = false, $classes = false )
    {
        include_once( 'kernel/classes/ezcontentclass.php' );
        $falseValue = "''";
        // If $classes is false we should check $node and fetch class list
        if ( $classes === false )
        {
            // If $node is object we should fetch available classes from node, from ezcontentclass otherwise
            $classes = ( is_object( $node ) and get_class( $node ) == 'ezcontentobjecttreenode' )
                        ? $node->canCreateClassList( false, $includeFilter, $groupList, $fetchID )
                        : eZContentClass::canInstantiateClassList( false, $includeFilter, $groupList, $fetchID );
        }
        if ( !is_array( $classes ) )
            return $falseValue;

        // Create javascript array
        $jsArray = array();
        $db =& eZDB::instance();
        foreach ( $classes as $class )
        {
            if ( is_object( $class ) )
            {
                $classID = $class->attribute( 'id' );
                $className = $class->attribute( 'name' );
            }
            elseif ( is_array( $class ) )
            {
                $classID = $class['id'];
                $className = $class['name'];
            }
            $jsArray[] = "{ classID: '" . $db->escapeString( $classID ) .
                          "', name: '" . $db->escapeString( $className ) . "' }";
        }

        if ( $jsArray )
            return '[ '.implode( ', ', $jsArray ).' ]';

        return $falseValue;
    }


    /// The current language for the node
    var $CurrentLanguage = false;

    /// Name of the node
    var $Name;

    /// Contains the cached value of the class identifier
    var $ClassIdentifier = null;
    var $ClassName = null;
}

?>
