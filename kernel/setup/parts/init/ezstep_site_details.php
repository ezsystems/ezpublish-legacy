<?php
//
// eZSetup
//
// Created on: <08-Nov-2002 11:00:54 kd>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
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
include( "kernel/setup/ezsetuptests.php" );

/*!
    Step 1: General tests and information for the databases
*/
function eZSetupStep_site_details( &$tpl, &$http, &$ini, &$persistenceList )
{
    $databaseMap = eZSetupDatabaseMap();

    include_once( 'lib/ezutils/classes/ezhttptool.php' );
    if ( $http->hasPostVariable( 'eZSetupEmailTransport' ) )
    {
        $persistenceList['email_info']['type'] = $http->postVariable( 'eZSetupEmailTransport' );
        $persistenceList['email_info']['sent'] = false;
        $persistenceList['email_info']['result'] = false;
        if ( $persistenceList['email_info']['type'] == 2 )
        {
            $persistenceList['email_info']['server'] = $http->postVariable( 'eZSetupSMTPServer' );
            $persistenceList['email_info']['user'] = $http->postVariable( 'eZSetupSMTPUser' );
            $persistenceList['email_info']['password'] = $http->postVariable( 'eZSetupSMTPPassword' );
        }
    }

    $siteInfo = array( 'title' => $ini->variable( 'SiteSettings', 'SiteName' ),
                       'url' => 'http://' . eZSys::hostName() . eZSys::indexDir() );
    if ( isset( $persistenceList['site_info'] ) )
        $siteInfo = $persistenceList['site_info'];

    $tpl->setVariable( 'site_info', $siteInfo );

    $result = array();
    // Display template
    $result['content'] = $tpl->fetch( "design:setup/init/site_details.tpl" );
    $result['path'] = array( array( 'text' => 'Site details',
                                    'url' => false ) );
    return $result;
}


?>
