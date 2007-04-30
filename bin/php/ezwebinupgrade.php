#!/usr/bin/env php
<?php
//
// Created on: <18-Apr-2007 15:00:00 dl>
//
// Copyright (C) 1999-2005 eZ systems as. All rights reserved.
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

// eZWebin upgrade Script
// file  bin/php/ezwebinupgrade.php


/*!
 define constans
*/
define( "EZ_INSTALL_PACKAGE_EXTRA_ACTION_QUIT", 'q' );
define( "EZ_INSTALL_PACKAGE_EXTRA_ACTION_SKIP_PACKAGE", 's' );

/*!
 define global vars
*/
global $cli;
global $script;


/*!
 includes
*/
include_once( 'kernel/classes/ezscript.php' );
include_once( 'kernel/common/i18n.php' );
include_once( 'kernel/classes/ezpackage.php' );


/**************************************************************
* 'cli->output' wrappers                                      *
***************************************************************/
function showError( $message, $addEOL = true, $bailOut = true )
{
    global $cli;
    global $script;

    $cli->output( $cli->stylize( 'error', "Error: " ) .  $message, $addEOL );

    if( $bailOut )
    {
        exit();
        $script->shutdown( 1 );
    }
}

function showWarning( $message, $addEOL = true )
{
    global $cli;
    $cli->output( $cli->stylize( 'warning', "Warning: " ) . $message, $addEOL );
}

function showNotice( $message, $addEOL = true )
{
    global $cli;
    $cli->output( $cli->stylize( 'notice', "Notice: " ) .  $message, $addEOL );
}

function showMessage( $message, $addEOL = true )
{
    global $cli;
    $cli->output( $cli->stylize( 'blue', $message ), $addEOL );
}

function showMessage2( $message, $addEOL = true )
{
    global $cli;
    $cli->output( $cli->stylize( 'red', $message ), $addEOL );
}

/*!
 Show available actions to user
*/
function showPackageActions( $actionList )
{
    foreach( $actionList as $action => $actionDescription )
    {
        showMessage( "    [ $action ]: " . $actionDescription );
    }
}

/*!
 add extra actions to default package item's actions
*/
function getExtraActions( &$actionList )
{
    $actionList[EZ_INSTALL_PACKAGE_EXTRA_ACTION_SKIP_PACKAGE] = "Skipt rest of the package";
    $actionList[EZ_INSTALL_PACKAGE_EXTRA_ACTION_QUIT] = "Quit";
}

/*!
 prompt user to choose what to do next
*/
function getUserInput( $prompt )
{
    $stdin = fopen( "php://stdin", "r+" );

    fwrite( $stdin, $prompt );

    $userInput = fgets( $stdin );

    fclose( $stdin );

    return $userInput;
}

/*!
 handle installation error:
     - show error message;
     - ask user what to do next;
*/
function handlePackageError( $error )
{
    showWarning( $error['description'] );

    $actionList = $error['actions'];
    getExtraActions( $actionList );

    showPackageActions( $actionList );

    $actions = array_keys( $actionList );
    $actions = '[' . implode( '], [', $actions ) . ']';

    $userInput = getUserInput( "    Plese choose one of the following actions( $actions ): " );

    return $userInput;
}

/*!
 Check if dir $dirName exists. If not, ask user to create it.
*/
function checkDir( $dirName )
{
    if ( !file_exists( $dirName ) )
    {
        global $autoMode;
        if( $autoMode == 'on' )
        {
            $action = 'y';
        }
        else
        {
            $action = getUserInput( "Directory '$dirName' doesn't exist. Create? [y/n]: ");
        }

        if( strpos( $action, 'n' ) === 0 )
            showError( "Unable to continue. Aborting..." );

        if( !eZDir::mkdir( $dirName, eZDir::directoryPermission(), true ) )
            showError( "Unable to create dir '$dirName'. Aborting..." );
    }

    return true;
}

function installScriptDir( $packageRepository )
{
    return ( eZPackage::repositoryPath() . "/$packageRepository/ezwebin_site" );
}

/*!
 download packages if neede.
    1. check if packages specified in $packageList exist in $packageRepository(means already downloaded and imported).
       if yes - ask user to do download or not. If not - go out
    2. check $packgesList exists in $packageDir(means packages downloaded but not imported)
       if yes - ask user to import or not. If not - go out
    3. download and import.
*/
function downloadPackages( $packageList, $packageURL, $packageDir, $packageRepository )
{
    global $cli;

    showMessage2( "Configuring..." );

    if ( !is_array( $packageList ) || count( $packageList ) == 0 )
        showError( "Package list is empty. Aborting..." );

    // 1. check if packages specified in $packageList exist in $packageRepository(means already downloaded and imported).
    //    if yes - ask user to do download or not. If not - go out
    foreach( array_keys( $packageList ) as $k )
    {
        $packageName = $packageList[$k];
        $package = eZPackage::fetch( $packageName );

        if( is_object( $package ) )
        {
            global $autoMode;
            if( $autoMode == 'on' )
            {
                $action = 'y';
            }
            else
            {
                $action = getUserInput( "Package '$packageName' already imported. Import it anyway? [y/n]: " );
            }

            if ( strpos( $action, 'n' ) === 0 )
                unset( $packageList[$k] );
            else
            {
                eZDir::recursiveDelete( eZPackage::repositoryPath() . "/$packageRepository/$packageName" );
            }
        }
    }

    if( count( $packageList ) == 0 )
    {
        // all packages are imported.
        return true;
    }

    // 2. check $packgesList exists in $packageDir(means packages downloaded but not imported)
    //    if yes - ask user to import or not. If not - go out
    if( !checkDir( $packageDir ) )
        return false;

    $downloadPackageList = array();
    foreach( $packageList as $packageName )
    {
        if( file_exists( "$packageDir/$packageName.ezpkg" ) )
        {
            global $autoMode;
            if( $autoMode == 'on' )
            {
                $action = 'y';
            }
            else
            {
                $action = getUserInput( "Package '$packageName' already downloaded. Download it anyway? [y/n]: " );
            }

            if ( strpos( $action, 'n' ) === 0 )
                continue;
        }

        $downloadPackageList[] = $packageName;
    }

    //
    // download
    //
    showMessage2( "Downloading..." );
    if( count( $downloadPackageList ) > 0 )
    {
        // TODO: using 'eZStepSiteTypes' is hack.
        //       need to exclude 'downloadFile' from that class.
        include_once( 'kernel/setup/steps/ezstep_site_types.php' );

        $tpl = false;
        $http = false;
        $ini = false;
        $persistenceList = false;

        $downloader = new eZStepSiteTypes( $tpl, $http, $ini, $persistenceList );

        foreach( $downloadPackageList as $packageName )
        {
            showMessage( "$packageName" );
            $archiveName = $downloader->downloadFile( "$packageURL/$packageName.ezpkg", $packageDir );
            if ( $archiveName === false )
                showError( "Error while downloading: " . $downloader->ErrorMsg );
        }
    }

    //
    // import
    //
    showMessage2( "Importing..." );
    foreach( $packageList as $packageName )
    {
        showMessage( "$packageName" );
        $package = eZPackage::import( "$packageDir/$packageName.ezpkg", $packageName, false, $packageRepository );

        if( !is_object( $package ) )
            showError( "Faild to import '$packageName' package: err = $package" );
    }

    return true;
}

/*!
 install packages
*/
function installPackages( $packageList )
{
    global $cli;

    showMessage2( "Installing..." );

    $action = false;
    while( ( list( , $packageName ) = each( $packageList ) ) && $action != EZ_INSTALL_PACKAGE_EXTRA_ACTION_QUIT )
    {
        $action = false;

        $cli->output( $cli->stylize( 'emphasize', "Installing package '$packageName'" ), true );

        $params = array( 'install_type' => 'install' );

        $package = eZPackage::fetch( $packageName );
        if ( !is_object( $package ) )
        {
            showError( "can't fetch package '$packageName'. Aborting..." );
        }

        // skip installing 'site packages'
        $packageType = $package->attribute( 'type' );

        if ( $packageType == 'site' )
            continue;

        $packageItems = $package->installItemsList();

        while( ( list( , $item ) = each( $packageItems ) ) && $action != EZ_INSTALL_PACKAGE_EXTRA_ACTION_QUIT
                                                           && $action != EZ_INSTALL_PACKAGE_EXTRA_ACTION_SKIP_PACKAGE )
        {
            $itemInstalled = false;
            do
            {
                $action = false;
                $package->installItem( $item, $params );

                if ( isset( $params['error'] ) && is_array( $params['error'] ) && count( $params['error'] ) > 0 )
                {
                    global $autoMode;
                    if( $autoMode == 'on' )
                    {
                        switch( $packageType )
                        {
                            case 'contentclass':
                                $action = 2;
                                break;
                            case 'extension':
                                $action = 1;
                                break;
                            default:
                                $action = handlePackageError( $params['error'] );
                                break;
                        }
                    }
                    else
                    {
                        $action = handlePackageError( $params['error'] );
                    }

                    $params['error']['choosen_action'] = $action;
                }
                else
                {
                    $itemInstalled = true;
                }
            }
            while( !$itemInstalled && $action != EZ_INSTALL_PACKAGE_EXTRA_ACTION_QUIT
                                   && $action != EZ_INSTALL_PACKAGE_EXTRA_ACTION_SKIP_PACKAGE );
        }
    }
}

/*!
 update content classes
*/
function updateClasses()
{
    showMessage2( "Updateting content classes..." );

    eZWebinInstaller::addClassAttribute( array( 'class_identifier' => 'folder',
                                                'attribute_identifier' => 'tags',
                                                'attribute_name' => 'Tags',
                                                'datatype' => 'ezkeyword' ) );

    eZWebinInstaller::addClassAttribute( array( 'class_identifier' => 'folder',
                                                'attribute_identifier' => 'publish_date',
                                                'attribute_name' => 'Publish date',
                                                'datatype' => 'ezdatetime',
                                                'default_value' => 0 ) );

    $result = false;

    $classInfo = array( 'identifier' => 'template_look',
                        'attributes' => array( array( 'data_type_string' => 'ezurl',
                                                      'name' => 'Tag Cloud URL',
                                                      'identifier' => 'tag_cloud_url',
                                                      'can_translate' => 1,
                                                      'version' => 1,
                                                      'is_required' => 0,
                                                      'is_searchable' => 0 ) ) );

    $installer = new eZWebinInstaller();

    $attibutesDiff = $installer->updateContentClass( $classInfo );

    foreach( $attibutesDiff as $id )
    {
        if( ( $result = $installer->updateObject( $classInfo['identifier'], $id ) ) == false )
        {
            break;
        }
    }

    eZWebinInstaller::updateClassAttribute( array( 'class_identifier' => 'folder',
                                                   'class_attribute_identifier' => 'show_children',
                                                   'name' => 'Display sub items' ) );

    eZWebinInstaller::setRSSExport( array( 'creator' => '14',
                                           'access_url' => 'my_feed',
                                           'main_node_only' => '1',
                                           'number_of_objects' => '10',
                                           'rss_version' => '2.0',
                                           'status' => '1',
                                           'title' => 'My RSS Feed',
                                           'rss_export_itmes' => array( 0 => array( 'class_id' => '16',
                                                                                    'description' => 'intro',
                                                                                    'source_node_id' => '153',
                                                                                    'status' => '1',
                                                                                    'title' => 'title' ) ) ) );
    return $result;
}

/*!
 update content objects
*/
function updateObjects()
{
    showMessage2( "Updateting content objects..." );

    $installer = new eZWebinInstaller();

    $templateLookData = array( "tag_cloud_url" => array( "DataText" => "Tag cloud",
                                                         "Content" => "/content/view/tagcloud/2" ),
                               "footer_text" => array( "DataText" => "Copyright &#169; 2007 eZ systems AS. All rights reserved." ) );

    $result = $installer->addContentObjectData( $installer->setting( 'template_look_object_id' ), $templateLookData );

    return $result;
}

/*!
 update roles
*/
function updateRoles()
{
    showMessage2( "Updateing polices and roles..." );

    $installer = new eZWebinInstaller();

    // 'Anonymous role'
    $installer->removePoliciesForRole( array( 'role_name' => 'Anonymous',
                                              'policies' => array( array( 'module' => 'content',
                                                                          'function' => 'read' ) ) ) );

    $installer->addPoliciesForRole( array( 'role_name' => 'Anonymous',
                                           'policies' => array( array( 'module' => 'content',
                                                                       'function' => 'read',
                                                                       'limitation' => array( 'Class' => array( $installer->classIDbyIdentifier( array( 'class_identifier' => 'image' ) ) ,
                                                                                                                $installer->classIDbyIdentifier( array( 'class_identifier' => 'banner' ) ) ,
                                                                                                                $installer->classIDbyIdentifier( array( 'class_identifier' => 'flash' ) ) ,
                                                                                                                $installer->classIDbyIdentifier( array( 'class_identifier' => 'real_video' ) ) ,
                                                                                                                $installer->classIDbyIdentifier( array( 'class_identifier' => 'windows_media' ) ) ,
                                                                                                                $installer->classIDbyIdentifier( array( 'class_identifier' => 'quicktime' ) ) ),
                                                                                              'Section' => array( $installer->sectionIDbyName( array( 'section_name' => 'Media' ) ) ) ) ) ) ) );

    // 'Editor' role
    $installer->removePoliciesForRole( array( 'role_name' => 'Editor',
                                              'policies' => array( array( 'module' => 'content',
                                                                          'function' => '*' ) ) ) );
    $installer->addPoliciesForRole( array( 'role_name' => 'Editor',
                                           'policies' => array( array( 'module' => 'content',
                                                                      'function' => 'create',
                                                                      'limitation' => array( 'Class' => array( $installer->classIDbyIdentifier( array( 'class_identifier' => 'folder' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'link' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'file' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'product' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'feedback_form' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'frontpage' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'article' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'article_mainpage' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'article_subpage' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'blog' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'poll' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'multicalendar' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'documentation_page' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'infobox' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'flash' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'quicktime' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'windows_media' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'real_video' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'gallery' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'forum' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'forums' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'event_calendar' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'banner' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'image' ) ) ),
                                                                                             'ParentClass' => array( $installer->classIDbyIdentifier( array( 'class_identifier' => 'folder' ) ) ) ) ),
                                                               array( 'module' => 'content',
                                                                      'function' => 'create',
                                                                      'limitation' => array( 'Class' => array( $installer->classIDbyIdentifier( array( 'class_identifier' => 'blog_post' ) ) ),
                                                                                             'ParentClass' => array( $installer->classIDbyIdentifier( array( 'class_identifier' => 'blog' ) ) ) ) ),
                                                               array( 'module' => 'content',
                                                                      'function' => 'create',
                                                                      'limitation' => array( 'Class' => array( $installer->classIDbyIdentifier( array( 'class_identifier' => 'forum_topic' ) ) ),
                                                                                             'ParentClass' => array( $installer->classIDbyIdentifier( array( 'class_identifier' => 'forum' ) ) ) ) ),
                                                               array( 'module' => 'content',
                                                                      'function' => 'create',
                                                                      'limitation' => array( 'Class' => array( $installer->classIDbyIdentifier( array( 'class_identifier' => 'event' ) ) ),
                                                                                             'ParentClass' => array( $installer->classIDbyIdentifier( array( 'class_identifier' => 'event_calendar' ) ) ) ) ),
                                                               array( 'module' => 'content',
                                                                      'function' => 'create',
                                                                      'limitation' => array( 'Class' => array( $installer->classIDbyIdentifier( array( 'class_identifier' => 'image' ) ) ),
                                                                                             'ParentClass' => array( $installer->classIDbyIdentifier( array( 'class_identifier' => 'gallery' ) ) ) ) ),
                                                               array( 'module' => 'content',
                                                                      'function' => 'create',
                                                                      'limitation' => array( 'Class' => array( $installer->classIDbyIdentifier( array( 'class_identifier' => 'folder' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'link' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'feedback_form' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'frontpage' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'documentation_page' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'gallery' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'event_calendar' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'multicalendar' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'forums' ) ) ),
                                                                                             'ParentClass' => array( $installer->classIDbyIdentifier( array( 'class_identifier' => 'frontpage' ) ) ) ),
                                                               array( 'module' => 'content',
                                                                      'function' => 'edit' ),
                                                               array( 'module' => 'content',
                                                                      'function' => 'read',
                                                                      'limitation' => array( 'Section' => array( $installer->sectionIDbyName( array( 'section_name' => 'Standard' ) ),
                                                                                                                 $installer->sectionIDbyName( array( 'section_name' => 'Restricted' ) ),
                                                                                                                 $installer->sectionIDbyName( array( 'section_name' => 'Media' ) ) ) ) ) ) ) ) );


    $installer->addPoliciesForRole( array( 'role_name' => 'Editor',
                                           'policies' =>  array( array( 'module' => 'content',
                                                                        'function' => 'manage_locations' ),
                                                                 array( 'module' => 'shop',
                                                                        'function' => 'administrate' ) ) ) );

    // 'Member' role
    $installer->removePoliciesForRole( array( 'role_name' => 'Member',
                                              'policies' => array( array( 'module' => 'content',
                                                                          'function' => '*' ) ) ) );
    $installer->addPoliciesForRole( array( 'role_name' => 'Member',
                                           'policies' => array( array( 'module' => 'content',
                                                                      'function' => 'create',
                                                                      'limitation' => array( 'Class' => array( $installer->classIDbyIdentifier( array( 'class_identifier' => 'forum_topic' ) ) ),
                                                                                             'Section' => array( $installer->sectionIDbyName( array( 'section_name' => 'Standard' ) ) ),
                                                                                             'ParentClass' => array( $installer->classIDbyIdentifier( array( 'class_identifier' => 'forum' ) ) ) ) ),
                                                                array( 'module' => 'content',
                                                                      'function' => 'create',
                                                                      'limitation' => array( 'Class' => array( $installer->classIDbyIdentifier( array( 'class_identifier' => 'forum_reply' ) ) ),
                                                                                             'Section' => array( $installer->sectionIDbyName( array( 'section_name' => 'Standard' ) ) ),
                                                                                             'ParentClass' => array( $installer->classIDbyIdentifier( array( 'class_identifier' => 'forum_topic' ) ) ) ) ),
                                                                array( 'module' => 'content',
                                                                      'function' => 'create',
                                                                      'limitation' => array( 'Class' => array( $installer->classIDbyIdentifier( array( 'class_identifier' => 'comment' ) ) ),
                                                                                             'Section' => array( $installer->sectionIDbyName( array( 'section_name' => 'Standard' ) ) ),
                                                                                             'ParentClass' => array( $installer->classIDbyIdentifier( array( 'class_identifier' => 'article' ) ),
                                                                                                                     $installer->classIDbyIdentifier( array( 'class_identifier' => 'blog' ) ),
                                                                                                                     $installer->classIDbyIdentifier( array( 'class_identifier' => 'article_mainpage' ) ) ) ) ),
                                                                array( 'module' => 'content',
                                                                      'function' => 'edit',
                                                                      'limitation' => array( 'Class' => array( $installer->classIDbyIdentifier( array( 'class_identifier' => 'comment' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'forum_topic' ) ),
                                                                                                               $installer->classIDbyIdentifier( array( 'class_identifier' => 'forum_reply' ) ) ),
                                                                                             'Section' => array( $installer->sectionIDbyName( array( 'section_name' => 'Standard' ) ) ),
                                                                                             'Owner' => 1 ) ) ) ) );
}

/*!
*/
function updateINI()
{
    showMessage2( "Updateing INI-files..." );

    $userSiteaccessName = 'ezwebin_site';
    $parameters = array();

    $extraSettings = array();
    $extraSettings[] = eZSiteOverrideINISettings();
    $extraSettings[] = eZSiteImageINISettings();
    $extraSettings[] = eZSiteContentINISettings( $parameters );
    $extraSettings[] = eZSiteBrowseINISettings( $parameters );
    $extraSettings[] = eZSiteTemplateINISettings( $parameters );

    $extraCommonSettings = array();
    $extraCommonSettings[] = eZCommonContentINISettings( $parameters );

    // NOTE: it's copy/paste from ezstep_create_sites.php
    foreach ( $extraSettings as $extraSetting )
    {
        if ( $extraSetting === false )
            continue;

        $iniName = $extraSetting['name'];
        $settings = $extraSetting['settings'];
        $resetArray = false;
        if ( isset( $extraSetting['reset_arrays'] ) )
            $resetArray = $extraSetting['reset_arrays'];

        $tmpINI =& eZINI::create( $iniName );
        // Set ReadOnlySettingsCheck to false: towards
        // Ignore site.ini[eZINISettings].ReadonlySettingList[] settings when saving ini variables.
        $tmpINI->setReadOnlySettingsCheck( false );

        $tmpINI->setVariables( $settings );
        if ( $iniName == 'site.ini' )
        {
            $siteINIStored = true;
            $tmpINI->setVariables( $siteINIChanges );
            $tmpINI->setVariable( 'DesignSettings', 'SiteDesign', $userDesignName );
            $tmpINI->setVariable( 'DesignSettings', 'AdditionalSiteDesignList', array( 'base' ) );
        }
        else if ( $iniName == 'design.ini' )
        {
            if ( $siteCSS )
                $tmpINI->setVariable( 'StylesheetSettings', 'SiteCSS', $siteCSS );
            if ( $classesCSS )
                $tmpINI->setVariable( 'StylesheetSettings', 'ClassesCSS', $classesCSS );
            $designINIStored = true;
        }
        $tmpINI->save( false, '.append.php', false, true, "settings/siteaccess/$userSiteaccessName", $resetArray );

        if ( $siteType['existing_database'] != EZ_SETUP_DB_DATA_KEEP )
        {
            // setting up appropriate data in look&feel object
            $templateLookObject = eZContentObject::fetch( 54 );
            $dataMap =& $templateLookObject->fetchDataMap();
            $dataMap[ 'title' ]->setAttribute( 'data_text', $siteINIChanges['SiteSettings']['SiteName'] );
            $dataMap[ 'title' ]->store();
            $dataMap[ 'siteurl' ]->setAttribute( 'data_text', $siteINIChanges['SiteSettings']['SiteURL'] );
            $dataMap[ 'siteurl' ]->store();
            $dataMap[ 'email' ]->setAttribute( 'data_text', $siteINIChanges['MailSettings']['AdminEmail'] );
            $dataMap[ 'email' ]->store();
            $class = eZContentClass::fetch( $templateLookObject->attribute( 'contentclass_id' ) );
            $objectName = $class->contentObjectName( $templateLookObject );
            $templateLookObject->setName( $objectName );
            $templateLookObject->store();
        }
    }


    foreach ( $extraCommonSettings as $extraSetting )
    {
        if ( $extraSetting === false )
            continue;

        $iniName = $extraSetting['name'];
        $settings = $extraSetting['settings'];
        $resetArray = false;
        if ( isset( $extraSetting['reset_arrays'] ) )
            $resetArray = $extraSetting['reset_arrays'];

        if ( $iniName == 'site.ini' )
        {
            $siteINISettings[] = $settings;
            continue;
        }

        if ( file_exists( 'settings/override/' . $iniName . '.append' ) ||
             file_exists( 'settings/override/' . $iniName . '.append.php' ) )
        {
            $tmpINI =& eZINI::instance( $iniName, 'settings/override', null, null, false, true );
        }
        else
        {
            $tmpINI =& eZINI::create( $iniName );
        }
        // Set ReadOnlySettingsCheck to false: towards
        // Ignore site.ini[eZINISettings].ReadonlySettingList[] settings when saving ini variables.
        $tmpINI->setReadOnlySettingsCheck( false );
        $tmpINI->setVariables( $settings );
        $tmpINI->save( false, '.append.php', false, true, "settings/override", $resetArray );
    }
}

// script initializing
$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'description' => ( "\n" .
                                                         "This script will upgrade ezwebin 1.1-1 to 1.2-0\n" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => false,
                                      'user' => true ) );
$script->startup();

$scriptOptions = $script->getOptions( "[repository:][package:][package-dir:][url:][auto-mode:]",
                                      "",
                                      array( 'repository' => "Path to repository where unpacked(unarchived) packages are \n" .
                                                         "placed. it's relative to 'var/[site.ini].[FileSettings].[StorageDir]/[package.ini].[RepositorySettings].[RepositoryDirectory]' \n".
                                                         "(default is 'var/storage/packages/ez_systems')",
                                             'package' => "Package(s) to install, f.e. 'ezwebin-classes'",
                                             'package-dir' => "Path to directory with packed(ezpkg) packages(default is '/tmp/ezwebin') ",
                                             'url' => "URL to download packages, f.e. 'http://packages.ez.no/ezpublish/3.9'.\n" .
                                                      "'package-dir' can be specified to store uploaded packages on local computer.\n" .
                                                      "if 'package-dir' is not specified then default dir('/tmp/ezwebin') will be used.",
                                             'auto-mode' => "[on/off]. Do not ask what to do in case of confilicts. By default is 'on'"
                                           ),
                                      false,
                                      array( 'user' => true )
                                     );

$script->initialize();


/**************************************************************
* process options                                             *
***************************************************************/

//
// 'repository' option
//
$packageRepository = $scriptOptions['repository'];
if ( !$packageRepository )
{
    $packageRepository = "ez_systems";
}


//
// 'package' option
//
$packageList = $scriptOptions['package'];
if ( !$packageList )
{
    $packageList = array(   'ezwebin_classes'
                          , 'ezwebin_extension'
                          // skipping content for now
                          //, 'ezwebin_banners'
                          //, 'ezwebin_democontent'
                          , 'ezwebin_design'
                          , 'ezwebin_site'
                        );
}
else
{
    $packageList = split( ' ', $packageList );
}

//
// 'package-dir' option
//
$packageDir = $scriptOptions['package-dir'] ? $scriptOptions['package-dir'] : "/tmp/ezwebin";

//
// 'url' option
//
$packageURL = $scriptOptions['url'];
if ( !$packageURL )
{
    $packageINI =& eZINI::instance( 'package.ini' );
    $packageURL = $packageINI->variable( 'RepositorySettings', 'RemotePackagesIndexURL' );
}

//
// 'auto-mode' option
//
global $autoMode;
$autoMode = $scriptOptions['auto-mode'];
if( $autoMode != 'off' )
{
    $autoMode = 'on';
    $importDir = eZPackage::repositoryPath() . "/$packageRepository";
    showWarning( "Processing in auto-mode: \n".
                 "- packages will be downloaded to '$packageDir';\n" .
                 "- packages will be imported to '$importDir';\n" .
                 "- installing of existing classes will be skipped;\n" .
                 "- all files(extesion, design, downloaded and imported packages) will be overwritten;" );
    $action = getUserInput( "Continue? [y/n]: ");
    if( strpos( $action, 'y' ) !== 0 )
        $script->shutdown( 0, 'Done' );
}

/**************************************************************
* do the work                                                 *
***************************************************************/

if( downloadPackages( $packageList, $packageURL, $packageDir, $packageRepository ) )
{
    // install
    installPackages( $packageList );
}

if( file_exists( installScriptDir( $packageRepository ) ) )
{
    include_once( installScriptDir( $packageRepository ) . "/settings/ezwebininstaller.php" );
    include_once( installScriptDir( $packageRepository ) . "/settings/ini-site.php" );
    include_once( installScriptDir( $packageRepository ) . "/settings/ini-common.php" );

    updateClasses();

    updateObjects();

    updateRoles();

    updateINI();
}
else
{
    showWarning( "no data for updating content classes, objects, roles, ini" );
}

$script->shutdown( 0, 'Done' );

?>