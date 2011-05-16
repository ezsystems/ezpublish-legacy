<?php
/**
 * File containing the eZRegExpValidator class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZRegExpValidator ezregexpvalidator.php
  \brief Input validation using regexps

*/

class eZRegExpValidator extends eZInputValidator
{
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
