<?php
//
// Definition of eZOperationHandler class
//
// Created on: <06-Oct-2002 16:25:10 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

/*! \file ezoperationhandler.php
*/

/*!
  \class eZOperationHandler ezoperationhandler.php
  \brief The class eZOperationHandler does

*/

//include_once( 'lib/ezutils/classes/ezmoduleoperationinfo.php' );

class eZOperationHandler
{
    /*!
     Constructor
    */
    function eZOperationHandler()
    {
    }

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
            eZDebug::writeError( "Cannot execute operation '$operationName' in module '$moduleName', no valid data",
                                  'eZOperationHandler::execute' );
            return null;
        }
        return $moduleOperationInfo->execute( $operationName, $operationParameters, $lastTriggerName, $useTriggers );
    }
}

?>
