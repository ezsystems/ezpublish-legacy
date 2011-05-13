<?php
/**
 * File containing the eZSection class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZSection ezsection.php
  \brief eZSection handles grouping of content in eZ Publish

*/

class eZSection extends eZPersistentObject
{
    function eZSection( $row )
    {
        if ( !isset( $row['id'] ) )
        {
            $row['id'] = null;
        }
        $this->eZPersistentObject( $row );
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

    /**
     * Makes sure the global section ID is propagated to the template override key.
     * @deprecated since 4.4, global section support has been removed
     *
     * @return false
     */
    static function initGlobalID()
    {
        return false;
    }

    /**
     * Sets the current global section ID to \a $sectionID in the session and
     * the template override key
     * @deprecated since 4.4, global section support has been removed this
     *             function only sets value to override values for bc.
     *
     *  @param int $sectionID
     */
    static function setGlobalID( $sectionID )
    {
        // eZTemplateDesignResource will read this global variable
        $GLOBALS['eZDesignKeys']['section'] = $sectionID;
    }

    /**
     * Return the global section ID or \c null if it is not set yet.
     * @deprecated since 4.4, global section support has been removed and
     *             null is always returned.
     *
     * @return null
     */
    static function globalID()
    {
        return null;
    }

    /*!
     Will remove the current section from the database.
     \note Transaction unsafe. If you call several transaction unsafe methods you must enclose
     the calls within a db transaction; thus within db->begin and db->commit.
    */
    function removeThis( $conditions = null, $extraConditions = null )
    {
        eZPersistentObject::remove( array( "id" => $this->ID ), $extraConditions );
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

}

?>
