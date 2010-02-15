<?php
//
// Created on: <08-Aug-2002 14:04:07 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

require_once( "kernel/common/template.php" );
if ( eZPreferences::value( 'admin_search_stats_limit' ) )
{
    switch ( eZPreferences::value( 'admin_search_stats_limit' ) )
    {
        case '2': { $limit = 25; } break;
        case '3': { $limit = 50; } break;
        default:  { $limit = 10; } break;
    }
}
else
{
    $limit = 10;
}

$offset = $Params['Offset'];
if ( !is_numeric( $offset ) )
{
    $offset = 0;
}

$http = eZHTTPTool::instance();
$module = $Params['Module'];

if ( $module->isCurrentAction( 'ResetSearchStats' ) )
{
    eZSearchLog::removeStatistics();
}

$viewParameters = array( 'offset' => $offset, 'limit'  => $limit );
$tpl = templateInit();

$db = eZDB::instance();
$query = "SELECT count(*) as count FROM ezsearch_search_phrase";
$searchListCount = $db->arrayQuery( $query );

$mostFrequentPhraseArray = eZSearchLog::mostFrequentPhraseArray( $viewParameters );

$tpl->setVariable( "view_parameters", $viewParameters );
$tpl->setVariable( "most_frequent_phrase_array", $mostFrequentPhraseArray );
$tpl->setVariable( "search_list_count", $searchListCount[0]['count'] );

$Result = array();
$Result['content'] = $tpl->fetch( "design:search/stats.tpl" );
$Result['path'] = array( array( 'text' => eZi18n::translate( 'kernel/search', 'Search stats' ),
                                'url' => false ) );

?>
