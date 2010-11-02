<?php

class ezpTemplateDeprecatedFunctions implements ezcTemplateCustomFunction
{
    public static function getCustomFunctionDefinition($name)
    {
        if ( $name == 'pdf' )
            return ezpTemplateFunctions::mapFunctionArgs( __CLASS__, $name );
        return ezpTemplateFunctions::mapFunction( __CLASS__, $name );
    }

    /**
     * Prints or throws a deprecation error.
     *
     * If strict mode is on it will throw the error, otherwise print it.
     * @sa ezpTemplateFunctions::isStrictMode
     */
    public static function error( $name, $text = null )
    {
        $msg = "The template function '$name' is deprecated";
        if ( $text )
            $msg .= ": " . $text;
        if ( ezpTemplateFunctions::isStrictMode() )
            throw new ezcTemplateRuntimeException( $msg );
        else
            echo "<pre>Run-time Error: $msg</pre>\n";
    }

    public static function exturl($url)
    {
        self::error( __FUNCTION__ );
        return $url;
    }

    public static function x18n($extension, $string, $options = null, $parameters = null, $para = null)
    {
        self::error( __FUNCTION__, "Function is obsolete, use 'i18n' instead" );
    }

    /* JB-TODO: Propose to transform in conversion script to use debug_dump */
    /**
     * Makes it possible to inspect the contents of arrays, hashes and objects.
     */
    public static function attribute(/*string*/ $showValues = null, $maxVal = 2, $asHTML = true)
    {
        self::error( __FUNCTION__, "Function is obsolete, use 'debug_dump' instead" );
    }

    /**
     * Provides access to the PDF functions.
     */
    public static function pdf($text)
    {
        self::error( __FUNCTION__ );
    }
}

?>
