<?php
//
// Created on: <17-Apr-2007 11:07:06 bjorn>
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
  \class eZISBN13 ezisbn13.php
  \brief The class eZISBN13 handles ISBN-13 numbers.

  The class is containing an ISBN-13 number and extracts the different groups
  based on the information stored in the different ranges for Registration group
  and Registration elements. The Publication element will get the space left available.
*/

class eZISBN13
{
    const PREFIX_LENGTH = 3;
    const CHECK_LENGTH = 1;
    const LENGTH = 13;
    const PREFIX_978 = 978;
    const PREFIX_979 = 979;

    /*!
     Constructor
     \param $isbnNr is the ISBN-13 number. example is: 978-0-11-000222-4
     \param $separator is the hyphen used in the ISBN number to make the
                       ISBN number more visible.
    */
    function eZISBN13( $isbnNr = null, $separator = '-' )
    {
        if ( $isbnNr !== null )
        {
            $this->extractISBNNumber( $isbnNr, $separator );
        }
        else
        {
            $this->Prefix = false;
            $this->RegistrationGroup = false;
            $this->RegistrantElement = false;
            $this->PublicationElement = false;
            $this->CheckDigit = false;
        }
    }

    /*!
     Contains a list of all attributes for this class.
     \return the array with existing attributes.
    */
    function attributes()
    {
        return array( 'has_content',
                      'group_ranges',
                      'groups' );
    }

    /*!
     Fetch the attribute sent in $value.
     \param $value is the name of the attribute that should be fetched.
     \return the result of the attribute.
    */
    function attribute( $value )
    {
        switch ( $value )
        {
            case "has_content":
            {
                return eZISBN13::hasRangeData();
            }break;

            case "groups":
            {
                $groupList = eZISBNGroup::fetchList();
                return array( 'group_list' => $groupList,
                              'count' => count(  $groupList ) );
            }break;

            case "group_ranges":
            {
                $groupList = eZISBNGroupRange::fetchList();
                return array( 'group_list' => $groupList,
                              'count' => count( $groupList ) );
            }break;
        }
        return null;
    }

    /*!
     Check if the attribute set in the string $value exists.
     \param $value is the attribute you want to see if exist.
     \return true if the attribute is found.
    */
    function hasAttribute( $value )
    {
        return in_array( $value, eZISBN13::attributes() );
    }

    /*!
     Check if any ISBN ranges exist.
     \return true if any ranges are found.
    */
    function hasRangeData()
    {
        $db = eZDB::instance();
        $tableList = $db->eZTableList();
        if ( array_key_exists( 'ezisbn_group', $tableList ) and
             array_key_exists( 'ezisbn_group_range', $tableList ) and
             array_key_exists( 'ezisbn_registrant_range', $tableList ) )
        {
            $query = "SELECT count( ezisbn_group.id ) AS count
                      FROM ezisbn_group, ezisbn_group_range, ezisbn_registrant_range
                      WHERE ezisbn_group.group_number >= ezisbn_group_range.from_number AND
                            ezisbn_group.group_number <= ezisbn_group_range.to_number AND
                            ezisbn_group.id=ezisbn_registrant_range.isbn_group_id";
            $countArray = $db->arrayQuery( $query );
            return ( $countArray[0]['count'] > 0 );
        }
        else
            return false;
    }

    /*!
     Receives an ISBN number and place hyphen on the correct place in the number.
     If the placement is not found, an error message will be set and false

     The different parts of the ISBN-13 number will be stored in separate class variables.

     \param $isbnNr is the ISBN-13 number. Should be 13 digits long and may contain space or hyphen as separator.
     \param $error is used to send back an error message that will be shown to the user if the ISBN number was
                   not extracted correctly.
     \param $separator is the separator used to make the ISBN number visible. Could be either a space or hyphen.
     \return A formated ISBN number or the original value if it was not possible to find the structure.
    */
    function formatedISBNValue( $isbnNr = false, &$error, $separator = '-' )
    {
        if ( $isbnNr !== false )
        {
            $formatedISBN13 = preg_replace( "/[\s|\-]+/", "-", $isbnNr );
            $status = $this->extractISBNNumber( $isbnNr, $error );

            if ( $status === false )
            {
                $formatedISBN13 = substr( $isbnNr, 0, self::PREFIX_LENGTH );
                if ( strlen( $this->RegistrationGroup ) > 0 )
                {
                    $formatedISBN13 .= $separator . $this->RegistrationGroup;
                    if ( strlen( $this->RegistrantElement ) > 0 )
                    {
                        $formatedISBN13 .= $separator . $this->RegistrantElement . $separator .
                                           $this->PublicationElement . $separator;
                    }
                    else
                    {
                        $offset = strlen( $this->RegistrationGroup ) + self::PREFIX_LENGTH;
                        $length = strlen( $isbnNr ) - $offset - self::CHECK_LENGTH;
                        $originalValue = substr( $isbnNr, $offset, $length );
                        $formatedISBN13 .= $originalValue;
                    }
                }
                else
                {
                    $offset = self::PREFIX_LENGTH;
                    $length = strlen( $isbnNr ) - $offset - self::CHECK_LENGTH;
                    $originalValue = substr( $isbnNr, $offset, $length );
                    $formatedISBN13 .= $originalValue;
                }

                $length = strlen( $isbnNr );
                $formatedISBN13 .= substr( $isbnNr, $length - self::CHECK_LENGTH, $length );
                return $formatedISBN13;
            }
        }
        else
        {
            $formatedISBN13 = $this->Prefix . $separator;
            if ( strlen( $this->RegistrationGroup ) > 0 )
            {
                $formatedISBN13 .= $this->RegistrationGroup . $separator;
                if ( strlen( $this->RegistrantElement ) > 0 )
                {
                    $formatedISBN13 .= $this->RegistrantElement . $separator .
                         $this->PublicationElement . $separator;
                }
                else
                {
                    $formatedISBN13 .= $this->RegistrantElement .
                         $this->PublicationElement . $separator;
                }
            }
            else
            {
                $formatedISBN13 .= $this->RegistrationGroup .
                     $this->RegistrantElement .
                     $this->PublicationElement . $separator;
            }
            $formatedISBN13 .= $this->CheckDigit;
        }

        if ( strlen( $this->Prefix . $this->RegistrationGroup . $this->RegistrantElement . $this->PublicationElement . $this->CheckDigit ) == self::LENGTH )
        {
            $formatedISBN13 = $this->Prefix . $separator .
                 $this->RegistrationGroup . $separator .
                 $this->RegistrantElement . $separator .
                 $this->PublicationElement . $separator .
                 $this->CheckDigit;
        }
        return $formatedISBN13;
    }

    /*!
      Extracts the ISBN-13 number and are setting the class variables for the different
      parts when the value is found. The class variables should be set as default false
      in the constructor.

      \param $isbnNr is the ISBN-13 number. Should be 13 digits long and may contain space or hyphen as separator.
      \param $error is used to send back an error message that will be shown to the user if the ISBN number was
                    not extracted correctly.

      \return true if the ISBN-13 number was successfully extracted and false if not.
    */
    function extractISBNNumber( $isbnNr = false, &$error )
    {
        $ini = eZINI::instance( 'content.ini' );
        $ean = preg_replace( "/[\s|\-]+/", "", $isbnNr );
        if ( is_numeric( $ean ) and strlen( $ean ) == self::LENGTH )
        {
            $prefix = substr( $ean, 0, self::PREFIX_LENGTH );
            $this->Prefix = $prefix;

            $checkDigit = substr( $ean, 12, self::CHECK_LENGTH );
            $this->CheckDigit = $checkDigit;
            if ( $prefix == self::PREFIX_978 )
            {
                $registrantValue = false;
                $groupValue = false;
                $publicationValue = false;
                $checkDigit = false;

                $groupRange = eZISBNGroupRange::extractGroup( $ean );
                $groupLength = false;
                if ( $groupRange )
                {
                    $groupLength = $groupRange->attribute( 'group_length' );
                }

                if ( $groupLength )
                {
                    $groupValue = substr( $ean, self::PREFIX_LENGTH, $groupLength );
                    $this->RegistrationGroup = $groupValue;

                    $group = eZISBNGroup::fetchByGroup( $groupValue );
                    if ( $group instanceof eZISBNGroup )
                    {
                        $registrant = eZISBNRegistrantRange::extractRegistrant( $ean, $group, $groupRange, $registrantLength );
                        if ( $registrant instanceof eZISBNRegistrantRange and
                             $registrantLength > 0 )
                        {
                            $registrantOffset = self::PREFIX_LENGTH + $groupLength;
                            $registrantValue = substr( $ean, $registrantOffset, $registrantLength );

                            $this->RegistrantElement = $registrantValue;

                            $publicationOffset = $registrantOffset + $registrantLength;
                            $publicationLength = 12 - $publicationOffset;
                            $publicationValue = substr( $ean, $publicationOffset, $publicationLength );
                            $this->PublicationElement = $publicationValue;
                        }
                        else
                        {
                            $strictValidation = $ini->variable( 'ISBNSettings', 'StrictValidation' );
                            if ( $strictValidation == 'true' )
                            {
                                $error = ezi18n( 'kernel/classes/datatypes', 'The registrant element of the ISBN number does not exist.' );
                                return false;
                            }
                        }
                    }
                    else
                    {
                        $strictValidation = $ini->variable( 'ISBNSettings', 'StrictValidation' );
                        if ( $strictValidation == 'true' )
                        {
                            $error = ezi18n( 'kernel/classes/datatypes', 'The ISBN number has a incorrect registration group number.' );
                            return false;
                        }
                    }
                }
                else
                {
                    $strictValidation = $ini->variable( 'ISBNSettings', 'StrictValidation' );
                    if ( $strictValidation == 'true' )
                    {
                        $error = ezi18n( 'kernel/classes/datatypes', 'The group element of the ISBN number does not exist.' );
                        return false;
                    }
                }
            }
            else
            {
                $strictValidation = $ini->variable( 'ISBNSettings', 'StrictValidation' );
                if ( $strictValidation == 'true' )
                {
                    $error = ezi18n( 'kernel/classes/datatypes', '%1 is not a valid prefix of the ISBN number.', null, array( $prefix ) );
                    return false;
                }
            }
        }
        else
        {
            $error = ezi18n( 'kernel/classes/datatypes', 'All ISBN 13 characters need to be numeric' );
            return false;
        }
        return true;
    }

    /*!
     Validates the ISBN-13 number \a $isbnNr.
     \param $isbnNr A string containing the number without any dashes.
     \param $error is used to send back an error message that will be shown to the user if the ISBN number was
                   not extracted correctly.
     \return \c true if it is valid.
    */
    function validate( $isbnNr, &$error )
    {
        $valid = $this->validateISBN13Checksum( $isbnNr, $error );
        if ( $valid == true )
        {
            $valid = $this->extractISBNNumber( $isbnNr, $error );
        }
        return $valid;
    }

    /*!
     \private
     Validates the ISBN-13 number \a $isbnNr.
     \param $isbnNr A string containing the number without any dashes.
     \param $error is used to send back an error message that will be shown to the user if the
                   ISBN number validated.
     \return \c true if it is valid.
    */
    function validateISBN13Checksum ( $isbnNr, &$error )
    {
        if ( !$isbnNr )
            return false;
        $isbnNr = preg_replace( "/[\s|\-]+/", "", $isbnNr );
        if ( substr( $isbnNr, 0, self::PREFIX_LENGTH ) != self::PREFIX_978 and
             substr( $isbnNr, 0, self::PREFIX_LENGTH ) != self::PREFIX_979 )
        {
            $error = ezi18n( 'kernel/classes/datatypes',
                             '13 digit ISBN must start with 978 or 979' );
            return false;
        }

        $checksum13 = 0;
        $weight13 = 1;
        if ( strlen( $isbnNr ) != self::LENGTH )
        {
            $error = ezi18n( 'kernel/classes/datatypes', 'ISBN length is invalid' );
            return false;
        }

        //compute checksum
        $val = 0;
        for ( $i = 0; $i < self::LENGTH; $i++ )
        {
            $val = $isbnNr{$i};
            if ( !is_numeric( $isbnNr{$i} ) )
            {
                $error = ezi18n( 'kernel/classes/datatypes', 'All ISBN 13 characters need to be numeric' );
                return false;
            }
            $checksum13 = $checksum13 + $weight13 * $val;
            $weight13 = ( $weight13 + 2 ) % 4;
        }
        if ( ( $checksum13 % 10 ) != 0 )
        {
            // Calculate the last digit from the 12 first numbers.
            $checkDigit = ( 10 - ( ( $checksum13 - ( ( $weight13 + 2 ) % 4 ) * $val ) % 10 ) ) % 10;
            //bad checksum
            $error = ezi18n( 'kernel/classes/datatypes', 'Bad checksum, last digit should be %1', null, array( $checkDigit ) );
            return false;
        }

        return true;
    }

    public $Prefix;
    public $RegistrationGroup;
    public $RegistrantElement;
    public $PublicationElement;
    public $CheckDigit;
}

?>
