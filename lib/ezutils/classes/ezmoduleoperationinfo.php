<?php
//
// Definition of eZModuleOperationInfo class
//
// Created on: <06-Oct-2002 16:27:36 amos>
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

/*! \file ezmoduleoperationinfo.php
*/

/*!
  \class eZModuleOperationInfo ezmoduleoperationinfo.php
  \brief The class eZModuleOperationInfo does

*/

include_once( 'lib/ezutils/classes/ezmodule.php' );
include_once( 'lib/ezutils/classes/ezdebug.php' );
include_once( 'lib/ezutils/classes/ezoperationmemento.php' );

define( 'EZ_MODULE_OPERATION_ERROR_NO_CLASS', 5 );
define( 'EZ_MODULE_OPERATION_ERROR_NO_CLASS_METHOD', 6 );
define( 'EZ_MODULE_OPERATION_ERROR_CLASS_INSTANTIATE_FAILED', 7 );
define( 'EZ_MODULE_OPERATION_ERROR_MISSING_PARAMETER', 8 );

class eZModuleOperationInfo
{
    /*!
     Constructor
    */
    function eZModuleOperationInfo( $moduleName )
    {
        $this->ModuleName = $moduleName;
        $this->IsValid = false;
        $this->OperationList = array();
        $this->UseOldCall = false;
    }

    function isValid()
    {
        return $this->IsValid;
    }

    function loadDefinition()
    {
        $pathList = eZModule::globalPathList();
        foreach ( $pathList as $path )
        {
            $definitionFile = $path . '/' . $this->ModuleName . '/operation_definition.php';
            if ( file_exists( $definitionFile ) )
                break;
            $definitionFile = null;
        }
        if ( $definitionFile === null )
        {
            eZDebug::writeError( 'Missing operation definition file for module: ' . $this->ModuleName,
                                 'eZModuleOperationInfo::loadDefinition' );
            return false;
        }
        unset( $OperationList );
        include( $definitionFile );
        if ( !isset( $OperationList ) )
        {
            eZDebug::writeError( 'Missing operation definition list for module: ' . $this->ModuleName,
                                 'eZModuleOperationInfo::loadDefinition' );
            return false;
        }
        $this->OperationList = $OperationList;
        $this->IsValid = true;
        return true;
    }

    function makeKeyArray( &$operationDefinition, $operationParameters )
    {
        $keyArray = array();
        if ( array_key_exists( 'keys', $operationDefinition ) && is_array( $operationDefinition['keys'] ) )
        {
            foreach ( $operationDefinition['keys'] as $key )
            {
                $keyArray[$key] = $operationParameters[$key];
            }
            return $keyArray;
        }
        foreach ( $operationDefinition['parameters'] as $operationParameter )
        {
            $keyArray[$operationParameter['name']] = $operationParameters[$operationParameter['name']];
        }
        return $keyArray;
    }
    
    function execute( $operationName, $operationParameters, $lastTrigger = null )
    {
        $moduleName = $this->ModuleName;
        if ( !isset( $this->OperationList[$operationName] ) )
        {
            eZDebug::writeError( "No such operation '$operationName' in module '$moduleName'",
                                 'eZModuleOperationInfo::execute' );
            return null;
        }
        $operationDefinition =& $this->OperationList[$operationName];
        if ( !isset( $operationName['default_call_method'] ) )
        {
            eZDebug::writeError( "No call method defined for operation '$operationName' in module '$moduleName'",
                                 'eZModuleOperationInfo::execute' );
            return null;
        }
        if ( !isset( $operationName['body'] ) )
        {
            eZDebug::writeError( "No body for operation '$operationName' in module '$moduleName'",
                                 'eZModuleOperationInfo::execute' );
            return null;
        }
        if ( !isset( $operationName['parameters'] ) )
        {
            eZDebug::writeError( "No parameters defined for operation '$operationName' in module '$moduleName'",
                                 'eZModuleOperationInfo::execute' );
            return null;
        }
        $callMethod =& $operationDefinition['default_call_method'];
        if ( isset( $callMethod['include_file'] ) and
             isset( $callMethod['class'] ) )
        {
            $callValues = array( 'loop_run' => array() );



            $keyArray = $this->makeKeyArray( $operationDefinition, $operationParameters );
            $keyArray['session_key'] = eZHttpTool::getSessionKey();
            $mementoList = eZOperationMemento::fetchList( $keyArray );

            if ( count( $mementoList ) > 0 )
            {
                 ///restoring running operation
                
            }
            else
            {
                ///start  new operation

                $resultArray =& $this->executeBody( $callMethod['include_file'], $callMethod['class'], $operationDefinition['body'],
                                                    $operationDefinition['parameters'], $operationParameters,
                                                    $lastTrigger, $callValues, $operationDefinition['name'] );
            }
//             print( "result=" );
            print( "<pre>" );
//             var_dump( $resultArray );
            var_dump( $callValues );
            print( "</pre>" );
        }
        else
        {
            eZDebug::writeError( "No valid call methods found for operation '$operationName' in module '$moduleName'",
                                 'eZModuleOperationInfo::execute' );
            return null;
        }
        if ( !is_array( $resultArray ) )
        {
            eZDebug::writeError( "Operation '$operationName' in module '$moduleName' did not return a result array",
                                 'eZOperationHandler::execute' );
            return null;
        }
        if ( isset( $resultArray['internal_error'] ) )
        {
            switch ( $resultArray['internal_error'] )
            {
                case EZ_MODULE_OPERATION_ERROR_NO_CLASS:
                {
                    $className = $resultArray['internal_error_class_name'];
                    eZDebug::writeError( "No class '$className' available for operation '$operationName' in module '$moduleName'",
                                         'eZModuleOperationInfo::execute' );
                    return null;
                } break;
                case EZ_MODULE_OPERATION_ERROR_NO_CLASS_METHOD:
                {
                    $className = $resultArray['internal_error_class_name'];
                    $classMethodName = $resultArray['internal_error_class_method_name'];
                    eZDebug::writeError( "No method '$classMethodName' in class '$className' available for operation '$operationName' in module '$moduleName'",
                                         'eZModuleOperationInfo::execute' );
                    return null;
                } break;
                case EZ_MODULE_OPERATION_ERROR_CLASS_INSTANTIATE_FAILED:
                {
                    $className = $resultArray['internal_error_class_name'];
                    eZDebug::writeError( "Failed instantiating class '$className' which is needed for operation '$operationName' in module '$moduleName'",
                                         'eZModuleOperationInfo::execute' );
                    return null;
                } break;
                case EZ_MODULE_OPERATION_ERROR_MISSING_PARAMETER:
                {
                    $parameterName = $resultArray['internal_error_parameter_name'];
                    eZDebug::writeError( "Missing parameter '$parameterName' for operation '$operationName' in module '$moduleName'",
                                         'eZModuleOperationInfo::execute' );
                    return null;
                } break;
                default:
                {
                    $internalError = $resultArray['internal_error'];
                    eZDebug::writeError( "Unknown internal error '$internalError' for operation '$operationName' in module '$moduleName'",
                                         'eZModuleOperationInfo::execute' );
                    return null;
                } break;
            }
            return null;
        }
        else if ( isset( $resultArray['error'] ) )
        {
        }
        else if ( isset( $resultArray['status'] ) )
        {
            return $resultArray;
        }
        else
        {
            eZDebug::writeError( "Operation '$operationName' in module '$moduleName' did not return a result value",
                                 'eZOperationHandler::execute' );
        }
        return null;
    }

    function &objectForClass( $className )
    {
        $classObjectList =& $GLOBALS['eZModuleOperationClassObjectList'];
        if ( !isset( $classObjectList ) )
            $classObjectList = array();
        if ( isset( $classObjectList[$className] ) )
            return $classObjectList[$className];
        $classObject = new $className();
        $classObjectList[$className] =& $classObject;
        return $classObject;
    }

    function executeBody( $includeFile, $className, $bodyStructure,
                          $operationParameterDefinitions, $operationParameters,
                          &$lastTrigger, &$callValues, $operationName,$currentLoopData = null )
    {
        $bodyReturnValue = array( 'status' => true );
        foreach ( $bodyStructure as $body )
        {
            if ( !isset( $body['type'] ) )
            {
                eZDebug::writeError( 'No type for body element, skipping', 'eZModuleOperationInfo::executeBody' );
                continue;
            }
            if ( !isset( $body['name'] ) )
            {
                eZDebug::writeError( 'No name for body element, skipping', 'eZModuleOperationInfo::executeBody' );
                continue;
            }
            $bodyName = $body['name'];
            if ( !isset( $callValues['loop_run'][$bodyName] ) )
                $callValues['loop_run'][$bodyName] = 0;
            $type = $body['type'];
            switch ( $type )
            {
                case 'loop':
                {
                    $children = $body['children'];
                    $tmpOperationParameterDefinitions = $operationParameterDefinitions;
                    if ( isset( $body['child_parameters'] ) )
                        $tmpOperationParameterDefinitions = $body['child_parameters'];
                    $loopName = $body['name'];

                    if ( $lastTrigger !== null )
                    {
                        print( "skipped $bodyName due to trigger restore<br/>" );
                        $returnValue = $this->executeBody( $includeFile, $className, $children,
                                                           $tmpOperationParameterDefinitions, $operationParameters,
                                                           $lastTrigger, $callValues, $operationName );
                        print( "returned from loop<br/>" );
                        if ( !$returnValue['status'] )
                            return $returnValue;
                    }
                    else
                    {
                        ++$callValues['loop_run'][$bodyName];

                        $method = $body['method'];
                        $resultArray =& $this->executeClassMethod( $includeFile, $className, $method,
                                                                   $operationParameterDefinitions, $operationParameters );
                        $parameters = array();
                        if ( isset( $resultArray['parameters'] ) )
                        {
                            $parameters = $resultArray['parameters'];
                        }
                        $count = 0;
                        foreach ( $parameters as $parameterStructure )
                        {
                            $tmpOperationParameters = $operationParameters;
                            foreach ( $parameterStructure as $parameterName => $parameterValue )
                            {
                                $tmpOperationParameters[$parameterName] = $parameterValue;
                            }
//                            ++$tmpCallValues['run_number'];
                            ++$count;
                            print( "loop " . $count . "<br/>" );
                            $returnValue = $this->executeBody( $includeFile, $className, $children,
                                                               $tmpOperationParameterDefinitions, $tmpOperationParameters,
                                                               $lastTrigger, $callValues, $operationName, array( 'name' => $loopName,
                                                                                                                 'count' => count( $parameters ),
                                                                                                                 'index' => $count  ) );
                            if ( !$returnValue['status'] )
                                $bodyReturnValue = $returnValue;
                        }
                        if ( !$bodyReturnValue['status'] )
                        {
                            print( "One or more loop items failed, returning<br/>" );
                            return $bodyReturnValue;
                        }
                    }
                } break;
                case 'trigger':
                {
                    $triggerName = $body['name'];
                    $triggerKeys = $body['keys'];
                    if ( $lastTrigger !== null )
                    {
                        if ( $lastTrigger['name'] == $triggerName )
                        {
                            print( "Restoring trigger:$triggerName<br/>" );
                            $operationParameters = array();
                            if ( isset( $lastTrigger['parameters'] ) )
                                $operationParameters = $lastTrigger['parameters'];
                            if ( isset( $lastTrigger['loop_run'] ) )
                                $callValues['loop_run'] = $lastTrigger['loop_run'];
                            if ( isset( $lastTrigger['loop_data'] ) )
                                $currentLoopData = $lastTrigger['loop_data'];
                            $lastTrigger = null;
                            ++$callValues['loop_run'][$bodyName];
                        }
                        else
                            print( "Skipping trigger:$triggerName<br/>" );
                    }
                    else
                    {
/*
                        $triggerParameters = array();
                        foreach ( $triggerKeys as $key )
                        {
                            $triggerParameters[$key] = $operationParameters[$key];
                        }

                        $Result = array();
                        $triggerStatus =  eZTrigger::runTrigger( $triggerName, $this->ModuleName, $operationName, $triggerParameters, $Result );
*/


                        // Hack to get trigger for node id 16
//                         if ( $bodyName == 'pre_read' and
//                              $operationParameters['node_id'] == 16 )
//                         {
//                             print( "Trigger halted:$triggerName<br/>" );
// //                             print( "<pre>" );
// //                             var_dump( $currentLoopData );
// //                             print( "</pre>" );
//                             $bodyReturnValue['status'] = false;
//                             $bodyReturnValue['result'] = array( 'content' => '<h1>sadfasdfsf</h1>' );
//                             return $bodyReturnValue;
//                         }
//                         else
                        {
                            ++$callValues['loop_run'][$bodyName];
                            print( "Trigger:$triggerName<br/>" );
                        }
                    }
                } break;
                case 'method':
                {
                    if ( $lastTrigger === null )
                    {
                        $method = $body['method'];
                        $frequency = $body['frequency'];
                        $executeMethod = true;
                        if ( $frequency == 'once' and
                             $callValues['loop_run'][$bodyName] != 0 )
                            $executeMethod = false;
                        print( "method call #" . $callValues['loop_run'][$bodyName] . ":$bodyName" . ( $executeMethod ? "" : "(skipped)" ) . "<br/>" );
                        $tmpOperationParameterDefinitions = $operationParameterDefinitions;
                        if ( isset( $body['parameters'] ) )
                            $tmpOperationParameterDefinitions = $body['parameters'];
                        if ( $executeMethod )
                        {
                            ++$callValues['loop_run'][$bodyName];
                            $this->executeClassMethod( $includeFile, $className, $method,
                                                       $tmpOperationParameterDefinitions, $operationParameters );
                        }
                    }
                    else
                        print( "skipped $bodyName due to trigger restore<br/>" );
                } break;
                default:
                {
                    eZDebug::writeError( "Unknown operation type $type", 'eZModuleOperationInfo::executeBody' );
                }
            }
        }
        return $bodyReturnValue;
    }

    function executeClassMethod( $includeFile, $className, $methodName,
                                 $operationParameterDefinitions, $operationParameters )
    {
        include_once( $includeFile );
        if ( !class_exists( $className ) )
        {
            return array( 'internal_error' => EZ_MODULE_OPERATION_ERROR_NO_CLASS,
                          'internal_error_class_name' => $className );
        }
        $classObject =& $this->objectForClass( $className );
        if ( $classObject === null )
        {
            return array( 'internal_error' => EZ_MODULE_OPERATION_ERROR_CLASS_INSTANTIATE_FAILED,
                          'internal_error_class_name' => $className );
        }
        if ( !method_exists( $classObject, $methodName ) )
        {
            return array( 'internal_error' => EZ_MODULE_OPERATION_ERROR_NO_CLASS_METHOD,
                          'internal_error_class_name' => $className,
                          'internal_error_class_method_name' => $methodName );
        }
        $parameterArray = array();
        foreach ( $operationParameterDefinitions as $operationParameterDefinition )
        {
            $parameterName = $operationParameterDefinition['name'];
            if ( isset( $operationParameterDefinition['constant'] ) )
            {
                $constantValue = $operationParameterDefinition['constant'];
                $parameterArray[] = $constantValue;
            }
            else if ( isset( $operationParameters[$parameterName] ) )
            {
                // Do type checking
                $parameterArray[] = $operationParameters[$parameterName];
            }
            else
            {
                if ( $operationParameterDefinition['required'] )
                {
                    return array( 'internal_error' => EZ_MODULE_OPERATION_ERROR_MISSING_PARAMETER,
                                  'internal_error_parameter_name' => $parameterName );
                }
                else if ( isset( $operationParameterDefinition['default'] ) )
                {
                    $parameterArray[] = $operationParameterDefinition['default'];
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
