<?php
//
// eZSetup - init part initialization
//
// Created on: <24-Sep-2003 13:41:54 kk>
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

$Module = $Params['Module'];

require_once( "kernel/common/template.php" );
$http = eZHTTPTool::instance();

//Get RSSImport id if it is accessable
$step = (int)$http->hasPostVariable( 'Step' ) ? $http->postVariable( 'step' ) : 1;
$rssImportID = isset( $Params['RSSImportID'] ) ? $Params['RSSImportID'] : false;

if ( $http->hasPostVariable( 'RSSImport_ID' ) )
{
    $rssImportID = $http->postVariable( 'RSSImport_ID' );
}

// Check if valid RSS ID //
if ( !is_numeric( $rssImportID ) )
{
    // Create default rssImport object to use
    $rssImport = eZRSSImport::create();
    $rssImport->store();
    $rssImportID = $rssImport->attribute( 'id' );
}

// Fetch RSS Import object //
$rssImport = eZRSSImport::fetch( $rssImportID, true, eZRSSImport::STATUS_DRAFT );
if ( !$rssImport )
{
    $rssImport = eZRSSImport::fetch( $rssImportID, true, eZRSSImport::STATUS_VALID );
    if ( $rssImport )
    {
        $rssImport->setAttribute( 'status', eZRSSImport::STATUS_DRAFT );
        $rssImport->store();
    }
}
if ( !$rssImport )
{
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'rss' );
}
else
{
    $timeout = checkTimeout( $rssImport );
    if ( $timeout !== false )
    {
        return $timeout;
    }
}

$importDescription = $rssImport->importDescription();

// Handle RSS module action //
if ( $Module->isCurrentAction( 'AnalyzeFeed' ) ||
     $Module->isCurrentAction( 'UpdateClass' ) )
{
    $version = eZRSSImport::getRSSVersion( $http->postVariable( 'url' ) );

    if ( !isset( $importDescription['rss_version'] ) ||
         $importDescription['rss_version'] != $version )
    {
        $importDescription['object_attributes'] = array();
        $importDescription['class_attributes'] = array();
    }
    $importDescription['rss_version'] = $version;
    $rssImport->setImportDescription( $importDescription );
    storeRSSImport( $rssImport, $http );
}
else if ( $Module->isCurrentAction( 'Store' ) )
{
    storeRSSImport( $rssImport, $http, true );
    return $Module->redirectTo( '/rss/list' );
}
else if ( $Module->isCurrentAction( 'Cancel' ) )
{
    $rssImport->remove();
    return $Module->redirectTo( '/rss/list' );
}
else if ( $Module->isCurrentAction( 'BrowseDestination' ) )
{
    storeRSSImport( $rssImport, $http );
    return eZContentBrowse::browse( array( 'action_name' => 'RSSObjectBrowse',
                                           'description_template' => 'design:rss/browse_destination.tpl',
                                           'from_page' => '/rss/edit_import/'.$rssImportID.'/destination' ),
                                    $Module );
}
else if ( $Module->isCurrentAction( 'BrowseUser' ) )
{
    storeRSSImport( $rssImport, $http );
    return eZContentBrowse::browse( array( 'action_name' => 'RSSUserBrowse',
                                           'description_template' => 'design:rss/browse_user.tpl',
                                           'from_page' => '/rss/edit_import/'.$rssImportID.'/user' ),
                                    $Module );
}

// Check if coming from browse, if so store result
if ( isset( $Params['BrowseType'] ) )
{
    switch ( $Params['BrowseType'] )
    {
        case 'destination': // Returning from destination browse
        {
            $nodeIDArray = $http->hasPostVariable( 'SelectedNodeIDArray' ) ? $http->postVariable( 'SelectedNodeIDArray' ) : null;
            if ( isset( $nodeIDArray ) && !$http->hasPostVariable( 'BrowseCancelButton' ) )
            {
                $rssImport->setAttribute( 'destination_node_id', $nodeIDArray[0] );
                $rssImport->store();
            }
        } break;

        case 'user': //Returning from user browse
        {
            $nodeIDArray = $http->postVariable( 'SelectedObjectIDArray' );
            if ( isset( $nodeIDArray ) && !$http->hasPostVariable( 'BrowseCancelButton' ) )
            {
                $rssImport->setAttribute( 'object_owner_id', $nodeIDArray[0] );
                $rssImport->store();
            }
        } break;
    }
}

$tpl = templateInit();

// Get classes and class attributes
$classArray = eZContentClass::fetchList();

$tpl->setVariable( 'rss_class_array', $classArray );
$tpl->setVariable( 'rss_import', $rssImport );
$tpl->setVariable( 'step', $step );

$Result = array();
$Result['content'] = $tpl->fetch( "design:rss/edit_import.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => eZi18n::translate( 'kernel/rss', 'Really Simple Syndication' ) ) );



function storeRSSImport( $rssImport, $http, $publish = false )
{
    $rssImport->setAttribute( 'name', $http->postVariable( 'name' ) );
    $rssImport->setAttribute( 'url', $http->postVariable( 'url' ) );
    if ( $http->hasPostVariable( 'active' ) )
        $rssImport->setAttribute( 'active', 1 );
    else
        $rssImport->setAttribute( 'active', 0 );

    if ( $http->hasPostVariable( 'Class_ID' ) )
    {
        $rssImport->setAttribute( 'class_id', $http->postVariable( 'Class_ID' ) );
    }

    $importDescription = $rssImport->importDescription();
    $classAttributeList = eZContentClassAttribute::fetchListByClassID( $rssImport->attribute( 'class_id' ) );

    $importDescription['class_attributes'] = array();
    foreach( $classAttributeList as $classAttribute )
    {
        $postVariableName = 'Class_Attribute_' . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $postVariableName ) )
        {
            $importDescription['class_attributes'][(string)$classAttribute->attribute( 'id' )] = $http->postVariable( $postVariableName );
        }
    }

    $importDescription['object_attributes'] = array();
    foreach( $rssImport->objectAttributeList() as $key => $attributeName )
    {
        $postVariableName = 'Object_Attribute_' . $key;
        if ( $http->hasPostVariable( $postVariableName ) )
        {
            $importDescription['object_attributes'][$key] = $http->postVariable( $postVariableName );
        }
    }

    $rssImport->setImportDescription( $importDescription );

    if ( $publish )
    {
        $db = eZDB::instance();
        $db->begin();
        $rssImport->setAttribute( 'status', eZRSSImport::STATUS_VALID );
        $rssImport->store();
        // remove draft
        $rssImport->setAttribute( 'status', eZRSSImport::STATUS_DRAFT );
        $rssImport->remove();
        $db->commit();
    }
    else
    {
        $rssImport->store();
    }
}

function checkTimeout( $rssImport )
{
    $user = eZUser::currentUser();
    $contentIni = eZINI::instance( 'content.ini' );
    $timeOut = $contentIni->variable( 'RSSImportSettings', 'DraftTimeout' );
    if ( $rssImport->attribute( 'modifier_id' ) != $user->attribute( 'contentobject_id' ) &&
         $rssImport->attribute( 'modified' ) + $timeOut > time() )
    {
        // locked editing
        $tpl = templateInit();

        $tpl->setVariable( 'rss_import', $rssImport );
        $tpl->setVariable( 'rss_import_id', $rssImportID );
        $tpl->setVariable( 'lock_timeout', $timeOut );

        $Result = array();
        $Result['content'] = $tpl->fetch( 'design:rss/edit_import_denied.tpl' );
        $Result['path'] = array( array( 'url' => false,
                                        'text' => eZi18n::translate( 'kernel/rss', 'Really Simple Syndication' ) ) );
        return $Result;
    }
    else if ( $timeOut > 0 && $rssImport->attribute( 'modified' ) + $timeOut < time() )
    {
        $rssImport->remove();
        $rssImport = false;
    }

    return false;
}

?>
