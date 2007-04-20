<?php
// Created on: <17-Apr-2007 11:10:23 bjorn>
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

/*! \file ezisbnregistrantrange.php
*/

/*!
  \class eZISBNRegistrantRange ezisbnregistrantrange.php
  \brief The class eZISBNRegistrantRange does

*/

class eZISBNRegistrantRange extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZISBNRegistrantRange( $row )
    {
        $this->eZPersistentObject( $row );
    }

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
                                         'registrant_from' => array( 'name' => 'RegistrantFrom',
                                                                 'datatype' => 'string',
                                                                 'default' => '',
                                                                 'required' => true ),
                                         'registrant_to' => array( 'name' => 'RegistrantTo',
                                                                  'datatype' => 'string',
                                                                  'default' => '',
                                                                  'required' => true ),
                                         'registrant_length' => array( 'name' => 'RegistrantLength',
                                                            'datatype' => 'integer',
                                                            'default' => 0,
                                                            'required' => true ),
                                         'isbn_group_id' => array( 'name' => 'ISBNGroupID',
                                                                     'datatype' => 'integer',
                                                                     'default' => 0,
                                                                     'required' => true ),
                                         ),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'class_name' => 'eZISBNRegistrantRange',
                      'name' => 'ezisbn_registrant_range' );
    }

    /*!
     \static
     Create a new registrant range for a ISBN group / area.
    */
    function create( $ISBNGroupID, $fromNumber, $toNumber, $registrantFrom, $registrantTo, $length )
    {
        $row = array(
            'id' => null,
            'from_number' => $fromNumber,
            'to_number' => $toNumber,
            'registrant_from' => $registrantFrom,
            'registrant_to' => $registrantTo,
            'registrant_length' => $length,
            'isbn_group_id' => $ISBNGroupID );
        return new eZISBNRegistrantRange( $row );
    }


    /*!
     \static
     Removes the registrant area based on ID \a $id.
    */
    function removeByID( $id )
    {
        eZPersistentObject::removeObject( eZISBNRegistrantRange::definition(),
                                          array( 'id' => $id ) );
    }

    /*!
     \return the registrant list for a isbn registration group id.
    */
    function fetchListByGroupID( $groupID, &$count, $asObject = true )
    {
        $conditions = array( 'isbn_group_id' => $groupID );
        $sortArray = array( array( 'from_number' => 'asc' ) );
        $registrantRangeArray = eZPersistentObject::fetchObjectList( eZISBNRegistrantRange::definition(),
                                                    null, $conditions, $sortArray, null,
                                                    $asObject );
        $count = count( $sortArray );
        return $registrantRangeArray;
    }

    function extractRegistrant( $isbnNr, $groupRange, &$registrantLength )
    {
        $registrant = false;
        if ( get_class( $groupRange ) == 'ezisbngrouprange' )
        {
            $groupLength =& $groupRange->attribute( 'group_length' );
            $groupValue = substr( $isbnNr, 3, $groupLength );
            $group = eZISBNGroup::fetchByGroup( $groupValue );
            if ( get_class( $group ) == 'ezisbngroup' )
            {
                $groupID =& $group->attribute( 'id' );
                $registrantOffset = 3 + $groupLength;
                $testSegment = substr( $isbnNr, $registrantOffset, 5 );
                if ( is_numeric( $testSegment ) )
                {
                    $conditions = array( 'from_number' => array( '<=', $testSegment ),
                                         'to_number' => array( '>=', $testSegment ),
                                         'isbn_group_id' => $groupID );
                    $groupRangeArray = eZPersistentObject::fetchObjectList( eZISBNRegistrantRange::definition(),
                                                                            null, $conditions, null, null,
                                                                            true );
                    if ( count( $groupRangeArray ) == 1 )
                    {
                        $length =& $groupRangeArray[0]->attribute( 'registrant_length' );

                        // Copy the length to send it back as a reference.
                        $registrantLength = $length;
                        $registrant = $groupRangeArray[0];
                    }
                }
            }
        }
        return $registrant;
    }

    function cleanAll()
    {
        $db =& eZDB::instance();
        $definition = eZISBNRegistrantRange::definition();
        $table = $definition['name'];
        $sql = "DELETE FROM " . $table;
        $db->query( $sql );
    }
}

?>
