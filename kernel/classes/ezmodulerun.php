<?php
//
// Definition of eZModuleRun class
//
// Created on: <30-Сен-2002 13:48:31 sp>
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

/*! \file ezmodulerun.php
*/

/*!
  \class eZModuleRun ezmodulerun.php
  \brief The class eZModuleRun does

*/
include_once( "lib/ezutils/classes/ezmodule.php" );

class eZModuleRun extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZModuleRun( $row )
    {
        $this->eZPersistentObject( $row );
    }
    function &definition()
    {
        return array( 'fields' => array( 'id' => 'ID',
                                         'workflow_process_id' => 'WorkflowProcessID',
                                         'module_name' => 'ModuleName',
                                         'function_name' => 'FunctionName',
                                         'module_data' => 'ModuleData'
                                         ),
                      'keys' => array( 'id' ),
                      "increment_key" => "id",
                      'class_name' => 'eZModuleRun',
                      'name' => 'ezmodule_run' );
    }

    function &create( $workflowProcessID )
    {
        return new eZModuleRun( array( 'workflow_process_id' => $workflowProcessID ) );
    }

    function &fetch ( $workflowProcessID, $as_object = true )
    {
        return eZPersistentObject::fetchObject( eZModuleRun::definition(),
                                                null,
                                                array( "workflow_process_id" => $workflowProcessID ),
                                                $as_object );
    }

    function &createFromModule( $workflowProcessID, $module = false )
    {
        $dataArray = array();
//        var_dump( $module );

        if ( !$module )
            $module =& eZModule::currentModule();
//        var_dump( $module );
        $dataArray['ModuleName'] = $module->attribute( 'name' );
        $dataArray['ViewName'] = eZModule::currentView();
//        eZDebug::writeNotice($module, "module" );
        $dataArray['ViewParams'] = $module->ViewParameters;

        $dataArray['CurrentAction'] = $module->currentAction();
        $http =& eZHTTPTool::instance();
        if ( isset( $module->Functions[$view]['post_action_parameters'] ) )
        {
            $postParameters =& $this->Functions[$view]['post_action_parameters'][$currentAction];
            foreach ( array_keys( $postParameters ) as $parameterName )
            {
                if ( $module->hasActionParameter( $parameterName ) )
                    $module->setActionParameter( $parameterName, $module->actionParameter( $parameterName ) );
            }
        }
        $dataArray['ActionParameters'] = $module->ViewActionParameters[ $dataArray['ViewName'] ];
        $moduleRun =& eZModuleRun::create( $workflowProcessID );
        $moduleRun->setAttribute( 'module_name', $dataArray['ModuleName'] );
        $moduleRun->setAttribute( 'function_name', $dataArray[ 'ViewName' ] );
        $moduleRun->setAttribute( 'module_data', serialize( $dataArray ) );
        $moduleRun->store();
    }
    function runFromDB( $workflowProcessID )
    {
        $moduleRun =& eZModuleRun::fetch( $workflowProcessID );
//        var_dump($moduleRun);
        $module =& eZModule::exists( $moduleRun->attribute( 'module_name' ) );
        $moduleData = unserialize ( $moduleRun->attribute( 'module_data' ) );
        $currentView =& $GLOBALS['eZModuleCurrentView'];
        $functionName = $moduleRun->attribute( 'function_name' );

        $currentView = array( 'view' => $functionName,
                              'module' => $moduleRun->attribute( 'module_name' ) );
//        var_dump( $module );
        $module->setCurrentAction( $moduleData['CurrentAction'] );
        $module->ViewActionParameters[ $moduleData['ViewName'] ] == $moduleData['ActionParameters'];
//        var_dump($moduleData);
        $module->run( $moduleData['ViewName'], $moduleData['ViewParams'] );

    }
}

?>
