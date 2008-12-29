<?php
//
// Created on: <17-Apr-2007 11:10:23 bjorn>
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

/*! \file ezisbnregistrantrange.php
*/

/*!
  \class eZISBNRegistrantRange ezisbnregistrantrange.php
  \brief The class eZISBNRegistrantRange handles Registrant ranges.

  Has information about how the different ranges the registrant element
  could be in. Example: From 00 to 19 and continues from 200-699.
  This means that the length of the registrant can differ from
  range to range.

  The registrant element is the third element in the ISBN-13 number, after
  the Prefix and Registration group number.

  Example: 978-0-11-000222-4 where 11 is the registrant number.

  The different Registrant ranges are described in more detail at
  http://www.isbn-international.org
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

    /*!
      Definition of the ranges for ISBN Registrant.
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
     \param $ISBNGroupID    The id that point to the ISBN Group object
                            (Which contain info about the area and the unique group number).
     \param $fromNumber     Group is starting from test number, which is based on. Example: 20000
                            the 5 numbers after the Prefix number and Registration Group number.
     \param $toNumber       Group is ending on test number, which is based on. Example: 69999
                            the 5 numbers after the Prefix number and Registration Group number.
     \param $registrantFrom Registrant number is starting on, based on the length set
                            in the registrant range. Is a string to support 0 in front. Example: 200
     \param $registrantTo   Registrant number ending on, based on the length set
                            in the registrant range. Is a string to support 0 in front. Example: 699
     \param $length         How many characters $registrantFrom and $registrantTo should have.

     Create a new registrant range for an ISBN group / area.

     \return A new eZISBNRegistrantRange object.
    */
    static function create( $ISBNGroupID, $fromNumber, $toNumber, $registrantFrom, $registrantTo, $length )
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
    static function removeByID( $id )
    {
        eZPersistentObject::removeObject( eZISBNRegistrantRange::definition(),
                                          array( 'id' => $id ) );
    }

    /*!
     \static

     Fetch the registrant group for a unique registration group area.

     \param $groupID  The id that point to the ISBN Group object
                      (Which contain info about the area and the unique group number).
     \param $asObject If the result should be returned as object or an array.
     \return the registrant list for an ISBN registration group id.
    */
    static function fetchListByGroupID( $groupID, $asObject = true )
    {
        $conditions = array( 'isbn_group_id' => $groupID );
        $sortArray = array( array( 'from_number' => 'asc' ) );
        return eZPersistentObject::fetchObjectList( eZISBNRegistrantRange::definition(),
                                                    null, $conditions, $sortArray, null,
                                                    $asObject );
    }

    /*!
     \static

     Will extract the registrant number based on the different ranges
     which is based on the 5 first digits after the Prefix field and the registration group number.

     \param $isbnNr Should be a stripped down ISBN number with just the digits (ean number).
     \param $group is an object of eZISBNGroup, which needs to be known before this function is called.
                   Contains information about the group itself.
     \param $groupRange is an object of eZISBNGroupRange, which needs to be known before this function is called.
                        Contains information about the valid ranges for the ISBN group.
     \param $registrantLength is the length of the Registrant in the range that was found.
                              Is sent back in the reference variable.

     \return the registrant range object if found and false if not found.
    */
    static function extractRegistrant( $isbnNr, $group, $groupRange, &$registrantLength )
    {
        $registrant = false;
        if ( $group instanceof eZISBNGroup and
             $groupRange instanceof eZISBNGroupRange )
        {
            $groupLength = $groupRange->attribute( 'group_length' );
            $groupID = $group->attribute( 'id' );

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
                    $length = $groupRangeArray[0]->attribute( 'registrant_length' );

                    // Copy the length to send it back as a reference.
                    $registrantLength = $length;
                    $registrant = $groupRangeArray[0];
                }
            }
        }
        return $registrant;
    }

    /*!
     \static
     Removes all ISBN group ranges from the database.
    */
    static function cleanAll()
    {
        $db = eZDB::instance();
        $definition = eZISBNRegistrantRange::definition();
        $table = $definition['name'];
        $sql = "TRUNCATE TABLE " . $table;
        $db->query( $sql );
    }
}

?>
