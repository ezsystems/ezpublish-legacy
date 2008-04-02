<?php
//
// Definition of eZContentObjectVersion class
//
// Created on: <18-Apr-2002 10:05:34 bf>
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
define( "EZ_VERSION_STATUS_INTERNAL_DRAFT", 5 );
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

    function definition()
    {
        return array( "fields" => array( 'id' =>  array( 'name' => 'ID',
                                                         'datatype' => 'integer',
                                                         'default' => 0,
                                                         'required' => true ),
                                         'contentobject_id' =>  array( 'name' => 'ContentObjectID',
                                                                       'datatype' => 'integer',
                                                                       'default' => 0,
                                                                       'required' => true,
                                                                       'foreign_class' => 'eZContentObject',
                                                                       'foreign_attribute' => 'id',
                                                                       'multiplicity' => '1..*' ),
                                         'creator_id' =>  array( 'name' => 'CreatorID',
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true,
                                                                 'foreign_class' => 'eZUser',
                                                                 'foreign_attribute' => 'id',
                                                                 'multiplicity' => '1..*' ),
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
                                                                         'required' => true ),
                                         'user_id' =>  array( 'name' => 'UserID',
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true,
                                                              'foreign_class' => 'eZUser',
                                                              'foreign_attribute' => 'contentobject_id',
                                                              'multiplicity' => '1..*' ),
                                         'language_mask' => array( 'name' => 'LanguageMask',
                                                                   'datatype' => 'integer',
                                                                   'default' => 0,
                                                                   'required' => true ),
                                         'initial_language_id' => array( 'name' => 'InitialLanguageID',
                                                                         'datatype' => 'integer',
                                                                         'default' => 0,
                                                                         'required' => true,
                                                                         'foreign_class' => 'eZContentLanguage',
                                                                         'foreign_attribute' => 'id',
                                                                         'multiplicity' => '1..*' ) ),
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
                                                      'can_remove' => 'canVersionRemove',
                                                      "data_map" => "dataMap",
                                                      'node_assignments' => 'nodeAssignments',
                                                      'contentobject' => 'contentObject',
                                                      'initial_language' => 'initialLanguage',
                                                      'language_list' => 'translations',
                                                      'translation' => 'translation',
                                                      'translation_list' => 'defaultTranslationList',
                                                      'complete_translation_list' => 'translationList',
                                                      'temp_main_node' => 'tempMainNode' ),
                      'class_name' => "eZContentObjectVersion",
                      "increment_key" => "id",
                      'sort' => array( 'version' => 'asc' ),
                      'name' => 'ezcontentobject_version' );
    }

    function statusList( $limit = false )
    {
        if ( $limit == 'remove' )
        {
            $versions = array( array( 'name' => 'Draft', 'id' =>  EZ_VERSION_STATUS_DRAFT ),
                               array( 'name' => 'Pending', 'id' =>  EZ_VERSION_STATUS_PENDING ),
                               array( 'name' => 'Archived', 'id' =>  EZ_VERSION_STATUS_ARCHIVED ),
                               array( 'name' => 'Rejected', 'id' =>  EZ_VERSION_STATUS_REJECTED ) );
        }
        else
        {
            $versions = array( array( 'name' => 'Draft', 'id' =>  EZ_VERSION_STATUS_DRAFT ),
                               array( 'name' => 'Published', 'id' =>  EZ_VERSION_STATUS_PUBLISHED ),
                               array( 'name' => 'Pending', 'id' =>  EZ_VERSION_STATUS_PENDING ),
                               array( 'name' => 'Archived', 'id' =>  EZ_VERSION_STATUS_ARCHIVED ),
                               array( 'name' => 'Rejected', 'id' =>  EZ_VERSION_STATUS_REJECTED ) );
        }
        return $versions;
    }
    /*!
     \return true if the requested attribute exists in object.
    */

    function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZContentObjectVersion::definition(),
                                                null,
                                                array( 'id' => $id ),
                                                $asObject );
    }

    function fetchVersion( $version, $contentObjectID, $asObject = true )
    {
        $ret = eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                    null, array( "version" => $version,
                                                                 "contentobject_id" => $contentObjectID
                                                                 ),
                                                    null, null,
                                                    $asObject );
        return isset( $ret[0] ) ? $ret[0] : false;
    }

    function fetchUserDraft( $objectID, $userID )
    {
        $versions = eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                          null, array( 'creator_id' => $userID,
                                                                       'contentobject_id' => $objectID,
                                                                       'status' => array( array( EZ_VERSION_STATUS_DRAFT, EZ_VERSION_STATUS_INTERNAL_DRAFT ) ) ),
                                                          array( 'version' => 'asc' ), null,
                                                          true );
        if ( $versions === null or
             count( $versions ) == 0 )
            return null;
        return $versions[0];
    }

    function fetchForUser( $userID, $status = EZ_VERSION_STATUS_DRAFT )
    {
        return eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                    null, array( 'creator_id' => $userID,
                                                                 'status' => $status
                                                                 ),
                                                    null, null,
                                                    true );
    }

    function fetchFiltered( $filters, $offset, $limit )
    {
        $limits = null;
        if ( $offset or $limit )
            $limits = array( 'offset' => $offset,
                             'length' => $limit );
        return eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                    null, $filters,
                                                    null, $limits,
                                                    true );
    }

    /*!
     \return an eZContentObjectTreeNode object which doesn't really exist in the DB,
             this can be passed to a node view template.
    */
    function &tempMainNode()
    {
        if ( $this->TempNode !== null )
            return $this->TempNode;
        $object =& $this->contentObject();
        if ( $object->attribute( 'status' ) == EZ_CONTENT_OBJECT_STATUS_DRAFT )
        {
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
            if ( $mainNodeAssignment )
            {
                $this->TempNode =& $mainNodeAssignment->tempNode();
            }
        }
        else if ( $object->attribute( 'status' ) == EZ_CONTENT_OBJECT_STATUS_PUBLISHED )
        {
            $mainNode =& $object->mainNode();
            if ( is_object( $mainNode ) )
            {
                $this->TempNode = eZContentObjectTreeNode::create( $mainNode->attribute( 'parent_node_id' ),
                                                                   $mainNode->attribute( 'contentobject_id' ),
                                                                   $this->attribute( 'version' ),
                                                                   $mainNode->attribute( 'sort_field' ),
                                                                   $mainNode->attribute( 'sort_order' ) );
                $this->TempNode->setName( $mainNode->Name );
            }
        }
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
        if ( $lang !== false )
        {
            $contentObject =& $this->contentObject();
            if ( $contentObject )
            {
                $tmpVar = $contentObject->name( $lang ); //avoiding Notice "Only variable references should be returned by reference"
                return $tmpVar;
            }
        }
        if ( $this->VersionName === false )
        {
            $contentObject =& $this->contentObject();
            if ( $contentObject )
            {
                $this->VersionName = $contentObject->name( false, $lang );
            }
        }
        return $this->VersionName;
    }

    /*!
     \return the name of the current version, optionally in the specific language \a $lang
    */
    function &versionName( $lang = false )
    {
        if ( !$lang )
        {
            $lang = $this->initialLanguageCode();
        }

        if ( isset( $this->VersionNameCache[$lang] ) )
            return $this->VersionNameCache[$lang];

        $object =& $this->attribute( 'contentobject' );
        if ( !$object )
        {
            $retValue = false;
            return $retValue;
        }

        $class =& $object->attribute( 'content_class' );
        if ( !$class )
        {
            $retValue = false;
            return $retValue;
        }

        $this->VersionNameCache[$lang] = $class->contentObjectName( $object,
                                                                    $this->attribute( 'version' ),
                                                                    $lang );
        return $this->VersionNameCache[$lang];
    }

    /*!
     \return \c true if the current user can read this version of the object.
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &canVersionRead( )
    {
        if ( !isset( $this->Permissions["can_versionread"] ) )
        {
            $this->Permissions["can_versionread"] = $this->checkAccess( 'versionread' );
        }
        $p = ( $this->Permissions["can_versionread"] == 1 );
        return $p;
    }

    /*!
     \return \c true if the current user can remove this version of the object.
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &canVersionRemove( )
    {
        if ( !isset( $this->Permissions['can_versionremove'] ) )
        {
            $this->Permissions['can_versionremove'] = $this->checkAccess( 'versionremove' );
        }
        $p = ( $this->Permissions['can_versionremove'] == 1 );
        return $p;
    }

    function checkAccess( $functionName, $originalClassID = false, $parentClassID = false, $returnAccessList = false, $language = false )
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

        // 'create' is not allowed on versions
        if ( $functionName == 'create' )
        {
            return false;
        }

        if ( $functionName == 'edit' )
        {
            // Extra checking for status and ownership
            if ( !in_array( $this->attribute( 'status' ),
                            array( EZ_VERSION_STATUS_DRAFT,
                                   EZ_VERSION_STATUS_INTERNAL_DRAFT,
                                   EZ_VERSION_STATUS_PENDING ) ) ||
                 $this->attribute( 'creator_id' ) != $userID )
            {
                return false;
            }
            return true;
        }

        if ( $functionName == 'versionremove' and $this->attribute( 'status' ) == EZ_VERSION_STATUS_PUBLISHED )
        {
            return 0;
        }
//         eZDebug::writeDebug( $accessWord, 'accessword' );
        if ( $accessWord == 'yes' )
        {
            return 1;
        }
        elseif ( $accessWord == 'no' )
        {
            if ( $functionName == 'edit' )
            {
                // Check if we have 'create' access under the main parent
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
            $policies  =& $accessResult['policies'];
//             eZDebug::writeDebug( $policies, 'policies' );
            foreach ( array_keys( $policies ) as $key  )
            {
                $limitationList[] =& $policies[$key];
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
                    foreach ( $limitationArray as $key => $limitation )
                    {
                        $access = 'denied';

                        if ( $key == 'Class' )
                        {
                            if ( $functionName == 'create' and !$originalClassID )
                                $access = 'allowed';
                            else if ( $functionName == 'create' and in_array( $classID, $limitation ) )
                                $access = 'allowed';
                            elseif ( in_array( $objectClassID, $limitation ) )
                                $access = 'allowed';
                            else
                            {
                                $access = 'denied';
                                break;
                            }
                        }
                        elseif ( $key == 'Status' )
                        {
                            if (  in_array( $this->attribute( 'status' ), $limitation ) )
                                $access = 'allowed';
                            else
                            {
                                $access = 'denied';
                                break;
                            }
                        }
                        elseif ( $key == 'Section' || $key == 'User_Section' )
                        {
                            if (  in_array( $object->attribute( 'section_id' ), $limitation ) )
                                $access = 'allowed';
                            else
                            {
                                $access = 'denied';
                                break;
                            }
                        }
                        elseif (  $key == 'Language' )
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
                                        $languageMask = $this->attribute( 'initial_language_id' );
                                        // We are restricting language check to just one language
                                        // If the specified language is not the one in the version
                                        // it will become 0.
                                        $languageMask &= $language;
                                    }
                                }
                                else
                                {
                                    $languageMask = $this->attribute( 'initial_language_id' );
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
                            }
                        }
                        elseif ( $key == 'Owner' )
                        {
                            if ( $this->attribute( 'creator_id' ) == $userID )
                                $access = 'allowed';
                            else
                            {
                                $access = 'denied';
                                break;
                            }
                        }
                        elseif ( $key == 'Node' )
                        {
                            $accessNode = false;
                            $contentObjectID = $this->attribute( 'contentobject_id' );
                            foreach ( $limitation as $nodeID )
                            {
                                $node = eZContentObjectTreeNode::fetch( $nodeID, false, false );
                                $limitationObjectID = $node['contentobject_id'];
                                if ( $contentObjectID == $limitationObjectID )
                                {
                                    $access = 'allowed';
                                    $accessNode = true;
                                    break;
                                }
                            }
                            if ( $access == 'denied' && $checkedSubtree && !$accessSubtree )
                            {
                                break;
                            }
                            else
                            {
                                $access = 'allowed';
                            }
                            $checkedNode = true;
                        }
                        elseif ( $key == 'Subtree' )
                        {
                            $accessSubtree = false;
                            $contentObject = $this->attribute( 'contentobject' );
                            foreach ( $contentObject->attribute( 'assigned_nodes' ) as  $assignedNode )
                            {
                                $path = $assignedNode->attribute( 'path_string' );
                                $subtreeArray =& $limitation;
                                foreach ( $subtreeArray as $subtreeString )
                                {
                                    if ( strstr( $path, $subtreeString ) )
                                    {
                                        $access = 'allowed';
                                        $accessSubtree = true;
                                        break;
                                    }
                                }
                                if ( $access == 'allowed' )
                                {
                                    break;
                                }
                            }
                            if ( $access != 'allowed' )
                            {
                                foreach( $this->attribute( 'node_assignments' ) as $nodeAssignment )
                                {
                                    $parentNode = $nodeAssignment->attribute( 'parent_node_obj' );
                                    $path = $parentNode->attribute( 'path_string' );
                                    $subtreeArray =& $limitation;
                                    foreach ( $subtreeArray as $subtreeString )
                                    {
                                        if ( strstr( $path, $subtreeString ) )
                                        {
                                            $access = 'allowed';
                                            $accessSubtree = true;
                                            break;
                                        }
                                    }
                                    if ( $access == 'allowed' )
                                    {
                                        break;
                                    }
                                }
                            }
                            if ( $access == 'denied' && $checkedNode && !$accessNode )
                            {
                                break;
                            }
                            else
                            {
                                $access = 'allowed';
                            }
                            $checkedSubtree = true;
                        }
                        elseif ( $key == 'User_Subtree' )
                        {
                            $contentObject = $this->attribute( 'contentobject' );
                            foreach ( $contentObject->attribute( 'assigned_nodes' ) as  $assignedNode )
                            {
                                $path = $assignedNode->attribute( 'path_string' );
                                $subtreeArray =& $limitation;
                                foreach ( $subtreeArray as $subtreeString )
                                {
                                    if ( strstr( $path, $subtreeString ) )
                                    {
                                        $access = 'allowed';
                                        $accessSubtree = true;
                                        break;
                                    }
                                }
                                if ( $access == 'allowed' )
                                {
                                    break;
                                }
                            }
                            if ( $access != 'allowed' )
                            {
                                foreach( $this->attribute( 'node_assignments' ) as $nodeAssignment )
                                {
                                    $parentNode = $nodeAssignment->attribute( 'parent_node_obj' );
                                    $path = $parentNode->attribute( 'path_string' );
                                    $subtreeArray =& $limitation;
                                    foreach ( $subtreeArray as $subtreeString )
                                    {
                                        if ( strstr( $path, $subtreeString ) )
                                        {
                                            $access = 'allowed';
                                            break;
                                        }
                                    }
                                    if ( $access == 'allowed' )
                                    {
                                        break;
                                    }
                                }
                            }
                            if ( $access == 'denied' )
                            {
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

    /*!
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &mainParentNodeID()
    {
        $temp = eZNodeAssignment::fetchForObject( $this->attribute( 'contentobject_id' ), $this->attribute( 'version' ), 1 );
        if ( $temp == null )
        {
            $mainParentNodeID = 1;
        }
        else
        {
            $mainParentNodeID = $temp[0]->attribute( 'parent_node' );
        }
        return $mainParentNodeID;
    }

    function &parentNodes( )
    {
        $retNodes = array();
        $nodeAssignmentList = eZNodeAssignment::fetchForObject( $this->attribute( 'contentobject_id' ), $this->attribute( 'version' ) );
        foreach ( array_keys( $nodeAssignmentList ) as $key )
        {
            $nodeAssignment =& $nodeAssignmentList[$key];
            $retNodes[] =& $nodeAssignment;
        }
        return $retNodes;
    }
    function &nodeAssignments()
    {
        $nodeAssignmentList = eZNodeAssignment::fetchForObject( $this->attribute( 'contentobject_id' ), $this->attribute( 'version' ) );
        return $nodeAssignmentList;
    }

    function &assignToNode( $nodeID, $main = 0, $fromNodeID = 0, $sortField = null, $sortOrder = null,
                            $remoteID = 0 )
    {
        if ( $fromNodeID == 0 && ( $this->attribute( 'status' ) == EZ_VERSION_STATUS_DRAFT ||
                                   $this->attribute( 'status' ) == EZ_VERSION_STATUS_INTERNAL_DRAFT ) )
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

        $nodeAssignment = eZNodeAssignment::create( $nodeRow );
        $nodeAssignment->store();
        return $nodeAssignment;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function removeAssignment( $nodeID )
    {
        $nodeAssignmentList =& $this->attribute( 'node_assignments' );
        $db =& eZDb::instance();
        $db->begin();

        foreach ( array_keys( $nodeAssignmentList ) as $key  )
        {
            $nodeAssignment =& $nodeAssignmentList[$key];
            if ( $nodeAssignment->attribute( 'parent_node' ) == $nodeID )
            {
                $nodeAssignment->remove();
            }
        }
        $db->commit();
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
            foreach( $data as $key => $item )
            {
                $identifier = $item->contentClassAttributeIdentifier();
                $ret[$identifier] =& $data[$key];
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
        $relatedArray =& eZContentObject::relatedContentObjectArray( $this->Version, $objectID );
        return $relatedArray;
    }

    function create( $contentobjectID, $userID = false, $version = 1, $initialLanguageCode = false )
    {
        if ( $userID === false )
        {
            $user =& eZUser::currentUser();
            $userID =& $user->attribute( 'contentobject_id' );
        }
        $time = time();

        if ( $initialLanguageCode == false )
        {
            $initialLanguageCode = eZContentObject::defaultLanguage();
        }

        $initialLanguageID = eZContentLanguage::idByLocale( $initialLanguageCode );

        $row = array(
            "contentobject_id" => $contentobjectID,
            'initial_language_id' => $initialLanguageID,
            'language_mask' => 0,
            "version" => $version,
            "created" => $time,
            "modified" => $time,
            'creator_id' => $userID );
        return new eZContentObjectVersion( $row );
    }

    /*!
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &reverseRelatedObjectList()
    {
        $objectID = $this->attribute( 'contentobject_id' );
        $reverseRelatedArray =& eZContentObject::reverseRelatedObjectList( $this->Version, $objectID );
        return $reverseRelatedArray;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function remove()
    {
        $contentobjectID = $this->attribute( 'contentobject_id' );
        $versionNum = $this->attribute( 'version' );

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
        $db =& eZDB::instance();
        $db->begin();
        $db->query( "DELETE FROM ezcontentobject_link
                         WHERE from_contentobject_id=$contentobjectID AND from_contentobject_version=$versionNum" );
        $db->query( "DELETE FROM eznode_assignment
                         WHERE contentobject_id=$contentobjectID AND contentobject_version=$versionNum" );

        $db->query( 'DELETE FROM ezcontentobject_version
                         WHERE id=' . $this->attribute( 'id' ) );

        $contentobject = $this->attribute( 'contentobject' );
        if ( is_object( $contentobject ) )
        {
            if ( !$contentobject->hasRemainingVersions() )
            {
                $contentobject->purge();
            }

            $version = $contentobject->CurrentVersion;
            if ( $contentobject->CurrentVersion == $versionNum ) //will assign another current_version in contetnObject.
            {
               //search for version that will be current after removing of this one.
               $candidateToBeCurrent = $db->arrayQuery( "SELECT version
                                                 FROM ezcontentobject_version
                                                 WHERE contentobject_id={$contentobject->ID} AND
                                                       version!={$contentobject->CurrentVersion}
                                                 ORDER BY modified DESC",
                                             array( 'offset' => 0, 'limit' => 1 ) );

               if ( isset($candidateToBeCurrent[0]['version']) && is_numeric($candidateToBeCurrent[0]['version']) )
               {
                   $contentobject->CurrentVersion = $candidateToBeCurrent[0]['version'];
                   $contentobject->store();
               }
            }
        }
        $db->query( "DELETE FROM ezcontentobject_name
                         WHERE contentobject_id=$contentobjectID AND content_version=$versionNum" );

        $db->commit();
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function removeTranslation( $languageCode )
    {
//         eZDebug::writeDebug( $this, 'removeTranslation:version' );
        $versionNum = $this->attribute( 'version' );

        $contentObjectAttributes =& $this->contentObjectAttributes( $languageCode );

        $db =& eZDB::instance();
        $db->begin();
        foreach ( array_keys( $contentObjectAttributes ) as $attributeKey )
        {
            $attribute =& $contentObjectAttributes[$attributeKey];
            $attribute->remove( $attribute->attribute( 'id' ), $versionNum );
        }
        $db->commit();

        $this->updateLanguageMask();
    }

    /*!
     \static
     Will remove all version that match the status set in \a $versionStatus.
     \param $versionStatus can either be a single value or an array with values,
                           if \c false the function will remove all status except published.
     \param $limit limits count of versions which should be removed.
     \param $expiryTime if not false then method will remove only versions which have modified time less than specified expiry time.
     \param $fetchPortionSize portion size for single fetch() call to avoid memory overflow erros (default 50).
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function removeVersions( $versionStatus = false, $limit = false, $expiryTime = false, $fetchPortionSize = 50 )
    {
        $statuses = array( EZ_VERSION_STATUS_DRAFT,
                           EZ_VERSION_STATUS_PENDING,
                           EZ_VERSION_STATUS_ARCHIVED,
                           EZ_VERSION_STATUS_REJECTED,
                           EZ_VERSION_STATUS_INTERNAL_DRAFT );
        if ( $versionStatus === false )
        {
            $versionStatus = $statuses;
        }
        else if ( !is_array( $versionStatus ) )
        {
            $versionStatus = array( $versionStatus );
        }

        $versionStatus = array_unique( $versionStatus );
        $checkIntersect = array_intersect( $versionStatus, $statuses );
        if ( count( $checkIntersect ) != count( $versionStatus ) )
        {
            eZDebug::writeError( 'Invalid version status was passed in.', 'eZContentObjectVersion::removeVersions()' );
            return false;
        }

        if ( $limit !== false and ( !is_numeric( $limit ) or $limit < 0 ) )
        {
            eZDebug::writeError( '$limit must be either false or positive numeric value.', 'eZContentObjectVersion::removeVersions()' );
            return false;
        }

        if ( !is_numeric( $fetchPortionSize ) or $fetchPortionSize < 1 )
            $fetchPortionSize = 50;

        $filters = array();
        $filters['status'] = array( $versionStatus );
        if ( $expiryTime !== false )
            $filters['modified'] = array( '<', $expiryTime );

        $processedCount = 0;
        while ( $processedCount < $limit or !$limit )
        {
            // fetch by versions by preset portion at a time to avoid memory overflow
            $tmpLimit = ( !$limit or ( $limit - $processedCount ) > $fetchPortionSize ) ?
                            $fetchPortionSize : $limit - $processedCount;
            $versions = eZContentObjectVersion::fetchFiltered( $filters, 0, $tmpLimit );
            if ( count( $versions ) < 1 )
                break;

            $db =& eZDB::instance();
            $db->begin();
            foreach ( $versions as $version )
            {
                $version->remove();
            }
            $db->commit();
            $processedCount += count( $versions );
        }
        return $processedCount;
    }

    /*!
     Clones the version with new version \a $newVersionNumber and creator \a $userID
     \note The cloned version is not stored.
    */
    function clone( $newVersionNumber, $userID, $contentObjectID = false, $status = EZ_VERSION_STATUS_DRAFT )
    {
        $time = time();
        $clonedVersion = $this;
        $clonedVersion->setAttribute( 'id', null );
        if ( $contentObjectID !== false )
            $clonedVersion->setAttribute( 'contentobject_id', $contentObjectID );
        $clonedVersion->setAttribute( 'version', $newVersionNumber );
        $clonedVersion->setAttribute( 'created', $time );
        $clonedVersion->setAttribute( 'modified', $time );
        $clonedVersion->setAttribute( 'creator_id', $userID );
        if ( $status !== false )
            $clonedVersion->setAttribute( 'status', $status );
        return $clonedVersion;
    }

    /*!
     \return An array with all the translations for the current version.
     \note The reference for the return value is required to workaround
           a bug with PHP references.
    */
    function &translations( $asObject = true )
    {
        $translationList =& $this->translationList( false, $asObject );
        return $translationList;
    }

    /*!
     \return An array with all the translations for the current version.
     \note The reference for the return value is required to workaround
           a bug with PHP references.
     \deprecated
    */
    function &translation( $asObject = true )
    {
        $translation = false;
        return $translation;
    }

    /*!
     \return An array with all the translations for the current version.
     \note The reference for the return value is required to workaround
           a bug with PHP references.

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
           the calls within a db transaction; thus within db->begin and db->commit.
    */
    function &translationList( $language = false, $asObject = true )
    {
        $db =& eZDB::instance();

        $languageSQL = '';
        if ( $language !== false )
        {
            $language = $db->escapeString( $language );
            $languageSQL = "AND language_code!='$language'";
        }

        $query = "SELECT DISTINCT language_code
                  FROM ezcontentobject_attribute
                  WHERE contentobject_id='$this->ContentObjectID' AND version='$this->Version'
                  $languageSQL
                  ORDER BY language_code";

        $languageCodes = $db->arrayQuery( $query );

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
     \return An array with all translations except default language for the this version.
     \note The reference for the return value is required to workaround
           a bug with PHP references.

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
           the calls within a db transaction; thus within db->begin and db->commit.
    */
    function &defaultTranslationList()
    {
        $defaultTranslationList = $this->translationList();
        return $defaultTranslationList;
    }

    /*!
     Returns the attributes for the current content object version.
     If \a $language is not specified it will use the initial language of the version.
    */
    function &contentObjectAttributes( $languageCode = false, $asObject = true )
    {
        if ( $languageCode == false )
        {
            if ( $this->Status == EZ_VERSION_STATUS_DRAFT || $this->Status == EZ_VERSION_STATUS_INTERNAL_DRAFT || $this->Status == EZ_VERSION_STATUS_PENDING )
            {
                $languageCode = $this->initialLanguageCode();
            }
            else if ( $this->CurrentLanguage )
            {
                $languageCode = $this->CurrentLanguage;
            }
        }

        $attributes = eZContentObjectVersion::fetchAttributes( $this->Version, $this->ContentObjectID, $languageCode, $asObject );
        return $attributes;
    }

    /*!
     Returns the attributes for the content object version \a $version and content object \a $contentObjectID.
     \a $language defines the language to fetch.
     \static
     \sa attributes
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function fetchAttributes( $version, $contentObjectID, $language = false, $asObject = true )
    {
        $db =& eZDB::instance();
        $language = $db->escapeString( $language );
        $contentObjectID = (int) $contentObjectID;
        $version =(int) $version;
        $query = "SELECT ezcontentobject_attribute.*, ezcontentclass_attribute.identifier as classattribute_identifier,
                        ezcontentclass_attribute.can_translate, ezcontentclass_attribute.serialized_name_list as attribute_serialized_name_list
                  FROM  ezcontentobject_attribute, ezcontentclass_attribute, ezcontentobject_version
                  WHERE
                    ezcontentclass_attribute.version = '0' AND
                    ezcontentclass_attribute.id = ezcontentobject_attribute.contentclassattribute_id AND
                    ezcontentobject_attribute.version = '$version' AND
                    ezcontentobject_attribute.contentobject_id = '$contentObjectID' AND
                    ezcontentobject_version.contentobject_id = '$contentObjectID' AND
                    ezcontentobject_version.version = '$version' AND ".
                    ( ( $language )? "ezcontentobject_attribute.language_code = '$language'": eZContentLanguage::sqlFilter( 'ezcontentobject_attribute', 'ezcontentobject_version' ) ).
                  " ORDER by
                    ezcontentclass_attribute.placement ASC";

        $attributeArray = $db->arrayQuery( $query );

        $returnAttributeArray = array();
        foreach ( $attributeArray as $attribute )
        {
            $attr = new eZContentObjectAttribute( $attribute );

            $attr->setContentClassAttributeIdentifier( $attribute['classattribute_identifier'] );

            $dataType = $attr->dataType();

            if ( is_object( $dataType ) &&
                 $dataType->Attributes["properties"]["translation_allowed"] &&
                 $attribute['can_translate'] )
                $attr->setContentClassAttributeCanTranslate( 1 );
            else
                $attr->setContentClassAttributeCanTranslate( 0 );

            $attr->setContentClassAttributeName( eZContentClassAttribute::nameFromSerializedString( $attribute['attribute_serialized_name_list'] ) );

            $returnAttributeArray[] = $attr;
        }
        return $returnAttributeArray;
    }

    /*!
     \private
     Maps input lange to another one if defined in $options['language_map'].
     If it cannot map it returns the original language.
     \returns string
     */
    function mapLanguage( $language, $options )
    {
        if ( isset( $options['language_map'][$language] ) )
        {
            return $options['language_map'][$language];
        }
        return $language;
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
     \param package

     \returns created object, false if could not create object/xml invalid
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function &unserialize( &$domNode, &$contentObject, $ownerID, $sectionID, $activeVersion, $firstVersion, &$nodeList, &$options, &$package, $handlerType = 'ezcontentobject' )
    {
        $oldVersion = $domNode->attributeValue( 'version' );
        $status = $domNode->attributeValue( 'status' );
        $languageNodeArray = $domNode->elementsByName( 'object-translation' );

        $initialLanguage   = false;
        $importedLanguages = $options['language_array'];
        $currentLanguages  = array();
        foreach( $languageNodeArray as $languageNode )
        {
            $language = eZContentObjectVersion::mapLanguage( $languageNode->attributeValue( 'language' ), $options );
            if ( in_array( $language, $importedLanguages ) )
            {
                $currentLanguages[] = $language;
            }
        }
        foreach ( eZContentLanguage::prioritizedLanguages() as $language )
        {
            if ( in_array( $language->attribute( 'locale' ), $currentLanguages ) )
            {
                $initialLanguage = $language->attribute( 'locale' );
                break;
            }
        }
        if ( !$initialLanguage )
        {
            $initialLanguage = $currentLanguages[0];
        }

        if ( $firstVersion )
        {
            $contentObjectVersion = $contentObject->version( 1 );
        }
        else
        {
            // Create new version in specific language but with empty data.
            $contentObjectVersion = $contentObject->createNewVersionIn( $initialLanguage );
        }

        include_once( 'lib/ezlocale/classes/ezdateutils.php' );
        $created = eZDateUtils::textToDate( $domNode->attributeValue( 'created' ) );
        $modified = eZDateUtils::textToDate( $domNode->attributeValue( 'modified' ) );
        $contentObjectVersion->setAttribute( 'created', $created );
        $contentObjectVersion->setAttribute( 'modified', $modified );

        $contentObjectVersion->setAttribute( 'status', EZ_VERSION_STATUS_DRAFT );
        $contentObjectVersion->store();

        $db =& eZDB::instance();
        $db->begin();
        foreach( $languageNodeArray as $languageNode )
        {
            $language = eZContentObjectVersion::mapLanguage( $languageNode->attributeValue( 'language' ), $options );
            // Only import allowed languages.
            if ( !in_array( $language, $importedLanguages ) )
            {
                continue;
            }

            $attributeArray =& $contentObjectVersion->contentObjectAttributes( $language );
            if ( count( $attributeArray ) == 0)
            {
                $hasTranslation = eZContentLanguage::fetchByLocale( $language );

                if ( !$hasTranslation )
                {
                    // if there is no needed translation in system then add it
                    $locale =& eZLocale::instance( $language );

                    if ( $locale->isValid() )
                    {
                        eZContentLanguage::addLanguage( $locale->localeCode(), $locale->internationalLanguageName() );
                        $hasTranslation = true;
                    }
                    else
                        $hasTranslation = false;
                }

                if ( $hasTranslation )
                {
                    // Add translated attributes for the translation
                    $originalContentAttributes =& $contentObjectVersion->contentObjectAttributes( $initialLanguage );
                    foreach ( array_keys( $originalContentAttributes ) as $contentAttributeKey )
                    {
                        $originalContentAttribute =& $originalContentAttributes[$contentAttributeKey];
                        $contentAttribute =& $originalContentAttribute->translateTo( $language );
                        $contentAttribute->sync();
                        $attributeArray[] =& $contentAttribute;
                    }
                }

                // unserialize object name in current version-translation
                $objectName = $languageNode->attributeValue( 'object_name' );
                if ( $objectName )
                    $contentObject->setName( $objectName, $contentObjectVersion->attribute( 'version' ), $language );
            }

            foreach( array_keys( $attributeArray ) as $attributeKey )
            {
                $attribute =& $attributeArray[$attributeKey];

                $attributeIdentifier =& $attribute->attribute( 'contentclass_attribute_identifier' );

                $attributeDomNode =& $languageNode->elementByAttributeValue( 'identifier', $attributeIdentifier );
                if ( !$attributeDomNode )
                {
                    continue;
                }

                $attribute->unserialize( $package, $attributeDomNode );
                $attribute->store();
            }
        }

        $objectRelationList = $domNode->elementByName( 'object-relation-list' );
        if ( $objectRelationList )
        {
            $objectRelationArray = $objectRelationList->elementsByName( 'related-object-remote-id' );
            foreach( $objectRelationArray as $objectRelation )
            {
                $relatedObjectRemoteID = $objectRelation->textContent();
                if ( $relatedObjectRemoteID )
                {
                    $object = eZContentObject::fetchByRemoteID( $relatedObjectRemoteID );
                    $relatedObjectID = ( $object !== null ) ? $object->attribute( 'id' ) : null;

                    if ( $relatedObjectID )
                    {
                        $contentObject->addContentObjectRelation( $relatedObjectID, $contentObjectVersion->attribute( 'version' ) );
                    }
                    else
                    {
                        if ( !isset( $options['suspended-relations'] ) )
                        {
                            $options['suspended-relations'] = array();
                        }

                        $options['suspended-relations'][] = array( 'related-object-remote-id' => $relatedObjectRemoteID,
                                                                   'contentobject-id'         => $contentObject->attribute( 'id' ),
                                                                   'contentobject-version'    => $contentObjectVersion->attribute( 'version' ) );
                    }
                }
            }
        }

        $nodeAssignmentNodeList = $domNode->elementByName( 'node-assignment-list' );
        $nodeAssignmentNodeArray = $nodeAssignmentNodeList->elementsByName( 'node-assignment' );
        foreach( $nodeAssignmentNodeArray as $nodeAssignmentNode )
        {
            $result = eZContentObjectTreeNode::unserialize( $nodeAssignmentNode,
                                                            $contentObject,
                                                            $contentObjectVersion->attribute( 'version' ),
                                                            ( $oldVersion == $activeVersion ? 1 : 0 ),
                                                            $nodeList,
                                                            $options,
                                                            $handlerType );
            if ( $result === false )
            {
                $db->commit();
                $retValue = false;
                return $retValue;
            }
        }

        $contentObjectVersion->store();
        $db->commit();

        return $contentObjectVersion;
    }

    function postUnserialize( &$package )
    {
        $transltaions =& $this->translations( false );
        foreach( $transltaions as $language )
        {
            $attributeArray =& $this->contentObjectAttributes( $language );
            foreach( array_keys( $attributeArray ) as $key )
            {
                $attribute =& $attributeArray[$key];
                $attribute->postUnserialize( $package );
            }
        }
    }

    /*!
     \return a DOM structure of the content object version, it's translations and attributes.

     \param package
     \param package options ( optianal )
     \param array of allowed nodes ( optional )
     \param array of top nodes in current package export (optional )
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function serialize( &$package, $options = false, $contentNodeIDArray = false, $topNodeIDArray = false )
    {
        include_once( 'lib/ezxml/classes/ezdomdocument.php' );
        include_once( 'lib/ezxml/classes/ezdomnode.php' );
        $versionNode = new eZDOMNode();

        include_once( 'lib/ezlocale/classes/ezdateutils.php' );

        $versionNode->setName( 'version' );
        $versionNode->setPrefix( 'ezobject' );
        $versionNode->appendAttribute( eZDOMDocument::createAttributeNode( 'version', $this->Version, 'ezremote' ) );
        $versionNode->appendAttribute( eZDOMDocument::createAttributeNode( 'status', $this->Status, 'ezremote' ) );
        $versionNode->appendAttribute( eZDOMDocument::createAttributeNode( 'created', eZDateUtils::rfc1123Date( $this->attribute( 'created' ) ), 'ezremote' ) );
        $versionNode->appendAttribute( eZDOMDocument::createAttributeNode( 'modified', eZDateUtils::rfc1123Date( $this->attribute( 'modified' ) ), 'ezremote' ) );

        $translationList = $this->translationList( false, false );
        $contentObject   = $this->attribute( 'contentobject' );

        $db =& eZDB::instance();
        $db->begin();
        $allowedLanguages = $options['language_array'];
        if ( $options['only_initial_language'] )
        {
            $initialLanguageCode = $this->initialLanguageCode();
            if ( !in_array( $initialLanguageCode, $allowedLanguages ) )
            {
                // We can only export initial language but is not in the allowed
                // language list so we return false, ie. no export of this version.
                return false;
            }
            // Make sure only the initial language is exported
            $allowedLanguages = array( $initialLanguageCode );
        }
        $exportedLanguages = array();
        foreach ( $translationList as $translationItem )
        {
            $language = $translationItem;
            if ( !in_array( $language, $allowedLanguages ) )
            {
                continue;
            }

            $translationNode = new eZDOMNode();
            $translationNode->setName( 'object-translation' );
            $translationNode->setPrefix( 'ezobject' );
            $translationNode->appendAttribute( eZDOMDocument::createAttributeNode( 'language', $language ) );

            // serialize object name in current version-translation
            $objectName = $contentObject->name( $this->Version, $language );
            if ( $objectName )
            {
                $translationNode->appendAttribute( eZDOMDocument::createAttributeNode( 'object_name', $objectName ) );
            }
            else
            {
                eZDebug::writeWarning( sprintf( "Name for object %s of version %s in translation %s not found",
                                                $contentObject->attribute( 'id' ),
                                                $this->Version,
                                                $language ) );
            }

            $attributes = $this->contentObjectAttributes( $language );
            foreach ( $attributes as $attribute )
            {
                $translationNode->appendChild( $attribute->serialize( $package ) );
            }

            $versionNode->appendChild( $translationNode );
            unset( $translationNode );
            $exportedLanguages[] = $language;
        }

        $nodeAssignmentListNode = new eZDOMNode();
        $nodeAssignmentListNode->setName( 'node-assignment-list' );
        $nodeAssignmentListNode->setPrefix( 'ezobject' );
        $versionNode->appendChild( $nodeAssignmentListNode );

        $contentNodeArray = eZContentObjectTreeNode::fetchByContentObjectID( $this->ContentObjectID, true, $this->Version );
        foreach( $contentNodeArray as $contentNode )
        {
            unset( $contentNodeDOMNode );
            $contentNodeDOMNode = $contentNode->serialize( $options, $contentNodeIDArray, $topNodeIDArray );
            if ( $contentNodeDOMNode !== false )
            {
                $nodeAssignmentListNode->appendChild( $contentNodeDOMNode );
            }
        }
        $initialLanguage = $this->attribute( 'initial_language' );
        $initialLanguageCode = $initialLanguage->attribute( 'locale' );
        if ( in_array( $initialLanguageCode, $exportedLanguages ) )
        {
            $versionNode->appendAttribute( eZDOMDocument::createAttributeNode( 'initial_language', $initialLanguageCode ) );
        }

        if ( $options['related_objects'] === 'selected' )
        {
            $relatedObjectArray = eZContentObject::relatedContentObjectList( $this->Version, $contentObject->ID, 0, false, array( 'AllRelations' => EZ_CONTENT_OBJECT_RELATION_COMMON ) );
            if ( count( $relatedObjectArray ) )
            {
                $relationListNode = new eZDOMNode();
                $relationListNode->setName( 'object-relation-list' );
                $relationListNode->setPrefix( 'ezobject' );

                foreach( array_keys( $relatedObjectArray ) as $Key )
                {
                    $relatedObject =& $relatedObjectArray[$Key];
                    $relatedObjectRemoteID = $relatedObject->attribute( 'remote_id' );

                    $relationNode = eZDOMDocument::createElementTextNode( 'related-object-remote-id', $relatedObjectRemoteID );

                    $relationListNode->appendChild( $relationNode );
                }
                $versionNode->appendChild( $relationListNode );
            }
        }

        $db->commit();
        return $versionNode;
    }

    /*!
     \return the creator of the current version.
    */
    function &creator()
    {
        if ( isset( $this->CreatorID ) and $this->CreatorID )
        {
            include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
            if ( $this->CreatorID != 0 )
                $creator = & eZContentObject::fetch( $this->CreatorID );
            else
                $creator = null;
        }
        else
            $creator = null;
        return $creator;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
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
        {
            $retValue = null;
            return $retValue;
        }

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

    function &languageMask()
    {
        $mask = (int)$this->attribute( 'language_mask' );
        return $mask;
    }

    function updateLanguageMask( $mask = false, $forceStore = true )
    {
        if ( $mask == false )
        {
            $mask = eZContentLanguage::maskByLocale( $this->translationList( false, false ), true );
        }

        $this->setAttribute( 'language_mask', $mask );

        if ( $forceStore )
        {
            $this->store();
        }
    }

    function &initialLanguage()
    {
        $initialLanguage = eZContentLanguage::fetch( $this->InitialLanguageID );

        return $initialLanguage;
    }

    function initialLanguageCode()
    {
        $initialLanguage = $this->initialLanguage();

        $initialLanguageCode = $initialLanguage !== false ?  $initialLanguage->attribute( 'locale' ) : false;

        return $initialLanguageCode;
    }

    function nonTranslatableAttributesToUpdate( )
    {
        $object = $this->contentObject();
        $version = $this->attribute( 'version' );
        $objectID = $object->attribute( 'id' );
        $initialLanguageID = $object->attribute( 'initial_language_id' );
        $db =& eZDB::instance();

        $attributeRows = $db->arrayQuery( "SELECT ezcontentobject_attribute.id, ezcontentobject_attribute.version
            FROM ezcontentobject_version,
                 ezcontentobject_attribute,
                ezcontentclass_attribute
            WHERE
                    ezcontentobject_version.contentobject_id='$objectID'
                AND ( ezcontentobject_version.status in ( " .
                      EZ_VERSION_STATUS_DRAFT . ", " . EZ_VERSION_STATUS_PENDING . ", " . EZ_VERSION_STATUS_INTERNAL_DRAFT .
                      " ) OR ( ezcontentobject_version.status = '1' AND ezcontentobject_version.version = '$version' ) )
                AND ezcontentobject_attribute.contentobject_id=ezcontentobject_version.contentobject_id
                AND ezcontentobject_attribute.version=ezcontentobject_version.version
                AND ezcontentobject_attribute.language_id!='$initialLanguageID'
                AND ezcontentobject_attribute.contentclassattribute_id=ezcontentclass_attribute.id
                AND ezcontentclass_attribute.can_translate=0" );

        $attributes = array();
        foreach( $attributeRows as $row )
        {
            $attributes[] = eZContentObjectAttribute::fetch( $row['id'], $row['version'] );
        }
        return $attributes;
    }

    function setAlwaysAvailableLanguageID( $languageID )
    {
        $db =& eZDB::instance();
        $db->begin();

        $objectID = $this->attribute( 'contentobject_id' );
        $version = $this->attribute( 'version' );

        // reset 'always available' flag
        $sql = "UPDATE ezcontentobject_attribute SET language_id=";
        if ( $db->databaseName() == 'oracle' )
        {
            $sql .= "bitand( language_id, -2 )";
        }
        else
        {
            $sql .= "language_id & ~1";
        }
        $sql .= " WHERE contentobject_id = '$objectID' AND version = '$version'";
        $db->query( $sql );

        if ( $languageID != false )
        {
            $newLanguageID = $languageID | 1;

            $sql = "UPDATE ezcontentobject_attribute
                    SET language_id='$newLanguageID'
                WHERE language_id='$languageID' AND contentobject_id = '$objectID' AND version = '$version'";
            $db->query( $sql );
        }

        $db->commit();
    }

    function clearAlwaysAvailableLanguageID()
    {
        $this->setAlwaysAvailableLanguageID( false );
    }

    // Checks if there is another version (published or archived status) which has higher modification time than the
    // current version creation time.
    // Typically this function can be used before object's publishing to prevent conflicts.
    //
    // \return  array of version objects that caused conflict or false.
    function hasConflicts( $editLanguage = false )
    {
        $object =& $this->contentObject();
        if ( !$editLanguage )
            $editLanguage = $this->initialLanguageCode();

        // Get versions (with published or archived status)
        $versions =& $object->versions( true, array( 'conditions' => array( 'status' => array( array( EZ_VERSION_STATUS_PUBLISHED, EZ_VERSION_STATUS_ARCHIVED ) ),
                                                                            'language_code' => $editLanguage ) ) );

        $conflictVersions = array();
        foreach ( array_keys( $versions ) as $key )
        {
            $version =& $versions[$key];
            if ( $version->attribute( 'modified' ) > $this->attribute( 'created' ) )
            {
                $conflictVersions[] =& $version;
            }
        }
        if ( !count( $conflictVersions ) )
            return false;
        else
            return $conflictVersions;
    }

    var $CurrentLanguage = false;
}

?>
