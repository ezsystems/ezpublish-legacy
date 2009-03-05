<?php
//
// Created on: <17-Apr-2007 11:08:55 bjorn>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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

/*! \file
*/

/*!
  \class eZISBNGroupRange ezisbngrouprange.php
  \brief The class eZISBNGroupRange handle registration group ranges.

  Has information about how the different ranges the registration group
  element could be in. Example: From 0 to 5 and continues from 600-602.
  This means that the length of the registration group can differ from
  range to range.

  The different Registration group ranges are described in more detail at
  http://www.isbn-international.org

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
    static function definition()
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
     Create a new group range for an ISBN number.
     \param $fromNumber Group is starting from test number, which is based on
                        the 5 numbers after the Prefix number.
     \param $toNumber   Group is ending on the To test number, which is based on
                        the 5 numbers after the Prefix number.
     \param $groupFrom  Group number is starting on, based on the length set
                        in the Group.
     \param $groupTo    Group number is ending on, based on the length set
                        in the group.
     \param $length     How many characters $groupFrom and $groupTo should have.
     \return a new eZISBNGroupRange object.
    */
    static function create( $fromNumber, $toNumber, $groupFrom, $groupTo, $length )
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
    static function removeByID( $id )
    {
        eZPersistentObject::removeObject( eZISBNGroupRange::definition(),
                                          array( 'id' => $id ) );
    }

    /*!
     \param $asObject Whether if the result should be sent back as objects or an array.
     \return the group range list for ISBN groups.
    */
    static function fetchList( $asObject = true )
    {
        $sortArray = array( 'from_number' => 'asc' );
        return eZPersistentObject::fetchObjectList( eZISBNGroupRange::definition(),
                                                    null, null, $sortArray, null,
                                                    $asObject );
    }

    /*!
     \static
     Will extract the group number based on the different ranges
     which is based on the 5 first digits after the Prefix field.
     \param $isbnNr Should be a stripped down ISBN number with just the digits (ean number).
     \return the group range object if found and false if not found.
    */
    static function extractGroup( $isbnNr )
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
            }
        }
        return $groupRange;
    }

    /*!
     \static
     Removes all ISBN group ranges from the database.
    */
    static function cleanAll()
    {
        $db = eZDB::instance();
        $definition = eZISBNGroupRange::definition();
        $table = $definition['name'];
        $sql = "TRUNCATE TABLE " . $table;
        $db->query( $sql );
    }

}

?>
