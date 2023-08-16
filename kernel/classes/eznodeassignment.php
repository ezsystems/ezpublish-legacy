<?php
/**
 * File containing the eZNodeAssignment class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZNodeAssignment eznodeassignment.php
  \brief The class eZNodeAssignment does

*/
class eZNodeAssignment extends eZPersistentObject
{
    public $TempNode;
    public $Name;
    public $OpCode;
    // Bit 0 is used to mark if the action is to be performed or not
    // A value of 0 means ignore and 1 means execute
    const OP_CODE_NOP =          0;
    const OP_CODE_EXECUTE =      1;
    // Create the node at specified location
    const OP_CODE_CREATE_NOP =   2;
    const OP_CODE_CREATE =       3;
    // Move the node to new location
    const OP_CODE_MOVE_NOP =     4;
    const OP_CODE_MOVE =         5;
    // Remove existing node
    const OP_CODE_REMOVE_NOP =   6;
    const OP_CODE_REMOVE =       7;
    // Set (update/create) values for node
    const OP_CODE_SET_NOP =      8;
    const OP_CODE_SET =          9;

    public function __construct( $row )
    {
        $this->TempNode = null;
        $this->Name = false;
        parent::__construct( $row );
    }

    static function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         // Used for the remote id of the node assignment itself
                                         'remote_id' => array( 'name' => 'RemoteID',
                                                               'datatype' => 'string',
                                                               'default' => 0,
                                                               'required' => true ),
                                         'contentobject_id' => array( 'name' => 'ContentobjectID',
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
                                         'parent_node' => array( 'name' => 'ParentNode',
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true,
                                                                 'foreign_class' => 'eZContentObjectTreeNode',
                                                                 'foreign_attribute' => 'node_id',
                                                                 'multiplicity' => '1..*' ),
                                         'sort_field' => array( 'name' => 'SortField',
                                                                'datatype' => 'integer',
                                                                'default' => eZContentObjectTreeNode::SORT_FIELD_PATH,
                                                                'required' => true ),
                                         'sort_order' => array( 'name' => 'SortOrder',
                                                                'datatype' => 'integer',
                                                                'default' => eZContentObjectTreeNode::SORT_ORDER_ASC,
                                                                'required' => true ),
                                         'is_main' => array( 'name' => 'Main',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'from_node_id' => array( 'name' => 'FromNodeID',
                                                                  'datatype' => 'integer',
                                                                  'default' => 0,
                                                                  'required' => true,
                                                                  'foreign_class' => 'eZContentObjectTreeNode',
                                                                  'foreign_attribute' => 'node_id',
                                                                  'multiplicity' => '1..*' ),
                                         // Used for the remote id of the corresponding tree node (not the parent tree node!)
                                         'parent_remote_id' => array( 'name' => 'ParentRemoteID',
                                                                      'datatype' => 'string',
                                                                      'default' => '',
                                                                      'required' => false ),
                                         'is_hidden' => array( 'name' => 'Hidden',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => false ),
                                         'priority' => array( 'name' => 'Priority',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => false ),
                                         'op_code' => array( 'name' => 'OpCode',
                                                             'datatype' => 'int',
                                                             'default' => 0, // eZNodeAssignment::OP_CODE_NOP
                                                             'required' => true ) ),
                      'keys' => array( 'id' ),
                      "function_attributes" => array( "parent_node_obj"      => "getParentNode",
                                                      "parent_contentobject" => "getParentObject",
                                                      "node"                 => "fetchNode",
                                                      'is_nop_operation'     => 'isNopOperation',
                                                      'is_create_operation'  => 'isCreateOperation',
                                                      'is_move_operation'    => 'isMoveOperation',
                                                      'is_remove_operation'  => 'isRemoveOperation',
                                                      'is_set_operation'     => 'isSetOperation',
                                                      'temp_node'            => 'tempNode' ),
                      "increment_key" => "id",
                      'class_name' => 'eZNodeAssignment',
                      'name' => 'eznode_assignment' );
    }

    function tempNode()
    {
        if ( $this->TempNode == null )
        {
            $this->TempNode = eZContentObjectTreeNode::create( $this->attribute( 'parent_node' ),
                                                               $this->attribute( 'contentobject_id' ),
                                                               $this->attribute( 'contentobject_version' ),
                                                               $this->attribute( 'sort_field' ),
                                                               $this->attribute( 'sort_order' ) );
            $this->TempNode->setName( $this->Name );
        }
        return $this->TempNode;
    }

    function setName( $name )
    {
        return $this->Name = $name;
    }

    function name()
    {
        return $this->Name;
    }

    /*!
     * Returns true if the assignment is a nop (no operation) operation.
     * \return bool
     */
    function isNopOperation()
    {
        return ( $this->OpCode & 1 ) == eZNodeAssignment::OP_CODE_NOP;
    }

    /*!
     * Returns true if the assignment is a create operation.
     * \return bool
     */
    function isCreateOperation()
    {
        return $this->OpCode == eZNodeAssignment::OP_CODE_CREATE;
    }

    /*!
     * Returns true if the assignment is a move operation.
     * \return bool
     */
    function isMoveOperation()
    {
        return $this->OpCode == eZNodeAssignment::OP_CODE_MOVE;
    }

    /*!
     * Returns true if the assignment is a remove operation.
     * \return bool
     */
    function isRemoveOperation()
    {
        return $this->OpCode == eZNodeAssignment::OP_CODE_REMOVE;
    }

    /*!
     * Returns true if the assignment is a set (update/create) operation.
     * \return bool
     */
    function isSetOperation()
    {
        return $this->OpCode == eZNodeAssignment::OP_CODE_SET;
    }

    static function create( $parameters = array() )
    {
        if ( !isset( $parameters['contentobject_id'] ) )
        {
            eZDebug::writeError( "Cannot create node assignment without contentobject_id, got: " . var_export( $parameters ), __METHOD__ );
            $retValue = null;
            return $retValue;
        }
        if ( !isset( $parameters['contentobject_version'] ) )
        {
            $parameters['contentobject_version'] = 1;
        }
        if ( !isset( $parameters['remote_id'] ) )
        {
            $parameters['remote_id'] = 0;
        }
        if ( !isset( $parameters['parent_node'] ) )
        {
            $parameters['parent_node'] = 2;
        }
        if ( !isset( $parameters['is_main'] ) )
        {
            $parameters['is_main'] = 0;
        }
        if ( !isset( $parameters['is_hidden'] ) )
        {
            $parameters['is_hidden'] = 0;
        }
        if ( !isset( $parameters['priority'] ) )
        {
            $parameters['priority'] = 0;
        }
        if ( !isset( $parameters['sort_field'] ) )
        {
            $parameters['sort_field'] = eZContentObjectTreeNode::SORT_FIELD_PUBLISHED;
        }
        if ( !isset( $parameters['sort_order'] ) )
        {
            $parameters['sort_order'] = eZContentObjectTreeNode::SORT_ORDER_DESC;
        }
        if ( !isset( $parameters['from_node_id'] ) )
        {
            $parameters['from_node_id'] = 0;
        }
        if ( !isset( $parameters['parent_remote_id'] ) || empty( $parameters['parent_remote_id'] ) )
        {
            $parameters['parent_remote_id'] = eZRemoteIdUtility::generate( 'node' );
        }
        if ( !isset( $parameters['op_code'] ) )
        {
            // The default value for new node-assigments is to create nodes from them.
            $parameters['op_code'] = eZNodeAssignment::OP_CODE_CREATE;
        }

        return new eZNodeAssignment( $parameters );
    }

    /*!
     Marks the specified nodeassignment to remove its node. It uses
     \a parentNodeID and \a contentObjectID if they are given,
     if they are \c false it will mark the current node assignment.

     \param $parentNodeID The ID of the parent node
     \param $contentObjectID The ID of the object
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function remove( $parentNodeID = false, $contentObjectID = false )
    {
        $db = eZDB::instance();
        if ( $parentNodeID == false and $contentObjectID == false )
        {
            $nodeAssignment = $this;
        }
        else
        {
            $parentNodeID =(int) $parentNodeID;
            $contentObjectID =(int) $contentObjectID;
            $cond = array( 'parent_node' => $parentNodeID,
                           'contentobject_id' => $contentObjectID );
            $nodeAssignment = eZPersistentObject::fetchObject( eZNodeAssignment::definition(),
                                                               null,
                                                               $cond,
                                                               true );
        }
        $nodeAssignment->setAttribute( "op_code", eZNodeAssignment::OP_CODE_REMOVE );
        $nodeAssignment->store();
    }

    /*!
     \static
     Marks the node assignment with the ID \a $assignmentID to remove its node.

     \param $assignmentID Either an ID or an array with IDs.
     \return \c true if it were able to remove the assignments, \c false if something failed.
     \note If \a $assignmentID is an empty array it immediately returns \c false.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function removeByID( $assignmentID )
    {
        $db = eZDB::instance();
        if ( is_array( $assignmentID ) )
        {
            if ( count( $assignmentID ) == 0 )
            {
                return false;
            }
            $sql = "UPDATE eznode_assignment SET op_code = " . eZNodeAssignment::OP_CODE_REMOVE . ", is_main = 0 WHERE id IN ( ";
            $i = 0;
            foreach ( $assignmentID as $id )
            {
                if ( $i > 0 )
                    $sql .= ", ";
                $sql .= (int)$id;
                ++$i;
            }
            $sql .= ' )';
        }
        else
        {
            $sql = "UPDATE eznode_assignment SET op_code = " . eZNodeAssignment::OP_CODE_REMOVE . ", is_main = 0 WHERE id=" . (int)$assignmentID;
        }
        $db->query( $sql );
        return true;
    }

    /*!
     Delete specified nodeassignment if \a parentNodeID and \a contentObjectID are given,
     if they are \c false it will remove the current node assignment.

     \param $parentNodeID The ID of the parent node
     \param $contentObjectID The ID of the object
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function purge( $parentNodeID = false, $contentObjectID = false )
    {
        if ( $parentNodeID == false && $contentObjectID == false )
        {
            eZDB::instance()->query( "DELETE FROM eznode_assignment WHERE id=" . (int)$this->attribute( 'id' ) );
        }
        else
        {
            self::purgeByParentAndContentObjectID( $parentNodeID, $contentObjectID );
        }
    }

    /**
     * Remove assignments based on their $parentNodeID and $contentObjectID
     *
     * @param int $parentNodeID
     * @param int $contentObjectID
     */
    public static function purgeByParentAndContentObjectID( $parentNodeID, $contentObjectID )
    {
        eZDB::instance()->query(
            "DELETE FROM eznode_assignment WHERE parent_node=" . (int)$parentNodeID ." AND contentobject_id=" . (int)$contentObjectID
        );
    }

    /*!
     \static
     Delelet the node assignment with the ID \a $assignmentID.

     \param $assignmentID Either an ID or an array with IDs.
     \return \c true if it were able to remove the assignments, \c false if something failed.
     \note If \a $assignmentID is an empty array it immediately returns \c false.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function purgeByID( $assignmentID )
    {
        $db = eZDB::instance();
        if ( is_array( $assignmentID ) )
        {
            if ( count( $assignmentID ) == 0 )
            {
                return false;
            }
            $sql = "DELETE FROM eznode_assignment WHERE id IN ( ";
            $i = 0;
            foreach ( $assignmentID as $id )
            {
                if ( $i > 0 )
                    $sql .= ", ";
                $sql .= (int)$id;
                ++$i;
            }
            $sql .= ' )';
        }
        else
        {
            $sql = "DELETE FROM eznode_assignment WHERE id=" . (int)$assignmentID;
        }
        $db->query( $sql );
        return true;
    }

    static function fetchForObject( $contentObjectID, $version = 1, $main = 0, $asObject = true )
    {
        $cond = array( 'contentobject_id' => $contentObjectID,
                       'contentobject_version' => $version );
        if( $main > 0 )
        {
            $cond['is_main'] = 1;
        }
        $objectList = eZPersistentObject::fetchObjectList( eZNodeAssignment::definition(),
                                                            null,
                                                            $cond,
                                                            null,
                                                            null,
                                                            $asObject );
        return $objectList;
    }

    static function fetch( $contentObjectID, $version = 1, $parentNode = 0 ,$asObject = true )
    {
        $cond = array( 'contentobject_id' => $contentObjectID,
                       'contentobject_version' => $version,
                       'parent_node' => $parentNode );
        return eZPersistentObject::fetchObject( eZNodeAssignment::definition(),
                                                null,
                                                $cond,
                                                $asObject );
    }

    /*!
     Finds the node for the current assignemnt if it exists and returns it.
     \return An eZContentObjectTreeNode object or \c null if no node was found.
      \sa eZContentObjectTreeNode::fetchNode
    */
    function fetchNode()
    {
        return eZContentObjectTreeNode::fetchNode( $this->ContentobjectID, $this->ParentNode );
    }

    /*!
     Fetches the node assignment which has id \a $id and returns it.
     \sa fetchListByID
    */
    static function fetchByID( $id ,$asObject = true )
    {
        $cond = array( 'id' => $id );
        return eZPersistentObject::fetchObject( eZNodeAssignment::definition(),
                                                null, $cond,
                                                $asObject );
    }

    /*!
     Fetches all node assignments which is mentioned in array \a $ID and returns it.
     \sa fetchByID
    */
    static function fetchListByID( $idList ,$asObject = true )
    {
        $cond = array( 'id' => array( $idList ) );
        return eZPersistentObject::fetchObjectList( eZNodeAssignment::definition(),
                                                    null, $cond, null, null,
                                                    $asObject );
    }

    /**
     * Fetch node assignment count by version status, giving parent node id list.
     *
     * @param array $parentNodeIDList
     * @param int $status
     * @return int
     */
    static function fetchChildCountByVersionStatus( $parentNodeIDList, $status = eZContentObjectVersion::STATUS_PENDING )
    {
        $db = eZDB::instance();
        $parentIDStatement = $db->generateSQLINStatement( $parentNodeIDList );
        $sql = "SELECT COUNT( DISTINCT eznode_assignment.id ) AS cnt
                    FROM ezcontentobject_version, eznode_assignment
                    WHERE ezcontentobject_version.contentobject_id = eznode_assignment.contentobject_id
                    AND ezcontentobject_version.version = eznode_assignment.contentobject_version
                    AND ezcontentobject_version.status = $status
                    AND eznode_assignment.parent_node $parentIDStatement ";
         $countResult = $db->arrayQuery( $sql );
         return (int)$countResult[0]['cnt'];
    }

    /**
     * Fetch node assignment list by version status, giving parent node id list.
     *
     * @param array $idList node id array
     * @param int $status status of the object version
     * @param boolean $count
     * @param boolean $asObject valid only when $count is false
     * @return array|int
     */
    static function fetchChildListByVersionStatus( $parentNodeIDList, $status = eZContentObjectVersion::STATUS_PENDING, $asObject = true )
    {
        $db = eZDB::instance();
        $parentIDStatement = $db->generateSQLINStatement( $parentNodeIDList );

        $sql = "SELECT DISTINCT eznode_assignment.*
                FROM ezcontentobject_version, eznode_assignment
                WHERE ezcontentobject_version.contentobject_id = eznode_assignment.contentobject_id
                AND ezcontentobject_version.version = eznode_assignment.contentobject_version
                AND ezcontentobject_version.status = $status
                AND eznode_assignment.parent_node $parentIDStatement
                ORDER BY eznode_assignment.contentobject_id";
        $nodeAssignmentArray = $db->arrayQuery( $sql );
        if( $asObject )
        {
            $result = array();
            foreach( $nodeAssignmentArray as $nodeAssignment )
            {
                $result[] = new eZNodeAssignment( $nodeAssignment );
            }
            return $result;
        }
        else
        {
            return $nodeAssignmentArray;
        }
    }


    function cloneNodeAssignment( $nextVersionNumber = 1, $contentObjectID = false )
    {
        $assignmentRow = array( 'contentobject_id' => $this->attribute( 'contentobject_id' ),
                                'contentobject_version' => $nextVersionNumber,
                                'remote_id' => $this->attribute( 'remote_id' ),
                                'parent_node' => $this->attribute( 'parent_node' ),
                                'sort_field' => $this->attribute( 'sort_field' ),
                                'sort_order' => $this->attribute( 'sort_order' ),
                                'is_main' => $this->attribute( 'is_main' ),
                                'is_hidden' => $this->attribute( 'is_hidden' ),
                                'priority' => $this->attribute( 'priority' ),
                                'parent_remote_id' => $this->attribute( 'parent_remote_id' ) );
        if ( $contentObjectID !== false )
            $assignmentRow['contentobject_id'] = $contentObjectID;
        return eZNodeAssignment::create( $assignmentRow );
    }

    function getParentNode()
    {
        return eZContentObjectTreeNode::fetch( $this->attribute( 'parent_node' ) );
    }

    /*!
     \return The contentobject which the parent node points to.
    */
    function getParentObject( )
    {
        return eZContentObject::fetchByNodeID( $this->attribute( 'parent_node' ) );
    }

    /*!
    \static
    Chooses and sets new main assignment for the specified object, in case if there's main assignment already.
    \return false if there is already main assignment, true on success.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    static function setNewMainAssignment( $objectID, $version )
    {
        $assignments = eZNodeAssignment::fetchForObject( $objectID, $version );

        if ( count( $assignments ) == 0 )
            return true;

        // check: if there is already main assignment for the object then we should do nothing
        // BTW choose first nonremoving assignment as new main assignment
        $newMainAssignment = null;
        foreach ( $assignments as $key => $assignment )
        {
            if ( $assignment->attribute( 'op_code' ) != eZNodeAssignment::OP_CODE_REMOVE )
            {
                if ( $newMainAssignment === null )
                {
                    $newMainAssignment = $assignment;
                }
                if ( $assignment->attribute( 'is_main' ) )
                {
                    return false;
                }
            }
        }

        $db = eZDB::instance();

        if ( $newMainAssignment === null )
        {
            $db->query( "UPDATE eznode_assignment SET is_main=0 WHERE contentobject_id=$objectID AND contentobject_version=$version" );
            return false;
        }

        $parentMainNodeID = $newMainAssignment->attribute( 'parent_node' );

        $db->begin();
        $db->query( "UPDATE eznode_assignment SET is_main=1 WHERE contentobject_id=$objectID AND contentobject_version=$version AND parent_node=$parentMainNodeID" );
        $db->query( "UPDATE eznode_assignment SET is_main=0 WHERE contentobject_id=$objectID AND contentobject_version=$version AND parent_node<>$parentMainNodeID" );
        $db->commit();

        return true;
    }

    /// \privatesection
    public $ID;
    /// Used for giving unique values to an assignment which can later be checked.
    /// This is often used in templates to provide limited choices for assignments.
    public $RemoteID;
    // Used for the remote id of the corresponding tree node (not the parent tree node!)
    public $ParentRemoteID;
    public $ContentobjectID;
    public $ContentObjectVersion;
    public $ParentNode;
    public $SortField;
    public $SortOrder;
    public $Main;
    public $FromNodeID;
    public $Hidden;
    public $Priority;
}

?>
