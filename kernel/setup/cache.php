<?php
//
// Created on: <15-Apr-2003 11:25:31 bf>
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

$http = eZHTTPTool::instance();
$module = $Params['Module'];

require_once( "kernel/common/template.php" );
$ini = eZINI::instance( );
$tpl = templateInit();

$cacheList = eZCache::fetchList();

$cacheCleared = array( 'all' => false,
                       'content' => false,
                       'ini' => false,
                       'template' => false,
                       'list' => false,
                       'static' => false );

$contentCacheEnabled = $ini->variable( 'ContentSettings', 'ViewCaching' ) == 'enabled';
$iniCacheEnabled = true;
$templateCacheEnabled = $ini->variable( 'TemplateSettings', 'TemplateCache' ) == 'enabled';

$cacheEnabledList = array();
foreach ( $cacheList as $cacheItem )
{
    $cacheEnabledList[$cacheItem['id']] = $cacheItem['enabled'];
}

$cacheEnabled = array( 'all' => true,
                       'content' => $contentCacheEnabled,
                       'ini' => $iniCacheEnabled,
                       'template' => $templateCacheEnabled,
                       'list' => $cacheEnabledList );

if ( $module->isCurrentAction( 'ClearAllCache' ) )
{
    eZCache::clearAll();
    $cacheCleared['all'] = true;
}

if ( $module->isCurrentAction( 'ClearContentCache' ) )
{
    eZCache::clearByTag( 'content' );
    $cacheCleared['content'] = true;
}

if ( $module->isCurrentAction( 'ClearINICache' ) )
{
    eZCache::clearByTag( 'ini' );
    $cacheCleared['ini'] = true;
}

if ( $module->isCurrentAction( 'ClearTemplateCache' ) )
{
    eZCache::clearByTag( 'template' );
    $cacheCleared['template'] = true;
}

if ( $module->isCurrentAction( 'ClearCache' ) && $module->hasActionParameter( 'CacheList' ) && is_array( $module->actionParameter( 'CacheList' ) ) )
{
    $cacheClearList = $module->actionParameter( 'CacheList' );
    eZCache::clearByID( $cacheClearList );
    $cacheItemList = array();
    foreach ( $cacheClearList as $cacheClearItem )
    {
        foreach ( $cacheList as $cacheItem )
        {
            if ( $cacheItem['id'] == $cacheClearItem )
            {
                $cacheItemList[] = $cacheItem;
                break;
            }
        }
    }
    $cacheCleared['list'] = $cacheItemList;
}

if ( $module->isCurrentAction( 'RegenerateStaticCache' ) )
{
    $staticCache = new eZStaticCache();
    $staticCache->generateCache( true, true );
    eZStaticCache::executeActions();
    $cacheCleared['static'] = true;
}

$tpl->setVariable( "cache_cleared", $cacheCleared );
$tpl->setVariable( "cache_enabled", $cacheEnabled );
$tpl->setVariable( 'cache_list', $cacheList );


$Result = array();
$Result['content'] = $tpl->fetch( "design:setup/cache.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::translate( 'kernel/setup', 'Cache admin' ) ) );

?>
