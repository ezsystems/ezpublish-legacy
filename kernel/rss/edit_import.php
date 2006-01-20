<?php
//
// eZSetup - init part initialization
//
// Created on: <24-Sep-2003 13:41:54 kk>
//
// Copyright (C) 1999-2006 eZ systems as. All rights reserved.
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
include_once( 'lib/ezutils/classes/ezhttppersistence.php' );

$http =& eZHTTPTool::instance();

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
$rssImport = eZRSSImport::fetch( $rssImportID, true, EZ_RSSIMPORT_STATUS_DRAFT );
if ( !$rssImport )
{
    $rssImport = eZRSSImport::fetch( $rssImportID, true, EZ_RSSIMPORT_STATUS_VALID );
    if ( $rssImport )
    {
        $rssImport->setAttribute( 'status', EZ_RSSIMPORT_STATUS_DRAFT );
        $rssImport->store();
    }
}
if ( !$rssImport )
{
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'rss' );
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
    include_once( 'kernel/classes/ezcontentbrowse.php' );
    return eZContentBrowse::browse( array( 'action_name' => 'RSSObjectBrowse',
                                           'description_template' => 'design:rss/browse_destination.tpl',
                                           'from_page' => '/rss/edit_import/'.$rssImportID.'/destination' ),
                                    $Module );
}
else if ( $Module->isCurrentAction( 'BrowseUser' ) )
{
    storeRSSImport( $rssImport, $http );
    include_once( 'kernel/classes/ezcontentbrowse.php' );
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

$tpl =& templateInit();

// Get classes and class attributes
$classArray = eZContentClass::fetchList();

$tpl->setVariable( 'rss_class_array', $classArray );
$tpl->setVariable( 'rss_import', $rssImport );
$tpl->setVariable( 'step', $step );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:rss/edit_import.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/rss', 'Really Simple Syndication' ) ) );



function storeRSSImport( &$rssImport, $http, $publish = false )
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
        $db =& eZDB::instance();
        $db->begin();
        $rssImport->setAttribute( 'status', EZ_RSSIMPORT_STATUS_VALID );
        $rssImport->store();
        // remove draft
        $rssImport->setAttribute( 'status', EZ_RSSIMPORT_STATUS_DRAFT );
        $rssImport->remove();
        $db->commit();
    }
    else
    {
        $rssImport->store();
    }
}

function checkTimeout( &$rssImport )
{
    include_once( 'lib/ezlocale/classes/ezdatetime.php' );
    $user =& eZUser::currentUser();
    $contentIni =& eZIni::instance( 'content.ini' );
    $timeOut = $contentIni->variable( 'RSSImportSettings', 'DraftTimeout' );
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

    return false;
}

?>
