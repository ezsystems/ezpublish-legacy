<?php
//
// Created on: <08-Aug-2003 11:27:10 bf>
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

        foreach ( array_keys( $sourceArray ) as $keyID )
        {
            unset( $alias );
            $alias = eZURLAlias::fetch( $keyID );

            if ( $alias )
            {
                $alias->setAttribute( 'source_url', $sourceArray[$keyID] );
                $alias->setAttribute( 'destination_url', $destArray[$keyID] );
                $alias->store();
            }
        }
    }
}

if ( $module->isCurrentAction( 'NewURLAlias' ) )
{
    if ( $http->hasPostVariable( 'NewURLAliasSouce' ) and
         $http->hasPostVariable( 'NewURLAliasDestination' ) )
    {
        $source = $http->postVariable( 'NewURLAliasSouce' );
        $destination = $http->postVariable( 'NewURLAliasDestination' );
        $alias = new eZURLAlias( array() );
        $alias->setAttribute( 'source_url', $source );
        $alias->setAttribute( 'destination_url', $destination );
        $alias->setAttribute( 'is_internal', false );
        $alias->store();
    }
}

$aliasList =& eZURLAlias::fetchByOffset( $Offset, $limit, true );
$aliasCount =& eZURLAlias::totalCount();

$tpl->setVariable( 'alias_limit', $limit );
$tpl->setVariable( 'alias_list', $aliasList );
$tpl->setVariable( 'alias_count', $aliasCount );
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['content'] =& $tpl->fetch( 'design:content/urltranslator.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/content', 'URL translator' ),
                                'url' => false ) );

?>

