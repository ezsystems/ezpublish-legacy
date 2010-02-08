<?php
//
// Definition of eZDateTimeValidator class
//
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

/*! \file
*/

/*!
  \class eZDateTimeValidator ezdatetimevalidator.php
  \brief The class eZDateTimeValidator does

*/

class eZDateTimeValidator extends eZInputValidator
{
    /*!
     Constructor
    */
    function eZDateTimeValidator()
    {
    }

    static function validateDate( $day, $month, $year )
    {
        $check = checkdate( $month, $day, $year );
        $datetime = mktime( 0, 0, 0, $month, $day, $year );
        if ( !$check or
             $year < 1970 or
             $datetime === false )
        {
            return eZInputValidator::STATE_INVALID;
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    static function validateTime( $hour, $minute, $second = 0 )
    {
        if ( preg_match( '/\d+/', trim( $hour )   ) &&
             preg_match( '/\d+/', trim( $minute ) ) &&
             preg_match( '/\d+/', trim( $second ) ) &&
             $hour >= 0 && $minute >= 0 && $second >= 0 &&
             $hour < 24 && $minute < 60 && $second < 60 )
        {
            return eZInputValidator::STATE_ACCEPTED;
        }
        return eZInputValidator::STATE_INVALID;
    }

    static function validateDateTime( $day, $month, $year, $hour, $minute, $second = 0 )
    {
        $check = checkdate( $month, $day, $year );
        $datetime = mktime( $hour, $minute, $second, $month, $day, $year );
        if ( !$check or
             $year < 1970 or
             $datetime === false or
             eZDateTimeValidator::validateTime( $hour, $minute ) == eZInputValidator::STATE_INVALID )
        {
            return eZInputValidator::STATE_INVALID;
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    /// \privatesection
}

?>
