<?php
//
// Created on: <24-Apr-2002 16:06:53 bf>
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

include_once( "lib/ezutils/classes/ezhttptool.php" );

include_once( "kernel/common/template.php" );

include_once( "kernel/classes/ezsearch.php" );
include_once( "kernel/classes/ezsearchlog.php" );

/*!
 Get search limit
 */
function pageLimit( $searchPageLimit )
{
    switch ( $searchPageLimit )
    {
        case 1:
            return 5;

        case 2:
        default:
            return 10;

        case 3:
            return 20;

        case 4:
            return 30;

        case 5:
            return 50;
    }
}

$http =& eZHTTPTool::instance();

$Module =& $Params["Module"];
$Offset = $Params['Offset'];

if ( !is_numeric( $Offset ) )
    $Offset = 0;

$tpl =& templateInit();

$ini =& eZINI::instance();
$useSearchCode = $ini->variable( 'SearchSettings', 'SearchViewHandling' ) == 'default';
$logSearchStats = $ini->variable( 'SearchSettings', 'LogSearchStats' ) == 'enabled';

$searchPageLimit = 2;
if ( $http->hasVariable( 'SearchPageLimit' ) )
{
    $searchPageLimit = $http->variable( 'SearchPageLimit' );
}

$pageLimit = pageLimit( $searchPageLimit );

$maximumSearchLimit = $ini->variable( 'SearchSettings', 'MaximumSearchLimit' );
if ( $pageLimit > $maximumSearchLimit )
    $pageLimit = $maximumSearchLimit;

$searchText = '';
if ( $http->hasVariable( "SearchText" ) )
{
    $searchText = $http->variable( "SearchText" );
}

$searchSectionID = -1;
if ( $http->hasVariable( "SectionID" ) )
{
    $searchSectionID = $http->variable( "SectionID" );
}

$searchTimestamp = false;
if ( $http->hasVariable( 'SearchTimestamp' ) and
     $http->variable( 'SearchTimestamp' ) )
{
    $searchTimestamp = $http->variable( 'SearchTimestamp' );
}

$searchType = "fulltext";
if ( $http->hasVariable( "SearchType" ) )
{
    $searchType = $http->variable( "SearchType" );
}

$subTreeArray = array();
if ( $http->hasVariable( "SubTreeArray" ) )
{
    if ( is_array( $http->variable( "SubTreeArray" ) ) )
        $subTreeList = $http->variable( "SubTreeArray" );
    else
        $subTreeList = array( $http->variable( "SubTreeArray" ) );
    foreach ( $subTreeList as $subTreeItem )
    {
        if ( $subTreeItem > 0 )
            $subTreeArray[] = $subTreeItem;
    }
}

$Module->setTitle( "Search for: $searchText" );

if ( $useSearchCode )
{
    $sortArray = array( array( 'attribute', true, 153 ), array( 'priority', true ) );
    $searchResult = eZSearch::search( $searchText, array( "SearchType" => $searchType,
                                                          "SearchSectionID" => $searchSectionID,
                                                          "SearchSubTreeArray" => $subTreeArray,
                                                          'SearchTimestamp' => $searchTimestamp,
                                                          "SearchLimit" => $pageLimit,
                                                          "SearchOffset" => $Offset ) );
}

if ( $searchSectionID != -1 )
{
    include_once( 'kernel/common/eztemplatedesignresource.php' );
    $res =& eZTemplateDesignResource::instance();
    $res->setKeys( array( array( 'section', $searchSectionID ) ) );
}

$viewParameters = array( 'offset' => $Offset );

$searchData = false;
$tpl->setVariable( "search_data", $searchData );
$tpl->setVariable( "search_section_id", $searchSectionID );
$tpl->setVariable( "search_subtree_array", $subTreeArray );
$tpl->setVariable( 'search_timestamp', $searchTimestamp );
$tpl->setVariable( "search_text", $searchText );
$tpl->setVariable( 'search_page_limit', $searchPageLimit );

$tpl->setVariable( "view_parameters", $viewParameters );
$tpl->setVariable( 'use_template_search', !$useSearchCode );

if ( $http->hasVariable( 'Mode' ) && $http->variable( 'Mode' ) == 'browse' )
{
    if( !isset( $searchResult ) )
        $searchResult = eZSearch::search( $searchText, array( "SearchType" => $searchType,
                                                              "SearchSectionID" => $searchSectionID,
                                                              "SearchSubTreeArray" => $subTreeArray,
                                                              'SearchTimestamp' => $searchTimestamp,
                                                              "SearchLimit" => $pageLimit,
                                                              "SearchOffset" => $Offset ) );
    $sys = eZSYS::instance();
    $searchResult['RequestedURI'] = "content/search";
//    $searchResult['RequestedURISuffix'] = $sys->serverVariable( "QUERY_STRING" );


    $searchResult['RequestedURISuffix'] = 'SearchText=' . urlencode ( $searchText ) . ( ( $searchTimestamp > 0 ) ?  '&SearchTimestamp=' . $searchTimestamp : '' ) . '&Mode=browse';
    return $Module->run( 'browse',array(),array( "NodeList" => $searchResult,
                                                 "Offset" => $Offset ) );
}

// --- Compatability code start ---
if ( $useSearchCode )
{
    $tpl->setVariable( "offset", $Offset );
    $tpl->setVariable( "page_limit", $pageLimit );
    $tpl->setVariable( "search_text_enc", urlencode( $searchText ) );
    $tpl->setVariable( "search_result", $searchResult["SearchResult"] );
    $tpl->setVariable( "search_count", $searchResult["SearchCount"] );
    $tpl->setVariable( "stop_word_array", $searchResult["StopWordArray"] );
    if ( isset( $searchResult["SearchExtras"] ) )
    {
        $tpl->setVariable( "search_extras", $searchResult["SearchExtras"] );
    }
}
else
{
    $tpl->setVariable( "offset", false );
    $tpl->setVariable( "page_limit", false );
    $tpl->setVariable( "search_text_enc", false );
    $tpl->setVariable( "search_result", false );
    $tpl->setVariable( "search_count", false );
    $tpl->setVariable( "stop_word_array", false );
}
// --- Compatability code end ---

$Result = array();
$Result['content'] =& $tpl->fetch( "design:content/search.tpl" );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'Search' ),
                                'url' => false ) );

$searchData = false;
if ( !$useSearchCode )
{
    if ( $tpl->hasVariable( "search_data" ) )
    {
        $searchData = $tpl->variable( "search_data" );
    }
}
else
{
    $searchData = $searchResult;
}

if ( $logSearchStats and
     trim( $searchText ) != "" and
     is_array( $searchData ) and
     array_key_exists( 'SearchCount', $searchData ) and
     is_numeric( $searchData['SearchCount'] ) )
{
    eZSearchLog::addPhrase( $searchText, $searchData["SearchCount"] );
}

?>
