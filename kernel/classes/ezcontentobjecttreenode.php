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
// http://ez.no/products/licences/professional/. For pricing of this licence
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
                                                      'creator' => 'creator',
                                                      "path" => "fetchPath",
                                                      'path_array' => 'pathArray',
                                                      "parent" => "fetchParent",
                                                      'url' => 'url',
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
                      'modified_subnode' => 0,
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
        else if ( $attr == 'object' )
        {
            $obj = $this->object();
            return $obj;
        }
        else if ( $attr == 'subtree' )
        {
            return $this->subTree();
        }
        else if ( $attr == 'contentobject_version_object' )
        {
            return $this->contentObjectVersionObject();
        }
        else if ( $attr == 'children' )
        {
            return $this->children();
        }
        else if ( $attr == 'children_count' )
        {
            return $this->childrenCount();
        }
        else if ( $attr == 'sort_array' )
        {
            return $this->sortArray();
        }
        else if ( $attr == 'path' )
        {
            return $this->fetchPath();
        }
        else if ( $attr == 'path_array' )
        {
            return $this->pathArray();
        }
        else if ( $attr == 'parent' )
        {
            return $this->fetchParent();
        }
        else if ( $attr == 'creator' )
        {
            return $this->creator();
        }
        else if ( $attr == 'url_alias' )
        {
            return $this->urlAlias();
        }
        else if ( $attr == 'url' )
        {
            return $this->url();
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
        if ( $handler->hasTimestamp( 'content-cache' ) )
        {
            $expiryTime = $handler->timestamp( 'content-cache' );
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
        if ( $handler->hasTimestamp( 'content-cache' ) )
        {
            $expiryTime = $handler->timestamp( 'content-cache' );
        }

        if ( $phpCache->canRestore( $expiryTime ) )
        {
            $var =& $phpCache->restore( array( 'identifierHash' => 'identifier_hash' ) );
            $identifierHash =& $var['identifierHash'];
        }
        else
        {
            // Fetch identifier/id pair from db
            $query = "SELECT id, identifier FROM ezcontentclass";
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
        if ( $handler->hasTimestamp( 'content-cache' ) )
        {
            $expiryTime = $handler->timestamp( 'content-cache' );
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

    function &subTree( $params = false ,$nodeID = 0 )
    {
        if ( !is_numeric( $nodeID ) )
        {
            return array();
        }

        if ( $params === false )
        {
            $params = array( 'Depth' => false,
                             'Offset' => false,
                             'Limit' => false,
                             'SortBy' => false,
                             'AttributeFilter' => false,
                             'ExtendedAttributeFilter' => false,
                             'ClassFilterType' => false,
                             'ClassFilterArray' => false,
                             'GroupBy' => false );
        }
        $depth = false;
        $offset = false;
        $limit = false;
        $asObject = true;
        $limitationList = array();
        if ( isset( $params['AsObject'] ) )
             $asObject = $params['AsObject'];
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
                $params['Limitation'] =& $accessResult['policies'];
                $limitationList =& $params['Limitation'];
                $GLOBALS['ezpolicylimitation_list']['content']['read'] =& $params['Limitation'];
            }
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
        $attributeJoinCount = 0;
        $attributeFromSQL = "";
        $attributeWereSQL = "";
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
                            $attributeFromSQL .= ", ezcontentobject_attribute as a$attributeJoinCount";
                            $attributeWereSQL .= "
                                   a$attributeJoinCount.contentobject_id = ezcontentobject.id AND
                                   a$attributeJoinCount.contentclassattribute_id = $sortClassID AND
                                   a$attributeJoinCount.version = ezcontentobject_name.content_version AND
                                   a$attributeJoinCount.language_code = ezcontentobject_name.real_translation AND ";

                            $attributeJoinCount++;

                        }break;

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

        // Should we sort?
        if ( $sortCount == 0 )
        {
            $sortingFields = " path_string ASC";
        }

        // Check for class filtering
        $classCondition = '';
        if ( !isset( $params['ClassFilterType'] ) )
            $params['ClassFilterType'] = false;
        if ( ( $params['ClassFilterType'] == 'include' or $params['ClassFilterType'] == 'exclude' )
             and count( $params['ClassFilterArray'] ) > 0 )
        {
            $classCondition = ' ( ';
            $i = 0;
            $classCount = count( $params['ClassFilterArray'] );
            foreach ( $params['ClassFilterArray'] as $classID )
            {
                // Check if classes are recerenced by identifier
                if ( is_string( $classID ) && !is_numeric( $classID ) )
                {
                    $classID = eZContentObjectTreeNode::classIDByIdentifier( $classID );
                }
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


        $extendedAttributeFilterTables = '';
        $extendedAttributeFilterJoins = '';
        if ( isset( $params['ExtendedAttributeFilter'] ) and count( $params['ExtendedAttributeFilter'] ) > 1 )
        {
            $extendedAttributeFilterID = $params['ExtendedAttributeFilter']['id'];
            $extendedAttributeFilterParams = $params['ExtendedAttributeFilter']['params'];
            $filterINI =& eZINI::instance( 'extendedattributefilter.ini' );

            $filterClassName = $filterINI->variable( $extendedAttributeFilterID, 'ClassName' );
            $filterMethodName = $filterINI->variable( $extendedAttributeFilterID, 'MethodName' );
            $filterFile = $filterINI->variable( $extendedAttributeFilterID, 'FileName' );
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
            $classObject = new $filterClassName();
            $parameterArray = array( $extendedAttributeFilterParams );
            $sqlResult = call_user_func_array( array( $classObject, $filterMethodName ), $parameterArray );
            $extendedAttributeFilterTables = $sqlResult['tables'];
            $extendedAttributeFilterJoins = $sqlResult['joins'];
            eZDebug::writeDebug( $extendedAttributeFilterJoins, 'extendedAttributeFilterJoins' );
            if ( $extendedAttributeFilterJoins == '' )
            {
                return array();
            }
        }

        // Main node check
        $mainNodeOnlyCond = '';
        if ( isset( $params['MainNodeOnly'] ) && $params['MainNodeOnly'] === true )
        {
            $mainNodeOnlyCond = 'ezcontentobject_tree.node_id = ezcontentobject_tree.main_node_id AND';
        }

        // Check for attribute filtering
        $attributeFilterFromSQL = "";
        $attributeFilterWhereSQL = "";

        if ( isset( $params['AttributeFilter'] ) && $params['AttributeFilter'] !== false )
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
            $filterCount = $sortCount;
            $justFilterCount = 0;

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
                        if ( $filterCount >= $attributeJoinCount )
                        {
                            $attributeFilterFromSQL .= ", ezcontentobject_attribute as a$filterCount ";
                            $attributeFilterWhereSQL .= "
                               a$filterCount.contentobject_id = ezcontentobject.id AND
                               a$filterCount.contentclassattribute_id = $filterAttributeID AND
                               a$filterCount.version = ezcontentobject_name.content_version AND
                               a$filterCount.language_code = ezcontentobject_name.real_translation AND ";

                        }
                        else
                        {
                            $attributeFilterWhereSQL .= "
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
                        if ( ( $filterCount - $sortCount ) > 0 )
                            $attibuteFilterJoinSQL .= " $filterJoinType ";
                        $attibuteFilterJoinSQL .= "$filterField $filterOperator '$filterValue' ";
                        $filterCount++;
                        $justFilterCount++;
                    }
                }
            }

            if ( $justFilterCount > 0 )
                $attributeFilterWhereSQL .= "\n                            ( " . $attibuteFilterJoinSQL . " ) AND ";
        }

        if ( $nodeID == 0 )
        {
            $nodeID = $this->attribute( 'node_id' );
            $node = $this;
        }
        else if ( is_numeric( $nodeID ) )
        {
            $node = eZContentObjectTreeNode::fetch( $nodeID );
        }

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

        $ini =& eZINI::instance();
        $db =& eZDB::instance();

        $groupBySelectText = false;
        $groupByText = false;
        if ( isset( $params['GroupBy'] ) )
        {
            $groupBy = $params['GroupBy'];
            if ( isset( $groupBy['field'] ) and
                 isset( $groupBy['type'] ) )
            {
                $groupByField = $groupBy['field'];
                $groupByFieldType = $groupBy['type'];
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
                $groupBySelectText = ", " . $groupBySelect['select'];
                $groupByText = "GROUP BY " . $groupBySelect['group_field'];
            }
        }

        $useVersionName = true;

        $versionNameTables = ', ezcontentobject_name ';
        $versionNameTargets = ', ezcontentobject_name.name as name,  ezcontentobject_name.real_translation ';

        $lang = eZContentObject::defaultLanguage();

        $versionNameJoins = " and  ezcontentobject_tree.contentobject_id = ezcontentobject_name.contentobject_id and
                              ezcontentobject_tree.contentobject_version = ezcontentobject_name.content_version and
                              ezcontentobject_name.content_translation = '$lang' ";

        if ( count( $limitationList) > 0 )
        {
            $sqlParts = array();
            foreach( $limitationList as $limitationArray )
            {
                $sqlPartPart = array();
                $hasNodeLimitation = false;
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
                            $hasNodeLimitation = true;
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
                if ( $hasNodeLimitation )
                    $sqlParts[] = implode( ' OR ', $sqlPartPart );
                else
                    $sqlParts[] = implode( ' AND ', $sqlPartPart );
            }
            $sqlPermissionCheckingString = ' AND ((' . implode( ') or (', $sqlParts ) . ')) ';
            $query = "SELECT ezcontentobject.*,
                           ezcontentobject_tree.*,
                           ezcontentclass.name as class_name
                           $groupBySelectText
                           $versionNameTargets
                    FROM
                          ezcontentobject_tree,
                          ezcontentobject,ezcontentclass
                          $versionNameTables
                          $attributeFromSQL
                          $attributeFilterFromSQL
                          $extendedAttributeFilterTables
                       WHERE
                          $pathStringCond
                          $extendedAttributeFilterJoins
                          $attributeWereSQL
                          $attributeFilterWhereSQL
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
                    ORDER BY $sortingFields";
        }
        else
        {
            $query = "SELECT ezcontentobject.*,
                             ezcontentobject_tree.*,
                             ezcontentclass.name as class_name
                             $groupBySelectText
                             $versionNameTargets
                      FROM
                            ezcontentobject_tree,
                            ezcontentobject,ezcontentclass
                            $versionNameTables
                            $attributeFromSQL
                            $attributeFilterFromSQL
                            $extendedAttributeFilterTables
                      WHERE
                            $pathStringCond
                            $extendedAttributeFilterJoins
                            $attributeWereSQL
                            $attributeFilterWhereSQL
                            ezcontentclass.version=0 AND
                            $notEqParentString
                            ezcontentobject_tree.contentobject_id = ezcontentobject.id  AND
                            ezcontentclass.id = ezcontentobject.contentclass_id AND
                            $mainNodeOnlyCond
                            $classCondition
                            ezcontentobject_tree.contentobject_is_published = 1
                            $versionNameJoins
                     $groupByText
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
        /* else if ( isset( $GLOBALS['ezpolicylimitation_list'] ) )
        {
            $policyList =& $GLOBALS['ezpolicylimitation_list'];
            $limitationList = array();
            foreach ( array_keys( $policyList ) as $key )
            {
                $policy =& $policyList[$key];
                $limitationList[] =& $policy->attribute( 'limitations' );

            }
            eZDebugSetting::writeDebug( 'kernel-content-treenode', $limitationList, "limitation list"  );

        }*/
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

        $notEqParentString = "node_id != $fromNode AND";
        if ( isset( $params[ 'Depth' ] ) && $params[ 'Depth' ] > 0 )
        {

            $nodeDepth += $params[ 'Depth' ];
            if ( isset( $params[ 'DepthOperator' ] ) && $params[ 'DepthOperator' ] == 'eq' )
            {
                $depthCond = ' depth = ' . $nodeDepth . ' and ';
                $notEqParentString = "";

            }
            else
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
                if ( is_string( $classID ) && !is_numeric( $classID ) )
                {
                    $classID = eZContentObjectTreeNode::classIDByIdentifier( $classID );
                }
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
                        $attributeFilterFromSQL .= ", ezcontentobject_attribute as a$filterCount ";
                        $attributeFilterWhereSQL .= "
                            a$filterCount.contentobject_id = ezcontentobject.id AND
                               a$filterCount.version = ezcontentobject.current_version AND
                               a$filterCount.contentclassattribute_id = $filterAttributeID AND ";

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
                $hasNodeLimitation = false;
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
                            $hasNodeLimitation = true;
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
                if ( $hasNodeLimitation )
                     $sqlParts[] = implode( ' OR ', $sqlPartPart );
                else
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
    */
    function &childrenCount( )
    {
        return $this->subTreeCount( array( 'Depth' => 1,
                                           'DepthOperator' => 'eq' ) );
//                                           'Limitation' => $limitationList
//                                           ) );
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

    function &fetchByCRC( $pathStr )
    {
        eZDebug::writeWarning( "Obsolete: use ezurlalias instead", 'eZContentObjectTreeNode::fetchByCRC' );
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

    function &fetchByPath( $pathString, $asObject = true )
    {
         return eZPersistentObject::fetchObject( eZContentObjectTreeNode::definition(),
                                                 null,
                                                 array( "path_string" => $pathString ),
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

    function &fetchNode( $contentObjectID, $parentNodeID )
    {
        $returnValue = null;
        $ini =& eZINI::instance();
        $db =& eZDB::instance();
        $query="SELECT *
                FROM ezcontentobject_tree
                WHERE contentobject_id = $contentObjectID AND
                      parent_node_id = $parentNodeID";
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

    function fetchPath()
    {
        $nodeID = $this->attribute( 'node_id' );
        $nodePath = $this->attribute( 'path_string' );

        $pathArray = explode( '/', trim($nodePath,'/') );
        $pathArray = array_slice( $pathArray, 0, count($pathArray)-1 );

        if ( count( $pathArray ) == 0 )
        {
            return eZContentObjectTreeNode::makeObjectsArray( $pathArray );
        }

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

            $lang = eZContentObject::defaultLanguage();

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


        $insertedNode =& new eZContentObjectTreeNode();
        $insertedNode->setAttribute( 'parent_node_id', $parentMainNodeID );
        $insertedNode->setAttribute( 'contentobject_id', $contentobjectID );
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

        $sqlToCheckOriginalName = 'select path_identification_string
                                   from ezcontentobject_tree
                                   where  path_identification_string = \'' . $path . '\'
                                          and node_id != ' . $nodeID;

        $retNode = $db->arrayQuery( $sqlToCheckOriginalName );
        if ( count( $retNode ) == 0 )
        {
            return $path;
        }
        $sqlToCheckCurrentName = 'select path_identification_string
                                  from ezcontentobject_tree
                                  where ( path_identification_string = \'' . $path . '\' or
                                          path_identification_string like \'' . $path . '\\\_\\\_%\' )
                                          and node_id = ' . $nodeID ;
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
        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler =& eZExpiryHandler::instance();
        $handler->setTimestamp( 'content-cache', mktime() );
        $handler->store();

        if ( $nodeID == 0 )
        {
            $node =& $this;
        }
        else
        {
            $node =& eZContentObjectTreeNode::fetch( $nodeID );
        }

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
    }

    /*!
      Moves the node to the given node.
    */
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

    function &makeObjectsArray( &$array , $with_contentobject = true )
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
                if ( isset( $node['real_translation'] ) && $node['real_translation'] != '' )
                {
                    $object->CurrentLanguage = $node['real_translation'];
                    $contentObject->CurrentLanguage = $node['real_translation'];
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
     Update and store modified_subnode value for this node and all super nodes.
    */
    function updateAndStoreModified()
    {
        $pathString =& $this->attribute( 'path_string' );

        $pathArray = explode( '/', $pathString );
        $sqlParts = array();
        $sql = '';

        $sqlParts = '/';
        for( $pathCount = 1; $pathCount < count( $pathArray ) - 1; $pathCount++ )
        {
            $sqlParts .= $pathArray[$pathCount] . '/' ;
            $sql .= ( $pathCount != 1 ? ' OR' : '' ) . ' path_string=\'' . $sqlParts . '\' ';
        }

        $sql = 'UPDATE ezcontentobject_tree SET modified_subnode=' . eZDateTime::currentTimeStamp() .
             ' WHERE ' . $sql;

        $db =& eZDB::instance();
        $db->query( $sql );
    }

    function store()
    {
        include_once( 'lib/ezutils/classes/ezexpiryhandler.php' );
        $handler =& eZExpiryHandler::instance();
        $handler->setTimestamp( 'content-cache', mktime() );
        $handler->store();

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

    /// The current language for the node
    var $CurrentLanguage = false;

    /// Name of the node
    var $Name;
}

?>
