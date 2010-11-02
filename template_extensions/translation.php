<?php

class Translation implements ezcTemplateCustomFunction
{
    /**
     * Left-to-right text direction (most languages).
     */
    const DIR_LTR = 'ltr';

    /**
     * Right-to-left text direction (for example Arabic).
     */
    const DIR_RTL = 'rtl';

    private static $manager = null;
    public static $context = "nb_NO";

    public static function getCustomFunctionDefinition($name)
    {
        switch ($name)
        {
            case "translate":
                $def = new ezcTemplateCustomFunctionDefinition();
                $def->class = __CLASS__;
                $def->method = "translate";
                $def->parameters = array("string", "[context]", "[parameters]");
                return $def;

            case "text_direction":
                $def = new ezcTemplateCustomFunctionDefinition();
                $def->class = __CLASS__;
                $def->method = "text_direction";
                $def->parameters = array( "lang" );
                return $def;
        }
    }
   
    public static function translate( $str, $context = '', $parameters = array() )
    {
        if( self::$manager === null )
        {
            $backend = new ezcTranslationTsBackend( dirname( __FILE__ ). '/../translations' );
            $backend->setOptions( array( 'format' => 'translation-[LOCALE].xml' ) );

            self::$manager = new ezcTranslationManager( $backend );
        }

        $lang = self::$manager->getContext( self::$context, $context);
        return $lang->getTranslation( $str, $parameters );
    }

    public static function text_direction( $lang )
    {
        if ( in_array( $lang, array( 'ar_AR' ) ) )
        {
            return self::DIR_RTL;
        }
        return self::DIR_LTR;
    }
}
?>
