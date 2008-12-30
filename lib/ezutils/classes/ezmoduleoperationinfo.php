<?php
//
// Definition of eZModuleOperationInfo class
//
// Created on: <06-Oct-2002 16:27:36 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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

/*! \file
*/

/*!
  \class eZModuleOperationInfo ezmoduleoperationinfo.php
  \brief The class eZModuleOperationInfo does

*/

class eZModuleOperationInfo
{
    const ERROR_NO_CLASS = 5;
    const ERROR_NO_CLASS_METHOD = 6;
    const ERROR_CLASS_INSTANTIATE_FAILED = 7;
    const ERROR_MISSING_PARAMETER = 8;

    const STATUS_CONTINUE = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_HALTED = 3;

    /*!
     Constructor
    */
    function eZModuleOperationInfo( $moduleName, $useTriggers = true )
    {
        $this->ModuleName = $moduleName;
        $this->IsValid = false;
        $this->OperationList = array();
        $this->Memento = null;
        $this->UseTriggers = $useTriggers;
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

    function makeOperationKeyArray( $operationDefinition, $operationParameters )
    {
        $keyDefinition = null;
        if ( array_key_exists( 'keys', $operationDefinition ) and
             is_array( $operationDefinition['keys'] ) )
        {
            $keyDefinition = $operationDefinition['keys'];
        }
        return $this->makeKeyArray( $keyDefinition, $operationDefinition['parameters'], $operationParameters );
    }

    function makeKeyArray( $keyDefinition, $parameterDefinition, $operationParameters )
    {
        $keyArray = array();
        if ( $keyDefinition !== null )
        {
            foreach ( $keyDefinition as $key )
            {
                $keyArray[$key] = $operationParameters[$key];
            }
        }
        else
        {
            foreach ( $parameterDefinition as $operationParameter )
            {
                $keyArray[$operationParameter['name']] = $operationParameters[$operationParameter['name']];
            }
        }
        return $keyArray;
    }

    function execute( $operationName, $operationParameters, $mementoData = null )
    {
        $moduleName = $this->ModuleName;
        if ( !isset( $this->OperationList[$operationName] ) )
        {
            eZDebug::writeError( "No such operation '$operationName' in module '$moduleName'",
                                 'eZModuleOperationInfo::execute' );
            return null;
        }
        $operationDefinition = $this->OperationList[$operationName];
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
        $callMethod = $operationDefinition['default_call_method'];
        $resultArray = null;
        $this->Memento = null;
        if ( isset( $callMethod['include_file'] ) and
             isset( $callMethod['class'] ) )
        {
            $bodyCallCount = array( 'loop_run' => array() );
            $operationKeys = null;
            if ( isset( $operationDefinition['keys'] ) )
                $operationKeys = $operationDefinition['keys'];
            $operationParameterDefinitions = $operationDefinition['parameters'];

            $db = eZDB::instance();
            $db->begin();

            $this->storeOperationMemento( $operationKeys, $operationParameterDefinitions, $operationParameters, $bodyCallCount, $operationName );

            $runOperation = true;
            if ( $mementoData === null )
            {
                $keyArray = $this->makeOperationKeyArray( $operationDefinition, $operationParameters );
                $http = eZHTTPTool::instance();
                $keyArray['session_key'] = $http->getSessionKey();
                $mainMemento = null;
                if ( $this->UseTriggers )
                    $mainMemento = eZOperationMemento::fetchMain( $keyArray );

                if ( $mainMemento !== null )
                {
                    $this->Memento = $mainMemento;
                    $mementoOperationData = $this->Memento->data();
                    if ( isset( $mementoOperationData['loop_run'] ) )
                        $bodyCallCount['loop_run'] = $mementoOperationData['loop_run'];
                }
//                 else
//                     eZDebug::writeWarning( 'Missing main operation memento for key: ' . $this->Memento->attribute( 'memento_key' ), 'eZModuleOperationInfo::execute' );

                $mementoList = null;
                if ( $this->UseTriggers )
                    $mementoList = eZOperationMemento::fetchList( $keyArray );

                if ( count( $mementoList ) > 0 )
                {
                    $lastResultArray = array();
                    $mementoRestoreSuccess = true;
                    // restoring running operation
                    foreach ( $mementoList as $memento )
                    {
                        $mementoData = $memento->data();
                        $memento->remove();

                        $resultArray = $this->executeBody( $callMethod['include_file'], $callMethod['class'], $operationDefinition['body'],
                                                           $operationKeys, $operationParameterDefinitions, $operationParameters,
                                                           $mementoData, $bodyCallCount, $operationDefinition['name'] );
                        if ( is_array( $resultArray ) )
                        {
                            $lastResultArray = array_merge( $lastResultArray, $resultArray );
                            if ( !$resultArray['status'] )
                                $mementoRestoreSuccess = false;
                        }
                    }
                    $resultArray = $lastResultArray;
                    //                 $resultArray['status'] = $mementoRestoreSuccess;
                    $runOperation = false;
                }
            }
            if ( $runOperation )
            {
                // start  new operation
                $resultArray = $this->executeBody( $callMethod['include_file'], $callMethod['class'], $operationDefinition['body'],
                                                    $operationKeys, $operationParameterDefinitions, $operationParameters,
                                                    $mementoData, $bodyCallCount, $operationDefinition['name'] );

//                 eZDebug::writeDebug( $resultArray, 'ezmodule operation result array' );
            }

            if ( is_array( $resultArray ) and
                 isset( $resultArray['status'] ) and
                 $resultArray['status'] == eZModuleOperationInfo::STATUS_HALTED )
            {
//                 eZDebug::writeDebug( $this->Memento, 'ezmodule operation result halted' );
                if ( $this->Memento !== null )
                {
                    $this->Memento->store();
                }
            }
            else if ( $this->Memento !== null and
                      $this->Memento->attribute( 'id' ) !== null )
            {
//                 eZDebug::writeDebug( $this->Memento, 'ezmodule operation result not halted' );
                $this->Memento->remove();
            }
//            if ( $resultArray['status'] == eZModuleOperationInfo::STATUS_CANCELLED )
//            {
//                return null;
//            }
            /*
            else if ( isset( $mementoData['memento_key'] ) )
            {
                $memento = eZOperationMemento::fetch( $mementoData['mementoKey'] );
                if ( $memento->attribute( 'main_key') !=  $mementoData['mementoKey'] )
                {
                    $mainMemento = eZOperationMemento::fetch( $memento->attribute( 'main_key') );
                }
                $memento->remove();
            }
            */
            $this->Memento = null;

            $db->commit();
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
                case eZModuleOperationInfo::ERROR_NO_CLASS:
                {
                    $className = $resultArray['internal_error_class_name'];
                    eZDebug::writeError( "No class '$className' available for operation '$operationName' in module '$moduleName'",
                                         'eZModuleOperationInfo::execute' );
                    return null;
                } break;
                case eZModuleOperationInfo::ERROR_NO_CLASS_METHOD:
                {
                    $className = $resultArray['internal_error_class_name'];
                    $classMethodName = $resultArray['internal_error_class_method_name'];
                    eZDebug::writeError( "No method '$classMethodName' in class '$className' available for operation '$operationName' in module '$moduleName'",
                                         'eZModuleOperationInfo::execute' );
                    return null;
                } break;
                case eZModuleOperationInfo::ERROR_CLASS_INSTANTIATE_FAILED:
                {
                    $className = $resultArray['internal_error_class_name'];
                    eZDebug::writeError( "Failed instantiating class '$className' which is needed for operation '$operationName' in module '$moduleName'",
                                         'eZModuleOperationInfo::execute' );
                    return null;
                } break;
                case eZModuleOperationInfo::ERROR_MISSING_PARAMETER:
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

    function executeBody( $includeFile, $className, $bodyStructure,
                          $operationKeys, $operationParameterDefinitions, $operationParameters,
                          &$mementoData, &$bodyCallCount, $operationName, $currentLoopData = null )
    {
        $bodyReturnValue = array( 'status' => eZModuleOperationInfo::STATUS_CONTINUE );
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
            if ( !isset( $bodyCallCount['loop_run'][$bodyName] ) )
                $bodyCallCount['loop_run'][$bodyName] = 0;
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

                    if ( $mementoData !== null )
                    {
                        $returnValue = $this->executeBody( $includeFile, $className, $children,
                                                           $operationKeys, $tmpOperationParameterDefinitions, $operationParameters,
                                                           $mementoData, $bodyCallCount, $operationName, null );
                        if ( !$returnValue['status'] )
                            return $returnValue;
                    }
                    else
                    {
                        ++$bodyCallCount['loop_run'][$bodyName];

                        $method = $body['method'];
                        $resultArray = $this->executeClassMethod( $includeFile, $className, $method,
                                                                  $operationParameterDefinitions, $operationParameters );
                        $parameters = array();
                        if ( isset( $resultArray['parameters'] ) )
                        {
                            $parameters = $resultArray['parameters'];
                        }
                        $count = 0;
                        $countDone = 0;
                        $countHalted = 0;
                        $countCanceled = 0;
                        foreach ( $parameters as $parameterStructure )
                        {
                            $tmpOperationParameters = $operationParameters;
                            foreach ( $parameterStructure as $parameterName => $parameterValue )
                            {
                                $tmpOperationParameters[$parameterName] = $parameterValue;
                            }

                            ++$count;
                            $returnValue = $this->executeBody( $includeFile, $className, $children,
                                                               $operationKeys, $tmpOperationParameterDefinitions, $tmpOperationParameters,
                                                               $mementoData, $bodyCallCount, $operationName, array( 'name' => $loopName,
                                                                                                                    'count' => count( $parameters ),
                                                                                                                    'index' => $count  ) );
                            switch( $returnValue['status'] )
                            {
                                case eZModuleOperationInfo::STATUS_CANCELLED:
                                {
                                    $bodyReturnValue = $returnValue;
                                    ++$countCanceled;
                                }break;
                                case eZModuleOperationInfo::STATUS_CONTINUE:
                                {
                                    $bodyReturnValue = $returnValue;

                                    ++$countDone;
                                }break;
                                case eZModuleOperationInfo::STATUS_HALTED:
                                {
                                    $bodyReturnValue = $returnValue;
                                    ++$countHalted;
                                }break;
                            }
                        }
                        if ( $body['continue_operation'] == 'all' )
                        {
                            if ( $count == $countDone )
                            {
                                // continue operation
                            }
                            if ( $countCanceled > 0 )
                            {
                                return $bodyReturnValue;
                                //cancel operation
                            }
                            if ( $countHalted > 0 )
                            {
                                return $bodyReturnValue;                                //show tempalate
                            }

                        }
                    }
                } break;
                case 'trigger':
                {
                    if ( !$this->UseTriggers )
                    {
                        $bodyReturnValue['status'] = eZModuleOperationInfo::STATUS_CONTINUE;
                        continue;

                    }

                    $triggerName = $body['name'];
                    $triggerRestored = false;
                    $executeTrigger = true;
                    if ( $mementoData !== null )
                    {
                        if ( $mementoData['name'] == $triggerName )
                        {
                            $executeTrigger =  $this->restoreBodyMementoData( $bodyName, $mementoData,
                                                                              $operationParameters, $bodyCallCount, $currentLoopData );
                            $triggerRestored = true;
                        }
                        else
                        {
                            $executeTrigger = false;
                        }
                    }
                    if ( $executeTrigger )
                    {
                        $status = $this->executeTrigger( $bodyReturnValue, $body,
                                                          $operationParameterDefinitions, $operationParameters,
                                                          $bodyCallCount, $currentLoopData,
                                                          $triggerRestored, $operationName, $operationKeys );

                        switch( $status )
                        {
                            case eZModuleOperationInfo::STATUS_CONTINUE:
                            {
                                $bodyReturnValue['status'] = eZModuleOperationInfo::STATUS_CONTINUE;
                            }break;
                            case eZModuleOperationInfo::STATUS_CANCELLED:
                            {
                                $bodyReturnValue['status'] = eZModuleOperationInfo::STATUS_CANCELLED;
                                return $bodyReturnValue;
                            }break;
                            case eZModuleOperationInfo::STATUS_HALTED:
                            {

                                $bodyReturnValue['status'] = eZModuleOperationInfo::STATUS_HALTED;
                                return $bodyReturnValue;
                            }
                        }
                    }else
                    {
                        $bodyReturnValue['status'] = eZModuleOperationInfo::STATUS_CONTINUE;
                    }
                } break;
                case 'method':
                {
                    if ( $mementoData === null )
                    {
                        $method = $body['method'];
                        $frequency = $body['frequency'];
                        $executeMethod = true;
                        if ( $frequency == 'once' and
                             $bodyCallCount['loop_run'][$bodyName] != 0 )
                            $executeMethod = false;
                        $tmpOperationParameterDefinitions = $operationParameterDefinitions;
                        if ( isset( $body['parameters'] ) )
                            $tmpOperationParameterDefinitions = $body['parameters'];
                        if ( $executeMethod )
                        {
                            ++$bodyCallCount['loop_run'][$bodyName];
                            $result = $this->executeClassMethod( $includeFile, $className, $method,
                                                                 $tmpOperationParameterDefinitions, $operationParameters );
                            if ( $result && array_key_exists( 'status', $result ) )
                {
                                switch( $result['status'] )
                                {
                                    case eZModuleOperationInfo::STATUS_CONTINUE:
                                    default:
                                    {
                                        $result['status'] = eZModuleOperationInfo::STATUS_CONTINUE;
                                        $bodyReturnValue = $result;
                                    } break;

                                    case eZModuleOperationInfo::STATUS_CANCELLED:
                                    case eZModuleOperationInfo::STATUS_HALTED:
                                    {
                                        return $result;
                                    } break;
                                }
                            }
                        }
                    }
                } break;
                default:
                {
                    eZDebug::writeError( "Unknown operation type $type", 'eZModuleOperationInfo::executeBody' );
                }
            }
        }

        return $bodyReturnValue;
    }

    function executeTrigger( &$bodyReturnValue, $body,
                             $operationParameterDefinitions, $operationParameters,
                             &$bodyCallCount, $currentLoopData,
                             $triggerRestored, $operationName, &$operationKeys )
    {
        $triggerName = $body['name'];
        $triggerKeys = $body['keys'];

        $status = eZTrigger::runTrigger( $triggerName, $this->ModuleName, $operationName, $operationParameters, $triggerKeys );


        if ( $status['Status'] == eZTrigger::WORKFLOW_DONE ||
             $status['Status'] == eZTrigger::NO_CONNECTED_WORKFLOWS )
        {
            ++$bodyCallCount['loop_run'][$triggerName];
            return eZModuleOperationInfo::STATUS_CONTINUE;
        }
        else if ( $status['Status'] == eZTrigger::STATUS_CRON_JOB ||
                  $status['Status'] == eZTrigger::FETCH_TEMPLATE ||
                  $status['Status'] == eZTrigger::REDIRECT )
        {
            $bodyMemento = $this->storeBodyMemento( $triggerName, $triggerKeys,
                                                    $operationKeys, $operationParameterDefinitions, $operationParameters,
                                                    $bodyCallCount, $currentLoopData, $operationName );
            $workflowProcess = $status['WorkflowProcess'];
            if ( ! is_null( $workflowProcess ) )
            {
                $workflowProcess->setAttribute( 'memento_key', $bodyMemento->attribute( 'memento_key' ) );
                $workflowProcess->store();
            }

            $bodyReturnValue['result'] = $status['Result'];
            if ( $status['Status'] == eZTrigger::REDIRECT )
            {
                $bodyReturnValue['redirect_url'] = $status['Result'];
            }
            return eZModuleOperationInfo::STATUS_HALTED;
        }
        else if ( $status['Status'] == eZTrigger::WORKFLOW_CANCELLED or
                  $status['Status'] == eZTrigger::WORKFLOW_RESET )
        {
             return eZModuleOperationInfo::STATUS_CANCELLED;
             $bodyReturnValue['result'] = $status['Result'];
        }
    }

    function storeOperationMemento( $operationKeys, $operationParameterDefinitions, $operationParameters,
                                    &$bodyCallCount, $operationName )
    {
        $mementoData = array();
        $mementoData['module_name'] = $this->ModuleName;
        $mementoData['operation_name'] = $operationName;
        if ( $this->Memento === null )
        {
            $keyArray = $this->makeKeyArray( $operationKeys, $operationParameterDefinitions, $operationParameters );
            $http = eZHTTPTool::instance();
            $keyArray['session_key'] = $http->getSessionKey();
            $mementoData['loop_run'] = $bodyCallCount['loop_run'];
            $memento = eZOperationMemento::create( $keyArray, $mementoData, true );
            $this->Memento = $memento;
        }
        else
        {
            $mementoData = $this->Memento->data();
            $mementoData['loop_run'] = $bodyCallCount['loop_run'];
            $this->Memento->setData( $mementoData );
        }
    }

    function removeBodyMemento( $bodyName, $bodyKeys,
                                $operationKeys, $operationParameterDefinitions, $operationParameters,
                                &$bodyCallCount, $currentLoopData, $operationName )
    {
        $keyArray = $this->makeKeyArray( $operationKeys, $operationParameterDefinitions, $operationParameters );
    }
    function storeBodyMemento( $bodyName, $bodyKeys,
                               $operationKeys, $operationParameterDefinitions, $operationParameters,
                               &$bodyCallCount, $currentLoopData, $operationName )
    {
        $this->storeOperationMemento( $operationKeys, $operationParameterDefinitions, $operationParameters, $bodyCallCount, $operationName );

        $keyArray = $this->makeKeyArray( $operationKeys, $operationParameterDefinitions, $operationParameters );
        $http = eZHTTPTool::instance();
        $keyArray['session_key'] = $http->getSessionKey();
        $mementoData = array();
        $mementoData['name'] = $bodyName;
        $mementoData['parameters'] = $operationParameters;
        $mementoData['loop_data'] = $currentLoopData;
        $mementoData['module_name'] = $this->ModuleName;
        $mementoData['operation_name'] = $operationName;
        $memento = eZOperationMemento::create( $keyArray, $mementoData, false, $this->Memento->attribute( 'memento_key' ) );
        $memento->store();
        return $memento;
    }

    function restoreBodyMementoData( $bodyName, &$mementoData,
                                     &$operationParameters, &$bodyCallCount, &$currentLoopData )
    {
        $operationParameters = array();
        if ( isset( $mementoData['parameters'] ) )
            $operationParameters = $mementoData['parameters'];
        if ( isset( $mementoData[ 'main_memento' ] ) )
        {
            $this->Memento = $mementoData[ 'main_memento' ];
            $mainMementoData = $this->Memento->data();
            if ( isset( $mainMementoData['loop_run'] ) )
            {
                $bodyCallCount['loop_run'] = $mainMementoData['loop_run'];
            }

        }

//         if ( $this->Memento !== null )
//         {
//             $mementoOperationData = $this->Memento->data();
//             if ( isset( $mementoOperationData['loop_run'] ) )
//                 $bodyCallCount['loop_run'] = $mementoOperationData['loop_run'];
//         }
        if ( isset( $mementoData['loop_data'] ) )
            $currentLoopData = $mementoData['loop_data'];

        if ( isset( $mementoData['skip_trigger'] ) && $mementoData['skip_trigger'] == true )
        {
            $mementoData = null;
            return false;
        }
        else
        {
            $mementoData = null;
            return true;
        }

        return true;
    }

    function executeClassMethod( $includeFile, $className, $methodName,
                                 $operationParameterDefinitions, $operationParameters )
    {
        include_once( $includeFile );
        if ( !class_exists( $className ) )
        {
            return array( 'internal_error' => eZModuleOperationInfo::ERROR_NO_CLASS,
                          'internal_error_class_name' => $className );
        }
        $classObject = $this->objectForClass( $className );
        if ( $classObject === null )
        {
            return array( 'internal_error' => eZModuleOperationInfo::ERROR_CLASS_INSTANTIATE_FAILED,
                          'internal_error_class_name' => $className );
        }
        if ( !method_exists( $classObject, $methodName ) )
        {
            return array( 'internal_error' => eZModuleOperationInfo::ERROR_NO_CLASS_METHOD,
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

                    return array( 'internal_error' => eZModuleOperationInfo::ERROR_MISSING_PARAMETER,
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

        return call_user_func_array( array( $classObject, $methodName ), $parameterArray );
    }

    function objectForClass( $className )
    {
        if ( !isset( $GLOBALS['eZModuleOperationClassObjectList'] ) )
        {
            $GLOBALS['eZModuleOperationClassObjectList'] = array();
        }
        if ( isset( $GLOBALS['eZModuleOperationClassObjectList'][$className] ) )
        {
            return $GLOBALS['eZModuleOperationClassObjectList'][$className];
        }

        return $GLOBALS['eZModuleOperationClassObjectList'][$className] = new $className();
    }

    /*!
     \deprecated use call_user_func_array() instead
    */
    function callClassMethod( $methodName, $classObject, $parameterArray )
    {
        return call_user_func_array( array( $classObject, $methodName ), $parameterArray );
    }


    /// \privatesection
    public $ModuleName;
    public $FunctionList;
    public $IsValid;
    public $UseTriggers = false;
}

?>
