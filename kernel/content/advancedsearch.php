<?php
//
// Created on: <23-Jul-2002 17:11:13 bf>
//
// Copyright (C) 1999-2003 eZ systems as. All rights reserved.
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

include_once( "lib/ezutils/classes/ezhttptool.php" );

include_once( "kernel/common/template.php" );

include_once( "kernel/classes/ezsearch.php" );
include_once( "kernel/classes/ezcontentclass.php" );

include_once( "kernel/classes/ezsearchlog.php" );
include_once( "kernel/classes/ezsection.php" );

$http =& eZHTTPTool::instance();

$Module =& $Params["Module"];
$ViewMode = $Params["ViewMode"];
$Offset = $Params['Offset'];

if ( $ViewMode == "offset" )
    $ViewMode = "";

$tpl =& templateInit();

$searchText = '';
$originalSearchText = '';
$phraseSearchText = '';

$pageLimit = 10;
if ( !is_numeric( $Offset ) )
    $Offset = 0;

if ( $http->hasVariable( "PhraseSearchText" ) and trim( $http->variable( "PhraseSearchText" ) ) != "" )
{
    $searchText = "\"" . $http->variable( "PhraseSearchText" ) . "\"";
    $phraseSearchText = $http->variable( "PhraseSearchText" );
}

$fullSearchText = "";
if ( $http->hasVariable( "SearchText" ) )
{
    if ( $searchText != "" )
        $searchText .= " ";
    $originalSearchText = $http->variable( 'SearchText' );
    $searchText .= $originalSearchText;
    $fullSearchText = $http->variable( "SearchText" );
}

$searchContentClassID = -1;
$searchContentClassAttributes = 0;
$searchContentClassAttributeArray = array();
if ( $http->hasVariable( "SearchContentClassID" ) and
     $http->variable( "SearchContentClassID" ) != -1 )
{
    $searchContentClassID = $http->variable( "SearchContentClassID" );
    $searchContentClass =& eZContentClass::fetch( $searchContentClassID );
    $searchContentClassAttributeArray =& $searchContentClass->fetchSearchableAttributes();
}

$searchContentClassAttributeID = -1;
if ( $http->hasVariable( "SearchContentClassAttributeID" ) and
     $http->variable( "SearchContentClassAttributeID" ) != -1 )
{
    $searchContentClassAttributeID = $http->variable( "SearchContentClassAttributeID" );
}

$searchDate = -1;
if ( $http->hasVariable( "SearchDate" ) and
     $http->variable( "SearchDate" ) != -1 )
{
    $searchDate = $http->variable( "SearchDate" );
}

$searchSectionID = -1;
if ( $http->hasVariable( "SearchSectionID" ) and
     $http->variable( "SearchSectionID" ) != -1 )
{
    $searchSectionID = $http->variable( "SearchSectionID" );
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

$classArray =& eZContentClass::fetchList();

$sectionArray =& eZSection::fetchList();

$searchResult =& eZSearch::search( $searchText, array( "SearchSectionID" => $searchSectionID,
                                                       "SearchContentClassID" => $searchContentClassID,
                                                       "SearchContentClassAttributeID" => $searchContentClassAttributeID,
                                                       "SearchSubTreeArray" => $subTreeArray,
                                                       "SearchDate" => $searchDate,
                                                       "SearchLimit" => $pageLimit,
                                                       "SearchOffset" => $Offset ) );

$viewParameters = array( 'offset' => $Offset );

$tpl->setVariable( "search_contentclass_id", $searchContentClassID );
$tpl->setVariable( 'search_contentclass_attribute_id', $searchContentClassAttributeID );
$tpl->setVariable( "search_section_id", $searchSectionID );
$tpl->setVariable( "search_date", $searchDate );
$tpl->setVariable( "search_sub_tree", $subTreeArray );

$tpl->setVariable( "view_parameters", $viewParameters );

// --- Compatability code start ---
$tpl->setVariable( "offset", $Offset );
$tpl->setVariable( "page_limit", $pageLimit );
$tpl->setVariable( "search_text_enc", urlencode( $originalSearchText ) );
$tpl->setVariable( 'phrase_search_text_enc', urlencode( $phraseSearchText ) );
// --- Compatability code end ---

$tpl->setVariable( "search_result", $searchResult["SearchResult"] );
$tpl->setVariable( "search_count", $searchResult["SearchCount"] );
$tpl->setVariable( "stop_word_array", $searchResult["StopWordArray"] );
$tpl->setVariable( "search_text", $searchText );
$tpl->setVariable( "full_search_text", $fullSearchText );
$tpl->setVariable( "phrase_search_text", $phraseSearchText );
$tpl->setVariable( "content_class_array", $classArray );
$tpl->setVariable( "section_array", $sectionArray );
$tpl->setVariable( "search_content_class_attribute_array", $searchContentClassAttributeArray );

if ( $searchSectionID != -1 )
{
    include_once( 'kernel/common/eztemplatedesignresource.php' );
    $res =& eZTemplateDesignResource::instance();
    $res->setKeys( array( array( 'section', $searchSectionID ) ) );
}

$Result = array();

if ( trim( $ViewMode ) != "" )
{
    // Fetch override template for viewmode if wanted
    $Result['content'] =& $tpl->fetch( "design:content/advancedsearch/$ViewMode.tpl" );
}
else
{
    $Result['content'] =& $tpl->fetch( "design:content/advancedsearch.tpl" );
}
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'Search' ),
                                'url' => false ),
                         array( 'text' => ezi18n( 'kernel/content', 'Advanced' ),
                                'url' => false ) );

eZSearchLog::addPhrase( $searchText, $searchResult["SearchCount"] );

?>
