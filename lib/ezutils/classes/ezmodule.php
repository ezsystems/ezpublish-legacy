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
    function eZModule( $path, $file, $moduleName )
    {
        $this->initialize( $path, $file, $moduleName );
    }

    function initialize( $path, $file, $moduleName )
    {
        unset( $FunctionArray );
        unset( $Module );
        if ( file_exists( $file ) )
        {
            include( $file );
            $this->Functions =& $ViewList;
            $this->FunctionList =& $FunctionList;
            $this->Module =& $Module;
            $this->Name = $moduleName;
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
                $func["uri"] = "/$moduleName/$key";
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
            $this->Name = $moduleName;
            $this->Path = $path;
            $this->Title = "";
        }
        $this->ExitStatus = EZ_MODULE_STATUS_IDLE;
        $this->ErrorCode = 0;
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

    /*!
     \return the error code if the function failed to run or \a 0 if no error code.
     \sa setErrorCode
    */
    function errorCode()
    {
        return $this->ErrorCode;
    }

    /*!
     Sets the current error code.
     \note You need to set the exit status to EZ_MODULE_STATUS_FAILED for the error code to be used.
     \sa setExitStatus, errorCode
    */
    function setErrorCode( $errorCode )
    {
        $this->ErrorCode = $errorCode;
    }

    /*!
     \return the name of the module which will be run on errors.
             The default name is 'errror'.
     \sa handleError
    */
    function errorModule()
    {
        $globalErrorModule =& $GLOBALS['eZModuleGlobalErrorModule'];
        if ( !isset( $globalErrorModule ) )
            $globalErrorModule = 'error';
        return $globalErrorModule;
    }

    /*!
     Sets the name of the module which will be run on errors.
     \sa handleError
    */
    function setErrorModule( $moduleName )
    {
        $globalErrorModule =& $GLOBALS['eZModuleGlobalErrorModule'];
        $globalErrorModule = $moduleName;
    }

    /*!
     Tries to run the error module with the error code \a $errorCode.
     Sets the state of the module object to \c failed and sets the error code.
    */
    function handleError( $errorCode )
    {
        $this->setExitStatus( EZ_MODULE_STATUS_FAILED );
        $this->setErrorCode( $errorCode );
        $errorModule =& eZModule::errorModule();
        $module =& eZModule::findModule( $errorModule, $this );
        if ( $module === null )
            return false;
        return $module->run( $errorCode, array() );
    }

    /*!
     Makes sure that the module is redirected to the URI \a $uri when the function exits.
     \sa setRedirectURI, setExitStatus
    */
    function redirectTo( $uri )
    {
        $this->RedirectURI = $uri;
        $this->setExitStatus( EZ_MODULE_STATUS_REDIRECT );
    }

    /*!
     \return the URI which will be redirected to when the function exits.
    */
    function redirectURI()
    {
        return $this->RedirectURI;
    }

    /*!
     Sets the URI which will be redirected to when the function exits.
    */
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

    /*!
     Tries to run the function \a $functionName in the current module.
     \param parameters An indexed list of parameters, these will be mapped
                       onto real parameter names using the defined parameter
                       names in the module/function definition.
                       If this variable is shorter than the required parameters
                       they will be set to \a null.
     \param overrideParameters An associative array of parameters which
                               will override any parameters found using the
                               defined parameters.
     \return null if function could not be run or no return value was found.
    */
    function &run( $functionName, $parameters, $overrideParameters = false )
    {
        if ( count( $this->Functions ) > 0 and
             !isset( $this->Functions[$functionName] ) )
        {
            eZDebug::writeError( "Undefined function: " . $this->Module["name"] . "::$functionName ",
                                 "eZModule" );
            return null;
        }
        if ( $this->singleFunction() )
            $function =& $this->Module["function"];
        else
            $function =& $this->Functions[$functionName];
        $functionParameterDefinitions =& $function["params"];
        $params = array();
        $i = 0;
        foreach ( $functionParameterDefinitions as $param )
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

        if ( is_array( $overrideParameters ) )
        {
            foreach ( $overrideParameters as $param => $value )
            {
                $params[$param] = $value;
            }
        }
        $params["Module"] =& $this;
        $params["ModuleName"] = $this->Name;
        $params["FunctionName"] = $functionName;
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

    /*!
     \static
     \return the global path list which is used for finding modules. Returns \c null if no
             list is available.
     \sa setGlobalPathList, addGlobalPathList
    */
    function globalPathList()
    {
        if ( !isset( $GLOBALS['eZModuleGlobalPathList'] ) )
            return null;
        return $GLOBALS['eZModuleGlobalPathList'];
    }

    /*!
     \static
     Sets the global path list which is used for finding modules.
     \param $pathList Is either an array with path strings or a single path string
     \sa addGlobalPathList
    */
    function setGlobalPathList( $pathList )
    {
        $globalPathList =& $GLOBALS['eZModuleGlobalPathList'];
        if ( !is_array( $pathList ) )
            $pathList = array( $pathList );
        $globalPathList = $pathList;
    }

    /*!
     \static
     Adds the pathlist entries \a $pathList to the global path list which is used for finding modules.
     \param $pathList Is either an array with path strings or a single path string
     \sa setGlobalPathList
    */
    function addGlobalPathList( $pathList )
    {
        $globalPathList =& $GLOBALS['eZModuleGlobalPathList'];
        if ( !is_array( $globalPathList ) )
            $globalPathList = array();
        if ( !is_array( $pathList ) )
            $pathList = array( $pathList );
        $globalPathList = array_merge( $globalPathList, $pathList );
    }

    /*!
     \static
     Tries to locate the module named \a $moduleName and returns an eZModule object
     for it. Returns \c null if no module can be found.

     It uses the globalPathList() to search for modules, use \a $pathList to add
     additional path.
     \param $moduleName The name of the module to find
     \param $pathList Is either an array with path strings or a single path string
    */
    function &exists( $moduleName, $pathList = null )
    {
        $module = null;
        return eZModule::findModule( $moduleName, $module, $pathList );
    }

    function &findModule( $moduleName, &$module, $pathList = null )
    {
        if ( $pathList === null )
            $pathList = array();
        else if ( !is_array( $pathList ) )
            $pathList = array( $pathList );
        $searchPathList = eZModule::globalPathList();
        if ( $searchPathList === null )
            $searchPathList = array();
        $searchPathList = array_merge( $searchPathList, $pathList );
        foreach ( $searchPathList as $path )
        {
            $file = "$path/$moduleName/module.php";
            if ( file_exists( $file ) )
            {
                if ( $module === null )
                    $module = new eZModule( $path, $file, $moduleName );
                else
                    $module->initialize( $path, $file, $moduleName );
                return $module;
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
    var $ErrorCode;
    var $RedirectURI;
    var $Title;
}

?>
