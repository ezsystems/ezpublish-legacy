<?php
/**
 * File containing the eZStepEmailSettings class.
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class eZStepEmailSettings ezstep_email_settings.php
  \brief The class eZStepEmailSettings does

*/

class eZStepEmailSettings extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepEmailSettings( $tpl, $http, $ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'email_settings', 'Email settings' );
    }

    function processPostData()
    {
        if ( $this->Http->hasPostVariable( 'eZSetupEmailTransport' ) )
        {
            $this->PersistenceList['email_info']['type'] = $this->Http->postVariable( 'eZSetupEmailTransport' );
            $this->PersistenceList['email_info']['result'] = false;
            if ( $this->PersistenceList['email_info']['type'] == 2 )
            {
                $this->PersistenceList['email_info']['server'] = $this->Http->postVariable( 'eZSetupSMTPServer' );
                $this->PersistenceList['email_info']['user'] = $this->Http->postVariable( 'eZSetupSMTPUser' );
                $this->PersistenceList['email_info']['password'] = $this->Http->postVariable( 'eZSetupSMTPPassword' );
            }
        }

        return true;
    }

    function init()
    {
        if ( $this->hasKickstartData() )
        {
            $data = $this->kickstartData();
            $this->PersistenceList['email_info']['result'] = false;
            $this->PersistenceList['email_info']['type'] = 1;

            $systemType = eZSys::filesystemType();
            if ( $systemType == 'win32' )
                $data['Type'] = 'smtp';

            if ( $data['Type'] == 'smtp' )
            {
                $this->PersistenceList['email_info']['type'] = 2;
                $this->PersistenceList['email_info']['server'] = $data['Server'];
                $this->PersistenceList['email_info']['user'] = $data['User'];
                $this->PersistenceList['email_info']['password'] = $data['Password'];
            }
            return $this->kickstartContinueNextStep();
        }
        return false; // Always display email settings
    }

    function display()
    {
        $emailInfo = array( 'type' => 1,
                            'server' => false,
                            'user' => false,
                            'password' => false,
                            'result' => false );
        if ( isset( $this->PersistenceList['email_info'] ) )
            $emailInfo = array_merge( $emailInfo, $this->PersistenceList['email_info'] );
        if ( $emailInfo['server'] and
             $this->Ini->variable( 'MailSettings', 'TransportServer' ) )
            $emailInfo['server'] = $this->Ini->variable( 'MailSettings', 'TransportServer' );
        if ( $emailInfo['user'] and
             $this->Ini->variable( 'MailSettings', 'TransportUser' ) )
            $emailInfo['user'] = $this->Ini->variable( 'MailSettings', 'TransportUser' );
        if ( $emailInfo['password'] and
             $this->Ini->variable( 'MailSettings', 'TransportPassword' ) )
            $emailInfo['password'] = $this->Ini->variable( 'MailSettings', 'TransportPassword' );

        $this->Tpl->setVariable( 'email_info', $emailInfo );

        $systemType = eZSys::filesystemType();
        $this->Tpl->setVariable( 'system', array( 'type' => $systemType ) );

        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( "design:setup/init/email_settings.tpl" );
        $result['path'] = array( array( 'text' => ezpI18n::tr( 'design/standard/setup/init',
                                                          'Email settings' ),
                                        'url' => false ) );
        return $result;

    }
}

?>
