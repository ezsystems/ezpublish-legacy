<?php

/**
 * Contains helper functions for template functions in eZ publish.
 */
class ezpTemplateFunctions
{
    /**
     * Maps the static function $name on the class $class to appear as
     * a template function with the same name.
     *
     * The arguments of the template function will be the same as the
     * PHP function (using reflection).
     *
     * @return ezcTemplateCustomFunctionDefinition or null if function does not exist.
     */
    public static function mapFunction( $class, $name )
    {
        if( method_exists( $class, $name ) )
        {
            $def = new ezcTemplateCustomFunctionDefinition();
            $def->class  = $class;
            $def->method = $name;
            return $def;
        }
    }

    /**
     * Maps the static function $name on the class $class to appear as
     * a template function with the same name.
     *
     * The arguments of the template function will be the same as the
     * PHP function (using reflection), in addition the template function
     * will accept any number of extra arguments (use func_get_args).
     *
     * @return ezcTemplateCustomFunctionDefinition or null if function does not exist.
     */
    public static function mapFunctionArgs( $class, $name )
    {
        if( method_exists( $class, $name ) )
        {
            $def = new ezcTemplateCustomFunctionDefinition();
            $def->class  = $class;
            $def->method = $name;
            $def->variableArgumentList = true;
            return $def;
        }
    }

    /**
     * Returns true if strict mode is on, false otherwise.
     *
     * Strict mode is on by default, to manually control it set
     * the global variable $ezpStrict.
     */
    public static function isStrictMode()
    {
        global $ezpStrict;
        if ( isset( $ezpStrict ) )
            return $ezpStrict;
        return true;
    }

    /**
     * Handles run-time errors.
     * If strict mode is on it will throw an ezcTemplateRuntimeException with
     * $msg as the message, otherwise it will print $msg.
     */
    public static function runtimeError( $msg )
    {
        if ( self::isStrictMode() )
            throw new ezcTemplateRuntimeException( $msg );
        else
            echo "<pre>Run-time Error: $msg</pre>\n";
    }
}

?>
