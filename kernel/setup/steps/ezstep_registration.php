<?php
/**
 * File containing the eZStepRegistration class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZStepRegistration ezstep_registration.php
  \brief The class eZStepRegistration does

*/

class eZStepRegistration extends eZStepInstaller
{
    /**
     * Constructor for eZStepRegistration
     *
     * @param \eZTemplate $tpl
     * @param \eZHTTPTool $http
     * @param \eZINI $ini
     * @param array $persistenceList
     */
    function eZStepRegistration( eZTemplate $tpl, eZHTTPTool $http, eZINI $ini, array &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'registration', 'Registration' );
    }

    /**
     * @param \eZTemplate $mailTpl
     * @param array $userData
     *
     * @return array|null|string
     */
    function generateRegistration( eZTemplate $mailTpl, array $userData )
    {
        $databaseMap = eZSetupDatabaseMap();
        $databaseInfo = $this->PersistenceList['database_info'];
        $databaseInfo['info'] = $databaseMap[$databaseInfo['type']];
        $regionalInfo = $this->PersistenceList['regional_info'];
        if ( !isset( $regionalInfo['languages'] ) )
            $regionalInfo['languages'] = array();

        $emailInfo = $this->PersistenceList['email_info'];

        $siteType = $this->chosenSiteType();


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


        $testsRun = $this->PersistenceList['tests_run'];
        $imageMagickProgram = $this->PersistenceList['imagemagick_program'];
        $imageGDExtension = $this->PersistenceList['imagegd_extension'];
        $phpVersion = $this->PersistenceList['phpversion'];
        $phpVersion['sapi'] = PHP_SAPI;

        $webserverInfo = false;
        if ( function_exists( 'apache_get_version' ) )
        {
            $webserverInfo = array( 'version' => apache_get_version() );
        }
        else if ( !empty( $_SERVER['SERVER_SOFTWARE'] ) )
        {
            $webserverInfo = array( 'version' => $_SERVER['SERVER_SOFTWARE'] );
        }

        $systemInfo = new eZSysInfo();
        $systemInfo->scan();

        $optionalTests = eZSetupOptionalTests();
        $runResult = eZSetupRunTests( $optionalTests, 'eZSetup:init:send_registration', $this->PersistenceList );
        $testResults = $runResult['results'];

        // Generate email body e-mail
        $mailTpl->setVariable( 'user_data', $userData );
        $mailTpl->setVariable( 'database_info', $databaseInfo );
        $mailTpl->setVariable( 'regional_info', $regionalInfo );
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
        $mailTpl->setVariable( "version", array( "text" => eZPublishSDK::version(),
                                                 "major" => eZPublishSDK::majorVersion(),
                                                 "minor" => eZPublishSDK::minorVersion(),
                                                 "release" => eZPublishSDK::release() ) );

        return $mailTpl->fetch( 'design:setup/registration_email.tpl' );
    }

    /**
     * @return bool
     */
    function processPostData()
    {
        if ( !$this->Http->hasPostVariable( 'eZSetupSendRegistration' ) )// skip site registration
        {
            return true;
        }

        if ( !$this->Http->hasPostVariable( 'eZSetupRegistrationData' ) )
        {
          return false;
        }

        // Get post variables and make sure they keep same order independent of checkboxes
        $rawUserData = $this->Http->postVariable( 'eZSetupRegistrationData' );
        $userData['first_time_user'] = isset( $rawUserData['first_time_user'] ) ? true : false;
        $userData['include_tech_stats'] = isset( $rawUserData['include_tech_stats'] ) ? true : false;
        unset( $rawUserData['first_time_user'], $rawUserData['include_tech_stats'] );
        $userData = $rawUserData + $userData + $this->defaultUserData;

        // Store on persistence list so data doesn't need to be entered several times
        $this->PersistenceList['email_info']['user_data'] = $userData;

        // Make sure required data is present
        $validationMessages = array();
        if ( !$userData['first_name'] )
        {
            $validationMessages[] = ezpI18n::tr(
                'design/standard/setup/init',
                'Registration field "%fieldName" is empty',
                false,
                array( '%fieldName' => ezpI18n::tr( 'design/standard/setup/init', 'First name' ) )
            );
        }

        if ( !$userData['last_name'] )
        {
            $validationMessages[] = ezpI18n::tr(
                'design/standard/setup/init',
                'Registration field "%fieldName" is empty',
                false,
                array( '%fieldName' => ezpI18n::tr( 'design/standard/setup/init', 'Last name' ) )
            );
        }

        if ( !$userData['email'] )
        {
            $validationMessages[] = ezpI18n::tr(
                'design/standard/setup/init',
                'Registration field "%fieldName" is empty',
                false,
                array( '%fieldName' => ezpI18n::tr( 'design/standard/setup/init', 'Your email' ) )
            );
        }
        else if ( !eZMail::validate( $userData['email'] ) )
        {
            $validationMessages[] = ezpI18n::tr(
                'design/standard/setup/init',
                'Registration field "%fieldName" has wrong format',
                false,
                array( '%fieldName' => ezpI18n::tr( 'design/standard/setup/init', 'Your email' ) )
            );
        }

        if ( !$userData['country'] )
        {
            $validationMessages[] = ezpI18n::tr(
                'design/standard/setup/init',
                'Registration field "%fieldName" is empty',
                false,
                array( '%fieldName' => ezpI18n::tr( 'design/standard/setup/init', 'Country' ) )
            );
        }

        if ( !empty( $validationMessages ) )
        {
            $this->Tpl->setVariable( 'validation_messages', $validationMessages );
            return false;
        }

        $mailTpl = eZTemplate::factory();
        $bodyText = $this->generateRegistration( $mailTpl, $userData );
        $subject = $mailTpl->variable( 'subject' );

        // Fill in E-Mail data and send it
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

    /**
     * @return bool
     */
    function init()
    {
        if ( $this->hasKickstartData() )
        {
            $data = $this->kickstartData();

            $this->PersistenceList['email_info']['send'] = isset( $data['Send'] ) ? ( $data['Send'] == 'true' ) : true;
            $this->PersistenceList['email_info']['user_data'] = isset( $data['UserData'] ) ? $data['UserData'] : $this->defaultUserData;

            if ( $this->kickstartContinueNextStep() )
            {
                if ( $this->PersistenceList['email_info']['send'] )
                {
                    $mailTpl = eZTemplate::factory();
                    $bodyText = $this->generateRegistration( $mailTpl, $this->PersistenceList['email_info']['user_data'] );
                    $subject = $mailTpl->variable( 'subject' );

                    // Fill in E-Mail data and send it
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

    /**
     * @return array
     */
    function display()
    {
        $mailTpl = eZTemplate::factory();

        $userData = isset( $this->PersistenceList['email_info']['user_data'] ) ? $this->PersistenceList['email_info']['user_data'] : $this->defaultUserData;

        $bodyText = $this->generateRegistration( $mailTpl, $userData );// using default data
        $send     = ( isset( $this->PersistenceList['email_info']['send'] ) )     ? $this->PersistenceList['email_info']['send'] : true;


        $this->Tpl->setVariable( 'email_body', $bodyText );
        $this->Tpl->setVariable( 'send_registration', $send );
        $this->Tpl->setVariable( 'email_user_data', $userData );
        $this->Tpl->setVariable( 'setup_previous_step', 'Registration' );
        $this->Tpl->setVariable( 'setup_next_step', 'DatabaseCreate' );

        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( "design:setup/init/registration.tpl" );
        $result['path'] = array( array( 'text' => ezpI18n::tr( 'design/standard/setup/init',
                                                          'Registration' ),
                                        'url' => false ) );
        return $result;
    }


    private $defaultUserData = array(
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'country' => '',
            'company' => '',
            'first_time_user' => false,
            'include_tech_stats' => true,
    );
}

?>
