<?php
//
// Created on: <08-Aug-2003 11:27:10 bf>
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

        $alias =& eZURLAlias::create( $source, $destination, false );
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

        $existingAlias =& eZURLAlias::fetchBySourceURL( $destination );
        if ( !$existingAlias )
        {
            $existingAlias =& eZURLAlias::fetchByDestinationURL( $destination );
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

        $alias =& eZURLAlias::create( $source, $destination, false, false, $isForwarding ? EZ_URLALIAS_WILDCARD_TYPE_FORWARD : EZ_URLALIAS_WILDCARD_TYPE_DIRECT );
        $alias->store();
        eZURLAlias::expireWildcards();
    }
}

$aliasList =& eZURLAlias::fetchByOffset( $Offset, $limit, true );
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
