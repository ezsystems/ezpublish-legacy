<?php
//
// Definition of eZStepEmailSettings class
//
// Created on: <12-Aug-2003 10:39:13 kk>
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

/*! \file ezstep_email_settings.php
*/

include_once( 'kernel/setup/steps/ezstep_installer.php');
include_once( 'kernel/common/i18n.php' );

/*!
  \class eZStepEmailSettings ezstep_email_settings.php
  \brief The class eZStepEmailSettings does

*/

class eZStepEmailSettings extends eZStepInstaller
{
    /*!
     Constructor
     \reimp
    */
    function eZStepEmailSettings(&$tpl, &$http, &$ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'email_settings', 'Email settings' );
    }

    /*!
     \reimp
     */
    function processPostData()
    {
        if ( $this->Http->hasPostVariable( 'eZSetupEmailTransport' ) )
        {
            $this->PersistenceList['email_info']['type'] = $this->Http->postVariable( 'eZSetupEmailTransport' );
            $this->PersistenceList['email_info']['sent'] = false;
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

    /*!
     \reimp
     */
    function init()
    {
        if ( $this->hasKickstartData() )
        {
            $data = $this->kickstartData();
            $this->PersistenceList['email_info']['sent'] = false;
            $this->PersistenceList['email_info']['result'] = false;
            $this->PersistenceList['email_info']['type'] = 1;
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

        /*!
     \reimp
     */
    function &display()
    {
        $emailInfo = array( 'type' => 1,
                            'server' => false,
                            'user' => false,
                            'password' => false,
                            'sent' => false,
                            'result' => false );
        if ( isset( $this->PersistenceList['email_info'] ) )
            $emailInfo = $this->PersistenceList['email_info'];
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
        $result['path'] = array( array( 'text' => ezi18n( 'design/standard/setup/init',
                                                          'Email settings' ),
                                        'url' => false ) );
        return $result;

    }
}

?>
