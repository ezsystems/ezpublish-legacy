<?php
/**
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 * @package kernel
 */

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


$ini = eZINI::instance();
$tpl = eZTemplate::factory();

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
                                'text' => ezpI18n::tr( 'kernel/design', 'Template list' ) ) );

?>
