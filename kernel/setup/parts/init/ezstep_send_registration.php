<?php
//
// eZSetup
//
// Created on: <08-Nov-2002 11:00:54 kd>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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

// All test functions should be defined in ezsetuptests
include_once( "kernel/setup/ezsetuptests.php" );

/*!
    Step 1: General tests and information for the databases
*/
function eZSetupStep_send_registration( &$tpl, &$http, &$ini, &$persistenceList )
{
    include_once( 'lib/ezutils/classes/ezmail.php' );
    include_once( 'lib/ezutils/classes/ezmailtransport.php' );

    eZSetupChangeEmailSetting( $persistenceList['email_info'] );

    $databaseMap = eZSetupDatabaseMap();
    $databaseInfo = $persistenceList['database_info'];
    $databaseInfo['info'] = $databaseMap[$databaseInfo['type']];
    $regionalInfo = $persistenceList['regional_info'];
    if ( !isset( $regionalInfo['languages'] ) )
        $regionalInfo['languages'] = array();
    $demoData = $persistenceList['demo_data'];
    $emailInfo = $persistenceList['email_info'];
    $siteInfo = $persistenceList['site_info'];
    $url = $siteInfo['url'];
    if ( !preg_match( "#^[a-zA-Z0-9]+://(.*)$#", $url ) )
    {
        $url = 'http://' . $url;
        $siteInfo['url'] = $url;
    }

    $testsRun = $persistenceList['tests_run'];
    $imageMagickProgram = $persistenceList['imagemagick_program'];
    $imageGDExtension = $persistenceList['imagegd_extension'];
    $phpVersion = $persistenceList['phpversion'];

    $optionalTests = eZSetupOptionalTests();
    $testTable = eZSetupTestTable();

    $arguments = array();
    $runResult = eZSetupRunTests( $optionalTests, $arguments, 'eZSetup:init:send_registration' );
    $testResults = $runResult['results'];
    $testResult = $runResult['result'];
    $successCount = $runResult['success_count'];
    $persistenceData = $runResult['persistence_list'];

    $mail = new eZMail();
    $mail->setReceiver( 'registerezsite@ez.no', 'eZ Site Registration' );
//     $mail->setReceiver( 'jb@ez.no', 'eZ Site Registration' );
//     $mail->addReceiver( 'bf@ez.no', 'eZ Site Registration' );
    $mail->setSenderText( $ini->variable( 'MailSettings', 'AdminEmail' ) );

    // Send e-mail
    include_once( 'kernel/common/template.php' );
    $mailTpl =& templateInit( 'email' );

    $comments = false;
    if ( $http->hasPostVariable( 'eZSetupRegistrationComment' ) )
    {
        $comments = $http->postVariable( 'eZSetupRegistrationComment' );
    }

    $mailTpl->setVariable( 'comments', $comments );
    $mailTpl->setVariable( 'database_info', $databaseInfo );
    $mailTpl->setVariable( 'regional_info', $regionalInfo );
    $mailTpl->setVariable( 'demo_data', $demoData );
    $mailTpl->setVariable( 'email_info', $emailInfo );
    $mailTpl->setVariable( 'site_info', $siteInfo );
    $mailTpl->setVariable( 'tests_run', $testsRun );
    $mailTpl->setVariable( 'imagemagick_program', $imageMagickProgram );
    $mailTpl->setVariable( 'imagegd_extension', $imageGDExtension );
    $mailTpl->setVariable( 'phpversion', $phpVersion );
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

    $persistenceList['email_info']['sent'] = true;
    $persistenceList['email_info']['result'] = $mailResult;

    $result = array( 'change_step' => 13 );
    return $result;
}


?>
