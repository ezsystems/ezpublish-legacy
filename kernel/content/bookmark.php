<?php
//
// Definition of Bookmark class
//
// Created on: <30-Apr-2003 13:46:01 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2009 eZ Systems AS
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
$userID = $user->id();

if ( $Module->isCurrentAction( 'Remove' )  )
{
    if ( $Module->hasActionParameter( 'DeleteIDArray' ) )
    {
        $deleteIDArray = $Module->actionParameter( 'DeleteIDArray' );

        foreach ( $deleteIDArray as $deleteID )
        {
            $bookmark = eZContentBrowseBookmark::fetch( $deleteID );
            if ( $bookmark === null )
                continue;
            if ( $bookmark->attribute( 'user_id' ) == $userID )
                $bookmark->remove();
        }
    }
    if ( $http->hasPostVariable( 'NeedRedirectBack' ) and $http->hasSessionVariable( "LastAccessesURI" ) )
    {
        $Module->redirectTo( $http->sessionVariable( "LastAccessesURI" ) );
        return;
    }
}
else if ( $Module->isCurrentAction( 'Add' )  )
{
    return eZContentBrowse::browse( array( 'action_name' => 'AddBookmark',
                                           'description_template' => 'design:content/browse_bookmark.tpl',
                                           'from_page' => "/content/bookmark" ),
                                    $Module );
}
else if ( $Module->isCurrentAction( 'AddBookmark' )  )
{
    $nodeList = eZContentBrowse::result( 'AddBookmark' );
    if ( $nodeList )
    {
        $db = eZDB::instance();
        $db->begin();
        foreach ( $nodeList as $nodeID )
        {
            $node = eZContentObjectTreeNode::fetch( $nodeID );
            if ( $node )
            {
                $nodeName = $node->attribute( 'name' );
                eZContentBrowseBookmark::createNew( $userID, $nodeID, $nodeName );
            }
        }
        $db->commit();
    }
}

$tpl = templateInit();
$tpl->setVariable('view_parameters', $viewParameters );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:content/bookmark.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'My bookmarks' ),
                                'url' => false ) );


?>
