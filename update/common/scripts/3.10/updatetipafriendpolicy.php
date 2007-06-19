#!/usr/bin/env php
<?php
//
// Created on: <22-Aug-2006 12:05:27 ks>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
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

include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();

$script =& eZScript::instance( array( 'description' => "\nThis script is optional for upgrading to 3.10.\n" .
                                                       "The script adds a role which contains a policy 'content/tipafriend' and" .
                                                       "\nassigns this role to all user groups except anonymous. That will give " .
                                                       "\npossibility to use tipafriend view for all users except anonymous." .
                                                       "\n\nNote: siteacces, login and password options are required and" .
                                                       "\nmust to be set to admin siteaccess and admin login/passsword accordingly" .
                                                       "\n\n(See doc/feature/../content_tipafriend_policy.txt for more information).",
                                      'use-session' => false,
                                      'use-modules' => false,
                                      'use-extensions' => true ) );

$script->startup();
$options = $script->getOptions( '', '', false, false,
                                array( 'siteaccess' => true,
                                       'user' => true )
                                );

$siteAccess = $options['siteaccess'] ? $options['siteaccess'] : false;
$script->setUseSiteAccess( $siteAccess );
$script->initialize();

$cli->notice( "\nStart." );

$contentIni =& eZINI::instance( 'content.ini' );
$userRootNodeID = $contentIni->variable( 'NodeSettings', 'UserRootNode' );

$siteIni =& eZINI::instance( 'site.ini' );
$anonymousUserID = $siteIni->variable( 'UserSettings', 'AnonymousUserID' );
$anonymousUser = eZUser::fetch( $anonymousUserID );
$anonymousUserGroups = array();
if ( is_object( $anonymousUser ) )
{
    $anonymousUserGroups = $anonymousUser->groups();
    $anonymousUserGroups[] = $anonymousUserID;
}

include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'kernel/classes/ezrole.php' );

$topUserNodes =& eZContentObjectTreeNode::subTree( array( 'Depth' => 1 ), $userRootNodeID );

$roleName = 'Tipafriend Role';
$role = eZRole::fetchByName( $roleName );
if ( is_object( $role ) )
{
    $cli->warning( "The 'Tipafriend Role' already exists in the system. This means that\n" .
                   "the script was already run before or you have added the role manually.\n" .
                   "The role will not be added. Check the role settings of the system." );
    $role->remove();
}
else
{
    $role = eZRole::create( $roleName );
    $role->store();
    $role->appendPolicy( 'content', 'tipafriend' );
    $role->store();

    $userInput = '';
    $roleWasAssigned = false;
    $stdin = fopen( "php://stdin", "r+" );

    foreach ( $topUserNodes as $userGroupNode )
    {
        if ( !in_array( $userGroupNode->attribute( 'contentobject_id' ), $anonymousUserGroups ) )
        {
            if ( $userInput != 'a' )
            {
                $name = $userGroupNode->getName();
                $cli->output( "Assign 'Tipafriend Role' to the group/user '$name'? y(yes)/n(no)/a(all)/s(skip all): ", false );
                $userInput = fgets( $stdin );
                $userInput = trim( $userInput, "\n" );
            }
            if ( $userInput == 'y' or $userInput == 'a' )
            {
                $role->assignToUser( $userGroupNode->attribute( 'contentobject_id' ) );
                $roleWasAssigned = true;
            }
            else if ( $userInput == 's' )
            {
                break;
            }
        }
    }
    fclose( $stdin );

    if ( $roleWasAssigned )
    {
        // clear role cache
        eZRole::expireCache();
        include_once( 'kernel/classes/ezcontentcachemanager.php' );
        eZContentCacheManager::clearAllContentCache();
        // clear policy cache
        eZUser::cleanupCache();
    }
    else
    {
        $role->remove();
        $cli->notice( "\nThe role wasn't added because you've chosen no group to assign." );
    }
}

$cli->notice( "\nDone." );
$script->shutdown();

?>
