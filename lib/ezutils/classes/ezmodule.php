<?php
//
// Definition of eZModule class
//
// Created on: <17-Apr-2002 11:11:39 amos>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*!
  \class eZModule ezmodule.php
  \ingroup eZUtils
  \brief Allows execution of modules and functions

*/

include_once( "lib/ezutils/classes/ezdebug.php" );
include_once( "lib/ezutils/classes/ezmodulefeatures.php" );

define( "EZ_MODULE_STATUS_IDLE", 0 );
define( "EZ_MODULE_STATUS_OK", 1 );
define( "EZ_MODULE_STATUS_FAILED", 2 );
define( "EZ_MODULE_STATUS_REDIRECT", 3 );
define( "EZ_MODULE_STATUS_SHOW_LOGIN_PAGE", 4 );

class eZModule
{
    function eZModule( $path, $file, $module_name )
    {
        unset( $FunctionArray );
        unset( $Module );
        if ( file_exists( $file ) )
        {
            include( $file );
            $this->Functions =& $ViewList;
            $this->FunctionList =& $FunctionList;
            $this->Module =& $Module;
            $this->Name = $module_name;
            $this->Path = $path;
            $this->Title = "";
            $this->Features = array();
            $this->FeatureObj = null;
            if ( isset( $FeatureArray ) )
                $this->Features =& $FeatureArray;
            reset( $this->Functions );
            while( ( $key = key( $this->Functions ) ) !== null )
            {
                $func =& $this->Functions[$key];
                $func["uri"] = "/$module_name/$key";
                next( $this->Functions );
            }
        }
        else
        {
            $this->Functions = array();
            $this->Features = array();
            $this->Module = array( "name" => "null",
                                   "variable_params" => false,
                                   "function" => array() );
            $this->Name = $module_name;
            $this->Path = $path;
            $this->Title = "";
        }
        $this->ExitStatus = EZ_MODULE_STATUS_IDLE;
    }

    function uri()
    {
        return "/" . $this->Name;
    }

    function functionURI( $function )
    {
        if ( $this->singleFunction() )
            return $this->uri();
        if ( isset( $this->Functions[$function] ) )
            return $this->Functions[$function]["uri"];
        else
            return null;
    }

    function title()
    {
        return $this->Title;
    }

    function setTitle( $title )
    {
        $this->Title = $title;
    }

    function singleFunction()
    {
        return count( $this->Functions ) == 0;
    }

    function exitStatus()
    {
        return $this->ExitStatus;
    }

    function setExitStatus( $stat )
    {
        $this->ExitStatus = $stat;
    }

    function redirectTo( $uri )
    {
        $this->RedirectURI = $uri;
        $this->setExitStatus( EZ_MODULE_STATUS_REDIRECT );
    }

    function redirectURI()
    {
        return $this->RedirectURI;
    }

    function setRedirectURI( $uri )
    {
        $this->RedirectURI = $uri;
    }

    function attributes()
    {
        return array( "uri", "functions", "name", "path", "info", "features", "aviable_functions" );
    }

    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function &attribute( $attr )
    {
        switch( $attr )
        {
            case "uri":
                return $this->uri();
            case "functions":
                return $this->Functions;
            case "views":
                return $this->Functions;
            case "name":
                return $this->Name;
            case "path":
                return $this->Path;
            case "info":
                return $this->Module;
            case "aviable_functions":
                return $this->FunctionList;
            case "features":
            {
                $features =& $this->FeatureObj;
                if ( get_class( $features ) != "ezmodulefeatures" )
                {
                    include_once( "lib/ezutils/classes/ezmodulefeatures.php" );
                    $features = new eZModuleFeatures( $this, $this->Features );
                }
                return $features;
            }
        }
        return null;
    }

    function &run( $function_name, $parameters, $override_parameters = false )
    {
        if ( count( $this->Functions ) > 0 and
             !isset( $this->Functions[$function_name] ) )
        {
            eZDebug::writeWarning( "Undefined function: " . $this->Module["name"] . "::$function_name ",
                                   "eZModule" );
            return null;
        }
        if ( $this->singleFunction() )
            $function =& $this->Module["function"];
        else
            $function =& $this->Functions[$function_name];
        $function_params =& $function["params"];
        $params = array();
        $i = 0;
        foreach ( $function_params as $param )
        {
            if ( isset( $parameters[$i] ) )
                $params[$param] = $parameters[$i];
            else
                $params[$param] = null;
            ++$i;
        }
        if ( array_key_exists( 'Limitation', $parameters  ) )
        {
            $params['Limitation'] =& $parameters[ 'Limitation' ];
        }

        // check for unordered parameters and initialize variables if they exist
        if ( isset( $function["unordered_params"] ) )
        {
            $unorderedParams =& $function["unordered_params"];

            reset( $unorderedParams );
            while ( list( $urlParamName, $variableParamName ) = each( $unorderedParams ) )
            {
                if ( in_array( $urlParamName, $parameters ) )
                {
                    $pos = array_search( $urlParamName, $parameters );

                    $params[$variableParamName] = $parameters[$pos+1];
                }
                else
                {
                    $params[$variableParamName] = false;
                }
            }
        }

        if ( is_array( $override_parameters ) )
        {
            foreach ( $override_parameters as $param => $value )
            {
                $params[$param] = $value;
            }
        }
        $params["Module"] =& $this;
        $params["ModuleName"] = $this->Name;
        $params["FunctionName"] = $function_name;
        $params["Parameters"] =& $parameters;
        $params_as_var = isset( $this->Module["variable_params"] ) ? $this->Module["variable_params"] : false;
        include_once( "lib/ezutils/classes/ezprocess.php" );
        $this->ExitStatus = EZ_MODULE_STATUS_OK;
//        eZDebug::writeNotice( $params, 'module parameters1' );

        $Return =& eZProcess::run( $this->Path . "/" . $this->Name . "/" . $function["script"],
                                   $params,
                                   $params_as_var );

        return $Return;
    }

    function &exists( $path_list, $module )
    {
        foreach ( $path_list as $path )
        {
            $file = "$path/$module/module.php";
            if ( file_exists( $file ) )
            {
                $mod = new eZModule( $path, $file, $module );
                return $mod;
            }
        }
        return null;
    }

    /// \privatesection
    var $Functions;
    var $Features;
    var $FeatureObj;
    var $Module;
    var $Name;
    var $Path;
    var $ExitStatus;
    var $RedirectURI;
    var $Title;
}

?>
