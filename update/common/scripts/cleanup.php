#!/usr/bin/env php
<?php
//
// Created on: <18-Dec-2003 17:44:15 amos>
//
// Copyright (C) 1999-2004 eZ systems as. All rights reserved.
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

set_time_limit( 0 );

include_once( "lib/ezutils/classes/ezextension.php" );
include_once( "lib/ezutils/classes/ezmodule.php" );
include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'kernel/classes/ezscript.php' );

$cli =& eZCLI::instance();
$script =& eZScript::instance( array( 'debug-message' => '',
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$endl = $cli->endlineString();
$webOutput = $cli->isWebOutput();

function help()
{
    $argv = $_SERVER['argv'];
    $cli =& eZCLI::instance();
    $cli->output( "Usage: " . $argv[0] . " [OPTION]... NAME [NAME]...\n" .
                  "eZ publish database cleanup.\n" .
                  "Will cleanup various data from the currently used database in eZ publish\n" .
                  "\n" .
                  "General options:\n" .
                  "  -h,--help          display this help and exit \n" .
                  "  -q,--quiet         do not give any output except when errors occur\n" .
                  "  -s,--siteaccess    selected siteaccess for operations, if not specified default siteaccess is used\n" .
                  "  -d,--debug         display debug output at end of execution\n" .
                  "  --db-host=HOST     Use database host HOST\n" .
                  "  --db-user=USER     Use database user USER\n" .
                  "  --db-password=PWD  Use database password PWD\n" .
                  "  --db-database=DB   Use database named DB\n" .
                  "  --db-driver=DRIVER Use database driver DRIVER\n" .
                  "  -c,--colors        display output using ANSI colors\n" .
                  "  --sql              display sql queries\n" .
                  "  --logfiles         create log files\n" .
                  "  --no-logfiles      do not create log files (default)\n" .
                  "  --no-colors        do not use ANSI coloring (default)\n" .
                  "\n" .
                  "Possible values for NAME is:\n" .
                  "session, preferences, browse, tipafriend, shop, forgotpassword, workflow,\n" .
                  "collaboration, collectedinformation, notification, searchstats or all (for all items)\n" );
}

function changeSiteAccessSetting( &$siteaccess, $optionData )
{
    global $isQuiet;
    $cli =& eZCLI::instance();
    if ( file_exists( 'settings/siteaccess/' . $optionData ) )
    {
        $siteaccess = $optionData;
        if ( !$isQuiet )
            $cli->notice( "Using siteaccess $siteaccess for search index update" );
    }
    else
    {
        if ( !$isQuiet )
            $cli->notice( "Siteaccess $optionData does not exist, using default siteaccess" );
    }
}

$siteaccess = false;
$debugOutput = false;
$allowedDebugLevels = false;
$useDebugAccumulators = false;
$useDebugTimingpoints = false;
$useIncludeFiles = false;
$useColors = false;
$isQuiet = false;
$useLogFiles = false;
$showSQL = false;

$dbUser = false;
$dbPassword = false;
$dbHost = false;
$dbName = false;
$dbImpl = false;

$cleanAllItems = false;
$clean = array( 'session' => false,
                'preferences' => false,
                'browse' => false,
                'tipafriend' => false,
                'shop' => false,
                'forgotpassword' => false,
                'workflow' => false,
                'collaboration' => false,
                'collectedinformation' => false,
                'notification' => false,
                'searchstats' => false );

$optionsWithData = array( 's' );
$longOptionsWithData = array( 'siteaccess' );

$readOptions = true;

for ( $i = 1; $i < count( $argv ); ++$i )
{
    $arg = $argv[$i];
    if ( $readOptions and
         strlen( $arg ) > 0 and
         $arg[0] == '-' )
    {
        if ( strlen( $arg ) > 1 and
             $arg[1] == '-' )
        {
            $flag = substr( $arg, 2 );
            if ( in_array( $flag, $longOptionsWithData ) )
            {
                $optionData = $argv[$i+1];
                ++$i;
            }
            if ( $flag == 'help' )
            {
                help();
                exit();
            }
            else if ( $flag == 'siteaccess' )
            {
                changeSiteAccessSetting( $siteaccess, $optionData );
            }
            else if ( preg_match( "/^db-host=(.*)$/", $flag, $matches ) )
            {
                $dbHost = $matches[1];
            }
            else if ( preg_match( "/^db-user=(.*)$/", $flag, $matches ) )
            {
                $dbUser = $matches[1];
            }
            else if ( preg_match( "/^db-password=(.*)$/", $flag, $matches ) )
            {
                $dbPassword = $matches[1];
            }
            else if ( preg_match( "/^db-database=(.*)$/", $flag, $matches ) )
            {
                $dbName = $matches[1];
            }
            else if ( preg_match( "/^db-driver=(.*)$/", $flag, $matches ) )
            {
                $dbImpl = $matches[1];
            }
            else if ( $flag == 'debug' )
            {
                $debugOutput = true;
            }
            else if ( $flag == 'quiet' )
            {
                $isQuiet = true;
            }
            else if ( $flag == 'colors' )
            {
                $useColors = true;
            }
            else if ( $flag == 'no-colors' )
            {
                $useColors = false;
            }
            else if ( $flag == 'no-logfiles' )
            {
                $useLogFiles = false;
            }
            else if ( $flag == 'logfiles' )
            {
                $useLogFiles = true;
            }
            else if ( $flag == 'sql' )
            {
                $showSQL = true;
            }
        }
        else
        {
            $flag = substr( $arg, 1, 1 );
            $optionData = false;
            if ( in_array( $flag, $optionsWithData ) )
            {
                if ( strlen( $arg ) > 2 )
                {
                    $optionData = substr( $arg, 2 );
                }
                else
                {
                    $optionData = $argv[$i+1];
                    ++$i;
                }
            }
            if ( $flag == 'h' )
            {
                help();
                exit();
            }
            else if ( $flag == 'q' )
            {
                $isQuiet = true;
            }
            else if ( $flag == 'c' )
            {
                $useColors = true;
            }
            else if ( $flag == 'd' )
            {
                $debugOutput = true;
                if ( strlen( $arg ) > 2 )
                {
                    $levels = explode( ',', substr( $arg, 2 ) );
                    $allowedDebugLevels = array();
                    foreach ( $levels as $level )
                    {
                        if ( $level == 'all' )
                        {
                            $useDebugAccumulators = true;
                            $allowedDebugLevels = false;
                            $useDebugTimingpoints = true;
                            break;
                        }
                        if ( $level == 'accumulator' )
                        {
                            $useDebugAccumulators = true;
                            continue;
                        }
                        if ( $level == 'timing' )
                        {
                            $useDebugTimingpoints = true;
                            continue;
                        }
                        if ( $level == 'include' )
                        {
                            $useIncludeFiles = true;
                        }
                        if ( $level == 'error' )
                            $level = EZ_LEVEL_ERROR;
                        else if ( $level == 'warning' )
                            $level = EZ_LEVEL_WARNING;
                        else if ( $level == 'debug' )
                            $level = EZ_LEVEL_DEBUG;
                        else if ( $level == 'notice' )
                            $level = EZ_LEVEL_NOTICE;
                        else if ( $level == 'timing' )
                            $level = EZ_LEVEL_TIMING;
                        $allowedDebugLevels[] = $level;
                    }
                }
            }
            else if ( $flag == 's' )
            {
                changeSiteAccessSetting( $siteaccess, $optionData );
            }
        }
    }
    else
    {
        $item = strtolower( $arg );
        if ( $item == 'all' )
            $cleanAllItems = true;
        else
            $cleanItems[] = $item;
    }
}

if ( $cleanAllItems )
{
    $names = array_keys( $clean );
    foreach ( $names as $name )
    {
        $clean[$name] = true;
    }
}
else
{
    if ( count( $cleanItems ) == 0 )
    {
        help();
        exit;
    }
    foreach ( $cleanItems as $name )
    {
        $clean[$name] = true;
    }
}


$script->setUseDebugOutput( $debugOutput );
$script->setAllowedDebugLevels( $allowedDebugLevels );
$script->setUseDebugAccumulators( $useDebugAccumulators );
$script->setUseDebugTimingPoints( $useDebugTimingpoints );
$script->setUseIncludeFiles( $useIncludeFiles );

if ( $webOutput )
    $useColors = true;

$cli->setUseStyles( $useColors );
$script->setDebugMessage( "\n\n" . str_repeat( '#', 36 ) . $cli->style( 'emphasize' ) . " DEBUG " . $cli->style( 'emphasize-end' )  . str_repeat( '#', 36 ) . "\n" );

$script->setUseSiteAccess( $siteaccess );

$script->initialize();

$db =& eZDB::instance();
if ( $dbHost or $dbName or $dbUser or $dbImpl )
{
    $params = array();
    if ( $dbHost !== false )
        $params['server'] = $dbHost;
    if ( $dbUser !== false )
    {
        $params['user'] = $dbUser;
        $params['password'] = '';
    }
    if ( $dbPassword !== false )
        $params['password'] = $dbPassword;
    if ( $dbName !== false )
        $params['database'] = $dbName;
    $db =& eZDB::instance( $dbImpl, $params, true );
    eZDB::setInstance( $db );
}

$db->setIsSQLOutputEnabled( $showSQL );

include_once( 'kernel/classes/ezpersistentobject.php' );

include_once( 'lib/ezutils/classes/ezsession.php' );
if ( $clean['session'] )
{
    $cli->output( "Removing all sessions" );
    eZSessionEmpty();
    include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
    eZUser::cleanupSessionLink();
}
else
{
    $cli->output( "Removing expired sessions,", false );
    eZSessionGarbageCollector();
    $activeCount = eZSessionCountActive();
    $cli->output( " " . $cli->stylize( 'emphasize', $activeCount ) . " left" );
}

if ( $clean['preferences'] )
{
    include_once( 'kernel/classes/ezpreferences.php' );
    $cli->output( "Removing all preferences" );
    eZPreferences::cleanup();
}

if ( $clean['browse'] )
{
    include_once( 'kernel/classes/ezcontentbrowserecent.php' );
    include_once( 'kernel/classes/ezcontentbrowsebookmark.php' );
    $cli->output( "Removing all recent items and bookmarks for browse page" );
    eZContentBrowseRecent::cleanup();
    eZContentBrowseBookmark::cleanup();
}

if ( $clean['tipafriend'] )
{
    include_once( 'kernel/classes/eztipafriendcounter.php' );
    $cli->output( "Removing all counters for tip-a-friend" );
    eZTipafriendCounter::cleanup();
}

if ( $clean['shop'] )
{
    include_once( 'kernel/classes/ezbasket.php' );
    $cli->output( "Removing all baskets" );
    eZBasket::cleanup();
    include_once( 'kernel/classes/ezwishlist.php' );
    $cli->output( "Removing all wishlists" );
    eZWishList::cleanup();
    include_once( 'kernel/classes/ezorder.php' );
    $cli->output( "Removing all orders" );
    eZOrder::cleanup();
    $productCount = eZProductCollection::count();
    if ( $productCount > 0 )
    {
        $cli->warning( "$productCount product collections still exists, must be a leak" );
    }
}

if ( $clean['forgotpassword'] )
{
    include_once( 'kernel/classes/datatypes/ezuser/ezforgotpassword.php' );
    $cli->output( "Removing all forgot password requests" );
    eZForgotPassword::cleanup();
}

if ( $clean['workflow'] )
{
    include_once( 'lib/ezutils/classes/ezoperationmemento.php' );
    include_once( 'kernel/classes/ezworkflowprocess.php' );
    $cli->output( "Removing all workflow processes and operation mementos" );
    eZOperationMemento::cleanup();
    eZWorkflowProcess::cleanup();
}

if ( $clean['collaboration'] )
{
    include_once( 'kernel/classes/ezcollaborationitem.php' );
    $cli->output( "Removing all collaboration elements" );
    eZCollaborationItem::cleanup();
}

if ( $clean['collectedinformation'] )
{
    include_once( 'kernel/classes/ezinformationcollection.php' );
    $cli->output( "Removing all collected information" );
    eZInformationCollection::cleanup();
}

if ( $clean['notification'] )
{
    include_once( 'kernel/classes/notification/eznotificationevent.php' );
    include_once( 'kernel/classes/notification/eznotificationcollection.php' );
    include_once( 'kernel/classes/notification/eznotificationeventfilter.php' );
    $cli->output( "Removing all notifications events" );
    eZNotificationEvent::cleanup();
    eZNotificationCollection::cleanup();
    eZNotificationEventFilter::cleanup();
}

if ( $clean['searchstats'] )
{
    include_once( 'kernel/classes/ezsearchlog.php' );
    $cli->output( "Removing all search statistics" );
    eZSearchLog::removeStatistics();
}


$script->shutdown();

?>
