<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$module = $Params['Module'];

$tpl = eZTemplate::factory();

$offset = $Params['Offset'];
$limit = 50;


if( eZPreferences::value( 'admin_archivelist_sortfield' ) )
{
    $sortField = eZPreferences::value( 'admin_archivelist_sortfield' );
}

if ( !isset( $sortField ) || ( ( $sortField != 'created' ) && ( $sortField!= 'user_name' ) ) )
{
    $sortField = 'created';
}

if( eZPreferences::value( 'admin_archivelist_sortorder' ) )
{
    $sortOrder = eZPreferences::value( 'admin_archivelist_sortorder' );
}

if ( !isset( $sortOrder ) || ( ( $sortOrder != 'asc' ) && ( $sortOrder!= 'desc' ) ) )
{
    $sortOrder = 'asc';
}

$http = eZHTTPTool::instance();

// Unarchive options.
if ( $http->hasPostVariable( 'UnarchiveButton' ) )
{
    if ( $http->hasPostVariable( 'OrderIDArray' ) )
    {
        $orderIDArray = $http->postVariable( 'OrderIDArray' );
        if ( $orderIDArray !== null )
        {
            $http->setSessionVariable( 'OrderIDArray', $orderIDArray );
            $Module->redirectTo( $Module->functionURI( 'unarchiveorder' ) . '/' );
        }
    }
}

$archiveArray = eZOrder::active( true, $offset, $limit, $sortField, $sortOrder, eZOrder::SHOW_ARCHIVED );
$archiveCount = eZOrder::activeCount( eZOrder::SHOW_ARCHIVED );

$tpl->setVariable( 'archive_list', $archiveArray );
$tpl->setVariable( 'archive_list_count', $archiveCount );
$tpl->setVariable( 'limit', $limit );

$viewParameters = array( 'offset' => $offset );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'sort_field', $sortField );
$tpl->setVariable( 'sort_order', $sortOrder );

$Result = array();
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'kernel/shop', 'Order list' ),
                                'url' => false ) );

$Result['content'] = $tpl->fetch( 'design:shop/archivelist.tpl' );
?>
