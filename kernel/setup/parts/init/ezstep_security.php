<?php
//
// eZSetup
//
// Created on: <23-Apr-2003 15:05:11 amos>
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

// All test functions should be defined in ezsetuptests
include( "kernel/setup/ezsetuptests.php" );

function eZSetupStep_security( &$tpl, &$http, &$ini, &$persistenceList )
{
    $databaseMap = eZSetupDatabaseMap();

    include_once( 'lib/ezutils/classes/ezhttptool.php' );
    if ( $http->hasPostVariable( 'eZSetupSiteTitle' ) )
        $persistenceList['site_info']['title'] = $http->postVariable( 'eZSetupSiteTitle' );
    if ( $http->hasPostVariable( 'eZSetupSiteURL' ) )
        $persistenceList['site_info']['url'] = $http->postVariable( 'eZSetupSiteURL' );

    include_once( 'lib/ezutils/classes/ezsys.php' );
    $security = array( 'virtualhost_mode' => eZSys::indexFileName() == '' );

    $tpl->setVariable( 'security', $security );
    $tpl->setVariable( 'path', realpath( '.' ) );

    $result = array();
    // Display template
    $result['content'] = $tpl->fetch( "design:setup/init/security.tpl" );
    $result['path'] = array( array( 'text' => 'Security',
                                    'url' => false ) );
    return $result;
}


?>
