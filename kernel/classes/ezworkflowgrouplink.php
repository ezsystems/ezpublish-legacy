<?php
/**
 * File containing the eZWorkflowGroupLink class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

//!! eZKernel
//! The class eZWorkflowGroupLink
/*!

*/

class eZWorkflowGroupLink extends eZPersistentObject
{
    static function definition()
    {
        return array( "fields" => array( "workflow_id" => array( 'name' => "WorkflowID",
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true,
                                                                 'foreign_class' => 'eZWorkflow',
                                                                 'foreign_attribute' => 'id',
                                                                 'multiplicity' => '1..*' ),
                                         "workflow_version" => array( 'name' => "WorkflowVersion",
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         "group_id" => array( 'name' => "GroupID",
                                                              'datatype' => 'integer',
                                                              'default' => 0,
                                                              'required' => true,
                                                              'foreign_class' => 'eZWorkflowGroup',
                                                              'foreign_attribute' => 'id',
                                                              'multiplicity' => '1..*' ),
                                         "group_name" => array( 'name' => "GroupName",
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ) ),
                      "keys" => array( "workflow_id", "workflow_version", "group_id" ),
                      "class_name" => "eZWorkflowGroupLink",
                      "sort" => array( "workflow_id" => "asc" ),
                      "name" => "ezworkflow_group_link" );
    }

    static function create( $workflow_id, $workflow_version, $group_id, $group_name )
    {
        $row = array("workflow_id" => $workflow_id,
                     "workflow_version" => $workflow_version,
                     "group_id" => $group_id,
                     "group_name" => $group_name);
        return new eZWorkflowGroupLink( $row );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function removeByID( $workflow_id, $workflow_version, $group_id )
    {
        eZPersistentObject::removeObject( eZWorkflowGroupLink::definition(),
                                          array("workflow_id" => $workflow_id,
                                                "workflow_version" =>$workflow_version,
                                                "group_id" => $group_id ) );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function removeGroupMembers( $group_id )
    {
        eZPersistentObject::removeObject( eZWorkflowGroupLink::definition(),
                                          array( "group_id" => $group_id ) );
    }

    /*!
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
     */
    static function removeWorkflowMembers( $workflow_id, $workflow_version )
    {
        eZPersistentObject::removeObject( eZWorkflowGroupLink::definition(),
                                          array( "workflow_id" =>$workflow_id,
                                                 "workflow_version" =>$workflow_version ) );
    }

    static function fetch( $workflow_id, $workflow_version, $group_id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( eZWorkflowGroupLink::definition(),
                                                null,
                                                array("workflow_id" => $workflow_id,
                                                      "workflow_version" =>$workflow_version,
                                                      "group_id" => $group_id ),
                                                $asObject );
    }

    static function fetchWorkflowList( $workflow_version, $group_id, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZWorkflowGroupLink::definition(),
                                                    null,
                                                    array( "workflow_version" =>$workflow_version,
                                                           "group_id" => $group_id ),
                                                    null,
                                                    null,
                                                    $asObject);
    }

    static function fetchGroupList( $workflow_id, $workflow_version, $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZWorkflowGroupLink::definition(),
                                                    null,
                                                    array( "workflow_id" => $workflow_id,
                                                           "workflow_version" =>$workflow_version ),
                                                    null,
                                                    null,
                                                    $asObject);
    }

    /// \privatesection
    public $WorkflowID;
    public $WorkflowVersion;
    public $GroupID;
    public $GroupName;
}

?>
