<?php
//
// Definition of eZStepSiteDetails class
//
// Created on: <12-Aug-2003 18:30:57 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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

/*! \file ezstep_site_details.php
*/
include_once( 'kernel/setup/steps/ezstep_installer.php');
include_once( "kernel/common/i18n.php" );
include_once( "lib/ezutils/classes/ezmail.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );

define( 'EZ_SETUP_SITE_ADMIN_PASSWORD_MISSMATCH', 1 );
define( 'EZ_SETUP_SITE_ADMIN_FIRST_NAME_MISSING', 2 );
define( 'EZ_SETUP_SITE_ADMIN_LAST_NAME_MISSING', 3 );
define( 'EZ_SETUP_SITE_ADMIN_EMAIL_MISSING', 4 );
define( 'EZ_SETUP_SITE_ADMIN_EMAIL_INVALID', 5 );
define( 'EZ_SETUP_SITE_ADMIN_PASSWORD_MISSING', 6 );
define( 'EZ_SETUP_SITE_ADMIN_PASSWORD_TOO_SHORT', 7 );

/*!
  \class eZStepSiteAdmin ezstep_site_admin.php
  \brief The class eZStepSiteAdmin does

*/

class eZStepSiteAdmin extends eZStepInstaller
{
    /*!
     Constructor
    */
    function eZStepSiteAdmin( &$tpl, &$http, &$ini, &$persistenceList )
    {
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList,
                                'site_admin', 'Site admin' );
    }

    /*!
     \reimp
    */
    function processPostData()
    {
        $user = array();

        $user['first_name'] = $this->Http->postVariable( 'eZSetup_site_templates_first_name' );
        $user['last_name'] = $this->Http->postVariable( 'eZSetup_site_templates_last_name' );
        $user['email'] = $this->Http->postVariable( 'eZSetup_site_templates_email' );
        if ( strlen( trim( $user['first_name'] ) ) == 0 )
        {
            $this->Error[] = EZ_SETUP_SITE_ADMIN_FIRST_NAME_MISSING;
        }
        if ( strlen( trim( $user['last_name'] ) ) == 0 )
        {
            $this->Error[] = EZ_SETUP_SITE_ADMIN_LAST_NAME_MISSING;
        }
        if ( strlen( trim( $user['email'] ) ) == 0 )
        {
            $this->Error[] = EZ_SETUP_SITE_ADMIN_EMAIL_MISSING;
        }
        else if ( !eZMail::validate( trim( $user['email'] ) ) )
        {
            $this->Error[] = EZ_SETUP_SITE_ADMIN_EMAIL_INVALID;
        }
        if ( strlen( trim( $this->Http->postVariable( 'eZSetup_site_templates_password1' ) ) ) == 0 )
        {
            $this->Error[] = EZ_SETUP_SITE_ADMIN_PASSWORD_MISSING;
        }
        else if ( $this->Http->postVariable( 'eZSetup_site_templates_password1' ) != $this->Http->postVariable( 'eZSetup_site_templates_password2' ) )
        {
            $this->Error[] = EZ_SETUP_SITE_ADMIN_PASSWORD_MISSMATCH;
        }
        else if ( !eZUser::validatePassword( trim( $this->Http->postVariable( 'eZSetup_site_templates_password1' ) ) ) )
        {
            $this->Error[] = EZ_SETUP_SITE_ADMIN_PASSWORD_TOO_SHORT;
        }
        else
        {
            $user['password'] = $this->Http->postVariable( 'eZSetup_site_templates_password1' );
        }
        if ( !isset( $user['password'] ) )
            $user['password'] = '';
        $this->PersistenceList['admin'] = $user;

        return ( count( $this->Error ) == 0 );
    }

    /*!
     \reimp
    */
    function init()
    {
        $siteType = $this->chosenSiteType();
        if ( isset( $siteType['existing_database'] ) &&
             $siteType['existing_database'] == EZ_SETUP_DB_DATA_KEEP ) // Keep existing data in database, no need to reset admin user.
        {
            return true;
        }

        if ( $this->hasKickstartData() )
        {
            $data = $this->kickstartData();

            $adminUser = array( 'first_name' => 'Administrator',
                                'last_name' => 'User',
                                'email' => false,
                                'password' => false );

            if ( isset( $data['FirstName'] ) )
                $adminUser['first_name'] = $data['FirstName'];
            if ( isset( $data['LastName'] ) )
                $adminUser['last_name'] = $data['LastName'];
            if ( isset( $data['Email'] ) )
                $adminUser['email'] = $data['Email'];
            if ( isset( $data['Password'] ) )
                $adminUser['password'] = $data['Password'];

            $this->PersistenceList['admin'] = $adminUser;
            return $this->kickstartContinueNextStep();
        }

        // Set default values for admin user
        if ( !isset( $this->PersistenceList['admin'] ) )
        {
            $adminUser = array( 'first_name' => 'Administrator',
                                'last_name' => 'User',
                                'email' => false,
                                'password' => false );
            $this->PersistenceList['admin'] = $adminUser;
        }

        return false;
    }

    /*!
     \reimp
    */
    function display()
    {
        $this->Tpl->setVariable( 'first_name_missing', 0 );
        $this->Tpl->setVariable( 'last_name_missing', 0 );
        $this->Tpl->setVariable( 'email_missing', 0 );
        $this->Tpl->setVariable( 'email_invalid', 0 );
        $this->Tpl->setVariable( 'password_missmatch', 0 );
        $this->Tpl->setVariable( 'password_missing', 0 );
        $this->Tpl->setVariable( 'password_too_short', 0 );

        if ( isset( $this->Error[0] ) )
        {
            switch ( $this->Error[0] )
            {
                case EZ_SETUP_SITE_ADMIN_FIRST_NAME_MISSING:
                {
                    $this->Tpl->setVariable( 'first_name_missing', 1 );
                } break;

                case EZ_SETUP_SITE_ADMIN_LAST_NAME_MISSING:
                {
                    $this->Tpl->setVariable( 'last_name_missing', 1 );
                } break;

                case EZ_SETUP_SITE_ADMIN_EMAIL_MISSING:
                {
                    $this->Tpl->setVariable( 'email_missing', 1 );
                } break;

                case EZ_SETUP_SITE_ADMIN_EMAIL_INVALID:
                {
                    $this->Tpl->setVariable( 'email_invalid', 1 );
                } break;

                case EZ_SETUP_SITE_ADMIN_PASSWORD_MISSMATCH:
                {
                    $this->Tpl->setVariable( 'password_missmatch', 1 );
                } break;

                case EZ_SETUP_SITE_ADMIN_PASSWORD_MISSING:
                {
                    $this->Tpl->setVariable( 'password_missing', 1 );
                } break;

                case EZ_SETUP_SITE_ADMIN_PASSWORD_TOO_SHORT:
                {
                    $this->Tpl->setVariable( 'password_too_short', 1 );
                } break;
            }
        }

        $this->Tpl->setVariable( 'has_errors', count( $this->Error ) > 0 );

        $adminUser = array( 'first_name' => false,
                            'last_name' => false,
                            'email' => false,
                            'password' => false );
        if ( isset( $this->PersistenceList['admin'] ) )
            $adminUser = $this->PersistenceList['admin'];

        $this->Tpl->setVariable( 'admin', $adminUser );

        // Return template and data to be shown
        $result = array();
        // Display template
        $result['content'] = $this->Tpl->fetch( 'design:setup/init/site_admin.tpl' );
        $result['path'] = array( array( 'text' => ezi18n( 'design/standard/setup/init',
                                                          'Site administrator' ),
                                        'url' => false ) );
        return $result;
    }

    public $Error = array();
}

?>
