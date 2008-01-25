#!/usr/bin/env php
<?php
//
// Created on: <22-Aug-2006 12:05:27 ks>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

//include_once( 'lib/ezutils/classes/ezcli.php' );
//include_once( 'kernel/classes/ezscript.php' );

require 'autoload.php';

$cli = eZCLI::instance();

$script = eZScript::instance( array( 'description' => "\nThis script is optional for upgrading to 3.10.\n" .
                                                       "The script adds a role which contains a policy 'content/tipafriend' and" .
                                                       "\nassigns this role to all user groups except anonymous. That will give " .
                                                       "\npossibility to use tipafriend view for all users except anonymous." .
                                                       "\n\nNote: siteacces, login and password options are required and" .
                                                       "\nmust to be set to admin siteaccess and admin login/passsword accordingly" .
                                                       "\n\n(See doc/feature/(3.8|3.9|3.10)/content_tipafriend_policy.txt for more information).",
                                      'use-session' => false,
                                      'use-modules' => false,
                                      'use-extensions' => true ) );

$script->startup();
$options = $script->getOptions( '', '', false, false,
                                array( 'siteaccess' => true,
                                       'user' => true ) );

$siteAccess = $options['siteaccess'] ? $options['siteaccess'] : false;
$script->setUseSiteAccess( $siteAccess );
$script->initialize();

$cli->notice( "\nStart." );

$contentIni = eZINI::instance( 'content.ini' );
$userRootNodeID = $contentIni->variable( 'NodeSettings', 'UserRootNode' );

$siteIni = eZINI::instance( 'site.ini' );
$anonymousUserID = $siteIni->variable( 'UserSettings', 'AnonymousUserID' );
$anonymousUser = eZUser::fetch( $anonymousUserID );
$anonymousUsers = array();
if ( is_object( $anonymousUser ) )
{
    $anonymousUsers = $anonymousUser->groups();
    $anonymousUsers[] = $anonymousUserID;
}

//include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
//include_once( 'kernel/classes/ezrole.php' );

$topUserNodes = eZContentObjectTreeNode::subTreeByNodeID( array( 'Depth' => 1 ), $userRootNodeID );

if ( count( $topUserNodes ) == 0 )
{
    $cli->warning( "Unable to retrieve the user root node. Please make sure\n" .
                   "you log in to the system with the administrator's user\n" .
                   "acount by using the -l and -p command line options." );
    $script->shutdown( 1 );
}

$roleName = 'Tipafriend Role';
$role = eZRole::fetchByName( $roleName );
if ( is_object( $role ) )
{
    $cli->warning( "The 'Tipafriend Role' already exists in the system. This means that\n" .
                   "the script was already run before or the same role was added manually.\n" .
                   "The role will not be added. Check the role settings of the system." );
}
else
{
    $userInput = '';
    $usersToAssign = array();
    $stdin = fopen( "php://stdin", "r+" );
    foreach ( $topUserNodes as $userNode )
    {
        if ( $userInput != 'a' )
        {
            $name = $userNode->getName();
            if ( in_array( $userNode->attribute( 'contentobject_id' ), $anonymousUsers ) )
                $cli->output( "Note: the '$name' group/user is anonymous." );
            $cli->output( "Assign 'Tipafriend Role' to the '$name' group/user? y(yes)/n(no)/a(all)/s(skip all): ", false );
            $userInput = fgets( $stdin );
            $userInput = trim( $userInput );
        }
        if ( $userInput == 'y' or $userInput == 'a' )
        {
            $usersToAssign[] = $userNode->attribute( 'contentobject_id' );
        }
        else if ( $userInput == 's' )
        {
            break;
        }
    }
    fclose( $stdin );

    if ( count( $usersToAssign ) > 0 )
    {
        $role = eZRole::create( $roleName );
        $role->store();
        $role->appendPolicy( 'content', 'tipafriend' );
        $role->store();

        foreach ( $usersToAssign as $userID )
        {
            $role->assignToUser( $userID );
        }
        // clear role cache
        eZRole::expireCache();
        //include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearAllContentCache();
        // clear policy cache
        eZUser::cleanupCache();
    }
    else
    {
        $cli->notice( "\nThe role wasn't added because you didn't choose any group to assign." );
    }
}

$cli->notice( "\nDone." );
$script->shutdown();

?>
