<?php
//
// Created on: <15-Apr-2004 11:25:31 bh>
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
include_once( 'kernel/common/template.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'lib/ezutils/classes/ezsession.php' );

$tpl =& templateInit();
$sessionsRemoved = false;
$http =& eZHTTPTool::instance();

$module =& $Params["Module"];
$param['limit'] = 50;

$filterType = 'registered';
if ( $http->hasSessionVariable( 'eZSessionFilterType' ) )
    $filterType = $http->sessionVariable( 'eZSessionFilterType' );

$userID = $Params['UserID'];

if ( $module->isCurrentAction( 'ShowAllUsers' ) )
{
    return $module->redirectToView( 'session' );
}
else if ( $module->isCurrentAction( 'ChangeFilter' ) )
{
    $filterType = $module->actionParameter( 'FilterType' );
    if ( !in_array( $filterType, array( 'everyone', 'registered', 'anonymous' ) ) )
        $filterType = 'registered';
    $http->setSessionVariable( 'eZSessionFilterType', $filterType );
}
else if ( $module->isCurrentAction( 'RemoveAllSessions' ) )
{
    eZSessionEmpty();
    $sessionsRemoved = true;
}
else if ( $module->isCurrentAction( 'RemoveTimedOutSessions' ) )
{
    eZSessionGarbageCollector();
    $sessionsRemoved = true;
}
else if ( $module->isCurrentAction( 'RemoveSelectedSessions' ) )
{
    if ( $userID )
    {
        if ( eZHTTPTool::hasPostVariable( 'SessionKeyArray' ) )
        {
            $sessionKeyArray = eZHTTPTool::postVariable( 'SessionKeyArray' );
            foreach ( $sessionKeyArray as $sessionKeyItem )
            {
                eZSessionDestroy( $sessionKeyItem );
            }
        }
    }
    else
    {
        if ( eZHTTPTool::hasPostVariable( 'UserIDArray' ) )
        {
            $userIDArray = eZHTTPTool::postVariable( 'UserIDArray' );
            if ( count( $userIDArray ) > 0 )
            {
                include_once( 'lib/ezdb/classes/ezdb.php' );
                $db =& eZDB::instance();
                $rows = $db->arrayQuery( "SELECT session_key FROM ezsession WHERE user_id IN ( " . implode( ', ', $userIDArray ) . " )" );
                foreach ( $rows as $row )
                {
                    eZSessionDestroy( $row['session_key'] );
                }
            }
        }
    }
}

$viewParameters = $Params['UserParameters'];
if ( isset( $viewParameters['offset'] ) and
     is_numeric( $viewParameters['offset'] ) )
{
    $param['offset'] = $viewParameters['offset'];
}
else
{
    $param['offset'] = 0;
    $viewParameters['offset'] = 0;
}


/*
  Get all sessions by limit and offset, and returns it
*/
function &eZFetchActiveSessions( $params = array() )
{
    if ( isset( $params['limit'] ) )
        $limit = $params['limit'];
    else
        $limit = 20;

    if ( isset( $params['offset'] ) )
        $offset = $params['offset'];
    else
        $offset = 0;
    $orderBy = "ezsession.expiration_time DESC";

    switch ( $params['sortby'] )
    {
        case 'login':
        {
            $orderBy = "ezuser.login ASC";
        } break;

        case 'email':
        {
            $orderBy = "ezuser.email ASC";
        } break;

        case 'name':
        {
            $orderBy = "ezcontentobject.name ASC";
        } break;

        case 'idle':
        {
            $orderBy = "ezsession.expiration_time DESC";
        } break;
    }
    $filterType = $params['filter_type'];
    switch ( $filterType )
    {
        case 'registered':
        {
            $filterSQL = 'AND ezsession.user_id != ' . EZ_USER_ANONYMOUS_ID;
        } break;

        case 'anonymous':
        {
            $filterSQL = 'AND ezsession.user_id = ' . EZ_USER_ANONYMOUS_ID;
        } break;

        case 'everyone':
        default:
        {
            $filterSQL = '';
        } break;
    }
    
    $userID = $params['user_id'];
    $countField = '';
    $countGroup = '';
    if ( $userID )
    {
        $filterSQL = 'AND ezsession.user_id = ' .  (int)$userID;
        $expirationSQL = 'ezsession.expiration_time';
    }
    else
    {
        $countField = ', count( ezsession.user_id ) AS count';
        $countGroup = 'GROUP BY ezsession.user_id';
        $expirationSQL = 'max( ezsession.expiration_time ) as expiration_time';
    }

    include_once( 'lib/ezdb/classes/ezdb.php' );
    $db =& eZDB::instance();
    $query = "SELECT ezsession.user_id, $expirationSQL, ezsession.session_key $countField
FROM ezsession, ezuser, ezcontentobject
WHERE ezsession.user_id=ezuser.contentobject_id AND
      ezsession.user_id=ezcontentobject.id
      $filterSQL
$countGroup
ORDER BY $orderBy";

    $rows = $db->arrayQuery( $query, array( 'offset' => $offset, 'limit' => $limit ) );

    $time = mktime();
    $ini =& eZINI::instance();
    $activityTimeout = $ini->variable( 'Session', 'ActivityTimeout' );
    $sessionTimeout = $ini->variable( 'Session', 'SessionTimeout' );
    $sessionTimeoutValue = $time - $sessionTimeout;

    $resultArray = array();
    foreach ( $rows as $row )
    {
        $sessionUser =& eZUser::fetch( $row['user_id'], true );
        $session['user_id'] = $row['user_id'];
        if ( !$userID )
            $session['count'] = $row['count'];
        $session['expiration_time'] = $row['expiration_time'];
        $session['session_key'] = $row['session_key'];
        $session['idle_time'] = $row['expiration_time'] - $sessionTimeout;
        $idleTime = $time - $row['expiration_time'] + $sessionTimeout;
        $session['idle']['hour'] = (int)( $idleTime / 3600 );
        $session['idle']['minute'] = (int)( ( $idleTime / 60 ) % 60 );
        $session['idle']['second'] = abs( $idleTime % 60 );

        if ( $session['idle']['minute'] < 10 && $session['idle']['minute']>=0 )
        {
            $session['idle']['minute'] = "0" . $session['idle']['minute'];
        }

        if ( $session['idle']['second'] < 10 )
        {
            $session['idle']['second'] = "0" . $session['idle']['second'];
        }

        $session['email'] = $sessionUser->attribute( 'email' );
        $session['login'] = $sessionUser->attribute( 'login' );
        $resultArray[] = $session;
    }
    return $resultArray;
}
//$userID=14;
$param['sortby'] = false;
$param['filter_type'] = $filterType;
$param['user_id'] = $userID;
if ( isset( $viewParameters['sortby'] ) )
    $param['sortby'] = $viewParameters['sortby'];
$sessionsActive = eZSessionCountActive();
$sessionsList =& eZFetchActiveSessions( $param );


if ( $param['offset'] >= $sessionsActive and $sessionsActive != 0 )
{
    $module->redirectTo( '/setup/session' );
}

$tpl->setVariable( "sessions_removed", $sessionsRemoved );
$tpl->setVariable( "sessions_active", $sessionsActive );
$tpl->setVariable( "sessions_list", $sessionsList );
$tpl->setVariable( "page_limit", $param['limit'] );
$tpl->setVariable( "view_parameters", $viewParameters );
$tpl->setVariable( "form_parameter_string", $viewParameters );
$tpl->setVariable( 'filter_type', $filterType );
$tpl->setVariable( 'user_id', $userID );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:setup/session.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/setup', 'Session admin' ) ) );

?>
