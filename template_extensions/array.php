<?php

class ezpTemplateArrayFunctions implements ezcTemplateCustomFunction
{
    public static function getCustomFunctionDefinition($name)
    {
        return ezpTemplateFunctions::mapFunctionArgs( __CLASS__, $name );
    }

    /* Replaces old 'merge' function where parameters are not array */
    public static function array_merge2($input, $arg1)
    {
        $operands = func_get_args();
        $args = array();
        foreach ( $operands as $operand )
        {
            if ( !is_array( $operand ) )
                $operand = array( $operand );
            $args[] = $operand;
        }
        return call_user_func_array( 'array_merge', $args );
    }

}

?>
