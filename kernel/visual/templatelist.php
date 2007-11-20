<?php
//
// Created on: <07-May-2003 15:37:09 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
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

$offset = $Params['Offset'];

$doFiltration = false;
$filterString = '';

if ( !is_numeric( $offset ) )
    $offset = 0;

if ( $http->hasVariable( 'filterString' ) )
{
    $filterString = $http->variable('filterString');
    if ( ( strlen( trim( $filterString ) ) > 0 ) )
        $doFiltration = true;
}

require_once( "kernel/common/template.php" );
//include_once( "kernel/common/eztemplatedesignresource.php" );
//include_once( 'lib/ezutils/classes/ezhttptool.php' );

$ini = eZINI::instance();
$tpl = templateInit();

$siteAccess = $http->sessionVariable( 'eZTemplateAdminCurrentSiteAccess' );

$overrideArray = eZTemplateDesignResource::overrideArray( $siteAccess );

$mostUsedOverrideArray = array();
$filteredOverrideArray = array();
$mostUsedMatchArray = array( 'node/view/', 'content/view/embed', 'pagelayout.tpl', 'search.tpl', 'basket' );
foreach ( array_keys( $overrideArray ) as $overrideKey )
{
    foreach ( $mostUsedMatchArray as $mostUsedMatch )
    {
        if ( strpos( $overrideArray[$overrideKey]['template'], $mostUsedMatch ) !== false )
        {
            $mostUsedOverrideArray[$overrideKey] = $overrideArray[$overrideKey];
        }
    }
    if ( $doFiltration ) {
        if ( strpos( $overrideArray[$overrideKey]['template'], $filterString ) !== false )
        {
            $filteredOverrideArray[$overrideKey] = $overrideArray[$overrideKey];
        }
    }
}

$tpl->setVariable( 'filterString', $filterString );

if ( $doFiltration )
{
    $tpl->setVariable( 'template_array', $filteredOverrideArray );
    $tpl->setVariable( 'template_count', count( $filteredOverrideArray ) );
}
else
{
    $tpl->setVariable( 'template_array', $overrideArray );
    $tpl->setVariable( 'template_count', count( $overrideArray ) );
}

$tpl->setVariable( 'most_used_template_array', $mostUsedOverrideArray );
$viewParameters = array( 'offset' => $offset );
$tpl->setVariable( 'view_parameters', $viewParameters );

$Result = array();
$Result['content'] = $tpl->fetch( "design:visual/templatelist.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/design', 'Template list' ) ) );

?>
