<?php
//
// Definition of eZContentObject class
//
// Created on: <17-Apr-2002 09:15:27 bf>
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

/*!
  \class eZContentObject ezcontentobject.php
  \ingroup eZKernel
  \brief The class eZContentObject handles eZ publish content objects

  \todo Add version and laguage to the cached attributes
  \sa eZContentClass
*/

include_once( "lib/ezdb/classes/ezdb.php" );
include_once( 'lib/ezlocale/classes/ezlocale.php' );
include_once( "kernel/classes/ezpersistentobject.php" );
include_once( "kernel/classes/ezcontentobjectversion.php" );
include_once( "kernel/classes/ezcontentobjectattribute.php" );
include_once( "kernel/classes/ezcontentclass.php" );
include_once( "kernel/classes/ezcontentobjecttreenode.php" );
include_once( "kernel/classes/eznodeassignment.php" );

include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );

class eZContentObject extends eZPersistentObject
{
    function eZContentObject( $row )
    {
        $this->eZPersistentObject( $row );
        $this->CurrentLanguage = eZContentObject::defaultLanguage();
    }

    function &definition()
    {
        return array( "fields" => array( "id" => "ID",
                                         "parent_id" => "ParentID",
                                         "main_node_id" => "MainNodeID",
                                         "section_id" => "SectionID",
                                         "owner_id" => "OwnerID",
                                         "contentclass_id" => "ClassID",
                                         "name" => "Name",
                                         "is_published" => "IsPublished",
                                         "published" => "Published",
                                         "modified" => "Modified",
                                         "current_version" => "CurrentVersion",
                                         "permission_id" => "PermissionID" ),
                      "keys" => array( "id" ),
                      "function_attributes" => array( "current" => "currentVersion",
                                                      "class_name" => "className",
                                                      "contentobject_attributes" => "contentObjectAttributes",
                                                      "owner" => "owner",
                                                      "related_contentobject_array" => "relatedContentObjectArray",
                                                      "children" => "children",
                                                      "can_read" => "canRead",
                                                      "can_create" => "canCreate",
                                                      "can_create_class_list" => "canCreateClassList",
                                                      "can_edit" => "canEdit",
                                                      "can_remove" => "canRemove",
                                                      "data_map" => "fetchData",
                                                      "main_parent_node_id" => "mainParentNodeID",
                                                      "assigned_nodes" => "assignedNodes",
                                                      "parent_nodes" => "parentNodes",
                                                      "main_node" => "mainNode",
                                                      "content_action_list" => "contentActionList" ),
                      "increment_key" => "id",
                      "class_name" => "eZContentObject",
                      "sort" => array( "id" => "asc" ),
                      "name" => "ezcontentobject" );
    }

    function &attribute( $attr )
    {
        if ( $attr == "current" or
             $attr == "class_name" or
             $attr == "owner" or
             $attr == "contentobject_attributes" or
             $attr == "related_contentobject_array" or
             $attr == "can_read" or
             $attr == "can_create" or
             $attr == "can_create_class_list" or
             $attr == "can_edit" or
             $attr == "can_remove" or
             $attr == "data_map" or
             $attr == "children" or
             $attr == "content_action_list"
             )
        {
            if ( $attr == "current" )
                return $this->currentVersion();
            if ( $attr == "class_name" )
                return $this->className();
            if ( $attr == "owner" )
                return $this->owner();
            else if ( $attr == "can_read" )
                return $this->canRead();
            else if ( $attr == "can_create" )
                return $this->canCreate();
            else if ( $attr == "can_create_class_list" )
                return $this->canCreateClassList();
            else if ( $attr == "can_edit" )
                return $this->canEdit();
            else if ( $attr == "can_remove" )
                return $this->canRemove();
            else if ( $attr == "contentobject_attributes" )
                return $this->contentObjectAttributes();
            else if ( $attr == "related_contentobject_array" )
                return $this->relatedContentObjectArray();
            else if ( $attr == "content_action_list" )
                return $this->contentActionList();
            else if ( $attr == "children" )
                return $this->children();
            else if ( $attr == "data_map" )
            {
                return $this->dataMap();
            }
        }
        elseif ( $attr == "main_parent_node_id" )
        {
            return  $this->mainParentNodeID() ;
        }
        elseif ( $attr == 'assigned_nodes' )
        {
            return $this->assignedNodes( true );
        }
        elseif ( $attr == 'parent_nodes' )
        {
            return $this->parentNodes( true, false );
        }
        elseif ( $attr == 'main_node' )
        {
            return $this->mainNode();
        }
        else
            return eZPersistentObject::attribute( $attr );
    }


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
     Returns the owner of the object as a content object.
    */
    function &owner()
    {
        return eZContentObject::fetch( $this->OwnerID );
    }

    /*!
     \return the content class for the current content object
    */
    function &contentClass()
    {
        return eZContentClass::fetch( $this->ClassID );
    }

    function &mainParentNodeID()
    {
        $temp = eZContentObjectTreeNode::getParentNodeId( $this->attribute( 'main_node_id' ) );

        return $temp;
    }


    function &fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZContentObject::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
    }

    function &fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                    null, null, null, null,
                                                    $asObject );
    }

    function &fetchSameClassList( $contentClassID, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZContentObject::definition(),
                                                    null,
                                                    array( "contentclass_id" => $contentClassID ),
                                                    null,
                                                    null,
                                                    $asObject );
    }
    /*!
      Returns the current version of this document.
    */
    function &currentVersion( $asObject = true )
    {
        return eZContentObjectVersion::fetchVersion( $this->attribute( "current_version" ), $this->ID, $asObject );
    }

    /*!
      Returns the given object version. False is returned if the versions does not exist.
    */
    function version( $version, $asObject = true )
    {
        return eZContentObjectVersion::fetchVersion( $version, $this->ID, $asObject );
    }

    /*!
      \return an array of versions for the current object.
    */
    function versions( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                    null, array( "contentobject_id" => $this->ID
                                                                 ),
                                                    null, null,
                                                    $asObject );
    }

    function &createInitialVersion( $userID )
    {
        return eZContentObjectVersion::create( $this->attribute( "id" ), $userID, 1 );
    }

    /*!
     Creates a new version and returns it as an eZContentObjectVersion object.
     If version number is given as argument that version is used to create a copy.
    */
    function &createNewVersion( $copyFromVersion = false )
    {
        // get the next available version number
        $nextVersionNumber = $this->nextVersion();

        $user =& eZUser::currentUser();
        $userID =& $user->attribute( 'contentobject_id' );

        if ( $copyFromVersion == false )
        {
            $version =& $this->currentVersion();
        }
        else
        {
            $version =& $this->version( $copyFromVersion );
        }
        eZDebug::writeNotice( $version , 'version' );
        $nodeAssignmentList =& $version->attribute( 'node_assignments' );

        eZDebug::writeNotice( $nodeAssignmentList , 'nodeAssignmentList' );

        foreach ( array_keys( $nodeAssignmentList ) as $key )
        {
            $nodeAssignment =& $nodeAssignmentList[$key];
            $clonedAssignment =& $nodeAssignment->clone( $nextVersionNumber );
            $clonedAssignment->store();
        }


        $currentVersionNumber = $version->attribute( "version" );
        $contentObjectTranslations =& $version->translations();

        $clonedVersion = $version->clone( $nextVersionNumber, $userID );
        $clonedVersion->store();

        foreach ( array_keys( $contentObjectTranslations ) as $contentObjectTranslationKey )
        {
            $contentObjectTranslation =& $contentObjectTranslations[$contentObjectTranslationKey];
            $contentObjectAttributes =& $contentObjectTranslation->objectAttributes();
            foreach ( array_keys( $contentObjectAttributes ) as $attributeKey )
            {
                $attribute =& $contentObjectAttributes[$attributeKey];
                $clonedAttribute =& $attribute->clone( $nextVersionNumber, $currentVersionNumber );
                $clonedAttribute->store();
            }
        }
        return $version;
    }

    /*!
      Reverts the object to the given version. All versions newer then the given version will
      be deleted.
    */
    function revertTo( $version )
    {
        $db =& eZDB::instance();

        // Delete stored attribute from other tables
        $contentobjectAttributes =& $this->allContentObjectAttributes( $this->ID );
        foreach (  $contentobjectAttributes as $contentobjectAttribute )
        {
            $contentobjectAttributeVersion = $contentobjectAttribute->attribute("version");
            if( $contentobjectAttributeVersion > $version )
            {
                $classAttribute =& $contentobjectAttribute->contentClassAttribute();
                $dataType =& $classAttribute->dataType();
                $dataType->deleteStoredObjectAttribute( $contentobjectAttribute, $contentobjectAttributeVersion );
            }
        }

        $db->query( "DELETE FROM ezcontentobject_attribute
					      WHERE contentobject_id='$this->ID' AND version>'$version'" );

        $db->query( "DELETE FROM ezcontentobject_version
					      WHERE contentobject_id='$this->ID' AND version>'$version'" );

        $db->query( "DELETE FROM eznode_assignment
					      WHERE contentobject_id='$this->ID' AND contentobject_version > '$version'" );

        $this->CurrentVersion = $version;
        $this->store();
    }

    /*!
     Copies the given version of the object and creates a new current version.
    */
    function copyRevertTo( $version )
    {
        $versionObject =& $this->createNewVersion( $version );

//         $this->CurrentVersion = $versionObject->attribute( 'version' );
//         $this->store();
        return $versionObject->attribute( 'version' );
    }

    /*!
      If nodeID is not given, this function will remove object from database. All versions and translations of this object will be lost.
      Otherwise, it will check node assignment and only delete the object from this node if it was assigned to other nodes as well.
    */
    function remove( $id = false, $nodeID = null )
    {
        $delID = $this->ID;
        if ( is_numeric( $id ) )
        {
            $delID = $id;
            $contentobject =& eZContentObject::fetch( $delID );
        }
        else
        {
            $contentobject =& $this;
        }

        $nodes = $contentobject->attribute( 'assigned_nodes' );

        if ( $nodeID === null  or count( $nodes ) <= 1 )
        {
            foreach ( $nodes as $node )
            {
                $node->remove();
            }
            $db =& eZDB::instance();

            // Delete stored attribute from other tables
            $contentobjectAttributes =& $contentobject->allContentObjectAttributes( $delID );
            foreach (  $contentobjectAttributes as $contentobjectAttribute )
            {
                $classAttribute =& $contentobjectAttribute->contentClassAttribute();
                $dataType =& $classAttribute->dataType();
                $dataType->deleteStoredObjectAttribute( $contentobjectAttribute );
            }

            $db->query( "DELETE FROM ezcontentobject_attribute
		     WHERE contentobject_id='$delID'" );

            $db->query( "DELETE FROM ezcontentobject_version
		     WHERE contentobject_id='$delID'" );

            $db->query( "DELETE FROM ezcontentobject
		     WHERE id='$delID'" );

            $db->query( "DELETE FROM eznode_assignment
             WHERE contentobject_id = '$delID'" );

            $db->query( "DELETE FROM ezcontentobject_link
             WHERE from_contentobject_id = '$delID' OR to_contentobject_id = '$delID'" );
        }
        else
        {
             eZContentObjectTreeNode::remove( $nodeID );
        }
    }


    /*
     Fetch all attributes of all versions belongs to a contentObject.
    */
    function &allContentObjectAttributes( $contentObjectID, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(),
                                                    null,
                                                    array("contentobject_id" => $contentObjectID ),
                                                    null,
                                                    null,
                                                    $asObject );
    }

    /*!
      Fetches the attributes for the current published version of the object.
    */
    function &contentObjectAttributes( $asObject=true )
    {
        $db =& eZDB::instance();

        $query = "SELECT ezcontentobject_attribute.*, ezcontentclass_attribute.identifier as identifier FROM
                    ezcontentobject_attribute, ezcontentclass_attribute
                  WHERE
                    ezcontentclass_attribute.version = '0' AND
                    ezcontentclass_attribute.id = ezcontentobject_attribute.contentclassattribute_id AND
                    ezcontentobject_attribute.version = '$this->CurrentVersion' AND
                    ezcontentobject_attribute.contentobject_id = '$this->ID' AND
                    ezcontentobject_attribute.language_code = '$this->CurrentLanguage'
                  ORDER by
                    ezcontentclass_attribute.placement ASC";

        $attributeArray =& $db->arrayQuery( $query );

        $returnAttributeArray = array();
        foreach ( $attributeArray as $attribute )
        {
            $attr = new eZContentObjectAttribute( $attribute );
            $attr->setContentClassAttributeIdentifier( $attribute['identifier'] );
            $returnAttributeArray[] = $attr;
        }

        return $returnAttributeArray;
    }

    /*!
	  Returns the parent objects.
    */
    function &parents( )
    {
        $objectID = $this->ID;

        $parents = array();

        $parentID = $this->ParentID;

        $parent =& eZContentObject::fetch( $parentID );

        if ( $parentID > 0 )
            while ( ( $parentID > 0 ) )
            {
                $parents = array_merge( array( $parent ), $parents );
                $parentID = $parent->attribute( "parent_id" );
                $parent =& eZContentObject::fetch( $parentID );
            }
        return $parents;
    }

    function &fetchTree( $objectID=0, $level=0 )
    {
        $objectList =& eZContentObject::children( $objectID, true );

        $tree = array( );
        if ( $level == 0 )
            $tree[] = array( "Level" => $level, "Object" => eZContentObject::fetch( $objectID ) );

        $level++;
        foreach ( $objectList as $childObject )
        {
            $tree[] = array( "Level" => $level, "Object" => $childObject );

            $tree = array_merge( $tree, eZContentObject::fetchTree( $childObject->attribute( "id" ), $level ) );
        }
        return $tree;
    }

    /*!
	 Returns the next available version number for this object.
    */
    function nextVersion()
    {
        $db =& eZDB::instance();
        $versions =& $db->arrayQuery( "SELECT ( MAX( version ) + 1 ) AS next_id FROM ezcontentobject_version
				       WHERE contentobject_id='$this->ID'" );
        return $versions[0]["next_id"];

    }

    /*!
	 Returns number of exist versions.
    */
    function getVersionCount()
    {
        $db =& eZDB::instance();
        $versionCount =& $db->arrayQuery( "SELECT ( COUNT( version ) ) AS version_count FROM ezcontentobject_version
				       WHERE contentobject_id='$this->ID'" );
        return $versionCount[0]["version_count"];

    }
    function setCurrentLanguage( $lang )
    {
        $this->CurrentLanguage = $lang;
    }

    /*!
     Adds a link to the given content object id.
    */
    function addContentObjectRelation( $objectID, $version )
    {
        $db =& eZDB::instance();
        $db->query( "INSERT INTO ezcontentobject_link ( from_contentobject_id, from_contentobject_version, to_contentobject_id )
		     VALUES ( '$this->ID', '$version', '$objectID' )" );
    }

    /*!
     Removes a link to the given content object id.
    */
    function removeContentObjectRelation( $objectID, $version = null )
    {
        $db =& eZDB::instance();
        $db->query( "DELETE FROM ezcontentobject_link WHERE from_contentobject_id='$this->ID' AND  from_contentobject_version='$version' AND to_contentobject_id='$objectID'" );
    }

    /*!
     Returns the related objects.
    */
    function &relatedContentObjectArray( $version = false )
    {
        if ( $version == false )
            $version = $this->CurrentVersion;

        $db =& eZDB::instance();
        $relatedObjects =& $db->arrayQuery( "SELECT
					       ezcontentobject.*
					     FROM
					       ezcontentobject, ezcontentobject_link
					     WHERE
					       ezcontentobject.id=ezcontentobject_link.to_contentobject_id AND
					       ezcontentobject_link.from_contentobject_id='$this->ID' AND
					       ezcontentobject_link.from_contentobject_version='$version'" );

        $return = array();
        foreach ( $relatedObjects as $object )
        {
            $return[] = new eZContentObject( $object );
        }
        return $return;
    }

    /*!
     Creates a new content object instance and stores it.
    */
    function &create( $name, $contentclassID, $userID, $sectionID = 0, $version = 1 )
    {
        $row = array(
            "name" => $name,
            "current_version" => $version,
            "contentclass_id" => $contentclassID,
            "permission_id" => 1,
            "parent_id" => 0,
            "main_node_id" => 0,
            "owner_id" => $userID,
            "section_id" => $sectionID );
        return new eZContentObject( $row );
    }

    /*!
     \return the parnet nodes for the current object.
    */
    function &parentNodes( $version = false, $asObject = true )
    {
        $retNodes = array();
        if ( $version )
        {
            if( is_numeric( $version ) )
            {
                $nodeAssignmentList =& eZNodeAssignment::fetchForObject( $this->attribute( 'id' ), $version );
            }
            else
            {
                $nodeAssignmentList =& eZNodeAssignment::fetchForObject( $this->attribute( 'id' ), $this->attribute( 'current_version' ) );
            }
            foreach ( array_keys( $nodeAssignmentList ) as $key )
            {
                $nodeAssignment =& $nodeAssignmentList[$key];
                if ( $asObject )
                {
                    $retNodes[] =& $nodeAssignment->attribute( 'parent_node_obj' );
                }
                else
                {
                    $retNodes[] =& $nodeAssignment->attribute( 'parent_node' );
                }
            }
            return $retNodes;
        }
        $nodes = $this->attribute( 'assigned_nodes' );
        //  $retNodes = array();
        if ( $asObject )
        {
            foreach ( $nodes as $node )
            {
                if ( $node->attribute( 'parent_node_id' ) != 1 )
                {
                    $retNodes[] =& eZContentObjectTreeNode::fetch( $node->attribute( 'parent_node_id' ) );
                }
            }
        }
        else
        {
            foreach ( $nodes as $node )
            {
                $retNodes[] = $node->attribute( 'parent_node_id' );
            }
        }
        var_dump($retNodes);
        return $retNodes;
    }

    /*!
     Returns the node assignments for the current object.
    */
    function assignedNodes( $asobject = true)
    {
        $contentobjectID = $this->attribute( 'id' );
        $query = "SELECT ezcontentobject.*,
			 ezcontentobject_tree.*,
			 ezcontentclass.name as class_name
		  FROM   ezcontentobject_tree,
			 ezcontentobject,
			 ezcontentclass
		  WHERE  contentobject_id=$contentobjectID AND
			 ezcontentobject_tree.contentobject_id=ezcontentobject.id  AND
			 ezcontentclass.version=0 AND
			 ezcontentclass.id = ezcontentobject.contentclass_id
		  ORDER BY path_string";
        $db =& eZDB::instance();
        $nodesListArray = $db->arrayQuery( $query );
        $nodes = eZContentObjectTreeNode::makeObjectsArray( $nodesListArray );
        return $nodes;
    }

    function mainNode()
    {
        return eZContentObjectTreeNode::fetch( $this->attribute( 'id' ) );
    }

    /*!
     Sets the permissions for this object.
    */
    function setPermissions( $permissionArray )
    {
        $this->Permissions =& $permissionArray;
    }

    /*!
     Returns the permission for the current object.
    */
    function permissions( )
    {
        return $this->Permissions;
    }



    function checkAccess( $functionName, $classID = false, $parentClassID = false )
    {
        $user =& eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );
        $accessResult =  $user->hasAccessTo( 'content' , $functionName );
        $accessWord = $accessResult['accessWord'];
        if ( ! $classID )
        {
            $classID = $this->attribute( 'contentclass_id' );
        }
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
            foreach ( array_keys( $policies ) as $key  )
            {
                $policy =& $policies[$key];
                $limitationList[] =& $policy->attribute( 'limitations' );
            }
            if ( count( $limitationList ) > 0 )
            {
                $access = 'denied';
                foreach ( array_keys( $limitationList ) as $key  )
                {
                    $limitationArray =& $limitationList[ $key ];
                    if ( $access == 'allowed' )
                    {
                        break;
                    }
                    foreach ( array_keys( $limitationArray ) as $key  )
//                    foreach ( $limitationArray as $limitation )
                    {
                        $limitation =& $limitationArray[$key];
//                        if ( $functionName == 'remove' )
//                        {
//                            eZDebug::writeNotice( $limitation, 'limitation in check access' );
//                        }

                        if ( $limitation->attribute( 'identifier' ) == 'Class' )
                        {
                            if ( $functionName == 'create' )
                            {
                                $access = 'allowed';
                            }
                            elseif ( in_array( $this->attribute( 'contentclass_id' ), $limitation->attribute( 'values_as_array' )  )  )
                            {
                                $access = 'allowed';
                            }
                            else
                            {
                                $access = 'denied';
                                break;
                            }
                        }
                        elseif ( $limitation->attribute( 'identifier' ) == 'ParentClass' )
                        {

                            if (  in_array( $this->attribute( 'contentclass_id' ), $limitation->attribute( 'values_as_array' )  ) )
                            {
                                $access = 'allowed';
                            }
                            else
                            {
                                $access = 'denied';
                                break;
                            }
                        }
                        elseif ( $limitation->attribute( 'identifier' ) == 'Section' )
                        {
                            if (  in_array( $this->attribute( 'section_id' ), $limitation->attribute( 'values_as_array' )  ) )
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
                            if ( $this->attribute( 'owner_id' ) == $userID )
                            {
                                $access = 'allowed';
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

    function classListFromLimitation( $limitationList )
    {
        $canCreateClassIDListPart = array();
        foreach ( $limitationList as $limitation )
        {
            if ( $limitation->attribute( 'identifier' ) == 'Class' )
            {
                $canCreateClassIDListPart =& $limitation->attribute( 'values_as_array' );
                $hasClassIDLimitation = true;
            }
            elseif ( $limitation->attribute( 'identifier' ) == 'Section' )
            {
                if ( !in_array( $this->attribute( 'section_id' ), $limitation->attribute( 'values_as_array' )  ) )
                {
                    return array();
                }
            }
            elseif ( $limitation->attribute( 'identifier' ) == 'ParentClass' )
            {
                if ( !in_array( $this->attribute( 'contentclass_id' ), $limitation->attribute( 'values_as_array' )  ) )
                {
                    return array();
                }
            }

            elseif ( $limitation->attribute( 'name' ) == 'Assigned' )
            {
                if ( $this->attribute( 'owner_id' ) != $user->attribute( 'contentobject_id' )  )
                {
                    return array();
                }
            }
        }
        if ( $hasClassIDLimitation )
        {
            return $canCreateClassIDListPart;
        }
        return '*';
    }

    function &canCreateClassList()
    {

//        eZDebug::writeNotice( $this, "object in canCreateClass" );
        $user =& eZUser::currentUser();
        $accessResult =  $user->hasAccessTo( 'content' , 'create' );
        $accessWord = $accessResult['accessWord'];

        if ( $accessWord == 'yes' )
        {
            $classList =& eZContentClass::fetchList( 0, false,false, null, array( 'id', 'name' ) );
//            eZDebug::writeNotice( $classList, 'can create everithing' );
            return $classList;
        }
        elseif ( $accessWord == 'no' )
        {
//            eZDebug::writeNotice( array(), 'can create nothing' );
            return array();
        }
        else
        {
            $policies  =& $accessResult['policies'];
            $classIDArray = array();
            foreach ( $policies as $policy )
            {
//                $classIDArrayPart = array();
                $limitationArray =& $policy->attribute( 'limitations' );
                $classIDArrayPart = $this->classListFromLimitation( $limitationArray );
                if ( $classIDArrayPart == '*' )
                {
                    $classList =& eZContentClass::fetchList( 0, false,false, null, array( 'id', 'name' ) );
//                    eZDebug::writeNotice( $classList, 'can create everything' );
                    return $classList;
                }else
                {
                    $classIDArray = array_merge( $classIDArray, array_diff( $classIDArrayPart, $classIDArray ) );
                    unset( $classIDArrayPart );
                }
            }
        }
        if( count( $classIDArray ) == 0  )
        {
//            eZDebug::writeNotice( array(), 'can create nothing' );
            return array();
        }
        $classList = array();
        // needs to be optimized
        $db = eZDb::instance();
        $classString = implode( ',', $classIDArray );
        $classList = $db->arrayQuery( "select id, name from ezcontentclass where id in ( $classString  )  and version = 0" );
//        eZDebug::writeNotice( $classList, 'can create some classes' );
        return $classList;
    }

    /*!
     Returns true if the current
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

    function canCreate( )
    {
        if ( !isset( $this->Permissions["can_create"] ) )
        {
            $this->Permissions["can_create"] = $this->checkAccess( 'create' );
        }
        $p = ( $this->Permissions["can_create"] == 1 );
        return $p;
    }


    function canEdit( )
    {
        if ( !isset( $this->Permissions["can_edit"] ) )
        {
            $this->Permissions["can_edit"] = $this->checkAccess( 'edit' );
        }
        $p = ( $this->Permissions["can_edit"] == 1 );
        return $p;
    }

    function canRemove( )
    {

        if ( !isset( $this->Permissions["can_remove"] ) )
        {
            $this->Permissions["can_remove"] = $this->checkAccess( 'remove' );
        }
        $p = ( $this->Permissions["can_remove"] == 1 );
        return $p;
    }

    function &className()
    {
        return $this->ClassName;
    }

    /*!
     Returns an array of the content actions which can be performed on
     the current object.
    */
    function &contentActionList( )
    {
        if ( $this->ContentObjectAttributeArray == false )
        {
            $this->ContentObjectAttributeArray =& $this->contentObjectAttributes();
        }

        $contentActionList = array();
        foreach ( $this->ContentObjectAttributeArray as $attribute )
        {
            $contentActionList = array_merge( $attribute->contentActionList(), $contentActionList );
        }
        $contentActionList = array_unique( $contentActionList );
        return $contentActionList;
    }

    function defaultLanguage()
    {
        $ini =& eZINI::instance();
        return $ini->variable( 'RegionalSettings', 'ContentObjectLocale' );
//         return eZLocale::currentLocaleCode();
    }

    /*!

    */
    function setClassName( $name )
    {
        $this->ClassName = $name;
    }

    /*!
     \returns an array with locale strings, these strings represents the languages which content objects are allowed to be translated into.
     \note the setting ContentSettings/TranslationList in site.ini determines the array.
     \sa translationList
    */
    function translationStringList()
    {
        $translationList =& $GLOBALS['eZContentTranslationStringList'];
        if ( isset( $translationList ) )
            return $translationList;
        $ini =& eZINI::instance();
        $translationList = $ini->variableArray( 'ContentSettings', 'TranslationList' );
        return $translationList;
    }

    /*!
     \returns an array with locale objects, these objects represents the languages the content objects are allowed to be translated into.
     \note the setting ContentSettings/TranslationList in site.ini determines the array.
     \sa translationStringList
    */
    function &translationList()
    {
        $translationList =& $GLOBALS['eZContentTranslationList'];
        if ( isset( $translationList ) )
            return $translationList;
        $translationList = array();
        $translationStringList = eZContentObject::translationStringList();
        foreach ( $translationStringList as $translationString )
        {
            $translationList[] =& eZLocale::instance( $translationString );
        }
        return $translationList;
    }

    /*!
     \return a DOM structure of the content object and it's attributes.
    */
    function &serialize()
    {
        include_once( 'lib/ezxml/classes/ezdomdocument.php' );
        include_once( 'lib/ezxml/classes/ezdomnode.php' );
        $objectNode = new eZDOMNode();

        $objectNode->setName( 'object' );
        $objectNode->appendAttribute( eZDOMDocument::createAttributeNode( 'id', $this->ID ) );
        $attributes =& $this->contentObjectAttributes();

        foreach ( $attributes as $attribute )
        {
            $objectNode->appendChild( $attribute->serialize() );
        }
        return $objectNode;
    }

    var $ID;
    var $Name;

    /// Stores the current language
    var $CurrentLanguage;

    /// Stores the current permissions
    var $ClassName;

    /// Contains the current attributes
    var $ContentObjectAttributeArray = false;

    /// Contains the datamap for content object attributes
    var $DataMap = false;

}

?>
