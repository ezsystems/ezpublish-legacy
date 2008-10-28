<?php
//
// Created on: <17-Apr-2007 11:07:53 bjorn>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file ezisbngroup.php
*/

/*!
  \class eZISBNGroup ezisbngroup.php
  \brief The class eZISBNGroup handles ISBN Registration groups.

  Contains a list of registration numbers that exists for each area. The registration group is
  the second field of an ISBN-13 number. Example: 978-0-11-000222-4 where 0 is the registration
  group number.

  The different Registration numbers are described in more detail at
  http://www.isbn-international.org
*/

class eZISBNGroup extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZISBNGroup( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \static
     returns a definition of the ISBN group.
    */
    static function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'description' => array( 'name' => 'Description',
                                                         'datatype' => 'string',
                                                         'default' => '',
                                                         'required' => false ),
                                         'group_number' => array( 'name' => 'GroupNumber',
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         ),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'class_name' => 'eZISBNGroup',
                      'name' => 'ezisbn_group' );
    }

    /*!
     \static
     Create a new area for an ISBN number.
     \param $groupNumber is the unique identifier for the area. Could be from 1 to 5 digits.
     \param $description a small description of the registration group area.
     \return a new eZISBNGroup object containing group number and a description.
    */
    static function create( $groupNumber, $description = "" )
    {
        $row = array( 'id' => null,
                      'description' => $description,
                      'group_number' => $groupNumber );
        return new eZISBNGroup( $row );
    }


    /*!
     \static
     Removes the ISBN group based on ID \a $id.
     \param $id is the id the object will be removed based on.
    */
    static function removeByID( $id )
    {
        eZPersistentObject::removeObject( eZISBNGroup::definition(),
                                          array( 'id' => $id ) );
    }

    /*!
     \static
     \param $count The count of the result.
     \param $asObject Whether if the result should be sent back as objects or an array.
     \return the group range list for ISBN groups.
    */
    static function fetchList( $asObject = true )
    {
        $sortArray = array( 'id' => 'asc' );
        return eZPersistentObject::fetchObjectList( eZISBNGroup::definition(),
                                                    null, null, $sortArray, null,
                                                    $asObject );
    }

    /*!
     \static
     \param $groupNumber is the unique number of the Registration group area.
     \param $asObject Whether if the result should be sent back as objects or an array.
     \return the group range list for ISBN groups.
    */
    static function fetchByGroup( $groupNumber, $asObject = true )
    {
        $conditions = array( 'group_number' => $groupNumber );
        $group = false;
        $groupArray = eZPersistentObject::fetchObjectList( eZISBNGroup::definition(),
                                                           null, $conditions, null, null,
                                                           $asObject );
        if ( count( $groupArray ) == 1 )
        {
            $group = $groupArray[0];
        }
        return $group;
    }

    /*!
     \static
     Removes all ISBN groups from the database.
    */
    static function cleanAll()
    {
        $db = eZDB::instance();
        $definition = eZISBNGroup::definition();
        $table = $definition['name'];
        $sql = "TRUNCATE TABLE " . $table;
        $db->query( $sql );
    }
}

?>
