<?php
//
// Definition of eZContentObjectVersion class
//
// Created on: <18-Apr-2002 10:05:34 bf>
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

/*!
  \class eZContentObjectVersion ezcontentobjectversion.php
  \brief The class eZContentObjectVersion handles different versions of an content object
  \ingourp eZKernel

*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/eznodeassignment.php" );
include_once( "kernel/classes/ezcontentobject.php" );

include_once( "kernel/classes/ezcontentobjectattribute.php" );
include_once( "kernel/classes/ezcontentobjecttranslation.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
include_once( "kernel/classes/ezcontentclassattribute.php" );


define( "EZ_VERSION_STATUS_DRAFT", 0 );
define( "EZ_VERSION_STATUS_PUBLISHED", 1 );
define( "EZ_VERSION_STATUS_PENDING", 2 );
define( "EZ_VERSION_STATUS_ARCHIVED", 3 );
define( "EZ_VERSION_STATUS_REJECTED", 4 );
//define( "EZ_VERSION_STATUS_SHOWN", 5 );


class eZContentObjectVersion extends eZPersistentObject
{
    function eZContentObjectVersion( $row=array() )
    {
        $this->ContentObjectAttributeArray = false;
        $this->DataMap = false;
        $this->TempNode = null;
        $this->VersionName = null;
        $this->VersionNameCache = array();
        $this->eZPersistentObject( $row );
    }

    function &definition()
    {
        return array( "fields" => array( 'id' =>  array( 'name' => 'ID',
                                                         'datatype' => 'integer',
                                                         'default' => 0,
                                                         'required' => true ),
                                         'contentobject_id' =>  array( 'name' => 'ContentObjectID',
                                                                       'datatype' => 'integer',
                                                                       'default' => 0,
                                                                       'required' => true ),
                                         'creator_id' =>  array( 'name' => 'CreatorID',
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         'version' =>  array( 'name' => 'Version',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         'status' =>  array( 'name' => 'Status',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'created' =>  array( 'name' => 'Created',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true ),
                                         'modified' =>  array( 'name' => 'Modified',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         'workflow_event_pos' =>  array( 'name' => 'WorkflowEventPos',
                                                                         'datatype' => 'integer',
                                                                         'default' => 0,
                                                                         'required' => true ) ),
                      'keys' => array( 'id' ),
                      'function_attributes' => array( // 'data' => 'fetchData',
                                                      'creator' => 'creator',
                                                      "name" => "name",
                                                      "version_name" => "versionName",
                                                      'main_parent_node_id' => 'mainParentNodeID',
                                                      "contentobject_attributes" => "contentObjectAttributes",
                                                      "related_contentobject_array" => "relatedContentObjectArray",
                                                      'reverse_related_object_list' => "reverseRelatedObjectList",
                                                      'parent_nodes' => 'parentNodes',
                                                      "can_read" => "canVersionRead",
                                                      "data_map" => "dataMap",
                                                      'node_assignments' => 'nodeAssignments',
                                                      'contentobject' => 'contentObject',
                                                      'language_list' => 'translations',
                                                      'translation' => 'translation',
                                                      'translation_list' => 'translationList',
                                                      'temp_main_node' => 'tempMainNode'
                                                      ),
                      'class_name' => "eZContentObjectVersion",
                      'sort' => array( 'version' => 'asc' ),
                      'name' => 'ezcontentobject_version' );
    }

    function statusList()
    {
        return array( array( 'name' => "Draft", 'id' =>  EZ_VERSION_STATUS_DRAFT ),
                      array( 'name' => "Published", 'id' =>  EZ_VERSION_STATUS_PUBLISHED ),
                      array( 'name' => "Pending", 'id' =>  EZ_VERSION_STATUS_PENDING ),
                      array( 'name' => "Archived", 'id' =>  EZ_VERSION_STATUS_ARCHIVED ),
                      array( 'name' => "Rejected", 'id' =>  EZ_VERSION_STATUS_REJECTED ) );
    }
    /*!
     \return true if the requested attribute exists in object.
    */

    function hasAttribute( $attr )
    {
        return $attr == 'creator'
            or $attr == 'name'
            or $attr == 'version_name'
            or $attr == 'main_parent_node_id'
            or $attr == 'parent_nodes'
            or $attr == 'node_assignments'
            or $attr == 'contentobject'
            or $attr == 'language_list'
            or $attr == 'translation'
            or $attr == 'translation_list'
            or $attr == 'related_contentobject_array'
            or $attr == 'reverse_related_object_list'
            or $attr == 'temp_main_node'
            or eZPersistentObject::hasAttribute( $attr );
    }

    function &fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZContentObjectVersion::definition(),
                                                null,
                                                array( 'id' => $id ),
                                                $asObject );
    }

    function &fetchVersion( $version, $contentObjectID, $asObject = true )
    {
        $ret =& eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                     null, array( "version" => $version,
                                                                  "contentobject_id" => $contentObjectID
                                                                 ),
                                                    null, null,
                                                     $asObject );
        return $ret[0];
    }

    function fetchUserDraft( $objectID, $userID )
    {
        $versions =& eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                          null, array( 'creator_id' => $userID,
                                                                       'contentobject_id' => $objectID,
                                                                       'status' => array( array( EZ_VERSION_STATUS_DRAFT, EZ_VERSION_STATUS_DRAFT ) )
                                                                       ),
                                                          array( 'version' => 'asc' ), null,
                                                          true );
        if ( $versions === null or
             count( $versions ) == 0 )
            return null;
        return $versions[0];
    }

    function &fetchForUser( $userID, $status = EZ_VERSION_STATUS_DRAFT )
    {
        $versions =& eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                          null, array( 'creator_id' => $userID,
                                                                       'status' => $status
                                                                       ),
                                                          null, null,
                                                          true );
        return $versions;
    }

    function &fetchFiltered( $filters, $offset, $limit )
    {
        $limits = null;
        if ( $offset or $limit )
            $limits = array( 'offset' => $offset,
                             'length' => $limit );
        $versions =& eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                          null, $filters,
                                                          null, $limits,
                                                          true );
        return $versions;
    }

    /*!
     \return the attribute with the requested name.
    */
    function &attribute( $attr )
    {
        if ( $attr == 'creator' )
        {
            return $this->creator();
        }
        else if ( $attr == 'name' )
        {
            return $this->name();
        }
        else if ( $attr == 'version_name' )
        {
            return $this->versionName();
        }
        elseif ( $attr == 'main_parent_node_id' )
        {
            return $this->mainParentNodeID();
        }
        elseif ( $attr == 'parent_nodes' )
        {
            return  $this->parentNodes();
        }
        elseif ( $attr == 'node_assignments' )
        {
            return  $this->nodeAssignments();
        }
        elseif ( $attr == 'contentobject' )
        {
            return  $this->contentObject();
        }
        else if ( $attr == "data_map" )
        {
            return $this->dataMap();
        }
        elseif ( $attr == 'contentobject_attributes' )
        {
            return  $this->contentObjectAttributes();
        }
        elseif ( $attr == 'related_contentobject_array' )
        {
            return  $this->relatedContentObjectArray();
        }
        elseif ( $attr == 'reverse_related_object_list' )
        {
            return  $this->reverseRelatedObjectList();
        }
        elseif ( $attr == 'language_list' )
        {
            return  $this->translations();
        }
        elseif ( $attr == 'translation' )
        {
            return  $this->translation();
        }
        elseif ( $attr == 'translation_list' )
        {
            return  $this->translationList( eZContentObject::defaultLanguage() );
        }
        else if ( $attr == 'temp_main_node' )
        {
            return $this->tempMainNode();
        }
        else if ( $attr == "can_versionread" )
            return $this->canVersionRead();
        else
        {
            return eZPersistentObject::attribute( $attr );
        }
    }

    /*!
     \return an eZContentObjectTreeNode object which doesn't really exist in the DB,
             this can be passed to a node view template.
    */
    function &tempMainNode()
    {
        if ( $this->TempNode !== null )
            return $this->TempNode;
        $nodeAssignments =& $this->nodeAssignments();
        $mainNodeAssignment = null;
        for ( $i = 0; $i < count( $nodeAssignments ); ++$i )
        {
            $nodeAssignment =& $nodeAssignments[$i];
            if ( $nodeAssignment->attribute( 'is_main' ) )
            {
                $mainNodeAssignment =& $nodeAssignment;
                break;
            }
        }
        if ( $mainNodeAssignment === null and
             count( $nodeAssignments ) > 0 )
            $mainNodeAssignment =& $nodeAssignments[0];
        $this->TempNode =& $mainNodeAssignment->tempNode();
        return $this->TempNode;
    }

    /*!
     \return the name of the current version, optionally in the specific language \a $lang
    */
    function &name( $lang = false )
    {
        if ( $this->VersionName !== null )
            return $this->VersionName;
        $this->VersionName = eZContentObject::versionLanguageName( $this->attribute( 'contentobject_id' ),
                                                                   $this->attribute( 'version' ),
                                                                   $lang );
        if ( $this->VersionName === false )
        {
            $contentObject =& $this->contentObject();
            $this->VersionName = $contentObject->name( $lang );
        }
        return $this->VersionName;
    }

    /*!
     \return the name of the current version, optionally in the specific language \a $lang
    */
    function &versionName( $lang = false )
    {
        if ( !$lang )
            $lang = eZContentObject::defaultLanguage();
        if ( isset( $this->VersionNameCache[$lang] ) )
            return $this->VersionNameCache[$lang];
        $object =& $this->attribute( 'contentobject' );
        if ( !$object )
            return false;
        $class =& $object->attribute( 'content_class' );
        if ( !$class )
            return false;
        $this->VersionNameCache[$lang] = $class->contentObjectName( $object,
                                                                    $this->attribute( 'version' ),
                                                                    $lang );
        return $this->VersionNameCache[$lang];
    }

    /*!
     Returns true if the current
    */
    function canVersionRead( )
    {
        if ( !isset( $this->Permissions["can_versionread"] ) )
        {
            $this->Permissions["can_versionread"] = $this->checkAccess( 'versionread' );
        }
        $p = ( $this->Permissions["can_versionread"] == 1 );
//         eZDebug::writeDebug( $p ? "true" : "false", 'p' );
        return $p;
    }

    function checkAccess( $functionName, $originalClassID = false, $parentClassID = false )
    {
        $classID = $originalClassID;
        $user =& eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );
        $accessResult =  $user->hasAccessTo( 'content' , $functionName );
        $accessWord = $accessResult['accessWord'];
        $object =& $this->attribute( 'contentobject' );
        $objectClassID = $object->attribute( 'contentclass_id' );
        if ( ! $classID )
        {
            $classID = $objectClassID;
        }
//         eZDebug::writeDebug( $accessWord, 'accessword' );
        if ( $accessWord == 'yes' )
        {
            return 1;
        }
        elseif ( $accessWord == 'no' )
        {
            return 0;
        }
        else
        {
            $policies  =& $accessResult['policies'];
//             eZDebug::writeDebug( $policies, 'policies' );
            foreach ( array_keys( $policies ) as $key  )
            {
                $policy =& $policies[$key];
                $limitationList[] =& $policy->attribute( 'limitations' );
            }
            if ( count( $limitationList ) > 0 )
            {
                eZDebug::writeDebug("goes here 2");
                $access = 'denied';
                foreach ( array_keys( $limitationList ) as $key  )
                {
                    $limitationArray =& $limitationList[ $key ];
                    eZDebug::writeDebug($limitationArray,"goes here 3");
                    if ( $access == 'allowed' )
                    {
                        break;
                    }
                    foreach ( array_keys( $limitationArray ) as $key  )
//                    foreach ( $limitationArray as $limitation )
                    {
                        $limitation =& $limitationArray[$key];
                        eZDebug::writeDebug($limitation,"goes here 4");
//                        if ( $functionName == 'remove' )
//                        {
//                            eZDebug::writeNotice( $limitation, 'limitation in check access' );
//                        }

                        if ( $limitation->attribute( 'identifier' ) == 'Class' )
                        {
                            if ( $functionName == 'create' and
                                 !$originalClassID )
                            {
                                $access = 'allowed';
                            }
                            else if ( $functionName == 'create' and
                                 in_array( $classID, $limitation->attribute( 'values_as_array' ) ) )
                            {
                                $access = 'allowed';
                            }
                            elseif ( in_array( $objectClassID, $limitation->attribute( 'values_as_array' )  )  )
                            {
                                $access = 'allowed';
                            }
                            else
                            {
                                $access = 'denied';
                                break;
                            }
                        }
                        elseif ( $limitation->attribute( 'identifier' ) == 'Status' )
                        {

                            if (  in_array( $this->attribute( 'status' ), $limitation->attribute( 'values_as_array' )  ) )
                            {
                                $access = 'allowed';
                            }
                            else
                            {
                                $access = 'denied';
                                break;
                            }
                        }
//                         elseif ( $limitation->attribute( 'identifier' ) == 'ParentClass' )
//                         {

//                             if (  in_array( $this->attribute( 'contentclass_id' ), $limitation->attribute( 'values_as_array' )  ) )
//                             {
//                                 $access = 'allowed';
//                             }
//                             else
//                             {
//                                 $access = 'denied';
//                                 break;
//                             }
//                         }
                        elseif ( $limitation->attribute( 'identifier' ) == 'Section' )
                        {
                            if (  in_array( $object->attribute( 'section_id' ), $limitation->attribute( 'values_as_array' )  ) )
                            {
                                $access = 'allowed';
                            }
                            else
                            {
                                $access = 'denied';
                                break;
                            }
                        }
                        elseif ( $limitation->attribute( 'identifier' ) == 'Owner' )
                        {
                            if ( $this->attribute( 'creator_id' ) == $userID )
                            {
                                $access = 'allowed';
                            }
                            else
                            {
                                $access = 'denied';
                                break;
                            }
                        }
                        elseif ( $limitation->attribute( 'identifier' ) == 'Node' )
                        {
                            $contentObjectID = $this->attribute( 'contentobject_id' );
                            foreach ( $limitation->attribute( 'values_as_array' ) as $nodeID )
                            {
                                $node = eZContentObjectTreeNode::fetch( $nodeID );
                                $limitationObjectID = $node->attribute( 'contentobject_id' );
                                if ( $contentObjectID == $limitationObjectID )
                                {
                                    $access = 'allowed';
                                }
                            }
                            if ( $access == 'allowed' )
                            {
                                // do nothing
                            }
                            else
                            {
                                $access = 'denied';
                                break;
                            }
                        }
                        elseif ( $limitation->attribute( 'identifier' ) == 'Subtree' )
                        {
                            $contentObject = $this->attribute( 'contentobject' );
                            $assignedNodes = $contentObject->attribute( 'assigned_nodes' );
                            foreach (  $assignedNodes as  $assignedNode )
                            {
                                $path =  $assignedNode->attribute( 'path_string' );
                                $subtreeArray = $limitation->attribute( 'values_as_array' );
                                foreach ( $subtreeArray as $subtreeString )
                                {
                                    if (  strstr( $path, $subtreeString ) )
                                    {
                                        $access = 'allowed';
                                    }
                                }
                            }
                            if ( $access == 'allowed' )
                            {
                                // do nothing
                            }
                            else
                            {
                                $access = 'denied';
                                break;
                            }
                        }
                    }
                }
                if ( $access == 'denied' )
                {
                    return 0;
                }
                else
                {
                    return 1;
                }
            }
        }
    }

    function &contentObject()
    {
        if( !isset( $this->ContentObject ) )
        {
            $this->ContentObject =& eZContentObject::fetch( $this->attribute( 'contentobject_id' ) );
        }
        return $this->ContentObject;
    }

    function mainParentNodeID()
    {
        $temp =& eZNodeAssignment::fetchForObject( $this->attribute( 'contentobject_id' ), $this->attribute( 'version' ), 1 );
        if ( $temp == null )
        {
            return 1;
        }
        return $temp[0]->attribute( 'parent_node' );
    }

    function &parentNodes( )
    {
        $retNodes = array();
        $nodeAssignmentList =& eZNodeAssignment::fetchForObject( $this->attribute( 'contentobject_id' ), $this->attribute( 'version' ) );
        foreach ( array_keys( $nodeAssignmentList ) as $key )
        {
            $nodeAssignment =& $nodeAssignmentList[$key];
            /*  if( $nodeAssignment->attribute( 'parent_node' ) != '1' )
            {
                $retNodes[] =& $nodeAssignment;
            }*/
            $retNodes[] =& $nodeAssignment;
        }
        return $retNodes;
    }
    function &nodeAssignments()
    {
        $nodeAssignmentList = array();
        $nodeAssignmentList =& eZNodeAssignment::fetchForObject( $this->attribute( 'contentobject_id' ), $this->attribute( 'version' ) );
//         eZDebug::writeDebug( $nodeAssignmentList, "nodeAssignmentList" );
        return $nodeAssignmentList;
    }

    function &assignToNode( $nodeID, $main = 0, $fromNodeID = 0, $sortField = null, $sortOrder = null,
                            $remoteID = 0 )
    {
        if ( $fromNodeID == 0 && $this->attribute( 'status' ) == EZ_VERSION_STATUS_DRAFT )
            $fromNodeID = -1;
        $nodeRow = array( 'contentobject_id' => $this->attribute( 'contentobject_id' ),
                          'contentobject_version' => $this->attribute( 'version' ),
                          'parent_node' => $nodeID,
                          'is_main' => $main,
                          'remote_id' => $remoteID,
                          'from_node_id' => $fromNodeID );
        if ( $sortField !== null )
            $nodeRow['sort_field'] = $sortField;
        if ( $sortOrder !== null )
            $nodeRow['sort_order'] = ( $sortOrder ? 1 : 0 );

        $nodeAssignment =& eZNodeAssignment::create( $nodeRow );
        $nodeAssignment->store();
        return $nodeAssignment;
    }

    function removeAssignment( $nodeID )
    {
        $nodeAssignmentList =& $this->attribute( 'node_assignments' );
        foreach ( array_keys( $nodeAssignmentList ) as $key  )
        {
            $nodeAssignment =& $nodeAssignmentList[$key];
            if ( $nodeAssignment->attribute( 'parent_node' ) == $nodeID )
            {
                $nodeAssignment->remove();
            }
        }
    }

    /*!
     Returns the attributes for the current content object version. The wanted language
     must be specified.
    */
/*    function &contentObjectAtributes( $language = false, $asObject = true )
    {
        return eZContentObject::contentObjectAttributes( $asObject, $this->Version, $language );
        if ( $language === false )
        {
            $language = eZContentObject::defaultLanguage();
        }

        return eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(),
                                                    null, array( "version" => $this->Version,
                                                                 "contentobject_id" => $this->ContentObjectID,
                                                                 "language_code" => $language
                                                                 ),
                                                    null, null,
                                                    $as_object );
    }
*/

    /*!
	 \return the content object attribute
    */
    function &dataMap()
    {
        if ( $this->ContentObjectAttributeArray === false )
        {
            $data =& $this->contentObjectAttributes();
            // Store the attributes for later use
            $this->ContentObjectAttributeArray =& $data;
        }
        else
        {
            $data =& $this->ContentObjectAttributeArray;
        }

        if ( $this->DataMap == false )
        {
            $ret = array();
            reset( $data );
            while( ( $key = key( $data ) ) !== null )
            {
                $item =& $data[$key];

                $identifier = $item->contentClassAttributeIdentifier();

                $ret[$identifier] =& $item;

                next( $data );
            }
            $this->DataMap =& $ret;
        }
        else
        {
            $ret =& $this->DataMap;
        }
        return $ret;
    }

    function resetDataMap()
    {
        $this->ContentObjectAttributeArray = false;
        $this->DataMap = false;
        return $this->DataMap;
    }

    /*!
     Returns the related objects.
    */
    function &relatedContentObjectArray()
    {
        $objectID = $this->attribute( 'contentobject_id' );
//        eZDebug::writeDebug( eZContentObject::relatedContentObjectArray( $this->Version ), "related objects" );
        return eZContentObject::relatedContentObjectArray( $this->Version, $objectID );
    }

    function create( $contentobjectID, $userID = false, $version = 1 )
    {
        if ( $userID === false )
        {
            $user =& eZUser::currentUser();
            $userID =& $user->attribute( 'contentobject_id' );
        }
		$time = time();
        $row = array(
            "contentobject_id" => $contentobjectID,
            "version" => $version,
            "created" => $time,
            "modified" => $time,
            'creator_id' => $userID );
        return new eZContentObjectVersion( $row );
    }

    function reverseRelatedObjectList()
    {
        $objectID = $this->attribute( 'contentobject_id' );
        return eZContentObject::reverseRelatedObjectList( $this->Version, $objectID );
    }

    function remove()
    {
        $contentobjectID = $this->attribute( 'contentobject_id' );
        $versionNum = $this->attribute( 'version' );

        $db =& eZDB::instance();
        $db->query( "DELETE FROM ezcontentobject_link
                         WHERE from_contentobject_id=$contentobjectID AND from_contentobject_version=$versionNum" );
        $db->query( "DELETE FROM eznode_assignment
                         WHERE contentobject_id=$contentobjectID AND contentobject_version=$versionNum" );

        $db->query( 'DELETE FROM ezcontentobject_version
                         WHERE id=' . $this->attribute( 'id' ) );

        $contentObjectTranslations =& $this->translations();

        foreach ( array_keys( $contentObjectTranslations ) as $contentObjectTranslationKey )
        {
            $contentObjectTranslation =& $contentObjectTranslations[$contentObjectTranslationKey];
            $contentObjectAttributes =& $contentObjectTranslation->objectAttributes();
            foreach ( array_keys( $contentObjectAttributes ) as $attributeKey )
            {
                $attribute =& $contentObjectAttributes[$attributeKey];
                $attribute->remove( $attribute->attribute( 'id' ), $versionNum );
            }
        }
        $contentobject = $this->attribute( 'contentobject' );
        if ( !$contentobject->hasRemainingVersions() )
        {
            $contentobject->purge();
        }
    }

    function removeTranslation( $languageCode )
    {
//         eZDebug::writeDebug( $this, 'removeTranslation:version' );
        $versionNum = $this->attribute( 'version' );

        $contentObjectAttributes =& $this->contentObjectAttributes( $languageCode );

        foreach ( array_keys( $contentObjectAttributes ) as $attributeKey )
        {
            $attribute =& $contentObjectAttributes[$attributeKey];
            $attribute->remove( $attribute->attribute( 'id' ), $versionNum );
        }
    }

    /*!
     Clones the version with new version \a $newVersionNumber and creator \a $userID
     \note The cloned version is not stored.
    */
    function &clone( $newVersionNumber, $userID, $contentObjectID = false, $status = EZ_VERSION_STATUS_DRAFT )
    {
		$time = time();
        $clonedVersion = $this;
        $clonedVersion->setAttribute( 'id', null );
        if ( $contentObjectID !== false )
            $clonedVersion->setAttribute( 'contentobject_id', $contentObjectID );
        $clonedVersion->setAttribute( 'version', $newVersionNumber );
        include_once( 'lib/ezlocale/classes/ezdatetime.php' );
        $clonedVersion->setAttribute( 'created', $time );
        $clonedVersion->setAttribute( 'modified', $time );
        $clonedVersion->setAttribute( 'creator_id', $userID );
        if ( $status !== false )
            $clonedVersion->setAttribute( 'status', $status );
        return $clonedVersion;
    }

    /*!
     Returns an array with all the translations for the current version.
    */
    function translations( $asObject = true )
    {
        return $this->translationList( false, $asObject );
    }

    /*!
     Returns an array with all the translations for the current version.
    */
    function translation( $asObject = true )
    {
        return new eZContentObjectTranslation( $this->ContentObjectID, $this->Version, eZContentObject::defaultLanguage() );
    }

    /*!
     Returns an array with all the translations for the current version.
    */
    function translationList( $language = false, $asObject = true )
    {
        $db =& eZDB::instance();

        $languageSQL = '';
        if ( $language !== false )
        {
            $languageSQL = "AND language_code!='$language'";
        }
        $query = "SELECT language_code FROM ezcontentobject_attribute
                  WHERE contentobject_id='$this->ContentObjectID' AND version='$this->Version'
                        $languageSQL
                  GROUP BY language_code";

        $languageCodes =& $db->arrayQuery( $query );

        $translations = array();
        if ( $asObject )
        {
            foreach ( $languageCodes as $languageCode )
            {
                $translations[] = new eZContentObjectTranslation( $this->ContentObjectID, $this->Version, $languageCode["language_code"] );
            }
        }
        else
        {
            foreach ( $languageCodes as $languageCode )
            {
                $translations[] = $languageCode["language_code"];
            }
        }

        return $translations;
    }

    /*!
     Returns the attributes for the current content object version.
     If \a $language is not specified it will use eZContentObject::defaultLanguage.
    */
    function &contentObjectAttributes( $language = false, $asObject = true )
    {
        if ( $language === false )
        {
            if ( $this->CurrentLanguage != false )
            {
                $language = $this->CurrentLanguage;
            }
            else
            {
                $language = eZContentObject::defaultLanguage();
            }
        }

        return eZContentObjectVersion::fetchAttributes( $this->Version, $this->ContentObjectID, $language, $asObject );
    }

    /*!
     Returns the attributes for the content object version \a $version and content object \a $contentObjectID.
     \a $language defines the language to fetch.
     \static
     \sa attributes
    */
    function &fetchAttributes( $version, $contentObjectID, $language, $asObject = true )
    {
        $db =& eZDB::instance();

        $query = "SELECT ezcontentobject_attribute.* from ezcontentobject_attribute, ezcontentclass_attribute
                  WHERE
                    ezcontentclass_attribute.version = '0' AND
                    ezcontentclass_attribute.id = ezcontentobject_attribute.contentclassattribute_id AND
                    ezcontentobject_attribute.version = '$version' AND
                    ezcontentobject_attribute.contentobject_id = '$contentObjectID' AND
                    ezcontentobject_attribute.language_code = '$language'
                  ORDER by
                    ezcontentclass_attribute.placement ASC";

        $attributeArray =& $db->arrayQuery( $query );

        $returnAttributeArray = array();
        foreach ( $attributeArray as $attribute )
        {
            $attr = new eZContentObjectAttribute( $attribute );
            $returnAttributeArray[] = $attr;
        }
        return $returnAttributeArray;
    }

    /*!
     \static
     Unserialize xml structure. Create object from xml input.

     \param XML DOM Node
     \param contentobject.
     \param owner ID
     \param section ID
     \param new object, true if first version of new object
     \param options

     \returns created object, false if could not create object/xml invalid
    */
    function &unserialize( &$domNode, &$contentObject, $ownerID, $sectionID, $activeVersion, $firstVersion, $options )
    {
        $oldVersion =& $domNode->attributeValue( 'version' );
        $status =& $domNode->attributeValue( 'status' );

        if ( $firstVersion )
        {
            $contentObjectVersion = $contentObject->version( 1 );
        }
        else
        {
            $contentObjectVersion =& $contentObject->createNewVersion();
        }
        if ( !$contentObject )
        {
            eZDebug::writeError( 'Could not fetch object version : ' . $oldVersion,
                                 'eZContentObjectVersion::unserialize()' );
            return false;
        }

        $contentObjectVersion->setAttribute( 'status', EZ_VERSION_STATUS_DRAFT );
        $contentObjectVersion->store();

        $languageNodeArray =& $domNode->elementsByName( 'object-translation' );
        foreach( array_keys( $languageNodeArray ) as $languageKey )
        {
            $languageNode =& $languageNodeArray[$languageKey];
            $language =& $languageNode->attributeValue( 'language' );

            $attributeArray =& $contentObjectVersion->contentObjectAttributes( $language );
            $attributeNodeArray =& $languageNode->elementsByName( 'attribute' );
            foreach( array_keys( $attributeArray ) as $attributeKey )
            {
                $attribute =& $attributeArray[$attributeKey];

                $attributeIdentifier =& $attribute->attribute( 'contentclass_attribute_identifier' );

                $attributeDomNode =& $languageNode->elementByAttributeValue( 'identifier', $attributeIdentifier );
                if ( !$attributeDomNode )
                {
                    continue;
                }

                $attribute->unserialize( $attributeDomNode );
                $attribute->store();
            }
        }

        $nodeAssignmentNodeList = $domNode->elementByName( 'node-assignment-list' );
        $nodeAssignmentNodeArray = $nodeAssignmentNodeList->elementsByName( 'node-assignment' );
        foreach( $nodeAssignmentNodeArray as $nodeAssignmentNode )
        {
            eZContentObjectTreeNode::unserialize( $nodeAssignmentNode,
                                                  $contentObject,
                                                  $contentObjectVersion->attribute( 'version' ),
                                                  ( $oldVersion == $activeVersion ? 1 : 0 ),
                                                  $options );
        }
        $contentObjectVersion->store();

        return $contentObjectVersion;
    }

    /*!
     \return a DOM structure of the content object version, it's translations and attributes.

     \param package options ( optianal )
     \param array of allowed nodes ( optional )
     \param array of top nodes in current package export (optional )
    */
    function &serialize( $options = false, $contentNodeIDArray = false, $topNodeIDArray = false )
    {
        include_once( 'lib/ezxml/classes/ezdomdocument.php' );
        include_once( 'lib/ezxml/classes/ezdomnode.php' );
        $versionNode = new eZDOMNode();

        $versionNode->setName( 'version' );
        $versionNode->setPrefix( 'ezobject' );
        $versionNode->appendAttribute( eZDOMDocument::createAttributeNode( 'version', $this->Version ) );
        $versionNode->appendAttribute( eZDOMDocument::createAttributeNode( 'status', $this->Status ) );

        $translationList =& $this->translationList( false, false );
        foreach ( $translationList as $translationItem )
        {
            $language = $translationItem;
            if ( !in_array( $language, $options['language_array'] ) )
            {
                continue;
            }

            $translationNode = new eZDOMNode();
            $translationNode->setName( 'object-translation' );
            $translationNode->setPrefix( 'ezobject' );
            $translationNode->appendAttribute( eZDOMDocument::createAttributeNode( 'language', $language ) );

            eZDebug::writeDebug( "Attribute fetch start", 'eZContentObjectVersion::serialize' );
            $attributes =& $this->contentObjectAttributes( $language );
            eZDebug::writeDebug( "Attribute fetch end", 'eZContentObjectVersion::serialize' );
            foreach ( $attributes as $attribute )
            {
                $translationNode->appendChild( $attribute->serialize() );
            }
            $versionNode->appendChild( $translationNode );
        }

        $nodeAssignmentListNode = new eZDOMNode();
        $nodeAssignmentListNode->setName( 'node-assignment-list' );
        $nodeAssignmentListNode->setPrefix( 'ezobject' );
        $versionNode->appendChild( $nodeAssignmentListNode );

        $contentNodeArray = eZContentObjectTreeNode::fetchByContentObjectID( $this->ContentObjectID, true, $this->Version );
        foreach( $contentNodeArray as $contentNode )
        {
            $contentNodeDOMNode = $contentNode->serialize( $options, $contentNodeIDArray, $topNodeIDArray );
            if ( $contentNodeDOMNode !== false )
            {
                $nodeAssignmentListNode->appendChild( $contentNodeDOMNode );
            }
        }

        return $versionNode;
    }

    /*!
     \return the creator of the current version.
    */
    function &creator()
    {
        return eZContentObject::fetch( $this->CreatorID );
    }

    function unpublish()
    {
        if ( $this->attribute( 'status' ) == EZ_VERSION_STATUS_PUBLISHED )
        {
            $this->setAttribute( 'status', EZ_VERSION_STATUS_ARCHIVED );
            $parentNodeList =& $this->attribute( 'parent_nodes' );
            $parentNodeIDList = array();
            foreach( array_keys( $parentNodeList ) as $key )
            {
                $parentNode =& $parentNodeList[$key];
                $parentNodeIDList[] = $parentNode->attribute( 'parent_node' );
            }
            if ( count( $parentNodeIDList ) == 0 )
            {
                eZDebug::writeWarning( $this, "unable to get parent nodes for version" );
                return;
            }
            $parentNodeIDString = implode( ',' , $parentNodeIDList );
            $contentObjectID = $this->attribute( 'contentobject_id' );
            $version = $this->attribute( 'version' );
            $db =& eZDb::instance();
            $query = "update ezcontentobject_tree
                      set contentobject_is_published = '0'
                      where parent_node_id in ( $parentNodeIDString ) and
                            contentobject_id = $contentObjectID and
                            contentobject_version = $version" ;
            $db->query( $query );
        }
        else
        {
            eZDebug::writeWarning( $this, "trying to unpublish non published version");
        }

    }

    /*!
     \returns an array with locale objects, these objects represents the languages the content objects are allowed to be translated into.
              The array will not include locales that has been translated in this version.
    */
    function &nonTranslationList()
    {
        $translationList =& eZContentObject::translationList();
        if ( $translationList === null )
            return null;
        $translations =& $this->translations( false );
        $nonTranslationList = array();
        foreach ( $translationList as $translationItem )
        {
            $locale = $translationItem->attribute( 'locale_code' );
            if ( !in_array( $locale, $translations ) )
                $nonTranslationList[] = $translationItem;
        }
        return $nonTranslationList;
    }

    var $CurrentLanguage = false;
}

?>
