<?php
// Created on: <17-Apr-2007 11:08:55 bjorn>
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

/*! \file ezisbngrouprange.php
*/

/*!
  \class eZISBNGroupRange ezisbngrouprange.php
  \brief The class eZISBNGroupRange does

*/

class eZISBNGroupRange extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZISBNGroupRange( $row )
    {
        $this->eZPersistentObject( $row );
    }


    /*!
      Definition of the ranges for ISBN groups.
    */
    function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         'from_number' => array( 'name' => 'FromNumber',
                                                                 'datatype' => 'integer',
                                                                 'default' => 0,
                                                                 'required' => true ),
                                         'to_number' => array( 'name' => 'ToNumber',
                                                               'datatype' => 'integer',
                                                               'default' => 0,
                                                               'required' => true ),
                                         'group_from' => array( 'name' => 'GroupFrom',
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => true ),
                                         'group_to' => array( 'name' => 'GroupTo',
                                                              'datatype' => 'string',
                                                              'default' => '',
                                                              'required' => true ),
                                         'group_length' => array( 'name' => 'GroupLength',
                                                                  'datatype' => 'integer',
                                                                  'default' => 0,
                                                                  'required' => true ),
                                         ),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'class_name' => 'eZISBNGroupRange',
                      'name' => 'ezisbn_group_range' );
    }

    /*!
     \static
     Create a new group range for a ISBN.
    */
    function create( $fromNumber, $toNumber, $groupFrom, $groupTo, $length )
    {
        $row = array(
            'id' => null,
            'from_number' => $fromNumber,
            'to_number' => $toNumber,
            'group_from' => $groupFrom,
            'group_to' => $groupTo,
            'group_length' => $length );
        return new eZISBNGroupRange( $row );
    }


    /*!
     \static
     Removes the ISBN group based on ID \a $id.
    */
    function removeByID( $id )
    {
        eZPersistentObject::removeObject( eZISBNGroupRange::definition(),
                                          array( 'id' => $id ) );
    }

    /*!
     \return the group range list for isbn groups.
    */
    function fetchList( &$count, $asObject = true )
    {
        $sortArray = array( array( 'from_number' => 'asc' ) );
        $groupRangeArray = eZPersistentObject::fetchObjectList( eZISBNGroupRange::definition(),
                                                                null, null, $sortArray, null,
                                                                $asObject );
        $count = count( $sortArray );
        return $groupRangeArray;
    }

    function extractGroup( $isbnNr, &$groupLength )
    {
        $groupRange = false;
        $testSegment = substr( $isbnNr, 3, 5 );
        if ( is_numeric( $testSegment ) )
        {
            $conditions = array( 'from_number' => array( '<=', $testSegment ),
                                 'to_number' => array( '>=', $testSegment ) );
            $groupRangeArray = eZPersistentObject::fetchObjectList( eZISBNGroupRange::definition(),
                                                                    null, $conditions );
            if ( count( $groupRangeArray ) == 1 )
            {
                $groupRange = $groupRangeArray[0];
                $length =& $groupRange->attribute( 'group_length' );

                $groupLength = $length;
            }
        }
        return $groupRange;
    }

    function cleanAll()
    {
        $db =& eZDB::instance();
        $definition = eZISBNGroupRange::definition();
        $table = $definition['name'];
        $sql = "DELETE FROM " . $table;
        $db->query( $sql );
    }

}

?>
