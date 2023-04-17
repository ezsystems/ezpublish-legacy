<?php
/**
 * File containing the eZSection class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZSection ezsection.php
  \brief eZSection handles grouping of content in eZ Publish

*/

class eZSection extends eZPersistentObject
{
    public function __construct( $row )
    {
        if ( !isset( $row['id'] ) )
        {
            $row['id'] = null;
        }
        parent::__construct( $row );
    }

    /*!
     \return the persistent object definition for the eZSection class.
    */
    static function definition()
    {
        static $definition = array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "name" => array( 'name' => "Name",
                                                          'datatype' => 'string',
                                                          'default' => 0,
                                                          'required' => true ),
                                         "navigation_part_identifier" => array( 'name' => "NavigationPartIdentifier",
                                                                                'datatype' => 'string',
                                                                                'default' => 'ezcontentnavigationpart',
                                                                                'required' => true ),
                                         "locale" => array( 'name' => "Locale",
                                                            'datatype' => 'string',
                                                            'default' => '',
                                                            'required' => true ),
                                         "identifier" => array( 'name' => "Identifier",
                                                            'datatype' => 'string',
                                                            'default' => '',
                                                            'required' => true ) ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZSection",
                      "sort" => array( "name" => "asc" ),
                      "name" => "ezsection" );
        return $definition;
    }

    /*!
     \return the section object with the given id.
    */
    static function fetch( $sectionID, $asObject = true )
    {
        global $eZContentSectionObjectCache;

        // If the object given by its id is not cached or should be returned as array
        // then we fetch it from the DB (objects are always cached as arrays).
        if ( !isset( $eZContentSectionObjectCache[$sectionID] ) or $asObject === false )
        {
            $section = eZPersistentObject::fetchObject( eZSection::definition(),
                                                null,
                                                array( "id" => $sectionID ),
                                                $asObject );
            if ( $asObject )
            {
                $eZContentSectionObjectCache[$sectionID] = $section;
            }
        }
        else
        {
            $section = $eZContentSectionObjectCache[$sectionID];
        }
        return $section;
    }

    /**
     * fetch object by section identifier
     * @param string $sectionIdentifier
     * @param boolean $asObject
     * @return object|null
     */
    static function fetchByIdentifier( $sectionIdentifier, $asObject = true )
    {
        global $eZContentSectionObjectCache;
        if( !isset( $eZContentSectionObjectCache[$sectionIdentifier] ) || $asObject === false )
        {
            $sectionFetched = eZPersistentObject::fetchObject( eZSection::definition(),
                                                       null,
                                                       array( "identifier" => $sectionIdentifier ),
                                                       $asObject );
            if( !$sectionFetched )
            {
                return null;
            }

            if( $asObject )
            {
                // the section identifier index refers to the id index object
                $sectionID = $sectionFetched->attribute( 'id' );
                if( !isset( $eZContentSectionObjectCache[$sectionID] ) )
                {
                    $eZContentSectionObjectCache[$sectionID] = $sectionFetched;
                }
                $eZContentSectionObjectCache[$sectionIdentifier] = $eZContentSectionObjectCache[$sectionID];
            }
            else
            {
                return $sectionFetched;
            }
        }
        $section = $eZContentSectionObjectCache[$sectionIdentifier];
        return $section;
    }

    static function fetchFilteredList( $conditions = null, $offset = false, $limit = false, $asObject = true )
    {
        $limits = null;
        if ( $offset or $limit )
            $limits = array( 'offset' => $offset,
                             'length' => $limit );
        return eZPersistentObject::fetchObjectList( eZSection::definition(),
                                                    null,
                                                    $conditions, null, $limits,
                                                    $asObject );
    }

    static function fetchList( $asObject = true )
    {
        return eZPersistentObject::fetchObjectList( eZSection::definition(),
                                                    null, null, null, null,
                                                    $asObject );
    }

    static function fetchByOffset( $offset, $limit, $asObject = true )
    {
        $sectionList = eZPersistentObject::fetchObjectList( eZSection::definition(),
                                                             null,
                                                             null,
                                                             array( 'name' => 'ASC' ),
                                                             array( 'offset' => $offset, 'length' => $limit ),
                                                             $asObject );
        return $sectionList;
    }

     /*!
     \return the number of active orders
     */
    static function sectionCount()
    {
        $db = eZDB::instance();

        $countArray = $db->arrayQuery(  "SELECT count( * ) AS count FROM ezsection" );
        return $countArray[0]['count'];
    }

    /*!
     Will remove the current section from the database.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function removeThis( $conditions = null, $extraConditions = null )
    {
        $this->remove( array( "id" => $this->ID ), $extraConditions );
    }

    /*
     Check if this section is allowed to remove from the system
    */
    function canBeRemoved( $sectionID = false )
    {
        if ( $sectionID === false )
        {
            $sectionID = $this->attribute( 'id' );
        }

        $objects = eZPersistentObject::fetchObjectList( eZContentObject::definition(), null,
                                                        array( 'section_id' => $sectionID ) );
        $limitations = eZPolicyLimitation::findByType( 'Section', $sectionID, true, false );
        $userRoles = eZRole::fetchRolesByLimitation( 'section', $sectionID );

        if ( count( $objects ) > 0 or
             count( $limitations ) > 0 or
             count( $userRoles ) > 0 )
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function applyTo( eZContentObject $object )
    {
        $sectionID = $this->attribute( "id" );

        $currentUser = eZUser::currentUser();
        if ( !$currentUser->canAssignSectionToObject( $sectionID, $object ) )
        {
            eZDebug::writeError(
                "You do not have permissions to assign the section <" . $this->attribute( "name" ) .  "> to the object <" . $object->attribute( "name" ) . ">."
            );
            return false;
        }

        $db = eZDB::instance();
        $db->begin();
        $assignedNodes = $object->attribute( "assigned_nodes" );
        if ( !empty( $assignedNodes ) )
        {
            if ( eZOperationHandler::operationIsAvailable( "content_updatesection" ) )
            {
                foreach ( $assignedNodes as $node )
                {
                    eZOperationHandler::execute(
                        "content",
                        "updatesection",
                        array(
                            "node_id" => $node->attribute( "node_id" ),
                            "selected_section_id" => $sectionID
                        ),
                        null,
                        true
                    );
                }
            }
            else
            {
                foreach ( $assignedNodes as $node )
                {
                    eZContentOperationCollection::updateSection( $node->attribute( "node_id" ), $sectionID, false );
                }
            }
        }
        else
        {
            // If there are no assigned nodes we should update db for the current object.
            $objectID = $object->attribute( "id" );
            $db->query( "UPDATE ezcontentobject SET section_id='$sectionID' WHERE id = '$objectID'" );
            $db->query( "UPDATE ezsearch_object_word_link SET section_id='$sectionID' WHERE  contentobject_id = '$objectID'" );
        }
        eZContentCacheManager::clearContentCacheIfNeeded( $object->attribute( "id" ) );
        $object->expireAllViewCache();
        $commitResult = $db->commit();

        // Update search indexes AFTER transaction is completed, so changes are actually available for Search Engine.
        if ( $commitResult ) {
            if ( !empty( $assignedNodes ) ) {
                foreach ( $assignedNodes as $node ) {
                    $objectIDArray = eZContentObjectTreeNode::getObjectIdsInNodeSubTree( $node );

                    if ( is_array( $objectIDArray ) )
                    {
                        foreach (array_chunk($objectIDArray, 100) as $pagedObjectIDs) {
                            // Clear cache of affected objects in sub tree so they are up-to-date when indexed.
                            eZContentCacheManager::clearObjectViewCacheArray($pagedObjectIDs);
                            eZSearch::updateObjectsSection($pagedObjectIDs, $sectionID);
                        }
                    }
                }
            } else {
                eZSearch::updateObjectsSection(array($object->attribute("id")), $sectionID);
            }
        }
    }
}

?>
