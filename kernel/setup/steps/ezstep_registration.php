<?php
//
// Definition of eZStepRegistration class
//
// Created on: <13-Aug-2003 11:17:34 kk>
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

/*! \file ezstep_registration.php
*/
//include_once( 'kernel/setup/steps/ezstep_installer.php');
//include_once( "kernel/setup/ezsetuptests.php" );
require_once( "kernel/common/i18n.php" );

/*!
  \class eZStepRegistration ezstep_registration.php
  \brief The class eZStepRegistration does

*/

class eZStepRegistration extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepRegistration( $tpl, $http, $ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'registration', 'Registration' );
    }

    function generateRegistration( $mailTpl, $comments )
    {
        $databaseMap = eZSetupDatabaseMap();
        $databaseInfo = $this->PersistenceList['database_info'];
        $databaseInfo['info'] = $databaseMap[$databaseInfo['type']];
        $regionalInfo = $this->PersistenceList['regional_info'];
        if ( !isset( $regionalInfo['languages'] ) )
            $regionalInfo['languages'] = array();
//        $demoData = $this->PersistenceList['demo_data'];
        $emailInfo = $this->PersistenceList['email_info'];

        $siteTemplates = array();
        $siteType = $this->chosenSiteType();


       /* $typeFunctionality = eZSetupFunctionality( $siteType['identifier'] );
        $additionalPackages = array();
        if ( isset( $this->PersistenceList['additional_packages'] ) )
            $additionalPackages = $this->PersistenceList['additional_packages'];
        $extraFunctionality = array_merge( $additionalPackages,
                                           $typeFunctionality['required'] );
        $extraFunctionality = array_unique( $extraFunctionality );*/
        $url = $siteType['url'];
        if ( !preg_match( "#^[a-zA-Z0-9]+://(.*)$#", $url ) )
        {
            $url = 'http://' . $url;
        }
        $currentURL = $url;
        $adminURL = $url;
        if ( $siteType['access_type'] == 'url' )
        {
            $url .= '/' . $siteType['access_type_value'];
            $adminURL .= '/' . $siteType['admin_access_type_value'];
        }
        else if ( $siteType['access_type'] == 'hostname' )
        {
            $url = eZHTTPTool::createRedirectURL( 'http://' . $siteType['access_type_value'] );
            $adminURL = eZHTTPTool::createRedirectURL( 'http://' . $siteType['admin_access_type_value'] );
        }
        else if ( $siteType['access_type'] == 'port' )
        {
            $url = eZHTTPTool::createRedirectURL( $currentURL, array( 'override_port' => $siteType['access_type_value'] ) );
            $adminURL = eZHTTPTool::createRedirectURL( $currentURL, array( 'override_port' => $siteType['admin_access_type_value'] ) );
        }
        $siteType['url'] = $url;
        $siteType['admin_url'] = $adminURL;
        //$siteType['extra_functionality'] = $extraFunctionality;


        $testsRun = $this->PersistenceList['tests_run'];
        $imageMagickProgram = $this->PersistenceList['imagemagick_program'];
        $imageGDExtension = $this->PersistenceList['imagegd_extension'];
        $phpVersion = $this->PersistenceList['phpversion'];
        $webserverInfo = false;
        if ( function_exists( 'apache_get_version' ) )
        {
            $webserverInfo = array( 'version' => apache_get_version() );
        }
        //include_once( 'lib/ezutils/classes/ezsysinfo.php' );
        $systemInfo = new eZSysInfo();
        $systemInfo->scan();

        $optionalTests = eZSetupOptionalTests();
        $testTable = eZSetupTestTable();

        $runResult = eZSetupRunTests( $optionalTests, 'eZSetup:init:send_registration', $this->PersistenceList );
        $testResults = $runResult['results'];
        $testResult = $runResult['result'];
        $successCount = $runResult['success_count'];
        $persistenceData = $runResult['persistence_list'];

        // Send e-mail

        $mailTpl->setVariable( 'comments', $comments );
        $mailTpl->setVariable( 'database_info', $databaseInfo );
        $mailTpl->setVariable( 'regional_info', $regionalInfo );
//        $mailTpl->setVariable( 'demo_data', $demoData );
        $mailTpl->setVariable( 'email_info', $emailInfo );
        $mailTpl->setVariable( 'site_type', $siteType );
        $mailTpl->setVariable( 'tests_run', $testsRun );
        $mailTpl->setVariable( 'imagemagick_program', $imageMagickProgram );
        $mailTpl->setVariable( 'imagegd_extension', $imageGDExtension );
        $mailTpl->setVariable( 'phpversion', $phpVersion );
        $mailTpl->setVariable( 'webserver', $webserverInfo );
        $mailTpl->setVariable( 'system', $systemInfo );
        $mailTpl->setVariable( 'os', array( 'name' => php_uname() ) );
        $mailTpl->setVariable( 'optional_tests', $testResults );
        //include_once( 'lib/version.php' );
        $mailTpl->setVariable( "version", array( "text" => eZPublishSDK::version(),
                                                 "major" => eZPublishSDK::majorVersion(),
                                                 "minor" => eZPublishSDK::minorVersion(),
                                                 "release" => eZPublishSDK::release() ) );

        return $mailTpl->fetch( 'design:setup/registration_email.tpl' );
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

        require_once( 'kernel/common/template.php' );
        $mailTpl = templateInit( 'email' );
        $comments = false;
        if ( $this->Http->hasPostVariable( 'eZSetupRegistrationComment' ) )
        {
            $comments = $this->Http->postVariable( 'eZSetupRegistrationComment' );
        }
        $bodyText = $this->generateRegistration( $mailTpl, $comments );
        $subject = $mailTpl->variable( 'subject' );

        // Fill in E-Mail data and send it
        //include_once( 'lib/ezutils/classes/ezmail.php' );
        //include_once( 'lib/ezutils/classes/ezmailtransport.php' );
        $mail = new eZMail();
        $mail->setReceiver( 'registerezsite@ez.no', 'eZ Site Registration' );
        $mail->setSender( 'registerezsite@ez.no' );
        $mail->setSubject( $subject );
        $mail->setBody( $bodyText );
        $mailResult = eZMailTransport::send( $mail );

        $this->PersistenceList['email_info']['send'] = true;
        $this->PersistenceList['email_info']['result'] = $mailResult;

        return true; // Always continue
    }

    /*!
     \reimp
     */
    function init()
    {
        if ( $this->hasKickstartData() )
        {
            $data = $this->kickstartData();

            $this->PersistenceList['email_info']['send']     =   isset( $data['Send'] ) ? ( $data['Send'] == 'true' ) : true;
            $this->PersistenceList['email_info']['comments'] = ( isset( $data['Comments'] ) ) ? $data['Comments'] : false;

            if ( $this->kickstartContinueNextStep() )
            {
                if ( $this->PersistenceList['email_info']['send'] )
                {
                    require_once( 'kernel/common/template.php' );
                    $mailTpl = templateInit( 'email' );
                    $bodyText = $this->generateRegistration( $mailTpl, $comments );
                    $subject = $mailTpl->variable( 'subject' );

                    // Fill in E-Mail data and send it
                    //include_once( 'lib/ezutils/classes/ezmail.php' );
                    //include_once( 'lib/ezutils/classes/ezmailtransport.php' );
                    $mail = new eZMail();
                    $mail->setReceiver( 'registerezsite@ez.no', 'eZ Site Registration' );
                    $mail->setSender( 'registerezsite@ez.no' );
                    $mail->setSubject( $subject );
                    $mail->setBody( $bodyText );
                    $mailResult = eZMailTransport::send( $mail );

                    $this->PersistenceList['email_info']['result'] = $mailResult;
                }
                else
                {
                    $this->PersistenceList['email_info']['result'] = false;
                }
                return true;
            }
            else
            {
                return false;
            }
        }

        return false; // Always display registration information
    }

    /*!
     \reimp
    */
    function display()
    {
        require_once( 'kernel/common/template.php' );

        $mailTpl  = templateInit( 'email' );

        $bodyText = $this->generateRegistration( $mailTpl, false );
        $send     = ( isset( $this->PersistenceList['email_info']['send'] ) )     ? $this->PersistenceList['email_info']['send'] : true;
        $comments = ( isset( $this->PersistenceList['email_info']['comments'] ) ) ? $this->PersistenceList['email_info']['comments'] : false;

        $this->Tpl->setVariable( 'email_body', $bodyText );
        $this->Tpl->setVariable( 'send_registration', $send );
        $this->Tpl->setVariable( 'email_comments', $comments );
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
