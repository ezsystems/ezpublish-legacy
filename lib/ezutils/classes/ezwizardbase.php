<?php
//
// Definition of eZWizardBase class
//
// Created on: <12-Nov-2004 16:24:31 kk>
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

/*! \file ezwizardbase.php
*/

/*!
  \class eZWizardBase ezwizardbase.php
  \brief The class eZWizardBase does

*/

define( 'EZ_WIZARD_STAGE_PRE', 0 );
define( 'EZ_WIZARD_STAGE_POST', 1 );

class eZWizardBase
{
    /*!
     Constructor

     \param Template class
     \param Module
     \param Storage Name, optional.
    */
    function eZWizardBase( $tpl, &$module, $storageName = false )
    {
        if ( $storageName )
        {
            $this->StorageName = $storageName;
        }

        $this->TPL = $tpl;
        $this->Module = $module;
        $this->HTTP = eZHTTPTool::instance();
        $this->VariableList = $this->HTTP->sessionVariable( $this->StorageName . $this->VariableListName );
        $this->MetaData = $this->HTTP->sessionVariable( $this->StorageName . $this->MetaDataName );

        $this->initialize();
    }

    /*!
     Set needed variables.
    */
    function initialize()
    {
        if ( !$this->hasMetaData( 'current_step' ) )
        {
            $this->setMetaData( 'current_step', 0 );
        }

        if ( !$this->hasMetaData( 'current_stage' ) )
        {
            $this->setMetaData( 'current_stage', EZ_WIZARD_STAGE_PRE );
        }
    }

    /*!
     \reimp
    */
    function attributes()
    {
        return array( 'error_count',
                      'error_list',
                      'warning_count',
                      'warning_list',
                      'step_template',
                      'variable_list',
                      'url' );
    }

    /*!
     \reimp
    */
    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    /*!
     \reimp
    */
    function attribute( $attr )
    {
        switch( $attr )
        {
            case 'error_count':
            {
                return count( $this->ErrorList );
            } break;

            case 'error_list':
            {
                return $this->ErrorList;
            } break;

            case 'warning_count':
            {
                return count( $this->WarningList );
            } break;

            case 'warning_list':
            {
                return $this->WarningList;
            } break;

            case 'step_template':
            {
                return $this->stepTemplate();
            } break;

            case 'variable_list':
            {
                return $this->variableList();
            } break;

            case 'url':
            {
                return $this->WizardURL;
            } break;

            default:
            {
                eZDebug::writeError( "Attribute '$attr' does not exist", 'eZWizardBase::attribute' );
                return null;
            }
            break;
        }
    }

    /*!
     Will run the wizard, and continue from the current step.
     This method will run postCheck, redirect to next, etc. depending on the current state.

     return Module Result or module redirect.
    */
    function run()
    {
        if ( $this->HTTP->hasPostVariable( 'PreviousButton' ) )
        {
            return $this->previousStep();
        }

        switch( $this->metaData( 'current_stage' ) )
        {
            case EZ_WIZARD_STAGE_PRE:
            {
                $this->preCheck();
                $this->nextStep();
                if ( $this->skip() )
                {
                    return $this->nextStep();
                }
                return $this->process();
            } break;

            case EZ_WIZARD_STAGE_POST:
            {
                if ( $this->postCheck() )
                {
                    return $this->nextStep();
                }
                else
                {
                    return $this->process();
                }
            } break;
        }
    }

    /*!
     \private
     \virtual
     Pre check current step to check that it's safe to execute current step.
     Return false if current step should not be processed, and set warning message

     \return true if everything ok
             false if not
    */
    function preCheck()
    {
        return true;
    }

    /*!
     \private
     \virtual
     Post check current step to check that it's safe to continue to next step.
     Return false if current step should be processed once again, and set warning message

     \return true if everything ok
             false if not
    */
    function postCheck()
    {
        if ( $this->HTTP->hasPostVariable( 'NextButton' ) )
        {
            return true;
        }

        return false;
    }

    /*!
     \private
     \virtual
     Return true to skip current step.
     Current step will not be processed.

     \return true skip current step.
             false - perform current step.
    */
    function skip()
    {
        return false;
    }

    /*!
     \virtual
     Process the current step, and present the HTML.

     \return Module Result
    */
    function process()
    {
    }

    /*!
     Store meta data.

     \param key
     \param value
    */
    function setMetaData( $key, $value )
    {
        $this->MetaData[$key] = $value;
        eZDebug::writeNotice( 'Set MetaData : [' . $key . '] = ' . $value,
                              'eZWizardBase::setMetaData()' );
        $this->savePersistentData();
    }

    /*!
     Get metadata

    */
    function metaData( $key )
    {
        return $this->MetaData[$key];
    }

    /*!
     Check if has metadata value
    */
    function hasMetaData( $key )
    {
        return isset( $this->MetaData[$key] );
    }

    /*!
     Store variable. Variable/value will be available in current and next wizard steps.

     \param key
     \param value
    */
    function setVariable( $key, $value )
    {
        $this->VariableList[$key] = $value;
        $this->savePersistentData();
    }

    /*!
     Get stored wizard values.

     \param key

     \return value
    */
    function &variable( $key )
    {
        if ( isset( $this->VariableList[$key] ) )
            $retValue = $this->VariableList[$key];
        else
            $retValue = false;
        return $retValue;
    }

    /*!
     Check if wizard variable exists

     \param variable key

     \return variable value
    */
    function hasVariable( $key )
    {
        return isset( $this->VariableList[$key] );
    }

    /*!
     Return variable list.

     \return variable list
    */
    function variableList()
    {
        return $this->VariableList;
    }

    /*!
     \private
     Get Step template name.

     \return current step template
    */
    function stepTemplate()
    {
        return $this->StepTemplateBase . '_' . ( $this->metaData( 'current_step' ) + 1 ) . '.tpl';
    }

    /*!
     Cleanup variables used during wizard
    */
    function cleanup()
    {
        $this->HTTP->removeSessionVariable( $this->StorageName . $this->MetaDataName );
        $this->HTTP->removeSessionVariable( $this->StorageName . $this->VariableListName );
        $this->MetaData = array();
        $this->VariableList = array();
    }

    /*!
     Go back to previous step
    */
    function previousStep()
    {
        $this->setMetaData( 'current_stage', EZ_WIZARD_STAGE_PRE );
        $this->setMetaData( 'current_step', $this->metaData( 'current_step' ) - 1 );
        $this->savePersistentData();

        return $this->Module->redirectTo( $this->WizardURL );
    }

    /*!
     Increate Step counter
    */
    function nextStep()
    {
        if ( $this->metaData( 'current_stage' ) == EZ_WIZARD_STAGE_PRE )
        {
            $this->setMetaData( 'current_stage', EZ_WIZARD_STAGE_POST );
        }
        else
        {
            $this->setMetaData( 'current_stage', EZ_WIZARD_STAGE_PRE );
            $this->setMetaData( 'current_step', $this->metaData( 'current_step' ) + 1 );

            return $this->Module->redirectTo( $this->WizardURL );
        }

        $this->savePersistentData();
    }

    /*!
     Save persistent data
    */
    function savePersistentData()
    {
        $this->HTTP->setSessionVariable( $this->StorageName . $this->MetaDataName, $this->MetaData );
        $this->HTTP->setSessionVariable( $this->StorageName . $this->VariableListName, $this->VariableList );
    }

    /* Private messages */
    public $ErrorList = array();
    public $WarningList = array();

    /* Step list, used to determine wizard steps */
    public $StepList = array();

    public $HTTP;
    public $Tpl;
    public $Module;
    public $WizardURL = ''; /* url to wizard */

    /* Array used to store wizzard values */
    public $VariableList = array();
    public $MetaData = array();
    public $StorageName = 'eZWizard';
    public $MetaDataName = '_meta';
    public $VariableListName = '_data';

    /* Step templates */
    public $StepTemplateBase = 'design:wizard/step';

    /* Array containing the wizard steps */
    public $StepArray = array();
}

class eZWizardBaseClassLoader
{
    /*!
     \static
     Create new specified class
    */
    function createClass( $tpl,
                          &$module,
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
