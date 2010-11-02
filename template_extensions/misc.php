<?php

class ezpTemplateMiscFunctions implements ezcTemplateCustomFunction
{
    public static function getCustomFunctionDefinition($name)
    {
        return ezpTemplateFunctions::mapFunction( __CLASS__, $name );
    }

    public static function alphabet()
    {
        return eZAlphabetOperator::fetchAlphabet();
    }

    /**
     * Returns the type of the provided variable.
     */
    public static function get_type($operand)
    {
        if ( $operand === null )
            return 'null';
        else if ( is_bool( $operand ) )
            return 'boolean[' . ( $operand ? 'true' : 'false' ) . ']';
        else if ( is_object( $operand ) )
            return 'object[' . strtolower( get_class( $operand ) ) . ']';
        else if ( is_array( $operand ) )
            return 'array[' . count( $operand ) . ']';
        else if ( is_string( $operand ) )
            return 'string[' . strlen( $operand ) . ']';
        else
            return gettype( $operand );
    }

    /**
     * Generates a roman representation of a number.
     */
    public static function roman( $value )
    {
        return ezpRomanNumber::generate( $value );
    }

    /**
     * Handles unit display of values (output formatting).
     */
    public static function si( $value, /*string*/ $unit, /*string*/ $prefix = 'auto',
                               $decimal_count = false, $decimal_symbol = false, $thousands_separator = false )
    {
        return ezpMathUnit::format( $value, $unit, $prefix,
                                    $decimal_count, $decimal_symbol, $thousands_separator );
    }
}

?>
