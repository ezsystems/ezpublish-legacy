<?php
//
// Definition of eZSetupSummary class
//
// Created on: <13-Aug-2003 17:49:28 kk>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE included in
// the packaging of this file.
//
// Licencees holding a valid "eZ publish professional licence" version 2
// may use this file in accordance with the "eZ publish professional licence"
// version 2 Agreement provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" version 2 is available at
// http://ez.no/ez_publish/licences/professional/ and in the file
// PROFESSIONAL_LICENCE included in the packaging of this file.
// For pricing of this licence please contact us via e-mail to licence@ez.no.
// Further contact information is available at http://ez.no/company/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.

//

/*! \file ezsetup_summary.php
*/
include_once( "kernel/setup/ezsetuptests.php" );

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
    function eZSetupSummary( &$tpl, &$persistenceList )
    {
        $this->Tpl =& $tpl;
        $this->PersistenceList =& $persistenceList;
    }

    /*!
    Get summary

    \return Summary
    */
    function &summary()
    {
        $databaseMap = eZSetupDatabaseMap();

        if ( isset( $this->PersistenceList['tests_run'] ) )
        {
            $checkPassed = true;
            foreach ( $this->PersistenceList['tests_run'] as $checkValue )
            {
                if ( $checkValue != 1 )
                {
                    $checkPassed = false;
                    break;
                }
            }
            if ( $checkPassed === true )
            {
                $this->Tpl->setVariable( 'system_check', 'ok' );
            }
        }

        // Image settings
        $persistenceList = $this->PersistenceList;
        if ( isset( $persistenceList['imagegd_extension'] ) && $persistenceList['imagegd_extension']['result'] )
        {
            $this->Tpl->setVariable( 'image_processor', 'ImageGD' );
        }
        else if ( isset( $persistenceList['imagemagick_program'] ) && $persistenceList['imagemagick_program']['result'] )
        {
            $this->Tpl->setVariable( 'image_processor', 'ImageMagick' );
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
        $chosenSiteTypes = false;
        if ( isset( $this->PersistenceList['chosen_site_types'] ) )
        {
            $chosenTypes = $this->PersistenceList['chosen_site_types'];
            include_once( 'kernel/setup/ezsetuptypes.php' );
            $siteTypes = eZSetupTypes();
            foreach ( $chosenTypes as $chosenType )
            {
                if ( isset( $siteTypes[$chosenType] ) )
                    $chosenSiteTypes[] = $siteTypes[$chosenType];
            }
        }

        $this->Tpl->setVariable( 'site_types', $chosenSiteTypes );

        return $this->Tpl->fetch( 'design:setup/summary.tpl' );
    }

    var $Tpl;
    var $PersistenceList;
}

?>
