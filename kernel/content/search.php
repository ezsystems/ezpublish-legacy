<?php
//
// Created on: <24-Apr-2002 16:06:53 bf>
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
        $subTreeList =& $http->variable( "SubTreeArray" );
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
    $searchResult =& eZSearch::search( $searchText, array( "SearchType" => $searchType,
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

// --- Compatability code start ---
if ( $useSearchCode )
{
    $tpl->setVariable( "offset", $Offset );
    $tpl->setVariable( "page_limit", $pageLimit );
    $tpl->setVariable( "search_text_enc", urlencode( $searchText ) );
    $tpl->setVariable( "search_result", $searchResult["SearchResult"] );
    $tpl->setVariable( "search_count", $searchResult["SearchCount"] );
    $tpl->setVariable( "stop_word_array", $searchResult["StopWordArray"] );
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
if ( !$useSearchCode )
{
    $searchData = $tpl->variable( "search_data" );
}
else
{
    $searchData = $searchResult;
}

if ( $logSearchStats and trim( $searchText ) != "" )
    eZSearchLog::addPhrase( $searchText, $searchData["SearchCount"] );
?>
