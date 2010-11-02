<?php

class TranslationExtension implements ezcTemplateCustomFunction, ezcTemplateCustomBlock
{
    private static $manager = null;
    public static $lang = "nb_NO";

    public static function getCustomFunctionDefinition($name)
    {
        switch ($name)
        {
            case "_":
                $def = new ezcTemplateCustomFunctionDefinition();
                $def->class = __CLASS__;
                $def->method = "translate";
                return $def;

            case "lang":
                $def = new ezcTemplateCustomFunctionDefinition();
                $def->class = __CLASS__;
                $def->method = "getLanguage";
                return $def;
        }
    }

    public static function getCustomBlockDefinition($name)
    {
        switch ($name)
        {
            case "tr":
                $def = new ezcTemplateCustomBlockDefinition();
                $def->class = __CLASS__;
                $def->method = "translateCB";
                $def->startExpressionName = "string";
                $def->requiredParameters = array("string");

                $def->optionalParameters = array("context", "parameters");
                $def->hasCloseTag = false;
                return $def;

            case "tr2":
                $def = new ezcTemplateCustomBlockDefinition();
                $def->class = __CLASS__;
                $def->method = "translateCB";
                $def->startExpressionName = "string";
                $def->requiredParameters = array("string");

                $def->optionalParameters = array("context", "parameters");
                $def->hasCloseTag = false;
                $def->isStatic = true;
                return $def;

            case "t":
                $def = new ezcTemplateCustomBlockDefinition();
                $def->class = __CLASS__;
                $def->method = "translateCloseTagCB";
                $def->optionalParameters = array("context", "parameters");
                $def->hasCloseTag = true;
                return $def;
        }
    }

    public static function getLanguage()
    {
        return self::$lang;
    }

    public static function translate( $str, $context = 'local', $parameters = array() )
    {
        if( self::$manager === null )
        {
            $backend = new ezcTranslationTsBackend( dirname( __FILE__ ). '/../translations' );
            $backend->setOptions( array( 'format' => 'translation-[LOCALE].xml' ) );

            self::$manager = new ezcTranslationManager( $backend );
        }

        $lang = self::$manager->getContext( self::$lang, $context);
        return $lang->getTranslation( $str, $parameters );
    }

    public static function translateCB( $parameters = array() )
    {
        return self::translate( $parameters["string"] );
    }

    public static function translateCloseTagCB( $parameters = array(), $string = "")
    {
        return self::translate( $string );
    }



}

?>
