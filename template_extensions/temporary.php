<?php

class TemporaryFunctions implements ezcTemplateCustomFunction
{
    public static function getCustomFunctionDefinition($name)
    {
        return ezpTemplateFunctions::mapFunctionArgs( __CLASS__, $name );
    }



    public static function conversion_error($msg)
    {
        ezpTemplateFunctions::runtimeError( $msg );
        return $msg;
    }






    /* JB-TODO: Propose to remove or put in compatability package, if needed a simpler system can be made.
                Parameters and behaviour needs some rethinking. */
    // image.php
    public static function texttoimage($value, $class, $family = null, $pointSize = null, $angle = null, $bgColor = null, $textColor = null, $x = null, $y = null, $w = null, $h = null, $useCache = null, $storeImage = true)
    {
        ezpTemplateFunctions::runtimeError( "texttoimage: Function is not yet enabled" );
    }

    /* JB-TODO: Propose to remove or put in compatability package, if needed a simpler system can be made. */
    // image.php
    public static function imagefile($filename, $options = false)
    {
        ezpTemplateFunctions::runtimeError( "imagefile: Function is not yet enabled" );
    }

    /* JB-TODO: Propose to remove or put in compatability package, if needed a simpler system can be made. */
    // image.php
    public static function image()
    {
        ezpTemplateFunctions::runtimeError( "image: Function is not yet enabled" );
    }




    // ezwebin: extension/ezwebin/autoloads/eztagcloud.php
    public static function eztagcloud($var)
    {
        ezpTemplateFunctions::runtimeError( "eztagcloud: Function is not yet enabled" );
    }

    // ezwebin: extension/ezwebin/autoloads/ezkeywordlist.php
    public static function ezkeywordlist($var)
    {
        ezpTemplateFunctions::runtimeError( "ezkeywordlist: Function is not yet enabled" );
    }
 
    // ezwebin: extension/ezwebin/autoloads/ezarchive.php
    public static function ezarchive($var)
    {
        ezpTemplateFunctions::runtimeError( "ezarchive: Function is not yet enabled" );
    }

    // ezwebin: extension/ezwebin/autoloads/ezcreateclasslistgroups.php
    public static function ezcreateclasslistgroups($var)
    {
        ezpTemplateFunctions::runtimeError( "ezcreateclasslistgroups: Function is not yet enabled" );
    }



    // The following functions should be handled by the conversion system and
    // should no longer be needed.

    /*

    public static function cond()
    {
        $arguments = func_get_args();
        $num = sizeof( $arguments );
        $lastArgument = ($num % 2 == 1 ? $arguments[$num - 1] : false );

        for ( $i = 0; $i < $num -1; $i += 2)
        {
            if( $arguments[$i] )
            {
                return $arguments[$i + 1];
            }
        }

        return $lastArgument;
    }

    public static function first_set()
    {
        $arguments = func_get_args();
        foreach( $arguments as $arg )
        {
            if( isset( $arg ) && $arg )
            {
                return $arg;
            }
        }

        return false;

    }
    */
}
