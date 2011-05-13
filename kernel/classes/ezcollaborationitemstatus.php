<?php
/**
 * File containing the eZCollaborationItemStatus class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZCollaborationItemStatus ezcollaborationitemstatus.php
  \brief The class eZCollaborationItemStatus does

*/

class eZCollaborationItemStatus extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZCollaborationItemStatus( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( 'fields' => array( 'collaboration_id' => array( 'name' => 'CollaborationID',
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true,
                                                                      'foreign_class' => 'eZCollaborationItem',
                                                                      'foreign_attribute' => 'id',
                                                                      'multiplicity' => '1..*' ),
                                         'user_id' => array( 'name' => 'UserID',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true,
                                                             'foreign_class' => 'eZUser',
                                                             'foreign_attribute' => 'contentobject_id',
                                                             'multiplicity' => '1..*' ),
                                         'is_read' => array( 'name' => 'IsRead',
                                                             'datatype' => 'integer',
                                                             'default' => 0,
                                                             'required' => true ),
                                         'is_active' => array( 'name' => 'IsActive',
                                                               'datatype' => 'integer',
                                                               'default' => 1,
                                                               'required' => true ),
                                         'last_read' => array( 'name' => 'LastRead',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ) ),
                      'keys' => array( 'collaboration_id', 'user_id' ),
                      'class_name' => 'eZCollaborationItemStatus',
                      'name' => 'ezcollab_item_status' );
    }

    static function create( $collaborationID, $userID = false )
    {
        if ( $userID === false )
            $userID = eZUser::currentUserID();
        $row = array(
            'collaboration_id' => $collaborationID,
            'user_id' => $userID,
            'is_read' => false,
            'is_active' => true,
            'last_read' => 0 );
        return $GLOBALS['eZCollaborationItemStatusCache'][$collaborationID][$userID] = new eZCollaborationItemStatus( $row );
    }

    function store( $fieldFilters = null )
    {
        $stored = eZPersistentObject::store( $fieldFilters );
        $this->updateCache();
        return $stored;
    }

    function updateCache()
    {
        $userID = $this->UserID;
        $collaborationID = $this->CollaborationID;
        $GLOBALS['eZCollaborationItemStatusCache'][$collaborationID][$userID] = $this;
    }

    static function fetch( $collaborationID, $userID = false, $asObject = true )
    {
        if ( $userID === false )
        {
            $userID = eZUser::currentUserID();
        }
        if ( !isset( $GLOBALS['eZCollaborationItemStatusCache'][$collaborationID][$userID] ) )
        {
            $conditions = array( 'collaboration_id' => $collaborationID,
                                 'user_id' => $userID );
            $GLOBALS['eZCollaborationItemStatusCache'][$collaborationID][$userID] = eZPersistentObject::fetchObject(
                eZCollaborationItemStatus::definition(),
                null,
                $conditions,
                $asObject );
        }
        return $GLOBALS['eZCollaborationItemStatusCache'][$collaborationID][$userID];
    }

    static function setLastRead( $collaborationID, $userID = false, $timestamp = false )
    {
        if ( $timestamp === false )
            $timestamp = time();

        eZCollaborationItemStatus::updateFields( $collaborationID, $userID, array( 'last_read' => $timestamp,
                                                                                   'is_read' => 1 ) );
    }

    static function updateFields( $collaborationID, $userID = false, $fields )
    {
        if ( $userID === false )
            $userID = eZUser::currentUserID();

        eZPersistentObject::updateObjectList( array( 'definition' => eZCollaborationItemStatus::definition(),
                                                     'update_fields' => $fields,
                                                     'conditions' => array( 'collaboration_id' => $collaborationID,
                                                                            'user_id' => $userID ) ) );
        $statusObject =& $GLOBALS['eZCollaborationItemStatusCache'][$collaborationID][$userID];
        if ( isset( $statusObject ) )
        {
            foreach ( $fields as $field => $value )
            {
                $statusObject->setAttribute( $field, $value );
            }
        }
    }

}

?>
