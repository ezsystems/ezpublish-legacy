<?php
// Created on: <17-Apr-2007 11:07:53 bjorn>
//
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: n.n.n
// BUILD VERSION: nnnnn
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

/*! \file ezisbngroup.php
*/

/*!
  \class eZISBNGroup ezisbngroup.php
  \brief The class eZISBNGroup does

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
     returns a definition of the isbn group.
    */
    function definition()
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
     Create a new area for a ISBN.
    */
    function create( $groupNumber, $description = "" )
    {
        $row = array( 'id' => null,
                      'description' => $description,
                      'group_number' => $groupNumber );
        return new eZISBNGroup( $row );
    }


    /*!
     \static
     Removes the ISBN group based on ID \a $id.
    */
    function removeByID( $id )
    {
        eZPersistentObject::removeObject( eZISBNGroup::definition(),
                                          array( 'id' => $id ) );
    }

    /*!
     \static
     \return the group range list for isbn groups.
    */
    function fetchList( &$count, $asObject = true )
    {
        $sortArray = array( array( 'from_number' => 'asc' ) );
        $groupArray = eZPersistentObject::fetchObjectList( eZISBNGroup::definition(),
                                                           null, null, $sortArray, null,
                                                           $asObject );
        $count = count( $sortArray );
        return $groupArray;
    }

    /*!
     \static
     \return the group range list for isbn groups.
    */
    function fetchByGroup( $groupNumber, $asObject = true )
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

    function cleanAll()
    {
        $db =& eZDB::instance();
        $definition = eZISBNGroup::definition();
        $table = $definition['name'];
        $sql = "DELETE FROM " . $table;
        $db->query( $sql );
    }
}

?>
