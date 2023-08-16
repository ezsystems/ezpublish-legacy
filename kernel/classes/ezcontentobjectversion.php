<?php
/**
 * File containing the eZContentObjectVersion class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZContentObjectVersion ezcontentobjectversion.php
  \brief The class eZContentObjectVersion handles different versions of an content object
  \ingroup eZKernel

*/

class eZContentObjectVersion extends eZPersistentObject
{
    public $ContentObjectAttributeArray;
    public $DataMap;
    public $TempNode;
    public $VersionName;
    public $VersionNameCache;
    public $Permissions;
    public $ContentObject;
    public $Version;
    public $ContentObjectID;
    public $Status;
    public $CreatorID;
    public $InitialLanguageID;
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_PENDING = 2;
    const STATUS_ARCHIVED = 3;
    const STATUS_REJECTED = 4;
    const STATUS_INTERNAL_DRAFT = 5;
    // used when a workflow event returns FETCH_TEMPLATE_REPEAT to allow editing again
    const STATUS_REPEAT = 6;
    const STATUS_QUEUED = 7;

    public function __construct( $row = array() )
    {
        $this->ContentObjectAttributeArray = false;
        $this->DataMap = false;
        $this->TempNode = null;
        $this->VersionName = null;
        $this->VersionNameCache = array();
        parent::__construct( $row );
    }

    static function definition()
    {
        static $definition = array( "fields" => array( 'id' =>  array( 'name' => 'ID',
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
        return $definition;
    }

    static function statusList( $limit = false )
    {
        if ( $limit == 'remove' )
        {
            $versions = array( array( 'name' => 'Draft', 'id' =>  eZContentObjectVersion::STATUS_DRAFT ),
                               array( 'name' => 'Pending', 'id' =>  eZContentObjectVersion::STATUS_PENDING ),
                               array( 'name' => 'Archived', 'id' =>  eZContentObjectVersion::STATUS_ARCHIVED ),
                               array( 'name' => 'Rejected', 'id' =>  eZContentObjectVersion::STATUS_REJECTED ) );
        }
        else
        {
            $versions = array( array( 'name' => 'Draft', 'id' =>  eZContentObjectVersion::STATUS_DRAFT ),
                               array( 'name' => 'Published', 'id' =>  eZContentObjectVersion::STATUS_PUBLISHED ),
                               array( 'name' => 'Pending', 'id' =>  eZContentObjectVersion::STATUS_PENDING ),
                               array( 'name' => 'Archived', 'id' =>  eZContentObjectVersion::STATUS_ARCHIVED ),
                               array( 'name' => 'Rejected', 'id' =>  eZContentObjectVersion::STATUS_REJECTED ) );
        }
        return $versions;
    }
    /*!
     \return true if the requested attribute exists in object.
    */

    static function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZContentObjectVersion::definition(),
                                                null,
                                                array( 'id' => $id ),
                                                $asObject );
    }

    static function fetchVersion( $version, $contentObjectID, $asObject = true )
    {
        $ret = eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                    null, array( "version" => $version,
                                                                 "contentobject_id" => $contentObjectID
                                                                 ),
                                                    null, null,
                                                    $asObject );
        return isset( $ret[0] ) ? $ret[0] : false;
    }

    static function fetchVersionForUpdate( $version, $contentObjectID, $asObject = true )
    {
        global $eZContentObjectVersionCache;

        $db = eZDB::instance();
        $version = (int) $version;
        $contentObjectID = (int) $contentObjectID;

        // Select for update, to lock the row
        $resArray = $db->arrayQuery(
            "SELECT * FROM
                 ezcontentobject_version
             WHERE
                 version='$version' AND
                 contentobject_id='$contentObjectID'
             FOR UPDATE"
        );

        if ( !is_array( $resArray ) || count( $resArray ) !== 1 )
        {
            eZDebug::writeError( "Version '$version' for content '$contentObjectID' not found", __METHOD__ );
            return null;
        }

        if ( !isset( $resArray[0] ) )
        {
            return false;
        }
        $versionArray = $resArray[0];

        if ( !$asObject )
        {
            return $versionArray;
        }

        $versionObject = new eZContentObjectVersion( $versionArray );
        $eZContentObjectVersionCache[$contentObjectID][$version] = $versionObject;

        return $versionObject;
    }

    static function fetchUserDraft( $objectID, $userID )
    {
        $versions = eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                          null, array( 'creator_id' => $userID,
                                                                       'contentobject_id' => $objectID,
                                                                       'status' => array( array( eZContentObjectVersion::STATUS_DRAFT, eZContentObjectVersion::STATUS_INTERNAL_DRAFT ) ) ),
                                                          array( 'version' => 'asc' ), null,
                                                          true );
        if ( $versions === null or
             count( $versions ) == 0 )
            return null;
        return $versions[0];
    }

    /**
     * Fetch the latest draft by user id
     *
     * @since 4.7
     * @param int $objectID
     * @param int $userID
     * @param int $languageID
     * @param int $modified
     * @return eZContentObjectVersion|null
     */
    public static function fetchLatestUserDraft( $objectID, $userID, $languageID, $modified = 0 )
    {
        $versions = eZPersistentObject::fetchObjectList(
            eZContentObjectVersion::definition(),
            null,
            array(
                'creator_id' => $userID,
                'contentobject_id' => $objectID,
                'initial_language_id' => $languageID,
                'modified' => array( '>', $modified ),
                'status' => array(
                    array(
                        eZContentObjectVersion::STATUS_DRAFT,
                        eZContentObjectVersion::STATUS_INTERNAL_DRAFT
                    )
                )
            ),
            array( 'modified' => 'desc' ),
            array( 'offset' => 0, 'length' => 1 ),
            true
        );

        if ( empty( $versions ) )
            return null;
        return $versions[0];
    }

    static function fetchForUser( $userID, $status = eZContentObjectVersion::STATUS_DRAFT )
    {
        return eZPersistentObject::fetchObjectList( eZContentObjectVersion::definition(),
                                                    null, array( 'creator_id' => $userID,
                                                                 'status' => $status
                                                                 ),
                                                    null, null,
                                                    true );
    }

    static function fetchFiltered( $filters, $offset, $limit )
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
    function tempMainNode()
    {
        if ( $this->TempNode !== null )
            return $this->TempNode;
        $object = $this->contentObject();
        if ( $object->attribute( 'status' ) == eZContentObject::STATUS_DRAFT )
        {
            $nodeAssignments = $this->nodeAssignments();
            $mainNodeAssignment = null;
            foreach( $nodeAssignments as $nodeAssignment )
            {
                if ( $nodeAssignment->attribute( 'is_main' ) )
                {
                    $mainNodeAssignment = $nodeAssignment;
                    break;
                }
            }
            if ( $mainNodeAssignment === null and
                 count( $nodeAssignments ) > 0 )
            {
                $mainNodeAssignment = $nodeAssignments[0];
            }
            if ( $mainNodeAssignment )
            {
                $this->TempNode = $mainNodeAssignment->tempNode();
            }
        }
        else if ( $object->attribute( 'status' ) == eZContentObject::STATUS_PUBLISHED )
        {
            $mainNode = $object->mainNode();
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
    function name( $lang = false )
    {
        if ( $this->VersionName !== null )
            return $this->VersionName;
        $this->VersionName = $this->attribute( 'contentobject' )->versionLanguageName( $this->attribute( 'version' ),
                                                                                       $lang );
        if ( $lang !== false )
        {
            $contentObject = $this->contentObject();
            if ( $contentObject )
            {
                return $contentObject->name( false, $lang );
            }
        }
        if ( $this->VersionName === false )
        {
            $contentObject = $this->contentObject();
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
    function versionName( $lang = false )
    {
        if ( !$lang )
        {
            $lang = $this->initialLanguageCode();
        }

        if ( isset( $this->VersionNameCache[$lang] ) )
            return $this->VersionNameCache[$lang];

        $object = $this->attribute( 'contentobject' );
        if ( !$object )
        {
            $retValue = false;
            return $retValue;
        }

        $class = $object->attribute( 'content_class' );
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
    */
    function canVersionRead( )
    {
        if ( !isset( $this->Permissions["can_versionread"] ) )
        {
            $this->Permissions["can_versionread"] = $this->checkAccess( 'versionread' );
        }
        return ( $this->Permissions["can_versionread"] == 1 );
    }

    /*!
     \return \c true if the current user can remove this version of the object.
    */
    function canVersionRemove( )
    {
        if ( !isset( $this->Permissions['can_versionremove'] ) )
        {
            $this->Permissions['can_versionremove'] = $this->checkAccess( 'versionremove' );
        }
        return ( $this->Permissions['can_versionremove'] == 1 );
    }

    function checkAccess( $functionName, $originalClassID = false, $parentClassID = false, $returnAccessList = false, $language = false )
    {
        $classID = $originalClassID;
        $user = eZUser::currentUser();
        $userID = $user->attribute( 'contentobject_id' );
        $accessResult =  $user->hasAccessTo( 'content' , $functionName );
        $accessWord = $accessResult['accessWord'];
        $object = $this->attribute( 'contentobject' );
        $objectClassID = $object->attribute( 'contentclass_id' );
        if ( ! $classID )
        {
            $classID = $objectClassID;
        }

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
                            array( eZContentObjectVersion::STATUS_DRAFT,
                                   eZContentObjectVersion::STATUS_INTERNAL_DRAFT,
                                   eZContentObjectVersion::STATUS_PENDING ) ) ||
                 $this->attribute( 'creator_id' ) != $userID )
            {
                return false;
            }
            return true;
        }

        if ( $functionName == 'versionremove' and $this->attribute( 'status' ) == eZContentObjectVersion::STATUS_PUBLISHED )
        {
            return 0;
        }

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
                    if ( $parentObj instanceof eZContentObject )
                    {
                        $result = $parentObj->checkAccess( 'create', $object->attribute( 'contentclass_id' ),
                                                           $parentObj->attribute( 'contentclass_id' ), false, $originalLanguage );
                        return $result;
                    }
                    else
                    {
                        eZDebug::writeError( "Error retrieving parent object of main node for object id: " . $object->attribute( 'id' ), __METHOD__ );
                    }
                }
            }

            return 0;
        }
        else
        {
            $limitationList = $accessResult['policies'];

            if ( count( $limitationList ) > 0 )
            {
                $access = 'denied';
                foreach ( $limitationList as $limitationArray  )
                {
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
                                $limitationObjectID = $node['id'];
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
                                $subtreeArray = $limitation;
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
                                    if ( !$parentNode instanceof eZContentObjectTreeNode )
                                    {
                                        eZDebug::writeError( "Error retrieving parent of main node for object id: " . $this->attribute( 'contentobject_id' ), __METHOD__ );
                                        return 0;
                                    }
                                    $path = $parentNode->attribute( 'path_string' );
                                    $subtreeArray = $limitation;
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
                                $subtreeArray = $limitation;
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
                                    if ( !$parentNode instanceof eZContentObjectTreeNode )
                                    {
                                        eZDebug::writeError( "Error retrieving parent of main node for object id: " . $this->attribute( 'contentobject_id' ), __METHOD__ );
                                        return 0;
                                    }
                                    $path = $parentNode->attribute( 'path_string' );
                                    $subtreeArray = $limitation;
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

    function contentObject()
    {
        if( !isset( $this->ContentObject ) )
        {
            $this->ContentObject = eZContentObject::fetch( $this->attribute( 'contentobject_id' ) );
        }
        return $this->ContentObject;
    }

    function mainParentNodeID()
    {
        $temp = eZNodeAssignment::fetchForObject( $this->attribute( 'contentobject_id' ), $this->attribute( 'version' ), 1 );
        if ( $temp == null )
        {
            return 1;
        }
        else
        {
            return $temp[0]->attribute( 'parent_node' );
        }
    }

    function parentNodes( )
    {
        $retNodes = array();
        $nodeAssignmentList = eZNodeAssignment::fetchForObject( $this->attribute( 'contentobject_id' ), $this->attribute( 'version' ) );
        foreach ( array_keys( $nodeAssignmentList ) as $key )
        {
            $nodeAssignment = $nodeAssignmentList[$key];
            $retNodes[] = $nodeAssignment;
        }
        return $retNodes;
    }
    function nodeAssignments()
    {
        return eZNodeAssignment::fetchForObject( $this->attribute( 'contentobject_id' ), $this->attribute( 'version' ) );
    }

    /**
     * @param int $nodeID
     * @param int $main
     * @param int $fromNodeID
     * @param null|int $sortField
     * @param null|int $sortOrder
     * @param int|string $remoteID remote id of the node assignment
     * @param null|string $parentRemoteId remote id of the assigned tree node (not the parent tree node!)
     *
     * @return eZNodeAssignment|null
     */
    function assignToNode( $nodeID, $main = 0, $fromNodeID = 0, $sortField = null, $sortOrder = null, $remoteID = 0, $parentRemoteId = null )
    {
        if ( $fromNodeID == 0 && ( $this->attribute( 'status' ) == eZContentObjectVersion::STATUS_DRAFT ||
                                   $this->attribute( 'status' ) == eZContentObjectVersion::STATUS_INTERNAL_DRAFT ) )
            $fromNodeID = -1;
        $nodeRow = array( 'contentobject_id' => $this->attribute( 'contentobject_id' ),
                          'contentobject_version' => $this->attribute( 'version' ),
                          'parent_node' => $nodeID,
                          'is_main' => $main,
                          'remote_id' => $remoteID,
                          'parent_remote_id' => $parentRemoteId,
                          'from_node_id' => $fromNodeID );
        if ( $sortField !== null )
            $nodeRow['sort_field'] = $sortField;
        if ( $sortOrder !== null )
            $nodeRow['sort_order'] = ( $sortOrder ? eZContentObjectTreeNode::SORT_ORDER_ASC : eZContentObjectTreeNode::SORT_ORDER_DESC );

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
        $nodeAssignmentList = $this->attribute( 'node_assignments' );
        $db = eZDB::instance();
        $db->begin();

        foreach ( $nodeAssignmentList as $nodeAssignment )
        {
            if ( $nodeAssignment->attribute( 'parent_node' ) == $nodeID )
            {
                $nodeAssignment->remove();
            }
        }
        $db->commit();
    }

    /*!
     \return the content object attribute
    */
    function dataMap()
    {
        if ( $this->ContentObjectAttributeArray === false )
        {
            $data = $this->contentObjectAttributes();
            // Store the attributes for later use
            $this->ContentObjectAttributeArray = $data;
        }
        else
        {
            $data = $this->ContentObjectAttributeArray;
        }

        if ( $this->DataMap == false )
        {
            $ret = array();
            foreach( $data as $item )
            {
                $ret[$item->contentClassAttributeIdentifier()] = $item;
            }
            $this->DataMap = $ret;
        }
        else
        {
            $ret = $this->DataMap;
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
    function relatedContentObjectArray()
    {
        $object = $this->attribute( 'contentobject' );
        return $object->relatedContentObjectArray( $this->Version );
    }

    static function create( $contentobjectID, $userID = false, $version = 1, $initialLanguageCode = false )
    {
        if ( $userID === false )
        {
            $user = eZUser::currentUser();
            $userID = $user->attribute( 'contentobject_id' );
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
            'language_mask' => $initialLanguageID,
            "version" => $version,
            "created" => $time,
            "modified" => $time,
            'creator_id' => $userID );
        return new eZContentObjectVersion( $row );
    }

    function reverseRelatedObjectList()
    {
        return $this->attribute( 'contentobject' )->reverseRelatedObjectList( $this->Version );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function removeThis()
    {
        $contentobjectID = $this->attribute( 'contentobject_id' );
        $versionNum = $this->attribute( 'version' );

        $db = eZDB::instance();
        $db->begin();

        // Ensure no one else deletes this version while we are doing it.
        $db->query( 'SELECT * FROM ezcontentobject_version
                         WHERE id=' . $this->attribute( 'id' ) . ' FOR UPDATE' );

        $contentObjectTranslations = $this->translations();

        foreach ( $contentObjectTranslations as $contentObjectTranslation )
        {
            /** @var eZContentObjectAttribute $attribute */
            foreach ( $contentObjectTranslation->objectAttributes() as $attribute )
            {
                $attribute->removeThis( $attribute->attribute( 'id' ), $versionNum );
            }
        }
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
            else
            {
                $version = $contentobject->CurrentVersion;
                if ( $contentobject->CurrentVersion == $versionNum ) //will assign another current_version in contentObject.
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

        $versionNum = $this->attribute( 'version' );

        $contentObjectAttributes = $this->contentObjectAttributes( $languageCode );

        $db = eZDB::instance();
        $db->begin();
        foreach ( $contentObjectAttributes as $attribute )
        {
            $attribute->removeThis( $attribute->attribute( 'id' ), $versionNum );
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
    static function removeVersions( $versionStatus = false, $limit = false, $expiryTime = false, $fetchPortionSize = 50 )
    {
        $statuses = array( eZContentObjectVersion::STATUS_DRAFT,
                           eZContentObjectVersion::STATUS_PENDING,
                           eZContentObjectVersion::STATUS_REJECTED,
                           eZContentObjectVersion::STATUS_ARCHIVED,
                           eZContentObjectVersion::STATUS_INTERNAL_DRAFT );
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
            eZDebug::writeError( 'Invalid version status was passed in.', __METHOD__ );
            return false;
        }

        if ( $limit !== false and ( !is_numeric( $limit ) or $limit < 0 ) )
        {
            eZDebug::writeError( '$limit must be either false or positive numeric value.', __METHOD__ );
            return false;
        }

        if ( !is_numeric( $fetchPortionSize ) or $fetchPortionSize < 1 )
            $fetchPortionSize = 50;

        $filters = array();
        $filters['status'] = array( $versionStatus );
        if ( $expiryTime !== false )
            $filters['modified'] = array( '<', $expiryTime );

        $processedCount = 0;
        $db = eZDB::instance();
        while ( $processedCount < $limit or !$limit )
        {
            // fetch by versions by preset portion at a time to avoid memory overflow
            $tmpLimit = ( !$limit or ( $limit - $processedCount ) > $fetchPortionSize ) ?
                            $fetchPortionSize : $limit - $processedCount;
            $versions = eZContentObjectVersion::fetchFiltered( $filters, 0, $tmpLimit );
            if ( count( $versions ) < 1 )
                break;

            foreach ( $versions as $version )
            {
                $db->begin();
                $version->removeThis();
                $db->commit();
            }
            $processedCount += count( $versions );
        }
        return $processedCount;
    }

    /*!
     Clones the version with new version \a $newVersionNumber and creator \a $userID
     \note The cloned version is not stored.
    */
    function cloneVersion( $newVersionNumber, $userID, $contentObjectID = false, $status = eZContentObjectVersion::STATUS_DRAFT )
    {
        $time = time();
        $clonedVersion = clone $this;
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
    */
    function translations( $asObject = true )
    {
        return $this->translationList( false, $asObject );
    }

    /*!
     \return An array with all the translations for the current version.
     \deprecated
    */
    function translation( $asObject = true )
    {
        return false;
    }

    /*!
     \return An array with all the translations for the current version.

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
           the calls within a db transaction; thus within db->begin and db->commit.
    */
    function translationList( $language = false, $asObject = true )
    {
        $db = eZDB::instance();

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

     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
           the calls within a db transaction; thus within db->begin and db->commit.
    */
    function defaultTranslationList()
    {
        return $this->translationList();
    }

    /*!
     Returns the attributes for the current content object version.
     If \a $language is not specified it will use the initial language of the version.
    */
    function contentObjectAttributes( $languageCode = false, $asObject = true )
    {
        if ( $languageCode == false )
        {
            if ( $this->Status == eZContentObjectVersion::STATUS_DRAFT || $this->Status == eZContentObjectVersion::STATUS_INTERNAL_DRAFT || $this->Status == eZContentObjectVersion::STATUS_PENDING )
            {
                $languageCode = $this->initialLanguageCode();
            }
            else if ( $this->CurrentLanguage )
            {
                $languageCode = $this->CurrentLanguage;
            }
        }

        return eZContentObjectVersion::fetchAttributes( $this->Version, $this->ContentObjectID, $languageCode, $asObject );
    }

    /*!
     Returns the attributes for the content object version \a $version and content object \a $contentObjectID.
     \a $language defines the language to fetch.
     \static
     \sa attributes
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function fetchAttributes( $version, $contentObjectID, $language = false, $asObject = true )
    {
        $db = eZDB::instance();
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
    static function mapLanguage( $language, $options )
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

     \param domNode XML DOM Node
     \param contentObject contentobject
     \param ownerID owner ID
     \param sectionID section ID
     \param activeVersion new object, true if first version of new object
     \param firstVersion If true, unserialize to version 1, otherwise create new version
     \param nodeList
     \param options
     \param package
     \param handlerType
     \param firstVersioninitialLanguage Locale string (e.g. "eng-GB") of the initial language to use if unserializing to version 1.

     \returns created object, false if could not create object/xml invalid
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function unserialize( $domNode, $contentObject, $ownerID, $sectionID, $activeVersion, $firstVersion, &$nodeList, &$options, $package, $handlerType = 'ezcontentobject', $firstVersioninitialLanguage = false )
    {
        $oldVersion = $domNode->getAttributeNS( 'http://ez.no/ezobject', 'version' );
        $status = $domNode->getAttributeNS( 'http://ez.no/ezobject', 'status' );
        $languageNodeArray = $domNode->getElementsByTagName( 'object-translation' );

        $initialLanguage   = false;
        $importedLanguages = $options['language_array'];
        $currentLanguages  = array();
        foreach( $languageNodeArray as $languageNode )
        {
            $language = eZContentObjectVersion::mapLanguage( $languageNode->getAttribute( 'language' ), $options );
            if ( in_array( $language, $importedLanguages ) )
            {
                $currentLanguages[] = $language;
            }
        }
        if ( $firstVersion )
        {
            $initialLanguage = $firstVersioninitialLanguage;
        }
        if ( !$initialLanguage )
        {
            foreach ( eZContentLanguage::prioritizedLanguages() as $language )
            {
                if ( in_array( $language->attribute( 'locale' ), $currentLanguages ) )
                {
                    $initialLanguage = $language->attribute( 'locale' );
                    break;
                }
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

        $created = eZDateUtils::textToDate( $domNode->getAttributeNS( 'http://ez.no/ezobject', 'created' ) );
        $modified = eZDateUtils::textToDate( $domNode->getAttributeNS( 'http://ez.no/ezobject', 'modified' ) );
        $contentObjectVersion->setAttribute( 'created', $created );
        $contentObjectVersion->setAttribute( 'modified', $modified );

        $contentObjectVersion->setAttribute( 'status', eZContentObjectVersion::STATUS_DRAFT );
        $contentObjectVersion->store();

        $db = eZDB::instance();
        $db->begin();
        foreach( $languageNodeArray as $languageNode )
        {
            $language = eZContentObjectVersion::mapLanguage( $languageNode->getAttribute( 'language' ), $options );
            // Only import allowed languages.
            if ( !in_array( $language, $importedLanguages ) )
            {
                continue;
            }

            $attributeArray = $contentObjectVersion->contentObjectAttributes( $language );
            if ( count( $attributeArray ) == 0)
            {
                $hasTranslation = eZContentLanguage::fetchByLocale( $language );

                if ( !$hasTranslation )
                {
                    // if there is no needed translation in system then add it
                    $locale = eZLocale::instance( $language );

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
                    $originalContentAttributes = $contentObjectVersion->contentObjectAttributes( $initialLanguage );
                    foreach ( $originalContentAttributes as $originalContentAttribute )
                    {
                        $contentAttribute = $originalContentAttribute->translateTo( $language );
                        $contentAttribute->sync();
                        $attributeArray[] = $contentAttribute;
                    }
                }

                // unserialize object name in current version-translation
                $objectName = $languageNode->getAttribute( 'object_name' );
                if ( $objectName )
                    $contentObject->setName( $objectName, $contentObjectVersion->attribute( 'version' ), $language );
            }

            $xpath = new DOMXPath( $domNode->ownerDocument );
            $xpath->registerNamespace( 'ezobject', 'http://ez.no/object/' );
            $xpath->registerNamespace( 'ezremote', 'http://ez.no/ezobject' );

            foreach( $attributeArray as $attribute )
            {
                $attributeIdentifier = $attribute->attribute( 'contentclass_attribute_identifier' );
                $xpathQuery = "ezobject:attribute[@ezremote:identifier='$attributeIdentifier']";
                $attributeDomNodes = $xpath->query( $xpathQuery, $languageNode );
                $attributeDomNode = $attributeDomNodes->item( 0 );
                if ( !$attributeDomNode )
                {
                    continue;
                }
                $attribute->unserialize( $package, $attributeDomNode );
                $attribute->store();
            }
        }

        $objectRelationList = $domNode->getElementsByTagName( 'object-relation-list' )->item( 0 );
        if ( $objectRelationList )
        {
            $objectRelationArray = $objectRelationList->getElementsByTagName( 'related-object-remote-id' );
            foreach( $objectRelationArray as $objectRelation )
            {
                $relatedObjectRemoteID = $objectRelation->textContent;
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

        $nodeAssignmentNodeList = $domNode->getElementsByTagName( 'node-assignment-list' )->item( 0 );
        $nodeAssignmentNodeArray = $nodeAssignmentNodeList->getElementsByTagName( 'node-assignment' );
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

    function postUnserialize( $package )
    {
        foreach( $this->translations( false ) as $language )
        {
            foreach( $this->contentObjectAttributes( $language ) as $attribute )
            {
                $attribute->postUnserialize( $package );
            }
        }
    }

    /*!
     \return a DOM structure of the content object version, it's translations and attributes.

     \param package
     \param options package options ( optional )
     \param contentNodeIDArray array of allowed nodes ( optional )
     \param topNodeIDArray array of top nodes in current package export (optional )
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function serialize( $package, $options = false, $contentNodeIDArray = false, $topNodeIDArray = false )
    {
        $dom = new DOMDocument( '1.0', 'utf-8' );

        $versionNode = $dom->createElementNS( 'http://ez.no/object/', 'ezobject:version' );
        $dom->appendChild( $versionNode );

        $versionNode->setAttributeNS( 'http://ez.no/ezobject', 'ezremote:version', $this->Version );
        $versionNode->setAttributeNS( 'http://ez.no/ezobject', 'ezremote:status', $this->Status );
        $versionNode->setAttributeNS( 'http://ez.no/ezobject', 'ezremote:created', eZDateUtils::rfc1123Date( $this->attribute( 'created' ) ) );
        $versionNode->setAttributeNS( 'http://ez.no/ezobject', 'ezremote:modified', eZDateUtils::rfc1123Date( $this->attribute( 'modified' ) ) );

        $translationList = $this->translationList( false, false );
        $contentObject   = $this->attribute( 'contentobject' );

        $db = eZDB::instance();
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

            $translationNode = $dom->createElementNS( 'http://ez.no/object/', 'ezobject:object-translation' );
            $translationNode->setAttribute( 'language', $language );

            // serialize object name in current version-translation
            $objectName = $contentObject->name( $this->Version, $language );
            if ( $objectName )
            {
                $translationNode->setAttribute( 'object_name', $objectName );
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
                $serializedAttributeNode = $attribute->serialize( $package );
                $importedSerializedAttributeNode = $dom->importNode( $serializedAttributeNode, true );
                $translationNode->appendChild( $importedSerializedAttributeNode );
            }

            $versionNode->appendChild( $translationNode );
            $exportedLanguages[] = $language;
        }

        $nodeAssignmentListNode = $dom->createElementNS( 'http://ez.no/object/', 'ezobject:node-assignment-list' );
        $versionNode->appendChild( $nodeAssignmentListNode );

        $contentNodeArray = eZContentObjectTreeNode::fetchByContentObjectID( $this->ContentObjectID, true, $this->Version );
        foreach( $contentNodeArray as $contentNode )
        {
            $contentNodeDOMNode = $contentNode->serialize( $options, $contentNodeIDArray, $topNodeIDArray );
            if ( $contentNodeDOMNode !== false )
            {
                $importedContentDOMNode = $dom->importNode( $contentNodeDOMNode, true );
                $nodeAssignmentListNode->appendChild( $importedContentDOMNode );
            }
        }
        $initialLanguage = $this->attribute( 'initial_language' );
        $initialLanguageCode = $initialLanguage->attribute( 'locale' );
        if ( in_array( $initialLanguageCode, $exportedLanguages ) )
        {
            $versionNode->setAttribute( 'initial_language', $initialLanguageCode );
        }

        if ( $options['related_objects'] === 'selected' )
        {
            $relatedObjectArray = $contentObject->relatedContentObjectList( $this->Version, $contentObject->ID, 0, false,
                                                                             array( 'AllRelations' => eZContentObject::RELATION_COMMON ) );
            if ( count( $relatedObjectArray ) )
            {
                $relationListNode = $dom->createElementNS( 'http://ez.no/object/', 'ezobject:object-relation-list' );

                foreach( $relatedObjectArray as $relatedObject )
                {
                    $relatedObjectRemoteID = $relatedObject->attribute( 'remote_id' );

                    $relationNode = $dom->createElement( 'related-object-remote-id' );
                    $relationNode->appendChild( $dom->createTextNode( $relatedObjectRemoteID ) );

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
    function creator()
    {
        if ( isset( $this->CreatorID ) and $this->CreatorID )
        {
            return eZContentObject::fetch( $this->CreatorID );
        }
        return null;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function unpublish()
    {
        if ( $this->attribute( 'status' ) == eZContentObjectVersion::STATUS_PUBLISHED )
        {
            $this->setAttribute( 'status', eZContentObjectVersion::STATUS_ARCHIVED );
            $parentNodeList = $this->attribute( 'parent_nodes' );
            $parentNodeIDList = array();
            foreach( $parentNodeList as $parentNode )
            {
                $parentNodeIDList[] = $parentNode->attribute( 'parent_node' );
            }
            if ( count( $parentNodeIDList ) == 0 )
            {
                eZDebug::writeWarning( "Unable to get parent nodes for ContentobjectID {$this->attribute( 'contentobject_id' )}, Version {$this->attribute( 'version' )}", __METHOD__ );
                return;
            }
            $parentNodeIDString = implode( ',' , $parentNodeIDList );
            $contentObjectID = $this->attribute( 'contentobject_id' );
            $version = $this->attribute( 'version' );
            $db = eZDB::instance();
            $query = "update ezcontentobject_tree
                      set contentobject_is_published = '0'
                      where parent_node_id in ( $parentNodeIDString ) and
                            contentobject_id = $contentObjectID and
                            contentobject_version = $version" ;
            $db->query( $query );
        }
        else
        {
            eZDebug::writeWarning( "Trying to unpublish non published version {$this->attribute( 'version' )} of Contentobject {$this->attribute( 'contentobject_id' )}", __METHOD__ );
        }

    }

    /*!
     \returns an array with locale objects, these objects represents the languages the content objects are allowed to be translated into.
              The array will not include locales that has been translated in this version.
    */
    function nonTranslationList()
    {
        $translationList = eZContentObject::translationList();
        if ( $translationList === null )
        {
            $retValue = null;
            return $retValue;
        }

        $translations = $this->translations( false );
        $nonTranslationList = array();
        foreach ( $translationList as $translationItem )
        {
            $locale = $translationItem->attribute( 'locale_code' );
            if ( !in_array( $locale, $translations ) )
                $nonTranslationList[] = $translationItem;
        }
        return $nonTranslationList;
    }

    function languageMask()
    {
        return (int)$this->attribute( 'language_mask' );
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

    function initialLanguage()
    {
        return eZContentLanguage::fetch( $this->InitialLanguageID );
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
        $db = eZDB::instance();

        $attributeRows = $db->arrayQuery( "SELECT ezcontentobject_attribute.id, ezcontentobject_attribute.version
            FROM ezcontentobject_version,
                 ezcontentobject_attribute,
                ezcontentclass_attribute
            WHERE
                    ezcontentobject_version.contentobject_id='$objectID'
                AND ( ezcontentobject_version.status in ( " .
                      eZContentObjectVersion::STATUS_DRAFT . ", " . eZContentObjectVersion::STATUS_PENDING . ", " . eZContentObjectVersion::STATUS_INTERNAL_DRAFT .
                      " ) OR ( ezcontentobject_version.status = '" . self::STATUS_PUBLISHED . "' AND ezcontentobject_version.version = '$version' ) )
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
        $db = eZDB::instance();
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
        $object = $this->contentObject();
        if ( !$editLanguage )
            $editLanguage = $this->initialLanguageCode();

        // Get versions (with published or archived status)
        $versions = $object->versions( true, array( 'conditions' => array( 'status' => array( array( eZContentObjectVersion::STATUS_PUBLISHED, eZContentObjectVersion::STATUS_ARCHIVED ) ),
                                                                           'language_code' => $editLanguage ) ) );

        $conflictVersions = array();
        foreach ( array_keys( $versions ) as $key )
        {
            $version =& $versions[$key];
            if ( $version->attribute( 'modified' ) > $this->attribute( 'created' ) )
            {
                $conflictVersions[] = $version;
            }
        }
        if ( !count( $conflictVersions ) )
        {
            return false;
        }

        return $conflictVersions;
    }

    public function store( $fieldFilters = null )
    {
        eZContentObject::clearCache( $this->attribute( 'contentobject_id' ) );
        parent::store( $fieldFilters );
    }

    public $CurrentLanguage = false;
}

?>
