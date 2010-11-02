<?php

class ezpTemplateStringFunctions implements ezcTemplateCustomFunction
{
    public static function getCustomFunctionDefinition($name)
    {
        return ezpTemplateFunctions::mapFunction( __CLASS__, $name );
    }

    /**
     * Returns the input string with all newlines converted to HTML breaks.
     */
    public static function str_break($text)
    {
        return nl2br( $text );
    }

    /**
     * Returns the input string with capitalized initial letters.
     */
    public static function upword($text)
    {
        return ucwords($text);
    }

    /**
     * Returns an indented version of the input string.
     */
    public static function indent($text, $count, $type = "space", $filler = false)
    {
        if ( $filler === false )
        {
            if ( $type == 'space' )
            {
                $filler = ' ';
            }
            else if ( $type == 'tab' )
            {
                $filler = "\t";
            }
            else if ( $type != 'custom' )
            {
                $filler = ' ';
            }
        }

        $fillText = str_repeat( $filler, $count );
        return $fillText . str_replace( "\n", "\n" . $fillText, $text );
    }

    /**
     * Returns a shortened version of the input string.
     */
    public static function shorten($text, $length = 80, $seq = "...", $trimType = "right")
    {
        if ( $trimType === "middle" )
        {
            $appendedStrLen = strlen( $seq );
            if ( $length > $appendedStrLen && ( strlen( $text ) > $length ) )
            {
                $operatorValueLength = strlen( $text );
                $chop = $length - $appendedStrLen;
                $middlePos = (int)($chop / 2);
                $leftPartLength = $middlePos;
                $rightPartLength = $chop - $middlePos;
                $result = trim( substr( $text, 0, $leftPartLength ) . $seq . substr( $text, $operatorValueLength - $rightPartLength, $rightPartLength ) );
            }
            else
            {
                $result = $text;
            }
        }
        else // default: trim_type === "right"
        {
            $maxLength = $length - strlen( $seq );
            if ( ( strlen( $text ) > $length ) && strlen( $text ) > $maxLength )
            {
                $result = trim( substr( $text, 0, $maxLength) ) . $seq;
            }
            else
            {
                $result = $text;
            }
        }

        return $result;
    }

    /**
     * Returns the CRC32 polynomial of the input string.
     */
    public static function crc32($digestData)
    {
        include_once( 'lib/ezutils/classes/ezsys.php' );
        return eZSys::ezcrc32( $digestData );
    }

    /**
     * Returns the MD5 hash of the input string.
     */
    public static function md5($digestData)
    {
        return md5( $digestData );
    }

    /**
     * Returns the SHA1 hash of the input string.
     */
    public static function sha1($digestData)
    {
        return sha1( $digestData );
    }

    /**
     * Returns a ROT13 transformation of the input string.
     */
    public static function rot13($text)
    {
        return str_rot13( $text );
    }

    /**
     * Marks a string for translation.
     */
    public static function i18n($input, $context = null, $comment = null, $parameters = null)
    {
        return ezi18n($context, $input, $comment, $parameters);
    }
}

?>
