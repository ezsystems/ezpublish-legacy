<?php

class ezpTemplateGlobalFunctions implements ezcTemplateCustomFunction, ezcTemplateCustomBlock
{
    public static function getCustomFunctionDefinition($name)
    {
        if ( $name == 'globals' )
        {
            $def = new ezcTemplateCustomFunctionDefinition();
            $def->class = __CLASS__;
            $def->method = 'get_globals';
            $def->variableArgumentList = false;
            return $def;
        }
    }

    public static function getCustomBlockDefinition($name)
    {
        if( $name == "set_global" )
        {
            $def = new ezcTemplateCustomBlockDefinition();
            $def->class = __CLASS__;
            $def->method = "set_global";
            $def->hasCloseTag = false;
            $def->startExpressionName = 'section';
            $def->requiredParameters = array("section");
            $def->optionalParameters = array();
            $def->excessParameters = true;

            return $def;
        }
    }

    /**
     * Returns the global ezpGlobals object.
     *
     * <code>
     * {get_globals()}
     * </code>
     */
    public static function get_globals()
    {
        return ezpGlobals::instance();
    }

    /**
     * Sets global variables in a specific section.
     *
     * {set_global <section> <var1 = value1> [var2 = value...]}
     *
     * <code>
     * {set_global 'notification' subject = "Hi"}
     */
    public static function set_global($parameters)
    {
        $sectionname = $parameters['section'];
        $glob = ezpGlobals::instance();
        if ( !isset( $glob->$sectionname ) )
            throw new ezpGlobalsError( "No such global section named '$sectionname'" );

        $section = $glob->$sectionname;

        unset( $parameters['section'] );
        foreach ( $parameters as $key => $value )
        {
            $section->$key = $value;
        }
    }
}
