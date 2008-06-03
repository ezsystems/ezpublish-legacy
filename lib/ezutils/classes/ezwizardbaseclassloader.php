<?php
//
// Definition of eZWizardBaseClassLoader class
//
// Created on: <12-Nov-2004 16:24:31 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
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

/*! \file ezwizardbaseclassloader.php
*/

/*!
  \class eZWizardBaseClassLoader ezwizardbaseclassloader.php
  \brief The class eZWizardBaseClassLoader does

*/

class eZWizardBaseClassLoader
{
    /*!
     \static
     Create new specified class
    */
    static function createClass( $tpl,
                                 $module,
                                 $stepArray,
                                 $basePath,
                                 $storageName = false,
                                 $metaData = false )
    {
        if ( !$storageName )
        {
            $storageName = 'eZWizard';
        }

        if ( !$metaData )
        {
            $http = eZHTTPTool::instance();
            $metaData = $http->sessionVariable( $storageName . '_meta' );
        }

        if ( !isset( $metaData['current_step'] ) ||
             $metaData['current_step'] < 0 )
        {
            $metaData['current_step'] = 0;
            eZDebug::writeNotice( 'Setting wizard step to : ' . $metaData['current_step'],
                                  'eZWizardBaseClassLoader::createClass()' );
        }
        $currentStep = $metaData['current_step'];

        if ( count( $stepArray ) <= $currentStep )
        {
            eZDebug::writeError( 'Invalid wizard step count: ' . $currentStep,
                                 'eZWizardBaseClassLoader::createClass()'  );
            return false;
        }

        $filePath = $basePath . $stepArray[$currentStep]['file'];
        if ( !file_exists( $filePath ) )
        {
            eZDebug::writeError( 'Wizard file not found : ' . $filePath,
                                 'eZWizardBaseClassLoader::createClass()'  );
            return false;
        }

        include_once( $filePath );

        $className = $stepArray[$currentStep]['class'];
        eZDebug::writeNotice( 'Creating class : ' . $className,
                              'eZWizardBaseClassLoader::createClass()' );
        $returnClass =  new $className( $tpl, $module, $storageName );

        if ( isset( $stepArray[$currentStep]['operation'] ) )
        {
            $operation = $stepArray[$currentStep]['operation'];
            return $returnClass->$operation();
            eZDebug::writeNotice( 'Running : "' . $className . '->' . $operation . '()". Specified in StepArray',
                                  'eZWizardBaseClassLoader::createClass()' );
        }

        if ( isset( $metaData['current_stage'] ) )
        {
            $returnClass->setMetaData( 'current_stage', $metaData['current_stage'] );
            eZDebug::writeNotice( 'Setting wizard stage to : ' . $metaData['current_stage'],
                                  'eZWizardBaseClassLoader::createClass()' );
        }

        return $returnClass;
    }
}

?>
