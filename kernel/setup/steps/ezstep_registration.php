<?php
//
// Definition of eZStepRegistration class
//
// Created on: <13-Aug-2003 11:17:34 kk>
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

/*! \file ezstep_registration.php
*/
include_once( 'kernel/setup/steps/ezstep_installer.php');
include_once( "kernel/setup/ezsetuptests.php" );
include_once( "kernel/common/i18n.php" );

/*!
  \class eZStepRegistration ezstep_registration.php
  \brief The class eZStepRegistration does

*/

class eZStepRegistration extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepRegistration( &$tpl, &$http, &$ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList );
    }

    /*!
     \reimp
    */
    function processPostData()
    {
        if ( !$this->Http->hasPostVariable( 'eZSetupSendRegistration' ) )// skip site registration
        {
            return true;
        }

        include_once( 'lib/ezutils/classes/ezmail.php' );
        include_once( 'lib/ezutils/classes/ezmailtransport.php' );

        $databaseMap = eZSetupDatabaseMap();
        $databaseInfo = $this->PersistenceList['database_info'];
        $databaseInfo['info'] = $databaseMap[$databaseInfo['type']];
        $regionalInfo = $this->PersistenceList['regional_info'];
        if ( !isset( $regionalInfo['languages'] ) )
            $regionalInfo['languages'] = array();
        $demoData = $this->PersistenceList['demo_data'];
        $emailInfo = $this->PersistenceList['email_info'];

        $siteTemplates = array();
        $siteTypes = $this->chosenSiteTypes();
        $counter = 0;
        foreach ( array_keys( $siteTypes ) as $siteTypeKey )
        {
            $siteType =& $siteTypes[$siteTypeKey];
            $siteTemplates[$counter] = $siteType;
            $url = $siteTemplates[$counter]['url'];
            if ( !preg_match( "#^[a-zA-Z0-9]+://(.*)$#", $url ) )
            {
                $url = 'http://' . $url;
            }
            $currentURL = $url;
            $adminURL = $url;
            if ( $siteTemplates[$counter]['access_type'] == 'url' )
            {
                $url .= '/' . $siteTemplates[$counter]['access_type_value'];
                $adminURL .= '/' . $siteTemplates[$counter]['admin_access_type_value'];
            }
            else if ( $siteTemplates[$counter]['access_type'] == 'hostname' )
            {
                $url = eZHTTPTool::createRedirectURL( $currentURL, array( 'host' => $siteTemplates[$counter]['access_type_value'] ) );
                $adminURL = eZHTTPTool::createRedirectURL( $currentURL, array( 'host' => $siteTemplates[$counter]['admin_access_type_value'] ) );
            }
            else if ( $siteTemplates[$counter]['access_type'] == 'port' )
            {
                $url = eZHTTPTool::createRedirectURL( $currentURL, array( 'port' => $siteTemplates[$counter]['access_type_value'] ) );
                $adminURL = eZHTTPTool::createRedirectURL( $currentURL, array( 'port' => $siteTemplates[$counter]['admin_access_type_value'] ) );
            }
            $siteTemplates[$counter]['url'] = $url;
            $siteTemplates[$counter]['admin_url'] = $adminURL;
            ++$counter;
        }

        $testsRun = $this->PersistenceList['tests_run'];
        $imageMagickProgram = $this->PersistenceList['imagemagick_program'];
        $imageGDExtension = $this->PersistenceList['imagegd_extension'];
        $phpVersion = $this->PersistenceList['phpversion'];
        $webserverInfo = false;
        if ( function_exists( 'apache_get_version' ) )
        {
            $webserverInfo = array( 'version' => apache_get_version() );
        }

        $optionalTests = eZSetupOptionalTests();
        $testTable = eZSetupTestTable();

        $arguments = array();
        $runResult = eZSetupRunTests( $optionalTests, $arguments, 'eZSetup:init:send_registration', $this->PersistenceList );
        $testResults = $runResult['results'];
        $testResult = $runResult['result'];
        $successCount = $runResult['success_count'];
        $persistenceData = $runResult['persistence_list'];

        $mail = new eZMail();
        $mail->setReceiver( 'registerezsite@ez.no', 'eZ Site Registration' );
        $mail->setSender( 'registerezsite@ez.no' );

        // Send e-mail
        include_once( 'kernel/common/template.php' );
        $mailTpl =& templateInit( 'email' );

        $comments = false;
        if ( $this->Http->hasPostVariable( 'eZSetupRegistrationComment' ) )
        {
            $comments = $this->Http->postVariable( 'eZSetupRegistrationComment' );
        }

        $mailTpl->setVariable( 'comments', $comments );
        $mailTpl->setVariable( 'database_info', $databaseInfo );
        $mailTpl->setVariable( 'regional_info', $regionalInfo );
        $mailTpl->setVariable( 'demo_data', $demoData );
        $mailTpl->setVariable( 'email_info', $emailInfo );
        $mailTpl->setVariable( 'site_templates', $siteTemplates );
        $mailTpl->setVariable( 'tests_run', $testsRun );
        $mailTpl->setVariable( 'imagemagick_program', $imageMagickProgram );
        $mailTpl->setVariable( 'imagegd_extension', $imageGDExtension );
        $mailTpl->setVariable( 'phpversion', $phpVersion );
        $mailTpl->setVariable( 'webserver', $webserverInfo );
        $mailTpl->setVariable( 'os', array( 'name' => php_uname() ) );
        $mailTpl->setVariable( 'optional_tests', $testResults );
        include_once( 'lib/version.php' );
        $mailTpl->setVariable( "version", array( "text" => eZPublishSDK::version(),
                                                 "major" => eZPublishSDK::majorVersion(),
                                                 "minor" => eZPublishSDK::minorVersion(),
                                                 "release" => eZPublishSDK::release() ) );

        $bodyText =& $mailTpl->fetch( 'design:setup/registration_email.tpl' );

        $subject =& $mailTpl->variable( 'subject' );
        $mail->setSubject( $subject );
        $mail->setBody( $bodyText );
        $mailResult = eZMailTransport::send( $mail );

        $this->PersistenceList['email_info']['sent'] = true;
        $this->PersistenceList['email_info']['result'] = $mailResult;

        return true; // Always continue
    }

    /*!
     \reimp
     */
    function init()
    {
        return false; // Always display registration information
    }

    /*!
     \reimp
    */
    function &display()
    {
        // generate email ti display
        include_once( 'lib/ezutils/classes/ezmail.php' );
        include_once( 'lib/ezutils/classes/ezmailtransport.php' );

        $databaseMap = eZSetupDatabaseMap();
        $databaseInfo = $this->PersistenceList['database_info'];
        $databaseInfo['info'] = $databaseMap[$databaseInfo['type']];
        $regionalInfo = $this->PersistenceList['regional_info'];
        if ( !isset( $regionalInfo['languages'] ) )
            $regionalInfo['languages'] = array();
        $demoData = $this->PersistenceList['demo_data'];
        $emailInfo = $this->PersistenceList['email_info'];

        $siteTemplates = array();
        $siteTypes = $this->chosenSiteTypes();
        $counter = 0;
        foreach ( array_keys( $siteTypes ) as $siteTypeKey )
        {
            $siteType =& $siteTypes[$siteTypeKey];
            $siteTemplates[$counter] = $siteType;

            $typeFunctionality = eZSetupFunctionality( $siteType['identifier'] );
            $extraFunctionality = array_merge( $this->PersistenceList['additional_packages'],
                                               $typeFunctionality['required'] );
            $extraFunctionality = array_unique( $extraFunctionality );

            $url = $siteTemplates[$counter]['url'];
            if ( !preg_match( "#^[a-zA-Z0-9]+://(.*)$#", $url ) )
            {
                $url = 'http://' . $url;
            }
            $currentURL = $url;
            $adminURL = $url;
            if ( $siteTemplates[$counter]['access_type'] == 'url' )
            {
                $url .= '/' . $siteTemplates[$counter]['access_type_value'];
                $adminURL .= '/' . $siteTemplates[$counter]['admin_access_type_value'];
            }
            else if ( $siteTemplates[$counter]['access_type'] == 'hostname' )
            {
                $url = eZHTTPTool::createRedirectURL( $currentURL, array( 'host' => $siteTemplates[$counter]['access_type_value'] ) );
                $adminURL = eZHTTPTool::createRedirectURL( $currentURL, array( 'host' => $siteTemplates[$counter]['admin_access_type_value'] ) );
            }
            else if ( $siteTemplates[$counter]['access_type'] == 'port' )
            {
                $url = eZHTTPTool::createRedirectURL( $currentURL, array( 'port' => $siteTemplates[$counter]['access_type_value'] ) );
                $adminURL = eZHTTPTool::createRedirectURL( $currentURL, array( 'port' => $siteTemplates[$counter]['admin_access_type_value'] ) );
            }
            $siteTemplates[$counter]['url'] = $url;
            $siteTemplates[$counter]['admin_url'] = $adminURL;
            $siteTemplates[$counter]['extra_functionality'] = $extraFunctionality;
            ++$counter;
        }

        $testsRun = $this->PersistenceList['tests_run'];
        $imageMagickProgram = $this->PersistenceList['imagemagick_program'];
        $imageGDExtension = $this->PersistenceList['imagegd_extension'];
        $phpVersion = $this->PersistenceList['phpversion'];
        $webserverInfo = false;
        if ( function_exists( 'apache_get_version' ) )
        {
            $webserverInfo = array( 'version' => apache_get_version() );
        }

        $optionalTests = eZSetupOptionalTests();
        $testTable = eZSetupTestTable();

        $arguments = array();
        $runResult = eZSetupRunTests( $optionalTests, $arguments, 'eZSetup:init:send_registration', $this->PersistenceList );
        $testResults = $runResult['results'];
        $testResult = $runResult['result'];
        $successCount = $runResult['success_count'];
        $persistenceData = $runResult['persistence_list'];

        $mail = new eZMail();
        $mail->setReceiver( 'registerezsite@ez.no', 'eZ Site Registration' );
        $mail->setSender( 'registerezsite@ez.no' );

        // Send e-mail
        include_once( 'kernel/common/template.php' );
        $mailTpl =& templateInit( 'email' );
        $mailTpl->setVariable( 'database_info', $databaseInfo );
        $mailTpl->setVariable( 'regional_info', $regionalInfo );
        $mailTpl->setVariable( 'demo_data', $demoData );
        $mailTpl->setVariable( 'email_info', $emailInfo );
        $mailTpl->setVariable( 'site_templates', $siteTemplates );
        $mailTpl->setVariable( 'tests_run', $testsRun );
        $mailTpl->setVariable( 'imagemagick_program', $imageMagickProgram );
        $mailTpl->setVariable( 'imagegd_extension', $imageGDExtension );
        $mailTpl->setVariable( 'phpversion', $phpVersion );
        $mailTpl->setVariable( 'webserver', $webserverInfo );
        $mailTpl->setVariable( 'os', array( 'name' => php_uname() ) );
        $mailTpl->setVariable( 'optional_tests', $testResults );
        include_once( 'lib/version.php' );
        $mailTpl->setVariable( "version", array( "text" => eZPublishSDK::version(),
                                                 "major" => eZPublishSDK::majorVersion(),
                                                 "minor" => eZPublishSDK::minorVersion(),
                                                 "release" => eZPublishSDK::release() ) );

        $bodyText =& $mailTpl->fetch( 'design:setup/registration_email.tpl' );

        $this->Tpl->setVariable( 'email_body', $bodyText );

        $this->Tpl->setVariable( 'setup_previous_step', 'Registration' );
        $this->Tpl->setVariable( 'setup_next_step', 'DatabaseCreate' );

        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( "design:setup/init/registration.tpl" );
        $result['path'] = array( array( 'text' => ezi18n( 'design/standard/setup/init',
                                                          'Registration' ),
                                        'url' => false ) );
        return $result;

    }
}

?>
