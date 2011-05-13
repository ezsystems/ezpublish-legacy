<?php
/**
 * File containing the eZInputValidator class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
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
        return is_numeric( $text ) ? eZInputValidator::STATE_ACCEPTED : eZInputValidator::STATE_INVALID;
    }

    function fixup( $text )
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
            return eZInputValidator::STATE_ACCEPTED;
        if ( is_numeric( $text ) )
            return eZInputValidator::STATE_INTERMEDIATE;
        return eZInputValidator::STATE_INVALID;
    }

    function fixup( $text )
    {
        $text = ( $text == 0 ? "false" : "true" );
    }
}
\endcode



*/

class eZInputValidator
{
    const STATE_ACCEPTED = 1;
    const STATE_INTERMEDIATE = 2;
    const STATE_INVALID = 3;

    /*!
     Default constructor, does nothing.
    */
    function eZInputValidator()
    {
    }

    /*!
     Tries to validate to the text \a $text and returns one of the validator states
     eZInputValidator::STATE_ACCEPTED, eZInputValidator::STATE_INTERMEDIATE or
     eZInputValidator::STATE_INVALID.
     This returns eZInputValidator::STATE_ACCEPTED as default and must be reimplemented
     in real valiators.
    */
    function validate( $text )
    {
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     Tries to fix the text \a $text which was previously marked as eZInputValidator::STATE_INTERMEDIATE
     so that it can be seen as eZInputValidator::STATE_ACCEPTED.
    */
    function fixup( $text )
    {
    }
}

?>
