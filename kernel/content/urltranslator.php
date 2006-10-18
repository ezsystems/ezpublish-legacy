<?php
//
// Created on: <08-Aug-2003 11:27:10 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
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

/*! \file urltranslator.php
*/
include_once( 'kernel/common/template.php' );
include_once( 'kernel/classes/ezcontentobjecttreenode.php' );
include_once( 'kernel/classes/ezurlalias.php' );

$module =& $Params['Module'];
$http =& eZHTTPTool::instance();

$Offset = $Params['Offset'];
$viewParameters = array( 'offset' => $Offset );

$tpl =& templateInit();
$limit = 20;

if ( $module->isCurrentAction( 'StoreURLAlias' ) )
{
    if ( $http->hasPostVariable( 'URLAliasSourceValue' ) and
         $http->hasPostVariable( 'URLAliasDestinationValue' ) )
    {
        $sourceArray = $http->postVariable( 'URLAliasSourceValue' );
        $destArray = $http->postVariable( 'URLAliasDestinationValue' );

        $requireExpiration = false;
        foreach ( array_keys( $sourceArray ) as $keyID )
        {
            unset( $alias );
            $alias = eZURLAlias::fetch( $keyID );

            if ( $alias )
            {
                if ( isset( $sourceArray[$keyID] ) )
                {
                    if ( $alias->attribute( 'source_url' ) !=  $sourceArray[$keyID] )
                        $alias->setAttribute( 'source_url', $sourceArray[$keyID] );
                }
                if ( isset( $destArray[$keyID] ) )
                {
                    if ( $alias->attribute( 'destination_url' ) != $destArray[$keyID] )
                        $alias->setAttribute( 'destination_url', $destArray[$keyID] );
                }
                if ( $alias->hasDirtyData() and
                     $alias->attribute( 'is_wildcard' ) != EZ_URLALIAS_WILDCARD_TYPE_NONE )
                    $requireExpiration = true;
                $alias->sync();
            }
        }
        if ( $requireExpiration )
            eZURLAlias::expireWildcards();
    }
}

if ( $module->isCurrentAction( 'RemoveURLAlias' ) )
{
    if ( $http->hasPostVariable( 'URLAliasSelection' ) )
    {
        $aliasSelection = $http->postVariable( 'URLAliasSelection' );

        $requireExpiration = false;
        foreach ( $aliasSelection  as $keyID )
        {
            unset( $alias );
            $alias = eZURLAlias::fetch( $keyID );

            if ( $alias )
            {
                $alias->cleanup();
                $requireExpiration = true;
            }
        }
        if ( $requireExpiration )
            eZURLAlias::expireWildcards();
    }
}

$translationInfo = false;

if ( $module->isCurrentAction( 'NewURLAlias' ) )
{
    if ( $http->hasPostVariable( 'NewURLAliasSource' ) and
         $http->hasPostVariable( 'NewURLAliasDestination' ) )
    {
        $source = $http->postVariable( 'NewURLAliasSource' );
        $destination = $http->postVariable( 'NewURLAliasDestination' );

        $translationInfo = array( 'error' => false,
                                  'source' => $source,
                                  'destination' => $destination );

        $alias = eZURLAlias::create( $source, $destination, false );
        $alias->store();
    }
}

$forwardInfo = false;

if ( $module->isCurrentAction( 'NewForwardURLAlias' ) )
{
    if ( $http->hasPostVariable( 'NewForwardURLAliasSource' ) and
         $http->hasPostVariable( 'NewForwardURLAliasDestination' ) )
    {
        $source = $http->postVariable( 'NewForwardURLAliasSource' );
        $destination = $http->postVariable( 'NewForwardURLAliasDestination' );

        $forwardInfo = array( 'error' => false,
                              'source' => $source,
                              'destination' => $destination );

        $existingAlias = eZURLAlias::fetchBySourceURL( $destination );
        if ( !$existingAlias )
        {
            $existingAlias = eZURLAlias::fetchByDestinationURL( $destination );
        }
        if ( $existingAlias )
        {
            $alias =& $existingAlias->createForForwarding( $source );
            $alias->setAttribute( 'is_internal', false );
            $alias->store();
        }
        else
        {
            $forwardInfo['error'] = true;
        }
    }
}

$wildcardInfo = false;

if ( $module->isCurrentAction( 'NewWildcardURLAlias' ) )
{
    if ( $http->hasPostVariable( 'NewWildcardURLAliasSource' ) and
         $http->hasPostVariable( 'NewWildcardURLAliasDestination' ) )
    {
        $source = $http->postVariable( 'NewWildcardURLAliasSource' );
        $destination = $http->postVariable( 'NewWildcardURLAliasDestination' );
        $isForwarding = false;
        if ( $http->hasPostVariable( 'NewWildcardURLAliasIsForwarding' ) )
            $isForwarding = true;

        $wildcardInfo = array( 'error' => false,
                               'source' => $source,
                               'destination' => $destination );

        $alias = eZURLAlias::create( $source, $destination, false, false, $isForwarding ? EZ_URLALIAS_WILDCARD_TYPE_FORWARD : EZ_URLALIAS_WILDCARD_TYPE_DIRECT );
        $alias->store();
        eZURLAlias::expireWildcards();
    }
}

$aliasList = eZURLAlias::fetchByOffset( $Offset, $limit, true );
$aliasCount =& eZURLAlias::totalCount();

$tpl->setVariable( 'alias_limit', $limit );
$tpl->setVariable( 'alias_list', $aliasList );
$tpl->setVariable( 'alias_count', $aliasCount );
$tpl->setVariable( 'translation_info', $translationInfo );
$tpl->setVariable( 'forward_info', $forwardInfo );
$tpl->setVariable( 'wildcard_info', $wildcardInfo );
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:content/urltranslator.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'URL translator' ),
                                'url' => false ) );

?>
