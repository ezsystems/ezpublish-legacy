<?php
//
// eZSetup - init part initialization
//
// Created on: <18-Sep-2003 14:49:54 kk>
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
include_once( 'kernel/common/template.php' );
include_once( 'kernel/classes/ezrssexport.php' );
include_once( 'kernel/classes/ezrssexportitem.php' );
include_once( 'lib/ezutils/classes/ezhttppersistence.php' );

$http =& eZHTTPTool::instance();

$validated = false;
if ( isset( $Params['RSSExportID'] ) )
    $RSSExportID = $Params['RSSExportID'];
else
    $RSSExportID = false;

if ( $http->hasPostVariable( 'RSSExport_ID' ) )
    $RSSExportID = $http->postVariable( 'RSSExport_ID' );

if ( $Module->isCurrentAction( 'Store' ) )
{
    if( $_POST['active'] == "on" and strlen( trim( $_POST['Access_URL'] ) ) == 0 )
    {
         storeRSSExport( $Module, $http );
         $validated = true;
    }
    else
    {
        return storeRSSExport( $Module, $http, true );
    }
}
else if ( $Module->isCurrentAction( 'UpdateItem' ) )
{
    storeRSSExport( $Module, $http );
}
else if ( $Module->isCurrentAction( 'AddItem' ) )
{
    $rssExportItem = eZRSSExportItem::create( $RSSExportID );
    $rssExportItem->store();
    storeRSSExport( $Module, $http );
}
else if ( $Module->isCurrentAction( 'Cancel' ) )
{
    $rssExport =& eZRSSExport::fetch( $RSSExportID, true, EZ_RSSEXPORT_STATUS_DRAFT );
    $rssExport->remove();
    return $Module->redirectTo( '/rss/list' );
}
else if ( $Module->isCurrentAction( 'BrowseImage' ) )
{
    storeRSSExport( $Module, $http );
    include_once( 'kernel/classes/ezcontentbrowse.php' );
    eZContentBrowse::browse( array( 'action_name' => 'RSSExportImageBrowse',
                                    'description_template' => 'design:rss/browse_image.tpl',
                                    'from_page' => '/rss/edit_export/'. $RSSExportID .'/0/ImageSource' ),
                             $Module );
}


if ( $http->hasPostVariable( 'Item_Count' ) )
{
    for ( $itemCount = 0; $itemCount < $http->postVariable( 'Item_Count' ); $itemCount++ )
    {
        if ( $http->hasPostVariable( 'SourceBrowse_'.$itemCount ) )
        {
            storeRSSExport( $Module, $http );
            include_once( 'kernel/classes/ezcontentbrowse.php' );
            eZContentBrowse::browse( array( 'action_name' => 'RSSObjectBrowse',
                                            'description_template' => 'design:rss/browse_source.tpl',
                                            'from_page' => '/rss/edit_export/'. $RSSExportID .'/'. $http->postVariable( 'Item_ID_'.$itemCount ) .'/NodeSource' ),
                                     $Module );
            break;
        }

        // remove selected source (if any)
        if ( $http->hasPostVariable( 'RemoveSource_'.$itemCount ) )
        {
            $itemID = $http->postVariable( 'Item_ID_'.$itemCount );
            if ( ( $rssExportItem = eZRSSExportItem::fetch( $itemID, true, EZ_RSSEXPORT_STATUS_DRAFT ) ) )
            {
                $rssExportItem->remove();
                storeRSSExport( $Module, $http );
            }

            break;
        }
    }
}

if ( is_numeric( $RSSExportID ) )
{
    $rssExportID = $RSSExportID;
    $rssExport =& eZRSSExport::fetch( $RSSExportID, true, EZ_RSSEXPORT_STATUS_DRAFT );

    if ( $rssExport )
    {
        include_once( 'lib/ezlocale/classes/ezdatetime.php' );
        $user =& eZUser::currentUser();
        $contentIni =& eZIni::instance( 'content.ini' );
        $timeOut =& $contentIni->variable( 'RSSExportSettings', 'DraftTimeout' );
        if ( $rssExport->attribute( 'modifier_id' ) != $user->attribute( 'contentobject_id' ) &&
             $rssExport->attribute( 'modified' ) + $timeOut > time() )
        {
            // locked editing
            $tpl =& templateInit();

            $tpl->setVariable( 'rss_export', $rssExport );
            $tpl->setVariable( 'rss_export_id', $rssExportID );
            $tpl->setVariable( 'lock_timeout', $timeOut );

            $Result = array();
            $Result['content'] =& $tpl->fetch( 'design:rss/edit_export_denied.tpl' );
            $Result['path'] = array( array( 'url' => false,
                                            'text' => ezi18n( 'kernel/rss', 'Really Simple Syndication' ) ) );
            return $Result;
        }
        else if ( $timeOut > 0 && $rssExport->attribute( 'modified' ) + $timeOut < time() )
        {
            $rssExport->remove();
            $rssExport = false;
        }
    }
    if ( !$rssExport )
    {
        $rssExport =& eZRSSExport::fetch( $RSSExportID, true, EZ_RSSEXPORT_STATUS_VALID );
        if ( $rssExport )
        {
            $rssItems = $rssExport->fetchItems();
            $rssExport->setAttribute( 'status', EZ_RSSEXPORT_STATUS_DRAFT );
            $rssExport->store();
            foreach( array_keys( $rssItems ) as $key )
            {
                $rssItem =& $rssItems[$key];
                $rssItem->setAttribute( 'status', EZ_RSSEXPORT_STATUS_DRAFT );
                $rssItem->store();
            }
        }
        else
        {
            return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
        }
    }

    include_once( 'kernel/classes/ezcontentbrowse.php' );

    switch ( $Params['BrowseType'] )
    {
        case 'NodeSource':
        {
            $nodeIDArray = $http->postVariable( 'SelectedNodeIDArray' );
            if ( isset( $nodeIDArray ) && !$http->hasPostVariable( 'BrowseCancelButton' ) )
            {
                $rssExportItem = eZRSSExportItem::fetch( $Params['RSSExportItemID'], true, EZ_RSSEXPORT_STATUS_DRAFT );
                $rssExportItem->setAttribute( 'source_node_id', $nodeIDArray[0] );
                $rssExportItem->store();
            }
        } break;

        case 'ImageSource':
        {
            $imageNodeIDArray = $http->postVariable( 'SelectedNodeIDArray' );
            if ( isset( $imageNodeIDArray ) && !$http->hasPostVariable( 'BrowseCancelButton' ) )
            {
                $rssExport->setAttribute( 'image_id', $imageNodeIDArray[0] );
            }
        } break;
    }
}
else // New RSSExport
{
    include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
    $user =& eZUser::currentUser();
    $user_id = $user->attribute( "contentobject_id" );

    // Create default rssExport object to use
    $rssExport = eZRSSExport::create( $user_id );
    $rssExport->store();
    $rssExportID = $rssExport->attribute( 'id' );

    // Create One empty export item
    $rssExportItem = eZRSSExportItem::create( $rssExportID );
    $rssExportItem->store();
}

$tpl =& templateInit();

// Populate site access list
$config =& eZINI::instance( 'site.ini' );
//$siteAccess = $config->variable( 'SiteAccessSettings', 'AvailableSiteAccessList' );
$rssVersionArray = $config->variable( 'RSSSettings', 'AvailableVersionList' );
$rssDefaultVersion = $config->variable( 'RSSSettings', 'DefaultVersion' );

// Get Classes and class attributes
$classArray =& eZContentClass::fetchList();

$tpl->setVariable( 'rss_version_array', $rssVersionArray );
$tpl->setVariable( 'rss_version_default', $rssDefaultVersion );
//$tpl->setVariable( 'rss_site_access', $siteAccess );
$tpl->setVariable( 'rss_class_array', $classArray );
$tpl->setVariable( 'rss_export', $rssExport );
$tpl->setVariable( 'rss_export_id', $rssExportID );

$tpl->setVariable( 'validaton', $validated );
$Result = array();
$Result['content'] =& $tpl->fetch( "design:rss/edit_export.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/rss', 'Really Simple Syndication' ) ) );


?>
