<?php
//
// Definition of eZRegExpValidator class
//
// Created on: <08-Jul-2002 16:17:15 amos>
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

/*! \file ezregexpvalidator.php
*/

/*!
  \class eZRegExpValidator ezregexpvalidator.php
  \brief Input validation using regexps

*/

include_once( "lib/ezutils/classes/ezinputvalidator.php" );

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
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
        $accepted =& $this->RegExpRule["accepted"];
        if ( preg_match( $accepted, $text ) )
            return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
        $intermediate =& $this->RegExpRule["intermediate"];
        if ( preg_match( $intermediate, $text ) )
            return EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE;
        return EZ_INPUT_VALIDATOR_STATE_INVALID;
    }

    function &fixup( $text )
    {
        if ( !is_array( $this->RegExpRule ) )
            return $text;
        $intermediate =& $this->RegExpRule["intermediate"];
        $fixup =& $this->RegExpRule["fixup"];
        if ( is_array( $fixup ) )
        {
            $intermediate =& $fixup["match"];
            $fixup =& $fixup["replace"];
        }
        $text = preg_replace( $intermediate, $fixup, $text );
        return $text;
    }

    /// \privatesection
    var $RegExpRule;
}

?>
