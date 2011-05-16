<?php
/**
 * File containing the eZFloatValidator class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZFloatValidator ezintegervalidator.php
  \brief The class eZFloatValidator does

*/

class eZFloatValidator extends eZRegExpValidator
{
    /*!
     Constructor
    */
    function eZFloatValidator( $min = false, $max = false )
    {
        $rule = array( "accepted" => "/^-?[0-9]+([.][0-9]+)?$/",
                       "intermediate" => "/(-?[0-9]+([.][0-9]+)?)/",
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
