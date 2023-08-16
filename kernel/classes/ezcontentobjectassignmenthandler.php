<?php
/**
 * File containing the eZContentObjectAssignmentHandler class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZContentObjectAssignmentHandler ezcontentobjectassignmenthandler.php
  \brief Handles default assignments for content objects

*/

class eZContentObjectAssignmentHandler
{
    public $CurrentVersion;
    /**
     * Constructor
     * @param eZContentObject $contentObject
     * @param eZContentObjectVersion $contentVersion
     */
    public function __construct( $contentObject, $contentVersion )
    {
        $this->CurrentObject = $contentObject;
        $this->CurrentVersion = $contentVersion;
    }

    function nodeIDList( $selectionText )
    {
        $nodeList = array();
        $items = explode( ',', trim( $selectionText ) );
        foreach ( $items as $item )
        {
            $item = trim( $item );
            if ( $item != '' )
            {
                $nodeID = $this->nodeID( $item );
                if ( $nodeID !== false )
                {
                    $nodeList[] = $nodeID;
                }
            }
        }
        return $nodeList;
    }

    function nodeID( $name )
    {
        if ( is_numeric( $name ) )
        {
            $nodeID = false;
            $node = eZContentObjectTreeNode::fetch( $name, false, false );
            if ( $node )
                $nodeID = $node['node_id'];
            return $nodeID;
        }
        $contentINI = eZINI::instance( 'content.ini' );
        switch ( $name )
        {
            case 'root':
            {
                return $contentINI->variable( 'NodeSettings', 'RootNode' );
            }
            case 'users':
            {
                return $contentINI->variable( 'NodeSettings', 'UserRootNode' );
            }
            case 'none':
            {
                return false;
            }
            default:
            {
                eZDebug::writeError( "Unknown node type '$name'", __METHOD__ );
            } break;
        }
        return false;
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    function setupAssignments( $parameters )
    {
        $parameters = array_merge( array( 'group-name' => false,
                                          'default-variable-name' => false,
                                          'specific-variable-name' => false,
                                          'fallback-node-id' => false,
                                          'section-id-wanted' => false ),
                                   $parameters );
        if ( !$parameters['group-name'] and
             !$parameters['default-variable-name'] and
             !$parameters['specific-variable-name'] )
             return false;
        $contentINI = eZINI::instance( 'content.ini' );
        $defaultAssignment = $contentINI->variable( $parameters['group-name'], $parameters['default-variable-name'] );
        $specificAssignments = $contentINI->variable( $parameters['group-name'], $parameters['specific-variable-name'] );
        $hasAssignment = false;
        $assignments = false;
        $sectionIDWanted = $parameters['section-id-wanted'];
        $sectionID = 0;
        $contentClass = $this->CurrentObject->attribute( 'content_class' );
        $contentClassIdentifier = $contentClass->attribute( 'identifier' );
        $contentClassID = $contentClass->attribute( 'id' );
        foreach ( $specificAssignments as $specificAssignment )
        {
            $assignmentRules = explode( ';', $specificAssignment );
            $classMatches = $assignmentRules[0];
            $assignments = $assignmentRules[1];
            $mainID = false;
            if ( isset( $assignmentRules[2] ) )
                $mainID = $assignmentRules[2];
            $classMatchArray = explode( ',', $classMatches );
            foreach ( $classMatchArray as $classMatch )
            {
                $classMatch = trim( $classMatch );
                if ( preg_match( "#^group_([0-9]+)$#", $classMatch, $matches ) )
                {
                    $classGroupID = $matches[1];
                    if ( $contentClass->inGroup( $classGroupID ) )
                    {
                        $hasAssignment = true;
                        break;
                    }
                }
                else if ( $classMatch == $contentClassIdentifier )
                {
                    $hasAssignment = true;
                    break;
                }
                else if ( $classMatch == $contentClassID )
                {
                    $hasAssignment = true;
                    break;
                }
            }
            if ( $hasAssignment )
                break;
        }
        if ( !$hasAssignment )
        {
            $assignmentRules = explode( ';', $defaultAssignment );
            $assignments = $assignmentRules[0];
            $mainID = false;
            if ( isset( $assignmentRules[1] ) )
                $mainID = $assignmentRules[1];
        }
        eZDebug::writeDebug( $assignments, __METHOD__ );
        if ( $assignments )
        {
            if ( $mainID )
                $mainID = $this->nodeID( $mainID );
            $nodeList = $this->nodeIDList( $assignments );
            eZDebug::writeDebug( $nodeList, __METHOD__ );
            $assignmentCount = 0;
            eZDebug::writeDebug( $this->CurrentObject->attribute( 'id' ), 'current object' );
            eZDebug::writeDebug( $this->CurrentVersion->attribute( 'version' ), 'current version' );
            foreach ( $nodeList as $nodeID )
            {
                $node = eZContentObjectTreeNode::fetch( $nodeID );
                if ( !$node )
                    continue;
                $parentContentObject = $node->attribute( 'object' );

                eZDebug::writeDebug( "Checking for '$nodeID'", __METHOD__ );
                if ( $parentContentObject->checkAccess( 'create',
                                                        $contentClassID,
                                                        $parentContentObject->attribute( 'contentclass_id' ) ) == '1' )
                {
                    eZDebug::writeDebug( "Adding to '$nodeID' and main = '$mainID'", __METHOD__ );
                    if ( $mainID === false )
                    {
                        $isMain = ( $assignmentCount == 0 );
                    }
                    else
                        $isMain = ( $mainID == $nodeID );

                    /* Here we figure out the section ID in case it is needed
                     * to assign a newly created object to. */
                    if ( $sectionIDWanted and $isMain )
                    {
                        $db = eZDB::instance();
                        $query = "SELECT section_id
                                  FROM ezcontentobject c, ezcontentobject_tree t
                                  WHERE t.node_id = 109
                                      AND t.contentobject_id = c.id";
                        $result = $db->arrayQuery( $query );
                        $sectionID = $result[0]['section_id'];
                    }

                    $nodeAssignment = eZNodeAssignment::create( array( 'contentobject_id' => $this->CurrentObject->attribute( 'id' ),
                                                                       'contentobject_version' => $this->CurrentVersion->attribute( 'version' ),
                                                                       'parent_node' => $node->attribute( 'node_id' ),
                                                                       'is_main' => $isMain ) );
                    $nodeAssignment->store();
                    ++$assignmentCount;
                }
                return $sectionID;
            }

            if ( $assignmentCount == 0 &&
                 $parameters['fallback-node-id'] )
            {
                $nodeAssignment = eZNodeAssignment::create( array( 'contentobject_id' => $this->CurrentObject->attribute( 'id' ),
                                                                   'contentobject_version' => $this->CurrentVersion->attribute( 'version' ),
                                                                   'parent_node' => $parameters['fallback-node-id'],
                                                                   'is_main' => true ) );
                $nodeAssignment->store();
                ++$assignmentCount;
            }
        }
        return true;
    }

    /// \privatesection
    /**
     * @var eZContentObject
     */
    public $CurrentObject;

    /**
     * @var eZContentObjectVersion
     */
    public $ContentVersion;
}

?>
