<?php
/**
 * File containing the eZOperationHandler class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZOperationHandler ezoperationhandler.php
  \brief The class eZOperationHandler does

*/

class eZOperationHandler
{
    /*!
     Constructor
    */
    function eZOperationHandler()
    {
    }

    /**
     * Factory for modules' moduleOperationInfo objects.
     *
     * @param string $moduleName
     * @param bool $useTriggers*
     *
     * @return eZModuleOperationInfo
     */
    static function moduleOperationInfo( $moduleName, $useTriggers = true )
    {
        if ( !isset( $GLOBALS['eZGlobalModuleOperationList'] ) )
        {
            $GLOBALS['eZGlobalModuleOperationList'] = array();
        }
        if ( isset( $GLOBALS['eZGlobalModuleOperationList'][$moduleName] ) )
        {
            return $GLOBALS['eZGlobalModuleOperationList'][$moduleName];
        }
        $moduleOperationInfo = new eZModuleOperationInfo( $moduleName, $useTriggers );
        $moduleOperationInfo->loadDefinition();
        return $GLOBALS['eZGlobalModuleOperationList'][$moduleName] = $moduleOperationInfo;
    }

    static function execute( $moduleName, $operationName, $operationParameters, $lastTriggerName = null, $useTriggers = true )
    {
        $moduleOperationInfo = eZOperationHandler::moduleOperationInfo( $moduleName, $useTriggers );
        if ( !$moduleOperationInfo->isValid() )
        {
            eZDebug::writeError( "Cannot execute operation '$operationName' in module '$moduleName', no valid data", __METHOD__ );
            return null;
        }
        return $moduleOperationInfo->execute( $operationName, $operationParameters, $lastTriggerName, $useTriggers );
    }

    /**
     * Checks if a trigger is defined in worklow.ini/[OperationSettings]/AvailableOperations
     *
     * @param string $name
     * @return boolean true if the operation is available, false otherwise
     */
    static public function operationIsAvailable( $name )
    {
        $workflowINI = eZINI::instance( 'workflow.ini' );
        $operationList = $workflowINI->variableArray( 'OperationSettings', 'AvailableOperations' );
        $operationList = array_unique( array_merge( $operationList, $workflowINI->variable( 'OperationSettings', 'AvailableOperationList' ) ) );

        return in_array( $name, $operationList ) || in_array( "before_{$name}", $operationList ) || in_array( "after_{$name}", $operationList );
    }

}

?>
