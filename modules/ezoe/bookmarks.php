<?php
//
// Created on: <25-Feb-2008 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor extension for eZ Publish
// SOFTWARE RELEASE: 5.0
// COPYRIGHT NOTICE: Copyright (C) 2008 eZ Systems AS
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

/*
 * Expand the children of a node with offset and limit as a json response for use in javascript
 */


//include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'extension/ezoe/classes/ezoeajaxcontent.php' );


$limit  = (int) isset( $Params['Limit'] ) ? $Params['Limit'] : 10;
$offset = (int) $Params['Offset'];
$http   = eZHTTPTool::instance();
$user   = eZUser::currentUser();
$sort   = 'desc';

if ( !$user instanceOf eZUser )
{
    header("HTTP/1.0 500 Internal Server Error");
    echo ezi18n( 'design/standard/error/kernel', 'Your current user does not have the proper privileges to access this page.' );
    eZExecution::cleanExit();
}

$userID = $user->attribute('contentobject_id');

if ( $http->hasPostVariable( 'SortBy' ) && $http->postVariable( 'SortBy' ) !== 'asc' )
{
    $sort = 'asc';
}

// fetch bookmarks
$list       = '[]';
$objectList = eZPersistentObject::fetchObjectList( eZContentBrowseBookmark::definition(),
                                                    null,
                                                    array( 'user_id' => $userID ),
                                                    array( 'id' => $sort ),
                                                    array( 'offset' => $offset, 'length' => $limit ),
                                                    true );

// eZPersistentObject::count was added in eZ Publish 4.0.1, so we need to check that we have it
if ( method_exists('eZPersistentObject','count') )
{
    $count = eZPersistentObject::count( eZContentBrowseBookmark::definition(), array( 'user_id' => $userID ) );
}
else
{
    $count = count( $objectList );
}

// generate json response from bookmarks list
if ( $objectList )
{
    $list = eZOEAjaxContent::encode( $objectList, array( 'loadImages' => true, 'fetchNodeFunction' => 'fetchNode' ) );
}


$result = '{list:' . $list . 
     ",\r\ncount:" . count( $objectList ) .
     ",\r\ntotal_count:" . $count .
     ",\r\noffset:" . $offset .
     ",\r\nlimit:" . $limit .
     "\r\n};";

// Output debug info as js comment
echo "/*\r\n";
eZDebug::printReport( false, false );
echo "*/\r\n" . $result;


eZDB::checkTransactionCounter();
eZExecution::cleanExit();
//$GLOBALS['show_page_layout'] = false;

?>