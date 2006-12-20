<?php
//
// Definition of eZModuleFunctionInfo class
//
// Created on: <06-Oct-2002 16:27:36 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file ezmodulefunctioninfo.php
*/

/*!
  \class eZModuleFunctionInfo ezmodulefunctioninfo.php
  \brief The class eZModuleFunctionInfo does

*/

include_once( 'lib/ezutils/classes/ezmodule.php' );
include_once( 'lib/ezutils/classes/ezdebug.php' );

define( 'EZ_MODULE_FUNCTION_ERROR_NO_CLASS', 5 );
define( 'EZ_MODULE_FUNCTION_ERROR_NO_CLASS_METHOD', 6 );
define( 'EZ_MODULE_FUNCTION_ERROR_CLASS_INSTANTIATE_FAILED', 7 );
define( 'EZ_MODULE_FUNCTION_ERROR_MISSING_PARAMETER', 8 );

class eZModuleFunctionInfo
{
    /*!
     Constructor
    */
    function eZModuleFunctionInfo( $moduleName )
    {
        $this->ModuleName = $moduleName;
        $this->IsValid = false;
        $this->FunctionList = array();
        $this->UseOldCall = false;
    }

    function isValid()
    {
        return $this->IsValid;
    }

    function loadDefinition()
    {
        $definitionFile = null;
        $pathList = eZModule::globalPathList();
        if ( $pathList )
        {
            foreach ( $pathList as $path )
            {
                $definitionFile = $path . '/' . $this->ModuleName . '/function_definition.php';
                if ( file_exists( $definitionFile ) )
                    break;
                $definitionFile = null;
            }
        }
        if ( $definitionFile === null )
        {
            eZDebug::writeError( 'Missing function definition file for module: ' . $this->ModuleName,
                                 'eZModuleFunctionInfo::loadDefinition' );
            return false;
        }
        unset( $FunctionList );
        include( $definitionFile );
        if ( !isset( $FunctionList ) )
        {
            eZDebug::writeError( 'Missing function definition list for module: ' . $this->ModuleName,
                                 'eZModuleFunctionInfo::loadDefinition' );
            return false;
        }
        $this->FunctionList = $FunctionList;
        $this->IsValid = true;
        return true;
    }

    /*!
      Check if a parameter for a function is an array

      \param function name
      \param parameter name

      \return true if parameter is supposed to be array
     */
    function isParameterArray( $functionName, $parameterName )
    {
        if ( isset( $this->FunctionList[$functionName]['parameters'] ) )
        {
            $parameterList =& $this->FunctionList[$functionName]['parameters'];
            foreach ( array_keys( $parameterList ) as $key )
            {
                if ( $parameterList[$key]['name'] == $parameterName )
                {
                    if ( $parameterList[$key]['type'] == 'array' )
                        return true;
                    return false;
                }
            }
        }
        return false;
    }

    /*!
     Pre execute, used by template compilation to check as much as possible before runtime.

     \param function name

     \return function definition, false if fails.
    */
    function &preExecute( $functionName )
    {
        /* Code copied from this->execute() */
        $Return = false;
        $moduleName = $this->ModuleName;
        if ( !isset( $this->FunctionList[$functionName] ) )
        {
            eZDebug::writeError( "No such function '$functionName' in module '$moduleName'",
                                 'eZModuleFunctionInfo::execute' );
            return $Return;
        }
        $functionDefinition =& $this->FunctionList[$functionName];
        if ( !isset( $functionName['call_method'] ) )
        {
            eZDebug::writeError( "No call method defined for function '$functionName' in module '$moduleName'",
                                 'eZModuleFunctionInfo::execute' );
            return $Return;
        }
        if ( !isset( $functionName['parameters'] ) )
        {
            eZDebug::writeError( "No parameters defined for function '$functionName' in module '$moduleName'",
                                 'eZModuleFunctionInfo::execute' );
            return $Return;
        }
        $callMethod =& $functionDefinition['call_method'];
        if ( isset( $callMethod['include_file'] ) and
             isset( $callMethod['class'] ) and
             isset( $callMethod['method'] ) )
        {
            $extension = false;
            if ( isset( $callMethod['extension'] ) )
                $extension = $callMethod['extension'];

            if ( $extension )
            {
                include_once( 'lib/ezutils/classes/ezextension.php' );
                $extensionDir = eZExtension::baseDirectory();
                $callMethod['include_file'] = $extensionDir . '/' . $extension . '/modules/' . $callMethod['include_file'];
            }
            include_once( $callMethod['include_file'] );
            if ( !class_exists( $callMethod['class'] ) )
            {
                return $Return;
            }
            $classObject =& $this->objectForClass( $callMethod['class'] );
            if ( $classObject === null )
            {
                return $Return;
            }
            if ( !method_exists( $classObject, $callMethod['method'] ) )
            {
                return $Return;
            }

            return $functionDefinition;
        }
        return $Return;
    }

    function execute( $functionName, $functionParameters )
    {
        $moduleName = $this->ModuleName;
        if ( !isset( $this->FunctionList[$functionName] ) )
        {
            eZDebug::writeError( "No such function '$functionName' in module '$moduleName'",
                                 'eZModuleFunctionInfo::execute' );
            return null;
        }
        $functionDefinition =& $this->FunctionList[$functionName];
        if ( !isset( $functionName['call_method'] ) )
        {
            eZDebug::writeError( "No call method defined for function '$functionName' in module '$moduleName'",
                                 'eZModuleFunctionInfo::execute' );
            return null;
        }
        if ( !isset( $functionName['parameters'] ) )
        {
            eZDebug::writeError( "No parameters defined for function '$functionName' in module '$moduleName'",
                                 'eZModuleFunctionInfo::execute' );
            return null;
        }
        $callMethod =& $functionDefinition['call_method'];
        if ( isset( $callMethod['include_file'] ) and
             isset( $callMethod['class'] ) and
             isset( $callMethod['method'] ) )
        {
            $extension = false;
            if ( isset( $callMethod['extension'] ) )
                $extension = $callMethod['extension'];
            $resultArray = $this->executeClassMethod( $extension, $callMethod['include_file'], $callMethod['class'], $callMethod['method'],
                                                      $functionDefinition['parameters'], $functionParameters );
        }
        else
        {
            eZDebug::writeError( "No valid call methods found for function '$functionName' in module '$moduleName'",
                                 'eZModuleFunctionInfo::execute' );
            return null;
        }
        if ( !is_array( $resultArray ) )
        {
            eZDebug::writeError( "Function '$functionName' in module '$moduleName' did not return a result array",
                                 'eZFunctionHandler::execute' );
            return null;
        }
        if ( isset( $resultArray['internal_error'] ) )
        {
            switch ( $resultArray['internal_error'] )
            {
                case EZ_MODULE_FUNCTION_ERROR_NO_CLASS:
                {
                    $className = $resultArray['internal_error_class_name'];
                    eZDebug::writeError( "No class '$className' available for function '$functionName' in module '$moduleName'",
                                         'eZModuleFunctionInfo::execute' );
                    return null;
                } break;
                case EZ_MODULE_FUNCTION_ERROR_NO_CLASS_METHOD:
                {
                    $className = $resultArray['internal_error_class_name'];
                    $classMethodName = $resultArray['internal_error_class_method_name'];
                    eZDebug::writeError( "No method '$classMethodName' in class '$className' available for function '$functionName' in module '$moduleName'",
                                         'eZModuleFunctionInfo::execute' );
                    return null;
                } break;
                case EZ_MODULE_FUNCTION_ERROR_CLASS_INSTANTIATE_FAILED:
                {
                    $className = $resultArray['internal_error_class_name'];
                    eZDebug::writeError( "Failed instantiating class '$className' which is needed for function '$functionName' in module '$moduleName'",
                                         'eZModuleFunctionInfo::execute' );
                    return null;
                } break;
                case EZ_MODULE_FUNCTION_ERROR_MISSING_PARAMETER:
                {
                    $parameterName = $resultArray['internal_error_parameter_name'];
                    eZDebug::writeError( "Missing parameter '$parameterName' for function '$functionName' in module '$moduleName'",
                                         "eZModuleFunctionInfo::execute $moduleName::$functionName" );
                    return null;
                } break;
                default:
                {
                    $internalError = $resultArray['internal_error'];
                    eZDebug::writeError( "Unknown internal error '$internalError' for function '$functionName' in module '$moduleName'",
                                         'eZModuleFunctionInfo::execute' );
                    return null;
                } break;
            }
            return null;
        }
        else if ( isset( $resultArray['error'] ) )
        {
        }
        else if ( isset( $resultArray['result'] ) )
        {
            return $resultArray['result'];
        }
        else
        {
            eZDebug::writeError( "Function '$functionName' in module '$moduleName' did not return a result value",
                                 'eZFunctionHandler::execute' );
        }
        return null;
    }

    function &objectForClass( $className )
    {
        $classObjectList =& $GLOBALS['eZModuleFunctionClassObjectList'];
        if ( !isset( $classObjectList ) )
            $classObjectList = array();
        if ( isset( $classObjectList[$className] ) )
            return $classObjectList[$className];
        $classObject = new $className();
        $classObjectList[$className] =& $classObject;
        return $classObject;
    }

    function executeClassMethod( $extension, $includeFile, $className, $methodName,
                                 $functionParameterDefinitions, $functionParameters )
    {
        if ( $extension )
        {
            include_once( 'lib/ezutils/classes/ezextension.php' );
            $extensionDir = eZExtension::baseDirectory();
            $includeFile = $extensionDir . '/' . $extension . '/modules/' . $includeFile;
        }
        include_once( $includeFile );
        if ( !class_exists( $className ) )
        {
            return array( 'internal_error' => EZ_MODULE_FUNCTION_ERROR_NO_CLASS,
                          'internal_error_class_name' => $className );
        }
        $classObject =& $this->objectForClass( $className );
        if ( $classObject === null )
        {
            return array( 'internal_error' => EZ_MODULE_FUNCTION_ERROR_CLASS_INSTANTIATE_FAILED,
                          'internal_error_class_name' => $className );
        }
        if ( !method_exists( $classObject, $methodName ) )
        {
            return array( 'internal_error' => EZ_MODULE_FUNCTION_ERROR_NO_CLASS_METHOD,
                          'internal_error_class_name' => $className,
                          'internal_error_class_method_name' => $methodName );
        }
        $parameterArray = array();
        foreach ( $functionParameterDefinitions as $functionParameterDefinition )
        {
            $parameterName = $functionParameterDefinition['name'];
            if ( isset( $functionParameters[$parameterName] ) )
            {
                // Do type checking
                $parameterArray[] = $functionParameters[$parameterName];
            }
            else
            {
                if ( $functionParameterDefinition['required'] )
                {
                    return array( 'internal_error' => EZ_MODULE_FUNCTION_ERROR_MISSING_PARAMETER,
                                  'internal_error_parameter_name' => $parameterName );
                }
                else if ( isset( $functionParameterDefinition['default'] ) )
                {
                    $parameterArray[] = $functionParameterDefinition['default'];
                }
                else
                {
                    $parameterArray[] = null;
                }
            }
        }
        return $this->callClassMethod( $methodName, $classObject, $parameterArray );
    }

    function callClassMethod( $methodName, &$classObject, $parameterArray )
    {
/*        echo "*********** fetching START **************** <br>";
        var_dump( $methodName );
        var_dump( $classObject );
        var_dump( $parameterArray );
        echo "*********** fetching END ******************<br><br><br>";*/
        if ( $this->UseOldCall )
            return call_user_method_array( $methodName, $classObject, $parameterArray );
        else
            return call_user_func_array( array( $classObject, $methodName ), $parameterArray );
    }


    /// \privatesection
    var $ModuleName;
    var $FunctionList;
    var $IsValid;
    var $UseOldCall;
}

?>
