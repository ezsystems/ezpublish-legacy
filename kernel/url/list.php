<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

$Module = $Params['Module'];
$ViewMode = $Params['ViewMode'];

if( eZPreferences::value( 'admin_url_list_limit' ) )
{
    switch( eZPreferences::value( 'admin_url_list_limit' ) )
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

if( $ViewMode != 'all' && $ViewMode != 'invalid' && $ViewMode != 'valid')
{
    $ViewMode = 'all';
}

if ( $Module->isCurrentAction( 'SetValid' ) )
{
    $urlSelection = $Module->actionParameter( 'URLSelection' );
    eZURL::setIsValid( $urlSelection, true );
}
else if ( $Module->isCurrentAction( 'SetInvalid' ) )
{
    $urlSelection = $Module->actionParameter( 'URLSelection' );
    eZURL::setIsValid( $urlSelection, false );
}


if( $ViewMode == 'all' )
{
    $listParameters = array( 'is_valid'       => null,
                             'offset'         => $offset,
                             'limit'          => $limit,
                             'only_published' => true );

    $countParameters = array( 'only_published' => true );
}
elseif( $ViewMode == 'valid' )
{
    $listParameters = array( 'is_valid'       => true,
                             'offset'         => $offset,
                             'limit'          => $limit,
                             'only_published' => true );

    $countParameters = array( 'is_valid' => true,
                              'only_published' => true );
}
elseif( $ViewMode == 'invalid' )
{
    $listParameters = array( 'is_valid'       => false,
                             'offset'         => $offset,
                             'limit'          => $limit,
                             'only_published' => true );

    $countParameters = array( 'is_valid' => false,
                              'only_published' => true );
}

$list = eZURL::fetchList( $listParameters );
$listCount = eZURL::fetchListCount( $countParameters );

$viewParameters = array( 'offset' => $offset, 'limit'  => $limit );


$tpl = eZTemplate::factory();

$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'url_list', $list );
$tpl->setVariable( 'url_list_count', $listCount );
$tpl->setVariable( 'view_mode', $ViewMode );

$Result = array();
$Result['content'] = $tpl->fetch( "design:url/list.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/url', 'URL' ) ),
                         array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/url', 'List' ) ) );
?>
