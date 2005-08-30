<?php
//
// Definition of eZWizardBase class
//
// Created on: <12-Nov-2004 16:24:31 kk>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
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
    function eZWizardBase( &$tpl, &$module, $storageName = false )
    {
        if ( $storageName )
        {
            $this->StorageName = $storageName;
        }

        $this->TPL =& $tpl;
        $this->Module =& $module;
        $this->HTTP =& eZHTTPTool::instance();
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
    function &attribute( $attr )
    {
        switch( $attr )
        {
            case 'error_count':
            {
                $retValue = count( $this->ErrorList );
            } break;

            case 'error_list':
            {
                return $this->ErrorList;
            } break;

            case 'warning_count':
            {
                $retValue = count( $this->WarningList );
            } break;

            case 'warning_list':
            {
                return $this->WarningList;
            } break;

            case 'step_template':
            {
                $retValue = $this->stepTemplate();
            } break;

            case 'variable_list':
            {
                $retValue = $this->variableList();
            } break;

            case 'url':
            {
                return $this->WizardURL;
            } break;
            default:
            {
                eZDebug::writeError( "Attribute '$attr' does not exist", 'eZWizardBase::attribute' );
                $retValue = null;
            }
            break;
        }
        return $retValue;
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
    var $ErrorList = array();
    var $WarningList = array();

    /* Step list, used to determine wizard steps */
    var $StepList = array();

    var $HTTP;
    var $Tpl;
    var $Module;
    var $WizardURL = ''; /* url to wizard */

    /* Array used to store wizzard values */
    var $VariableList = array();
    var $MetaData = array();
    var $StorageName = 'eZWizard';
    var $MetaDataName = '_meta';
    var $VariableListName = '_data';

    /* Step templates */
    var $StepTemplateBase = 'design:wizard/step';

    /* Array containing the wizard steps */
    var $StepArray = array();
}

class eZWizardBaseClassLoader
{
    /*!
     \static
     Create new specified class
    */
    function createClass( &$tpl,
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
            $http =& eZHTTPTool::instance();
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

        if ( isset( $stepArray['operation'] ) )
        {
            $operation = $stepArray['operation'];
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
