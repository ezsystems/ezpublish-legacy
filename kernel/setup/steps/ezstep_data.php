<?php
//
// eZSetup - init part initialization
//
// Created on: <08-Nov-2002 11:00:54 kd>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

/*!
  \class eZStepData ezstep_data.php
  \brief Encapsulates information on all steps

*/

class eZStepData
{
    function eZStepData( )
    {
    }

    /*!
      \static
      Get file and class info for specified step

      \param step number or
             step name
      \return array containing file name and class name
    */
    function step( $description )
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
        $retValue = null;
        return $retValue;
    }

    /*!
     \static
     Get nest install step from step array

     \param current step

     \return next step
    */
    function nextStep( $step )
    {
        foreach ( $this->StepTable as $key => $tableStep )
        {
            if ( $step['class'] == $tableStep['class'] )
            {
                return $this->StepTable[++$key];
            }
        }
        $retValue = null;
        return $retValue;
    }

    /*!
     \static
     Get previous install step from step array

     \param current step

     \return previous step
    */
    function previousStep( $step )
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
        $retValue = null;
        return $retValue;
    }

    /*!
     \static
     Get setup progress in percent of total number of steps

     \param current step

     \return Percentage of completet setup, step 1 => 0%, final step => 100%
    */
    function progress( $step )
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

    /// \privatesection
    /// Array contain all steps in the setup wizard
    public $StepTable = array( array( 'file' => 'welcome',
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
                           // array( 'file' => 'site_packages',
                           //        'class' => 'SitePackages' ),
                            array( 'file' => 'package_language_options',
                                   'class' => 'PackageLanguageOptions' ),
                            array( 'file' => 'site_access',
                                   'class' => 'SiteAccess'),
                            array( 'file' => 'site_details',
                                   'class' => 'SiteDetails'),
                            array( 'file' => 'site_admin',
                                   'class' => 'SiteAdmin'),
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
