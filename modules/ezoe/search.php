<?php
//
// Created on: <30-Jul-2007 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor MCE extension for eZ Publish
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
 * Search for nodes based on post / view parameters
 * returns response in json for use in javascript
 */

//include_once( 'kernel/classes/ezsearch.php' );
include_once( 'extension/ezoe/classes/ezajaxcontent.php' );


$http = eZHTTPTool::instance();
// get the search string, it can be passed on as POST string or view parameter
if ( $http->hasPostVariable( 'SearchStr' ) )
{
    $searchStr = trim( $http->postVariable( 'SearchStr' ) );
}
elseif ( $Params['SearchStr'] )
{
    $searchStr = trim( $Params['SearchStr'] );
}

$varName = '';
// optional var name for javascript variable the should be set to the search result
if ( $http->hasPostVariable( 'VarName' ))
{
    $varName = trim( $http->postVariable( 'VarName' ) );
}
elseif ( isset( $Params['VarName'] ) )
{
    $varName = trim( $Params['VarName'] );
}

if ( $varName )
{
    $varName .= ' = ';
}
    

if ( !$searchStr )
{
    echo $varName . "false;";
    eZExecution::cleanExit();
}

$searchOffset = 0;
if ( $http->hasPostVariable( 'SearchOffset' ))
{
    $searchOffset = (int) $http->postVariable( 'SearchOffset' );
}
elseif ( isset( $Params['SearchOffset'] ) )
{
    $searchOffset = (int) $Params['SearchOffset'];
}

$searchLimit = 10;
if ( $http->hasPostVariable( 'SearchLimit' ))
{
    $searchLimit = (int) $http->postVariable( 'SearchLimit' );
}
elseif ( isset( $Params['SearchLimit'] ) )
{
    $searchLimit = (int) $Params['SearchLimit'];
}


// Preper the search params
$param = array( 'SearchOffset' => $searchOffset,
                'SearchLimit' => $searchLimit,
                'SortArray' => array('published', 0)
              );

// Function to deal with post date that needs to be an array
function makeStringArray( $str )
{
    if ( is_array( $str ) )
        return $str;
    elseif( strpos($str, ',') === false )
        return array( $str );
    else
        return explode( ',', $str );
}

// Transform an array with class_identifier's to class_id's
function makeClassID( $arr )
{
    for( $i = 0, $c = count( $arr ); $i < $c; $i++)
    {
        if ( !is_numeric( $arr[$i] ) )
            $arr[$i] = eZContentObjectTreeNode::classIDByIdentifier( $arr[$i] ) ;
    }
    return $arr;
}

// Look after post params for some common search params
if ( $http->hasPostVariable( 'SearchContentClassAttributeID' ) )
{
    $param['SearchContentClassAttributeID'] = makeStringArray( $http->postVariable( 'SearchContentClassAttributeID' ) );
    if ( count($param['SearchContentClassAttributeID']) === 1 && $param['SearchContentClassAttributeID'][0] === ''  )
        unset( $param['SearchContentClassAttributeID'] );
}
else if ( $http->hasPostVariable( 'SearchContentClassID' ) )
{
    $param['SearchContentClassID'] = makeStringArray( $http->postVariable( 'SearchContentClassID' ) );
    if ( count($param['SearchContentClassID']) === 1 && $param['SearchContentClassID'][0] === ''  )
        unset( $param['SearchContentClassID'] );
}
else if ( $http->hasPostVariable( 'SearchContentClassIdentifier' ) )
{
    $param['SearchContentClassID'] = makeClassID( makeStringArray( $http->postVariable( 'SearchContentClassIdentifier' ) ) );
    if ( count($param['SearchContentClassID']) === 1 && $param['SearchContentClassID'][0] === ''  )
        unset( $param['SearchContentClassID'] );
}

if ( $http->hasPostVariable( 'SearchSubTreeArray' ) )
{
    $param['SearchSubTreeArray'] = makeStringArray( $http->postVariable( 'SearchSubTreeArray' ) );
}

if ( $http->hasPostVariable( 'SearchSectionID' ) )
{
    $param['SearchSectionID'] = makeStringArray(  $http->postVariable( 'SearchSectionID' ) );
}

if ( $http->hasPostVariable( 'SearchTimestamp' ) )
{
    $param['SearchTimestamp'] = makeStringArray( $http->postVariable( 'SearchTimestamp' ) );
}

if ( isset( $param['SearchTimestamp'][0] ) && !isset( $param['SearchTimestamp'][1] ) )
{
    $param['SearchTimestamp'] = $param['SearchTimestamp'][0];
}

// search
$searchList = eZSearch::search( $searchStr, $param );


if (!$searchList  || ( $searchOffset === 0 && count($searchList["SearchResult"]) === 0 ) )
{
    echo $varName . "false;";
    eZExecution::cleanExit();
}

// encode nodes to a json response
$list = eZAjaxContent::encode( $searchList["SearchResult"], array('loadImages' => true ) );


$json =  $varName . '{list:' . $list . 
     ",\r\ncount:" . count( $searchList["SearchResult"] ) .
     ",\r\ntotal_count:" . $searchList['SearchCount'] .
     ",\r\noffset:" . $searchOffset .
     ",\r\nlimit:" . $searchLimit .
     "\r\n};";

echo "/*\r\n";
eZDebug::printReport( false, false );
echo "*/\r\n" . $json;


eZDB::checkTransactionCounter();
eZExecution::cleanExit();
//$GLOBALS['show_page_layout'] = false;

?>