<?php
//
// Definition of eZIntegerValidator class
//
// Created on: <08-Jul-2002 17:15:51 amos>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezintegervalidator.php
*/

/*!
  \class eZIntegerValidator ezintegervalidator.php
  \brief The class eZIntegerValidator does

*/

include_once( "lib/ezutils/classes/ezregexpvalidator.php" );

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
        if ( $state == EZ_INPUT_VALIDATOR_STATE_ACCEPTED )
        {
            if ( ( $this->MinValue !== false and $text < $this->MinValue ) or
                 ( $this->MaxValue !== false and $text > $this->MaxValue ) )
                $state = EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE;
        }
        return $state;
    }

    function &fixup( $text )
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
    var $MinValue;
    var $MaxValue;
}

?>
