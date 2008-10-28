<?php
//
// Definition of eZIntegerValidator class
//
// Created on: <08-Jul-2002 17:15:51 amos>
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

/*! \file ezintegervalidator.php
*/

/*!
  \class eZIntegerValidator ezintegervalidator.php
  \brief The class eZIntegerValidator does

*/

class eZIntegerValidator extends eZRegExpValidator
{
    /*!
     Constructor
    */
    function eZIntegerValidator( $min = false, $max = false )
    {
        $rule = array( "accepted" => "/^-?[0-9]+$/",
                       "intermediate" => "/(-?[0-9]+)/",
                       "fixup" => "" );
        $this->eZRegExpValidator( $rule );
        $this->MinValue = $min;
        $this->MaxValue = $max;
        if ( $max !== false and $min !== false )
            $this->MaxValue = max( $min, $max );
    }

    function setRange( $min, $max )
    {
        $this->MinValue = $min;
        $this->MaxValue = $max;
        if ( $max !== false and $min !== false )
            $this->MaxValue = max( $min, $max );
    }

    function validate( $text )
    {
        $state = eZRegExpValidator::validate( $text );
        if ( $state == eZInputValidator::STATE_ACCEPTED )
        {
            if ( ( $this->MinValue !== false and $text < $this->MinValue ) or
                 ( $this->MaxValue !== false and $text > $this->MaxValue ) )
                $state = eZInputValidator::STATE_INTERMEDIATE;
        }
        return $state;
    }

    function fixup( $text )
    {
        if ( preg_match( $this->RegExpRule["intermediate"], $text, $regs ) )
            $text = $regs[1];
        if ( $this->MinValue !== false and $text < $this->MinValue )
            $text = $this->MinValue;
        else if ( $this->MaxValue !== false and $text > $this->MaxValue )
            $text = $this->MaxValue;
        return $text;
    }

    /// \privatesection
    public $MinValue;
    public $MaxValue;
}

?>
