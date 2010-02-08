<?php
//
// Definition of eZSetupSummary class
//
// Created on: <13-Aug-2003 17:49:28 kk>
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

/*! \file
*/
// include_once( "kernel/setup/ezsetuptests.php" );

/*!
  \class eZSetupSummary ezsetup_summary.php
  \brief The class eZSetupSummary does

*/

class eZSetupSummary
{
    /*!
     Constructor

     Create new object for generating summary

     \param template
     \param persistence list
    */
    function eZSetupSummary( $tpl, &$persistenceList )
    {
        $this->Tpl =& $tpl;
        $this->PersistenceList =& $persistenceList;
    }

    /*!
    Get summary

    \return Summary
    */
    function summary()
    {
        $databaseMap = eZSetupDatabaseMap();

        $persistenceList = $this->PersistenceList;

        if ( isset( $persistenceList['tests_run'] ) and
             count( $persistenceList['tests_run'] ) > 0 )
        {
            $checkPassed = true;
            foreach ( $persistenceList['tests_run'] as $checkValue )
            {
                if ( $checkValue != 1 )
                {
                    $checkPassed = false;
                    break;
                }
            }
            if ( $checkPassed === true )
                $this->Tpl->setVariable( 'system_check', 'ok' );
            else
                $this->Tpl->setVariable( 'system_check', '' );
        }
        else
        {
            $this->Tpl->setVariable( 'system_check', '' );
        }

        // Image settings
        if ( isset( $persistenceList['imagemagick_program'] ) && $persistenceList['imagemagick_program']['result'] )
        {
            $this->Tpl->setVariable( 'image_processor', 'ImageMagick' );
        }
        else if ( isset( $persistenceList['imagegd_extension'] ) && $persistenceList['imagegd_extension']['result'] )
        {
            $this->Tpl->setVariable( 'image_processor', 'ImageGD' );
        }
        else
        {
            $this->Tpl->setVariable( 'image_processor', '' );
        }

        // Database selected
        if ( isset( $persistenceList['database_info'] ) ) {
            $database = $databaseMap[$persistenceList['database_info']['type']]['name'];
            $this->Tpl->setVariable( 'database', $database );
        }
        else
        {
            $this->Tpl->setVariable( 'database', '' );
        }

        // Languages selected
        if ( isset( $persistenceList['regional_info'] ) ) {
            $languages = $persistenceList['regional_info']['languages'];
            $this->Tpl->setVariable( 'languages', $languages );
        }
        else
        {
            $this->Tpl->setVariable( 'languages', '' );
        }

        // Email settings
        $this->Tpl->setVariable( 'summary_email_info', '' );

        if ( isset( $persistenceList['email_info'] ) ) {
            if ( $persistenceList['email_info']['type'] == 1 )
            {
                $this->Tpl->setVariable( 'summary_email_info', 'sendmail' );
            }
            else if ( $persistenceList['email_info']['type'] == 2 )
            {
                $this->Tpl->setVariable( 'summary_email_info', 'SMTP' );
            }
        }

        // Templates chosen
        $chosenSitePackage = false;
        if ( isset( $this->PersistenceList['chosen_site_package']['0'] ) )
        {
            $chosenSitePackage = $this->PersistenceList['chosen_site_package']['0'];
        }

        $this->Tpl->setVariable( 'site_package', $chosenSitePackage );

        return $this->Tpl->fetch( 'design:setup/summary.tpl' );
    }

    public $Tpl;
    public $PersistenceList;
}

?>
