<?php
//
// Definition of eZStepSiteDetails class
//
// Created on: <12-Aug-2003 18:30:57 kk>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezstep_site_details.php
*/
include_once( 'kernel/setup/steps/ezstep_installer.php');
include_once( "kernel/common/i18n.php" );
include_once( "lib/ezutils/classes/ezmail.php" );

define( 'EZ_SETUP_SITE_ADMIN_PASSWORD_MISSMATCH', 1 );
define( 'EZ_SETUP_SITE_ADMIN_FIRST_NAME_MISSING', 2 );
define( 'EZ_SETUP_SITE_ADMIN_LAST_NAME_MISSING', 3 );
define( 'EZ_SETUP_SITE_ADMIN_EMAIL_MISSING', 4 );
define( 'EZ_SETUP_SITE_ADMIN_EMAIL_INVALID', 5 );
define( 'EZ_SETUP_SITE_ADMIN_PASSWORD_MISSING', 6 );

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
        $this->eZStepInstaller( $tpl, $http, $ini, $persistenceList );
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
        else
        {
            $user['password'] = $this->Http->postVariable( 'eZSetup_site_templates_password1' );
        }
        $this->PersistenceList['admin'] = $user;

        return ( count( $this->Error ) == 0 );
    }

    /*!
     \reimp
    */
    function init()
    {
        return false; // Always show site admin
    }

    /*!
     \reimp
    */
    function &display()
    {
        foreach ( $this->Error as $key => $error )
        {
            switch ( $error )
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

    var $Error = array();
}

?>
