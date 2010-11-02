<?php

class ezpTemplateUrlFunctions implements ezcTemplateCustomFunction
{
    public static function getCustomFunctionDefinition($name)
    {
        return ezpTemplateFunctions::mapFunction( __CLASS__, $name );
    }

    /*!
     Helper function for ezdesign and ezimage
     */
    public static function quote( $text, $quote )
    {
        if ( $quote == 'no' )
            return $text;

        if ( $quote == 'single' )
            $quote = "'";
        else if ( $quote == 'double' )
            $quote = "\"";
        else
            return ezpTemplateFunctions::runtimeError( "Invalid quote type '$quote'" );
        return $quote . $text . $quote;
    }

    /**
     * Returns a working version of an eZ Publish URL (provided as input).
     */
    public static function ezurl($urlName, $quote = "double", $serverURL = "relative")
    {
        eZURI::transformURI( $urlName, false, $serverURL);
        return self::quote( $urlName, $quote );
    }

    /**
     * Returns the input string prepended with the current design directory.
     */
    public static function ezdesign($operatorValue, $quote = 'double')
    {
        return self::quote( ezpResource::find( $operatorValue, false, false ), $quote );
    }

    /**
     * Returns the input string prepended with the current image directory.
     */
    public static function ezimage( $operatorValue, $quote = 'double', $skipSlash = false )
    {
        return self::quote( ezpResource::find( $operatorValue, 'images', $skipSlash ), $quote );
    }

    /**
     * Same as "ezurl" without "index.php" and the siteaccess name in the returned address.
     */
    public static function ezroot($operatorValue, $serverUrl = false)
    {
        if ( preg_match( "#^[a-zA-Z0-9]+:#", $operatorValue ) or
             substr( $operatorValue, 0, 2 ) == '//' )
             break;
        if ( strlen( $operatorValue ) > 0 and
             $operatorValue[0] != '/' )
            $operatorValue = '/' . $operatorValue;

        // Same as "ezurl" without "index.php" and the siteaccess name in the returned address.
        eZURI::transformURI( $operatorValue, true, $serverUrl );

        return $operatorValue;

    }

    // ezwebin: template.ini
    public static function rawurldecode($text)
    {
        return rawurldecode($text);
    }

    // ezwebin: template.ini
    public static function rawurlencode($text)
    {
        return rawurlencode($text);
    }
}

?>
