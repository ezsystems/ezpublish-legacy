<?php
//
// Created on: <24-Apr-2002 16:06:53 bf>
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
// http://ez.no/home/licences/professional/. For pricing of this licence
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
include_once( "kernel/classes/ezsearchlog.php" );

$http =& eZHTTPTool::instance();

$Module =& $Params["Module"];

$tpl =& templateInit();

if ( $http->hasVariable( "SearchText" ) )
{
    $searchText = $http->variable( "SearchText" );
}

$searchSectionID = -1;
if ( $http->hasVariable( "SectionID" ) )
{
    $searchSectionID = $http->variable( "SectionID" );
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

$searchText = eZSearch::normalizeText( $searchText );



$searchResult =& eZSearch::search( $searchText, array( "SearchType" => $searchType,
                                                       "SearchSectionID" => $searchSectionID,
                                                       "SearchSubTreeArray" => $subTreeArray ) );

$tpl->setVariable( "search_section_id", $searchSectionID );
$tpl->setVariable( "search_result", $searchResult["SearchResult"] );
$tpl->setVariable( "search_text", $searchText );
$tpl->setVariable( "search_count", $searchResult["SearchCount"] );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:content/search.tpl" );
$Result['path'] = array( array( 'text' => 'Search',
                                'url' => false ),
                         array( 'text' => 'Normal',
                                'url' => false ) );

if ( trim( $searchText ) != "" )
    eZSearchLog::addPhrase( $searchText, $searchResult["SearchCount"] );
?>
