<?php
//
// Definition of eZInputValidator class
//
// Created on: <08-Jul-2002 16:01:35 amos>
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

/*! \file ezinputvalidator.php
*/

/*!
  \class eZInputValidator ezinputvalidator.php
  \brief Input text validation and correction

  This is the base class for doing validation of input text and eventually correction.
  The general eZRegExpValidator can be used for most validations by supplying it with
  a regexp rule set, for more advanced validation you can use the eZIntegerValidator
  which can validate integers withing ranges.

  For creating your own validators you can either inherit this class or any of the
  advanced classes. The inherited class must implement the validate() function for
  validation and fixup() for fixing text to be acceptable.

  A validation will return a state which can either be Accepted, Intermediate or Invalid.
  Accepted means that the text can be used without modification, Invalid means that the
  text cannot be used at any cost while Intermediate means that the text can be used
  if it's fixed with the fixup() function.

  Example of a simple integer validator
\code
class IntegerValidator
{
    function IntegerValidator()
    {
    }

    function validate( $text )
    {
        return is_numeric( $text ) ? EZ_INPUT_VALIDATOR_STATE_ACCEPTED : EZ_INPUT_VALIDATOR_STATE_INVALID;
    }

    function fixup( &$text )
    {
    }
}
\endcode

  Example of a boolean validator
\code
class BooleanValidator
{
    function BooleanValidator()
    {
    }

    function validate( $text )
    {
        if ( strtolower( $text ) == "true" or
             strtolower( $text ) == "false" )
            return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
        if ( is_numeric( $text ) )
            return EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE;
        return EZ_INPUT_VALIDATOR_STATE_INVALID;
    }

    function fixup( &$text )
    {
        $text = ( $text == 0 ? "false" : "true" );
    }
}
\endcode



*/

define( "EZ_INPUT_VALIDATOR_STATE_ACCEPTED", 1 );
define( "EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE", 2 );
define( "EZ_INPUT_VALIDATOR_STATE_INVALID", 3 );

class eZInputValidator
{
    /*!
     Default constructor, does nothing.
    */
    function eZInputValidator()
    {
    }

    /*!
     Tries to validate to the text \a $text and returns one of the validator states
     EZ_INPUT_VALIDATOR_STATE_ACCEPTED, EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE or
     EZ_INPUT_VALIDATOR_STATE_INVALID.
     This returns EZ_INPUT_VALIDATOR_STATE_ACCEPTED as default and must be reimplemented
     in real valiators.
    */
    function validate( $text )
    {
        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Tries to fix the text \a $text which was previously marked as EZ_INPUT_VALIDATOR_STATE_INTERMEDIATE
     so that it can be seen as EZ_INPUT_VALIDATOR_STATE_ACCEPTED.
    */
    function fixup( &$text )
    {
    }
}

?>
