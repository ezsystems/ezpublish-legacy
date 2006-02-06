<?php
//
// eZSetup - init part initialization
//
// Created on: <24-Sep-2003 13:41:54 kk>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.5.x
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
else if ( $Module->isCurrentAction( 'Cancel' ) )
{
    $rssImport =& eZRSSImport::fetch( $RSSImportID, true, EZ_RSSIMPORT_STATUS_DRAFT );
    if ( $rssImport )
    {
        $rssImport->remove();
    }
    return $Module->redirectTo( '/rss/list' );
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
    $rssImportID = $RSSImportID;
    $rssImport =& eZRSSImport::fetch( $RSSImportID, true, EZ_RSSIMPORT_STATUS_DRAFT );

    if ( $rssImport )
    {
        include_once( 'lib/ezlocale/classes/ezdatetime.php' );
        $user =& eZUser::currentUser();
        $contentIni =& eZIni::instance( 'content.ini' );
        $timeOut =& $contentIni->variable( 'RSSImportSettings', 'DraftTimeout' );
        if ( $rssImport->attribute( 'modifier_id' ) != $user->attribute( 'contentobject_id' ) &&
             $rssImport->attribute( 'modified' ) + $timeOut > time() )
        {
            // locked editing
            $tpl =& templateInit();

            $tpl->setVariable( 'rss_import', $rssImport );
            $tpl->setVariable( 'rss_import_id', $rssImportID );
            $tpl->setVariable( 'lock_timeout', $timeOut );

            $Result = array();
            $Result['content'] =& $tpl->fetch( 'design:rss/edit_import_denied.tpl' );
            $Result['path'] = array( array( 'url' => false,
                                            'text' => ezi18n( 'kernel/rss', 'Really Simple Syndication' ) ) );
            return $Result;
        }
        else if ( $timeOut > 0 && $rssImport->attribute( 'modified' ) + $timeOut < time() )
        {
            $rssImport->remove();
            $rssImport = false;
        }
    }
    if ( !$rssImport )
    {
        $rssImport =& eZRSSImport::fetch( $RSSImportID, true, EZ_RSSIMPORT_STATUS_VALID );
        if ( $rssImport )
        {
            $rssImport->setAttribute( 'status', EZ_RSSIMPORT_STATUS_DRAFT );
            $rssImport->store();
        }
        else
        {
            return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
        }
    }

    // Check if coming from browse, if so store result
    if ( isset( $Params['BrowseType'] ) )
    {
        switch ( $Params['BrowseType'] )
        {
            case 'destination': // Returning from destination browse
            {
                include_once( 'kernel/classes/ezcontentbrowse.php' );
                $nodeIDArray = $http->postVariable( 'SelectedNodeIDArray' );
                if ( isset( $nodeIDArray ) && !$http->hasPostVariable( 'BrowseCancelButton' ) )
                {
                    $rssImport->setAttribute( 'destination_node_id', $nodeIDArray[0] );
                    $rssImport->store();
                }
            } break;

            case 'user': //Returning from user browse
            {
                include_once( 'kernel/classes/ezcontentbrowse.php' );
                $nodeIDArray = $http->postVariable( 'SelectedObjectIDArray' );
                if ( isset( $nodeIDArray ) && !$http->hasPostVariable( 'BrowseCancelButton' ) )
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
