<?php
//
// eZSetup - init part initialization
//
// Created on: <24-Sep-2003 13:41:54 kk>
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

$Module =& $Params["Module"];

include_once( 'kernel/rss/edit_functions.php' );
include_once( "kernel/common/template.php" );
include_once( 'kernel/classes/ezrssimport.php' );
include_once( 'lib/ezutils/classes/ezhttppersistence.php' );

$http =& eZHTTPTool::instance();

//Get RSSImport id if it is accessable
if ( isset( $Params['RSSImportID'] ) )
    $RSSImportID = $Params['RSSImportID'];
else
    $RSSImportID = false;
if ( $http->hasPostVariable( 'RSSImport_ID' ) )
    $RSSImportID = $http->postVariable( 'RSSImport_ID' );

// Check for current actions
if ( $Module->isCurrentAction( 'Store' ) )
{
    return storeRSSImport( $Module, $http, true );
}
else if ( $Module->isCurrentAction( 'Remove' ) )
{
    $rssImport =& eZRSSImport::fetch( $RSSImportID );
    $rssImport->remove();
    return $Module->run( 'list', array() );
}
else if ( $Module->isCurrentAction( 'UpdateClass' ) )
{
    storeRSSImport( $Module, $http );
}
else if ( $Module->isCurrentAction( 'BrowseDestination' ) )
{
    storeRSSImport( $Module, $http );
    include_once( 'kernel/classes/ezcontentbrowse.php' );
    eZContentBrowse::browse( array( 'action_name' => 'RSSObjectBrowse',
                                    'description_template' => 'design:rss/browse_destination.tpl',
                                    'from_page' => '/rss/edit_import/'.$RSSImportID.'/destination' ),
                             $Module );
}
else if ( $Module->isCurrentAction( 'BrowseUser' ) )
{
    storeRSSImport( $Module, $http );
    include_once( 'kernel/classes/ezcontentbrowse.php' );
    eZContentBrowse::browse( array( 'action_name' => 'RSSUserBrowse',
                                    'description_template' => 'design:rss/browse_user.tpl',
                                    'from_page' => '/rss/edit_import/'.$RSSImportID.'/user' ),
                             $Module );
}

// If RSSImport id specified, fetch it and use it, else create new RSSImport object
if ( is_numeric( $RSSImportID ) )
{
    $rssImport =& eZRSSImport::fetch( $RSSImportID );
    $rssImportID = $RSSImportID;

    // Check if coming from browse, if so store result
    if ( isset( $Params['BrowseType'] ) )
    {
        switch ( $Params['BrowseType'] )
        {
            case 'destination': // Returning from destination browse
            {
                include_once( 'kernel/classes/ezcontentbrowse.php' );
                $nodeIDArray = $http->postVariable( 'SelectedNodeIDArray' );
                if ( isset( $nodeIDArray ) )
                {
                    $rssImport->setAttribute( 'destination_node_id', $nodeIDArray[0] );
                    $rssImport->store();
                }
            } break;

            case 'user': //Returning from user browse
            {
                include_once( 'kernel/classes/ezcontentbrowse.php' );
                $nodeIDArray = $http->postVariable( 'SelectedObjectIDArray' );
                if ( isset( $nodeIDArray ) )
                {
                    $rssImport->setAttribute( 'object_owner_id', $nodeIDArray[0] );
                    $rssImport->store();
                }
            } break;
        }
    }
}
else
{
    include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
    $user =& eZUser::currentUser();
    $user_id = $user->attribute( "contentobject_id" );

    // Create default rssImport object to use
    $rssImport =& eZRSSImport::create( $user_id );
    $rssImport->store();
    $rssImportID = $rssImport->attribute( 'id' );
}

$tpl =& templateInit();

// Get classes and class attributes
$classArray =& eZContentClass::fetchList();

$tpl->setVariable( 'rss_class_array', $classArray );
$tpl->setVariable( 'rss_import', $rssImport );
$tpl->setVariable( 'rss_import_id', $rssImportID );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:rss/edit_import.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/rss', 'Really Simple Syndication' ) ) );


?>
