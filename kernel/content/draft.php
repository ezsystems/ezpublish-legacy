<?php
//
// Definition of List class
//
// Created on: <29-���-2002 16:14:57 sp>
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

/*! \file
*/
require_once( 'kernel/common/template.php' );
$Module = $Params['Module'];
$http = eZHTTPTool::instance();

$Offset = $Params['Offset'];
$viewParameters = array( 'offset' => $Offset );

$user = eZUser::currentUser();
if ( !$user->isLoggedIn() )
    return $Module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );

$userID = $user->id();

if ( $http->hasPostVariable( 'RemoveButton' )  )
{
    if ( $http->hasPostVariable( 'DeleteIDArray' ) )
    {
        $deleteIDArray = $http->postVariable( 'DeleteIDArray' );
        $db = eZDB::instance();
        $db->begin();
        foreach ( $deleteIDArray as $deleteID )
        {
            $version = eZContentObjectVersion::fetch( $deleteID );
            if ( $version instanceof eZContentObjectVersion )
            {
                eZDebug::writeNotice( $deleteID, "deleteID" );
                $version->removeThis();
            }
        }
        $db->commit();
    }
}

if ( $http->hasPostVariable( 'EmptyButton' )  )
{
    $versions = eZContentObjectVersion::fetchForUser( $userID );
    $db = eZDB::instance();
    $db->begin();
    foreach ( $versions as $version )
    {
        $version->removeThis();
    }
    $db->commit();
}

$tpl = templateInit();

$tpl->setVariable('view_parameters', $viewParameters );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:content/draft.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'My drafts' ),
                                'url' => false ) );

?>
