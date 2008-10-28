<?php
//
// Created on: <15-Apr-2004 11:25:31 bh>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
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
require_once( 'kernel/common/template.php' );
require_once( 'lib/ezutils/classes/ezsession.php' );

$tpl = templateInit();
$sessionsRemoved = false;
$http = eZHTTPTool::instance();

$module = $Params['Module'];
$param['limit'] = 50;

$filterType = 'registered';
if ( $http->hasSessionVariable( 'eZSessionFilterType' ) )
    $filterType = $http->sessionVariable( 'eZSessionFilterType' );
$expirationFilterType = 'active';
if ( $http->hasSessionVariable( 'eZSessionExpirationFilterType' ) )
    $expirationFilterType = $http->sessionVariable( 'eZSessionExpirationFilterType' );

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
    if ( $module->hasActionParameter( 'InactiveUsersCheckExists' ) )
    {
        $expirationFilterType = 'active';
        if ( $module->hasActionParameter( 'InactiveUsersCheck' ) )
            $expirationFilterType = 'all';
    }
    if ( $module->hasActionParameter( 'ExpirationFilterType' ) )
    {
        $expirationFilterType = $module->actionParameter( 'ExpirationFilterType' );
    }
    if ( !in_array( $expirationFilterType, array( 'all', 'active' ) ) )
        $expirationFilterType = 'active';
    $http->setSessionVariable( 'eZSessionFilterType', $filterType );
    $http->setSessionVariable( 'eZSessionExpirationFilterType', $expirationFilterType );
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
        if ( $http->hasPostVariable( 'SessionKeyArray' ) )
        {
            $sessionKeyArray = $http->postVariable( 'SessionKeyArray' );
            foreach ( $sessionKeyArray as $sessionKeyItem )
            {
                eZSessionDestroy( $sessionKeyItem );
            }
        }
    }
    else
    {
        if ( $http->hasPostVariable( 'UserIDArray' ) )
        {
            $userIDArray = $http->postVariable( 'UserIDArray' );
            if ( count( $userIDArray ) > 0 )
            {
                $db = eZDB::instance();
                $userIDArrayString = $db->implodeWithTypeCast( ',', $userIDArray, 'int' );
                $rows = $db->arrayQuery( "SELECT session_key FROM ezsession WHERE user_id IN ( " . $userIDArrayString . " )" );
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
function eZFetchActiveSessions( $params = array() )
{
    if ( isset( $params['limit'] ) )
        $limit = $params['limit'];
    else
        $limit = 20;

    if ( isset( $params['offset'] ) )
        $offset = $params['offset'];
    else
        $offset = 0;
    $orderBy = " expiration_time DESC";

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
            $orderBy = " expiration_time DESC";
        } break;
    }

    $filterType = $params['filter_type'];
    switch ( $filterType )
    {
        case 'registered':
        {
            $filterSQL = 'AND ezsession.user_id != ' . eZUser::anonymousId();
        } break;

        case 'anonymous':
        {
            $filterSQL = 'AND ezsession.user_id = ' . eZUser::anonymousId();
        } break;

        case 'everyone':
        default:
        {
            $filterSQL = '';
        } break;
    }

    $expirationFilterType = $params['expiration_filter'];
    switch ( $expirationFilterType )
    {
        case 'active':
        {
            $ini = eZINI::instance();
            $time = time();
            $activityTimeout = $ini->variable( 'Session', 'ActivityTimeout' );
            $sessionTimeout = $ini->variable( 'Session', 'SessionTimeout' );
            $time = $time + $sessionTimeout - $activityTimeout;
            $expirationFilterSQL = ' AND ezsession.expiration_time > ' . $time;
        } break;

        case 'all':
        default:
        {
            $expirationFilterSQL = '';
        } break;
    }

    $userID = $params['user_id'];
    $countField = '';
    $countGroup = 'GROUP BY ezsession.user_id';
    if ( $userID )
    {
        $filterSQL = 'AND ezsession.user_id = ' .  (int)$userID;
        $expirationSQL = 'ezsession.expiration_time';
    }
    else
    {
        $countField = ', count( ezsession.user_id ) AS count';
        $expirationSQL = 'max( ezsession.expiration_time ) as expiration_time';
    }

    $db = eZDB::instance();
    $query = "SELECT ezsession.user_id, $expirationSQL, max(session_key) as session_key  $countField
FROM ezsession, ezuser, ezcontentobject
WHERE ezsession.user_id=ezuser.contentobject_id AND
      ezsession.user_id=ezcontentobject.id
      $filterSQL
      $expirationFilterSQL
$countGroup
ORDER BY $orderBy";

    $rows = $db->arrayQuery( $query, array( 'offset' => $offset, 'limit' => $limit ) );

    $time = time();
    $ini = eZINI::instance();
    $activityTimeout = $ini->variable( 'Session', 'ActivityTimeout' );
    $sessionTimeout = $ini->variable( 'Session', 'SessionTimeout' );
    $sessionTimeoutValue = $time - $sessionTimeout;

    $resultArray = array();
    foreach ( $rows as $row )
    {
        $sessionUser = eZUser::fetch( $row['user_id'], true );
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

/*
  Counts active sessions according the filters and returns the count.
*/
function eZFetchActiveSessionCount( $params = array() )
{
    $filterType = $params['filter_type'];
    switch ( $filterType )
    {
        case 'registered':
        {
            $filterSQL = ' ezsession.user_id != ' . eZUser::anonymousId();
        } break;

        case 'anonymous':
        {
            $filterSQL = ' ezsession.user_id = ' . eZUser::anonymousId();
        } break;

        case 'everyone':
        default:
        {
            $filterSQL = '';
        } break;
    }

    $expirationFilterType = $params['expiration_filter'];

    $userID = $params['user_id'];
    if ( $userID )
    {
        $filterSQL = ' ezsession.user_id = ' .  (int)$userID;
    }

    switch ( $expirationFilterType )
    {
        case 'active':
        {
            $ini = eZINI::instance();
            $time = time();
            $activityTimeout = $ini->variable( 'Session', 'ActivityTimeout' );
            $sessionTimeout = $ini->variable( 'Session', 'SessionTimeout' );
            $time = $time + $sessionTimeout - $activityTimeout;
            $expirationFilterSQL = '';
            if ( strlen( $filterSQL ) > 0 )
                $expirationFilterSQL .= ' AND ';
            $expirationFilterSQL .= ' ezsession.expiration_time > ' . $time;
        } break;

        case 'all':
        default:
        {
            $expirationFilterSQL = '';
        } break;
    }

    $whereSQL = '';
    if ( ( strlen( $filterSQL ) + strlen( $expirationFilterSQL ) ) > 0 )
        $whereSQL = 'WHERE';

    $db = eZDB::instance();
    $query = "SELECT count( DISTINCT ezsession.user_id ) AS count
              FROM ezsession
              $whereSQL
              $filterSQL
              $expirationFilterSQL";

    $rows = $db->arrayQuery( $query );

    return $rows[0]['count'];
}

$param['sortby'] = false;
$param['filter_type'] = $filterType;
$param['expiration_filter'] = $expirationFilterType;
$param['user_id'] = $userID;
if ( isset( $viewParameters['sortby'] ) )
    $param['sortby'] = $viewParameters['sortby'];
$sessionsActive = eZSessionCountActive( $param );
$sessionsCount = eZFetchActiveSessionCount( $param );
$sessionsList = eZFetchActiveSessions( $param );


if ( $param['offset'] >= $sessionsActive and $sessionsActive != 0 )
{
    $module->redirectTo( '/setup/session' );
}

$tpl->setVariable( "sessions_removed", $sessionsRemoved );
$tpl->setVariable( "sessions_active", $sessionsActive );
$tpl->setVariable( "sessions_count", $sessionsCount );
$tpl->setVariable( "sessions_list", $sessionsList );
$tpl->setVariable( "page_limit", $param['limit'] );
$tpl->setVariable( "view_parameters", $viewParameters );
$tpl->setVariable( "form_parameter_string", $viewParameters );
$tpl->setVariable( 'filter_type', $filterType );
$tpl->setVariable( 'expiration_filter_type', $expirationFilterType );
$tpl->setVariable( 'user_id', $userID );

$Result = array();
$Result['content'] = $tpl->fetch( "design:setup/session.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/setup', 'Session admin' ) ) );

?>
