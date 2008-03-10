<?php
//
// Created on: <30-Jul-2007 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor MCE extension for eZ Publish
// SOFTWARE RELEASE: 1.0
// COPYRIGHT NOTICE: Copyright (C) 2008 eZ systems AS
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

//include_once( 'kernel/classes/ezsearch.php' );
include_once( 'extension/ezoe/classes/ezajaxcontent.php' );


$http = eZHTTPTool::instance();
if ( $http->hasPostVariable( 'SearchStr' ) )
    $searchStr = trim( $http->postVariable( 'SearchStr' ) );
elseif ( isSet( $Params['SearchStr'] ) )
    $searchStr = trim( $Params['SearchStr'] );

$varName = '';
if ( $http->hasPostVariable( 'VarName' ))
    $varName = trim( $http->postVariable( 'VarName' ) );
elseif ( isSet( $Params['VarName'] ) )
    $varName = trim( $Params['VarName'] );

if ( $varName )
    $varName .= ' = ';
    

if ( !$searchStr )
{
    echo $varName . "false;";
    eZExecution::cleanExit();
}


function makeStringArray( $str )
{
    if( strpos($str, ',') === false )
        return array( $str );
    else
        return explode( ',', $str );
}

function makeClassID( $arr )
{
    for( $i = 0, $c = count( $arr ); $i < $c; $i++)
    {
        $arr[$i] = eZContentObjectTreeNode::classIDByIdentifier( $arr[$i] ) ;
    }
    return $arr;
}

$searchOffset = 0;
if ( $http->hasPostVariable( 'SearchOffset' ))
    $searchOffset = (int) $http->postVariable( 'SearchOffset' );
elseif ( isSet( $Params['SearchOffset'] ) )
    $searchOffset = (int) $Params['SearchOffset'];

$searchLimit = 10;
if ( $http->hasPostVariable( 'SearchLimit' ))
    $searchLimit = (int) $http->postVariable( 'SearchLimit' );
elseif ( isSet( $Params['SearchLimit'] ) )
    $searchLimit = (int) $Params['SearchLimit'];


//Preper the search params
$param = array( 'SearchOffset' => $searchOffset,
                'SearchLimit' => $searchLimit+1,
                'SortArray' => array('published', 0)
              );


// if no checkbox select class_attr first if valid
if ( $http->hasPostVariable( 'SearchContentClassAttributeID' ) )
    $param['SearchContentClassAttributeID'] = makeStringArray( $http->postVariable( 'SearchContentClassAttributeID' ) );
else if ( $http->hasPostVariable( 'SearchContentClassID' ) )
    $param['SearchContentClassID'] = makeStringArray( $http->postVariable( 'SearchContentClassID' ) );
else if ( $http->hasPostVariable( 'SearchContentClassIdentifier' ) )
    $param['SearchContentClassID'] = makeClassID( makeStringArray( $http->postVariable( 'SearchContentClassIdentifier' ) ) );

if ( $http->hasPostVariable( 'SearchSubTreeArray' ) )
    $param['SearchSubTreeArray'] = makeStringArray( $http->postVariable( 'SearchSubTreeArray' ) );

if ( $http->hasPostVariable( 'SearchSectionID' ) )
    $param['SearchSectionID'] = makeStringArray(  $http->postVariable( 'SearchSectionID' ) );

if ( $http->hasPostVariable( 'SearchTimestamp' ) )
    $param['SearchTimestamp'] = makeStringArray( $http->postVariable( 'SearchTimestamp' ) );

if ( isSet( $param['SearchTimestamp'][0] ) && !isSet( $param['SearchTimestamp'][1] ) )
    $param['SearchTimestamp'] = $param['SearchTimestamp'][0];

 
$searchList = eZSearch::search( $searchStr, $param );


if (!$searchList  || count($searchList["SearchResult"]) === 0)
{
    echo $varName . "false;";
    eZExecution::cleanExit();
}


$r = eZAjaxContent::encode( $searchList["SearchResult"], array('dataMap' => array('image') ) );


echo $varName . '{list:' . $r . ',count:' . $searchList['SearchCount'] .
     ',offset:' . $searchOffset . ',limit:' . $searchLimit . '};';

eZExecution::cleanExit();
//$GLOBALS['show_page_layout'] = false;

?>