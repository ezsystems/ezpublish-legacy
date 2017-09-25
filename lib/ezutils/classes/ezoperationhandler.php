<?php
/**
 * File containing the eZOperationHandler class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package lib
 */

/*!
  \class eZOperationHandler ezoperationhandler.php
  \brief The class eZOperationHandler does

*/

class eZOperationHandler
{
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
        return $moduleOperationInfo->execute( $operationName, $operationParameters, $lastTriggerName );
    }

    /**
     * Checks if a trigger is defined in worklow.ini/[OperationSettings]/AvailableOperationList
     *
     * @param string $name
     * @return boolean true if the operation is available, false otherwise
     */
    static public function operationIsAvailable( $name )
    {
        $operationList = array_unique(
            eZINI::instance( 'workflow.ini' )->variable( 'OperationSettings', 'AvailableOperationList' )
        );

        return in_array( $name, $operationList ) || in_array( "before_{$name}", $operationList ) || in_array( "after_{$name}", $operationList );
    }

}

?>
