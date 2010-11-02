<?php

class ezpTemplateSystemFunctions implements ezcTemplateCustomFunction
{
    public static function getCustomFunctionDefinition($name)
    {
        return ezpTemplateFunctions::mapFunction( __CLASS__, $name );
    }

    /**
     * Returns TRUE if the specified module or view is enabled.
     */
    public static function ezmodule($uri = false)
    {
        $uri = new eZURI( $uri );
        $check = accessAllowed( $uri );
        return $check['result'];
    }

    /**
     * Returns misc values such as wwwdir, sitedir, etc.
     */
    public static function ezsys($name)
    {
        $sys = eZSys::instance();
        return $sys->$name;
    }

    /**
     * Returns GET, POST and session variables.
     */
    public static function ezhttp($name, $type = 'get', $checkExistence = false)
    {
        $types = array( 'post', 'get', 'session' );
        if ( !in_array( $type, $types ) )
            ezpTemplateFunctions::runtimeError( "ezhttp: Invalid type '$type'" );
        include_once( 'lib/ezutils/classes/ezhttptool.php' );
        $http = eZHTTPTool::instance();
        $func = 'has' . ucfirst( $type ) . 'Variable';
        if ( $http->$func( $name ) )
            return $checkExistence ? true : $http->{$type . 'Variable'};
        else if ( !$checkExistence )
            ezpTemplateFunctions::runtimeError( "ezhttp: HTTP " . strtoupper( $type ) . " variable '$name' does not exist" );
        else
            return false;
    }

    /**
     * Returns TRUE if the specified HTTP variable is set.
     */
    public static function ezhttp_hasvariable($name, $type = 'get')
    {
        return self::ezhttp($name, $type, true);
    }

    /**
     * Provides access to the fetch functions of a module.
     */
    public static function fetch($moduleName, $functionName, $parameters = array())
    {
        include_once( 'lib/ezutils/classes/ezfunctionhandler.php' );
        return eZFunctionHandler::execute( $moduleName, $functionName, $parameters );
    }

    public static function fetch_alias($functionName, $functionParameters = array())
    {
        include_once( 'lib/ezutils/classes/ezfunctionhandler.php' );
        return eZFunctionHandler::executeAlias( $functionName, $functionParameters );
    }

    /**
     * Returns TRUE if the specified configuration directive is set.
     */
    public static function ezini_hasvariable($iniGroup, $iniVariable, $iniName = false, $iniPath = false, $dynamic = false)
    {
        return self::ezini($iniGroup, $iniVariable, $iniName, $iniPath, $dynamic, true);
    }

    /**
     * Provides read access to settings in the configuration files.
     * @TODO $dynamic parameter is not handled yet
     */
    public static function ezini($iniGroup, $iniVariable, $iniName = false, $iniPath = false, $dynamic = false, $checkExistence = false)
    {
//        $checkExistence = false;
        if ( $iniPath !== false )
            $ini = eZINI::instance( $iniName, $iniPath, null, null, null, true );
        elseif ( $iniName !== false )
            $ini = eZINI::instance( $iniName );
        else
            $ini = eZINI::instance();

        if ( $ini->hasVariable( $iniGroup, $iniVariable ) )
        {
            $operatorValue = !$checkExistence ? $ini->variable( $iniGroup, $iniVariable ) : true;
        }
        else
        {
            if ( $checkExistence )
            {
                $operatorValue = false;
                return $operatorValue;
            }
            if ( $iniPath !== false )
            {
                // Return empty string instead of displaying error when using 'path' parameter
                // and DirectAccess mode for ezini.
                $operatorValue = '';
            }
            else
            {
                if ( $iniName === false )
                {
                    $iniName = 'site.ini';
                }
                print "!!!No such variable '$iniVariable' in group '$iniGroup' for $iniName";
            }
        }
        return $operatorValue;
    }

    /**
     * Provides access to a user's preference values.
     */
    public static function ezpreference($preference)
    {
        return eZPreferences::value( $preference );
    }

    /**
     * Extracts parameters from the module that was run.
     */
    public static function module_params()
    {
        return $GLOBALS['eZRequestedModuleParams'];
    }

    /**
     * Returns specified information from package object.
     */
    public static function ezpackage( $package, $class, $data = false )
    {
        $info = new ezpPackageInfo( $package );
        return $info->query( $class, $data );
    }
}

?>
