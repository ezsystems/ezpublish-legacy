<?php
//
// Definition of eZContentObjectTreeNode class
//
// Created on: <10-Jul-2002 19:28:22 sp>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
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

    function &definition()
    {
        return array( "fields" => array( "node_id" => array( 'name' => "NodeID",
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         "parent_node_id" => array( 'name' => "ParentNodeID",
                                                                    'datatype' => 'integer',
                                                                    'default' => 0,
                                                                    'required' => true ),
                                         "main_node_id" => array( 'name' => "MainNodeID",
                                                                  'datatype' => 'integer',
                                                                  'default' => 0,
                                                                  'required' => true ),
                                         "contentobject_id" => array( 'name' => "ContentObjectID",
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true ),
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
                                                               'required' => true ) ),

                      "keys" => array( "node_id" ),
                      "function_attributes" => array( "name" => "getName",
                                                      'data_map' => 'dataMap',
                                                      "object" => "object",
                                                      "subtree" => "subTree",
                                                      "children" => "children",
                                                      "children_count" => "childrenCount",
                                                      'contentobject_version_object' => 'contentObjectVersionObject',
                                                      'sort_array' => 'sortArray',
                                                      'can_read' => 'canRead',
                                                      'can_create' => 'canCreate',
                                                      'can_edit' => 'canEdit',
                                                      'can_remove' => 'canRemove',
                                                      'can_move' => 'canMove',
                                                      'creator' => 'creator',
                                                      "path" => "fetchPath",
                                                      'path_array' => 'pathArray',
                                                      "parent" => "fetchParent",
                                                      'url' => 'url',
                                                      'url_alias' => 'urlAlias',
                                                      'class_identifier' => 'classIdentifier',
                                                      'class_name' => 'className'
                                                      ),
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
    function &create( $parentNodeID = null, $contentObjectID = null, $contentObjectVersion = 0,
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
                      'modified_subnode' => 0,
                      'remote_id' => md5( (string)mt_rand() . (string)mktime() ),
                      'priority' => 0 );
        $node =& new eZContentObjectTreeNode( $row );
        return $node;
    }

    /*!
     Will call the function remoteID() if \c 'remote_id' is passed as \a $attr.
     If not it lets eZPersistentObject::attribute() do the rest.
    */
    function &attribute( $attr )
    {
        if ( $attr == 'remote_id' )
        {
            return $this->remoteID();
        }
        else
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

    /*!
     Get remote id of content node, the remote ID is often used to synchronise imports and exports.
     If there is no remote ID a new unique one will be generated.
    */
    function remoteID()
    {
        $remoteID = eZPersistentObject::attribute( 'remote_id' );
        if ( !$remoteID )
        {
            $this->setAttribute( 'remote_id', md5( (string)mt_rand() . (string)mktime() ) );
            $this->sync( array( 'remote_id' ) );
            $remoteID = eZPersistentObject::attribute( 'remote_id' );
        }

        return $remoteID;
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
            $var =& $phpCache->restore( array( 'identifierHash' => 'identifier_hash' ) );
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

        include_once( 'lib/ezutils/classes/ezphpcreator.php' );
        $cacheDir = eZSys::cacheDirectory();
        $phpCache = new eZPHPCreator( "$cacheDir", "classidentifiers_$dbName.php" );

        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler =& eZExpiryHandler::instance();
        $expiryTime = 0;
        if ( $handler->hasTimestamp( 'content-view-cache' ) )
        {
            $expiryTime = $handler->timestamp( 'content-view-cache' );
        }

        if ( $phpCache->canRestore( $expiryTime ) )
        {
            $var =& $phpCache->restore( array( 'identifierHash' => 'identifier_hash' ) );
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
    */
    function canRead( )
    {
        if ( !isset( $this->Permissions["can_read"] ) )
        {
            $this->Permissions["can_read"] = $this->checkAccess( 'read' );
        }
        $p = ( $this->Permissions["can_read"] == 1 );
        return $p;
    }

    /*!
     \return \c true if the node can be edited by the current user.
     \sa checkAccess().
    */
    function canEdit( )
    {
        if ( !isset( $this->Permissions["can_edit"] ) )
        {
            $this->Permissions["can_edit"] = $this->checkAccess( 'edit' );
        }
        $p = ( $this->Permissions["can_edit"] == 1 );
        return $p;
    }

    /*!
     \return \c true if the current user can create a new node as child of this node.
     \sa checkAccess().
    */
    function canCreate( )
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
    */
    function canRemove( )
    {
        if ( !isset( $this->Permissions["can_remove"] ) )
        {
            $this->Permissions["can_remove"] = $this->checkAccess( 'remove' );
        }
        $p = ( $this->Permissions["can_remove"] == 1 );
        return $p;
    }

    /*!
     \return \c true if the node can be moved by the current user.
     \sa checkAccess().
    */
    function canMove( )
    {
        if ( !isset( $this->Permissions["can_move"] ) )
        {
            $this->Permissions["can_move"] = $this->checkAccess( 'move' );
        }
        $p = ( $this->Permissions["can_move"] == 1 );
        return $p;
    }

    /*!
     \returns the sort key for the given classAttributeID.
      int|string is returend. False is returned if unsuccessful.
    */
    function sortKeyByClassAttributeID( $classAttributeID )
    {
        $db =& eZDB::instance();
        $dbName = $db->DB;

        include_once( 'lib/ezutils/classes/ezphpcreator.php' );
        $cacheDir = eZSys::cacheDirectory();
        $phpCache = new eZPHPCreator( "$cacheDir", "sortkey_$dbName.php" );

        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler =& eZExpiryHandler::instance();
        $expiryTime = 0;
        if ( $handler->hasTimestamp( 'content-view-cache' ) )
        {
            $expiryTime = $handler->timestamp( 'content-view-cache' );
        }

        if ( $phpCache->canRestore( $expiryTime ) )
        {
            $vars =& $phpCache->restore( array( 'datatype_array' => 'datatypeArray',
                                                'attribute_type_array' => 'attributeTypeArray' ) );
            $dataTypeArray =& $vars['datatype_array'];
            $attributeTypeArray =& $vars['attribute_type_array'];
        }
        else
        {
            // Fetch all datatypes and id's used
            $query = "SELECT id, data_type_string FROM ezcontentclass_attribute";
            $attributeArray = $db->arrayQuery( $query );

            $attributeTypeArray = array();
            $dataTypeArray = array();
            foreach ( $attributeArray as $attribute )
            {
                $attributeTypeArray[$attribute['id']] = $attribute['data_type_string'];
                $dataTypeArray[$attribute['data_type_string']] = 0;
            }

            include_once( 'kernel/classes/ezdatatype.php' );

            // Fetch datatype for every unique datatype
            foreach ( array_keys( $dataTypeArray ) as $key )
            {
                unset( $dataType );
                $datatype =& eZDataType::create( $key );

                $dataTypeArray[$key] = $datatype->sortKeyType();
            }
            unset( $dataType );

            // Store identifier list to cache file
            $phpCache->addVariable( 'datatypeArray', $dataTypeArray );
            $phpCache->addVariable( 'attributeTypeArray', $attributeTypeArray );
            $phpCache->store();
        }
        return $dataTypeArray[$attributeTypeArray[$classAttributeID]];
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
    function &createSortingSQLStrings( $sortList )
    {
        $sortingInfo = array( 'sortCount'           => 0,
                              'sortingFields'       => " path_string ASC",
                              'attributeJoinCount'  => 0,
                              'attributeFromSQL'    => "",
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

            foreach ( $sortList as $sortBy )
            {
                if ( is_array( $sortBy ) and count( $sortBy ) > 0 )
                {
                    if ( $sortCount > 0 )
                    {
                        $sortingInfo['sortingFields'] .= ', ';
                    }

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
                        case 'attribute':
                        {
                            $sortClassID = $sortBy[2];
                            if ( !is_numeric( $sortClassID ) )
                                $sortClassID = eZContentObjectTreeNode::classAttributeIDByIdentifier( $sortClassID );

                            // Look up datatype for sorting
                            $sortDataType = eZContentObjectTreeNode::sortKeyByClassAttributeID( $sortClassID );

                            $sortKey = false;
                            if ( $sortDataType == 'string' )
                            {
                                $sortKey = 'sort_key_string';
                            }
                            else
                            {
                                $sortKey = 'sort_key_int';
                            }
                            $sortingFields .= "a$attributeJoinCount.$sortKey";
                            $attributeFromSQL .= ", ezcontentobject_attribute a$attributeJoinCount";
                            $attributeWhereSQL .= "
                                   a$attributeJoinCount.contentobject_id = ezcontentobject.id AND
                                   a$attributeJoinCount.contentclassattribute_id = $sortClassID AND
                                   a$attributeJoinCount.version = ezcontentobject_name.content_version AND
                                   a$attributeJoinCount.language_code = ezcontentobject_name.real_translation AND ";

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

            $sortingInfo['sortCount']           =  $sortCount;
            $sortingInfo['sortingFields']       =& $sortingFields;
            $sortingInfo['attributeJoinCount']  =  $attributeJoinCount;
            $sortingInfo['attributeFromSQL']    =& $attributeFromSQL;
            $sortingInfo['attributeWhereSQL']   =& $attributeWhereSQL;
        }

        return $sortingInfo;
    }

    /*!
        \a static
    */
    function &createClassFilteringSQLString( $classFilterType, &$classFilterArray )
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
                    eZDebug::writeError("Invalid class identifier in subTree() classfilterarray, classID : " . $originalClassID );
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
        }

        return $classCondition;
    }

    /*!
        \a static
    */
    function &createExtendedAttributeFilterSQLStrings( &$extendedAttributeFilter )
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

            eZDebug::writeDebug( $filter['joins'], 'extendedAttributeFilterJoins' );
        }

        return $filter;
    }

    /*!
        \a static
    */
    function &createMainNodeConditionSQLString( $mainNodeOnly )
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
    function &createAttributeFilterSQLStrings( &$attributeFilter, &$sortingInfo )
    {
        // Check for attribute filtering

        $filterSQL = array( 'from'    => '',
                            'where'   => '' );

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
                foreach ( $filterArray as $filter )
                {
                    $filterAttributeID = $filter[0];
                    $filterType = $filter[1];
                    $filterValue = $db->escapeString( $filter[2] );

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
                            $filterField = 'ezcontentclass.name';
                        } break;
                        case 'priority':
                        {
                            $filterField = 'ezcontentobject_tree.priority';
                        } break;
                        case 'name':
                        {
                            $filterField = 'ezcontentobject_name.name';
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

                        // Use the same joins as we do when sorting,
                        // if more attributes are filtered by we will append them
                        if ( $filterCount >= $sortingInfo['attributeJoinCount'] )
                        {
                            $filterSQL['from']  .= ", ezcontentobject_attribute a$filterCount ";
                            $filterSQL['where'] .= "
                               a$filterCount.contentobject_id = ezcontentobject.id AND
                               a$filterCount.contentclassattribute_id = $filterAttributeID AND
                               a$filterCount.version = ezcontentobject_name.content_version AND
                               a$filterCount.language_code = ezcontentobject_name.real_translation AND ";

                        }
                        else
                        {
                            $filterSQL['where'] .= "
                              a$filterCount.contentobject_id = ezcontentobject.id AND
                              a$filterCount.contentclassattribute_id = $filterAttributeID AND
                              a$filterCount.version = ezcontentobject_name.content_version AND
                              a$filterCount.language_code = ezcontentobject_name.real_translation AND ";
                        }

                        // Check datatype for filtering
                        //

                        $filterDataType = eZContentObjectTreeNode::sortKeyByClassAttributeID( $filterAttributeID );

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
                    }

                    $hasFilterOperator = true;
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
                        $attibuteFilterJoinSQL .= "$filterField $filterOperator '$filterValue' ";
                        $filterCount++;
                        $justFilterCount++;
                    }
                }
            }

            if ( $justFilterCount > 0 )
                $filterSQL['where'] .= "\n                            ( " . $attibuteFilterJoinSQL . " ) AND ";
        }

        return $filterSQL;
    }

    /*!
        \a static
    */
    function &createNotEqParentSQLString( $nodeID, $depth, $depthOperator )
    {
        $notEqParentString  = '';
        if( !$depth || !$depthOperator || $depthOperator != 'eq' )
        {
            $notEqParentString  = "node_id != $nodeID AND";
        }

        return $notEqParentString;
    }

    /*!
        \a static
    */
    function &createPathConditionSQLString( $nodePath, $nodeDepth, $depth, $depthOperator )
    {
        $pathCondition  = '';
        $depthCondition = '';

        if ( $depth )
        {
            $nodeDepth += $depth;
            if ( $depthOperator && $depthOperator == 'eq' )
            {
                $depthCondition = ' depth = '  . $nodeDepth . ' and ';
            }
            else
            {
                $depthCondition = ' depth <= ' . $nodeDepth . ' and ';
            }
        }

        $pathCondition = " path_string like '$nodePath%' and $depthCondition ";
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
                $node           =& eZContentObjectTreeNode::fetch( $nodeID );
                $nodePath       = $node->attribute( 'path_string' );
                $nodeDepth      = $node->attribute( 'depth' );
                $depthCond      = '';
                if ( $depth )
                {
                    $nodeDepth += $depth;
                    $depthCond = ' and depth = ' . $nodeDepth . ' ';
                }

                $outNotEqParentStr          = " and node_id != $nodeID ";
                $sqlPartForOneNodeList[]    = " ( path_string like '$nodePath%'   $depthCond $outNotEqParentStr ) ";
                $outNotEqParentStr          = '';
            }
            $outPathConditionStr = implode( ' or ', $sqlPartForOneNodeList );
            $outPathConditionStr = ' (' . $outPathConditionStr . ') and';
        }
        else
        {
            if ( $nodeID == 0 )
            {
                $nodeID = $treeNode->attribute( 'node_id' );
                $node   =& $treeNode;
            }
            else
            {
                $node   =& eZContentObjectTreeNode::fetch( $nodeID );
            }

            $nodePath   = $node->attribute( 'path_string' );
            $nodeDepth  = $node->attribute( 'depth' );

            $outNotEqParentStr   = eZContentObjectTreeNode::createNotEqParentSQLString( $nodeID, $depth, $depthOperator );
            $outPathConditionStr = eZContentObjectTreeNode::createPathConditionSQLString( $nodePath, $nodeDepth, $depth, $depthOperator );
        }
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
    function &createVersionNameTablesSQLString( $useVersionName )
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
    function &createVersionNameTargetsSQLString( $useVersionName )
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
    function &createVersionNameJoinsSQLString( $useVersionName )
    {
        $versionNameJoins = '';

        if ( $useVersionName )
        {
            $lang = eZContentObject::defaultLanguage();

            $versionNameJoins   = " and  ezcontentobject_tree.contentobject_id = ezcontentobject_name.contentobject_id and
                                    ezcontentobject_tree.contentobject_version = ezcontentobject_name.content_version and
                                    ezcontentobject_name.content_translation = '$lang' ";
        }

        return $versionNameJoins;
    }

    /*!
        \a static
    */
    function &createPermissionCheckingSQLString( &$limitationList )
    {
        $sqlPermissionCheckingString = '';

        $db =& eZDB::instance();

        if ( count( $limitationList ) > 0 )
        {
            $sqlParts = array();
            foreach( $limitationList as $limitationArray )
            {
                $sqlPartPart = array();

                foreach ( array_keys( $limitationArray ) as $ident )
                {
                    switch( $ident )
                    {
                        case 'Class':
                        {
                            $sqlPartPart[] = 'ezcontentobject.contentclass_id in (' . implode( ', ', $limitationArray[$ident] ) . ')';
                        } break;

                        case 'Section':
                        {
                            $sqlPartPart[] = 'ezcontentobject.section_id in (' . implode( ', ', $limitationArray[$ident] ) . ')';
                        } break;

                        case 'Owner':
                        {
                            $user   =& eZUser::currentUser();
                            $userID = $user->attribute( 'contentobject_id' );
                            $sqlPartPart[] = "ezcontentobject.owner_id = '" . $db->escapeString( $userID ) . "'";
                        } break;

                        case 'Node':
                        {
                            $sqlPartPart[] = 'ezcontentobject_tree.node_id in (' . implode( ', ', $limitationArray[$ident] ) . ')';
                        } break;

                        case 'Subtree':
                        {
                            $pathArray       =& $limitationArray[$ident];
                            $sqlPartPartPart = array();
                            foreach ( $pathArray as $limitationPathString )
                            {
                                $sqlPartPartPart[] = "ezcontentobject_tree.path_string like '$limitationPathString%'";
                            }
                            $sqlPartPart[] = implode( ' OR ', $sqlPartPartPart );
                        } break;
                    }
                }
                $sqlParts[] = implode( ' AND ', $sqlPartPart );
            }
            $sqlPermissionCheckingString = ' AND ((' . implode( ') or (', $sqlParts ) . ')) ';
        }

        return $sqlPermissionCheckingString;
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
    */
    function &getLimitationList( &$limitation )
    {
        $limitationList = array();

        if ( $limitation )
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
            if ( $accessResult['accessWord'] == 'limited' )
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
            return array();
        }

        if ( $params === false )
        {
            $params = array( 'Depth'                    => false,
                             'Offset'                   => false,
                             'Limit'                    => false,
                             'SortBy'                   => false,
                             'AttributeFilter'          => false,
                             'ExtendedAttributeFilter'  => false,
                             'ClassFilterType'          => false,
                             'ClassFilterArray'         => false,
                             'GroupBy'                  => false );
        }

        $offset         = ( isset( $params['Offset'] ) && is_numeric( $params['Offset'] ) ) ? $params['Offset']             : false;
        $limit          = ( isset( $params['Limit']  ) && is_numeric( $params['Limit']  ) ) ? $params['Limit']              : false;
        $depth          = ( isset( $params['Depth']  ) && is_numeric( $params['Depth']  ) ) ? $params['Depth']              : false;
        $depthOperator  = ( isset( $params['DepthOperator']     ) )                         ? $params['DepthOperator']      : false;
        $asObject       = ( isset( $params['AsObject']          ) )                         ? $params['AsObject']           : true;
        $groupBy        = ( isset( $params['GroupBy']           ) )                         ? $params['GroupBy']            : false;
        $mainNodeOnly   = ( isset( $params['MainNodeOnly']      ) )                         ? $params['MainNodeOnly']       : false;

        $sortingInfo             =& eZContentObjectTreeNode::createSortingSQLStrings( $params['SortBy'] );
        $classCondition          =& eZContentObjectTreeNode::createClassFilteringSQLString( $params['ClassFilterType'], $params['ClassFilterArray'] );
        $attributeFilter         =& eZContentObjectTreeNode::createAttributeFilterSQLStrings( $params['AttributeFilter'], $sortingInfo );
        $extendedAttributeFilter =& eZContentObjectTreeNode::createExtendedAttributeFilterSQLStrings( $params['ExtendedAttributeFilter'] );
        $mainNodeOnlyCond        =& eZContentObjectTreeNode::createMainNodeConditionSQLString( $mainNodeOnly );

        $pathStringCond     = '';
        $notEqParentString  = '';
        eZContentObjectTreeNode::createPathConditionAndNotEqParentSQLStrings( $pathStringCond, $notEqParentString, $this, $nodeID, $depth, $depthOperator );

        $groupBySelectText  = '';
        $groupByText        = '';
        eZContentObjectTreeNode::createGroupBySQLStrings( $groupBySelectText, $groupByText, $groupBy );

        $useVersionName     = true;
        $versionNameTables  =& eZContentObjectTreeNode::createVersionNameTablesSQLString ( $useVersionName );
        $versionNameTargets =& eZContentObjectTreeNode::createVersionNameTargetsSQLString( $useVersionName );
        $versionNameJoins   =& eZContentObjectTreeNode::createVersionNameJoinsSQLString  ( $useVersionName );

        $limitationList              =& eZContentObjectTreeNode::getLimitationList( $params['Limitation'] );
        $sqlPermissionCheckingString =& eZContentObjectTreeNode::createPermissionCheckingSQLString( $limitationList );

        $query = "SELECT ezcontentobject.*,
                       ezcontentobject_tree.*,
                       ezcontentclass.name as class_name,
                       ezcontentclass.identifier as class_identifier
                       $groupBySelectText
                       $versionNameTargets
                   FROM
                      ezcontentobject_tree,
                      ezcontentobject,ezcontentclass
                      $versionNameTables
                      $sortingInfo[attributeFromSQL]
                      $attributeFilter[from]
                      $extendedAttributeFilter[tables]
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
                      ezcontentobject_tree.contentobject_is_published = 1
                      $versionNameJoins
                      $sqlPermissionCheckingString
                $groupByText
                ORDER BY $sortingInfo[sortingFields]";


        $db =& eZDB::instance();

        if ( !$offset && !$limit )
        {
            $nodeListArray =& $db->arrayQuery( $query );
        }
        else
        {
            $nodeListArray =& $db->arrayQuery( $query, array( 'offset' => $offset,
                                                              'limit'  => $limit ) );
        }

        if ( $asObject )
            $retNodeList =& eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );
        else
            $retNodeList =& $nodeListArray;

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
                      'group_field' => 'groupbyfield' );
    }

    /*!
     Count number of subnodes

     \param params array
    */
    function subTreeCount( $params = array(), $nodeID = 0 )
    {
        if ( $nodeID == 0 )
        {
            $nodeID = $this->attribute( 'node_id' );
            $node = $this;
        }
        else if ( is_numeric( $nodeID ) )
        {
            $node = eZContentObjectTreeNode::fetch( $nodeID );
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
                $node = eZContentObjectTreeNode::fetch( $nodeID );
                $nodePath =  $node->attribute( 'path_string' );
                $nodeDepth = $node->attribute( 'depth' );
                $childrensPath = $nodePath ;
                $pathString = " path_string like '$childrensPath%' ";
                if ( isset( $params[ 'Depth' ] ) and $params[ 'Depth' ] > 0 )
                {
                    $nodeDepth += $params[ 'Depth' ];
                    $depthCond = ' and depth = ' . $nodeDepth . ' ';
                }
                else
                {
                    $depthCond = '';
                }

                $notEqParentString = " and node_id != $nodeID ";

                $sqlPartForOneNodeList[] = " ( path_string like '$childrensPath%'   $depthCond $notEqParentString ) ";
                $notEqParentString = '';
            }
            $pathStringCond = implode( ' or ', $sqlPartForOneNodeList );
            $pathStringCond = ' (' . $pathStringCond . ') and';
        }
        else
        {
            $fromNode = $nodeID ;

            $nodePath = null;
            $nodeDepth = 0;
            if ( count( $node ) != 0 )
            {
                $nodePath = $node->attribute( 'path_string' );
                $nodeDepth = $node->attribute( 'depth' );
            }

            $childrensPath = $nodePath ;
            $pathLength = strlen( $childrensPath );

            $db =& eZDB::instance();
            $subStringString = $db->subString( 'path_string', 1, $pathLength );
            $pathString = " path_string like '$childrensPath%' and ";

            $notEqParentString = "node_id != $fromNode AND";
            $depthCond = '';
            if ( $depth )
            {

                $nodeDepth += $params[ 'Depth' ];
                if ( isset( $params[ 'DepthOperator' ] ) && $params[ 'DepthOperator' ] == 'eq' )
                {
                    $depthCond = ' depth = ' . $nodeDepth . ' and ';
                    $notEqParentString = '';
                }
                else
                    $depthCond = ' depth <= ' . $nodeDepth . ' and ';
            }

            $pathStringCond = $pathString . $depthCond;
        }

        $pathString = $pathStringCond;

        //$nodeDepth = $node->attribute( 'depth' );
        $depthCond = '';

        // $notEqParentString = "node_id != $fromNode AND";

        $limitationList = array();
        if ( isset( $params['Limitation'] ) )
        {
            $limitationList =& $params['Limitation'];
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
            if ( $accessResult['accessWord'] == 'limited' )
            {
                $limitationList =& $accessResult['policies'];
                $GLOBALS['ezpolicylimitation_list']['content']['read'] =& $params['Limitation'];
            }
        }


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
                    eZDebug::writeError("Invalid class identifier in subTree() classfilterarray, classID : " . $originalClassID );
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
                foreach ( $filterArray as $filter )
                {
                    $filterAttributeID = $filter[0];
                    $filterType = $filter[1];
                    $filterValue = $filter[2];

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
                            $filterField = 'ezcontentclass.name';
                        } break;
                        case 'priority':
                        {
                            $filterField = 'ezcontentobject_tree.priority';
                        } break;
                        case 'name':
                        {
                            $filterField = 'ezcontentobject_name.name';
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

                        // Use the same joins as we do when sorting,
                        // if more attributes are filtered by we will append them
                        $attributeFilterFromSQL .= ", ezcontentobject_attribute a$filterCount ";
                        $attributeFilterWhereSQL .= "
                            a$filterCount.contentobject_id = ezcontentobject.id AND
                               a$filterCount.version = ezcontentobject.current_version AND
                               a$filterCount.contentclassattribute_id = $filterAttributeID AND
                               a$filterCount.language_code = '".eZContentObject::defaultLanguage()."' AND ";

                        // Check datatype for filtering
                        //

                        $filterDataType = eZContentObjectTreeNode::sortKeyByClassAttributeID( $filterAttributeID );

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

                    }

                    $hasFilterOperator = true;
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
                        $attibuteFilterJoinSQL .= "$filterField $filterOperator '$filterValue' ";
                        $filterCount++;
                    }
                }
            }

            if ( $filterCount > 0 )
                $attributeFilterWhereSQL .= "\n                            ( " . $attibuteFilterJoinSQL . " ) AND ";
        }

        $versionNameTables = '';
        $versionNameTargets = '';
        $versionNameJoins = '';

        if ( $limitationList !== false && count( $limitationList ) > 0 )
        {
            $sqlParts = array();

            foreach( $limitationList as $limitationArray )
            {
                $sqlPartPart = array();
                foreach ( array_keys( $limitationArray ) as $ident )
                {
                    switch( $ident )
                    {
                        case 'Class':
                        {
                            $sqlPartPart[] = 'ezcontentobject.contentclass_id in (' . implode( ', ', $limitationArray[$ident] ) . ')';
                        } break;

                        case 'Section':
                        {
                            $sqlPartPart[] = 'ezcontentobject.section_id in (' . implode( ', ', $limitationArray[$ident] ) . ')';
                        } break;

                        case 'Owner':
                        {
                            $user =& eZUser::currentUser();
                            $userID = $user->attribute( 'contentobject_id' );
                            $sqlPartPart[] = "ezcontentobject.owner_id = '" . $db->escapeString( $userID ) . "'";
                        } break;

                        case 'Node':
                        {
                            $sqlPartPart[] = 'ezcontentobject_tree.node_id in (' . implode( ', ', $limitationArray[$ident] ) . ')';
                        } break;

                        case 'Subtree':
                        {
                            $pathArray =& $limitationArray[$ident];
                            $sqlPartPartPart = array();
                            foreach ( $pathArray as $limitationPathString )
                            {
                                $sqlPartPartPart[] = "ezcontentobject_tree.path_string like '$limitationPathString%'";
                            }
                            $sqlPartPart[] = implode( ' OR ', $sqlPartPartPart );
                        } break;
                    }
                }
                $sqlParts[] = implode( ' AND ', $sqlPartPart );
            }

            $sqlPermissionCheckingString = ' AND ((' . implode( ') or (', $sqlParts ) . ')) ';

            $query = "SELECT count(*) as count
                      FROM
                           ezcontentobject_tree,
                           ezcontentobject,ezcontentclass
                           $versionNameTables
                           $attributeFilterFromSQL
                      WHERE $pathString
                            $depthCond
                            $mainNodeOnlyCond
                            $classCondition
                            $attributeFilterWhereSQL
                            ezcontentclass.version=0 AND
                            $notEqParentString
                            ezcontentobject_tree.contentobject_id = ezcontentobject.id  AND
                            ezcontentclass.id = ezcontentobject.contentclass_id
                            $versionNameJoins
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
                          $versionNameTables
                          $attributeFilterFromSQL
                    WHERE
                           $pathString
                           $depthCond
                           $mainNodeOnlyCond
                           $classCondition
                           $attributeFilterWhereSQL
                           ezcontentclass.version=0 AND
                           $notEqParentString
                           ezcontentobject_tree.contentobject_id = ezcontentobject.id AND
                           ezcontentclass.id = ezcontentobject.contentclass_id
                           $versionNameJoins ";
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
                                      'DepthOperator' => 'eq' ) );
//                                      'Limitation' => $limitationList
//                                      ) );
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
        return $this->subTreeCount( $params );
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
        return eZContentObjectTreeNode::sortArrayBySortFieldAndSortOrder( $this->attribute( 'sort_field' ),
                                                                          $this->attribute( 'sort_order' ) );
    }

    /*!
     \static
     \return an array which defines the sorting method for this node.
     The array will contain one element which is an array with sort field
     and sort order.
    */
    function &sortArrayBySortFieldAndSortOrder( $sortField, $sortOrder )
    {
        $sort = array( eZContentObjectTreeNode::sortFieldName( $sortField ),
                       $sortOrder );
        return array( $sort );
    }

    /*!
     Will assign a section to the current node and all child objects.
     Only main node assignments will be updated.
    */
    function assignSectionToSubTree( $nodeID, $sectionID, $oldSectionID = false )
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

        $filterPart = '';
        if ( $oldSectionID !== false )
        {
            $filterPart = " section_id = '$oldSectionID' and ";
        }

        $db->query( "UPDATE ezcontentobject SET section_id='$sectionID' WHERE $filterPart id IN ( $inSQL )" );
        $db->query( "UPDATE ezsearch_object_word_link SET section_id='$sectionID' WHERE $filterPart contentobject_id IN ( $inSQL )" );
    }

    /*!
     \static
     Updates the main node selection for the content object \a $objectID.

     \param $mainNodeID The ID of the node that should be that main node
     \param $objectID The ID of the object that all nodes belong to
     \param $version The version of the object to update node assignments, use \c false for currently published version.
     \param $parentMainNodeID The ID of the parent node of the current main placement
    */
    function updateMainNodeID( $mainNodeID, $objectID, $version = false, $parentMainNodeID )
    {
        $mainNodeID = (int)$mainNodeID;
        $parentMainNodeID = (int)$parentMainNodeID;
        $objectID = (int)$objectID;
        $version = (int)$version;

        $db =& eZDB::instance();
        $db->query( "UPDATE ezcontentobject_tree SET main_node_id=$mainNodeID WHERE contentobject_id=$objectID" );
        if ( !$version )
        {
            $rows = $db->arrayQuery( "SELECT current_version FROM ezcontentobject WHERE id=$objectID" );
            $version = $rows[0]['current_version'];
        }
        $db->query( "UPDATE eznode_assignment SET is_main=1 WHERE contentobject_id=$objectID AND contentobject_version=$version AND parent_node=$parentMainNodeID" );
        $db->query( "UPDATE eznode_assignment SET is_main=0 WHERE contentobject_id=$objectID AND contentobject_version=$version AND parent_node!=$parentMainNodeID" );
    }

    function &fetchByCRC( $pathStr )
    {
        eZDebug::writeWarning( "Obsolete: use ezurlalias instead", 'eZContentObjectTreeNode::fetchByCRC' );
    }

    function &fetchByContentObjectID( $contentObjectID, $asObject = true, $contentObjectVersion = false )
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

    function &fetchByRemoteID( $remoteID, $asObject = true )
    {
         return eZPersistentObject::fetchObject( eZContentObjectTreeNode::definition(),
                                                 null,
                                                 array( "remote_id" => $remoteID ),
                                                 $asObject );
    }

    function &fetchByPath( $pathString, $asObject = true )
    {
         return eZPersistentObject::fetchObject( eZContentObjectTreeNode::definition(),
                                                 null,
                                                 array( "path_string" => $pathString ),
                                                 $asObject );
    }

    function &fetchByURLPath( $pathString, $asObject = true )
    {
         return eZPersistentObject::fetchObject( eZContentObjectTreeNode::definition(),
                                                 null,
                                                 array( "path_identification_string" => $pathString ),
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
                          ezcontentclass.id = ezcontentobject.contentclass_id";
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

    /*!
      Fetches the main nodes for an array of object id's
    */
    function &findMainNodeArray( $objectIDArray, $asObject = true )
    {
        if ( count( $objectIDArray ) )
        {
            $objectIDString = implode( ',', $objectIDArray );
            $query="SELECT ezcontentobject.*,
                           ezcontentobject_tree.*,
                           ezcontentclass.name as class_name
                    FROM ezcontentobject_tree,
                         ezcontentobject,
                         ezcontentclass
                    WHERE ezcontentobject_tree.contentobject_id in ( $objectIDString ) AND
                          ezcontentobject_tree.main_node_id = ezcontentobject_tree.node_id AND
                          ezcontentobject_tree.contentobject_id=ezcontentobject.id AND
                          ezcontentclass.version=0  AND
                          ezcontentclass.id = ezcontentobject.contentclass_id";

            $db =& eZDB::instance();
            $nodeListArray =& $db->arrayQuery( $query );
            if ( $asObject )
            {
                $retNodeArray =& eZContentObjectTreeNode::makeObjectsArray( $nodeListArray );
                return $retNodeArray;
            }
            else
            {
                return $nodeListArray;
            }
        }

        return null;
    }

    function &fetch( $nodeID, $lang = false )
    {
        $returnValue = null;
        $ini =& eZINI::instance();
        $db =& eZDB::instance();

        $useVersionName = true;
        if ( $useVersionName )
        {
            $versionNameTables = ', ezcontentobject_name ';
            $versionNameTargets = ', ezcontentobject_name.name as name,  ezcontentobject_name.real_translation ';

            if ( $lang === false )
            {
                $lang = eZContentObject::defaultLanguage();
            }

            $versionNameJoins = " and  ezcontentobject_tree.contentobject_id = ezcontentobject_name.contentobject_id and
                                  ezcontentobject_tree.contentobject_version = ezcontentobject_name.content_version and
                                  ezcontentobject_name.content_translation = '$lang' ";
        }

        if ( $nodeID != '' && is_numeric( $nodeID ) )
        {
            if ( $nodeID != 1 )
            {
                $query="SELECT ezcontentobject.*,
                           ezcontentobject_tree.*,
                           ezcontentclass.name as class_name,
                           ezcontentclass.identifier as class_identifier
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

    /*!
     \static
     Finds the node for the object \a $contentObjectID which placed as child of node \a $parentNodeID.
     \return An eZContentObjectTreeNode object or \c null if no node was found.
    */
    function &fetchNode( $contentObjectID, $parentNodeID )
    {
        $returnValue = null;
        $ini =& eZINI::instance();
        $db =& eZDB::instance();
        $lang = eZContentObject::defaultLanguage();
        $query = "SELECT ezcontentobject_tree.*, ezcontentobject_name.name as name, ezcontentobject_name.real_translation
                  FROM ezcontentobject_tree, ezcontentobject_name
                  WHERE ezcontentobject_tree.contentobject_id = $contentObjectID AND
                        ezcontentobject_tree.parent_node_id = $parentNodeID  AND
                        ezcontentobject_tree.contentobject_id = ezcontentobject_name.contentobject_id AND
                        ezcontentobject_tree.contentobject_version = ezcontentobject_name.content_version AND
                        ezcontentobject_name.content_translation = '$lang'";

        $nodeListArray =& $db->arrayQuery( $query );
        if ( count( $nodeListArray ) == 1 )
        {
            $retNodeArray =& eZContentObjectTreeNode::makeObjectsArray( $nodeListArray, false );
            $returnValue = $retNodeArray[0];
        }
        return $returnValue;
    }

    function fetchParent()
    {
        return $this->fetch( $this->attribute( 'parent_node_id' ) );
    }

    function pathArray()
    {
        $pathString = $this->attribute( 'path_string' );
        $pathItems = explode( '/', $pathString );
        $pathArray = array();
        foreach ( $pathItems as $pathItem )
        {
            if ( $pathItem != '' )
                $pathArray[] = $pathItem;
        }
        return $pathArray;
    }


    function &fetchPath()
    {
        $nodePath = $this->attribute( 'path_string' );

        $retNodes =& eZContentObjectTreeNode::fetchNodesByPathString( $nodePath, false, true );

        return $retNodes;
    }

    /*!
     \static
     \return An array with content node objects that is present in the node path \a $nodePath.
     \param $withLastNode If \c true the last node in the path is included in the list.
                          The last node is the node which the path was fetched from.
     \param $asObjects If \c true then return PHP objects, if not return raw row data.
    */
    function &fetchNodesByPathString( $nodePath, $withLastNode = false, $asObjects = true )
    {
        $nodesListArray = array();
        $pathString =& eZContentObjectTreeNode::createNodesConditionSQLStringFromPath( $nodePath, $withLastNode );

        if ( $pathString  )
        {
            $useVersionName     = true;
            $versionNameTables  =& eZContentObjectTreeNode::createVersionNameTablesSQLString ( $useVersionName );
            $versionNameTargets =& eZContentObjectTreeNode::createVersionNameTargetsSQLString( $useVersionName );
            $versionNameJoins   =& eZContentObjectTreeNode::createVersionNameJoinsSQLString  ( $useVersionName );

            $query = "SELECT ezcontentobject.*,
                             ezcontentobject_tree.*,
                             ezcontentclass.name as class_name,
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
            $retNodes =& eZContentObjectTreeNode::makeObjectsArray( $nodesListArray );
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
        $nodes =& eZContentObjectTreeNode::fetchNodesByPathString( $nodePath, $withLastNode, false );

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

        $class =& eZContentClass::fetch( $contentClassID );
        $parentNode =& eZContentObjectTreeNode::fetch( $parentNodeID );
        $parentContentObject =& $parentNode->attribute( 'object' );
        $sectionID = $parentContentObject->attribute( 'section_id' );
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
        }
        else
        {
            $node =& eZContentObjectTreeNode::fetch( $nodeID );
        }

        $db =& eZDB::instance();
        $parentMainNodeID = $node->attribute( 'node_id' ); //$parent->attribute( 'main_node_id' );
        $parentPath = $node->attribute( 'path_string' );
        $parentDepth = $node->attribute( 'depth' );

        $nodeDepth = $parentDepth + 1 ;

        $insertedNode =& eZContentObjectTreeNode::create( $parentMainNodeID, $contentobjectID );
        $insertedNode->setAttribute( 'depth', $nodeDepth );
        $insertedNode->setAttribute( 'path_string', '/TEMPPATH' );
        $insertedNode->store();
        $insertedID = $insertedNode->attribute( 'node_id' );
        $newNodePath = $parentPath . $insertedID . '/';
        $insertedNode->setAttribute( 'path_string', $newNodePath );

        $insertedNode->store();
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
     \return a url alias for the current node. It will genereate a unique alias.
    */
    function pathWithNames( $nodeID = 0 )
    {
        if ( $nodeID == 0 )
        {
            $node =& $this;
        }
        else
        {
            $node =& eZContentObjectTreeNode::fetch( $nodeID );
        }

        $nodeList =& $node->attribute( 'path' );
        if ( $node->attribute( 'depth' ) > 1 )
        {
            $parentNodeID = $node->attribute( 'parent_node_id' );
            $parentNode = eZContentObjectTreeNode::fetch( $parentNodeID );
            if ( ! is_null( $parentNode ) )
            {
                $parentNodePathString = $parentNode->attribute( 'path_identification_string' );
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
        $depth = $this->attribute( 'depth' );
        $parentNodeID = $this->attribute( 'parent_node_id' );
        $nodeID = $this->attribute( 'node_id' );

        $db =& eZDB::instance();

		/* The two checks below don't use the return value from the query,
		 * but are just checks to see if there *is* a record */
        $sqlToCheckOriginalName = 'SELECT 1
                                   FROM ezcontentobject_tree
                                   WHERE  path_identification_string = \'' . $path . '\'
                                          AND node_id != ' . $nodeID;

        $retNode = $db->arrayQuery( $sqlToCheckOriginalName );
        if ( count( $retNode ) == 0 )
        {
            return $path;
        }
        $sqlToCheckCurrentName = 'SELECT 1
                                  FROM ezcontentobject_tree
                                  WHERE ( path_identification_string = \'' . $path . '\' OR
                                          path_identification_string like \'' . $path . '\\\_\\\_%\' )
                                          AND node_id = ' . $nodeID ;
        $retNode = $db->arrayQuery( $sqlToCheckCurrentName );
        if ( count( $retNode ) > 0 )
        {
            return $retNode[0]['path_identification_string'];
		}

        $sql = 'SELECT path_identification_string
                FROM ezcontentobject_tree
                WHERE parent_node_id = ' . $parentNodeID . ' AND
                      depth = ' . $depth . ' AND
                      ( path_identification_string = \'' . $path . '\' OR path_identification_string like \'' . $path . '\\\_\\\_%\' ) AND
                      node_id != ' . $nodeID ;
        $retNodes = $db->arrayQuery( $sql );
        if ( count( $retNodes ) > 0 )
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

    function updateURLAlias()
    {
        $hasChanged = 0;
        include_once( 'kernel/classes/ezurlalias.php' );
        $newPathString = $this->pathWithNames();

        $existingUrlAlias = eZURLAlias::fetchBySourceURL( $newPathString );
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
            $alias =& eZURLAlias::create( $newPathString, 'content/view/full/' . $this->NodeID );
            $alias->store();
            $hasChanged++;
        }

        eZURLAlias::cleanupForwardingURLs( $newPathString );
        eZURLAlias::cleanupWildcards( $newPathString );

        if ( $this->attribute( 'path_identification_string' ) != $newPathString )
            $hasChanged++;
        $this->setAttribute( 'path_identification_string', $newPathString );
        $this->store();

        return $hasChanged;
    }

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
            $alias =& eZURLAlias::create( $newPathString, 'content/view/full/' . $this->NodeID );
            $alias->store();
        }

        eZURLAlias::cleanupForwardingURLs( $newPathString );
        eZURLAlias::cleanupWildcards( $newPathString );

        $subNodeCount = $this->subTreeCount( array( 'Limitation' => array() ) );
        if ( $subNodeCount > 0 )
        {
            $wildcardAlias =& eZURLAlias::create( $oldPathString . '/*', $newPathString . '/{1}', true, false, EZ_URLALIAS_WILDCARD_TYPE_FORWARD );
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
        $url =& eZURL::urlByURL( "/" . $oldPathString );

        if ( $url )
        {
            $url->setAttribute( 'url', '/' . $newPathString );
            $url->store();
        }

        eZDebugSetting::writeDebug( 'kernel-content-treenode', $oldPathString .'  ' . strlen( $oldPathString ) . '  ' . $newPathString );
        $this->setAttribute( 'path_identification_string', $newPathString );
        $this->store();

        $oldPathStringLength = strlen( $oldPathString );
        $db =& eZDB::instance();
        $newPathStringText = $db->escapeString( $newPathString );
        $oldPathStringText = $db->escapeString( $oldPathString );
        $subStringQueryPart = $db->subString( 'path_identification_string', $oldPathStringLength + 1 );
        $newPathStringQueryPart = $db->concatString( array( "'$newPathStringText'", $subStringQueryPart ) );
        // Update children
        $sql = "UPDATE ezcontentobject_tree
SET
    path_identification_string = $newPathStringQueryPart
WHERE
    path_identification_string LIKE '$oldPathStringText/%'";

        $db->query( $sql );

        eZURLAlias::updateChildAliases( $newPathString, $oldPathString );

        eZURLAlias::expireWildcards();
    }

    /*!
      Removes the current node.
    */
    function remove( $nodeID = 0 )
    {
        include_once( "kernel/classes/ezrole.php" );
        include_once( "kernel/classes/ezpolicy.php" );
        include_once( "kernel/classes/ezpolicylimitation.php" );

        if ( $nodeID == 0 )
        {
            $node =& $this;
        }
        else
        {
            $node =& eZContentObjectTreeNode::fetch( $nodeID );
        }

        eZNodeAssignment::remove( $node->attribute( 'parent_node_id' ),
                                  $node->attribute( 'contentobject_id' ) );

        $nodePath = $node->attribute( 'path_string' );
        $childrensPath = $nodePath ;
        $pathLength = strlen( $childrensPath );

        $pathString = " path_string like '$childrensPath%' ";

        $db =& eZDB::instance();

        $subStringString = $db->subString( 'path_string', 1, $pathLength );

        $urlAlias = $node->attribute( 'url_alias' );

        $db->query( "DELETE FROM ezcontentobject_tree
                            WHERE $pathString OR
                            path_string = '$nodePath'" );

        // Clean up URL alias
        $urlObject =& eZURLAlias::fetchBySourceURL( $urlAlias );
        if ( $urlObject )
        {
            $urlObject->cleanup();
        }

        $ini =& eZINI::instance();
        // Clean up template cache bocks
        $templateBlockCacheEnabled = ( $ini->variable( 'TemplateSettings', 'TemplateCache' ) == 'enabled' );
        if ( $templateBlockCacheEnabled )
        {
            eZContentObject::expireTemplateBlockCache();
        }

        // Clean up content view cache
        $viewCacheEnabled = ( $ini->variable( 'ContentSettings', 'ViewCaching' ) == 'enabled' );
        if ( $viewCacheEnabled )
        {
            include_once( 'kernel/classes/ezcontentcache.php' );
            eZContentCache::cleanup( array( $node->attribute( 'parent_node_id' ), $node->attribute( 'node_id' ) ) );
        }

        // Clean up policies and limitations
        $limitationsToFix =& eZPolicyLimitation::findByType( 'SubTree', $node->attribute( 'path_string' ) );
        foreach( $limitationsToFix as $limitation )
        {

            $values =& $limitation->attribute( 'values' );

            $valueCount = count( $values );
            if ( $valueCount > 0 )
            {
                foreach ( $values as $value )
                {
                    if ( strpos( $value->attribute( 'value' ), $node->attribute( 'path_string' ) ) === 0 )
                    {
                        $value->remove();
                        $valueCount--;
                    }
                }
            }
            if( $valueCount == 0 )
            {
                $policy =& eZPolicy::fetch( $limitation->attribute( 'policy_id' ) );
                $policy->remove();
                eZContentObject::expireAllCache();
                eZRole::expireCache();
            }

        }

        // Clean up recent items
        $nodeID = $node->attribute( 'node_id' );
        include_once( 'kernel/classes/ezcontentbrowserecent.php' );
        eZContentBrowseRecent::removeRecentByNodeID( $nodeID );

        // Clean up bookmarks
        $nodeID = $node->attribute( 'node_id' );
        include_once( 'kernel/classes/ezcontentbrowsebookmark.php' );
        eZContentBrowseBookmark::removeByNodeID( $nodeID );
    }

    /*!
      Moves the node to the given node.
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

        /// role system clean up
        // Clean up policies and limitations

            $limitationsToFix =& eZPolicyLimitation::findByType( 'SubTree', $node->attribute( 'path_string' ), false );
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
        }
    }

    function checkAccess( $functionName, $originalClassID = false, $parentClassID = false )
    {
        $classID = $originalClassID;
        $user =& eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );

        $origFunctionName = $functionName;
        // The 'move' function simply reuses 'edit' for generic access
        // but adds another top-level check below
        // The original function is still available in $origFunctionName
        if ( $functionName == 'move' )
            $functionName = 'edit';

        $accessResult = $user->hasAccessTo( 'content' , $functionName );
        $accessWord = $accessResult['accessWord'];
        $contentObject =& $this->attribute( 'object' );

        if ( $origFunctionName == 'remove' or
             $origFunctionName == 'move' )
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
                            else if ( in_array( $contentObject->attribute( 'contentclass_id' ), $limitationArray[$key] )  )
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
                        {
                            if ( in_array( $contentObject->attribute( 'section_id' ), $limitationArray[$key]  ) )
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
                            if ( $contentObject->attribute( 'owner_id' ) == $userID || $contentObject->attribute( 'id' ) == $userID )
                            {
                                $access = 'allowed';
                            }
                            else
                            {
                                $access = 'denied';
                                $limitationList = array ( 'Limitation' => $key );
                            }
                        } break;

                        case 'Node':
                        {
                            $mainNodeID = $this->attribute( 'main_node_id' );
                            foreach ( $limitationArray[$key] as $nodeID )
                            {
                                $node = eZContentObjectTreeNode::fetch( $nodeID );
                                $limitationNodeID = $node->attribute( 'main_node_id' );
                                if ( $mainNodeID == $limitationNodeID )
                                {
                                    $access = 'allowed';
                                    break;
                                }
                            }
                            if ( $access != 'allowed' )
                            {
                                $access = 'denied';
                                $limitationList = array( 'Limitation' => $key,
                                                         'Required' => $limitationArray[$key] );
                                break;
                            }
                        } break;

                        case 'Subtree':
                        {
                            $path =  $this->attribute( 'path_string' );
                            $subtreeArray = $limitationArray[$key];
                            foreach ( $subtreeArray as $subtreeString )
                            {
                                if (  strstr( $path, $subtreeString ) )
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
                                                                   'MainNodeID' => $this->attribute( 'main_node_id' ) ),
                                     'PolicyList' => $policyList );
                return 0;
            }
            else
            {
                return 1;
            }
        }
    }

    function &makeObjectsArray( &$array , $with_contentobject = true )
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
                    $node['name'] = 'Top Level Nodes';
            }

            $object =& new eZContentObjectTreeNode( $node );
            $object->setName($node['name']);

            if ( isset( $node['class_name'] ) )
                $object->ClassName = $node['class_name'];
            if ( isset( $node['class_identifier'] ) )
                $object->ClassIdentifier = $node['class_identifier'];
            if ( $with_contentobject )
            {
                if ( array_key_exists( 'class_name', $node ) )
                {
                    $contentObject =& new eZContentObject( $node );

                    $permissions = array();
                    $contentObject->setPermissions( $permissions );
                    $contentObject->setClassName( $node['class_name'] );
                    if ( isset( $node['class_identifier'] ) )
                        $contentObject->ClassIdentifier = $node['class_identifier'];

                }
                else
                {
                    $contentObject =& new eZContentObject( array());
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

    function findNode( $parentNode, $id, $asObject = false, $remoteID = false )
    {
        if ( !isset( $parentNode) || $parentNode == NULL  )
        {
            $parentNode = 2;
        }

        $db =& eZDB::instance();
        if( $asObject )
        {
            if ( $remoteID )
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
            }
            else
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
            }

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
        }
        else
        {
            $getNodeQuery = "SELECT node_id
                           FROM ezcontentobject_tree
                           WHERE
                                parent_node_id=$parentNode AND
                                contentobject_id = $id ";
            $nodeArr = $db->arrayQuery( $getNodeQuery );
            if ( isset( $nodeArr[0] ) )
                return $nodeArr[0]['node_id'];
            else
                return false;
        }
    }

    function &getName()
    {
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
    */
    function unserialize( $contentNodeDOMNode, $contentObject, $version, $isMain, &$nodeList, $options )
    {
        $parentNodeID = -1;
        if ( $contentNodeDOMNode->attributeValue( 'parent-node-remote-id' ) !== false )
        {
            $parentNode = eZContentObjectTreeNode::fetchByRemoteID( $contentNodeDOMNode->attributeValue( 'parent-node-remote-id' ) );
            $parentNodeID = $parentNode->attribute( 'node_id' );
        }
        else
        {
            if ( isset( $options['top_nodes_map'][$contentNodeDOMNode->attributeValue( 'node-id' )]['new_node_id'] ) )
            {
                $parentNodeID = $options['top_nodes_map'][$contentNodeDOMNode->attributeValue( 'node-id' )]['new_node_id'];
                eZDebug::writeNotice( 'Using user specified top node: ' . $parentNodeID,
                                      'eZContentObjectTreeNode::unserialize()' );
            }
            else if ( isset( $options['top_nodes_map']['*'] ) )
            {
                $parentNodeID = $options['top_nodes_map']['*'];
                eZDebug::writeNotice( 'Using user specified top node: ' . $parentNodeID,
                                      'eZContentObjectTreeNode::unserialize()' );

            }
            else
            {
                eZDebug::writeError( 'New parent node not set ' . $contentNodeDOMNode->attributeValue( 'name' ),
                                     'eZContentObjectTreeNode::unserialize()' );
            }
        }

        $nodeInfo = array( 'contentobject_id' => $contentObject->attribute( 'id' ),
                           'contentobject_version' => $version,
                           'is_main' => $isMain,
                           'parent_node' => $parentNodeID,
                           'parent_remote_id' => $contentNodeDOMNode->attributeValue( 'remote-id' ),
                           'sort_field' => eZContentObjectTreeNode::sortFieldID( $contentNodeDOMNode->attributeValue( 'sort-field' ) ),
                           'sort_order' => $contentNodeDOMNode->attributeValue( 'sort-order' ),
                           'priority' => $contentNodeDOMNode->attributeValue( 'priority' ) );
        $nodeAssignment =& eZNodeAssignment::create( $nodeInfo );
        $nodeList[] = $nodeInfo;
        $nodeAssignment->store();
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

    function store()
    {
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
        $version =& eZContentObjectVersion::fetchVersion( $this->ContentObjectVersion, $this->ContentObjectID, $asObject );
        if ( $this->CurrentLanguage != false )
            $version->CurrentLanguage = $this->CurrentLanguage;
        return $version;
    }

    function &urlAlias()
    {
        $useURLAlias =& $GLOBALS['eZContentObjectTreeNodeUseURLAlias'];
        $ini =& eZINI::instance();
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
                if ( substr( $this->PathIdentificationString, 0, strlen( $prepend ) ) )
                {
                    return eZUrlAlias::cleanURL( substr( $this->PathIdentificationString, strlen( $prepend ) ) );
                }
                else
                {
                    return eZUrlAlias::cleanURL( $this->PathIdentificationString );
                }
            }
            else
            {
                return eZUrlAlias::cleanURL( $this->PathIdentificationString );
            }
        }
        else
            return eZUrlAlias::cleanURL( 'content/view/full/' . $this->NodeID );
    }

    function &url()
    {
        $ini =& eZINI::instance();
        if ( $ini->variable( 'URLTranslator', 'Translation' ) == 'enabled' )
            return $this->urlAlias();
        else
            return 'content/view/full/' . $this->NodeID;
    }


    /*!
     \return the cached value of the class identifier if it exists, it not it's fetched dynamically
    */
    function &classIdentifier()
    {
        $identifier = "";
        if ( $this->ClassIdentifier !== null )
        {
            $identifier = $this->ClassIdentifier;
        }
        else
        {
            $object =& $this->object();
            $class =& $object->contentClass();
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
            $object =& $this->object();
            $class =& $object->contentClass();
            $name = $class->attribute( 'name' );
        }

        return $name;
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
