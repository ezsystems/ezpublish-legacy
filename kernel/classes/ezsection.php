<?php
//
// Definition of eZSection class
//
// Created on: <27-Aug-2002 15:55:18 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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
     Makes sure the global section ID is propagated to the template override key.
    */
    static function initGlobalID()
    {
        global $eZSiteBasics;
        $sessionRequired = $eZSiteBasics['session-required'];
        $sectionID = false;
        if ( $sessionRequired )
        {
            $http = eZHTTPTool::instance();
            $sectionArray = array();
            if ( $http->hasSessionVariable( 'eZGlobalSection' ) )
                $sectionArray = $http->sessionVariable( 'eZGlobalSection' );
            if ( !isset( $sectionArray['id'] ) )
            {
                return false;
            }
            $sectionID = $sectionArray['id'];
        }

        if ( $sectionID )
        {
            // eZTemplateDesignResource will read this global variable
            $GLOBALS['eZDesignKeys']['section'] = $sectionID;
            return true;
        }
        return false;
    }

    /*!
     Sets the current global section ID to \a $sectionID in the session and
     the template override key.
    */
    static function setGlobalID( $sectionID )
    {
        $http = eZHTTPTool::instance();
        $sectionArray = array();
        if ( $http->hasSessionVariable( 'eZGlobalSection' ) )
            $sectionArray = $http->sessionVariable( 'eZGlobalSection' );
        $sectionArray['id'] = $sectionID;
        $http->setSessionVariable( 'eZGlobalSection', $sectionArray );

        // eZTemplateDesignResource will read this global variable
        $GLOBALS['eZDesignKeys']['section'] = $sectionID;
    }

    /*!
     \return the global section ID or \c null if it is not set yet.
    */
    static function globalID()
    {
        $http = eZHTTPTool::instance();
        if ( $http->hasSessionVariable( 'eZGlobalSection' ) )
        {
            $sectionArray = $http->sessionVariable( 'eZGlobalSection' );
            if ( isset( $sectionArray['id'] ) )
                return $sectionArray['id'];
        }
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
