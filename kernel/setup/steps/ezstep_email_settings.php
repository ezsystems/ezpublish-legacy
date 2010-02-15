<?php
//
// Definition of eZStepEmailSettings class
//
// Created on: <12-Aug-2003 10:39:13 kk>
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
        $result['path'] = array( array( 'text' => eZi18n::translate( 'design/standard/setup/init',
                                                          'Email settings' ),
                                        'url' => false ) );
        return $result;

    }
}

?>
