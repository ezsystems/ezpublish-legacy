<?php
/**
 * File containing the eZModuleOperationInfo class.
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
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
    const STATUS_REPEAT = 4;
    const STATUS_QUEUED = 5;

    /**
     * Constructor
     * @param string $moduleName
     * @param bool $useTriggers
     */
    function eZModuleOperationInfo( $moduleName, $useTriggers = true )
    {
        $this->ModuleName = $moduleName;
        $this->IsValid = false;
        $this->OperationList = array();
        $this->Memento = null;
        $this->UseTriggers = $useTriggers;
    }

    /**
     * ???
     *
     * @return bool
     */
    function isValid()
    {
        return $this->IsValid;
    }

    /**
     * Loads the operations definition for the current module
     * @return bool true if the operations were loaded, false if an error occured
     */
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
            eZDebug::writeError( 'Missing operation definition file for module: ' . $this->ModuleName, __METHOD__ );
            return false;
        }
        unset( $OperationList );
        include( $definitionFile );
        if ( !isset( $OperationList ) )
        {
            eZDebug::writeError( 'Missing operation definition list for module: ' . $this->ModuleName, __METHOD__ );
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

    /**
     * Executes the operation
     *
     * @param string $operationName
     * @param array $operationParameters
     * @param array $mementoData
     * @return mixed the operation execution result, or null if an error occured
     */
    function execute( $operationName, $operationParameters, $mementoData = null )
    {
        $moduleName = $this->ModuleName;
        if ( !isset( $this->OperationList[$operationName] ) )
        {
            eZDebug::writeError( "No such operation '$operationName' in module '$moduleName'", __METHOD__ );
            return null;
        }
        $operationDefinition = $this->OperationList[$operationName];
        if ( !isset( $operationDefinition['default_call_method'] ) )
        {
            eZDebug::writeError( "No call method defined for operation '$operationName' in module '$moduleName'", __METHOD__ );
            return null;
        }
        if ( !isset( $operationDefinition['body'] ) )
        {
            eZDebug::writeError( "No body for operation '$operationName' in module '$moduleName'", __METHOD__ );
            return null;
        }
        if ( !isset( $operationDefinition['parameters'] ) )
        {
            eZDebug::writeError( "No parameters defined for operation '$operationName' in module '$moduleName'", __METHOD__ );
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

            $this->storeOperationMemento( $operationKeys, $operationParameterDefinitions, $operationParameters, $bodyCallCount, $operationName );

            $runOperation = true;
            if ( $mementoData === null )
            {
                $keyArray = $this->makeOperationKeyArray( $operationDefinition, $operationParameters );
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
            }

            if ( is_array( $resultArray ) and
                 isset( $resultArray['status'] ) and
                 ( $resultArray['status'] == eZModuleOperationInfo::STATUS_HALTED
                   or $resultArray['status'] == eZModuleOperationInfo::STATUS_REPEAT ) )
            {
                if ( $this->Memento !== null )
                {
                    // $bodyCallCount stores an indexed array of each operation method that was executed
                    // it must be store in the memento on HALT/REPEAT so that the operation can be resumed
                    // where it was stopped (same behaviour as triggers)
                    $this->storeOperationMemento( $operationKeys, $operationParameterDefinitions, $operationParameters, $bodyCallCount, $operationName );
                    $data = $this->Memento->data();
                    $data['loop_run'] = $bodyCallCount['loop_run'];
                    $this->Memento->setData( $data );
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
        }
        else
        {
            eZDebug::writeError( "No valid call methods found for operation '$operationName' in module '$moduleName'", __METHOD__ );
            return null;
        }
        if ( !is_array( $resultArray ) )
        {
            eZDebug::writeError( "Operation '$operationName' in module '$moduleName' did not return a result array", __METHOD__ );
            return null;
        }
        if ( isset( $resultArray['internal_error'] ) )
        {
            switch ( $resultArray['internal_error'] )
            {
                case eZModuleOperationInfo::ERROR_NO_CLASS:
                {
                    $className = $resultArray['internal_error_class_name'];
                    eZDebug::writeError( "No class '$className' available for operation '$operationName' in module '$moduleName'", __METHOD__ );
                    return null;
                } break;
                case eZModuleOperationInfo::ERROR_NO_CLASS_METHOD:
                {
                    $className = $resultArray['internal_error_class_name'];
                    $classMethodName = $resultArray['internal_error_class_method_name'];
                    eZDebug::writeError( "No method '$classMethodName' in class '$className' available for operation '$operationName' in module '$moduleName'", __METHOD__ );
                    return null;
                } break;
                case eZModuleOperationInfo::ERROR_CLASS_INSTANTIATE_FAILED:
                {
                    $className = $resultArray['internal_error_class_name'];
                    eZDebug::writeError( "Failed instantiating class '$className' which is needed for operation '$operationName' in module '$moduleName'", __METHOD__ );
                    return null;
                } break;
                case eZModuleOperationInfo::ERROR_MISSING_PARAMETER:
                {
                    $parameterName = $resultArray['internal_error_parameter_name'];
                    eZDebug::writeError( "Missing parameter '$parameterName' for operation '$operationName' in module '$moduleName'", __METHOD__ );
                    return null;
                } break;
                default:
                {
                    $internalError = $resultArray['internal_error'];
                    eZDebug::writeError( "Unknown internal error '$internalError' for operation '$operationName' in module '$moduleName'", __METHOD__ );
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
            eZDebug::writeError( "Operation '$operationName' in module '$moduleName' did not return a result value", __METHOD__ );
        }
        return null;
    }

    /**
     * Executes the operation body
     *
     * @param string $includeFile Path to the file where the operation class is defined
     * @param string $className Name of the class holding the operation methods (@see $includeFile)
     * @param array $bodyStructure
     * @param array $operationKeys
     * @param array $operationParameterDefinitions
     * @param array $operationParameters
     * @param array $mementoData
     * @param int $bodyCallCount
     * @param string $operationName
     * @param array $currentLoopData
     * @return array
     */
    function executeBody( $includeFile, $className, $bodyStructure,
                          $operationKeys, $operationParameterDefinitions, $operationParameters,
                          &$mementoData, &$bodyCallCount, $operationName, $currentLoopData = null )
    {
        $bodyReturnValue = array( 'status' => eZModuleOperationInfo::STATUS_CONTINUE );
        foreach ( $bodyStructure as $body )
        {
            if ( !isset( $body['type'] ) )
            {
                eZDebug::writeError( 'No type for body element, skipping', __METHOD__ );
                continue;
            }
            if ( !isset( $body['name'] ) )
            {
                eZDebug::writeError( 'No name for body element, skipping', __METHOD__ );
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
                        $countRepeated = 0;
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
                                case eZModuleOperationInfo::STATUS_REPEAT:
                                {
                                    $bodyReturnValue = $returnValue;
                                    ++$countRepeated;
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
                            case eZModuleOperationInfo::STATUS_REPEAT:
                            {

                                $bodyReturnValue['status'] = eZModuleOperationInfo::STATUS_REPEAT;
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
                            $result = $this->executeClassMethod( $includeFile, $className, $method,
                                                                 $tmpOperationParameterDefinitions, $operationParameters );
                            if ( $result && array_key_exists( 'status', $result ) )
                            {
                                switch( $result['status'] )
                                {
                                    case eZModuleOperationInfo::STATUS_CONTINUE:
                                    default:
                                    {
                                        // moved from outside the case:
                                        // we don't want to count the method as executed if it doesn't return a CONTINUE status,
                                        // or it won't be executed next run
                                        ++$bodyCallCount['loop_run'][$bodyName];
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
                    eZDebug::writeError( "Unknown operation type $type", __METHOD__ );
                }
            }
        }

        return $bodyReturnValue;
    }

    /**
     * Executes an operation trigger
     *
     * @param array $bodyReturnValue The current return value
     * @param array $body Body data for the trigger being executed
     * @param array $operationParameterDefinitions Operation parameters definition
     * @param array $operationParameters Operation parameters values
     * @param int $bodyCallCount Number of times the body was called
     * @param array $currentLoopData Memento data for the operation
     * @param bool $triggerRestored Boolean that indicates if operation data (memento) was restored
     * @param string $operationName The operation name
     * @param array $operationKeys Additional parameters. Only used by looping so far.
     * @return
     */
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
                  $status['Status'] == eZTrigger::FETCH_TEMPLATE_REPEAT ||
                  $status['Status'] == eZTrigger::REDIRECT )
        {
            $bodyMemento = $this->storeBodyMemento( $triggerName, $triggerKeys,
                                                    $operationKeys, $operationParameterDefinitions, $operationParameters,
                                                    $bodyCallCount, $currentLoopData, $operationName );
            $workflowProcess = $status['WorkflowProcess'];
            if ( $workflowProcess !== null )
            {
                $workflowProcess->setAttribute( 'memento_key', $bodyMemento->attribute( 'memento_key' ) );
                $workflowProcess->store();
            }

            $bodyReturnValue['result'] = $status['Result'];
            if ( $status['Status'] == eZTrigger::REDIRECT )
            {
                $bodyReturnValue['redirect_url'] = $status['Result'];
            }
            if ( $status['Status'] == eZTrigger::FETCH_TEMPLATE_REPEAT )
            {
                // Hack for project issue #14371 (fetch template repeat)
                // The object version's status is set to REPEAT so that it can
                // be submitted again
                if ( $operationName == 'publish' && $this->ModuleName == 'content' )
                {
                    eZContentOperationCollection::setVersionStatus( $operationParameters['object_id'],
                        $operationParameters['version'], eZContentObjectVersion::STATUS_REPEAT );
                }
                return eZModuleOperationInfo::STATUS_REPEAT;
            }
            else
            {
                return eZModuleOperationInfo::STATUS_HALTED;
            }
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

    /**
     * Packs the current body data (memento) for save & re-use
     *
     * @param string $bodyName
     * @param array $bodyKeys
     * @param array $operationKeys
     * @param array $operationParameterDefinitions
     * @param array $operationParameters
     * @param int $bodyCallCount
     * @param array $currentLoopData
     * @param string $operationName
     * @return The memento
     */
    function storeBodyMemento( $bodyName, $bodyKeys,
                               $operationKeys, $operationParameterDefinitions, $operationParameters,
                               &$bodyCallCount, $currentLoopData, $operationName )
    {
        $this->storeOperationMemento( $operationKeys, $operationParameterDefinitions, $operationParameters, $bodyCallCount, $operationName );

        $keyArray = $this->makeKeyArray( $operationKeys, $operationParameterDefinitions, $operationParameters );
        $http = eZHTTPTool::instance();
        $keyArray['session_key'] = $http->sessionID();
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

    /**
     * Executes a class method in an operation body
     *
     * @param string $includeFile The file where the class & method are defined
     * @param string $className The class where the method is implemented
     * @param string $methodName The method to call
     * @param mixed $operationParameterDefinitions The method parameters definition
     * @param mixed $operationParameters The method parameters values
     * @return array
     */
    function executeClassMethod( $includeFile, $className, $methodName,
                                 $operationParameterDefinitions, $operationParameters )
    {
        if ( !class_exists( $className ) )
        {
            include_once( $includeFile );

            if ( !class_exists( $className, false ) )
            {
                return array( 'internal_error' => eZModuleOperationInfo::ERROR_NO_CLASS,
                              'internal_error_class_name' => $className );
            }
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

    /**
     * Helper method that keeps and returns the instances of operation objects
     * @param string $className The class the method should return an object for
     * @return $className
     * @private
     * @todo Use a static variable instead of globals
     */
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

    /// \privatesection
    public $ModuleName;
    public $FunctionList;
    public $IsValid;
    public $UseTriggers = false;

    /**
     * @var eZOperationMemento
     */
    public $Memento;
}

?>
