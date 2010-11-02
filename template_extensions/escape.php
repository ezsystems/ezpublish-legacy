<?php

class TemplateEscapeBlock implements ezcTemplateCustomBlock, ezcTemplateCustomFunction
{
    public static function getCustomBlockDefinition($name)
    {
        if( method_exists(__CLASS__, "block_".$name) )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "block_" . $name;
            $def->hasCloseTag = false;
            $def->startExpressionName = "expr";
            $def->requiredParameters = array("expr");
            $def->excessParameters = false;
            return $def;
        }
    }

    public static function getCustomFunctionDefinition($name)
    {
        return ezpTemplateFunctions::mapFunction( __CLASS__, $name );
    }

    /**
     * Returns an javascript-safe version of the input block.
     */
    public static function block_escape_javascript($param)
    {
        return self::escape_javascript($param["expr"]);
    }

    /**
     * Returns an javascript-safe version of the input string.
     */
    public static function escape_javascript($expr)
    {
        return str_replace( array( "\\", "\"", "'"), array( "\\\\", "\\042", "\\047" ), $expr);
    }

    /**
     * Returns an email-safe version of the input block.
     */
    public static function block_escape_email($param)
    {
        return self::escape_email($param["expr"]);
    }

    /**
     * Returns an email-safe version of the input string.
     */
    public static function escape_email($expr)
    {
        $ini = eZINI::instance('template.ini');
        $dotText = $ini->variable( 'WashSettings', 'EmailDotText' );
        $atText = $ini->variable( 'WashSettings', 'EmailAtText' );
        return str_replace( array( '.',
                                   '@' ),
                            array( $dotText,
                                   $atText ),
                            $expr );

        #throw new ezcTemplateRuntimeException( "escape_email is not implemented" );
    }

    /**
     * Returns an PDF-safe version of the input block.
     */
    public static function block_escape_pdf($param)
    {
        return self::escape_pdf($param["expr"]);
    }

    /**
     * Returns an PDF-safe version of the input string.
     */
    public static function escape_pdf($expr)
    {
        $out = str_replace( array( ' ', // use default callback functions in ezpdf library
                             "\r\n",
                             "\t" ),
                      array( '<C:callSpace>',
                             '<C:callNewLine>',
                             '<C:callTab>' ),
                      $expr );

        $out = str_replace( "\n", '<C:callNewLine>', $out );
        return $out;
    }

    /**
     * Returns an HTML-safe version of the input string.
     */
    public static function escape($expr)
    {
        return htmlspecialchars($expr);
    }
}



?>
