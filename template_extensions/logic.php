<?php

class ezpTemplateLogicFunctions implements ezcTemplateCustomFunction
{
    public static function getCustomFunctionDefinition($name)
    {
        return ezpTemplateFunctions::mapFunctionArgs( __CLASS__, $name );
    }

    /**
     * Returns one of the parameters (pinpointed by the input parameter).
     */
    public static function choose( $offset )
    {
        $arguments = func_get_args();
        array_shift( $arguments );
        if( is_numeric($offset) )
        {
            $offset = (int)$offset;
        }
        elseif( is_bool($offset) )
        {
            $offset = $offset ? 1 : 0;
        }
        else
        {
            throw new ezcTemplateRuntimeException("choose: \$offset must be a numeric or bool, got '$offset' which is " . gettype( $offset ));
        }
        if ( $offset < 0 || $offset > count( $arguments ) )
        {
            throw new ezcTemplateRuntimeException("choose: The \$offset value {$offset} is out of the range [0," . count( $arguments ) . "]");
        }
        return $arguments[$offset];
    }
}

?>
