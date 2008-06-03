<?php
//
// Definition of eZRegExpValidator class
//
// Created on: <08-Jul-2002 16:17:15 amos>
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

/*! \file ezregexpvalidator.php
*/

/*!
  \class eZRegExpValidator ezregexpvalidator.php
  \brief Input validation using regexps

*/

//include_once( "lib/ezutils/classes/ezinputvalidator.php" );

class eZRegExpValidator extends eZInputValidator
{
    /*!
    */
    function eZRegExpValidator( $rule = null )
    {
        $this->eZInputValidator();
        $this->RegExpRule = $rule;
    }

    function setRegExpRule( $rule )
    {
        $this->RegExpRule = $rule;
    }

    function validate( $text )
    {
        if ( !is_array( $this->RegExpRule ) )
            return eZInputValidator::STATE_INVALID;
        $accepted =& $this->RegExpRule["accepted"];
        if ( preg_match( $accepted, $text ) )
            return eZInputValidator::STATE_ACCEPTED;
        $intermediate =& $this->RegExpRule["intermediate"];
        if ( preg_match( $intermediate, $text ) )
            return eZInputValidator::STATE_INTERMEDIATE;
        return eZInputValidator::STATE_INVALID;
    }

    function fixup( $text )
    {
        if ( !is_array( $this->RegExpRule ) )
            return $text;
        $intermediate =& $this->RegExpRule["intermediate"];
        $fixup =& $this->RegExpRule["fixup"];
        if ( is_array( $fixup ) )
        {
            $intermediate = $fixup["match"];
            $fixup = $fixup["replace"];
        }
        $text = preg_replace( $intermediate, $fixup, $text );
        return $text;
    }

    /// \privatesection
    public $RegExpRule;
}

?>
