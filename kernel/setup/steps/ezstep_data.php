<?php
//
// eZSetup - init part initialization
//
// Created on: <08-Nov-2002 11:00:54 kd>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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


class eZStepData
{

    /*!
     Constructor
    */
    function eZStepData()
    {
    }

    /*!
      Get file and class info for specified step

      \param step number or
             step name
      \return array containing file name and class name
    */
    function &step( $description )
    {
        if ( is_string( $description ) )
        {
            foreach (  $this->StepTable as $step )
            {
                if ( $step['class'] == $description )
                {
                    return $step;
                }
            }
        }
        else if ( is_int( $description ) )
        {
            if ( isset( $this->StepTable[$description] ) )
            {
                return $this->StepTable[$description];
            }
        }

        return null;
    }

    /*!
     Get nest install step from step array

     \param current step

     \return next step
    */
    function &nextStep( &$step )
    {
        foreach ( $this->StepTable as $key => $tableStep )
        {
            if ( $step['class'] == $tableStep['class'] )
            {
                return $this->StepTable[++$key];
            }
        }
        return null;
    }

    /*!
     Get previous install step from step array

     \param current step

     \return previous step
    */
    function &previousStep( &$step )
    {
        if ( is_string( $step ) ){
            foreach ( $this->StepTable as $key => $tableStep )
            {
                if ( $step == $tableStep['class'] )
                {
                    return $this->StepTable[--$key];
                }
            }
        }
        else
        {
            foreach ( $this->StepTable as $key => $tableStep )
            {
                if ( $step['class'] == $tableStep['class'] )
                {
                    return $this->StepTable[--$key];
                }
            }
        }
        return null;
    }

    /*!
     Get setup progress in percent of total number of steps

     \param current step

     \return Percentage of completet setup, step 1 => 0%, final step => 100%
    */
    function progress( &$step )
    {
        $totalSteps = 0;
        foreach ( $this->StepTable as $tableStep )
        {
            if ( !isset( $tableStep['count_step'] ) or
                 $tableStep['count_step'] )
                ++$totalSteps;
        }

        $currentStep = 0;
        foreach ( $this->StepTable as $key => $tableStep )
        {
            if ( $step['class'] == $tableStep['class'] )
            {
                break;
            }
            else if ( !isset( $tableStep['count_step'] ) or
                      $tableStep['count_step'] )
                ++$currentStep;
        }

        return (int) ( $currentStep * 100 / ( $totalSteps - 1 ) );
    }

    var $StepTable = array( array( 'file' => 'welcome',
                                   'class' => 'Welcome' ),
                            array( 'file' => 'system_check',
                                   'class' => 'SystemCheck' ),
                            array( 'file' => 'system_finetune',
                                   'class' => 'SystemFinetune' ),
                            array( 'file' => 'email_settings',
                                   'class' => 'EmailSettings' ),
                            array( 'file' => 'database_choice',
                                   'class' => 'DatabaseChoice' ),
                            array( 'file' => 'database_init',
                                   'class' => 'DatabaseInit' ),
                            array( 'file' => 'language_options',
                                   'class' => 'LanguageOptions' ),
                            array( 'file' => 'site_types',
                                   'class' => 'SiteTypes'),
                            array( 'file' => 'site_packages',
                                   'class' => 'SitePackages' ),
                            array( 'file' => 'site_access',
                                   'class' => 'SiteAccess'),
                            array( 'file' => 'site_details',
                                   'class' => 'SiteDetails'),
                            array( 'file' => 'security',
                                   'class' => 'Security' ),
                            array( 'file' => 'registration',
                                   'class' => 'Registration' ),
                            array( 'file' => 'create_sites',
                                   'class' => 'CreateSites',
                                   'count_step' => false ),
                            array( 'file' => 'final',
                                   'class' => 'Final') );

}

?>
